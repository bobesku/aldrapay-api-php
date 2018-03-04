# Aldrapay payment system API integration library

[![Build Status Master](https://travis-ci.org/bobesku/aldrapay-api-php.svg?branch=master)](https://travis-ci.org/bobesku/aldrapay-api-php)

## Requirements

PHP 5.5+

## Test Account

Please register your merchant test account at https://secure.aldrapay.com/backoffice/register.html before starting the integration


### Test card numbers

Refer to the documentation https://secure.aldrapay.com/backoffice/docs/api/testing.html#test-cards for valid test card numbers.

## Getting started

### Setup

Before using the library classes you must configure it.
You have to setup values of variables as follows:

  * `merchantId`
  * `passCode`
  * `pSignAlgorithm`
  * `gatewayBase`

You will receive the above data after registering your account.

```php
\Aldrapay\Settings::$merchantId  = XXX;
\Aldrapay\Settings::$passCode = 'XXXXXXXXXXXXXXX';
\Aldrapay\Settings::$pSignAlgorithm = 'sha1'; //possible values see \Aldrapay\PSignAlgorithm
\Aldrapay\Settings::$gatewayBase = 'https://secure.aldrapay.com';
```

### Hosted payment page

Simple usage looks like:

```php
require_once __DIR__ . 'PATH_TO_INSTALLED_LIBRARY/lib/Aldrapay.php';
\Aldrapay\Settings::$merchantId  = XXX;
\Aldrapay\Settings::$passCode = 'XXXXXXXXXXXXXXX';

\Aldrapay\Logger::getInstance()->setLogLevel(\Aldrapay\Logger::INFO);

$transaction = new \Aldrapay\PaymentHostedPageOperation;

$transaction->money->setAmount(5.00);
$transaction->money->setCurrency('USD');
$transaction->setDescription('test');
$transaction->setTrackingId('my_custom_variable');
$transaction->setLanguage('en');
$transaction->setNotificationUrl('http://www.example.com/notify');
$transaction->setReturnUrl('http://www.example.com/return');

$transaction->customer->setFirstName('John');
$transaction->customer->setLastName('Doe');
$transaction->customer->setCountry('GB');
$transaction->customer->setAddress('Demo str 12');
$transaction->customer->setCity('London');
$transaction->customer->setZip('ATE223');
$transaction->customer->setIp('127.0.0.1');
$transaction->customer->setEmail('john@example.com');
$transaction->customer->setPhone('+441234567890');

$response = $transaction->submit();

if ($response->isSuccess() ) {

  $customerRedirect = new CustomerRedirectHostedPage($response->getRedirectUrl(), $response->getUid());
  $customerRedirect->money = $transaction->money;
  $customerRedirect->setTrackingId('my_custom_variable');
  $customerRedirect->setReturnUrl('http://www.example.com/return');
  $customerRedirect->setNotificationUrl('http://www.example.com/notify');

  header("Location: " . $customerRedirect->getFullRedirectUrl());
}
```

### Payment request via direct API

Simple usage looks like:

```php
require_once __DIR__ . 'PATH_TO_INSTALLED_LIBRARY/lib/Aldrapay.php';
\Aldrapay\Settings::$merchantId  = XXX;
\Aldrapay\Settings::$passCode = 'b8647b68898b084b';

\Aldrapay\Logger::getInstance()->setLogLevel(\Aldrapay\Logger::INFO);

$transaction = new \Aldrapay\Payment;

$transaction->money->setAmount(5.00);
$transaction->money->setCurrency('USD');
$transaction->setDescription('test order');
$transaction->setTrackingId('my_custom_variable');

$transaction->card->setCardNumber('4200000000000000');
$transaction->card->setCardHolder('John Doe');
$transaction->card->setCardExpMonth(1);
$transaction->card->setCardExpYear(2030);
$transaction->card->setCardCvc('123');

$transaction->customer->setFirstName('John');
$transaction->customer->setLastName('Doe');
$transaction->customer->setCountry('GB');
$transaction->customer->setAddress('Demo str 12');
$transaction->customer->setCity('London');
$transaction->customer->setZip('ATE223');
$transaction->customer->setIp('127.0.0.1');
$transaction->customer->setEmail('john@example.com');
$transaction->customer->setPhone('+441234567890');

$response = $transaction->submit();

if ($response->isSuccess()) {
  print("Status: " . $response->getStatus() . PHP_EOL);
  print("Transaction UID: " . $response->getUid() . PHP_EOL);
} elseif ($response->isFailed()) {
  print("Status: " . $response->getStatus() . PHP_EOL);
  print("Transaction UID: " . $response->getUid() . PHP_EOL);
  print("Reason: " . $response->getMessage() . PHP_EOL);
} else {
  print("Status: error" . PHP_EOL);
  print("Reason: " . $response->getMessage() . PHP_EOL);
}
```

## Examples

See the [examples](examples) directory for integration examples of different
transactions.

## Documentation

Visit https://secure.aldrapay.com/backoffice/docs/api/index.html for up-to-date documentation.

## Tests

To run tests

```bash
php -f ./test/Aldrapay.php
```
