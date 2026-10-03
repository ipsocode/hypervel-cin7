<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\Payment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\Payment\AdvancedPurchasePaymentData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `PUT advanced-purchase/payment`, body is an `AdvancedPurchasePaymentPutData` or an array carrying
 * `ID`; the response is the saved payment. `Type` and `DepositID` are available only for POST, and
 * `DateCreated` is the date Cin7 stamps on the record, so all three are left out of the body.
 *
 * @extends WriteRequest<AdvancedPurchasePaymentData>
 */
final class PutAdvancedPurchasePayment extends WriteRequest
{
    protected Method $method = Method::PUT;

    /**
     * @var list<string>
     */
    protected array $omit = ['Type', 'DepositID', 'DateCreated'];

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase/payment';
    }

    public function createDtoFromResponse(Response $response): AdvancedPurchasePaymentData
    {
        return AdvancedPurchasePaymentData::from($response->json())->setResponse($response);
    }
}
