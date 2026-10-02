"""Every name in the reference, grouped by its anchors, reference/<group>/<resource>.

    names.py list [<name>...]     the names with their status, all groups or the ones named
    names.py show <name>...       a resource's work order: operations, query, field tables, models used
    names.py example <path> <VERB> request|response [<n>]   an example body, as fixture JSON

A name is a group (`reference/purchase`, `reference/purchase/**`), a resource
(`reference/purchase/purchase-order`), an API path (`purchase/order`), a model (`PurchaseOrderLineModel`)
or a class (`PurchaseOrderLineData`, `GetPurchaseOrder`).
"""
import collections, json, os, pathlib, re, sys
sys.path.insert(0, str(pathlib.Path(__file__).resolve().parent))
import blueprint as bp

# CIN7_SRC points the status at another checkout's src/, such as a worktree of the base branch.
SRC = pathlib.Path(os.environ.get('CIN7_SRC') or bp.REPO / 'src')

# The path named after its model, by the maintainer's decision: its folders, classes and accessor.
PATH_NAMES = {'moneyOperation': 'MoneyTask'}

# Operations whose URI is a copy-paste from another section: follow the heading's path.
URI_FROM_HEADING = {('crm/taskcategory', 'crm/task'), ('crm/workflow', 'crm/task')}

# Links that misspell their model's anchor.
ANCHOR_TYPOS = {'ProductDealDiscountsModel': 'ProductDealDiscountModel', 'ShipZoneConditionsModel': 'ShipZoneConditionModel'}

# Request names where one path is documented twice: named after the resource's title.
NAMED_BY_TITLE = {'production/productionBOM'}

# Field tables named apart from their anchor or heading, keyed by (owner, heading): a wrong or
# generic heading, a plural heading for one record, a keyed envelope (plural), a trait or an enum.
# The owner is the resource's path, or its anchor where two resources share a path.
TABLES = {
    ('ref/account/bank', 'Available Fields for Bank Accounts'): ['BankAccountData'],
    ('ref/account', 'Available Fields for Chart of Accounts'): ['AccountData'],
    ('ref/customer/credits', 'Available fields for Customer Credits'): ['CustomerCreditData'],
    ('ref/fixedassettype', 'Available Fields for Fixed Asset Types'): ['FixedAssetTypeData'],
    ('me', 'Available Fields for ME'): ['MeData'],
    ('moneyOperation', 'Available fields for Money Task List'): ['MoneyTaskData'],
    ('bankTransfer', 'Available fields for Money Task List'): ['BankTransferData'],
    ('product/attachments', 'Available fields for POST Methods'): ['ProductAttachmentPostData'],
    ('productFamily/attachments', 'Available fields for POST Methods'): ['ProductFamilyAttachmentPostData'],
    ('ref/markupprices', 'Markup Prices Model'): ['MarkupPricesData'],
    ('reference/production/product-family-production-bom', 'Available Fields for Production BOM Operation'): ['ProductFamilyProductionBomOperationData'],
    ('production/orderList', 'Available Fields for Production Order List Item'): ['ProductionOrderListData'],
    ('purchase/stock', 'Available Fields for Purchase Stock Received'): ['PurchaseStockData'],
    ('advanced-purchase', 'Purchase POST/PUT Attributes'): ['AdvancedPurchasePostData', 'AdvancedPurchasePutData'],
    ('purchase/payment', 'Available Fields for Purchase Payments'): ['PurchasePaymentData'],
    ('purchase/attachment', 'Available fields for Purchase Attachments'): ['PurchaseAttachmentsData'],
    ('purchase/attachment', 'Available fields for POST Methods'): ['PurchaseAttachmentPostData'],
    ('advanced-purchase/invoice', 'Available Fields for Purchase Invoice'): ['AdvancedPurchaseInvoicesData'],
    ('advanced-purchase/creditnote', 'Available Fields for Purchase Credit Note'): ['AdvancedPurchaseCreditNotesData'],
    ('advanced-purchase/payment', 'Available Fields for Purchase Payments'): ['AdvancedPurchasePaymentData'],
    ('advanced-purchase/manualJournal', 'Available field for Purchase Manual Journal'): ['AdvancedPurchaseManualJournalsData'],
    ('advanced-purchase/manualJournal', 'Advanced purchase manual journal partial model'): ['AdvancedPurchasePartialManualJournalData'],
    ('sale/fulfilment', 'Available Fields for Sale Fulfilment'): ['SaleFulfilmentsData'],
    ('sale/invoice', 'Invoice Available Fields'): ['SaleInvoicesData'],
    ('sale/creditnote', 'Credit Note Available Fields'): ['SaleCreditNotesData'],
    ('sale/attachment', 'Available fields for Sale Attachments'): ['SaleAttachmentsData'],
    ('sale/attachment', 'Available fields for POST Methods'): ['SaleAttachmentPostData'],
    ('ref/supplier/deposits', 'Available fields for Supplier Deposits'): ['SupplierDepositData'],
    ('ref/templates', 'Available Fields for Templates'): ['TemplateData'],
    ('transactions', 'Available fields for Transactions'): ['TransactionData'],
    ('webhooks', 'Available fields for Webhooks'): ['WebhookData'],
    ('crm/opportunity', 'Available Fields for Opportunity Opportunity Additional Charge'): ['OpportunityAdditionalChargeData'],
    ('Other Models', 'DimensionUnitAvailableValues'): ['enum WeightUnit', 'enum DimensionUnit'],
    ('Other Models', 'PriceTierModel'): ['map: the product\'s PriceTiers, tier name to price; no class'],
    ('Other Models', 'SupplierAddressModel'): ['CustomerAddressData'],
    ('Other Models', 'SupplierContactModel'): ['CustomerContactData'],
    ('Other Models', 'ProductSupplierOptionsModel'): ['ProductSupplierOptionData'],
    ('Other Models', 'ProductSupplierOptionsIntervalModel'): ['ProductSupplierOptionIntervalData'],
}

