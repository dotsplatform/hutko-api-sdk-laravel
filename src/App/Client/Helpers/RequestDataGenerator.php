<?php
/**
 * Description of RequestDataGenerator.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Helpers;

use Dots\Hutko\App\Client\Auth\DTO\HutkoAuthDTO;
use Dots\Hutko\App\Client\Auth\HutkoSignatureGenerator;
use Dots\Hutko\App\Client\Resources\Consts\ApiVersion;

class RequestDataGenerator
{
    public static function generate(
        HutkoAuthDTO $authDTO,
        array $data,
        ApiVersion $version = ApiVersion::V1,
    ): array {
        return match ($version) {
            ApiVersion::V2 => self::generateVersion2($authDTO, $data),
            default => self::generateVersion1($authDTO, $data),
        };
    }

    private static function generateVersion1(HutkoAuthDTO $authDTO, array $data): array
    {
        $data['signature'] = HutkoSignatureGenerator::generate($authDTO, $data, ApiVersion::V1);
        $data = array_filter($data, 'strlen');
        ksort($data);

        return [
            'request' => $data,
        ];
    }

    private static function generateVersion2(HutkoAuthDTO $authDTO, array $data): array
    {
        $encodedData = RequestDataEncoder::encode($data, ApiVersion::V2);
        $signature = HutkoSignatureGenerator::generate($authDTO, $data, ApiVersion::V2);

        return [
            'request' => [
                'version' => ApiVersion::V2->value,
                'data' => $encodedData,
                'signature' => $signature,
            ],
        ];
    }
}
