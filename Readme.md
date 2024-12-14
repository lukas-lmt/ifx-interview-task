# Banking Application - Domain-Driven Design Example

This is a simplified implementation of a banking system using **Domain-Driven Design (DDD)** principles. The application focuses on handling `BankAccount` operations like crediting, debiting, and enforcing domain rules (e.g., daily debit limits, transaction fees, and currency validation).

## Features

- **Domain Layer**: Encapsulates business logic with entities, value objects, and specifications.
- **Application Layer**: Coordinates use cases through commands and application services.
- **Infrastructure Layer**: Provides in-memory persistence for testing and simplicity.
- **Specifications**: Enforces domain rules in a reusable and testable way.
- **Custom Exceptions**: Dedicated exceptions for clear and explicit domain error handling.

## Prerequisites

- PHP 8.2 or newer
- Composer (dependency manager)

## Installation

1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd <repository-folder>
   ```

2. Install dependencies:
   ```bash
   composer install
   ```

## Running Tests

This project uses **PHPUnit** for unit, integration/functional tests.

1. Run all tests:
   ```bash
   vendor/bin/phpunit tests
   ```
## Example Use Cases

1. **Credit an Account**
   - Credits the account balance while ensuring currency consistency.

2. **Debit an Account**
   - Deducts an amount from the account, applying transaction fees and ensuring sufficient balance, daily limits, and currency matching.

3. **Error Handling**
   - Custom exceptions such as `InsufficientBalanceException` and `DailyDebitLimitExceededException` are thrown for rule violations.

## Directory Structure

```plaintext
├── Application/         # Application layer (services, commands)
│   ├── Commands/
│   ├── Enums/
│   └── Services/
├── Domain               # Domain layer (entities, value objects, specifications)
│   ├── BankAccount/
│   │   ├── Entity/
│   │   ├── Enums/
│   │   ├── Repository/
│   │   └── ValueObject/
│   ├── Exceptions/
│   ├── Services/
│   ├── Specifications/
│   └── ValueObject/
└── Infrastructure/      # Infrastructure layer (repositories, persistence)
    └── Persistence/

```
```
src/
├── Application/       # Application layer (services, commands)
├── Domain/            # Domain layer (entities, value objects, specifications)
├── Infrastructure/    # Infrastructure layer (repositories, persistence)
└── Tests/             # Unit, integration, and functional tests
```