# One decision per resource where the reference is ambiguous or wrong, or work is still open.
NOTES = {
    'reference/money-task/money-operation': 'Named after the Money Task it serves (maintainer decision): MoneyTask folders, classes and `moneyTask()`; the URL stays `moneyOperation`.',
    'reference/money-task/bank-transfer': 'The table\'s heading says "Money Task List", copied from the list: the model is the bank transfer.',
    'reference/sale/sale-order': 'Open: the table says the totals are "Not required for POST" and SaleID is required; a `SaleOrderPostData` split, as quote and manual journal have, is not done.',
    'reference/price-tiers/price-tiers': 'Other Models\' PriceTierModel is the product\'s `PriceTiers` map (no class); this `{Code, Name}` table is `PriceTierData`, so correct ProductData\'s docblock that says there is none.',
    'reference/product-markup-prices/markup-prices': 'The heading says `/ref/markupprices`; every operation URI says `/product/markupprices`. Follow the URI: `Product/MarkupPrices`, reached as `product()->markupPrices()`.',
    'reference/crm/task-category': 'Its GET URI says `/crm/task`, copied from the Task section: follow the heading, `crm/taskcategory`.',
    'reference/crm/workflow': 'Its GET URI says `/crm/task`, copied from the Task section: follow the heading, `crm/workflow`.',
    'reference/crm/start-a-workflow': 'POST with query parameters only, no body; keep the documented `EnityType` key, misspelt as it is.',
    'reference/purchase/advanced-purchase-payments': 'Its DELETE URI is `/purchase/payment`, the simple purchase\'s: `delete()` sends `DeletePurchasePayment`, no new class.',
    'reference/production/product-production-bom': '`production/productionBOM` is documented twice: requests are named by title, `GetProductProductionBom` and `GetProductFamilyProductionBom` (and POST, PUT, DELETE), both in `Production/ProductionBom/`.',
    'reference/production/product-family-production-bom': 'See product-production-bom: `GetProductFamilyProductionBom` and its POST, PUT and DELETE. Its operation adds `VariationComponents` to the product BOM operation.',
    'reference/purchase/purchase-credit-note-list': 'Its example returns the list under `PurchaseList`: use the example\'s key.',
    'reference/stock/stock-take-list': 'Its example returns the list under `StockAdjustmentList`: use the example\'s key.',
    'reference/reference-books/ship-zones': 'The DELETE query key is documented as `ShipZoneID ` with a trailing space: send `ShipZoneID`. The Conditions field links `ShipZoneConditionsModel`; the model is `ShipZoneConditionModel`.',
    'reference/webhooks/webhooks': 'The webhook payload examples describe incoming events, not this endpoint: out of scope.',
    'reference/supplier/supplier': 'Addresses and Contacts are SupplierAddressModel and SupplierContactModel, built for the customer as `CustomerAddressData` and `CustomerContactData`: reuse them, moving them to `src/Data/Other/`.',
    'reference/crm/lead': 'Contacts and Addresses are the customer\'s `CustomerContactData` and `CustomerAddressData` (SupplierContactModel and SupplierAddressModel): reuse them from `src/Data/Other/`.',
}


