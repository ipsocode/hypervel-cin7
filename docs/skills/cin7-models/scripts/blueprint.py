"""Read the Cin7 Core API Blueprint: groups, resources, operations, field tables and examples.

The blueprint is the reference's source, downloaded once per session:

    curl -fsSL https://jsapi.apiary.io/apis/dearinventory.apib -o "$CIN7_BLUEPRINT"

CIN7_BLUEPRINT names the file; unset, it is dearinventory.apib in the system's temporary directory.
"""
import hashlib, json, os, pathlib, re, sys, tempfile
from functools import lru_cache

REPO = next(p for p in pathlib.Path(__file__).resolve().parents if (p / 'composer.json').is_file())
SOURCE = 'https://jsapi.apiary.io/apis/dearinventory.apib'
DOCS = 'https://dearinventory.docs.apiary.io/#'

# Lowercase run-on path segments, and segments whose words the casing alone cannot find.
SEGMENTS = {
    'attributeset': 'AttributeSet', 'creditnote': 'CreditNote', 'fixedassettype': 'FixedAssetType',
    'markupprices': 'MarkupPrices', 'paymentterm': 'PaymentTerm', 'productavailability': 'ProductAvailability',
    'productionBOM': 'ProductionBom', 'stockadjustment': 'StockAdjustment', 'stockadjustmentList': 'StockAdjustmentList',
    'stocktake': 'StockTake', 'taskcategory': 'TaskCategory', 'workcenters': 'WorkCenters',
    'workflowstart': 'WorkflowStart', 'crm': 'Crm',
}


def blueprint_path():
    return pathlib.Path(os.environ.get('CIN7_BLUEPRINT') or pathlib.Path(tempfile.gettempdir()) / 'dearinventory.apib')


def sha256():
    return hashlib.sha256(blueprint_path().read_bytes()).hexdigest()


def strip_html(text):
    return re.sub(r'<[^>]+>', '', text or '').strip()


def slug(title):
    """The reference's anchor for a group or resource title: `Sale Credit Note` is `sale-credit-note`."""
    return re.sub(r'[^a-z0-9]+', '-', title.strip().lower()).strip('-')


def segment(word):
    """One URI segment as PascalCase: `advanced-purchase` AdvancedPurchase, `stockTakeList` StockTakeList."""
    if word in SEGMENTS:
        return SEGMENTS[word]
    parts = re.split(r'[-_]', word)
    return ''.join(p[:1].upper() + p[1:] for p in parts if p)


def pascal(words):
    """Words as a class name: acronyms are words, `Production BOM Note` is ProductionBomNote."""
    out = []
    for w in re.split(r'[\s/\-]+', words.strip()):
        if not w:
            continue
        w = re.sub(r'[^A-Za-z0-9]', '', w)
        if w.isupper() and len(w) > 1:
            w = w.capitalize()
        out.append(w[:1].upper() + w[1:])
    name = ''.join(out)
    # An acronym inside a run-on word: ProductionBOMOperation, IDName.
    name = re.sub(r'BOM(?=[A-Z]|$)', 'Bom', name)
    return re.sub(r'^ID(?=[A-Z])', 'Id', name)


def arg_name(wire):
    """A query key as a PHP argument: `OrderLocationID` orderLocationId, `IncludeBOM` includeBom, `ID` id."""
    words = [w.lower() for w in re.findall(r'[A-Z]+(?=[A-Z][a-z]|\d|\b)|[A-Z]?[a-z]+|[A-Z]+|\d+', wire)]
    return words[0] + ''.join(w[:1].upper() + w[1:] for w in words[1:])


def lenient_json(text):
    """Parse an example body, fixing what the reference gets wrong: (value, fixes) or (None, error)."""
    try:
        return json.loads(text), []
    except Exception:
        pass
    fixes = []
    t = text
    attempts = [
        ('comments', lambda s: re.sub(r'(?m)^\s*//[^\n]*$|(?<=[,\[{\s])//[^\n"]*$', '', s)),
        ('trailing commas', lambda s: re.sub(r',(\s*[}\]])', r'\1', s)),
        ('unquoted keys', lambda s: re.sub(r'([{,]\s*)([A-Za-z_][\w ]*?)\s*:(?!//)', lambda m: f'{m.group(1)}"{m.group(2).strip()}":', s)),
        ('single quotes', lambda s: re.sub(r"'([^'\n]*)'", r'"\1"', s)),
        ('python literals', lambda s: re.sub(r'\b(True|False|None)\b', lambda m: {'True': 'true', 'False': 'false', 'None': 'null'}[m.group(1)], s)),
        ('missing commas', lambda s: re.sub(r'("|\d|true|false|null|[}\]])(\s*\n\s*")', r'\1,\2', s)),
    ]
    for name, fix in attempts:
        fixed = fix(t)
        if fixed != t:
            t = fixed
            fixes.append(name)
            try:
                return json.loads(t), fixes
            except Exception:
                continue
    try:
        return json.loads(t), fixes
    except Exception as e:
        return None, f'unparseable after {fixes}: {e}'


