<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Connector;

use Hypervel\Saloon\Exceptions\Request\ClientException;
use Hypervel\Saloon\Exceptions\Request\RequestException;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Ipsocode\Cin7\Data\Other\ErrorData;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Requests\Sale\Payment\DeleteSalePayment;
use Ipsocode\Cin7\Requests\Sale\Payment\GetSalePayment;
use Ipsocode\Cin7\Tests\TestCase;
use Workbench\App\Support\Cin7Payloads;

/**
 * A body carrying Cin7's Error Model, `{ErrorCode, Exception}`, fails the request whatever its
 * status, so `json()` and `dto()` never see it.
 *
 * @see docs/requests.md
 */
class ErrorModelTest extends TestCase
{
    public function testAnErrorModelInA200Throws(): void
    {
        Saloon::fake([MockResponse::make(Cin7Payloads::error('Customer not found', 404))]);

        try {
            $this->connector()->send(new GetCustomer);
            $this->fail('An Error Model body should have thrown.');
        } catch (RequestException $exception) {
            $this->assertNotInstanceOf(ClientException::class, $exception);
            $this->assertSame(200, $exception->status());
            $this->assertSame(
                ['ErrorCode' => 404, 'Exception' => 'Customer not found'],
                ErrorData::from($exception->response()->json())->toArray(),
            );
        }
    }

    /**
     * Cin7 can answer with a list of errors; a list starting with one fails too.
     */
    public function testAListOfErrorsInA200Throws(): void
    {
        Saloon::fake([MockResponse::make([Cin7Payloads::error()])]);

        $this->expectException(RequestException::class);

        $this->connector()->send(new GetSalePayment('sale-1'));
    }

    public function testAnErrorModelWithAClientErrorStatusKeepsItsExceptionType(): void
    {
        Saloon::fake([MockResponse::make(Cin7Payloads::error(), 400)]);

        $this->expectException(ClientException::class);

        $this->connector()->send(new GetCustomer);
    }

    public function testAListOfRecordsIsNotAnError(): void
    {
        Saloon::fake([MockResponse::make(Cin7Payloads::salePayments())]);

        $payments = $this->connector()->send(new GetSalePayment('sale-1'))->dto();

        $this->assertCount(2, $payments);
    }

    public function testAnEmptyListIsNotAnError(): void
    {
        Saloon::fake([MockResponse::make([])]);

        $this->assertSame([], $this->connector()->send(new GetSalePayment('sale-1'))->dto());
    }

    public function testABodyThatIsNotJsonIsLeftToTheStatus(): void
    {
        Saloon::fake([MockResponse::make('', 204)]);

        $this->assertSame(204, $this->connector()->send(new DeleteSalePayment('pay-1'))->status());
    }
}
