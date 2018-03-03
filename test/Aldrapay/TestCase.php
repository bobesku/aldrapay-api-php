<?php
namespace Aldrapay;

class TestCase extends \UnitTestCase {

  const SHOP_ID = 457;
  const SHOP_KEY = 't7Dop1ql%!nD';
  const PSIGN_ALGORITHM = 'sha1';
  const REMOTE_URL = 'https://secure.aldrapay.com';

  public static function authorizeFromEnv() {
    $shop_id = null;
    $shop_key = null;

    
    $shop_id = getenv('SHOP_ID');
    if (!$shop_id) {
        $shop_id = self::SHOP_ID;
    }
    $shop_key = getenv('SHOP_SECRET_KEY');
    if (!$shop_key) {
        $shop_key = self::SHOP_KEY;
    }
    $psign_algorithm = getenv('SHOP_PUB_KEY');
    if (!$psign_algorithm) {
        $psign_algorithm = self::PSIGN_ALGORITHM;
    }
    $remote_endpoint = getenv('REMOTE_URL');
    if (!$remote_endpoint) {
        $remote_endpoint = self::REMOTE_URL;
    }

    Settings::$merchantId = $shop_id;
    Settings::$passCode = $shop_key;
    Settings::$pSignAlgorithm = $psign_algorithm;
    Settings::$apiBase = Settings::$gatewayBase = Settings::$checkoutBase = $remote_endpoint; 
  }
}
?>
