<?php

/**
 * Description of HutkoWebhookDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Resources;

use Dots\Data\DTO;
use Dots\Hutko\App\Client\Resources\Consts\CaptureStatus;
use Dots\Hutko\App\Client\Resources\Consts\OrderStatus;

class HutkoWebhookDTO extends DTO
{
    protected ?string $order_id;

    protected ?string $payment_id;

    protected ?string $rrn;

    protected ?string $masked_card;

    protected ?string $sender_cell_phone;

    protected ?string $order_status;

    protected ?string $sender_account;

    protected ?string $fee;

    protected ?string $actual_amount;

    protected ?string $card_bin;

    protected ?string $card_type;

    protected ?string $amount;

    protected ?string $capture_status;

    protected ?string $response_status;

    protected ?string $signature;

    protected ?string $merchant_id;

    protected ?string $currency;

    protected ?string $merchant_data;

    public function getOrderId(): ?string
    {
        return $this->order_id;
    }

    public function getPaymentId(): ?string
    {
        return $this->payment_id;
    }

    public function getRrn(): ?string
    {
        return $this->rrn;
    }

    public function getMaskedCard(): ?string
    {
        return $this->masked_card;
    }

    public function getOrderStatus(): ?string
    {
        return $this->order_status;
    }

    public function getOrderStatusEnum(): ?OrderStatus
    {
        return OrderStatus::tryFrom($this->order_status ?? '');
    }

    public function getFee(): ?float
    {
        $fee = $this->fee;
        if (! $fee) {
            return null;
        }

        $fee = (float) $fee;
        if (! $fee) {
            return null;
        }

        return $fee;
    }

    public function getActualAmount(): int
    {
        return (int) $this->actual_amount;
    }

    public function getAmount(): int
    {
        return (int) $this->amount;
    }

    public function getCaptureStatus(): ?string
    {
        return $this->capture_status;
    }

    public function getCaptureStatusEnum(): ?CaptureStatus
    {
        return CaptureStatus::tryFrom($this->capture_status ?? '');
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function getMerchantId(): ?string
    {
        return $this->merchant_id;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function getMerchantData(): ?string
    {
        return $this->merchant_data;
    }

    public function isApproved(): bool
    {
        return $this->getOrderStatus() === OrderStatus::APPROVED->value;
    }

    public function isCaptured(): bool
    {
        return $this->getCaptureStatus() === CaptureStatus::CAPTURED->value;
    }

    public function isExpired(): bool
    {
        return $this->getOrderStatus() === OrderStatus::EXPIRED->value;
    }

    public function isDeclined(): bool
    {
        return $this->getOrderStatus() === OrderStatus::DECLINED->value;
    }

    public function isFailed(): bool
    {
        return $this->isExpired() || $this->isDeclined();
    }
}
