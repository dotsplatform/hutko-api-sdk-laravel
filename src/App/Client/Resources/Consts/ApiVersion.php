<?php
/**
 * Description of ApiVersion.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Resources\Consts;

enum ApiVersion: string
{
    case V1 = '1.0';
    case V2 = '2.0';
}
