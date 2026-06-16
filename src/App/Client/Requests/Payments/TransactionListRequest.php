<?php
/**
 * Description of TransactionListRequest.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Requests\Payments;

use Dots\Hutko\App\Client\Requests\PostHutkoRequest;
use Dots\Hutko\App\Client\Responses\TransactionsResponseDTO;
use Saloon\Http\Response;

class TransactionListRequest extends PostHutkoRequest
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
        return '/api/transaction_list';
    }

    public function createDtoFromResponse(Response $response): TransactionsResponseDTO
    {
        return TransactionsResponseDTO::fromResponse($response);
    }
}
