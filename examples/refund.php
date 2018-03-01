<?php
require_once __DIR__ . '/../lib/Aldrapay.php';
require_once __DIR__ . '/test_shop_data.php';

\Aldrapay\Logger::getInstance()->setLogLevel(\Aldrapay\Logger::DEBUG);

$transaction = new \Aldrapay\PaymentOperation;

$amount = rand(100, 10000);

$transaction->money->setAmount($amount);
$transaction->money->setCurrency('EUR');
$transaction->setDescription('test');
$transaction->setTrackingId('my_custom_variable');

$transaction->card->setCardNumber('4200000000000000');
$transaction->card->setCardHolder('John Doe');
$transaction->card->setCardExpMonth(1);
$transaction->card->setCardExpYear(2030);
$transaction->card->setCardCvc('123');

$transaction->customer->setFirstName('John');
$transaction->customer->setLastName('Doe');
$transaction->customer->setCountry('LV');
$transaction->customer->setAddress('Demo str 12');
$transaction->customer->setCity('Riga');
$transaction->customer->setZip('LV-1082');
$transaction->customer->setIp('127.0.0.1');
$transaction->customer->setEmail('john@example.com');

$response = $transaction->submit();

print("Transaction message: " . $response->getMessage() . PHP_EOL);
print("Transaction status: " . $response->getStatus(). PHP_EOL);

if ($response->isSuccess() ) {
  print("Transaction UID: " . $response->getUid() . PHP_EOL);
  print("Trying to Refund transaction " . $response->getUid() . PHP_EOL);

  $refund = new \Aldrapay\RefundOperation;
  $refund->setParentUid($response->getUid());
  $refund->money->setAmount($transaction->money->getAmount());
  $refund->setReason('customer request');

  $refund_response = $refund->submit();

  if ($refund_response->isSuccess()) {
    print("Refund successfuly. Refund transaction UID " . $refund_response->getUid() . PHP_EOL);
  }else{
    print("Problem to refund" . PHP_EOL);
    print("Refund message: " . $refund_response->getMessage() . PHP_EOL);
  }
}
?>

