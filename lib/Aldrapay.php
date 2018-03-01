<?php

// Tested on PHP 5.3

// This snippet (and some of the curl code) due to the Facebook SDK.
if (!function_exists('curl_init')) {
  throw new Exception('Aldrapay needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Aldrapay needs the JSON PHP extension.');
}
if (!function_exists('mb_detect_encoding')) {
  throw new Exception('Aldrapay needs the Multibyte String PHP extension.');
}

if (!class_exists('\Aldrapay\Settings')) {
  require_once (__DIR__ . '/Aldrapay/Settings.php');
  require_once (__DIR__ . '/Aldrapay/Logger.php');
  require_once (__DIR__ . '/Aldrapay/Language.php');
  require_once (__DIR__ . '/Aldrapay/Customer.php');
  require_once (__DIR__ . '/Aldrapay/Card.php');
  require_once (__DIR__ . '/Aldrapay/Money.php');
  require_once (__DIR__ . '/Aldrapay/ResponseBase.php');
  require_once (__DIR__ . '/Aldrapay/Response.php');
  require_once (__DIR__ . '/Aldrapay/ResponseCheckout.php');
  require_once (__DIR__ . '/Aldrapay/ApiAbstract.php');
  require_once (__DIR__ . '/Aldrapay/ChildTransaction.php');
  require_once (__DIR__ . '/Aldrapay/GatewayTransport.php');
  require_once (__DIR__ . '/Aldrapay/AuthorizationOperation.php');
  require_once (__DIR__ . '/Aldrapay/PaymentOperation.php');
  require_once (__DIR__ . '/Aldrapay/CaptureOperation.php');
  require_once (__DIR__ . '/Aldrapay/VoidOperation.php');
  require_once (__DIR__ . '/Aldrapay/RefundOperation.php');
  require_once (__DIR__ . '/Aldrapay/CreditOperation.php');
  require_once (__DIR__ . '/Aldrapay/QueryByUid.php');
  require_once (__DIR__ . '/Aldrapay/PaymentMethod/Base.php');
  require_once (__DIR__ . '/Aldrapay/PaymentMethod/CreditCard.php');
}
?>
