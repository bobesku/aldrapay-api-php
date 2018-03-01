<?php

namespace Aldrapay;

class Settings {
  public static $merchantId;
  public static $passCode;
  public static $shopPubKey;
  public static $gatewayBase  = 'https://secure.aldrapay.com/transaction/execute';
  public static $checkoutBase = 'https://secure.aldrapay.com/transaction/customerDirect';
  public static $apiBase      = 'https://secure.aldrapay.com';
}
?>
