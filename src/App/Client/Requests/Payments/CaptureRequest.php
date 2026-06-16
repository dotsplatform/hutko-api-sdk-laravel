<?php

/**
 * Description of CaptureRequest.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Requests\Payments;

use Dots\Hutko\App\Client\Requests\PostHutkoRequest;
use Dots\Hutko\App\Client\Responses\CapturePaymentResponseDTO;
use Saloon\Http\Response;

class CaptureRequest extends PostHutkoRequest
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
        return '/api/capture/order_id';
    }

    public function createDtoFromResponse(Response $response): CapturePaymentResponseDTO
    {
        return CapturePaymentResponseDTO::fromResponse($response);
    }
}
