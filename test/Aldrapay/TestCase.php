<?php
namespace Aldrapay;

class TestCase extends \UnitTestCase {

  const MERCHANT_ID = 457;
  const PASS_CODE = 't7Dop1ql%!nD';
  const PSIGN_ALGORITHM = 'sha1';
  const REMOTE_URL = 'https://secure.aldrapay.com';
  
  public $phpVer = '0';

  public static function getCurrentPhpVer() {
  	$phpVer = null;
  	$phpVer = getenv('RUNNABLE_PHPVER');
	if (!$phpVer)
		$phpVer = phpversion();
  }
  
  public static function authorizeFromEnv() {
    $shop_id = null;
    $shop_key = null;

    
    $shop_id = getenv('MERCHANT_ID');
    if (!$shop_id) {
        $shop_id = self::MERCHANT_ID;
    }
    $shop_key = getenv('PASS_CODE');
    if (!$shop_key) {
        $shop_key = self::PASS_CODE;
    }
    $psign_algorithm = getenv('PSIGN_ALGORITHM');
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
