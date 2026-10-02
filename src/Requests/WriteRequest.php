<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use Hypervel\Data\Data;
use Hypervel\Saloon\Traits\Body\HasJsonBody;

/**
 * Creates or updates a record from a JSON body: an array sent verbatim, or a data object sent
 * as its `toArray()`, which leaves out every property the caller did not set.
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
        $body = $this->body instanceof Data ? $this->body->toArray() : $this->body;

        foreach ($this->omit as $path) {
            $body = self::omitPath($body, explode('.', $path));
        }

        return $body;
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
