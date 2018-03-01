<?php

namespace Aldrapay;

class Settings {
  public static $merchantId;
  public static $passCode;
  public static $pSignAlgorithm = 'sha1';
  public static $gatewayBase  = 'https://secure.aldrapay.com/transaction/execute';
  public static $checkoutBase = 'https://secure.aldrapay.com/transaction/customerDirect';
  public static $apiBase      = 'https://secure.aldrapay.com';
}
?>
