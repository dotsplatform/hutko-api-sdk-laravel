# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

> Platform-wide conventions live in the root `/Users/bohdanmamontov/Dots/CLAUDE.md`. This file documents only what is specific to this package. On conflict, follow the root file.

## What this is

`dotsplatform/hutko-api-sdk-laravel` — a Laravel SDK that wraps the [Hutko](https://pay.hutko.org) payments HTTP API. It is consumed by the `payments` service via Composer (`dotsplatform/*`). It is a library package, not an application: it ships a service provider and a config file, has no routes/migrations, and is developed/tested against an in-memory app via `orchestra/testbench`.

- PHP `^8.5`, Saloon `^4`, `dotsplatform/utils ^2.3` (provides the `Dots\Data\DTO` base class all DTOs extend).
- PSR-4: `Dots\Hutko\` → `src/`. Tests: `Tests\` → `tests/`. Workbench app: `Workbench\App\` → `workbench/app/`.
- `HutkoServiceProvider` is auto-discovered (declared in `composer.json` `extra.laravel.providers`). It only merges/publishes `config/hutko.php`, whose single key `hutko.host` comes from `HUTKO_API_HOST` (default `https://pay.hutko.org`).

## Commands

```bash
composer test            # PHPUnit (phpunit.xml: bootstraps vendor/autoload, suite = tests/)
composer lint            # PHPStan / Larastan static analysis
composer build           # testbench workbench:build — builds the skeleton test app
composer serve           # build + testbench serve (run the workbench app locally)
composer init-pre-commit # install the pre-commit hook into .git/hooks

# Run a single test
vendor/bin/phpunit --filter testMethodName
vendor/bin/phpunit tests/Path/To/SomeTest.php
```

Note: `tests/` is currently empty. New tests run on the Testbench-provided app — extend `Orchestra\Testbench\TestCase` and register `HutkoServiceProvider` in `getPackageProviders()`. Use Saloon's `MockClient` to stub HTTP responses rather than hitting the live API.

Code style is enforced by `eduarguz/shift-php-cs` (`.php-cs-fixer.dist.php`, `@auto` ruleset, non-risky) via the pre-commit hook.

## Architecture

The whole SDK is a single Saloon connector plus typed request/response DTOs. Data flow for every call is identical:

```
HutkoConnector::<method>(RequestDTO)
  → RequestDataGenerator::generate(authDto, dto->toArray())   # wrap + sign payload
  → connector->send(new <Name>Request($requestData))         # Saloon Request
  → Request::createDtoFromResponse()                          # typed Response DTO
```

**`HutkoConnector`** (`src/App/Client/HutkoConnector.php`) is the single entry point. Construct it with a `HutkoAuthDTO` (`merchant_id`, `merchant_key`). Public methods map 1:1 to endpoints: `checkout`, `find` (status), `capture`, `reverse`, `transactions`, `fiscalization`. It uses Saloon's `AlwaysThrowOnErrors`, and `getRequestException()` converts any error response into a `HutkoException` carrying an `ErrorResponseDTO`. `resolveBaseUrl()` reads `config('hutko.host')`.

**Auth & signing** (`src/App/Client/Auth/`): `HutkoSignatureGenerator` computes the request signature. Two protocol versions exist, selected by the `ApiVersion` enum (`V1 = '1.0'`, `V2 = '2.0'`), **default V1**:
- V1: inject `merchant_id`, drop empty values, `ksort`, prefix `merchant_key`, `implode('|')`, `sha1`.
- V2: `sha1(merchant_key | base64(json({order: data})))`.

**Payload/response shaping** (`src/App/Client/Helpers/`):
- `RequestDataGenerator` builds the final body, always nested under a top-level `request` key, and adds the signature (V1 inlines `signature`; V2 wraps `version`/`data`/`signature`).
- `RequestDataEncoder` does the V2 base64/JSON encoding.
- `ResponseParser` unwraps `response['response']`; for V2 it base64/JSON-decodes and returns the `order` payload. **All response parsing goes through here** — keep V1/V2 handling in `ResponseParser`, not in individual DTOs.

**Requests** (`src/App/Client/Requests/`): `BaseHutkoRequest` (GET) → `PostHutkoRequest` (POST + JSON body). Each concrete request (e.g. `CheckoutRequest`, endpoint `/api/checkout/url`) takes the pre-built `$requestData` array, returns it as `defaultBody()`, and implements `createDtoFromResponse()` returning its typed response DTO.

**DTOs** all extend `Dots\Data\DTO` (from `dotsplatform/utils`), constructed via `::fromArray()`/`::fromResponse()`, exposed through getters:
- Request DTOs — `src/App/Client/Requests/Payments/DTO/` (`CheckoutRequestDTO`, `StatusRequestDTO`, `CaptureRequestDTO`, `ReverseRequestDTO`, `TransactionListRequestDTO`). Property names use Hutko's snake_case API field names (e.g. `order_id`, `order_desc`, `rectoken`, `preauth`).
- Response DTOs — `src/App/Client/Responses/`. `HutkoResponseDTO` is the base: it normalizes `err_code`/`error` aliases and exposes `isErrorResponse()`; specific responses (`StartPaymentResponseDTO`, `PaymentResponseDTO`, `CapturePaymentResponseDTO`, `ReversePaymentResponseDTO`, `TransactionsResponseDTO`, `ErrorResponseDTO`) extend it.
- Resource DTOs — `src/App/Client/Resources/`. `HutkoWebhookDTO` parses Hutko's server callback (carries `signature`, `merchant_data`, amounts as ints, and convenience predicates `isApproved`/`isCaptured`/`isExpired`/`isDeclined`/`isFailed`). `PaymentTransactionDTO` / `PaymentTransactions` model the transaction list. Status enums live in `Resources/Consts/` (`OrderStatus`, `CaptureStatus`, `ReverseStatus`, `ApiVersion`).

## Conventions specific to this package

- Every PHP file starts with the standard header docblock (`Description of <file>`, `@copyright Copyright (c) DOTSPLATFORM, LLC`, `@author Bogdan Mamontov <bohdan.mamontov@dotsplatform.com>`). Match it on new files.
- Helpers and the signature generator are **stateless static classes** (`::generate`, `::parse`, `::encode`); keep that style rather than injecting them.
- When adding an endpoint: add a `*RequestDTO`, a `*Request` extending `PostHutkoRequest` with `resolveEndpoint()` + `createDtoFromResponse()`, a response DTO extending `HutkoResponseDTO`, and a thin method on `HutkoConnector` that calls `RequestDataGenerator::generate(...)` then `send(...)->dto()`.
