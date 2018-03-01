<?php
require_once __DIR__ . '/../lib/Aldrapay.php';
require_once __DIR__ . '/test_shop_data.php';

\Aldrapay\Logger::getInstance()->setLogLevel(\Aldrapay\Logger::DEBUG);

$transaction = new \Aldrapay\Payment;

$transaction->setTrackingId('20180301-ABAB5C9C372F8B44D72C-F1BFC227E65FA9373298');

$response = $transaction->submit();

print("Transaction message: " . $response->getMessage() . PHP_EOL);
print("Transaction status: " . $response->getStatus(). PHP_EOL);

if ($response->isSuccess() ) {
  print("Transaction UID: " . $response->getUid() . PHP_EOL);
  print("Trying to Query by UID " . $response->getUid() . PHP_EOL);

  $query = new \Aldrapay\QueryByUid;
  $query->setUid($response->getUid());

  $query_response = $query->submit();

  print_r($query_response);
}
?>
