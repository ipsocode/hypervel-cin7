<?php

declare(strict_types=1);

namespace Workbench\App\Support;

/**
 * Cin7-shaped response bodies for the Workbench application and the test suite.
 *
 * Lists keep Cin7's `Total`, `Page` and `<Thing>List` envelope, so a test asserts on
 * the keys the real API sends.
 *
 * @see docs/testing.md
 */
final class Cin7Payloads
{
    /**
     * One page of customers. `$total` defaults to this page's item count, so a
     * multi-page fixture passes it.
     *
     * @param list<array<string, mixed>> $customers
     * @return array<string, mixed>
     */
    public static function customerList(array $customers = [], int $page = 1, ?int $total = null): array
    {
        return [
            'Total' => $total ?? count($customers),
            'Page' => $page,
            'CustomerList' => $customers,
        ];
    }

    /**
     * One customer record, as it appears inside `CustomerList`.
     *
     * @return array<string, mixed>
     */
    public static function customer(string $id = '11111111-2222-3333-4444-555555555555', string $name = 'ACME'): array
    {
        return [
            'ID' => $id,
            'Name' => $name,
            'Status' => 'Active',
            'Currency' => 'GBP',
        ];
    }

    /**
     * One page of products. Unlike the other lists, the V2 envelope is keyed `Products`
     * (`{Total, Page, Products}`), not `ProductList`.
     *
     * @param list<array<string, mixed>> $products
     * @return array<string, mixed>
     */
    public static function products(array $products = [], int $page = 1, ?int $total = null): array
    {
        return [
            'Total' => $total ?? count($products),
            'Page' => $page,
            'Products' => $products,
        ];
    }

    /**
     * One page of a customer's credits. Unlike the other lists, the V2 envelope has no
     * `Total` (`{Page, CustomerCredits}`).
     *
     * @param list<array<string, mixed>> $credits
     * @return array<string, mixed>
     */
    public static function customerCredits(array $credits = [], int $page = 1): array
    {
        return [
            'Page' => $page,
            'CustomerCredits' => $credits,
        ];
    }

    /**
     * The `customer` GET example from the V2 reference, whole: one customer with an address,
     * a contact and a child customer, and one with a parent, a contact and a product price.
     *
     * @return array<string, mixed>
     */
    public static function customerExample(): array
    {
        return [
            'Total' => 2,
            'Page' => 1,
            'CustomerList' => [
                [
                    'ID' => '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1',
                    'Name' => 'Human Test',
                    'DisplayName' => 'Mary Jane',
                    'Currency' => 'AUD',
                    'PaymentTerm' => '30 days',
                    'Discount' => 0,
                    'TaxRule' => 'BAS Excluded',
                    'Carrier' => 'DEFAULT Carrier',
                    'SalesRepresentative' => null,
                    'Location' => 'Main Warehouse',
                    'Comments' => '',
                    'AccountReceivable' => '610',
                    'RevenueAccount' => '200',
                    'PriceTier' => 'Tier 1',
                    'TaxNumber' => '',
                    'AdditionalAttribute1' => '',
                    'AdditionalAttribute2' => '',
                    'AdditionalAttribute3' => '',
                    'AdditionalAttribute4' => '',
                    'AdditionalAttribute5' => '',
                    'AdditionalAttribute6' => '',
                    'AdditionalAttribute7' => '',
                    'AdditionalAttribute8' => '',
                    'AdditionalAttribute9' => '',
                    'AdditionalAttribute10' => '',
                    'AttributeSet' => null,
                    'Tags' => '',
                    'Status' => 'Active',
                    'CreditLimit' => 0,
                    'IsOnCreditHold' => false,
                    'IsLegalEntity' => false,
                    'CustomerParentID' => null,
                    'CustomerParentName' => null,
                    'IsBillParent' => false,
                    'LastModifiedOn' => '2018-01-24T05:07:23.917Z',
                    'Addresses' => [
                        [
                            'Line1' => 'P O Box 456',
                            'Line2' => 'Southbank',
                            'City' => 'Melbourne GPO',
                            'State' => 'VIC',
                            'Postcode' => '3331',
                            'Country' => 'Uruguay',
                            'Type' => 'Billing',
                            'DefaultForType' => true,
                            'ID' => 'fc8ca28e-7f91-4ac3-8fff-deb86395b494',
                        ],
                    ],
                    'Contacts' => [
                        [
                            'Name' => 'Nick Wakefield',
                            'JobTitle' => null,
                            'Phone' => '03 3014556',
                            'MobilePhone' => null,
                            'Fax' => null,
                            'Email' => null,
                            'Website' => null,
                            'Default' => true,
                            'Comment' => null,
                            'IncludeInEmail' => false,
                            'MarketingConsent' => 1,
                            'ID' => 'c650db26-2fca-4fe4-837d-ffd0f4d97200',
                        ],
                    ],
                    'ChildCustomers' => [
                        [
                            'CustomerID' => '4f36c2a6-f736-450f-be41-28372bde80f6',
                            'CustomerName' => 'Child Customer',
                        ],
                    ],
                ],
                [
                    'ID' => '861918cc-81f9-4701-9639-864c796561cf',
                    'Name' => 'Customer Test',
                    'DisplayName' => 'John Doe',
                    'Currency' => 'AUD',
                    'PaymentTerm' => '30 days',
                    'Discount' => 0,
                    'TaxRule' => 'GST Free Exports',
                    'Carrier' => null,
                    'SalesRepresentative' => null,
                    'Location' => 'Main Warehouse',
                    'Comments' => null,
                    'AccountReceivable' => '610',
                    'RevenueAccount' => '200',
                    'PriceTier' => 'Tier 1',
                    'TaxNumber' => null,
                    'AdditionalAttribute1' => null,
                    'AdditionalAttribute2' => null,
                    'AdditionalAttribute3' => null,
                    'AdditionalAttribute4' => null,
                    'AdditionalAttribute5' => null,
                    'AdditionalAttribute6' => null,
                    'AdditionalAttribute7' => null,
                    'AdditionalAttribute8' => null,
                    'AdditionalAttribute9' => null,
                    'AdditionalAttribute10' => null,
                    'AttributeSet' => null,
                    'Tags' => null,
                    'Status' => 'Active',
                    'CreditLimit' => 0,
                    'IsOnCreditHold' => false,
                    'IsLegalEntity' => true,
                    'CustomerParentID' => 'ceb4ef7a-e23d-4730-8021-e807e5b76882',
                    'CustomerParentName' => 'Parent customer test',
                    'IsBillParent' => true,
                    'LastModifiedOn' => '2018-01-30T06:07:43.46Z',
                    'Addresses' => [],
                    'Contacts' => [
                        [
                            'Name' => 'Customer Test',
                            'JobTitle' => null,
                            'Phone' => null,
                            'MobilePhone' => null,
                            'Fax' => null,
                            'Email' => 'Customer@mail.ru',
                            'Website' => null,
                            'Default' => true,
                            'Comment' => null,
                            'IncludeInEmail' => false,
                            'MarketingConsent' => 2,
                            'ID' => '23ef723b-75b5-43b3-8ee7-56d193e53923',
                        ],
                    ],
                    'ProductPrices' => [
                        [
                            'ProductID' => '1cd995b0-a8f1-456c-9784-ecf3a8fc2804',
                            'ProductName' => 'Screws Drive Cross',
                            'ProductSKU' => 'Screws-SKU - 002',
                            'Price' => 1.1,
                        ],
                    ],
                    'ChildCustomers' => [],
                ],
            ],
        ];
    }

