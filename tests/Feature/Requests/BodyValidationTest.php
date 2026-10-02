<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Requests;

use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockClient;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Hypervel\Validation\ValidationException;
use Ipsocode\Cin7\Data\Product\ProductData;
use Ipsocode\Cin7\Data\Sale\Invoice\SaleInvoicePostData;
use Ipsocode\Cin7\Data\Sale\Payment\SalePaymentPostData;
use Ipsocode\Cin7\Data\Sale\SalePostPutData;
use Ipsocode\Cin7\Requests\Product\PostProduct;
use Ipsocode\Cin7\Requests\Sale\Invoice\PostSaleInvoice;
use Ipsocode\Cin7\Requests\Sale\Payment\PostSalePayment;
use Ipsocode\Cin7\Requests\Sale\PostSale;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Workbench\App\Support\Cin7Payloads;

/**
 * A data object body is checked against its class's rules, the reference's lengths, GUIDs and
 * dates among them, after its nulls and omitted fields are removed and before anything is sent.
 *
 * @see docs/data.md
 */
class BodyValidationTest extends TestCase
{
    private MockClient $mock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mock = Saloon::fake(array_fill(0, 4, MockResponse::make(Cin7Payloads::salePayment())));
    }

    public function testAValidBodyIsSent(): void
    {
        $this->connector()->send(new PostSalePayment(SalePaymentPostData::from(Cin7Payloads::salePaymentPost())));

        $this->mock->assertSentCount(1);
    }

    /**
     * @param array<string, mixed> $changes
     */
    #[DataProvider('invalidPaymentProvider')]
    public function testAPaymentBreakingARuleIsNotSent(array $changes, string $field): void
    {
        $body = SalePaymentPostData::from(array_replace(Cin7Payloads::salePaymentPost(), $changes));

        try {
            $this->connector()->send(new PostSalePayment($body));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame([$field], array_keys($exception->errors()));
        }

        $this->mock->assertNothingSent();
    }

    /**
     * @return array<string, array{array<string, mixed>, string}>
     */
    public static function invalidPaymentProvider(): array
    {
        return [
            'a TaskID that is not a GUID' => [['TaskID' => 'task-1'], 'TaskID'],
            'a DatePaid without a time' => [['DatePaid' => '2017-11-30'], 'DatePaid'],
            'a DatePaid with an offset' => [['DatePaid' => '2017-11-30T00:00:00+10:00'], 'DatePaid'],
            'an impossible DatePaid' => [['DatePaid' => '2017-13-45T00:00:00'], 'DatePaid'],
        ];
    }

    /**
     * Cin7's DateTime is `yyyy-MM-ddTHH:mm:ss`, with an optional fraction of up to seven digits and
     * an optional `Z`; the nil GUID is a GUID.
     */
    #[DataProvider('validDateProvider')]
    public function testCin7DateTimesAndTheNilGuidPass(string $date): void
    {
        $body = SalePaymentPostData::from(['TaskID' => '00000000-0000-0000-0000-000000000000', 'DatePaid' => $date] + Cin7Payloads::salePaymentPost());

        $this->connector()->send(new PostSalePayment($body));

        $this->mock->assertSentCount(1);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function validDateProvider(): array
    {
        return [
            'seconds' => ['2017-11-30T00:00:00'],
            'milliseconds' => ['2012-11-14T13:28:33.363'],
            'seven fraction digits and Z' => ['2017-11-22T06:58:21.8882229Z'],
        ];
    }

    /**
     * The Length column: a field one character longer than the reference allows fails, at any
     * depth, and one at the limit passes.
     */
    public function testALengthOverTheReferencesLimitFailsAtAnyDepth(): void
    {
        $invoice = Cin7Payloads::saleInvoicePost();
        $invoice['Memo'] = str_repeat('m', 1024);
        $invoice['BillingAddressLine1'] = str_repeat('a', 257);
        $invoice['Lines'][0]['SKU'] = str_repeat('s', 51);

        try {
            $this->connector()->send(new PostSaleInvoice(SaleInvoicePostData::from($invoice)));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['BillingAddressLine1', 'Lines.0.SKU'], array_keys($exception->errors()));
        }

        $this->mock->assertNothingSent();
    }

    /**
     * A sale needs a customer, by name or by ID; a body with neither is not sent.
     */
    public function testASaleWithoutACustomerIsNotSent(): void
    {
        try {
            $this->connector()->send(new PostSale(SalePostPutData::from(['Location' => 'Main Warehouse', 'CurrencyRate' => 1])));
            $this->fail('The body should have failed validation.');
        } catch (ValidationException $exception) {
            $this->assertSame(['Customer', 'CustomerID'], array_keys($exception->errors()));
        }

        $this->mock->assertNothingSent();
    }

    public function testEitherCustomerFieldIsEnoughForASale(): void
    {
        $this->connector()->send(new PostSale(SalePostPutData::from(['Customer' => 'ACME', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1])));
        $this->connector()->send(new PostSale(SalePostPutData::from(['CustomerID' => '6c18f8e9-90e1-418f-aebc-1219e67e4b9c', 'Location' => 'Main Warehouse', 'CurrencyRate' => 1])));

        $this->mock->assertSentCount(2);
    }

    /**
     * Validation runs on what is sent: a field the request leaves out is not checked.
     */
    public function testAnOmittedFieldIsNotValidated(): void
    {
        $this->connector()->send(new PostProduct(ProductData::from(['ID' => 'not-a-guid', 'SKU' => 'Bread'])));

        $this->assertSame(['SKU' => 'Bread'], $this->mock->lastPendingRequest()?->body());
    }

    /**
     * An array body is sent as given, so it is not validated.
     */
    public function testAnArrayBodyIsNotValidated(): void
    {
        $this->connector()->send(new PostSalePayment(['TaskID' => 'task-1', 'DatePaid' => 'tomorrow']));

        $this->assertSame(['TaskID' => 'task-1', 'DatePaid' => 'tomorrow'], $this->mock->lastPendingRequest()?->body());
    }
}
