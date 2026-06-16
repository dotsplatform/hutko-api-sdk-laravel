<?php

/**
 * Description of PaymentResponseDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Responses;

use Dots\Hutko\App\Client\Resources\Consts\CaptureStatus;
use Dots\Hutko\App\Client\Resources\Consts\OrderStatus;

class PaymentResponseDTO extends HutkoResponseDTO
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

    protected ?array $transaction;

    protected ?string $card_bin;

    protected ?string $card_type;

    protected ?string $amount;

    protected ?string $capture_status;

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

    public function getSenderCellPhone(): ?string
    {
        return $this->sender_cell_phone;
    }

    public function getOrderStatus(): ?string
    {
        return $this->order_status;
    }

    public function getOrderStatusEnum(): ?OrderStatus
    {
        return OrderStatus::tryFrom($this->order_status ?? '');
    }

    public function getSenderAccount(): ?string
    {
        return $this->sender_account;
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

    public function getTransaction(): ?array
    {
        return $this->transaction;
    }

    public function getCardBin(): ?string
    {
        return $this->card_bin;
    }

    public function getCardType(): ?string
    {
        return $this->card_type;
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

    public function isProcessing(): bool
    {
        return $this->getOrderStatus() === OrderStatus::PROCESSING->value;
    }

    public function isFailed(): bool
    {
        return $this->isExpired() || $this->isDeclined();
    }
}
