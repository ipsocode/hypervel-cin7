<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Requests;

use Closure;
use Hypervel\Saloon\Facades\Saloon;
use Hypervel\Saloon\Http\Faking\MockResponse;
use Ipsocode\Cin7\Requests\Cin7Request;
use Ipsocode\Cin7\Requests\Crm\WorkflowStart\PostCrmWorkflowStart;
use Ipsocode\Cin7\Requests\CustomPrices\DeleteCustomPrices;
use Ipsocode\Cin7\Requests\CustomPrices\PostCustomPrices;
use Ipsocode\Cin7\Requests\CustomPrices\PutCustomPrices;
use Ipsocode\Cin7\Requests\ProductSuppliers\DeleteProductSuppliers;
use Ipsocode\Cin7\Requests\ProductSuppliers\PostProductSuppliers;
use Ipsocode\Cin7\Requests\ProductSuppliers\PutProductSuppliers;
use Ipsocode\Cin7\Requests\Ref\Carrier\DeleteCarrier;
use Ipsocode\Cin7\Requests\Ref\Location\DeleteLocation;
use Ipsocode\Cin7\Requests\Reference\ShipZones\DeleteShipZones;
use Ipsocode\Cin7\Requests\Webhooks\DeleteWebhooks;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Workbench\App\Support\Cin7Payloads;

/**
 * The requests whose response is only `{Success}`, `{Errors}` or an empty list have no data
 * object: `json()` reads the reference's example and `dto()` is null.
 *
 * @see src/docs/requests.md
 */
class UnstructuredResponseTest extends TestCase
{
    /**
     * @param Closure(): Cin7Request<null> $request
     */
    #[DataProvider('requests')]
    public function testTheExampleResponseIsLeftToJsonAndHasNoDataObject(Closure $request, string $path, string $name): void
    {
        $example = Cin7Payloads::load($path, $name);

        Saloon::fake([MockResponse::make($example)]);

        $response = $this->connector()->send($request());

        $this->assertSame($example, $response->json());
        $this->assertNull($response->dto());
    }

    /**
     * @return array<string, array{Closure(): Cin7Request<null>, string, string}>
     */
    public static function requests(): array
    {
        $id = '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1';

        return [
            'crm/workflowstart' => [fn (): Cin7Request => new PostCrmWorkflowStart('2021-02-22T00:00:00', 'Lead', $id, name: 'Welcome'), 'crm/workflowstart', 'post.response'],
            'custom-prices post' => [fn (): Cin7Request => new PostCustomPrices(Cin7Payloads::load('custom-prices', 'post.request')), 'custom-prices', 'post.response'],
            'custom-prices put' => [fn (): Cin7Request => new PutCustomPrices(Cin7Payloads::load('custom-prices', 'put.request')), 'custom-prices', 'put.response'],
            'custom-prices delete' => [fn (): Cin7Request => new DeleteCustomPrices($id, $id), 'custom-prices', 'delete.response'],
            'product-suppliers post' => [fn (): Cin7Request => new PostProductSuppliers(Cin7Payloads::load('product-suppliers', 'post.request')), 'product-suppliers', 'post.response'],
            'product-suppliers put' => [fn (): Cin7Request => new PutProductSuppliers(Cin7Payloads::load('product-suppliers', 'put.request')), 'product-suppliers', 'put.response'],
            'product-suppliers delete' => [fn (): Cin7Request => new DeleteProductSuppliers($id, $id), 'product-suppliers', 'delete.response'],
            'ref/carrier delete' => [fn (): Cin7Request => new DeleteCarrier($id), 'ref/carrier', 'delete.response'],
            'ref/location delete' => [fn (): Cin7Request => new DeleteLocation($id), 'ref/location', 'delete.response'],
            'reference/shipZones delete' => [fn (): Cin7Request => new DeleteShipZones($id), 'reference/shipZones', 'delete.response'],
            'webhooks delete' => [fn (): Cin7Request => new DeleteWebhooks($id), 'webhooks', 'delete.response'],
        ];
    }

    public function testTheWorkflowStartExampleSendsNoBody(): void
    {
        $this->assertSame([], Cin7Payloads::load('crm/workflowstart', 'post.request'));

        $mock = Saloon::fake([MockResponse::make(Cin7Payloads::load('crm/workflowstart', 'post.response'))]);

        $this->connector()->send(new PostCrmWorkflowStart('2021-02-22T00:00:00', 'Lead', '0365e5bb-e5ea-4a45-b98b-fdc4466bdaf1'));

        $this->assertNull($mock->lastPendingRequest()?->body());
    }
}
