<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Supplier;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Supplier\SupplierData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT supplier`, body is a `SupplierPutData` and carries `ID`; the response is the saved
 * Supplier. `LastModifiedOn` is the date Cin7 stamps on a change, so it is left out of an array
 * body.
 *
 * @extends WriteRequest<SupplierData>
 */
final class PutSupplier extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = SupplierData::class;

    protected ?string $dtoPath = 'SupplierList.0';

    /**
     * @var list<string>
     */
    protected array $omit = ['LastModifiedOn'];

    public function resolveEndpoint(): string
    {
        return 'supplier';
    }
}
