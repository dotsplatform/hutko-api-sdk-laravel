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

    /**
     * A "0" value is NOT empty and must stay in the signature string
     * (the docs warn twice about languages that coerce 0 to an empty value).
     */
    public function testVersion1SignatureKeepsZeroValues(): void
    {
        $auth = $this->makeAuthDto();

        $signature = HutkoSignatureGenerator::generate($auth, [
            'order_id' => 'test123456',
            'amount' => '0',
            'currency' => 'USD',
        ], ApiVersion::V1);

        $this->assertSame(sha1('test|0|USD|1396424|test123456'), $signature);
    }

    /**
     * Regression: nullable DTO fields serialize to null, and the old
     * array_filter($data, 'strlen') emitted a "Passing null to strlen()"
     * deprecation on every request. Generation must now be deprecation-free.
     */
    public function testVersion1SignatureDoesNotEmitDeprecationForNullValues(): void
    {
        $auth = $this->makeAuthDto();

        set_error_handler(static function (int $errno, string $message): bool {
            throw new \RuntimeException($message);
        }, E_DEPRECATED);

        try {
            $signature = HutkoSignatureGenerator::generate($auth, [
                'order_id' => 'test123456',
                'order_desc' => 'test order',
                'currency' => 'USD',
                'amount' => '125',
                'server_callback_url' => null,
                'response_url' => null,
                'merchant_data' => null,
            ], ApiVersion::V1);
        } finally {
            restore_error_handler();
        }

        $this->assertSame(sha1('test|125|USD|1396424|test order|test123456'), $signature);
    }

    public function testCheckReturnsTrueForValidCallbackSignature(): void
    {
        $auth = $this->makeAuthDto();
        $callback = $this->makeCallbackPayload();

        $this->assertTrue(HutkoSignatureGenerator::check($callback, $auth));
    }

    public function testCheckReturnsFalseForTamperedCallback(): void
    {
        $auth = $this->makeAuthDto();
        $callback = $this->makeCallbackPayload();
        $callback['order_status'] = 'declined';

        $this->assertFalse(HutkoSignatureGenerator::check($callback, $auth));
    }

    public function testCheckReturnsFalseWhenSignatureIsMissing(): void
    {
        $auth = $this->makeAuthDto();
        $callback = $this->makeCallbackPayload();
        unset($callback['signature']);

        $this->assertFalse(HutkoSignatureGenerator::check($callback, $auth));
    }

    /**
     * response_signature_string is a test-mode hint and must be excluded
     * from the calculation, so its presence must not break verification.
     */
    public function testCheckIgnoresResponseSignatureStringField(): void
    {
        $auth = $this->makeAuthDto();
        $callback = $this->makeCallbackPayload();
        $callback['response_signature_string'] = 'this hint must be ignored';

        $this->assertTrue(HutkoSignatureGenerator::check($callback, $auth));
    }

    private function makeAuthDto(): HutkoAuthDTO
    {
        return HutkoAuthDTO::fromArray([
            'merchantId' => self::MERCHANT_ID,
            'merchantKey' => self::MERCHANT_KEY,
        ]);
    }

    /**
     * The callback example from the official Hutko documentation.
     * Its signature equals sha1() of the documented response_signature_string
     * (with the masked password replaced by "test").
     */
    private function makeCallbackPayload(): array
    {
        return [
            'rrn' => '429417347068',
            'masked_card' => '444455XXXXXX6666',
            'sender_cell_phone' => '',
            'response_status' => 'success',
            'sender_account' => '',
            'fee' => '',
            'rectoken_lifetime' => '',
            'reversal_amount' => '0',
            'settlement_amount' => '0',
            'actual_amount' => '3324000',
            'order_status' => 'approved',
            'response_description' => '',
            'verification_status' => '',
            'order_time' => '21.07.2017 15:20:27',
            'actual_currency' => 'UAH',
            'order_id' => '14#1500639628',
            'parent_order_id' => '',
            'merchant_data' => '',
            'tran_type' => 'purchase',
            'eci' => '',
            'settlement_date' => '',
            'payment_system' => 'card',
            'rectoken' => '',
            'approval_code' => '027440',
            'merchant_id' => 1396424,
            'settlement_currency' => '',
            'payment_id' => 51247263,
            'product_id' => '',
            'currency' => 'UAH',
            'card_bin' => 444455,
            'response_code' => '',
            'card_type' => 'VISA',
            'amount' => '3324000',
            'sender_email' => 'test@email.com',
            'signature' => 'c2f8bce2a279594a01566d1229f9fbbf172589fa',
        ];
    }
}