def existing():
    """Class short name -> path under src/, and (VERB, endpoint) -> request class."""
    classes, requests = {}, {}
    for f in SRC.rglob('*.php'):
        classes[f.stem] = 'src/' + f.relative_to(SRC).as_posix()
        if 'Requests' in f.parts:
            text = f.read_text()
            endpoint = re.search(r"function resolveEndpoint\(\): string\s*\{\s*return '([^']+)';", text)
            verb = re.search(r'Method::(GET|POST|PUT|DELETE)', text)
            verb = verb.group(1) if verb else ('GET' if 'extends ListRequest' in text else None)
            if endpoint and verb:
                requests[(verb, endpoint.group(1))] = f.stem
    return classes, requests


def request_name(verb, path, resource):
    if path in NAMED_BY_TITLE:
        return verb.capitalize() + bp.pascal(resource['title'])
    segments = path.split('/')
    if segments[0] in ('ref', 'reference'):
        segments = segments[1:]
    return verb.capitalize() + ''.join(PATH_NAMES.get(s) or bp.segment(s) for s in segments)


def folder(path):
    return '/'.join(PATH_NAMES.get(s) or bp.segment(s) for s in path.split('/'))


def accessor(path):
    return '$cin7->' + '->'.join(((PATH_NAMES.get(s) or bp.segment(s))[:1].lower() + (PATH_NAMES.get(s) or bp.segment(s))[1:]) + '()' for s in path.split('/'))


def subject(heading):
    h = bp.strip_html(heading or '').strip()
    m = re.match(r'(?i)^available fields? for (.+)$', h)
    h = m.group(1).strip() if m else h
    return re.sub(r'(?i)\s+model$', '', h).strip()


def table_classes(table, owner_path, resource_model, owner_anchor=None):
    """The classes a field table becomes."""
    if (owner_anchor, table['heading']) in TABLES:
        return TABLES[(owner_anchor, table['heading'])]
    key = (owner_path, table['anchors'][0] if owner_path == 'Other Models' and table['anchors'] else table['heading'])
    if key in TABLES:
        return TABLES[key]
    if table['anchors']:
        return [bp.pascal(re.sub(r'Model$', '', table['anchors'][0])) + 'Data']
    s = subject(table['heading'])
    if s.lower() == 'additional fields':
        return ['trait HasProductFields']
    m = re.match(r'(?i)^(.*?)\s*(post/put|post|put)(?:\s+(?:methods?|attributes|model))?$', s)
    if m:
        base = bp.pascal(m.group(1)) if m.group(1) else resource_model
        verbs = ['Post', 'Put'] if '/' in m.group(2) else [m.group(2).capitalize()]
        return [base + v + 'Data' for v in verbs]
    return [bp.pascal(s) + 'Data']


