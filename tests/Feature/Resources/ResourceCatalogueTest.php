<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Resources;

use Hypervel\Saloon\Http\BaseResource;
use Ipsocode\Cin7\Resources\ListResource;
use Ipsocode\Cin7\Tests\Catalogue;
use Ipsocode\Cin7\Tests\Resources;
use Ipsocode\Cin7\Tests\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;

/**
 * One row per resource method, from the per-path files under `tests/Fixtures/Catalogue/`,
 * asserting on the request it builds and the PendingRequest it sends.
 *
 * @see src/docs/resources.md
 */
class ResourceCatalogueTest extends TestCase
{
    /**
     * Each resource method that sends a request has a row keyed `<resource path> <method>`, or
     * that key and a variant (`… with data`): `sale payment put` for `Sale\PaymentResource::put()`.
     * Accessors, which return a resource, are covered by ConnectorResourcesTest.
     */
    public function testEveryResourceMethodHasARow(): void
    {
        $keys = array_keys(Catalogue::rows('resources'));

        foreach (Resources::classes() as $class) {
            $segments = explode('\\', substr($class, strlen('Ipsocode\Cin7\Resources\\'), -strlen('Resource')));
            $path = implode(' ', array_map(lcfirst(...), $segments));

            foreach (new ReflectionClass($class)->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
                $returns = $method->getReturnType();

                if ($method->getDeclaringClass()->getName() !== $class
                    || ($returns instanceof ReflectionNamedType && is_a($returns->getName(), BaseResource::class, true))) {
                    continue;
                }

                $key = $path . ' ' . $method->getName();
                $covered = array_filter($keys, static fn (string $row): bool => $row === $key || str_starts_with($row, $key . ' '));

                $this->assertNotEmpty($covered, "No resource row for {$class}::{$method->getName()}() ('{$key}').");
            }
        }
    }

    /**
     * A list-only endpoint's resource, `Resources\\SaleListResource` or `Resources\\Production\\OrderListResource`,
     * sends and paginates through `ListResource`, whose helpers take only a `ListRequest`.
     */
    public function testEveryListResourceExtendsListResource(): void
    {
        $lists = array_filter(Resources::classes(), static fn (string $class): bool => str_ends_with($class, 'ListResource'));

        $this->assertNotEmpty($lists);

        foreach ($lists as $class) {
            $this->assertTrue(is_subclass_of($class, ListResource::class), "{$class} must extend ListResource.");
        }
    }
}
