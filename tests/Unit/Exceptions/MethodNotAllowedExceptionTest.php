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
 * The exception carries no state of its own, so its contract is what it can be caught as.
 */
class MethodNotAllowedExceptionTest extends TestCase
{
    #[UnitTest]
    public function testItIsCatchableAsASaloonException(): void
    {
        $this->assertInstanceOf(SaloonException::class, new MethodNotAllowedException);
    }

    /**
     * Unlike `fromAccessor()`'s ValueError: an unsupported verb is a request the caller
     * can retry differently.
     */
    #[UnitTest]
    public function testItIsAPlainExceptionNotAnError(): void
    {
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
