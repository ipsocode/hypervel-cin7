<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\Purchase\Payment;

use Hypervel\Saloon\Enums\Method;
use Ipsocode\Cin7\Data\Purchase\Payment\PurchasePaymentData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT purchase/payment`, body is a `PurchasePaymentPutData` or an array carrying `ID`; the
 * response is the saved payment. `Type` and `DepositID` are available only for POST, and
 * `DateCreated` is the date Cin7 stamps on the record, so all three are left out of the body.
 *
 * @extends WriteRequest<PurchasePaymentData>
 */
final class PutPurchasePayment extends WriteRequest
{
    protected Method $method = Method::PUT;

    protected ?string $dto = PurchasePaymentData::class;

    /**
     * @var list<string>
     */
    protected array $omit = ['Type', 'DepositID', 'DateCreated'];

    public function resolveEndpoint(): string
    {
        return 'purchase/payment';
    }
}
