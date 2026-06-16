<?php

/**
 * Description of SettlementPaymentResponseDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Responses;

class SettlementPaymentResponseDTO extends HutkoResponseDTO
{
    protected ?string $order_status;

    protected ?string $merchant_data;

    protected ?int $merchant_id;

    protected ?string $operation_id;

    protected ?int $payment_id;

    protected ?string $order_id;

    protected ?string $order_type;

    protected ?string $settlement_date;

    protected ?string $response_description;

    protected ?string $reversal_amount;

    protected ?string $settlement_amount;

    protected ?string $settlement_currency;

    protected ?string $order_time;

    protected ?string $fee;

    protected ?array $transaction;

    public function getOrderStatus(): ?string
    {
        return $this->order_status;
    }

    public function getMerchantData(): ?string
    {
        return $this->merchant_data;
    }

    public function getMerchantId(): ?int
    {
        return $this->merchant_id;
    }

    public function getOperationId(): ?string
    {
        return $this->operation_id;
    }

    public function getPaymentId(): ?int
    {
        return $this->payment_id;
    }

    public function getOrderId(): ?string
    {
        return $this->order_id;
    }

    public function getOrderType(): ?string
    {
        return $this->order_type;
    }

    public function getSettlementDate(): ?string
    {
        return $this->settlement_date;
    }

    public function getResponseDescription(): ?string
    {
        return $this->response_description;
    }

    public function getReversalAmount(): ?string
    {
        return $this->reversal_amount;
    }

    public function getSettlementAmount(): ?string
    {
        return $this->settlement_amount;
    }

    public function getSettlementCurrency(): ?string
    {
        return $this->settlement_currency;
    }

    public function getOrderTime(): ?string
    {
        return $this->order_time;
    }

    public function getFee(): ?string
    {
        return $this->fee;
    }

    public function getTransaction(): ?array
    {
        return $this->transaction;
    }
}
