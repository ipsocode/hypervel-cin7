"""Write TODO.md at the repository root: every reference resource by name, grouped by
reference/<group>/**, ranked by priority, each model with its class, ticked where it exists.

    todo.py [<output>]

Move a group between tiers in HIGH and LOW (every other group with work left is Medium), then run it
again: the ticks come from the code, so a run never loses one.
"""
import re, sys, pathlib
sys.path.insert(0, str(pathlib.Path(__file__).resolve().parent))
import blueprint as bp, names

# The maintainer's ranking, by reference group name.
HIGH = ['Purchase', 'Supplier', 'Me']
LOW = ['Bank Accounts', 'Carrier', 'CRM', 'Disassembly', 'Finished Goods', 'Fixed Asset Type', 'Production', 'Reference Books', 'Templates', 'Unit of Measure']
WRONG_HEADING = {'moneyOperation': 'Money Task', 'bankTransfer': 'Bank Transfer'}


def label(t, r):
    if t['anchors']:
        return t['anchors'][0]
    s = names.subject(t['heading'])
    if r['path'] in WRONG_HEADING:
        return WRONG_HEADING[r['path']]
    if s.lower() == 'additional fields':
        return 'product fields'
    m = re.match(r'(?i)^(.*?)\s*(post/put|post|put)(?:\s+(?:methods?|attributes|model))?$', s)
    if m:
        return f"{m.group(1) or r['title']} {m.group(2).upper()} body".replace('  ', ' ').strip()
    return s[:1].upper() + s[1:]


groups, resources, classes = names.build()
price_tiers_map = 'PriceTiers' in (names.SRC / 'Data/Product/AbstractProductData.php').read_text()
out = [
    '# TODO: the rest of the Cin7 V2 reference',
    '',
    'This file lives on `feature/saloon` only: delete it before `feature/saloon` merges into `main`.',
    '',
    'Every resource of the reference, by its name `reference/<group>/<resource>` (the page',
    '`https://dearinventory.docs.apiary.io/#reference/<group>/<resource>`), grouped by reference group and',
    'ranked High, Medium or Low. Pick groups, or single resources, for an issue, then ask Claude Code to',
    'follow the cin7-models skill for them by name: `docs/skills/cin7-models/SKILL.md`.',
    '',
    'Each resource gives its API path and operations, then the models its section documents, each with',
    'the class it becomes: ticked when the class exists.',
    'Each tier ends with the shared models (the reference\'s Other Models) its groups use.',
    '',
    'A resource is ticked once every operation has its request class. The ticks come from the code:',
    'refresh them with `python3 docs/skills/cin7-models/scripts/todo.py`, and move a group between',
    'tiers in that script\'s HIGH and LOW lists.',
    '',
]


def cls_built(c):
    if c.startswith('map'):
        return price_tiers_map
    return (c.split(' ', 1)[1] if c.startswith(('trait ', 'enum ')) else c) in classes


def cls_label(c):
    return 'no class: a map of tier names to prices' if c.startswith('map') else f'`{c}`'


def group_block(g):
    rs = g['resources']
    ops = sum(len(r['operations']) for r in rs)
    left = [r for r in rs if r['status'] != 'done']
    size = f"{len(rs)} resource{'s' if len(rs) != 1 else ''}, {ops} operation{'s' if ops != 1 else ''}"
    if left and len(left) < len(rs):
        size += f", {len(left)} left"
    block = [f"### `reference/{g['slug']}/**` {g['name']} ({size})", '']
    for r in rs:
        tick = 'x' if r['status'] == 'done' else ' '
        verbs = ' '.join(o['verb'] for o in r['operations'])
        block.append(f"- [{tick}] `{r['anchor'].rsplit('/', 1)[1]}` · `{r['path']}` · {verbs}")
        for t in r['tables']:
            for c in t['classes']:
                block.append(f"  - [{'x' if cls_built(c) else ' '}] {label(t, r)}: {cls_label(c)}")
        if not r['tables'] and r['uses']:
            block.append(f"  - uses {', '.join(t['anchors'][0] for t in r['uses'])}")
    return block + ['']


tiers = {'High': [], 'Medium': [], 'Low': [], 'Done': []}
for g in groups.values():
    if g['name'] == 'Other Models':
        continue
    if all(r['status'] == 'done' for r in g['resources']):
        tiers['Done'].append(g)
    elif g['name'] in HIGH:
        tiers['High'].append(g)
    elif g['name'] in LOW:
        tiers['Low'].append(g)
    else:
        tiers['Medium'].append(g)
rank = {g['name']: tier for tier, gs in tiers.items() for g in gs}
order = {'High': 0, 'Medium': 1, 'Done': 1, 'Low': 2}
other = groups['Other Models']['models']


def built(t):
    if t['classes'][0].startswith('map'):
        return price_tiers_map
    return all((c.split(' ', 1)[1] if c.startswith(('trait ', 'enum ')) else c) in classes for c in t['classes'])


def model_tier(t):
    users = [r for r in resources if t in r['uses']]
    tiers_of = [rank[r['group']] for r in users]
    if not tiers_of:
        return 'Medium'
    best = min(tiers_of, key=lambda x: order[x])
    return 'Medium' if best == 'Done' else best


def model_line(t):
    users = [r['anchor'].rsplit('/', 1)[1] for r in resources if t in r['uses']]
    used = f" · used by {', '.join(users)}" if users else ' · linked by no resource'
    return f"- [{'x' if built(t) else ' '}] {t['anchors'][0]}: {', '.join(cls_label(c) for c in t['classes'])}{used}"


intro = {
    'High': 'Purchase, supplier and me, the maintainer\'s choice, with every shared model they use.',
    'Medium': 'Every group not ranked yet: move a group up or down as issues are planned.',
    'Low': 'CRM, disassembly, finished goods and production, the maintainer\'s choice.',
    'Done': 'Every resource in these groups is in.',
}
for tier, gs in tiers.items():
    out += [f'## {tier}', '', intro[tier], '']
    for g in gs:
        out += group_block(g)
    shared = [t for t in other if model_tier(t) == tier] if tier != 'Done' else []
    if shared:
        title = 'purchase, supplier and me' if tier == 'High' else ('the groups above' if tier == 'Medium' else 'the low groups only')
        out += [f"### `reference/other-models/**` Shared models for {title}", '',
                'Each is built with the first resource here that uses it; a ticked one is built, and moves to',
                '`src/Data/Other/` when a second family uses it.' if tier == 'High' else 'Built with the first resource that uses it.', '']
        out += [model_line(t) for t in shared] + ['']
target = pathlib.Path(sys.argv[1]) if len(sys.argv) > 1 else bp.REPO / 'TODO.md'
target.write_text('\n'.join(out).rstrip() + '\n')
print('wrote', target)
