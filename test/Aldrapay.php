<?php
echo "Running the Aldrapay PHP bindings test suite.\n".
     "If you're trying to use the PHP bindings you'll probably want ".
     "to require('lib/Aldrapay.php'); instead of this file\n\n" .
     "Setup the env variable LOG_LEVEL=DEBUG for more verbose output\n" ;

$ok = @include_once(dirname(__FILE__).'/simpletest/autorun.php');
if (!$ok) {
  echo "MISSING DEPENDENCY: The Aldrapay API test cases depend on SimpleTest. ".
       "Download it at <http://www.simpletest.org/>, and either install it ".
       "in your PHP include_path or put it in the test/ directory.\n";
  exit(1);
}

require_once(dirname(__FILE__) . '/../lib/Aldrapay.php');
// Throw an exception on any error
function exception_error_handler($errno, $errstr, $errfile, $errline) {
  throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler('exception_error_handler');
error_reporting(E_ALL | E_STRICT);

require_once(dirname(__FILE__) . '/../lib/Aldrapay.php');


$log_level = getenv('LOG_LEVEL');

if ($log_level == 'DEBUG') {
  \Aldrapay\Logger::getInstance()->setLogLevel(\Aldrapay\Logger::DEBUG);
} else {
  \Aldrapay\Logger::getInstance()->setLogLevel(\Aldrapay\Logger::INFO);
}

require_once(dirname(__FILE__) . '/Aldrapay/TestCase.php');
// require_once(dirname(__FILE__) . '/Aldrapay/MoneyTest.php');
// require_once(dirname(__FILE__) . '/Aldrapay/CustomerTest.php');
// require_once(dirname(__FILE__) . '/Aldrapay/AuthorizationOperationTest.php');
// require_once(dirname(__FILE__) . '/Aldrapay/PaymentOperationTest.php');
// require_once(dirname(__FILE__) . '/Aldrapay/CaptureOperationTest.php');
// require_once(dirname(__FILE__) . '/Aldrapay/VoidOperationTest.php');
// require_once(dirname(__FILE__) . '/Aldrapay/RefundOperationTest.php');
// require_once(dirname(__FILE__) . '/Aldrapay/QueryByUidTest.php');
// require_once(dirname(__FILE__) . '/Aldrapay/WebhookTest.php');
require_once(dirname(__FILE__) . '/Aldrapay/GatewayExceptionTest.php');
// require_once(dirname(__FILE__) . '/Aldrapay/PaymentMethod/CreditCardTest.php');
?>
