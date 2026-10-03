<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Tests\Resources;
use Ipsocode\Cin7\Tests\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

/**
 * Every accessor on the connector, and on each resource below it, returns its resource class,
 * fresh on each call so the connector singleton stays coroutine-safe. The accessor tree is walked
 * from the connector by reflection, so a new accessor is covered when it is added.
 *
 * @see docs/resources.md
 */
class ConnectorResourcesTest extends TestCase
{
    public function testEveryAccessorReturnsAFreshResource(): void
    {
        $reached = [];

        $this->walk($this->connector(), $reached);

        $this->assertNotEmpty($reached);
    }

    /**
     * An accessor that no path from the connector reaches is a resource nobody can call.
     */
    public function testEveryResourceIsReachedFromTheConnector(): void
    {
        $reached = [];

        $this->walk($this->connector(), $reached);

        $reached = array_keys($reached);
        sort($reached);

        $this->assertSame(Resources::classes(), $reached);
    }

    /**
     * Call each accessor twice, assert it returns its declared resource class, a new instance each
     * time, and walk into what it returned.
     *
     * @param array<class-string<BaseResource>, true> $reached
     */
    private function walk(object $parent, array &$reached): void
    {
        foreach (new ReflectionClass($parent)->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $returns = $method->getReturnType();

            if ($method->isStatic()
                || $method->getNumberOfParameters() > 0
                || ! $returns instanceof ReflectionNamedType
                || ! is_a($returns->getName(), BaseResource::class, true)) {
                continue;
            }

            $first = $method->invoke($parent);
            $second = $method->invoke($parent);

            $this->assertInstanceOf($returns->getName(), $first, $method->getName());
            $this->assertNotSame($first, $second, $method->getName());

            $reached[$first::class] = true;

            $this->walk($first, $reached);
        }
    }
}
