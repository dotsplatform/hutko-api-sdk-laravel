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

    /**
     * Verifies the signature of an incoming POST callback received on the
     * server_callback_url / response_url page.
     *
     * The signature must be checked against the RAW callback payload: it is
     * computed over every non-empty field, so passing a typed DTO that models
     * only a subset of the fields would never match. The signature itself and
     * the response_signature_string hint (returned only in test mode) must be
     * excluded from the calculation.
     */
    public static function check(
        array $response,
        HutkoAuthDTO $authDTO,
        ApiVersion $version = ApiVersion::V1,
    ): bool {
        if (! array_key_exists('signature', $response)) {
            return false;
        }

        $receivedSignature = (string) $response['signature'];
        $expectedSignature = self::generate($authDTO, self::clean($response), $version);

        return hash_equals($expectedSignature, $receivedSignature);
    }

    private static function clean(array $data): array
    {
        unset($data['signature'], $data['response_signature_string']);

        return $data;
    }

    private static function generateVersion1(HutkoAuthDTO $authDTO, array $data): string
    {
        $data['merchant_id'] = $authDTO->getMerchantId();
        $data = self::filterEmptyValues($data);

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

    private static function encodeDataForVersion2(array $data): string
    {
        return base64_encode(json_encode([
            'order' => $data,
        ]) ?: '') ?: '';
    }

    /**
     * Drops empty parameters from the signature string, matching the documented
     * rule: an empty parameter must not add a "|" separator. A "0" value is NOT
     * empty and must be kept. This mirrors the historical
     * array_filter($data, 'strlen') behaviour without triggering the
     * "Passing null to strlen() is deprecated" notice on PHP 8.1+.
     */
    private static function filterEmptyValues(array $data): array
    {
        return array_filter(
            $data,
            static fn($value): bool => (string) $value !== '',
        );
    }
}
