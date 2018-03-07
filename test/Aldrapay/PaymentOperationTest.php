<?php
namespace Aldrapay;

class PaymentOperationTest extends TestCase {

  public function test_setDescription() {

    $auth = $this->getTestObjectInstance();

    $description = 'Test desc '.substr($this->phpVer,0,strpos($this->phpVer,'-')).'-'.date('YmdHi');

    $auth->setDescription($description);

    $this->assertEqual($auth->getDescription(), $description);
  }

  public function test_setTrackingId() {

    $auth = $this->getTestObjectInstance();

    $tracking_id = 'Test tracking_id '.substr($this->phpVer,0,strpos($this->phpVer,'-')).'-'.date('YmdHi');

    $auth->setTrackingId($tracking_id);
    $this->assertEqual($auth->getTrackingId(), $tracking_id);
  }

  public function test_setNotificationUrl() {

    $auth = $this->getTestObjectInstance();

    $url = 'http://www.example.com';

    $auth->setNotificationUrl($url);

    $this->assertEqual($auth->getNotificationUrl(), $url);
  }

  public function test_setReturnUrl() {

    $auth = $this->getTestObjectInstance();

    $url = 'http://www.example.com';

    $auth->setReturnUrl($url);

    $this->assertEqual($auth->getReturnUrl(), $url);

  }

  public function test_endpoint() {

    $auth = $this->getTestObjectInstance();

    $reflection = new \ReflectionClass('Aldrapay\PaymentOperation');
    $method = $reflection->getMethod('_endpoint');
    $method->setAccessible(true);
    $url = $method->invoke($auth, '_endpoint');

    $this->assertEqual($url, Settings::$gatewayBase . '/transaction/execute');

  }

  public function test_buildRequestMessage() {
    $auth = $this->getTestObject();
    $arr = array(
    		'amount' => 12.33,
    		'currency' => 'USD',
    		'orderID' => 'TRACK-'.substr($this->phpVer,0,strpos($this->phpVer,'-')).'-'.date('YmdHi'),
    		'returnURL' => '',
    		'notifyURL' => '',
    		'customerIP' => '127.0.0.1',
    		//'customerForwardedIP' => $this->customer->getIP(),
    		//'customerUserAgent' => 'n/a',
    		//'customerAcceptLanguage' => $this->getLanguage(),
    		'customerEmail' => 'john@example.com',
    		'customerPhone' => '+447941622127',
    		'customerFirstName' => 'John',
    		'customerLastName' => 'Doe',
    		'customerAddress1' => 'Street 45',
    		//'customerAddress2' => $this->customer->getAddress(),
    		'customerCity' => 'London',
    		'customerZipCode' => 'ATE223',
    		'customerStateProvince' => 'London',
    		'customerCountry' => 'GB',
    		'cardNumber' => '4111110000000112',
    		'cardCVV2' => '001',
    		'cardExpiryDate' => '0130',
    		'cardHolderName' => 'John Doe',
    		//'cardHolderName' => $this->customer->getFirstName() . $this->customer->getLastName(),
    		//'saveCard' => $this->registerToken(),
    		'description' => 'payment test '.substr($this->phpVer,0,strpos($this->phpVer,'-')).'-'.date('YmdHi'),
    );

    $reflection = new \ReflectionClass( 'Aldrapay\PaymentOperation');
    $method = $reflection->getMethod('_buildRequestMessage');
    $method->setAccessible(true);

    $request = $method->invoke($auth, '_buildRequestMessage');

    $this->assertEqual($arr, $request);
  }


  public function test_successPayment() {
    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;
    $auth->setTrackingId($auth->getTrackingId().'SUCCESS');
    $auth->money->setAmount($amount);
    $cents = $auth->money->getCents();

    $response = $auth->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isSuccess());
    $this->assertEqual($response->getMessage(), 'Approved');
    $this->assertNotNull($response->getUid());
    $this->assertEqual($response->getStatus(), '1');
    $this->assertEqual($amount, $response->getResponse()->transaction->amount);

  }

  public function test_declinedPayment() {
    $auth = $this->getTestObject(true);

    $amount = rand(0,10000) / 100;
    $auth->setTrackingId($auth->getTrackingId().'DECLINED');
    $auth->money->setAmount($amount);
    $auth->card->setCardCvc('002');
    $cents = $auth->money->getCents();

    $response = $auth->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isDeclined());
    $this->assertEqual('Declined', $response->getMessage());
    $this->assertNotNull($response->getUid());
    $this->assertEqual($response->getStatus(), '2');
    $this->assertEqual($amount, $response->getResponse()->transaction->amount);

  }
  
  public function test_failedPayment() {
    $auth = $this->getTestObject(true);

    $amount = rand(0,10000) / 100;
    $auth->setTrackingId($auth->getTrackingId().'DECLINED');
    $auth->money->setAmount($amount);
    $auth->card->setCardCvc('003');
    $cents = $auth->money->getCents();

    $response = $auth->submit();

    $this->assertTrue($response->isFailed());
    $this->assertEqual('Failed', $response->getMessage());
    $this->assertEqual($response->getStatus(), '3');
  }


  protected function getTestObject($threed = false) {

    $transaction = $this->getTestObjectInstance($threed);
    
    $transaction->money->setAmount(12.33);
    $transaction->money->setCurrency('USD');
    $transaction->setDescription('payment test '.substr($this->phpVer,0,strpos($this->phpVer,'-')).'-'.date('YmdHi'));
    $transaction->setTrackingId('TRACK-'.substr($this->phpVer,0,strpos($this->phpVer,'-')).'-'.date('YmdHi'));
    $transaction->setLanguage('en');

    $transaction->card->setCardNumber('4111110000000112');
    $transaction->card->setCardHolder('John Doe');
    $transaction->card->setCardExpMonth(1);
    $transaction->card->setCardExpYear(2030);
    $transaction->card->setCardCvc('001');

    $transaction->customer->setFirstName('John');
    $transaction->customer->setLastName('Doe');
    $transaction->customer->setCountry('GB');
    $transaction->customer->setState('London');
    $transaction->customer->setAddress('Street 45');
    $transaction->customer->setCity('London');
    $transaction->customer->setZip('ATE223');
    $transaction->customer->setIp('127.0.0.1');
    $transaction->customer->setEmail('john@example.com');
    $transaction->customer->setPhone('+447941622127');

    return $transaction;
  }

  protected function getTestObjectInstance($threed = false) {
    self::authorizeFromEnv($threed);

    return new PaymentOperation();
  }
}
?>