    /**
     * The `product` GET example from the V2 reference, whole, with every nested list populated
     * except `Suppliers`, which is empty in the reference. The reference's example has an
     * unclosed quote on the movement's `Date`; it is closed here.
     *
     * @return array<string, mixed>
     */
    public static function productExample(): array
    {
        return [
            'Total' => 1,
            'Page' => 1,
            'Products' => [
                [
                    'ID' => '524c20a3-a8ec-44f2-9685-311f1f7d1498',
                    'SKU' => 'Bread',
                    'Name' => 'Baked Bread',
                    'Category' => 'Other',
                    'Brand' => null,
                    'Type' => 'Stock',
                    'CostingMethod' => 'FEFO - Batch',
                    'DropShipMode' => 'No Drop Ship',
                    'DefaultLocation' => 'Main Warehouse',
                    'Length' => 0,
                    'Width' => 0,
                    'Height' => 0,
                    'Weight' => 0,
                    'UOM' => 'Item',
                    'WeightUnits' => 'oz',
                    'DimensionsUnits' => null,
                    'Barcode' => '',
                    'MinimumBeforeReorder' => 0,
                    'ReorderQuantity' => 0,
                    'PriceTier1' => 8,
                    'PriceTier2' => 0,
                    'PriceTier3' => 0,
                    'PriceTier4' => 0,
                    'PriceTier5' => 0,
                    'PriceTier6' => 0,
                    'PriceTier7' => 0,
                    'PriceTier8' => 0,
                    'PriceTier9' => 0,
                    'PriceTier10' => 0,
                    'PriceTiers' => [
                        'Tier 1' => 8,
                        'Tier 2' => 0,
                        'Tier 3' => 0,
                        'Tier 4' => 0,
                        'Tier 5' => 0,
                        'Tier 6' => 0,
                        'Tier 7' => 0,
                        'Tier 8' => 0,
                        'Tier 9' => 0,
                        'Tier 10' => 0,
                    ],
                    'AverageCost' => 5,
                    'ShortDescription' => '',
                    'Description' => '',
                    'InternalNote' => '',
                    'AdditionalAttribute1' => '',
                    'AdditionalAttribute2' => '',
                    'AdditionalAttribute3' => '',
                    'AdditionalAttribute4' => '',
                    'AdditionalAttribute5' => '',
                    'AdditionalAttribute6' => '',
                    'AdditionalAttribute7' => '',
                    'AdditionalAttribute8' => '',
                    'AdditionalAttribute9' => '',
                    'AdditionalAttribute10' => '',
                    'AttributeSet' => null,
                    'DiscountRule' => null,
                    'Tags' => 'Bread',
                    'Status' => 'Active',
                    'StockLocator' => '',
                    'COGSAccount' => null,
                    'RevenueAccount' => null,
                    'ExpenseAccount' => null,
                    'InventoryAccount' => null,
                    'PurchaseTaxRule' => null,
                    'SaleTaxRule' => null,
                    'LastModifiedOn' => '2017-12-26T07:19:37.937Z',
                    'Sellable' => true,
                    'PickZones' => 'test',
                    'BillOfMaterial' => true,
                    'AutoAssembly' => false,
                    'AutoDisassembly' => false,
                    'QuantityToProduce' => 1,
                    'AssemblyInstructionURL' => '',
                    'AssemblyCostEstimationMethod' => 'Average Cost',
                    'Suppliers' => [],
                    'ReorderLevels' => [
                        [
                            'LocationID' => '19aeca31-bd49-4fbe-8abd-37a6169cc2cb',
                            'LocationName' => 'Main Warehouse',
                            'MinimumBeforeReorder' => 1,
                            'ReorderQuantity' => 1,
                            'StockLocator' => '1',
                            'PickZones' => 'test',
                        ],
                    ],
                    'BillOfMaterialsProducts' => [
                        [
                            'ComponentProductID' => 'ce9a6504-4207-4001-b430-749bf11fdc4f',
                            'ProductCode' => 'GB1-White',
                            'Name' => 'Golf balls - white single',
                            'Quantity' => 1,
                            'WastagePercent' => 0,
                            'WastageQuantity' => 1,
                            'CostPercentage' => 100,
                        ],
                    ],
                    'BillOfMaterialsServices' => [
                        [
                            'ComponentProductID' => '5c6a9204-275f-4a8b-8a90-317d674a4504',
                            'Name' => 'Half day training - Microsoft Office',
                            'Quantity' => 1,
                            'ExpenseAccount' => '',
                            'PriceTier' => 1,
                        ],
                    ],
                    'Movements' => [
                        [
                            'TaskID' => '85585398-caba-4df6-99a2-1ff2cecf9306',
                            'Type' => 'Adjustment',
                            'Date' => '2017-12-25T00:00:00',
                            'Number' => 'ST-00001',
                            'Quantity' => 111,
                            'Amount' => 555,
                            'Location' => 'Main Warehouse',
                            'BatchSN' => '1',
                            'ExpiryDate' => '2017-12-07T00:00:00',
                            'FromTo' => '',
                        ],
                    ],
                    'Attachments' => [
                        [
                            'ID' => '3fd5df29-83d6-4576-9e81-27c3560dfc7c',
                            'ContentType' => 'image/jpeg',
                            'FileName' => '1471081716149.jpg',
                            'DownloadUrl' => 'https://inventory.dearsystems.com/Attachment/Download?ID=3fd5df29-83d6-4576-9e81-27c3560dfc7c&ContentType=image/jpeg&FileName=1471081716149.jpg&isPublic=True',
                        ],
                    ],
                    'CustomPrices' => [
                        [
                            'ProductID' => '1cd995b0-a8f1-456c-9784-ecf3a8fc2804',
                            'ProductName' => 'Screws Drive Cross',
                            'ProductSKU' => 'Screws-SKU - 002',
                            'Price' => 1.1,
                        ],
                    ],
                    'CartonHeight' => 10,
                    'CartonWidth' => 10,
                    'CartonLength' => 10,
                    'CartonQuantity' => 2,
                    'CartonInnerQuantity' => 1,
                    'HSCode' => '654324',
                    'CountryOfOrigin' => 'Algeria',
                    'CountryOfOriginCode' => 'DZA',
                ],
            ],
        ];
    }

