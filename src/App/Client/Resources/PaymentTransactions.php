<?php
/**
 * Description of PaymentTransactions.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Resources;

use Illuminate\Support\Collection;

class PaymentTransactions extends Collection
{
    public static function fromArray(array $data): self
    {
        return new self(
            array_map(
                fn (array $item) => PaymentTransactionDTO::fromArray($item),
                $data,
            )
        );
    }
}
