<?php
/**
 * Description of CaptureRequestDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Requests\Payments\DTO;

use Dots\Data\DTO;

class CaptureRequestDTO extends DTO
{
    protected string $order_id;

    protected string $merchant_id;

    protected string $amount;

    protected string $currency;

    protected ?string $comment;

    protected ?string $reservation_data;

    public function getOrderId(): string
    {
        return $this->order_id;
    }

    public function getMerchantId(): string
    {
        return $this->merchant_id;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function getReservationData(): ?string
    {
        return $this->reservation_data;
    }
}
