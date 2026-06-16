<?php
/**
 * Description of RecurringRequest.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Requests\Payments;

use Dots\Hutko\App\Client\Requests\PostHutkoRequest;
use Dots\Hutko\App\Client\Responses\StartPaymentResponseDTO;
use Saloon\Http\Response;

class RecurringRequest extends PostHutkoRequest
{
    public function __construct(
        private readonly array $requestData,
    ) {
    }

    protected function defaultBody(): array
    {
        return $this->requestData;
    }

    public function resolveEndpoint(): string
    {
        return '/api/recurring/url';
    }

    public function createDtoFromResponse(Response $response): StartPaymentResponseDTO
    {
        return StartPaymentResponseDTO::fromResponse($response);
    }
}