def build():
    data = bp.parse()
    classes, requests = existing()
    tables = bp.models()
    by_anchor = {a: t for t in tables for a in t['anchors']}
    lines = bp.blueprint_path().read_text(encoding='utf-8').split('\n')
    starts = sorted([r['line'] for r in data['resources']] + [g['line'] for g in data['groups']] + [len(lines) + 1])
    groups = collections.OrderedDict()
    for g in data['groups']:
        groups[g['name']] = {'name': g['name'], 'slug': bp.slug(g['name']), 'line': g['line'], 'resources': [], 'models': []}
    resources = []
    for r in data['resources']:
        anchor = f"reference/{bp.slug(r['group'])}/{bp.slug(r['title'])}"
        ops = []
        for o in r['operations']:
            path = r['path'] if (r['path'], o['path']) in URI_FROM_HEADING else o['path']
            name = request_name(o['verb'], path, r)
            ops.append({'verb': o['verb'], 'path': path, 'name': name, 'line': o['line'], 'title': o['name'],
                        'parameters': o['parameters'], 'requests': o['requests'], 'responses': o['responses'],
                        'notes': o['notes'], 'done': requests.get((o['verb'], path)) is not None})
        end = next(s for s in starts if s > r['line'])
        res = {'anchor': anchor, 'title': r['title'], 'path': r['path'], 'line': r['line'], 'group': r['group'],
               'operations': ops, 'tables': [], 'links': set(re.findall(r'(?:href="|\]\()#([A-Za-z\']+)', '\n'.join(lines[r['line']:end - 1])))}
        groups[r['group']]['resources'].append(res)
        resources.append(res)
    for t in tables:
        if t['group'] == 'Other Models':
            groups[t['group']]['models'].append(t)
            continue
        if t['resource']:
            owner = next(x for x in resources if x['path'] == t['resource'] and x['line'] == t['resource_line'])
        else:
            owner = groups[t['group']]['resources'][0]
        owner['tables'].append(t)
    for res in resources:
        model = bp.pascal(subject(res['tables'][0]['heading'])) if res['tables'] else bp.pascal(res['title'])
        for t in res['tables']:
            t['classes'] = table_classes(t, res['path'], model, res['anchor'])
        # Every model the resource's tables and text link to, and theirs in turn.
        seen, todo = set(), [f['ref'] for t in res['tables'] for f in t['fields'] if f['ref']] + sorted(res['links'])
        while todo:
            a = todo.pop().strip("'")
            a = ANCHOR_TYPOS.get(a, a)
            if a in seen or a not in by_anchor:
                continue
            seen.add(a)
            todo += [f['ref'] for f in by_anchor[a]['fields'] if f['ref']]
        res['uses'] = [by_anchor[a] for a in sorted(seen, key=lambda a: by_anchor[a]['line']) if by_anchor[a]['group'] == 'Other Models']
    for t in groups['Other Models']['models']:
        t['classes'] = table_classes(t, 'Other Models', None)
        t['used_by'] = [r['anchor'] for r in resources if t in r['uses']]
    for res in resources:
        k = sum(o['done'] for o in res['operations'])
        res['status'] = 'done' if k == len(res['operations']) else ('partial' if k else 'todo')
    return groups, resources, classes


def mark(name, classes):
    plain = name.split(' ', 1)[1] if name.startswith(('trait ', 'enum ')) else name
    return f'`{name}` ✓' if plain in classes else f'`{name}`'


def resolve(names, groups, resources):
    out = []
    for raw in names:
        n = raw.strip().rstrip('*').rstrip('/')
        found = [r for r in resources if r['anchor'] == n or r['path'] == n or r['anchor'].endswith('/' + n)]
        if not found:
            g = next((g for g in groups.values() if f"reference/{g['slug']}" == n or g['slug'] == n), None)
            found = g['resources'] if g else []
        if not found:
            found = [r for r in resources if any(n in (o['name'],) for o in r['operations'])
                     or any(n in t['classes'] or n in t['anchors'] for t in r['tables'])
                     or any(n in t['classes'] or n in t['anchors'] for t in r['uses'])]
        if not found:
            sys.exit(f'No reference name matches {raw!r}: names.py list prints them all')
        out += [r for r in found if r not in out]
    return out


def cmd_list(names):
    groups, resources, classes = build()
    chosen = resolve(names, groups, resources) if names else resources
    for g in groups.values():
        rs = [r for r in g['resources'] if r in chosen]
        if not rs:
            continue
        print(f"reference/{g['slug']}/**  {g['name']}")
        for r in rs:
            ops = ' '.join(o['verb'] + ('✓' if o['done'] else '') for o in r['operations'])
            print(f"  {r['anchor'].rsplit('/', 1)[1]:<34} {r['path']:<32} {r['status']:<8} {ops}")


