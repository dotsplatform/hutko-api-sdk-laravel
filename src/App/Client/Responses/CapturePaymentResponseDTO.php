<?php

/**
 * Description of CapturePaymentResponseDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Responses;

use Dots\Hutko\App\Client\Resources\Consts\CaptureStatus;

class CapturePaymentResponseDTO extends HutkoResponseDTO
{
    protected ?string $order_id;

    protected ?string $capture_status;

    public function getOrderId(): ?string
    {
        return $this->order_id;
    }

    public function getCaptureStatus(): ?string
    {
        return $this->capture_status;
    }

    public function getCaptureStatusEnum(): ?CaptureStatus
    {
        return CaptureStatus::tryFrom($this->capture_status ?? '');
    }

    public function isCaptured(): bool
    {
        return $this->getCaptureStatus() === CaptureStatus::CAPTURED->value;
    }

    public function isErrorResponse(): bool
    {
        if (! $this->isCaptured()) {
            return true;
        }

        return parent::isErrorResponse();
    }
}
