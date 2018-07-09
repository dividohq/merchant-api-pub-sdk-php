
# PHP Merchant SDK

## Using the SDK

### Get all applications (simple example)

```php
<?php

// Create a Client
$sdk = new \Divido\MerchantSDK\Client('test_key', \Divido\MerchantSDK\Environment::SANDBOX);
```

```php
<?php

// Set any request options
$requestOptions = (new \Divido\MerchantSDK\Handlers\ApiRequestOptions());
```

```php
<?php

// Retrieve all applications for the merchant.
$applications = $sdk->getAllApplications($requestOptions);
```
