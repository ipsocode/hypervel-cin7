<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests;

use BackedEnum;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Hypervel\Data\Data;
use Hypervel\Saloon\Contracts\DataObjects\WithResponse;
use Hypervel\Saloon\Http\Request;
use Hypervel\Saloon\Http\Response;
use Hypervel\Saloon\Traits\Plugins\AlwaysThrowOnErrors;

/**
 * Base for every Cin7 request. The throttling retry policy is the connector's.
 *
 * @see src/docs/requests.md
 *
 * @template TDto
 * @extends Request<TDto>
 */
abstract class Cin7Request extends Request
{
    use AlwaysThrowOnErrors;

    /**
     * The data class of the record the response holds, which `createDtoFromResponse()` builds
     * `dto()` from. Null for a response `json()` reads (`{Success}`, `{Errors}`), and for a class
     * that overrides the method.
     *
     * @var null|class-string<Data&WithResponse>
     */
    protected ?string $dto = null;

    /**
     * Where the record sits in the response, e.g. `CustomerList.0`; null for the whole body.
     */
    protected ?string $dtoPath = null;

    /**
     * @param Response<mixed> $response
     * @return null|TDto
     */
    public function createDtoFromResponse(Response $response): mixed
    {
        if ($this->dto === null) {
            return null;
        }

        /** @var TDto $dto The class a subclass names in `$dto` is the one its `@extends` names. */
        $dto = $this->dto::from($response->json($this->dtoPath))->setResponse($response);

        return $dto;
    }

    /**
     * Map query values the way Cin7 expects them on the wire, leaving out the `null` ones: a
     * boolean as `true`/`false`, not `1`/empty, an enum as its value, and a date as ISO 8601 in
     * UTC with milliseconds, `yyyy-MM-ddTHH:mm:ss.fff`.
     *
     * @param array<string, mixed> $values
     * @return array<string, mixed>
     */
    protected function queryValues(array $values): array
    {
        return array_map(
            static fn (mixed $value): mixed => match (true) {
                is_bool($value) => $value ? 'true' : 'false',
                $value instanceof BackedEnum => $value->value,
                $value instanceof DateTimeInterface => DateTimeImmutable::createFromInterface($value)
                    ->setTimezone(new DateTimeZone('UTC'))
                    ->format('Y-m-d\TH:i:s.v'),
                default => $value,
            },
            array_filter($values, static fn (mixed $value): bool => $value !== null),
        );
    }

    /**
     * The items of a list response as data objects of this class, each holding the response.
     *
     * @template TItem of Data&WithResponse
     *
     * @param class-string<TItem> $class
     * @param Response<mixed> $response
     * @param array<array-key, mixed> $items
     * @return list<TItem>
     */
    protected function listOf(string $class, Response $response, array $items): array
    {
        return array_map(
            static fn (array $item): Data&WithResponse => $class::from($item)->setResponse($response),
            array_values($items),
        );
    }
}
