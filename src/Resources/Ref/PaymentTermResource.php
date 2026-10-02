<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Resources\Ref;

use Hypervel\Saloon\Http\BaseResource;
use Hypervel\Saloon\Http\Response;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Data\Ref\PaymentTerm\PaymentTermPostData;
use Ipsocode\Cin7\Data\Ref\PaymentTerm\PaymentTermPutData;
use Ipsocode\Cin7\Enums\PaymentTermMethod;
use Ipsocode\Cin7\Pagination\Cin7Paginator;
use Ipsocode\Cin7\Requests\Ref\PaymentTerm\DeletePaymentTerm;
use Ipsocode\Cin7\Requests\Ref\PaymentTerm\GetPaymentTerm;
use Ipsocode\Cin7\Requests\Ref\PaymentTerm\PostPaymentTerm;
use Ipsocode\Cin7\Requests\Ref\PaymentTerm\PutPaymentTerm;

/**
 * `ref/paymentterm`, the payment terms.
 *
 * @extends BaseResource<Cin7Connector>
 */
final class PaymentTermResource extends BaseResource
{
    /**
     * One page of payment terms; without a page or limit, page 1 of 100.
     *
     * @param null|int $page the page, from 1
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the payment term with this ID
     * @param null|string $name only payment terms whose name starts with this
     * @param null|PaymentTermMethod $method only payment terms with this method
     * @param null|bool $isActive only active payment terms
     * @param null|bool $isDefault only the default payment term
     */
    public function get(
        ?int $page = null,
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?PaymentTermMethod $method = null,
        ?bool $isActive = null,
        ?bool $isDefault = null,
    ): Response {
        return $this->connector->send(new GetPaymentTerm($page, $limit, $id, $name, $method, $isActive, $isDefault));
    }

    /**
     * Every page of payment terms, fetched as they are walked; call `startPage()` on the paginator to
     * begin later.
     *
     * @param null|int $limit the page size, 1 to 1000
     * @param null|string $id only the payment term with this ID
     * @param null|string $name only payment terms whose name starts with this
     * @param null|PaymentTermMethod $method only payment terms with this method
     * @param null|bool $isActive only active payment terms
     * @param null|bool $isDefault only the default payment term
     */
    public function paginate(
        ?int $limit = null,
        ?string $id = null,
        ?string $name = null,
        ?PaymentTermMethod $method = null,
        ?bool $isActive = null,
        ?bool $isDefault = null,
    ): Cin7Paginator {
        return $this->connector->paginate(new GetPaymentTerm(null, $limit, $id, $name, $method, $isActive, $isDefault));
    }

    /**
     * @param array<string, mixed>|PaymentTermPostData $body
     */
    public function post(array|PaymentTermPostData $body): Response
    {
        return $this->connector->send(new PostPaymentTerm($body));
    }

    /**
     * @param array<string, mixed>|PaymentTermPutData $body
     */
    public function put(array|PaymentTermPutData $body): Response
    {
        return $this->connector->send(new PutPaymentTerm($body));
    }

    /**
     * Delete the payment term with this ID.
     */
    public function delete(string $id): Response
    {
        return $this->connector->send(new DeletePaymentTerm($id));
    }
}
