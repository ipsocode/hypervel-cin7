"""Write request classes in this package's three shapes from a JSON list: requests.py <spec.json>.

Each entry names its shape, class, endpoint, the data class it returns (relative to
Ipsocode\\Cin7\\Data) and a one-sentence summary for the docblock:

    {"shape": "list", "class": "GetPurchaseList", "endpoint": "purchaseList", "list_key": "PurchaseList",
     "dto": "PurchaseList\\\\PurchaseListData", "doc": "`GET purchaseList`, the purchases.",
     "params": [["Search", "string"], ["UpdatedSince", "date"], ["Status", "enum:PurchaseStatus"]]}
    {"shape": "keyed", "class": "GetPurchaseOrder", "verb": "GET", "endpoint": "purchase/order",
     "dto": "Purchase\\\\Order\\\\PurchaseOrderData", "doc": "...",
     "params": [["TaskID", "string", true], ["CombineAdditionalCharges", "bool"]]}
    {"shape": "write", "class": "PostPurchaseOrder", "verb": "POST", "endpoint": "purchase/order",
     "dto": "Purchase\\\\Order\\\\PurchaseOrderData", "doc": "...", "omit": ["TotalBeforeTax"]}

A parameter is [wire key, type, required?]: string, bool, int, float, date (DateTimeInterface|string)
or enum:<Name>. Required parameters come first. "dto_path" reads a keyed envelope's item.
"""
import json, pathlib, sys, textwrap
sys.path.insert(0, str(pathlib.Path(__file__).resolve().parent))
import blueprint as bp
import names

ROOT = bp.REPO / 'src/Requests'
METHOD = 'Hypervel\\Saloon\\Enums\\Method'


def doc(text):
    return '\n'.join(' * ' + l for l in textwrap.wrap(text, 96, break_on_hyphens=False, break_long_words=False))


def php_type(t):
    if t == 'date':
        return 'DateTimeInterface|string'
    return t[5:] if t.startswith('enum:') else t


def optional(t):
    return f'{t}|null' if '|' in t else f'?{t}'


def imports(params, base, dto_fq, extra=()):
    out = {f'Ipsocode\\Cin7\\Data\\{dto_fq}', f'Ipsocode\\Cin7\\Requests\\{base}', 'Hypervel\\Saloon\\Http\\Response', *extra}
    for p in params:
        if p[1] == 'date':
            out.add('DateTimeInterface')
        if p[1].startswith('enum:'):
            out.add(f'Ipsocode\\Cin7\\Enums\\{p[1][5:]}')
    return '\n'.join(f'use {u};' for u in sorted(out))


def keyed(e):
    params = e.get('params', [])
    dto = e['dto'].rsplit('\\', 1)[1]
    ctor = '\n'.join('        protected readonly ' + (php_type(p[1]) if len(p) > 2 and p[2] else optional(php_type(p[1]))) + ' $' + bp.arg_name(p[0]) + ('' if len(p) > 2 and p[2] else ' = null') + ',' for p in params)
    query = '\n'.join(f"            '{p[0]}' => $this->{bp.arg_name(p[0])}," for p in params)
    json_call = f"$response->json('{e['dto_path']}')" if e.get('dto_path') else '$response->json()'
    uses = imports(params, 'Cin7Request', e['dto'], [METHOD])
    return f'''{uses}

/**
{doc(e['doc'])}
 *
 * @extends Cin7Request<{dto}>
 */
final class {e['class']} extends Cin7Request
{{
    protected Method $method = Method::{e['verb']};

    public function __construct(
{ctor}
    ) {{
        parent::__construct();
    }}

    public function resolveEndpoint(): string
    {{
        return '{e['endpoint']}';
    }}

    /**
     * @return array<string, mixed>
     */
    protected function defaultQuery(): array
    {{
        return $this->queryValues([
{query}
        ]);
    }}

    public function createDtoFromResponse(Response $response): {dto}
    {{
        return {dto}::from({json_call})->setResponse($response);
    }}
}}
'''


def write(e):
    dto = e['dto'].rsplit('\\', 1)[1]
    omit = ''
    if e.get('omit'):
        listed = ', '.join(f"'{o}'" for o in e['omit'])
        omit = f'''
    /**
     * @var list<string>
     */
    protected array $omit = [{listed}];
'''
    json_call = f"$response->json('{e['dto_path']}')" if e.get('dto_path') else '$response->json()'
    uses = imports([], 'WriteRequest', e['dto'], [METHOD])
    return f'''{uses}

/**
{doc(e['doc'])}
 *
 * @extends WriteRequest<{dto}>
 */
final class {e['class']} extends WriteRequest
{{
    protected Method $method = Method::{e['verb']};
{omit}
    public function resolveEndpoint(): string
    {{
        return '{e['endpoint']}';
    }}

    public function createDtoFromResponse(Response $response): {dto}
    {{
        return {dto}::from({json_call})->setResponse($response);
    }}
}}
'''


def listing(e):
    params = e.get('params', [])
    dto = e['dto'].rsplit('\\', 1)[1]
    ctor = '\n'.join(f"        protected readonly {optional(php_type(p[1]))} ${bp.arg_name(p[0])} = null," for p in params)
    filters = '\n'.join(f"            '{p[0]}' => $this->{bp.arg_name(p[0])}," for p in params)
    uses = imports(params, 'ListRequest', e['dto'])
    return f'''{uses}

/**
{doc(e['doc'])}
 *
 * @extends ListRequest<list<{dto}>>
 */
final class {e['class']} extends ListRequest
{{
    protected string $listKey = '{e['list_key']}';

    public function __construct(
        ?int $page = null,
        ?int $limit = null,
{ctor}
    ) {{
        parent::__construct($page, $limit);
    }}

    public function resolveEndpoint(): string
    {{
        return '{e['endpoint']}';
    }}

    /**
     * @return array<string, mixed>
     */
    protected function filters(): array
    {{
        return [
{filters}
        ];
    }}

    /**
     * @return list<{dto}>
     */
    public function createDtoFromResponse(Response $response): array
    {{
        return array_map(
            static fn (array $item): {dto} => {dto}::from($item)->setResponse($response),
            array_values($this->mapPaginatedResponseItems($response)),
        );
    }}
}}
'''


if __name__ == '__main__':
    if len(sys.argv) != 2:
        sys.exit(__doc__)
    for e in json.loads(pathlib.Path(sys.argv[1]).read_text()):
        folder = names.folder(e['endpoint'])
        namespace = 'Ipsocode\\Cin7\\Requests\\' + folder.replace('/', '\\')
        body = {'keyed': keyed, 'write': write, 'list': listing}[e['shape']](e)
        target = ROOT / folder / f"{e['class']}.php"
        target.parent.mkdir(parents=True, exist_ok=True)
        target.write_text('<?php\n\ndeclare(strict_types=1);\n\nnamespace ' + namespace + ';\n\n' + body)
        print('wrote', target.relative_to(bp.REPO))
