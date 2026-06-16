<?php
/**
 * Description of HutkoResponseDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Responses;

use Dots\Data\DTO;
use Dots\Hutko\App\Client\Helpers\ResponseParser;
use Saloon\Http\Response;

class HutkoResponseDTO extends DTO
{
    public const RESPONSE_STATUS_SUCCESS = 'success';

    public const RESPONSE_STATUS_FAILURE = 'failure';

    protected ?string $response_status;

    protected ?string $response_code;

    protected ?string $error_code;

    protected ?string $error_message;

    protected ?string $requestId;

    public static function fromArray(array $data): static
    {
        $data['error_code'] = $data['error_code'] ?? $data['err_code'] ?? null;
        $data['error_message'] = $data['error_message'] ?? $data['error'] ?? null;

        return parent::fromArray($data);
    }

    public static function fromResponse(Response $response): static
    {
        $data = $response->json();
        if (! is_array($data)) {
            $data = [];
        }

        $parsed = ResponseParser::parse($data);

        return static::fromArray($parsed);
    }

    public function getResponseStatus(): ?string
    {
        return $this->response_status;
    }

    public function getResponseCode(): ?string
    {
        return $this->response_code;
    }

    public function getErrorCode(): ?string
    {
        return $this->error_code;
    }

    public function getErrorMessage(): ?string
    {
        return $this->error_message;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    public function isErrorResponse(): bool
    {
        return $this->response_status === self::RESPONSE_STATUS_FAILURE
            || $this->getErrorCode()
            || $this->getResponseCode();
    }
}
