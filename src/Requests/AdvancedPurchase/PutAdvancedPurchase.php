<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\AdvancedPurchase\AdvancedPurchaseData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT advanced-purchase`, body is an `AdvancedPurchasePutData` or an array and carries `ID`; the
 * response is the saved purchase. `PurchaseType` is available only on POST, so it is left out of
 * an array body.
 *
 * @extends WriteRequest<AdvancedPurchaseData>
 */
final class PutAdvancedPurchase extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = AdvancedPurchaseData::class;

    /**
     * @var list<string>
     */
    protected array $omit = ['PurchaseType'];

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase';
    }
}
