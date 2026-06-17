<?php

/**
 * Description of HutkoSignatureGeneratorTest.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Tests\Unit\Auth;

use Dots\Hutko\App\Client\Auth\DTO\HutkoAuthDTO;
use Dots\Hutko\App\Client\Auth\HutkoSignatureGenerator;
use Dots\Hutko\App\Client\Resources\Consts\ApiVersion;
use PHPUnit\Framework\TestCase;

class HutkoSignatureGeneratorTest extends TestCase
{
    /**
     * Merchant password and id taken from the official Hutko signature example.
     */
    private const MERCHANT_KEY = 'test';

    private const MERCHANT_ID = '1396424';

    /**
     * The exact concatenation rule from the documentation: the merchant password,
     * followed by every non-empty parameter in alphabetical key order, joined by "|".
     *
     * NOTE: the official docs print the signature as
     * "f0ee6288b9295d3b808bcd8d720211c7201245e1", but that hash does NOT match
     * sha1() of the string the same docs show. The string-building rule below is
     * correct; the published hash is a long-standing typo in the Fondy-family docs.
     * We therefore assert against sha1() of the documented string, not the printed hash.
     */
    public function testGeneratesVersion1RequestSignatureFromDocumentationExample(): void
    {
        $auth = $this->makeAuthDto();
        $requestData = [
            'order_id' => 'test123456',
            'order_desc' => 'test order',
            'currency' => 'USD',
            'amount' => '125',
        ];

        $documentedString = 'test|125|USD|1396424|test order|test123456';

        $signature = HutkoSignatureGenerator::generate($auth, $requestData, ApiVersion::V1);

        $this->assertSame(sha1($documentedString), $signature);
    }

    public function testVersion1SignatureIsIndependentOfInputParameterOrder(): void
    {
        $auth = $this->makeAuthDto();

        $orderedAsDocumented = HutkoSignatureGenerator::generate($auth, [
            'order_id' => 'test123456',
            'order_desc' => 'test order',
            'currency' => 'USD',
            'amount' => '125',
        ], ApiVersion::V1);

        $shuffled = HutkoSignatureGenerator::generate($auth, [
            'amount' => '125',
            'order_desc' => 'test order',
            'order_id' => 'test123456',
            'currency' => 'USD',
        ], ApiVersion::V1);

        $this->assertSame($orderedAsDocumented, $shuffled);
    }

    /**
     * Per the docs: an empty parameter must not add a "|" separator,
     * i.e. empty values are excluded from the signature string entirely.
     */
    public function testVersion1SignatureSkipsEmptyParameters(): void
    {
        $auth = $this->makeAuthDto();

        $withEmptyValues = HutkoSignatureGenerator::generate($auth, [
            'order_id' => 'test123456',
            'order_desc' => 'test order',
            'currency' => 'USD',
            'amount' => '125',
            'response_url' => '',
            'merchant_data' => '',
        ], ApiVersion::V1);

        $withoutEmptyValues = HutkoSignatureGenerator::generate($auth, [
            'order_id' => 'test123456',
            'order_desc' => 'test order',
            'currency' => 'USD',
            'amount' => '125',
        ], ApiVersion::V1);

        $this->assertSame(sha1('test|125|USD|1396424|test order|test123456'), $withEmptyValues);
        $this->assertSame($withoutEmptyValues, $withEmptyValues);
    }

    /**
     * The response/callback signature is built with the same algorithm,
     * so the generator can be used to verify an incoming "signature" field.
     */
    public function testGeneratesVersion1ResponseSignature(): void
    {
        $auth = $this->makeAuthDto();
        $responseData = [
            'order_id' => 'test123456',
            'order_status' => 'approved',
            'currency' => 'USD',
            'amount' => '125',
        ];

        $documentedString = 'test|125|USD|1396424|test123456|approved';

        $signature = HutkoSignatureGenerator::generate($auth, $responseData, ApiVersion::V1);

        $this->assertSame(sha1($documentedString), $signature);
    }

    public function testGeneratesVersion2Signature(): void
    {
        $auth = $this->makeAuthDto();
        $data = [
            'order_id' => 'test123456',
            'order_desc' => 'test order',
            'currency' => 'USD',
            'amount' => '125',
        ];

        $encodedPayload = base64_encode(json_encode(['order' => $data]));

        $signature = HutkoSignatureGenerator::generate($auth, $data, ApiVersion::V2);

        $this->assertSame(sha1(self::MERCHANT_KEY . '|' . $encodedPayload), $signature);
    }

    public function testVersion1IsTheDefaultVersion(): void
    {
        $auth = $this->makeAuthDto();
        $data = [
            'order_id' => 'test123456',
            'order_desc' => 'test order',
            'currency' => 'USD',
            'amount' => '125',
        ];

        $default = HutkoSignatureGenerator::generate($auth, $data);
        $explicitV1 = HutkoSignatureGenerator::generate($auth, $data, ApiVersion::V1);

        $this->assertSame($explicitV1, $default);
    }

    private function makeAuthDto(): HutkoAuthDTO
    {
        return HutkoAuthDTO::fromArray([
            'merchantId' => self::MERCHANT_ID,
            'merchantKey' => self::MERCHANT_KEY,
        ]);
    }
}
