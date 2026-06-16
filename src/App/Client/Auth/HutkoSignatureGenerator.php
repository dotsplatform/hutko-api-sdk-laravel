<?php

/**
 * Description of HutkoSignatureGenerator.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Auth;

use Dots\Hutko\App\Client\Auth\DTO\HutkoAuthDTO;
use Dots\Hutko\App\Client\Resources\Consts\ApiVersion;

class HutkoSignatureGenerator
{
    public static function generate(
        HutkoAuthDTO $authDTO,
        array $data,
        ApiVersion $version = ApiVersion::V1,
    ): string {
        return match ($version) {
            ApiVersion::V2 => self::generateVersion2($authDTO, $data),
            default => self::generateVersion1($authDTO, $data),
        };
    }

    private static function generateVersion1(HutkoAuthDTO $authDTO, array $data): string
    {
        $data['merchant_id'] = $authDTO->getMerchantId();
        $data = array_filter($data, 'strlen');

        ksort($data);
        $values = array_values($data);
        array_unshift($values, $authDTO->getMerchantKey());
        $dataString = implode('|', $values);

        return sha1($dataString);
    }

    private static function generateVersion2(HutkoAuthDTO $authDTO, array $data): string
    {
        $encodedData = self::encodeDataForVersion2($data);

        return sha1("{$authDTO->getMerchantKey()}|{$encodedData}");
    }

    public static function encodeDataForVersion2(array $data): string
    {
        return base64_encode(json_encode([
            'order' => $data,
        ]) ?: '') ?: '';
    }
}
