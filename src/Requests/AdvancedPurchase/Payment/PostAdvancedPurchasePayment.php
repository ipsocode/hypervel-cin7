<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Requests\AdvancedPurchase\Payment;

use Hypervel\Saloon\Enums\Method;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Data\AdvancedPurchase\Payment\AdvancedPurchasePaymentData;
use Ipsocode\Cin7\Requests\WriteRequest;

/**
 * `POST advanced-purchase/payment`, body is an `AdvancedPurchasePaymentPostData` or an array; the
 * response is the saved payment. `ID` is available only for PUT, and `DateCreated` is the date Cin7
 * stamps on the record, so both are left out of the body.
 *
 * @extends WriteRequest<AdvancedPurchasePaymentData>
 */
final class PostAdvancedPurchasePayment extends WriteRequest
{
    protected Method $method = Method::POST;

    /**
     * @var list<string>
     */
    protected array $omit = ['ID', 'DateCreated'];

    public function resolveEndpoint(): string
    {
        return 'advanced-purchase/payment';
    }

    public function createDtoFromResponse(Response $response): AdvancedPurchasePaymentData
    {
        return AdvancedPurchasePaymentData::from($response->json())->setResponse($response);
    }
}
