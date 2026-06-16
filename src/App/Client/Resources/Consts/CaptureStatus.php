<?php

/**
 * Description of CaptureStatus.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Resources\Consts;

enum CaptureStatus: string
{
    case CREATED = 'created';
    case CAPTURED = 'captured';
    case DECLINED = 'declined';

    public function isCaptured(): bool
    {
        return $this === self::CAPTURED;
    }

    public function isDeclined(): bool
    {
        return $this === self::DECLINED;
    }
}
