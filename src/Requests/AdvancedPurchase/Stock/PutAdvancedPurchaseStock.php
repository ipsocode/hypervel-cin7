<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\Stock;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\AdvancedPurchase\Stock\AdvancedPurchaseStocksData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT advanced-purchase/stock`, body is an `AdvancedPurchaseStockPutData` or an array carrying
 * `PurchaseID` and `TaskID`; it overwrites that stock receiving task, and the response is the
 * purchase's stock receiving tasks. A line's `Name` and `Received` are read-only, so they are left
 * out of the body.
 *
 * @extends WriteRequest<AdvancedPurchaseStocksData>
 */
final class PutAdvancedPurchaseStock extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = AdvancedPurchaseStocksData::class;

    /**
     * @var list<string>
     */
    protected array $omit = ['Lines.*.Name', 'Lines.*.Received'];

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase/stock';
    }
}
