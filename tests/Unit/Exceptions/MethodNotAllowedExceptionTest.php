<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Unit\Exceptions;

use Exception;
use Hypervel\Foundation\Testing\Attributes\UnitTest;
use Hypervel\Saloon\Exceptions\SaloonException;
use Ipsocode\Cin7\Exceptions\MethodNotAllowedException;
use Ipsocode\Cin7\Tests\Unit\TestCase;
use RuntimeException;

/**
 * The exception carries no state of its own — its whole contract is what it can
 * be caught as. That is worth pinning: it replaces upstream's marker interfaces,
 * and a consumer guards a whole batch of Cin7 calls with one catch block.
 */
class MethodNotAllowedExceptionTest extends TestCase
{
    /**
     * A consumer already catching Saloon's own failures must catch this too —
     * it is raised on the same code path, just before the request goes out.
     */
    #[UnitTest]
    public function testItIsCatchableAsASaloonException(): void
    {
        $this->assertInstanceOf(SaloonException::class, new MethodNotAllowedException);
    }

    #[UnitTest]
    public function testItIsAPlainExceptionNotAnError(): void
    {
        // Distinct from Endpoint::fromAccessor()'s ValueError, which is an
        // Error: an unknown accessor is a programming mistake, an unsupported
        // verb is a request the caller can reasonably retry differently.
        $this->assertInstanceOf(Exception::class, new MethodNotAllowedException);
    }

    #[UnitTest]
    public function testItCarriesTheMessageTheGateBuilds(): void
    {
        $exception = new MethodNotAllowedException('Method [DELETE] is not allowed on the [customer] endpoint.');

        $this->assertSame('Method [DELETE] is not allowed on the [customer] endpoint.', $exception->getMessage());
    }

    #[UnitTest]
    public function testItPreservesCodeAndPreviousException(): void
    {
        $previous = new RuntimeException('root cause');

        $exception = new MethodNotAllowedException('boom', 42, $previous);

        $this->assertSame(42, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
