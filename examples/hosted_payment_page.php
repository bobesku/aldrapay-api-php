<?php
use Aldrapay\CustomerRedirectHostedPage;
use Aldrapay\Money;

require_once __DIR__ . '/../lib/Aldrapay.php';
require_once __DIR__ . '/test_shop_data.php';

\Aldrapay\Logger::getInstance()->setLogLevel(\Aldrapay\Logger::DEBUG);

$transaction = new \Aldrapay\PaymentHostedPageOperation;

$amount = rand(2, 20);
$currency = 'USD';
$trackingId = 'ORDER-'.date('ymdHis');

$transaction->money->setAmount($amount);
$transaction->money->setCurrency($currency);
//$transaction->setDescription('Trx desc '.rand(100,1000));
$transaction->setTrackingId($trackingId);

$transaction->setReturnUrl('http://www.example.com/return');
$transaction->setNotificationUrl('http://www.example.com/notify');

$transaction->customer->setFirstName('John');
$transaction->customer->setLastName('Doe');
$transaction->customer->setCountry('GB');
$transaction->customer->setAddress('Demo Street 12');
$transaction->customer->setCity('London');
$transaction->customer->setZip('ATE223');
$transaction->customer->setIp('127.0.0.1');
$transaction->customer->setEmail('john@example.com');
$transaction->customer->setPhone('+441234567890');

$response = $transaction->submit();

print("Transaction message: " . $response->getMessage() . PHP_EOL);
print("Transaction status: " . $response->getStatus(). PHP_EOL);

if ($response->isValid() && !empty($response->getRedirectUrl())) {
  print("Transaction UID: " . $response->getUid() . PHP_EOL);
  
  $customerRedirect = new CustomerRedirectHostedPage($response->getRedirectUrl(), $response->getUid());
  $customerRedirect->money = $transaction->money;
  $customerRedirect->setTrackingId($trackingId);
  $customerRedirect->setReturnUrl('http://www.example.com/return');
  $customerRedirect->setNotificationUrl('http://www.example.com/notify');
  
  print("Full Redirect: " . $customerRedirect->getFullRedirectUrl() . PHP_EOL);
}
?>
