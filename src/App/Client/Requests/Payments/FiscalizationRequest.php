<?php
/**
 * Description of FiscalizationRequest.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Requests\Payments;

use Dots\Hutko\App\Client\Requests\PostHutkoRequest;
use Saloon\Http\Response;

class FiscalizationRequest extends PostHutkoRequest
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
        return '/api/get_kkt_logs';
    }

    public function createDtoFromResponse(Response $response): array
    {
        return $response->json() ?? [];
    }
}
