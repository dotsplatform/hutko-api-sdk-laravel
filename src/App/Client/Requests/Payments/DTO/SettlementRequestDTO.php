<?php
/**
 * Description of SettlementRequestDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Requests\Payments\DTO;

use Dots\Data\DTO;

class SettlementRequestDTO extends DTO
{
    protected string $order_id;

    protected string $merchant_id;

    protected string $order_type;

    protected string $amount;

    protected string $currency;

    protected array $receiver;

    protected ?string $reservation_data;

    public function getOrderId(): string
    {
        return $this->order_id;
    }

    public function getMerchantId(): string
    {
        return $this->merchant_id;
    }

    public function getOrderType(): string
    {
        return $this->order_type;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getReceiver(): array
    {
        return $this->receiver;
    }

    public function getReservationData(): ?string
    {
        return $this->reservation_data;
    }
}
