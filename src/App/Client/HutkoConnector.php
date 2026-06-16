<?php
/**
 * Description of HutkoConnector.php
 * @copyright Copyright (c) DOTSPLATFORM, LLC
 * @author    Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>
 */

namespace Dots\Hutko\App\Client;

use Dots\Hutko\App\Client\Auth\DTO\HutkoAuthDTO;
use Dots\Hutko\App\Client\Auth\HutkoSignatureGenerator;
use Dots\Hutko\App\Client\Exceptions\HutkoException;
use Dots\Hutko\App\Client\Helpers\RequestDataGenerator;
use Dots\Hutko\App\Client\Requests\Payments\CaptureRequest;
use Dots\Hutko\App\Client\Requests\Payments\CheckoutRequest;
use Dots\Hutko\App\Client\Requests\Payments\DTO\CaptureRequestDTO;
use Dots\Hutko\App\Client\Requests\Payments\DTO\CheckoutRequestDTO;
use Dots\Hutko\App\Client\Requests\Payments\DTO\RecurringRequestDTO;
use Dots\Hutko\App\Client\Requests\Payments\DTO\ReverseRequestDTO;
use Dots\Hutko\App\Client\Requests\Payments\DTO\SettlementRequestDTO;
use Dots\Hutko\App\Client\Requests\Payments\DTO\StatusRequestDTO;
use Dots\Hutko\App\Client\Requests\Payments\DTO\TransactionListRequestDTO;
use Dots\Hutko\App\Client\Requests\Payments\FiscalizationRequest;
use Dots\Hutko\App\Client\Requests\Payments\RecurringRequest;
use Dots\Hutko\App\Client\Requests\Payments\ReverseRequest;
use Dots\Hutko\App\Client\Requests\Payments\SettlementRequest;
use Dots\Hutko\App\Client\Requests\Payments\StatusRequest;
use Dots\Hutko\App\Client\Requests\Payments\TransactionListRequest;
use Dots\Hutko\App\Client\Resources\Consts\ApiVersion;
use Dots\Hutko\App\Client\Responses\CapturePaymentResponseDTO;
use Dots\Hutko\App\Client\Responses\ErrorResponseDTO;
use Dots\Hutko\App\Client\Responses\PaymentResponseDTO;
use Dots\Hutko\App\Client\Responses\ReversePaymentResponseDTO;
use Dots\Hutko\App\Client\Responses\SettlementPaymentResponseDTO;
use Dots\Hutko\App\Client\Responses\StartPaymentResponseDTO;
use Dots\Hutko\App\Client\Responses\TransactionsResponseDTO;
use RuntimeException;
use Saloon\Http\Connector;
use Saloon\Http\Response;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Throwable;

class HutkoConnector extends Connector
{
    use AlwaysThrowOnErrors;

    public function __construct(
        private readonly HutkoAuthDTO $authDto,
    ) {
    }

    /**
     * @throws HutkoException
     */
    public function checkout(CheckoutRequestDTO $dto): StartPaymentResponseDTO
    {
        $requestData = RequestDataGenerator::generate(
            $this->authDto,
            $dto->toArray(),
        );

        return $this->send(new CheckoutRequest($requestData))->dto();
    }

    /**
     * @throws HutkoException
     */
    public function recurring(RecurringRequestDTO $dto): StartPaymentResponseDTO
    {
        $requestData = RequestDataGenerator::generate(
            $this->authDto,
            $dto->toArray(),
            ApiVersion::V2,
        );

        return $this->send(new RecurringRequest($requestData))->dto();
    }

    /**
     * @throws HutkoException
     */
    public function find(StatusRequestDTO $dto): PaymentResponseDTO
    {
        $requestData = RequestDataGenerator::generate(
            $this->authDto,
            $dto->toArray(),
        );

        return $this->send(new StatusRequest($requestData))->dto();
    }

    /**
     * @throws HutkoException
     */
    public function capture(CaptureRequestDTO $dto): CapturePaymentResponseDTO
    {
        $requestData = RequestDataGenerator::generate(
            $this->authDto,
            $dto->toArray(),
        );

        return $this->send(new CaptureRequest($requestData))->dto();
    }

    /**
     * @throws HutkoException
     */
    public function reverse(ReverseRequestDTO $dto): ReversePaymentResponseDTO
    {
        $requestData = RequestDataGenerator::generate(
            $this->authDto,
            $dto->toArray(),
        );

        return $this->send(new ReverseRequest($requestData))->dto();
    }

    /**
     * @throws HutkoException
     */
    public function settlement(SettlementRequestDTO $dto): SettlementPaymentResponseDTO
    {
        $requestData = RequestDataGenerator::generate(
            $this->authDto,
            $dto->toArray(),
            ApiVersion::V2,
        );

        return $this->send(new SettlementRequest($requestData))->dto();
    }

    /**
     * @throws HutkoException
     */
    public function transactions(TransactionListRequestDTO $dto): TransactionsResponseDTO
    {
        $requestData = RequestDataGenerator::generate(
            $this->authDto,
            $dto->toArray(),
        );

        return $this->send(new TransactionListRequest($requestData))->dto();
    }

    /**
     * @throws HutkoException
     */
    public function fiscalization(StatusRequestDTO $dto): array
    {
        $requestData = RequestDataGenerator::generate(
            $this->authDto,
            $dto->toArray(),
        );

        return $this->send(new FiscalizationRequest($requestData))->dto();
    }

    public function getAuthDto(): HutkoAuthDTO
    {
        return $this->authDto;
    }

    public function getSignatureGenerator(): HutkoSignatureGenerator
    {
        return new HutkoSignatureGenerator();
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function resolveBaseUrl(): string
    {
        $host = config('hutko.host');
        if (! is_string($host)) {
            throw new RuntimeException('Invalid Hutko API host');
        }

        return $host;
    }

    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        $errorResponse = ErrorResponseDTO::fromResponse($response);

        return new HutkoException($errorResponse);
    }
}
