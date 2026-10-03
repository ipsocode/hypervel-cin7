"""The `Yes` fields of the reference that their data class does not keep required.

    required.py [<name>...]     the resources named (as for names.py), or every resource

For each field table of a resource, and each shared model it uses, whose class exists, it lists every
field the table marks `Yes` that the class neither requires by type (a promoted constructor property
with no default) nor marks `#[Required]`, `#[RequiredWithout]` or `#[RequiredIf]`, following the
class's parents and traits. A field the class has no property for is left out on purpose (a read-only
field) and is not listed.

A field that stays optional on purpose is one of the three cases of the cin7-models skill: a verb's own
body class that leaves it out (a partial PUT), a value Cin7 computes, or the ID of a nested item being
added. The class's docblock and docs/data.md name it in backticks and say why; the field is then `explained`.
Exit status 1 when any field is neither required nor explained.
"""
import pathlib, re, sys
sys.path.insert(0, str(pathlib.Path(__file__).resolve().parent))
import blueprint as bp
import names

RULES = ('Required', 'RequiredWithout', 'RequiredIf')


def split_top(text, sep=','):
    """Split on `sep` outside brackets, parentheses, braces and quotes."""
    parts, depth, quote, cur = [], 0, None, ''
    for ch in text:
        if quote:
            cur += ch
            quote = None if ch == quote else quote
        elif ch in '\'"':
            cur += ch
            quote = ch
        elif ch in '([{':
            depth += 1
            cur += ch
        elif ch in ')]}':
            depth -= 1
            cur += ch
        elif ch == sep and depth == 0:
            parts.append(cur)
            cur = ''
        else:
            cur += ch
    if cur.strip():
        parts.append(cur)
    return parts


def balanced(text, start, open_ch, close_ch):
    """The text between the bracket at `start` and its match."""
    depth = 0
    for i in range(start, len(text)):
        if text[i] == open_ch:
            depth += 1
        elif text[i] == close_ch:
            depth -= 1
            if depth == 0:
                return text[start + 1:i]
    return text[start + 1:]


def attributes(text):
    """The attribute names in a run of `#[...]` groups."""
    names_ = []
    i = text.find('#[')
    while i != -1:
        body = balanced(text, i + 1, '[', ']')
        names_ += [re.match(r'\s*\\?(?:[\w\\]*\\)?(\w+)', a).group(1) for a in split_top(body) if a.strip()]
        i = text.find('#[', i + 2 + len(body))
    return names_


def declaration(text):
    """A promoted parameter or declared property: (name, has default, attribute names), else None."""
    m = re.search(r'\b(?:public|protected|private)\b[^$]*\$(\w+)\s*(=.*)?$', text, re.S)
    if not m:
        return None
    return m.group(1), m.group(2) is not None, attributes(text)


def parse(path):
    """A class file: parent, traits, properties by name, and the docblock."""
    text = path.read_text()
    head = re.search(r'((?:/\*\*(?:(?!\*/).)*\*/\s*)?)(?:(?:abstract|final)\s+)?(?:class|trait)\s+(\w+)(?:\s+extends\s+(\w+))?', text, re.S)
    doc, parent = head.group(1), head.group(3)
    body = text[text.index('{', head.end()) + 1:]
    props = {}
    ctor = re.search(r'function\s+__construct\s*\(', body)
    if ctor:
        params = balanced(body, ctor.end() - 1, '(', ')')
        for p in split_top(params):
            d = declaration(p)
            if d:
                props[d[0]] = {'type': not d[1], 'rule': any(a in RULES for a in d[2])}
        body = body[:ctor.start()] + body[ctor.end() + len(params) + 1:]
    for stmt in split_top(re.sub(r'/\*.*?\*/', '', body, flags=re.S), ';'):
        d = declaration(stmt.split('function', 1)[0]) if 'function' not in stmt.split('=')[0] else None
        if d:
            props[d[0]] = {'type': False, 'rule': any(a in RULES for a in d[2])}
    traits = re.findall(r'^\s+use\s+(\w+)\s*;', body, re.M)
    return {'parent': parent, 'traits': traits, 'props': props, 'doc': doc}


def model(name, classes, cache={}):
    """A class's properties with its parents' and traits' behind them, and the docblocks along the chain."""
    if name not in classes:
        return {'props': {}, 'docs': ''}
    if name not in cache:
        info = parse(bp.REPO / classes[name])
        props, docs = {}, ''
        for base in [info['parent']] + info['traits']:
            if base:
                inherited = model(base, classes)
                props.update(inherited['props'])
                docs += inherited['docs']
        props.update(info['props'])
        cache[name] = {'props': props, 'docs': info['doc'] + docs}
    return cache[name]


OPTIONAL = r'optional|left out|leaves? (?:it )?out|omit|not (?:take|send|require)|computes?|without|partial'


def explains(docs, field):
    """Whether a docblock has a sentence that names the field and says it is optional or left out."""
    flat = re.sub(r'\s*\n\s*\*?\s*', ' ', docs)
    return any(f'`{field}`' in s and re.search(OPTIONAL, s) for s in re.split(r'(?<=[.;:])\s', flat))


def documented(cls, field):
    """Whether docs/data.md has a paragraph that names the class and the field and says it is optional."""
    page = (bp.REPO / 'docs' / 'data.md').read_text()
    for paragraph in re.split(r'\n\s*\n|\n(?=- )', page):
        flat = re.sub(r'\s+', ' ', paragraph).strip()
        if not flat.startswith('|') and f'`{cls}`' in flat and f'`{field}`' in flat and re.search(OPTIONAL, flat):
            return True
    return False


def gaps(resources, classes):
    out, seen = [], set()
    for r in resources:
        for own, t in [(True, t) for t in r['tables']] + [(False, t) for t in r['uses']]:
            # A resource's own table also describes the bodies split from its class by verb; a shared
            # model's table only its own class, since the bodies follow the resource's table.
            for cls in [c for c in classes if any(c == b or (own and re.fullmatch(re.escape(b[:-4]) + r'(?:Post|Put|PostPut)Data', c)) for b in t['classes'])]:
                if (cls, t['line']) in seen:
                    continue
                seen.add((cls, t['line']))
                m = model(cls, classes)
                for f in t['fields']:
                    if f['required']['kind'] != 'yes':
                        continue
                    p = m['props'].get(f['name'])
                    if not p or p['type'] or p['rule']:
                        continue
                    out.append((r['anchor'], t['heading'], cls, f['name'], explains(m['docs'], f['name']) and documented(cls, f['name'])))
    return out


def main(args):
    groups, resources, classes = names.build()
    chosen = names.resolve(args, groups, resources) if args else resources
    found = sorted(set(gaps(chosen, classes)))
    unexplained = 0
    for anchor, heading, cls, field, explained in found:
        state = 'explained' if explained else 'UNEXPLAINED'
        print(f"{cls:<44} {field:<26} {state}   {anchor}")
        unexplained += not explained
    print(f"\n{len(found)} Yes field(s) not kept required, {unexplained} unexplained", file=sys.stderr)
    return 1 if unexplained else 0


if __name__ == '__main__':
    sys.exit(main(sys.argv[1:]))
