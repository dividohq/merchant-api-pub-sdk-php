
# PHP Merchant SDK

## Running phpunit and phpstan

Docker needs to be running for these commands to work.

**phpunit**: `make ci-test`  
**phpstan**: `make ci-analyze`  

## Requirements

Version 3.0.0 and above support PHP 5.6 to 7.0

Version 4.0.0 and above support PHP 7.1 and later.

## Getting Started

This SDK can be installed via [Composer](https://getcomposer.org/) using the following command:

`composer require divido/merchant-sdk` 

## Basic SDK usage

### Create a merchant sdk (Divido tenant)

```php
<?php

// find the environment
$array = explode('_', 'test_cfabc123.querty098765merchantsdk12345');
$identifier = strtoupper($array[0]);
$env =  ('LIVE' == $identifier)
    ? constant("Divido\MerchantSDK\Environment::PRODUCTION")
    : constant("Divido\MerchantSDK\Environment::$identifier");

// create a client wrapper

$httpClientWrapper = new \Divido\MerchantSDK\Wrappers\HttpWrapper(
    \Divido\MerchantSDK\Environment::CONFIGURATION[$env]['base_uri'],
    'test_cfabc123.querty098765merchantsdk12345'
);

// create the sdk
$sdk = new \Divido\MerchantSDK\Client($httpClientWrapper, $env);
```

### Create a merchant sdk (non-Divido tenant)

The value for the "TENANT_API_KEY" and the "TENANT_URI" will be supplied by Divido

```php
<?php

// find the environment
$array = explode('_', 'TENANT_API_KEY');
$identifier = strtoupper($array[0]);
$env =  ('LIVE' == $identifier)
    ? constant("Divido\MerchantSDK\Environment::PRODUCTION")
    : constant("Divido\MerchantSDK\Environment::$identifier");

// create a client wrapper

$httpClientWrapper = new \Divido\MerchantSDK\Wrappers\HttpWrapper(
    'TENANT_URI',
    'TENANT_API_KEY'
);

// create the sdk
$sdk = new \Divido\MerchantSDK\Client($httpClientWrapper, $env);
```

The SDK will automatically attempt to discover a compatible request factory interface to use. 
But with version 4+ of the SDK, you can explicitly specify this as a third argument for the Wrapper, ie:

```
$httpClientWrapper = new \Divido\MerchantSDK\Wrappers\HttpWrapper(
    'TENANT_URI',
    'TENANT_API_KEY',
    new \Laminas\Diactoros\RequestFactory()
);
```

You can also explicitly specify a Stream Factory Interface as your fourth argument:
```
$httpClientWrapper = new \Divido\MerchantSDK\Wrappers\HttpWrapper(
    'TENANT_URI',
    'TENANT_API_KEY',
    new \Laminas\Diactoros\RequestFactory()
    new \Laminas\Diactoros\StreamFactory()
);
```


### Get all finance plans

```php
<?php

// Set any request options.
$requestOptions = (new \Divido\MerchantSDK\Handlers\ApiRequestOptions());

// Retrieve all finance plans for the merchant.
$plans = $sdk->getAllPlans($requestOptions);

$plans = $plans->getResources();
```

### Get all applications

```php
<?php

// Set any request options.
$requestOptions = (new \Divido\MerchantSDK\Handlers\ApiRequestOptions());

// Retrieve all applications for the merchant.
$applications = $sdk->getAllApplications($requestOptions);

$applications = $applications->getResources();
```
### Get Single Application

```php
<?php
$application = $sdk->applications->getSingleApplication($applicationId);
$result = json_decode($application->getBody(), true);

```

### Create an application

```php
<?php

// Create an application model with the application data.
$application = (new \Divido\MerchantSDK\Models\Application())
    ->withCountryId('GB')
    ->withFinancePlanId('F335FED7A-A266-8BF-960A-4CB56CC6DE6F')
    ->withApplicants([
        [
            'firstName' => 'John',
            'lastName' => 'Smith',
            'phoneNumber' => '07512345678',
            'email' => 'john.smith@example.com',
            'addresses' => [[
                'text' => '115 High Street Westbury BA13 3BN'
            ]]
        ],
    ])
    ->withOrderItems([
        [
            'name' => 'Sofa',
            'quantity' => 1,
            'price' => 50000,
        ],
    ])
    ->withDepositAmount(10000)
    ->withFinalisationRequired(false)
    ->withMerchantReference("foo-ref")
    ->withUrls([
        'merchant_redirect_url' => 'http://merchant-redirect-url.example.com',
        'merchant_checkout_url' => 'http://merchant-checkout-url.example.com',
        'merchant_response_url' => 'http://merchant-response-url.example.com',
    ])
    ->withMetadata([
        'foo' => 'bar',
    ]);

// Note: If creating an application (credit request) on a merchant with a shared secret, you will have to pass in a correct hmac
$response = $sdk->applications()->createApplication($application, [], ['X-Divido-Hmac-Sha256' => 'EkDuBPzoelFHGYEmF30hU31G2roTr4OFoxI9efPxjKY=']);

$applicationResponseBody = $response->getBody()->getContents();
```
### Update an application

```php
<?php

// Create an application model with the application data.
$application = (new \Divido\MerchantSDK\Models\Application())
    ->withId('73bb63bf-212a-4598-afb6-cb1449280914')
    ->withCountryId('GB')
    ->withFinancePlanId('F335FED7A-A266-8BF-960A-4CB56CC6DE6F')
    ->withApplicants([
        [
            'firstName' => 'John',
            'lastName' => 'Smith',
            'phoneNumber' => '07512345678',
            'email' => 'john.smith@example.com',
            'addresses' => [[
                'text' => '115 High Street Westbury BA13 3BN'
            ]]
        ],
    ])
    ->withOrderItems([
        [
            'name' => 'Sofa',
            'quantity' => 1,
            'price' => 50000,
        ],
    ])
    ->withDepositAmount(10000)
    ->withFinalisationRequired(false)
    ->withMerchantReference("foo-ref")
    ->withUrls([
        'merchant_redirect_url' => 'http://merchant-redirect-url.example.com',
        'merchant_checkout_url' => 'http://merchant-checkout-url.example.com',
        'merchant_response_url' => 'http://merchant-response-url.example.com',
    ])
    ->withMetadata([
        'foo' => 'bar',
    ]);

// Note: If creating an application (credit request) on a merchant with a shared secret, you will have to pass in a correct hmac
$response = $sdk->applications()->updateApplication($application, [], ['X-Divido-Hmac-Sha256' => 'EkDuBPzoelFHGYEmF30hU31G2roTr4OFoxI9efPxjKY=']);

$applicationResponseBody = $response->getBody()->getContents();
```

### Activate an application

```php
<?php

// First get the application you wish to create an activation for.
$application = (new \Divido\MerchantSDK\Models\Application())
    ->withId('application-id-goes-here');

// Create a new application activation model.
$applicationActivation = (new \Divido\MerchantSDK\Models\ApplicationActivation())
    ->withAmount(18000)
    ->withReference('Order 235509678096')
    ->withComment('Order was delivered to the customer.')
    ->withOrderItems([
        [
            'name' => 'Handbag',
            'quantity' => 1,
            'price' => 3000,
        ],
    ])
    ->withDeliveryMethod('delivery')
    ->withTrackingNumber('988gbqj182836');

// Create a new activation for the application.
$response = $sdk->applicationActivations()->createApplicationActivation($application, $applicationActivation);

$activationResponseBody = $response->getBody()->getContents();
```

### Cancel an application

```php
<?php

// First get the application you wish to create an cancellation for.
$application = (new \Divido\MerchantSDK\Models\Application())
    ->withId('application-id-goes-here');

// Create a new application cancellation model.
$applicationCancellation = (new \Divido\MerchantSDK\Models\ApplicationCancellation())
    ->withAmount(18000)
    ->withReference('Order 235509678096')
    ->withComment('As per customer request.')
    ->withOrderItems([
        [
            'name' => 'Handbag',
            'quantity' => 1,
            'price' => 3000,
        ],
    ]);

// Create a new cancellation for the application.
$response = $sdk->applicationCancellations()->createApplicationCancellation($application, $applicationCancellation);

$cancellationResponseBody = $response->getBody()->getContents();
```

### Refund an application

```php
<?php

// First get the application you wish to create a refund for.
$application = (new \Divido\MerchantSDK\Models\Application())
    ->withId('application-id-goes-here');

// Create a new application refund model.
$applicationRefund = (new \Divido\MerchantSDK\Models\ApplicationRefund())
    ->withAmount(18000)
    ->withReference('Order 235509678096')
    ->withComment('As per customer request.')
    ->withOrderItems([
        [
            'name' => 'Handbag',
            'quantity' => 1,
            'price' => 3000,
        ],
    ]);

// Create a new refund for the application.
$response = $sdk->applicationRefunds()->createApplicationRefund($application, $applicationRefund);

$refundResponseBody = $response->getBody()->getContents();
```

## Pagination, filtering and sorting

You can use the following methods to do things like paginate, filter and/or sort the responses.

```php
<?php

// Set any request options.
$requestOptions = (new \Divido\MerchantSDK\Handlers\ApiRequestOptions())
    // Set the page you'd like to retrieve (default page is 1)
    ->setPage(2)
    // Add an optional sort (method chaining also possible).
    ->setSort('-amount')
    // Filter responses by passing an array of arguments.
    ->setFilters([
        'current_status' => 'deposit-paid',
        'created_after' => '2015-01-01',
    ]);

// Retrieve all applications for the merchant.
$applications = $sdk->getApplicationsByPage($requestOptions);

$applications = $applications->getResources();
```
