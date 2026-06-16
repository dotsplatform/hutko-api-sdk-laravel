<?php

/**
 * Description of SettlementRequest.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Requests\Payments;

use Dots\Hutko\App\Client\Requests\PostHutkoRequest;
use Dots\Hutko\App\Client\Responses\SettlementPaymentResponseDTO;
use Saloon\Http\Response;

class SettlementRequest extends PostHutkoRequest
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
        return '/api/settlement';
    }

    public function createDtoFromResponse(Response $response): SettlementPaymentResponseDTO
    {
        return SettlementPaymentResponseDTO::fromResponse($response);
    }
}
