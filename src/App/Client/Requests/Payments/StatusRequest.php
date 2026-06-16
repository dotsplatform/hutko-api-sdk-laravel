<?php

/**
 * Description of StatusRequest.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Requests\Payments;

use Dots\Hutko\App\Client\Requests\PostHutkoRequest;
use Dots\Hutko\App\Client\Responses\PaymentResponseDTO;
use Saloon\Http\Response;

class StatusRequest extends PostHutkoRequest
{
    public function __construct(
        private readonly array $requestData,
    ) {}

    protected function defaultBody(): array
    {
        return $this->requestData;
    }

    public function resolveEndpoint(): string
    {
        return '/api/status/order_id';
    }

    public function createDtoFromResponse(Response $response): PaymentResponseDTO
    {
        return PaymentResponseDTO::fromResponse($response);
    }
}
