<?php

/**
 * Description of ReverseStatus.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Resources\Consts;

enum ReverseStatus: string
{
    case CREATED = 'created';
    case APPROVED = 'approved';
    case DECLINED = 'declined';

    public function isApproved(): bool
    {
        return $this === self::APPROVED;
    }

    public function isDeclined(): bool
    {
        return $this === self::DECLINED;
    }
}
