<?php

/**
 * Description of RequestDataEncoder.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Helpers;

use Dots\Hutko\App\Client\Resources\Consts\ApiVersion;

class RequestDataEncoder
{
    public static function encode(array $data, ApiVersion $version = ApiVersion::V1): string
    {
        return match ($version) {
            ApiVersion::V2 => base64_encode(json_encode([
                'order' => $data,
            ]) ?: '') ?: '',
            default => json_encode($data) ?: '',
        };
    }
}
