<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Production;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductFamilyProductionBomPostData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductFamilyProductionBomPutData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductProductionBomPostData;
use Ipsocode\Cin7\Data\Production\ProductionBom\ProductProductionBomPutData;
use Ipsocode\Cin7\Requests\Production\ProductionBom\DeleteProductFamilyProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\DeleteProductProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\GetProductFamilyProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\GetProductProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\PostProductFamilyProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\PostProductProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\PutProductFamilyProductionBom;
use Ipsocode\Cin7\Requests\Production\ProductionBom\PutProductProductionBom;

/**
 * `production/productionBOM`, the productionBom resource.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class ProductionBomResource extends BaseResource
{
    /**
     * The production BOMs of a product.
     *
     * @param null|bool $returnAttachmentsContent return the attachments' content
     */
    public function getProduct(string $productId, ?bool $returnAttachmentsContent = null): Response
    {
        return $this->connector->send(new GetProductProductionBom($productId, $returnAttachmentsContent));
    }

    /**
     * @param array<string, mixed>|ProductProductionBomPostData $body
     */
    public function postProduct(array|ProductProductionBomPostData $body): Response
    {
        return $this->connector->send(new PostProductProductionBom($body));
    }

    /**
     * @param array<string, mixed>|ProductProductionBomPutData $body
     */
    public function putProduct(array|ProductProductionBomPutData $body): Response
    {
        return $this->connector->send(new PutProductProductionBom($body));
    }

    /**
     * Deletes a production BOM of a product; the response is not documented.
     */
    public function deleteProduct(string $productId, string $bomid): Response
    {
        return $this->connector->send(new DeleteProductProductionBom($productId, $bomid));
    }

    /**
     * The production BOMs of a product family.
     *
     * @param null|bool $returnAttachmentsContent return the attachments' content
     */
    public function getProductFamily(string $productFamilyId, ?bool $returnAttachmentsContent = null): Response
    {
        return $this->connector->send(new GetProductFamilyProductionBom($productFamilyId, $returnAttachmentsContent));
    }

    /**
     * @param array<string, mixed>|ProductFamilyProductionBomPostData $body
     */
    public function postProductFamily(array|ProductFamilyProductionBomPostData $body): Response
    {
        return $this->connector->send(new PostProductFamilyProductionBom($body));
    }

    /**
     * @param array<string, mixed>|ProductFamilyProductionBomPutData $body
     */
    public function putProductFamily(array|ProductFamilyProductionBomPutData $body): Response
    {
        return $this->connector->send(new PutProductFamilyProductionBom($body));
    }

    /**
     * Deletes a production BOM of a product family; the response is not documented.
     */
    public function deleteProductFamily(string $productFamilyId, string $bomid): Response
    {
        return $this->connector->send(new DeleteProductFamilyProductionBom($productFamilyId, $bomid));
    }
}
