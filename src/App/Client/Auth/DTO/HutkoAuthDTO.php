<?php
/**
 * Description of HutkoAuthDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Auth\DTO;

use Dots\Data\DTO;

class HutkoAuthDTO extends DTO
{
    protected string $merchantId;

    protected string $merchantKey;

    public function getMerchantId(): string
    {
        return $this->merchantId;
    }

    public function getMerchantKey(): string
    {
        return $this->merchantKey;
    }
}
