
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
