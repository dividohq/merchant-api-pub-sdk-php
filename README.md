
# PHP Merchant SDK

## Using the SDK

Start by create the Merchant SDK Client.

```php
<?php

$sdk = new \Divido\MerchantSDK\Client('test_cfabc123.querty098765merchantsdk12345', \Divido\MerchantSDK\Environment::SANDBOX);
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

### Create an application

```php
<?php

// Create an appication model with the application data.
$application = (new \Divido\MerchantSDK\Models\Application())
    ->withCountryId('GB')
    ->withCurrencyId('GBP')
    ->withLanguageId('EN')
    ->withFinancePlanId('F335FED7A-A266-8BF-960A-4CB56CC6DE6F')
    ->withMerchantChannelId('C47B81C83-08A8-B5A-EBD3-B9CFA1D60A07')
    ->withApplicants([
        [
            'firstName' => 'John',
            'lastName' => 'Smith',
            'phoneNumber' => '07512345678',
            'email' => 'john.smith@example.com',
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
    ->withDepositPercentage(0.02)
    ->withFinalisationRequired(false)
    ->withMerchantReference("foo-ref")
    ->withMerchantRedirectUrl("http://merchant-redirect-url.example.com")
    ->withMerchantCheckoutUrl("http://merchant-checkout-url.example.com")
    ->withMerchantResponseUrl("http://merchant-response-url.example.com")
    ->withMetadata([
        'foo' => 'bar',
    ]);

$response = $sdk->applications()->createApplication($application);

$applicationResponseBody = $response->getBody()->getContents();
```

### Activate an application

```php
<?php

// First get the application you wish to create an activation for.
$application = (new \Divido\MerchantSDK\Models\Application())
    ->withId('application-id-goes-here');

$items = [
    [
        'name' => 'Handbag',
        'quantity' => 1,
        'price' => 3000,
    ],
];

// Create a new application activation model.
$applicationActivation = (new \Divido\MerchantSDK\Models\ApplicationActivation())
    ->withAmount(18000)
    ->withReference('Order 235509678096')
    ->withComment('Order was delivered to the customer.')
    ->withOrderItems($items)
    ->withDeliveryMethod('delivery')
    ->withTrackingNumber('988gbqj182836');

// Create a new activation for the application.
$response = $sdk->application_activations()->createApplicationActivation($application, $applicationActivation);

$activationResponseBody = $response->getBody()->getContents();
```
