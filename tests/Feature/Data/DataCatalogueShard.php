<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Data;

use Hypervel\Data\Data;
use Hypervel\Data\Exceptions\CannotCreateData;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Support\Arr;
use Ipsocode\Cin7\Requests\Cin7Request;
use Ipsocode\Cin7\Tests\Catalogue;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * The rows of one shard of the `dtos`, `bodies` and `missing` catalogue kinds. ParaTest hands
 * a worker whole files, so the rows are spread over the `DataCatalogueShard*Test` classes
 * instead of sitting in one file that bounds the parallel run; the shards together hold every row once.
 *
 * @see docs/data.md
 */
abstract class DataCatalogueShard extends TestCase
{
    use RoundTripsFixtures;

    /**
     * Which of the `Catalogue::SHARDS` shards this class runs.
     */
    protected const int SHARD = 0;

    /**
     * @param class-string<Cin7Request> $class
     * @param list<mixed> $args
     * @param array<string, mixed> $fixture
     * @param class-string<Data> $dataClass
     * @param string $path where the record or list sits in the fixture, empty for the whole body
     */
    #[DataProvider('dtoProvider')]
    public function testTheDtoIsTheDataClassAndRoundTripsTheFixture(
        string $class,
        array $args,
        array $fixture,
        string $dataClass,
        string $path,
    ): void {
        Saloon::fake([MockResponse::make($fixture)]);

        $response = $this->connector()->send(new $class(...$args));
        $dto = $response->dto();
        $expected = $path === '' ? $fixture : Arr::get($fixture, $path);

        if (array_is_list($expected)) {
            $this->assertIsArray($dto);
            $this->assertContainsOnlyInstancesOf($dataClass, $dto);
            $this->assertRoundTrips($expected, array_map(static fn (Data $item): array => $item->toArray(), $dto));
            $this->assertSame($response, $dto[0]->getResponse());

            return;
        }

        $this->assertInstanceOf($dataClass, $dto);
        $this->assertRoundTrips($expected, $dto->toArray());
        $this->assertSame($response, $dto->getResponse());
    }

    /**
     * @return array<string, array{string, list<mixed>, array<string, mixed>, string, string}>
     */
    public static function dtoProvider(): array
    {
        return Catalogue::shard('dtos', static::SHARD);
    }

    /**
     * The body models are not responses, so their fixtures, the reference's request examples,
     * round-trip through the class.
     *
     * @param class-string<Data> $class
     * @param array<string, mixed> $fixture
     */
    #[DataProvider('bodyProvider')]
    public function testTheBodyModelsRoundTripTheirFixtures(string $class, array $fixture): void
    {
        $this->assertRoundTrips($fixture, $class::from($fixture)->toArray());
    }

    /**
     * @return array<string, array{class-string<Data>, array<string, mixed>}>
     */
    public static function bodyProvider(): array
    {
        return Catalogue::shard('bodies', static::SHARD);
    }

    /**
     * @param class-string<Data> $class
     * @param array<string, mixed> $payload
     */
    #[DataProvider('missingRequiredFieldProvider')]
    public function testAModelWithoutARequiredFieldCannotBeBuilt(string $class, array $payload): void
    {
        $this->expectException(CannotCreateData::class);

        $class::from($payload);
    }

    /**
     * @return array<string, array{class-string<Data>, array<string, mixed>}>
     */
    public static function missingRequiredFieldProvider(): array
    {
        return Catalogue::shard('missing', static::SHARD);
    }
}
