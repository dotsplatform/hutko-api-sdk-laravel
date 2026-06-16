<?php

/**
 * Description of TransactionListRequestDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Requests\Payments\DTO;

use Dots\Data\DTO;

class TransactionListRequestDTO extends DTO
{
    protected string $order_id;

    protected string $merchant_id;

    public function getOrderId(): string
    {
        return $this->order_id;
    }

    public function getMerchantId(): string
    {
        return $this->merchant_id;
    }
}
