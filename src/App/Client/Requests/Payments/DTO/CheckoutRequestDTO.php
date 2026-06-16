<?php

/**
 * Description of CheckoutRequestDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Requests\Payments\DTO;

use Dots\Data\DTO;

class CheckoutRequestDTO extends DTO
{
    protected string $order_id;

    protected string $merchant_id;

    protected string $order_desc;

    protected string $amount;

    protected string $currency;

    protected ?string $server_callback_url;

    protected ?string $response_url;

    protected ?string $rectoken;

    protected ?string $preauth;

    protected ?string $merchant_data;

    protected ?int $lifetime;

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

    public function getServerCallbackUrl(): ?string
    {
        return $this->server_callback_url;
    }

    public function getPreauth(): ?string
    {
        return $this->preauth;
    }

    public function getRectoken(): ?string
    {
        return $this->rectoken;
    }

    public function getLifetime(): ?int
    {
        return $this->lifetime;
    }

    public function getMerchantData(): ?string
    {
        return $this->merchant_data;
    }
}
