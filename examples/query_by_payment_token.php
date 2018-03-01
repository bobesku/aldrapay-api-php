<?php
require_once __DIR__ . '/../lib/Aldrapay.php';
require_once __DIR__ . '/test_shop_data.php';

\Aldrapay\Logger::getInstance()->setLogLevel(\Aldrapay\Logger::DEBUG);
$token = $argv[1];
print("Trying to Query by Payment token " . $token . PHP_EOL);

$query = new \Aldrapay\QueryByToken;
$query->setToken($token);

$query_response = $query->submit();

print_r($query_response);
?>
