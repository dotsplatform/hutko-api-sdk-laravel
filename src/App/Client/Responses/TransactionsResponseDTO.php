<?php

/**
 * Description of TransactionsResponseDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Responses;

use Dots\Hutko\App\Client\Helpers\ResponseParser;
use Dots\Hutko\App\Client\Resources\PaymentTransactionDTO;
use Dots\Hutko\App\Client\Resources\PaymentTransactions;
use Saloon\Http\Response;

class TransactionsResponseDTO extends HutkoResponseDTO
{
    protected ?PaymentTransactions $paymentTransactions;

    public static function fromResponse(Response $response): static
    {
        $data = $response->json();
        if (! is_array($data)) {
            $data = [];
        }

        $parsed = ResponseParser::parse($data);

        $transactions = null;
        if (! empty($parsed[0]['id'])) {
            $transactions = PaymentTransactions::fromArray($parsed);
        }

        return static::fromArray(array_merge([
            'paymentTransactions' => $transactions,
        ], $parsed));
    }

    public function getPaymentTransactions(): ?PaymentTransactions
    {
        return $this->paymentTransactions;
    }
}
