<?php

/**
 * Description of ErrorResponseDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Responses;

use Saloon\Http\Response;

class ErrorResponseDTO extends HutkoResponseDTO
{
    public static function fromResponse(Response $response): static
    {
        $data = $response->json();
        if (! is_array($data)) {
            $data = [];
        }

        return static::fromArray($data['response'] ?? $data);
    }
}
