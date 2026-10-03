<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Testing;

use Hypervel\Saloon\Exceptions\Request\RequestException;
use Hypervel\Saloon\Facades\Saloon;
use Ipsocode\Cin7\Requests\Customer\GetCustomer;
use Ipsocode\Cin7\Testing\Cin7Fake;
use Ipsocode\Cin7\Tests\TestCase;

/**
 * The builders an application fakes Cin7 with: each one's envelope, status and headers.
 *
 * @see docs/testing.md
 */
class Cin7FakeTest extends TestCase
{
    public function testAListCarriesCin7sEnvelopeAndTheItemCountAsItsTotal(): void
    {
        $fake = Cin7Fake::list('CustomerList', [['ID' => 'a'], ['ID' => 'b']]);

        $this->assertSame(200, $fake->status());
        $this->assertSame(
            ['Total' => 2, 'Page' => 1, 'CustomerList' => [['ID' => 'a'], ['ID' => 'b']]],
            $fake->body()->all(),
        );
    }

    public function testAListTakesItsPageAndTotal(): void
    {
        $fake = Cin7Fake::list('Products', [['ID' => 'a']], page: 2, total: 150);

        $this->assertSame(['Total' => 150, 'Page' => 2, 'Products' => [['ID' => 'a']]], $fake->body()->all());
    }

    public function testAnEmptyListIsAnEmptyPageOfTheNamedKey(): void
    {
        $this->assertSame(['Total' => 0, 'Page' => 1, 'CustomerList' => []], Cin7Fake::list('CustomerList')->body()->all());
    }

    public function testAListWithoutTotalSendsNoTotalKey(): void
    {
        $fake = Cin7Fake::listWithoutTotal('CustomerCredits', [['CreditID' => 'a']], page: 3);

        $this->assertSame(200, $fake->status());
        $this->assertSame(['Page' => 3, 'CustomerCredits' => [['CreditID' => 'a']]], $fake->body()->all());
        $this->assertSame(['Page' => 1, 'SupplierDeposits' => []], Cin7Fake::listWithoutTotal('SupplierDeposits')->body()->all());
    }

    public function testARecordIsTheBodyWithA200(): void
    {
        $fake = Cin7Fake::record(['ID' => 'a', 'Name' => 'ACME']);

        $this->assertSame(200, $fake->status());
        $this->assertSame(['ID' => 'a', 'Name' => 'ACME'], $fake->body()->all());
    }

    public function testAnErrorIsTheErrorModelWithTheCodeAsItsStatus(): void
    {
        $fake = Cin7Fake::error('Customer not found', 404);

        $this->assertSame(404, $fake->status());
        $this->assertSame(['ErrorCode' => 404, 'Exception' => 'Customer not found'], $fake->body()->all());
        $this->assertSame(400, Cin7Fake::error('Request is invalid')->status());
    }

    public function testAnErrorTakesAStatusOfItsOwn(): void
    {
        $fake = Cin7Fake::error('Customer not found', 404, status: 200);

        $this->assertSame(200, $fake->status());
        $this->assertSame(404, $fake->body()->get('ErrorCode'));
    }

    public function testThrottledIsA503WithNoRetryAfter(): void
    {
        $fake = Cin7Fake::throttled();

        $this->assertSame(503, $fake->status());
        $this->assertSame(['ErrorCode' => 503, 'Exception' => 'Service Unavailable'], $fake->body()->all());
        $this->assertSame([], $fake->headers());
    }

    public function testLimitReachedIsA429WithRetryAfterOnlyWhenGiven(): void
    {
        $bare = Cin7Fake::limitReached();
        $timed = Cin7Fake::limitReached(30);

        $this->assertSame(429, $bare->status());
        $this->assertSame(['ErrorCode' => 429, 'Exception' => 'You reached 60 calls per minute API limit'], $bare->body()->all());
        $this->assertSame([], $bare->headers());
        $this->assertSame(429, $timed->status());
        $this->assertSame(['Retry-After' => '30'], $timed->headers());
        $this->assertSame($bare->body()->all(), $timed->body()->all());
    }

    public function testCredentialsRejectedIsA403(): void
    {
        $fake = Cin7Fake::credentialsRejected();

        $this->assertSame(403, $fake->status());
        $this->assertSame(['ErrorCode' => 403, 'Exception' => 'Incorrect credentials!'], $fake->body()->all());
    }

    public function testAnErrorModelWithA200MakesTheConnectorThrow(): void
    {
        Saloon::fake([Cin7Fake::error('Customer not found', 404, status: 200)]);

        try {
            $this->connector()->send(new GetCustomer);
            $this->fail('An Error Model body should have thrown.');
        } catch (RequestException $exception) {
            $this->assertSame(200, $exception->status());
        }
    }
}
