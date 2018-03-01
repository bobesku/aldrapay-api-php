<?php
require_once __DIR__ . '/../lib/Aldrapay.php';
require_once __DIR__ . '/test_shop_data.php';

\Aldrapay\Logger::getInstance()->setLogLevel(\Aldrapay\Logger::DEBUG);

$transaction = new \Aldrapay\PaymentOperation;

$amount = rand(2, 20);

$transaction->money->setAmount($amount);
$transaction->money->setCurrency('EUR');
//$transaction->setDescription('Trx desc '.rand(100,1000));
$transaction->setTrackingId('ORDER-'.date('ymdHis'));

$transaction->card->setCardNumber('4200000000000000');
$transaction->card->setCardHolder('John Doe');
$transaction->card->setCardExpMonth(1);
$transaction->card->setCardExpYear(2030);
$transaction->card->setCardCvc('123');

$transaction->customer->setFirstName('John');
$transaction->customer->setLastName('Doe');
$transaction->customer->setCountry('LV');
$transaction->customer->setAddress('Demostr12');
$transaction->customer->setCity('Riga');
$transaction->customer->setZip('LV-1082');
$transaction->customer->setIp('86.120.93.21');
$transaction->customer->setEmail('john@example.com');

$response = $transaction->submit();

print("Transaction message: " . $response->getMessage() . PHP_EOL);
print("Transaction status: " . $response->getStatus(). PHP_EOL);

if ($response->isSuccess() || $response->isFailed() ) {
  print("Transaction UID: " . $response->getUid() . PHP_EOL);
}
?>
