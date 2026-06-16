<?php

/**
 * Description of BaseHutkoRequest.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

abstract class BaseHutkoRequest extends Request
{
    protected Method $method = Method::GET;
}
