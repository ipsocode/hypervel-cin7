"""Write a string-backed enum to src/Enums: enums.py <Name> "<summary sentence>" <value>...

The cases are the values in PascalCase and the values the wire strings verbatim:
`enums.py PurchaseStatus "The status of a purchase." DRAFT AUTHORISED` writes
`case Draft = 'DRAFT';` and `case Authorised = 'AUTHORISED';`.
"""
import re, sys, pathlib, textwrap
sys.path.insert(0, str(pathlib.Path(__file__).resolve().parent))
import blueprint as bp


def case_name(value):
    return ''.join(w[:1].upper() + (w[1:] if w != w.upper() and w != w.lower() else w[1:].lower()) for w in re.split(r'[^A-Za-z0-9]+', value) if w)


def write(name, summary, values):
    cases = '\n'.join(f"    case {case_name(v)} = '{v.replace(chr(39), chr(92) + chr(39))}';" for v in values)
    doc = '\n'.join(' * ' + l for l in textwrap.wrap(summary, 96, break_on_hyphens=False))
    target = bp.REPO / 'src/Enums' / f'{name}.php'
    target.write_text(f"""<?php

declare(strict_types=1);

namespace Ipsocode\\Cin7\\Enums;

/**
{doc}
 *
 * @see docs/data.md
 */
enum {name}: string
{{
{cases}
}}
""")
    return target


if __name__ == '__main__':
    if len(sys.argv) < 4:
        sys.exit(__doc__)
    print('wrote', write(sys.argv[1], sys.argv[2], sys.argv[3:]).relative_to(bp.REPO))
