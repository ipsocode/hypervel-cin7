<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Data;

use Hypervel\Data\Data;
use Hypervel\Validation\ValidationException;
use Ipsocode\Cin7\Data\ProductSuppliers\ProductSuppliersData;
use Ipsocode\Cin7\Data\Reference\Deals\ProductDealPostData;
use Ipsocode\Cin7\Data\Reference\Deals\ProductDealPutData;
use Ipsocode\Cin7\Tests\Catalogue;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Every `bodies` row, the reference's request example, goes through the validation `send()`
 * runs on a data object body (`WriteRequest::defaultBody()`: `required` relaxed to `present`),
 * so a wrong validation attribute fails here and not only for a real caller.
 *
 * The rows in `expectedFailures()` are the examples that fail it, each on exactly the fields
 * listed, with an index in a path written as `*`: the model requires what the reference's table
 * requires, and the example leaves it out (see "Where the reference's tables and examples
 * disagree" in `docs/data.md`).
 *
 * @see docs/data.md
 */
class BodyValidationTest extends TestCase
{
    /**
     * @param class-string<Data> $class
     * @param array<string, mixed> $fixture
     */
    #[DataProvider('bodyProvider')]
    public function testABodyValidatesTheWayASendDoes(string $key, string $class, array $fixture): void
    {
        $failed = [];

        try {
            $class::factory()
                ->afterRules(static fn (array $rules): array => array_map(
                    static fn (mixed $rule): mixed => $rule === 'required' ? 'present' : $rule,
                    $rules,
                ))
                ->validate(self::withoutNulls($class::from($fixture)->toArray()));
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
     * @return array<string, array{string, class-string<Data>, array<string, mixed>}>
     */
    public static function bodyProvider(): array
    {
        $rows = [];

        foreach (Catalogue::rows('bodies') as $key => [$class, $fixture]) {
            $rows[$key] = [(string) $key, $class, $fixture];
        }

        return $rows;
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
     * Drop the null values of a data object's array, at every depth, as `WriteRequest` does.
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
}