def field_row(f):
    rule = f['required']
    req = {'yes': 'required', 'no': '', 'verb': 'required on ' + '/'.join(rule.get('verbs', [])),
           'without': f"required without {rule.get('field')}", 'if': f"required if {rule.get('field')} is {', '.join(rule.get('values', []))}",
           'conditional': 'Yes* (bare: optional)', 'other': 'read the notes'}[rule['kind']]
    vals = (' values: ' + ', '.join(f['values'])) if f['values'] else ''
    vals += (' write: ' + ', '.join(f['write_values'])) if f['write_values'] else ''
    return f"    L{f['line']:<6} {f['name']:<34} {f['type'][:26]:<26} {str(f['length'] or ''):<5} {f['required_raw'][:12]:<12} {req}{vals}\n{'':12}{f['notes'][:300]}" if f['notes'] else \
           f"    L{f['line']:<6} {f['name']:<34} {f['type'][:26]:<26} {str(f['length'] or ''):<5} {f['required_raw'][:12]:<12} {req}{vals}"


def cmd_show(names):
    groups, resources, classes = build()
    for r in resolve(names, groups, resources):
        print(f"{r['anchor']}  {r['title']}  [{r['status']}]")
        print(f"  docs     {bp.DOCS}{r['anchor']}")
        print(f"  path     {r['path']} (blueprint L{r['line']})  folder {folder(PATH_NAMES.get(r['path']) or r['path'])}  accessor {accessor(r['path'])}")
        if r['anchor'] in NOTES:
            print(f"  decision {NOTES[r['anchor']]}")
        print('  operations')
        for o in r['operations']:
            print(f"    {o['verb']:<6} {o['path']:<36} {o['name']}{' ✓' if o['done'] else ''}  ({o['title']}, L{o['line']})")
            for p in o['parameters']:
                print(f"           {p['name']:<28} {p['type'] or '':<10} {'required' if p['required'] else ''} {('default ' + p['default']) if p['default'] else ''} {p['description'][:120]}")
            for kind in ('requests', 'responses'):
                for i, x in enumerate(o[kind]):
                    if x['body']:
                        shape = x['body']['json']
                        keys = list(shape)[:8] if isinstance(shape, dict) else (f'list of {len(shape)}' if isinstance(shape, list) else x['body']['error'])
                        print(f"           {kind[:-1]} {i} {x['status'] or ''} L{x['body']['line']} {keys}{' fixed: ' + ', '.join(x['body']['fixes']) if x['body']['fixes'] else ''}")
        for t in r['tables']:
            print(f"  table L{t['line']} {t['heading']} -> {', '.join(mark(c, classes) for c in t['classes'])}")
            for f in t['fields']:
                print(field_row(f))
        for t in r['uses']:
            where = ', '.join(classes.get(c, '') for c in t['classes'] if c in classes)
            print(f"  uses L{t['line']} {t['anchors'][0]} -> {', '.join(mark(c, classes) for c in t['classes'])} {where}  (used by {len(t['used_by'])}: {', '.join(u.split('/', 1)[1] for u in t['used_by'][:6])})")
            if not all(c in classes for c in t['classes']):
                for f in t['fields']:
                    print(field_row(f))
        print()


def cmd_example(path, verb, kind, index=0):
    for r in bp.parse()['resources']:
        for o in r['operations']:
            if o['path'] == path and o['verb'] == verb:
                bodies = [x['body'] for x in o[kind + 's'] if x['body']]
                if len(bodies) > index:
                    body = bodies[index]
                    value, fixes = bp.lenient_json(body['text'])
                    if value is None:
                        sys.exit(f"L{body['line']}: {fixes}; fix it by hand and log the fix")
                    print(json.dumps(value, indent=4, ensure_ascii=False))
                    print(f"L{body['line']} fixes: {fixes or 'none'}", file=sys.stderr)
                    return
    sys.exit(f'No {kind} example {index} for {verb} {path}')


if __name__ == '__main__':
    args = sys.argv[1:]
    if not args or args[0] not in ('list', 'show', 'example'):
        sys.exit(__doc__)
    if args[0] == 'list':
        cmd_list(args[1:])
    elif args[0] == 'show':
        cmd_show(args[1:])
    else:
        cmd_example(args[1], args[2].upper(), args[3], int(args[4]) if len(args) > 4 else 0)
