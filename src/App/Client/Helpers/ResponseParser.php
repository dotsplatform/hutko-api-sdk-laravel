<?php

/**
 * Description of ResponseParser.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Helpers;

use Dots\Hutko\App\Client\Resources\Consts\ApiVersion;

class ResponseParser
{
    public static function parse(array $response): array
    {
        if (self::isVersion2Response($response)) {
            return self::parseVersion2($response);
        }

        return self::parseVersion1($response);
    }

    private static function isVersion2Response(array $response): bool
    {
        if (! isset($response['response']['version'])) {
            return false;
        }

        return $response['response']['version'] === ApiVersion::V2->value;
    }

    private static function parseVersion1(array $response): array
    {
        return $response['response'];
    }

    private static function parseVersion2(array $response): array
    {
        if (self::isErrorResponse($response)) {
            return $response['response'];
        }

        $data = json_decode(base64_decode($response['response']['data']), true);
        if (! is_array($data)) {
            return [];
        }

        $order = $data['order'] ?? [];

        return is_array($order) ? $order : [];
    }

    private static function isErrorResponse(array $response): bool
    {
        return empty($response['response']['version']);
    }
}
