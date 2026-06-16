<?php

/**
 * Description of OrderStatus.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Resources\Consts;

enum OrderStatus: string
{
    case CREATED = 'created';
    case PROCESSING = 'processing';
    case APPROVED = 'approved';
    case DECLINED = 'declined';
    case EXPIRED = 'expired';
    case REVERSED = 'reversed';

    public function isApproved(): bool
    {
        return $this === self::APPROVED;
    }

    public function isDeclined(): bool
    {
        return $this === self::DECLINED;
    }

    public function isExpired(): bool
    {
        return $this === self::EXPIRED;
    }

    public function isProcessing(): bool
    {
        return $this === self::PROCESSING;
    }

    public function isReversed(): bool
    {
        return $this === self::REVERSED;
    }

    public function isFailed(): bool
    {
        return $this->isExpired() || $this->isDeclined();
    }
}
