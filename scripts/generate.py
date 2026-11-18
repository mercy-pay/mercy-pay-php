"""Generates src/Service/*Service.php (one class per resource, each parameter described as a
PHPDoc array shape that PhpStorm and PHPStan read) and src/Catalog.php, from the API
reference copy (tests/fixtures/openapi.json), tests/fixtures/operations.json and
tests/fixtures/catalog.json. Hand-written methods are kept in scripts/extras/<Service>.php.
Run: python3 scripts/generate.py"""
import json, os, re

root = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
spec = json.load(open(os.path.join(root, 'tests/fixtures/openapi.json')))
ops = json.load(open(os.path.join(root, 'tests/fixtures/operations.json')))
catalog = json.load(open(os.path.join(root, 'tests/fixtures/catalog.json')))
shared_params = spec['components'].get('parameters', {})

HEADER = '// Généré par scripts/generate.py depuis la référence de l\'API. Ne pas modifier à la main.'


def php_type(prop):
    if '$ref' in prop:
        return 'array<string, mixed>'
    if 'const' in prop:
        return json.dumps(prop['const']).replace('"', "'")
    t = prop.get('type', 'mixed')
    types = t if isinstance(t, list) else [t]
    out = []
    for one in types:
        if one == 'null':
            out.append('null')
        elif one == 'string':
            enum = [e for e in prop.get('enum', []) if e is not None]
            out.append('|'.join("'" + e + "'" for e in enum) if enum else 'string')
        elif one == 'integer':
            out.append('int')
        elif one == 'number':
            out.append('int|float')
        elif one == 'boolean':
            out.append('bool')
        elif one == 'array':
            item = php_type(prop.get('items', {})) if prop.get('items') else 'mixed'
            out.append(f'list<{item}>')
        elif one == 'object':
            if prop.get('properties'):
                required = set(prop.get('required', []))
                fields = ', '.join(f'{k}{"" if k in required else "?"}: {php_type(v)}' for k, v in prop['properties'].items())
                out.append('array{' + fields + '}')
            else:
                out.append('array<string, mixed>')
        else:
            out.append('mixed')
    return '|'.join(dict.fromkeys(out))


def shape(operation):
    """The params array shape: query parameters, then the JSON body."""
    fields, groups, required = [], {}, set()
    for p in operation.get('parameters', []):
        p = shared_params[p['$ref'].split('/')[-1]] if '$ref' in p else p
        if p['in'] != 'query':
            continue
        name = p['name'][:-2] if p['name'].endswith('[]') else p['name']
        group = re.match(r'^(\w+)\[(\w+)\]$', name)
        if group:
            groups.setdefault(group.group(1), []).append(f'{group.group(2)}?: {php_type(p.get("schema", {}))}')
        else:
            fields.append((name, php_type(p.get('schema', {}))))
    body = operation.get('requestBody', {}).get('content', {}).get('application/json', {}).get('schema', {})
    required = set(body.get('required', []))
    for name, prop in body.get('properties', {}).items():
        fields.append((name, php_type(prop)))
    for group, members in groups.items():
        fields.append((group, 'array{' + ', '.join(members) + '}'))
    if not fields:
        return 'array<string, mixed>', False
    parts = [f'{name}{"" if name in required else "?"}: {kind}' for name, kind in fields]
    return 'array{' + ', '.join(parts) + '}', bool(required)


by_id = {d['operationId']: (verb, path, d) for path, methods in spec['paths'].items() for verb, d in methods.items()}


def is_list(definition):
    ok = next((r for s, r in definition['responses'].items() if s.startswith('2')), {})
    schema = ok.get('content', {}).get('application/json', {}).get('schema', {})
    data = schema.get('properties', {}).get('data', {})
    return schema.get('properties', {}).get('object', {}).get('const') == 'list' or data.get('type') == 'array'


services = {}
for o in ops:
    services.setdefault(o['service'], []).append(o)

for service, items in services.items():
    lines = ['<?php', '', HEADER, '', 'declare(strict_types=1);', '', 'namespace MercyPay\\Service;', '']
    extras_path = os.path.join(root, 'scripts/extras', f'{service}Service.php')
    extras = open(extras_path).read().rstrip() + '\n' if os.path.exists(extras_path) else ''
    uses = ['MercyPay\\MercyPayObject', 'MercyPay\\Page']
    for match in re.findall(r'^use ([^;]+);', extras, re.M):
        uses.append(match)
    extras = re.sub(r'^use [^;]+;\n', '', extras, flags=re.M).strip('\n')
    lines += [f'use {u};' for u in sorted(set(uses))]
    lines += ['', f'final class {service}Service extends AbstractService', '{']
    methods = []
    for o in items:
        verb, path, definition = by_id[o['operationId']]
        params_shape, has_required = shape(definition)
        doc = ['    /**', f'     * {definition.get("summary", "")}.', '     *', f'     * {verb.upper()} {path}', '     *',
               f'     * @param  {params_shape}  $params']
        args = [f'string ${i}' for i in o['ids']]
        args.append('array $params' + ('' if has_required else ' = []'))
        php_path = "'" + re.sub(r'\{\}', lambda m, it=iter(o['ids']): "'.self::id($" + next(it) + ").'", o['path']) + "'"
        php_path = php_path.replace(".''", '')
        if is_list(definition):
            doc.append('     */')
            body = [f'    public function {o["method"]}({", ".join(args)}): Page', '    {', f'        return $this->list({php_path}, $params);', '    }']
        else:
            post = o['http'] in ('POST',)
            if post:
                doc.append('     * @param  array{idempotency_key?: string}  $options')
                args.append('array $options = []')
            doc.append('     */')
            extra = ', $options' if post else ''
            body = [f'    public function {o["method"]}({", ".join(args)}): MercyPayObject', '    {', f"        return $this->object('{o['http']}', {php_path}, $params{extra});", '    }']
        methods.append('\n'.join(doc + body))
    if extras:
        methods.append(extras)
    lines.append('\n\n'.join(methods))
    lines.append('}')
    open(os.path.join(root, 'src/Service', f'{service}Service.php'), 'w').write('\n'.join(lines) + '\n')

# The catalog as constants.
countries = catalog['countries']
cat = ['<?php', '', '// Généré par scripts/generate.py depuis le catalogue Mercy Pay. Ne pas modifier à la main.', '',
       'declare(strict_types=1);', '', 'namespace MercyPay;', '',
       '/**', ' * The open countries, their mobile money operators and the webhook event types. A new', ' * operator may appear before this list knows it: any code stays accepted.', ' */',
       'final class Catalog', '{']
cat.append('    /** @var array<string, array{name: string, currency: string, providers: array<string, string>}> */')
cat.append('    public const COUNTRIES = [')
for c in countries:
    providers = ', '.join(f"'{p['code']}' => '{p['name']}'" for p in c['providers'])
    cat.append(f"        '{c['code']}' => ['name' => '{c['name'].replace(chr(39), chr(92) + chr(39))}', 'currency' => '{c['currency']}', 'providers' => [{providers}]],")
cat.append('    ];')
cat.append('')
cat.append('    /** @var list<string> */')
cat.append('    public const EVENT_TYPES = [' + ', '.join(f"'{t}'" for t in catalog['event_types']) + '];')
cat.append('')
for c in countries:
    for p in c['providers']:
        cat.append(f"    public const {p['code']} = '{p['code']}';")
cat.append('}')
open(os.path.join(root, 'src/Catalog.php'), 'w').write('\n'.join(cat) + '\n')

print('services:', len(services), 'operations:', len(ops))
