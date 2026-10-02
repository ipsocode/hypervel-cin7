<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use Hypervel\Data\Data;
use Hypervel\Saloon\Traits\Body\HasJsonBody;

/**
 * Creates or updates a record from a JSON body: an array sent verbatim, or a data object.
 *
 * A data object's body is built in four steps: its constructor has already demanded the
 * mandatory fields and defaulted the optional ones to `null`; `toArray()` is taken without the
 * nulls, so a property the caller did not set is left out; the `$omit` paths are removed; and
 * what remains is validated against the class's rules (types, required fields, and the
 * reference's lengths, GUIDs and dates), throwing a `ValidationException` before anything is
 * sent. An explicit `null`, to clear a field, goes in an array body, which is not validated.
 *
 * The caller supplies the identifier a PUT body needs; writes never carry the
 * page/limit defaults.
 *
 * A subclass lists in `$omit` the fields Cin7's reference marks read-only, response-only or
 * available for the other method; they are left out of the body. A path is dot-separated, and
 * `*` stands for every item of a list, e.g. `ProductPrices.*.ProductName`.
 *
 * @see docs/requests.md
 *
 * @template TDto
 * @extends Cin7Request<TDto>
 */
abstract class WriteRequest extends Cin7Request
{
    use HasJsonBody;

    /**
     * @var list<string>
     */
    protected array $omit = [];

    /**
     * @param array<string, mixed>|Data $body
     */
    public function __construct(protected readonly array|Data $body = [])
    {
        parent::__construct();
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        $body = $this->body instanceof Data ? self::withoutNulls($this->body->toArray()) : $this->body;

        foreach ($this->omit as $path) {
            $body = self::omitPath($body, explode('.', $path));
        }

        if ($this->body instanceof Data) {
            $this->body::validate($body);
        }

        return $body;
    }

    /**
     * Drop the null values of a data object's array, at every depth. A list keeps its items, so
     * it still encodes as a JSON array.
     *
     * @param array<array-key, mixed> $data
     * @return array<array-key, mixed>
     */
    private static function withoutNulls(array $data): array
    {
        $list = array_is_list($data);

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::withoutNulls($value);
            } elseif ($value === null && ! $list) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    /**
     * @param array<array-key, mixed> $data
     * @param list<string> $path
     * @return array<array-key, mixed>
     */
    private static function omitPath(array $data, array $path): array
    {
        $key = array_shift($path);

        if ($path === []) {
            unset($data[$key]);

            return $data;
        }

        if ($key === '*') {
            return array_map(
                static fn (mixed $item): mixed => is_array($item) ? self::omitPath($item, $path) : $item,
                $data,
            );
        }

        if (isset($data[$key]) && is_array($data[$key])) {
            $data[$key] = self::omitPath($data[$key], $path);
        }

        return $data;
    }
}