@lru_cache(maxsize=1)
def parse():
    path = blueprint_path()
    if not path.is_file():
        sys.exit(f'No blueprint at {path}. Download it: curl -fsSL {SOURCE} -o {path}')
    lines = path.read_text(encoding='utf-8').split('\n')
    n = len(lines)
    groups, resources, tables, anchors = [], [], [], {}
    group = resource = operation = None
    heading = None
    pending_anchors = []

    def block_at(i):
        """The indented body after the `+ Body` line at index i: (text, end index)."""
        j = i + 1
        body = []
        while j < n:
            l = lines[j]
            if l.startswith('#') or re.match(r'^\s{0,4}\+ ', l) or re.match(r'^\s{4,}\+ (Body|Headers|Schema)\b', l):
                break
            body.append(l)
            j += 1
        nonempty = [l for l in body if l.strip()]
        indent = min((len(l) - len(l.lstrip(' ')) for l in nonempty), default=0)
        return '\n'.join(l[indent:] for l in body).strip(), j

    i = 0
    while i < n:
        l = lines[i]
        ln = i + 1
        pending_anchors += re.findall(r'<a name\s*=\s*"([^"]+)"', l)
        m = re.match(r'^# Group (.+)$', l)
        if m:
            group = {'name': m.group(1).strip(), 'line': ln}
            groups.append(group)
            resource = operation = None
            heading = (ln, strip_html(l.lstrip('# ')))
            i += 1
            continue
        m = re.match(r'^## (.+?)\s*\[(/[^\]]*)\]\s*$', l)
        if m:
            resource = {'title': strip_html(m.group(1)), 'path': m.group(2).split('?')[0].strip().strip('/'),
                        'line': ln, 'group': group['name'] if group else None, 'operations': [], 'notes': []}
            resources.append(resource)
            operation = None
            heading = (ln, resource['title'])
            i += 1
            continue
        m = re.match(r'^### (.+?)\s*\[(GET|POST|PUT|DELETE|PATCH)\b\s*([^\]]*)\]\s*$', l)
        if m and resource is not None:
            uri = m.group(3).strip()
            operation = {'name': m.group(1).strip(), 'verb': m.group(2), 'uri': uri,
                         'path': (uri.split('?')[0].strip().strip('/') or resource['path']),
                         'line': ln, 'notes': [], 'parameters': [], 'requests': [], 'responses': []}
            resource['operations'].append(operation)
            heading = (ln, operation['name'])
            i += 1
            continue
        if l.startswith('#'):
            heading = (ln, strip_html(l.lstrip('# ')))
            if l.startswith('## '):
                resource = operation = None
            i += 1
            continue
        if l.startswith('|') and i + 1 < n and re.match(r'^\|\s*:?-', lines[i + 1]):
            header = [c.strip() for c in l.strip().strip('|').split('|')]
            rows = []
            j = i + 2
            # Most rows start with a pipe; the CRM tables' rows do not.
            while j < n and lines[j].strip() and '|' in lines[j] and not lines[j].startswith('#'):
                cells = [c.strip() for c in lines[j].strip().removeprefix('|').removesuffix('|').split('|')]
                rows.append({'line': j + 1, 'cells': cells})
                j += 1
            table = {'id': len(tables), 'line': ln, 'header': header, 'heading': heading[1] if heading else None,
                     'anchors': [a.strip("'") for a in pending_anchors], 'group': group['name'] if group else None,
                     'resource': resource['path'] if resource else None,
                     'resource_line': resource['line'] if resource else None,
                     'operation': f"{operation['verb']} {operation['path']}" if operation else None, 'rows': rows}
            tables.append(table)
            for a in table['anchors']:
                anchors[a] = table['id']
            pending_anchors = []
            i = j
            continue
        if operation is not None:
            if re.match(r'^\+ Parameters', l):
                j = i + 1
                current = None
                while j < n and (lines[j].strip() == '' or re.match(r'^\s{4,}', lines[j])):
                    pm = re.match(r'^\s{4}\+ (.+?)\s*(?:\(([^)]*)\))?\s*(?:\.\.\.\s*(.*))?$', lines[j])
                    dm = re.match(r'^\s{8,}\+ Default:\s*(.*)$', lines[j])
                    if pm and not lines[j].startswith('        '):
                        spec = [s.strip().lower() for s in (pm.group(2) or '').split(',') if s.strip()]
                        current = {'name': pm.group(1).strip().rstrip(':'), 'required': 'required' in spec,
                                   'type': next((s for s in spec if s not in ('required', 'optional')), None),
                                   'description': (pm.group(3) or '').strip(), 'default': None, 'line': j + 1}
                        operation['parameters'].append(current)
                    elif dm and current is not None:
                        current['default'] = dm.group(1).strip()
                    j += 1
                i = j
                continue
            m = re.match(r'^\+ (Request|Response)\s*(\d{3})?', l)
            if m:
                j = i + 1
                body = None
                while j < n and not re.match(r'^\+ |^#', lines[j]):
                    if re.match(r'^\s+\+ Body', lines[j]):
                        text, j2 = block_at(j)
                        value, fixes = lenient_json(text) if text else (None, 'empty')
                        body = {'line': j + 2, 'text': text, 'json': value,
                                'fixes': fixes if isinstance(fixes, list) else [],
                                'error': fixes if isinstance(fixes, str) else None}
                        j = j2
                        continue
                    j += 1
                entry = {'line': ln, 'status': int(m.group(2)) if m.group(2) else None, 'body': body}
                operation['requests' if m.group(1) == 'Request' else 'responses'].append(entry)
                i = j
                continue
            if l.startswith('+ '):
                operation['notes'].append({'line': ln, 'text': l[2:].strip()})
        elif resource is not None and l.startswith('+ '):
            resource['notes'].append({'line': ln, 'text': l[2:].strip()})
        i += 1
    return {'groups': groups, 'resources': resources, 'tables': tables, 'anchors': anchors}