    /**
     * A POST or PUT `customer` response: the list envelope holding the one saved customer.
     *
     * @return array<string, mixed>
     */
    public static function customerSaved(): array
    {
        $example = self::customerExample();

        return ['Total' => 1, 'Page' => 1, 'CustomerList' => [$example['CustomerList'][0]]];
    }

    /**
     * A POST or PUT `product` response: the list envelope holding the one saved product.
     *
     * @return array<string, mixed>
     */
    public static function productSaved(): array
    {
        return self::productExample();
    }

    /**
     * The `ref/tax` GET example from the V2 reference: two rules, the first of which has a
     * component with no `Compound` key.
     *
     * @return array<string, mixed>
     */
    public static function taxList(): array
    {
        return [
            'Total' => 2,
            'Page' => 1,
            'TaxRuleList' => [
                [
                    'ID' => '9d707beb-19cf-4d7b-a5d9-9eaff05c504c',
                    'Name' => 'GST on Income',
                    'Account' => '820',
                    'IsActive' => true,
                    'TaxInclusive' => false,
                    'TaxPercent' => 10,
                    'IsTaxForSale' => true,
                    'IsTaxForPurchase' => false,
                    'Components' => [
                        [
                            'ID' => 'B1151EEA-3364-4534-88E6-DF2C6B59A6D8',
                            'Name' => 'Tax',
                            'Percent' => '10.0000000000',
                            'AccountCode' => '0',
                            'ComponentOrder' => '1',
                        ],
                    ],
                ],
                [
                    'ID' => 'd4d53cdf-4b87-4b67-9c17-733fc5419a2a',
                    'Name' => 'Tax test',
                    'Account' => '820',
                    'IsActive' => true,
                    'TaxInclusive' => false,
                    'TaxPercent' => 0,
                    'IsTaxForSale' => false,
                    'IsTaxForPurchase' => true,
                    'Components' => [
                        [
                            'ID' => '534AC4D9-6F37-448A-971E-DF1C2B21FB64',
                            'Name' => 'No Tax',
                            'Percent' => '0.0000000000',
                            'AccountCode' => '',
                            'Compound' => '0',
                            'ComponentOrder' => '1',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * The `ref/tax` POST example response from the V2 reference: one saved rule with two
     * components. The PUT response has the same shape.
     *
     * @return array<string, mixed>
     */
    public static function taxSaved(): array
    {
        return [
            'Total' => 1,
            'Page' => 1,
            'TaxRuleList' => [
                [
                    'ID' => '24551562-ebd1-4294-a04a-3ab258f5e541',
                    'Name' => 'Post test',
                    'Account' => '800',
                    'IsActive' => true,
                    'TaxInclusive' => false,
                    'TaxPercent' => 35,
                    'IsTaxForSale' => true,
                    'IsTaxForPurchase' => true,
                    'Components' => [
                        [
                            'ID' => '0119E1C0-504A-429B-B4E2-4EEC02A9E88A',
                            'Name' => 'Tax 1st',
                            'Percent' => '10.0000000000',
                            'AccountCode' => '800',
                            'Compound' => '1',
                            'ComponentOrder' => '1',
                        ],
                        [
                            'ID' => '0B6D5C0F-D8EF-41FF-86D2-A05E5616FC61',
                            'Name' => 'Tax 2nd',
                            'Percent' => '15.0000000000',
                            'AccountCode' => '800',
                            'Compound' => '0',
                            'ComponentOrder' => '2',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * The `ref/customer/credits` GET example from the V2 reference.
     *
     * @return array<string, mixed>
     */
    public static function customerCreditsExample(): array
    {
        return [
            'Page' => 1,
            'CustomerCredits' => [
                [
                    'CreditID' => '55607b9a-dc9b-4ef4-9ed3-495434e90434',
                    'CustomerID' => 'ce607b9a-dc9b-4ef4-9ed3-495434e90467',
                    'CustomerName' => 'Customer name',
                    'Account' => '1200',
                    'Amount' => 100,
                    'RemainingAmount' => 50,
                    'Currency' => 'USD',
                    'ConvRate' => 1,
                    'Date' => '2024-01-09T00:00:00',
                    'Description' => 'Credit by prepayments',
                ],
                [
                    'CreditID' => '67607b9a-dc9b-4ef4-9ed3-495434e90489',
                    'CustomerID' => 'ce607b9a-dc9b-4ef4-9ed3-495434e90467',
                    'CustomerName' => 'Customer name',
                    'Account' => '1300',
                    'Amount' => 100,
                    'RemainingAmount' => 0,
                    'Currency' => 'USD',
                    'ConvRate' => 1,
                    'Date' => '2024-04-09T00:00:00',
                    'Description' => 'Credit by prepayments',
                ],
            ],
        ];
    }

    /**
     * The `sale` GET example from the V2 reference: a sale keyed by `ID`, carrying every
     * nested model (quote, order, fulfilment, invoice, credit note, journals, attachments,
     * inventory movements and transactions). The `ID` is replaceable.
     *
     * @return array<string, mixed>
     */
    public static function sale(?string $id = null): array
    {
        $sale = [
            'ID' => '916ab4c0-6ccb-4c93-873d-0603859050e4',
            'Customer' => 'Hamilton Smith Pty',
            'CustomerID' => '6c18f8e9-90e1-418f-aebc-1219e67e4b9c',
            'Contact' => 'No Name',
            'Phone' => '03 3189080',
            'Email' => 'infodemo@hsmithdemo.co',
            'DefaultAccount' => '200',
            'SkipQuote' => false,
            'BillingAddress' => [
                'DisplayAddressLine1' => '3 Park Street Industrial Village Southbank',
                'DisplayAddressLine2' => 'Melbourne VIC 3331',
                'Line1' => '3 Park Street Industrial Village',
                'Line2' => 'Southbank',
                'City' => 'Melbourne',
                'State' => 'VIC',
                'Postcode' => '3331',
                'Country' => 'USA',
            ],
            'ShippingAddress' => [
                'DisplayAddressLine1' => '3 Park Street Industrial Village Southbank',
                'DisplayAddressLine2' => 'Melbourne VIC 3331',
                'Line1' => '3 Park Street Industrial Village',
                'Line2' => 'Southbank',
                'City' => 'Melbourne',
                'State' => 'VIC',
                'Postcode' => '3331',
                'Country' => 'USA',
                'Company' => 'DIISR - Small Business Services',
                'Contact' => 'Sheree Bond',
                'ShipToOther' => false,
            ],
            'ShippingNotes' => '',
            'BaseCurrency' => 'RUB',
            'CustomerCurrency' => 'RUB',
            'TaxRule' => 'Tax on Sales',
            'TaxCalculation' => 'Exclusive',
            'Terms' => '30 days',
            'PriceTier' => 'Tier 1',
            'ShipBy' => '2017-11-30T00:00:00',
            'Location' => 'Main Warehouse',
            'SaleOrderDate' => '2017-10-28T00:00:00',
            'LastModifiedOn' => '2017-11-22T10:10:49.75Z',
            'Note' => '',
            'CustomerReference' => '',
            'COGSAmount' => 0,
            'Status' => 'ORDERED',
            'CombinedPickingStatus' => 'NOT AVAILABLE',
            'CombinedPackingStatus' => 'NOT AVAILABLE',
            'CombinedShippingStatus' => 'NOT AVAILABLE',
            'FulFilmentStatus' => 'NOT AVAILABLE',
            'CombinedInvoiceStatus' => 'NOT AVAILABLE',
            'CombinedPaymentStatus' => 'UNPAID',
            'CombinedTrackingNumbers' => '',
            'Carrier' => 'test carrier',
            'CurrencyRate' => 1,
            'SalesRepresentative' => 'DEFAULT billing contact',
            'ServiceOnly' => false,
            'Type' => 'Advanced Sale',
            'SourceChannel' => 'Amazon_US',
            'Quote' => [
                'Memo' => '',
                'Status' => 'AUTHORISED',
                'Prepayments' => [],
                'Lines' => [
                    [
                        'ProductID' => '4aadd8f6-4d3d-46ca-acbb-1a9a662f9bc1',
                        'SKU' => 'Bread',
                        'Name' => 'Baked Bread',
                        'Quantity' => 1,
                        'Price' => 8,
                        'Discount' => 0,
                        'Tax' => 0,
                        'AverageCost' => 5,
                        'TaxRule' => 'Tax on Sales',
                        'Comment' => '',
                        'Total' => 8,
                        'ProductLength' => 0,
                        'ProductWidth' => 0,
                        'ProductHeight' => 0,
                        'ProductWeight' => 0,
                        'WeightUnits' => '',
                        'DimensionsUnits' => '',
                        'ProductCustomField1' => null,
                        'ProductCustomField2' => null,
                        'ProductCustomField3' => null,
                        'ProductCustomField4' => null,
                        'ProductCustomField5' => null,
                        'ProductCustomField6' => null,
                        'ProductCustomField7' => null,
                        'ProductCustomField8' => null,
                        'ProductCustomField9' => null,
                        'ProductCustomField10' => null,
                    ],
                ],
                'AdditionalCharges' => [
                    [
                        'Description' => 'Desktop/network support via phone. Per month fixed fee for minimum 20 hours/month.',
                        'Price' => 350,
                        'Quantity' => 1,
                        'Discount' => 0,
                        'Tax' => 0,
                        'Total' => 350,
                        'TaxRule' => 'Tax on Sales',
                        'Comment' => '',
                    ],
                ],
                'TotalBeforeTax' => 358,
                'Tax' => 0,
                'Total' => 358,
            ],
            'Order' => [
                'SaleOrderNumber' => 'SO-00001',
                'Memo' => '',
                'Status' => 'AUTHORISED',
                'Lines' => [
                    [
                        'ProductID' => '4aadd8f6-4d3d-46ca-acbb-1a9a662f9bc1',
                        'SKU' => 'Bread',
                        'Name' => 'Baked Bread',
                        'Quantity' => 1,
                        'Price' => 8,
                        'Discount' => 0,
                        'Tax' => 0,
                        'AverageCost' => 5,
                        'TaxRule' => 'Tax on Sales',
                        'Comment' => '',
                        'DropShip' => false,
                        'Backorder' => false,
                        'BackorderQuantity' => 0,
                        'Total' => 8,
                        'ProductLength' => 0,
                        'ProductWidth' => 0,
                        'ProductHeight' => 0,
                        'ProductWeight' => 0,
                        'WeightUnits' => '',
                        'DimensionsUnits' => '',
                        'ProductCustomField1' => null,
                        'ProductCustomField2' => null,
                        'ProductCustomField3' => null,
                        'ProductCustomField4' => null,
                        'ProductCustomField5' => null,
                        'ProductCustomField6' => null,
                        'ProductCustomField7' => null,
                        'ProductCustomField8' => null,
                        'ProductCustomField9' => null,
                        'ProductCustomField10' => null,
                    ],
                ],
                'AdditionalCharges' => [
                    [
                        'Description' => 'Desktop/network support via phone. Per month fixed fee for minimum 20 hours/month.',
                        'Price' => 350,
                        'Quantity' => 1,
                        'Discount' => 0,
                        'Tax' => 0,
                        'Total' => 350,
                        'TaxRule' => 'Tax on Sales',
                        'Comment' => '',
                    ],
                ],
                'TotalBeforeTax' => 8,
                'Tax' => 0,
                'Total' => 8,
            ],
            'Fulfilments' => [
                [
                    'TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57',
                    'FulfillmentNumber' => 1,
                    'LinkedInvoiceNumber' => 'INV-00001',
                    'FulFilmentStatus' => 'NOT FULFILLED',
                    'Pick' => [
                        'Status' => 'DRAFT',
                        'Lines' => [
                            [
                                'ProductID' => '4aadd8f6-4d3d-46ca-acbb-1a9a662f9bc1',
                                'SKU' => 'Bread',
                                'Name' => 'Baked Bread',
                                'Location' => 'Main Warehouse',
                                'LocationID' => '19aeca31-bd49-4fbe-8abd-37a6169cc2cb',
                                'Quantity' => 1,
                                'BatchSN' => 'PO-00001-1',
                                'ExpiryDate' => '2017-11-30T00:00:00',
                                'ProductLength' => 0,
                                'ProductWidth' => 0,
                                'ProductHeight' => 0,
                                'ProductWeight' => 0,
                                'WeightUnits' => '',
                                'DimensionsUnits' => '',
                                'ProductCustomField1' => null,
                                'ProductCustomField2' => null,
                                'ProductCustomField3' => null,
                                'ProductCustomField4' => null,
                                'ProductCustomField5' => null,
                                'ProductCustomField6' => null,
                                'ProductCustomField7' => null,
                                'ProductCustomField8' => null,
                                'ProductCustomField9' => null,
                                'ProductCustomField10' => null,
                            ],
                        ],
                    ],
                    'Pack' => [
                        'Status' => 'NOT AVAILABLE',
                        'Lines' => [
                            [
                                'ProductID' => '4aadd8f6-4d3d-46ca-acbb-1a9a662f9bc1',
                                'SKU' => 'Bread',
                                'Name' => 'Baked Bread',
                                'Location' => 'Main Warehouse',
                                'LocationID' => '19aeca31-bd49-4fbe-8abd-37a6169cc2cb',
                                'Box' => 'Box 1',
                                'Quantity' => 1,
                                'BatchSN' => 'PO-00001-1',
                                'ExpiryDate' => '2017-11-30T00:00:00',
                                'ProductLength' => 0,
                                'ProductWidth' => 0,
                                'ProductHeight' => 0,
                                'ProductWeight' => 0,
                                'WeightUnits' => '',
                                'DimensionsUnits' => '',
                                'ProductCustomField1' => null,
                                'ProductCustomField2' => null,
                                'ProductCustomField3' => null,
                                'ProductCustomField4' => null,
                                'ProductCustomField5' => null,
                                'ProductCustomField6' => null,
                                'ProductCustomField7' => null,
                                'ProductCustomField8' => null,
                                'ProductCustomField9' => null,
                                'ProductCustomField10' => null,
                            ],
                        ],
                    ],
                    'Ship' => [
                        'Status' => 'NOT AVAILABLE',
                        'RequireBy' => null,
                        'ShippingAddress' => [
                            'DisplayAddressLine1' => 'Line 1 Lines 2',
                            'DisplayAddressLine2' => 'City State Code Russia',
                            'Line1' => 'Line 1',
                            'Line2' => 'Lines 2',
                            'City' => 'City',
                            'State' => 'State',
                            'Postcode' => 'Code',
                            'Country' => 'Russia',
                            'Company' => 'DIISR - Small Business Services',
                            'Contact' => 'Sheree Bond',
                            'ShipToOther' => false,
                        ],
                        'ShippingNotes' => '',
                        'Lines' => [
                            [
                                'ID' => '67f67884-8115-4546-93c9-48230f24bd56',
                                'ShipmentDate' => '2017-11-22T00:00:00',
                                'Carrier' => 'DEFAULT Carrier',
                                'Boxes' => 'Box 1',
                                'TrackingNumber' => '',
                                'TrackingURL' => '',
                                'IsShipped' => true,
                            ],
                        ],
                    ],
                ],
            ],
            'Invoices' => [
                [
                    'TaskID' => 'b039f19e-66f8-4309-a4b1-abf928303c88',
                    'InvoiceNumber' => 'INV-00001',
                    'Memo' => '',
                    'Status' => 'AUTHORISED',
                    'InvoiceDate' => '2017-11-22T00:00:00',
                    'InvoiceDueDate' => '2017-12-22T00:00:00',
                    'CurrencyConversionRate' => 1,
                    'BillingAddressLine1' => '3 Park Street Industrial Village Southbank',
                    'BillingAddressLine2' => 'Melbourne VIC 3331',
                    'LinkedFulfillmentNumber' => '1',
                    'Lines' => [
                        [
                            'ProductID' => '4aadd8f6-4d3d-46ca-acbb-1a9a662f9bc1',
                            'SKU' => 'Bread',
                            'Name' => 'Baked Bread',
                            'Quantity' => 1,
                            'Price' => 8,
                            'Discount' => 0,
                            'Tax' => 0,
                            'Total' => 8,
                            'AverageCost' => 5,
                            'TaxRule' => 'Tax on Sales',
                            'Account' => '200',
                            'Comment' => '',
                            'ProductLength' => 0,
                            'ProductWidth' => 0,
                            'ProductHeight' => 0,
                            'ProductWeight' => 0,
                            'WeightUnits' => '',
                            'DimensionsUnits' => '',
                            'ProductCustomField1' => null,
                            'ProductCustomField2' => null,
                            'ProductCustomField3' => null,
                            'ProductCustomField4' => null,
                            'ProductCustomField5' => null,
                            'ProductCustomField6' => null,
                            'ProductCustomField7' => null,
                            'ProductCustomField8' => null,
                            'ProductCustomField9' => null,
                            'ProductCustomField10' => null,
                        ],
                    ],
                    'AdditionalCharges' => [
                        [
                            'Description' => 'Desktop/network support via phone. Per month fixed fee for minimum 20 hours/month.',
                            'Quantity' => 1,
                            'Price' => 350,
                            'Discount' => 0,
                            'Tax' => 0,
                            'Total' => 350,
                            'TaxRule' => 'Tax on Sales',
                            'Account' => '200',
                            'Comment' => '',
                        ],
                    ],
                    'Payments' => [],
                    'TotalBeforeTax' => 358,
                    'Tax' => 0,
                    'Total' => 358,
                    'Paid' => 0,
                ],
            ],
            'CreditNotes' => [
                [
                    'TaskID' => '280fba91-281c-4416-ad43-674ae2d17355',
                    'CreditNoteInvoiceNumber' => 'INV-00001',
                    'Memo' => '',
                    'Status' => 'DRAFT',
                    'CreditNoteDate' => '2017-11-22T00:00:00',
                    'CreditNoteNumber' => 'CR-00001',
                    'CreditNoteConversionRate' => 1,
                    'Lines' => [
                        [
                            'ProductID' => '4aadd8f6-4d3d-46ca-acbb-1a9a662f9bc1',
                            'SKU' => 'Bread',
                            'Name' => 'Baked Bread',
                            'Quantity' => 1,
                            'Price' => 8,
                            'Discount' => 0,
                            'Tax' => 0,
                            'Total' => 8,
                            'AverageCost' => 5,
                            'TaxRule' => 'Tax on Sales',
                            'Account' => '200',
                            'Comment' => '',
                            'ProductLength' => 0,
                            'ProductWidth' => 0,
                            'ProductHeight' => 0,
                            'ProductWeight' => 0,
                            'WeightUnits' => '',
                            'DimensionsUnits' => '',
                            'ProductCustomField1' => null,
                            'ProductCustomField2' => null,
                            'ProductCustomField3' => null,
                            'ProductCustomField4' => null,
                            'ProductCustomField5' => null,
                            'ProductCustomField6' => null,
                            'ProductCustomField7' => null,
                            'ProductCustomField8' => null,
                            'ProductCustomField9' => null,
                            'ProductCustomField10' => null,
                        ],
                    ],
                    'AdditionalCharges' => [
                        [
                            'Description' => 'Desktop/network support via phone. Per month fixed fee for minimum 20 hours/month.',
                            'Quantity' => 1,
                            'Price' => 350,
                            'Discount' => 0,
                            'Tax' => 0,
                            'Total' => 350,
                            'TaxRule' => 'Tax on Sales',
                            'Account' => '200',
                            'Comment' => '',
                        ],
                    ],
                    'Refunds' => [
                        [
                            'ID' => '20d5ff25-afa2-cd74-96d7-c7f0dd1fa1c1',
                            'Reference' => '',
                            'Amount' => 358,
                            'DatePaid' => '2017-11-23T00:00:00',
                            'Account' => '718',
                            'CurrencyRate' => 1,
                            'DateCreated' => '2017-11-22T06:58:21.8882229Z',
                        ],
                    ],
                    'Restock' => [
                        [
                            'ProductID' => '4aadd8f6-4d3d-46ca-acbb-1a9a662f9bc1',
                            'SKU' => 'Bread',
                            'Name' => 'Baked Bread',
                            'Location' => 'Main Warehouse',
                            'LocationID' => '19aeca31-bd49-4fbe-8abd-37a6169cc2cb',
                            'Quantity' => 1,
                            'BatchSN' => 'PO-00001-1',
                            'ExpiryDate' => '2017-11-30T00:00:00',
                        ],
                    ],
                    'TotalBeforeTax' => 358,
                    'Tax' => 0,
                    'Total' => 358,
                ],
            ],
            'ManualJournals' => [
                'Status' => 'NOT AVAILABLE',
                'Lines' => [],
            ],
            'ExternalID' => null,
            'AdditionalAttributes' => [
                'AdditionalAttribute1' => '',
                'AdditionalAttribute2' => '',
                'AdditionalAttribute3' => '',
                'AdditionalAttribute4' => '',
                'AdditionalAttribute5' => '',
                'AdditionalAttribute6' => '',
                'AdditionalAttribute7' => '',
                'AdditionalAttribute8' => '',
                'AdditionalAttribute9' => '',
                'AdditionalAttribute10' => '',
            ],
            'Attachments' => [
                [
                    'ID' => '1a103e3e-9837-4294-8cc3-640ebb4fc387',
                    'ContentType' => 'image/jpeg',
                    'FileName' => '1471081716149.jpg',
                    'DownloadUrl' => 'https://example.api.url/Attachment/Download?ID=1a103e3e-9837-4294-8cc3-640ebb4fc387&ContentType=image/jpeg&FileName=1471081716149.jpg',
                ],
            ],
            'InventoryMovements' => [
                [
                    'TaskID' => 'cde5fb4a-1dac-4e9a-bc33-5dfa14eedb57',
                    'ProductID' => '4aadd8f6-4d3d-46ca-acbb-1a9a662f9bc1',
                    'Date' => '2017-11-22T00:00:00',
                    'COGS' => 0,
                    'ProductLength' => 0,
                    'ProductWidth' => 0,
                    'ProductHeight' => 0,
                    'ProductWeight' => 0,
                    'WeightUnits' => '',
                    'DimensionsUnits' => '',
                    'ProductCustomField1' => null,
                    'ProductCustomField2' => null,
                    'ProductCustomField3' => null,
                    'ProductCustomField4' => null,
                    'ProductCustomField5' => null,
                    'ProductCustomField6' => null,
                    'ProductCustomField7' => null,
                    'ProductCustomField8' => null,
                    'ProductCustomField9' => null,
                    'ProductCustomField10' => null,
                ],
                [
                    'TaskID' => '529511e7-6ed6-4a99-a2a7-fab1d32198eb',
                    'ProductID' => '4aadd8f6-4d3d-46ca-acbb-1a9a662f9bc1',
                    'Date' => '2017-11-22T00:00:00',
                    'COGS' => 0,
                    'ProductLength' => 0,
                    'ProductWidth' => 0,
                    'ProductHeight' => 0,
                    'ProductWeight' => 0,
                    'WeightUnits' => '',
                    'DimensionsUnits' => '',
                    'ProductCustomField1' => null,
                    'ProductCustomField2' => null,
                    'ProductCustomField3' => null,
                    'ProductCustomField4' => null,
                    'ProductCustomField5' => null,
                    'ProductCustomField6' => null,
                    'ProductCustomField7' => null,
                    'ProductCustomField8' => null,
                    'ProductCustomField9' => null,
                    'ProductCustomField10' => null,
                ],
                [
                    'TaskID' => '529511e7-6ed6-4a99-a2a7-fab1d32198eb',
                    'ProductID' => '4aadd8f6-4d3d-46ca-acbb-1a9a662f9bc1',
                    'Date' => '2017-11-22T00:00:00',
                    'COGS' => 0,
                    'ProductLength' => 0,
                    'ProductWidth' => 0,
                    'ProductHeight' => 0,
                    'ProductWeight' => 0,
                    'WeightUnits' => '',
                    'DimensionsUnits' => '',
                    'ProductCustomField1' => null,
                    'ProductCustomField2' => null,
                    'ProductCustomField3' => null,
                    'ProductCustomField4' => null,
                    'ProductCustomField5' => null,
                    'ProductCustomField6' => null,
                    'ProductCustomField7' => null,
                    'ProductCustomField8' => null,
                    'ProductCustomField9' => null,
                    'ProductCustomField10' => null,
                ],
            ],
            'Transactions' => [
                [
                    'TaskID' => 'cc6e25b2-d9ad-434c-8d77-30f36f4cb7ed',
                    'TransactionID' => 'e960bd83-23e4-44d3-9762-0589a09407cf',
                    'Debit' => '610',
                    'Credit' => '200',
                    'Description' => '3',
                    'Amount' => 1,
                    'EffectiveDate' => '2018-09-25T00:00:00',
                ],
                [
                    'TaskID' => 'cc6e25b2-d9ad-434c-8d77-30f36f4cb7ed',
                    'TransactionID' => '0ae21546-2491-4f98-a898-1e6f0af633bc',
                    'Debit' => '610',
                    'Credit' => '200',
                    'Description' => '3',
                    'Amount' => -1,
                    'EffectiveDate' => '2018-09-25T00:00:00',
                ],
                [
                    'TaskID' => 'cc6e25b2-d9ad-434c-8d77-30f36f4cb7ed',
                    'TransactionID' => '6f278fc1-da3c-4bf0-9e13-31d5942b97c3',
                    'Debit' => '610',
                    'Credit' => '200',
                    'Description' => '1',
                    'Amount' => -2,
                    'EffectiveDate' => '2018-09-25T00:00:00',
                ],
            ],
        ];

        return $id === null ? $sale : ['ID' => $id] + $sale;
    }

    /**
     * The `saleList` GET example from the V2 reference: two sales, with the nulls it sends.
     *
     * @return array<string, mixed>
     */
    public static function saleList(): array
    {
        return [
            'Total' => 2,
            'Page' => 1,
            'SaleList' => [
                [
                    'SaleID' => '6222cb47-0e1e-450d-af6d-0cefcee9eef7',
                    'OrderNumber' => 'SO-00092',
                    'Status' => 'ORDERED',
                    'OrderDate' => '2017-09-29T00:00:00',
                    'InvoiceDate' => null,
                    'Customer' => 'Rock Star Transport',
                    'CustomerID' => '74de0528-fae7-4233-9222-e92110d57f5a',
                    'InvoiceNumber' => null,
                    'CustomerReference' => '',
                    'InvoiceAmount' => 0,
                    'PaidAmount' => 0,
                    'SaleInvoicesTotalAmount' => 0,
                    'InvoiceDueDate' => null,
                    'ShipBy' => null,
                    'BaseCurrency' => 'RUB',
                    'CustomerCurrency' => 'RUB',
                    'CreditNoteNumber' => null,
                    'Updated' => '2017-09-29T03:03:13.913Z',
                    'QuoteStatus' => 'AUTHORISED',
                    'OrderStatus' => 'AUTHORISED',
                    'CombinedPickingStatus' => 'PICKED',
                    'CombinedPaymentStatus' => 'PAID',
                    'CombinedTrackingNumbers' => '',
                    'CombinedPackingStatus' => 'PACKED',
                    'CombinedShippingStatus' => 'SHIPPING',
                    'CombinedInvoiceStatus' => 'INVOICED',
                    'CombinedPaymentTotal' => 0,
                    'CreditNoteStatus' => 'NOT AVAILABLE',
                    'FulFilmentStatus' => 'NOT FULFILLED',
                    'Type' => 'Advanced Sale',
                    'SourceChannel' => 'Amazon_US',
                    'ExternalID' => null,
                    'OrderLocationID' => '8b5d4343-c007-43d7-8e8f-6fa6c0d29f22',
                ],
                [
                    'SaleID' => 'a6558396-8893-479b-bca9-f89ea3e54633',
                    'OrderNumber' => 'SO-00091',
                    'Status' => 'BACKORDERED',
                    'OrderDate' => '2017-09-28T00:00:00',
                    'InvoiceDate' => '2017-09-28T00:00:00',
                    'Customer' => 'Rock Star Transport',
                    'CustomerID' => '9a4513e9-a7a4-4ee5-b240-84cfd8944cde',
                    'InvoiceNumber' => 'INV-06073',
                    'CustomerReference' => '19614',
                    'InvoiceAmount' => 0,
                    'PaidAmount' => 0,
                    'SaleInvoicesTotalAmount' => 0,
                    'InvoiceDueDate' => '2017-10-02T00:00:00',
                    'ShipBy' => '2017-09-29T00:00:00',
                    'BaseCurrency' => 'RUB',
                    'CustomerCurrency' => 'AUD',
                    'CreditNoteNumber' => null,
                    'Updated' => '2017-09-29T03:00:48.043Z',
                    'QuoteStatus' => 'NOT AVAILABLE',
                    'OrderStatus' => 'AUTHORISED',
                    'CombinedPickingStatus' => 'NOT PICKED',
                    'CombinedPaymentStatus' => 'UNPAID',
                    'CombinedTrackingNumbers' => '',
                    'CombinedPackingStatus' => 'NOT PACKED',
                    'CombinedShippingStatus' => 'NOT SHIPPED',
                    'CombinedInvoiceStatus' => 'NOT INVOICED',
                    'CombinedPaymentTotal' => 0,
                    'CreditNoteStatus' => 'NOT AVAILABLE',
                    'FulFilmentStatus' => 'NOT FULFILLED',
                    'Type' => 'Simple Sale',
                    'SourceChannel' => null,
                    'ExternalID' => null,
                    'OrderLocationID' => '8b5d4343-c007-43d7-8e8f-6fa6c0d29f22',
                ],
            ],
        ];
    }

    /**
     * The Sale Order of the `sale` GET example, as `sale/order` answers it.
     *
     * @return array<string, mixed>
     */
    public static function saleOrder(): array
    {
        return ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'] + self::sale()['Order'];
    }

    /**
     * Sale Invoice Partial Model, copied from the `sale` example's first invoice.
     *
     * @return array<string, mixed>
     */
    public static function saleInvoicePartial(): array
    {
        return ['CombineAdditionalCharges' => false] + self::sale()['Invoices'][0];
    }

    /**
     * The `{SaleID, Invoices}` envelope `sale/invoice` answers with.
     *
     * @return array<string, mixed>
     */
    public static function saleInvoices(): array
    {
        return ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'Invoices' => [self::saleInvoicePartial()]];
    }

    /**
     * Sale Invoice POST Model: the invoice's own fields plus `SaleID`, and an empty-GUID `TaskID`.
     *
     * @return array<string, mixed>
     */
    public static function saleInvoicePost(): array
    {
        $invoice = self::saleInvoicePartial();
        unset($invoice['Payments'], $invoice['TotalBeforeTax'], $invoice['Tax'], $invoice['Total'], $invoice['Paid']);

        return ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4']
            + ['TaskID' => '00000000-0000-0000-0000-000000000000'] + $invoice;
    }

    /**
     * Sale Credit Note Partial Model: the `sale` example's first credit note, with the
     * `CreditNoteBalance` and `Payments` the GET response adds.
     *
     * @return array<string, mixed>
     */
    public static function saleCreditNotePartial(): array
    {
        return self::sale()['CreditNotes'][0] + [
            'CombineAdditionalCharges' => false,
            'CreditNoteBalance' => 0,
            'Payments' => [
                [
                    'ID' => '20d5ff25-afa2-cd74-96d7-c7f0dd1fa1c1',
                    'Reference' => '',
                    'Amount' => 358,
                    'DatePaid' => '2017-11-23T00:00:00',
                    'Account' => '718',
                    'CurrencyRate' => 1,
                    'DateCreated' => '2017-11-22T06:58:21.8882229Z',
                ],
            ],
        ];
    }

    /**
     * The `{SaleID, CreditNotes}` envelope `sale/creditnote` answers with.
     *
     * @return array<string, mixed>
     */
    public static function saleCreditNotes(): array
    {
        return ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4', 'CreditNotes' => [self::saleCreditNotePartial()]];
    }

    /**
     * Sale Credit Note POST Model: the credit note's own fields plus `SaleID`.
     *
     * @return array<string, mixed>
     */
    public static function saleCreditNotePost(): array
    {
        $creditNote = self::sale()['CreditNotes'][0];
        unset($creditNote['TotalBeforeTax'], $creditNote['Tax'], $creditNote['Total']);

        return ['SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4'] + $creditNote;
    }

    /**
     * Sale Payment Line Partial Model, one payment of `sale/payment`.
     *
     * @return array<string, mixed>
     */
    public static function salePayment(): array
    {
        return [
            'ID' => '20d5ff25-afa2-cd74-96d7-c7f0dd1fa1c1',
            'SaleID' => '916ab4c0-6ccb-4c93-873d-0603859050e4',
            'TaskID' => '4733ba69-21c5-48f5-95e5-307aa9889747',
            'SaleOrderNumber' => 'SO-00001',
            'InvoiceNumber' => 'INV-00005',
            'CreditNoteNumber' => null,
            'Type' => 'Payment',
            'Reference' => 'BANK-1',
            'Amount' => 358,
            'DatePaid' => '2017-11-23T00:00:00',
            'Account' => '718',
            'CurrencyRate' => 1,
            'DateCreated' => '2017-11-22T06:58:21.8882229Z',
            'CreditID' => null,
        ];
    }

    /**
     * The `{Total, Page, MoneyTasks}` envelope `moneyTaskList` answers with, from the reference's example.
     *
     * @return array<string, mixed>
     */
    public static function moneyTaskList(): array
    {
        return [
            'Total' => 2,
            'Page' => 1,
            'MoneyTasks' => [
                [
                    'TaskID' => '07a2cdde-6a04-4925-be45-538e3a88dd7a',
                    'Date' => '2018-01-17T00:00:00',
                    'TaskType' => 'Receive Money',
                    'Status' => 'COMPLETED',
                    'SupplierCustomerName' => '',
                    'SupplierID' => null,
                    'CustomerID' => null,
                    'Reference' => '',
                    'TotalAmount' => 6,
                ],
                [
                    'TaskID' => 'ded51119-ec29-4fcd-b43b-4c63341f188e',
                    'Date' => '2018-01-17T00:00:00',
                    'TaskType' => 'Spend Money',
                    'Status' => 'COMPLETED',
                    'SupplierCustomerName' => 'Bayside Wholesale',
                    'SupplierID' => 'af09cddc-c4b0-47b2-8755-78edac52e920',
                    'CustomerID' => null,
                    'Reference' => 'Test 1',
                    'TotalAmount' => 1.8,
                ],
            ],
        ];
    }

    /**
     * Money Task, copied from the reference's `moneyOperation` GET example.
     *
     * @return array<string, mixed>
     */
    public static function moneyTask(): array
    {
        return [
            'TaskID' => 'c1aa3e7b-b085-4ef1-bec0-680505f97491',
            'TaskType' => 'Receive Money',
            'Status' => 'COMPLETED',
            'BankAccount' => '718',
            'CurrencyConversionRate' => 1,
            'SupplierCustomer' => 'Bayside Club',
            'SupplierID' => '71ada099-cc93-4aae-8c12-670d04587db3',
            'CustomerID' => null,
            'Reference' => 'Test',
            'Date' => '2018-01-17T00:00:00',
            'TaxInclusive' => true,
            'Note' => 'Note',
            'Lines' => [
                [
                    'Name' => 'Bread Test 1:Baked Bread Test 1',
                    'Comment' => '',
                    'Quantity' => 1,
                    'Price' => 2,
                    'Discount' => 0,
                    'Tax' => 0.18,
                    'TaxRuleName' => 'Tax Exempt',
                    'AccountCode' => '801',
                    'Total' => 2,
                ],
            ],
            'Transactions' => [
                [
                    'ID' => '5fae73f7-4b46-4c3d-b205-af6fe6ce17be',
                    'EffectiveDate' => '2018-01-17T00:00:00',
                    'Debit' => '718',
                    'Credit' => '801',
                    'Amount' => 1.82,
                ],
                [
                    'ID' => 'ee11d63c-78f4-4322-99c6-dc2884e6ede5',
                    'EffectiveDate' => '2018-01-17T00:00:00',
                    'Debit' => '718',
                    'Credit' => '820',
                    'Amount' => 0.18,
                ],
            ],
            'Attachments' => [
                [
                    'ID' => '0f1e7c3a-5b6d-4e8f-9a0b-1c2d3e4f5a6b',
                    'ContentType' => 'application/pdf',
                    'IsDefault' => false,
                    'FileName' => 'receipt.pdf',
                    'DownloadUrl' => 'https://example.test/receipt.pdf',
                ],
            ],
        ];
    }

    /**
     * The body of the 503 Cin7 returns when throttling; it carries no `Retry-After` header.
     *
     * @return array<string, mixed>
     */
    public static function throttled(): array
    {
        return ['Errors' => ['Service Unavailable']];
    }

    /**
     * The body Cin7 returns for a rejected request.
     *
     * @return array<string, mixed>
     */
    public static function error(string $message = 'Request is invalid'): array
    {
        return ['Errors' => [$message]];
    }
}
