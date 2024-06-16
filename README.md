# test systemeio symfony

[![codecov](https://codecov.io/github/a3naumov/test-systemeio-symfony/graph/badge.svg?token=IT9QSDYJ9N)](https://codecov.io/github/a3naumov/test-systemeio-symfony)

This is a test project for the Systeme.io company. The project is a simple e-commerce application that allows users to calculate the price of a product and make a purchase

## Task Description
For more information about the original task and repository, please visit the [Original Repository](https://github.com/systemeio/backend-test-task)

## Features
- Calculate the price of a product
- Make a purchase
- Use coupons to get discounts
- Use tax rates based on the country
- Use different payment processors (Stripe, PayPal, Global Payments)

## Installation

* Clone the repository
```bash
git clone
```

* Make the bin directory executable
```bash
chmod +x bin/*
```

* Generate a self-signed SSL certificate for the Nginx server
```bash
bin/generate-certs
```

* Copy the php.ini-development or php.ini-production file to php.ini
```bash
cp ./docker/php/conf/php.ini-production ./docker/php/conf/php.ini
```

* Copy the .env.example files to .env and fill in the appropriate values
```bash
cp .env.example .env
cp src/.env src/.env.local
```

* Update the .env file with the appropriate values
```bash
nano .env
```

* Generate secret and put it in the src/.env.local file
```bash
bin/generate-secret
```

* Run the docker-compose up command to start the containers
```bash
docker-compose up -d
```

* Install the composer dependencies
```bash
bin/composer install
```

* Run the migrations
```bash
bin/console doctrine:migrations:migrate
```

## Default Values Loaded from Fixtures

### Default Values
Below is a list of the default values and their descriptions:

#### Products
| ID | Name       | Price |
|----|------------|-------|
| 1  | Macbook    | 1500  |
| 2  | Ipad       | 500   |
| 3  | Iphone     | 100   |
| 4  | Headphones | 20    |
| 5  | Case       | 10    |

#### Coupons
| ID | Code | Type    | Value |
|----|------|---------|-------|
| 1  | P10  | percent | 10    |
| 2  | P30  | percent | 30    |
| 3  | P50  | percent | 50    |
| 4  | F10  | fixed   | 10    |
| 5  | F100 | fixed   | 100   |

#### Tax Rates
| ID | Country Code | Rate |
|----|--------------|------|
| 1  | DE           | 19   |
| 2  | IT           | 22   |
| 3  | FR           | 20   |
| 4  | GR           | 24   |

### How to Load Fixtures
To load the fixtures into the database, use the following command:

```bash
bin/console doctrine:fixtures:load
```

## Payment Processors Conditions
| Code              | Class Name                                          | Failed condition    |
|-------------------|-----------------------------------------------------|---------------------|
| "global_payments" | App\PaymentProcessor\Vendor\GlobalPaymentsProcessor | { amount } > 100    |
| "paypal"          | App\PaymentProcessor\Vendor\PaypalPaymentProcessor  | { amount } > 100000 |
| "stripe"          | App\PaymentProcessor\Vendor\StripePaymentProcessor  | { amount } < 100    |

## API Endpoints

#### Calculate Price
- **Endpoint:** `/calculate-price`
- **Method:** POST
- **Description:** Calculate price for a product
- **Example Request:**
  ```http
  POST /calculate-price HTTP/1.1
  Accept: application/json
  Content-Type: application/json
  Authorization: No Auth
  
  {
    "product": 1,
    "taxNumber": "DE123456789",
    "couponCode": "P10"
  }
  ```

#### Make Purchase
- **Endpoint:** `/purchase`
- **Method:** POST
- **Description:** Make a purchase
- **Example Request:**
  ```http
  POST /purchase HTTP/1.1
  Accept: application/json
  Content-Type: application/json
  Authorization: No Auth
  
  {
    "product": 1,
    "taxNumber": "DE123456789",
    "couponCode": "P10",
    "paymentProcessor": "stripe"
  }
  ```

### Testing Requests with `requests.http` file
To test the API requests, you can use the `requests.http` file included in the project. This file contains examples of all the main requests and can be used with an HTTP client

#### Setup Instructions
1. **Copy Environment File:**
    - Copy `http-client.env.json` to `http-client.private.env.json`
    - This file contains environment variables used in the requests

2. **Fill in Environment Variables:**
    - Open `http-client.private.env.json` and fill in the necessary data

3. **Use the HTTP Client:**
    - Open the `requests.http` file in your HTTP client
    - Execute the requests directly from the file to interact with the API

#### Example of `http-client.private.env.json`
```json
{
  "dev": {
    "host": "https://localhost"
  }
}