VALUE_INTRO = re.compile(r'(possible values?(?: are| is)?|available values?(?: are| is)?|valid values?(?: are| is)?|values are|accepted values?(?: are)?|can be one of|one of)\s*:?', re.I)


def value_list(notes):
    """(values, write subset) from a Notes cell: `Available values are ...`, `For POST available values are ...`."""
    intro = VALUE_INTRO.search(notes)
    if not intro:
        return [], []
    rest = notes[intro.end():]
    sub = re.search(r'For\s+`?(POST|PUT)`?(?:\s*/\s*`?(POST|PUT)`?)?\s+(?:method\s+)?available values are\s*(.*)$', rest, re.I)
    main = rest[:sub.start()] if sub else rest
    main = re.split(r'(?<=[`>])\s*\.\s+(?=[A-Z])', main)[0]
    return re.findall(r'`([^`]+)`', main), (re.findall(r'`([^`]+)`', sub.group(3)) if sub else [])


def required_rule(raw, notes):
    """The Required column, translated: yes, no, verb (POST/PUT only), without (another field
    stands in), if (another field's value), conditional (a bare Yes*) or other (read the notes)."""
    r = (raw or '').strip().lower()
    n = strip_html(notes or '')
    if r == 'yes':
        return {'kind': 'yes'}
    if not r or r == 'no':
        return {'kind': 'no'}
    if r.startswith('yes when updating'):
        return {'kind': 'verb', 'verbs': ['PUT']}
    m = re.search(r'required for\s+`?(POST|PUT|DELETE)`?(?:\s*(?:,|and|/)\s*`?(POST|PUT|DELETE)`?)?', n, re.I)
    if m:
        return {'kind': 'verb', 'verbs': [v.upper() for v in m.groups() if v]}
    m = re.search(r'required (?:if|when) `?(\w+)`? (?:is )?(?:empty|not (?:set|specified|provided))', n, re.I)
    if m:
        return {'kind': 'without', 'field': m.group(1)}
    m = re.search(r'required (?:if|when) `?(\w+)`?\s*(?:is|=|==)\s*(.+?)(?:\.|$)', n, re.I)
    if m:
        return {'kind': 'if', 'field': m.group(1), 'values': re.findall(r'`([^`]+)`', m.group(2)) or [m.group(2).strip()]}
    if r in ('yes*', 'yes *'):
        return {'kind': 'conditional'}
    return {'kind': 'other'}


@lru_cache(maxsize=1)
def models():
    """Every field table (a `Property` header with Type, Length, Required and Notes), normalised."""
    out = []
    for t in parse()['tables']:
        if not t['header'] or t['header'][0].strip('` ').lower() != 'property' or len(t['header']) < 4:
            continue
        fields = []
        for r in t['rows']:
            c = r['cells'] + [''] * 5
            name = strip_html(c[0]).strip('` ').strip()
            if not name or set(name) <= set(':- '):
                continue
            ref = re.search(r'href="#([^"]+)"', c[1])
            values, subset = value_list(c[4])
            fields.append({
                'name': name, 'line': r['line'], 'type': strip_html(c[1]).strip(), 'ref': ref.group(1) if ref else None,
                'length': int(c[2].strip()) if c[2].strip().isdigit() else None,
                'required_raw': strip_html(c[3]).strip(), 'required': required_rule(c[3], c[4]),
                'notes': strip_html(c[4]), 'values': values, 'write_values': subset,
            })
        out.append({**{k: t[k] for k in ('id', 'line', 'heading', 'anchors', 'group', 'resource', 'resource_line')}, 'fields': fields})
    return out
