<?php
/**
 * Description of PaymentTransactionDTO.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client\Resources;

use Dots\Data\DTO;

class PaymentTransactionDTO extends DTO
{
    protected int $id;

    protected ?string $masked_card;

    protected ?string $protocol;

    protected ?string $timestart;

    protected ?string $transaction_status;

    protected ?string $receiver_approval_code;

    protected ?float $fee;

    protected ?float $reversal_amount;

    protected ?float $settlement_amount;

    protected ?string $timeend;

    protected ?string $order_id;

    protected ?float $actual_amount;

    protected ?string $tran_type;

    protected ?string $settlement_date;

    protected ?string $payment_system;

    protected ?int $merchant_id;

    protected ?string $response_description;

    protected ?int $payment_id;

    protected ?string $capture_status;

    protected ?float $client_fee;

    protected ?float $capture_amount;

    protected ?string $card_type;

    protected ?float $amount;

    protected ?string $veres_status;

    protected ?string $settlement_status;

    protected ?int $response_code;

    public function getId(): int
    {
        return $this->id;
    }

    public function getMaskedCard(): ?string
    {
        return $this->masked_card;
    }

    public function getTransactionStatus(): ?string
    {
        return $this->transaction_status;
    }

    public function getFee(): ?float
    {
        return $this->fee;
    }

    public function getSettlementAmount(): ?float
    {
        return $this->settlement_amount;
    }

    public function getOrderId(): ?string
    {
        return $this->order_id;
    }

    public function getActualAmount(): ?float
    {
        return $this->actual_amount;
    }

    public function getSettlementDate(): ?string
    {
        return $this->settlement_date;
    }

    public function getPaymentId(): ?int
    {
        return $this->payment_id;
    }

    public function getCaptureStatus(): ?string
    {
        return $this->capture_status;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function getSettlementStatus(): ?string
    {
        return $this->settlement_status;
    }
}
