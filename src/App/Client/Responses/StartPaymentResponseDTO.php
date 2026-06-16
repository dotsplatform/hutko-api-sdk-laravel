<?php
/**
 * Description of StartPaymentResponseDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Responses;

use Dots\Hutko\App\Client\Helpers\ResponseParser;
use Saloon\Http\Response;

class StartPaymentResponseDTO extends HutkoResponseDTO
{
    protected ?string $token;

    protected ?int $payment_id;

    protected ?string $checkout_url;

    public static function fromResponse(Response $response): static
    {
        $data = $response->json();
        if (! is_array($data)) {
            $data = [];
        }

        $parsed = ResponseParser::parse($data);
        $parsed['token'] = self::extractTokenFromCheckoutUrl($parsed['checkout_url'] ?? null);

        return static::fromArray($parsed);
    }

    public function getPaymentId(): ?int
    {
        return $this->payment_id;
    }

    public function getCheckoutUrl(): ?string
    {
        return $this->checkout_url;
    }

    public function getToken(): string
    {
        return $this->token ?: '';
    }

    public function isErrorResponse(): bool
    {
        if (parent::isErrorResponse()) {
            return true;
        }

        if (! $this->getPaymentId()) {
            return true;
        }

        return ! $this->getToken();
    }

    private static function extractTokenFromCheckoutUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $queryString = parse_url($url, PHP_URL_QUERY);
        if (! $queryString) {
            return null;
        }

        parse_str($queryString, $urlQuery);
        $token = $urlQuery['token'] ?? null;
        if (! is_string($token)) {
            return null;
        }

        return $token;
    }
}
