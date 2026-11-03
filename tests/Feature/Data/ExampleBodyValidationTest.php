<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Data;

use Hypervel\Data\Data;
use Hypervel\Validation\ValidationException;
use Ipsocode\Cin7\Data\ProductSuppliers\ProductSuppliersData;
use Ipsocode\Cin7\Data\Reference\Deals\ProductDealPostData;
use Ipsocode\Cin7\Data\Reference\Deals\ProductDealPutData;
use Ipsocode\Cin7\Requests\WriteRequest;
use Ipsocode\Cin7\Tests\Catalogue;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use ReflectionClass;
use ReflectionMethod;

/**
 * Every `bodies` row, the reference's request example, is sent through the request that takes its
 * data class: `WriteRequest::defaultBody()` leaves out the nulls and the `$omit` fields and
 * validates what remains with `required` relaxed to `present`, so a wrong validation attribute
 * fails here and not only for a real caller.
 *
 * The rows in `expectedFailures()` are the examples that fail it, each on exactly the fields
 * listed, with an index in a path written as `*`: the model requires what the reference's table
 * requires, and the example leaves it out (see "Where the reference's tables and examples
 * disagree" in `src/docs/data.md`).
 *
 * @see src/docs/data.md
 */
class ExampleBodyValidationTest extends TestCase
{
    /**
     * @param class-string<Data> $class
     * @param class-string<WriteRequest<mixed>> $request
     * @param array<string, mixed> $fixture
     */
    #[DataProvider('bodyProvider')]
    public function testABodyValidatesTheWayASendDoes(string $key, string $class, string $request, array $fixture): void
    {
        $failed = [];

        try {
            self::bodyOf($request, $class::from($fixture));
        } catch (ValidationException $exception) {
            $failed = array_values(array_unique(array_map(
                static fn (string $field): string => (string) preg_replace('/\.\d+(?=\.|$)/', '.*', $field),
                array_keys($exception->errors()),
            )));
        }

        sort($failed);

        $expected = self::expectedFailures()[$key] ?? [];

        sort($expected);

        $this->assertSame($expected, $failed);
    }

    /**
     * @return array<string, array{string, class-string<Data>, class-string<WriteRequest<mixed>>, array<string, mixed>}>
     */
    public static function bodyProvider(): array
    {
        $rows = [];

        foreach (Catalogue::rows('bodies') as $key => [$class, $request, $fixture]) {
            $rows[$key] = [(string) $key, $class, $request, $fixture];
        }

        return $rows;
    }

    /**
     * Every `bodies` row names the write request that sends its data class.
     */
    public function testEveryBodyNamesAWriteRequest(): void
    {
        foreach (array_column(Catalogue::rows('bodies'), 1) as $request) {
            $this->assertTrue(is_subclass_of($request, WriteRequest::class), (string) $request);
        }
    }

    /**
     * The bodies that fail, by `bodies` row, with the fields they fail on.
     *
     * @return array<string, list<string>>
     */
    private static function expectedFailures(): array
    {
        $option = 'ProductSuppliers.*.ProductSupplierOptions.*.';
        $link = 'Operations.*.OperationLinks.*.';
        $discount = 'DealDiscounts.*.';

        return [
            // A supplier option's example sends both LocationID and LocationName as null, and the
            // table requires one of them.
            ProductSuppliersData::class . ' POST' => [$option . 'LocationID', $option . 'LocationName'],
            ProductSuppliersData::class . ' PUT' => [$option . 'LocationID', $option . 'LocationName'],
            // The example sends three of its four finished products without Received.
            'ProductionRunOperationCompletePutData production/order/run/operation/complete' => ['FinishedProducts.*.Received'],
            // The example links an operation by name, in its second operation. The Data library
            // resolves the rule's sibling field with the wrong index there, so it asks for the ID
            // as well.
            'ProductProductionBomPostData production/productionBOM' => ['ProductionBOMs.*.' . $link . 'RelatedOperationID'],
            // The example sends no Version, Name or IsDefault, and both link fields as null.
            'ProductFamilyProductionBomPostData production/productionBOM' => [
                'ProductionBOMs.*.IsDefault',
                'ProductionBOMs.*.Name',
                'ProductionBOMs.*.Version',
                'ProductionBOMs.*.' . $link . 'RelatedOperationID',
                'ProductionBOMs.*.' . $link . 'RelatedOperationName',
            ],
            // The example links an operation with both fields null.
            'ProductFamilyProductionBomPutData production/productionBOM' => [$link . 'RelatedOperationID', $link . 'RelatedOperationName'],
            // The examples send a deal's brands, categories, tags and products without the Type
            // the table requires, and a product without IsFamily.
            ProductDealPostData::class => [
                $discount . 'DealDiscountBrands.*.Type',
                $discount . 'DealDiscountCategories.*.Type',
                $discount . 'DealDiscountProducts.*.IsFamily',
                $discount . 'DealDiscountProducts.*.Type',
                $discount . 'DealDiscountTags.*.Type',
            ],
            ProductDealPutData::class => [
                $discount . 'DealDiscountProducts.*.Type',
                $discount . 'DealDiscountTags.*.Type',
            ],
        ];
    }

    /**
     * The body a request sends for a data object, as `send()` builds it.
     *
     * @param class-string<WriteRequest<mixed>> $request
     * @return array<array-key, mixed>
     */
    private static function bodyOf(string $request, Data $body): array
    {
        $instance = new ReflectionClass($request)->newInstanceWithoutConstructor();

        // The request's own arguments (an ID, a query) play no part in the body.
        new ReflectionMethod(WriteRequest::class, '__construct')->invoke($instance, $body);

        return new ReflectionMethod($instance, 'defaultBody')->invoke($instance);
    }
}
