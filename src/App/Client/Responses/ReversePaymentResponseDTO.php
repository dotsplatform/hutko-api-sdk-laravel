<?php
/**
 * Description of ReversePaymentResponseDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Responses;

use Dots\Hutko\App\Client\Resources\Consts\ReverseStatus;

class ReversePaymentResponseDTO extends HutkoResponseDTO
{
    protected ?string $order_id;

    protected ?string $reverse_status;

    public function getOrderId(): ?string
    {
        return $this->order_id;
    }

    public function getReverseStatus(): ?string
    {
        return $this->reverse_status;
    }

    public function getReverseStatusEnum(): ?ReverseStatus
    {
        return ReverseStatus::tryFrom($this->reverse_status ?? '');
    }

    public function isApproved(): bool
    {
        return $this->getReverseStatus() === ReverseStatus::APPROVED->value;
    }

    public function isErrorResponse(): bool
    {
        if (! $this->isApproved()) {
            return true;
        }

        return parent::isErrorResponse();
    }
}
