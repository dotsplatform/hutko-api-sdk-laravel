<?php

/**
 * Description of PostHutkoRequest.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Traits\Body\HasJsonBody;

abstract class PostHutkoRequest extends BaseHutkoRequest implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;
}
