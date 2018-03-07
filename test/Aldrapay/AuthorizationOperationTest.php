<?php
namespace Aldrapay;

class AuthorizationOperationTest extends TestCase {

  public function test_setDescription() {

    $auth = $this->getTestObjectInstance();

    $description = 'Test desc '.substr(phpversion(),0,strpos(phpversion(),'-')).'-'.date('YmdHi');

    $auth->setDescription($description);

    $this->assertEqual($auth->getDescription(), $description);
  }

  public function test_setTrackingId() {
    $auth = $this->getTestObjectInstance();
    $tracking_id = 'Test tracking_id '.substr(phpversion(),0,strpos(phpversion(),'-')).'-'.date('YmdHi');
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

  public function test_buildRequestMessage() {
    $auth = $this->getTestObject();
    
    $arr = array(
    		'amount' => 12.33,
    		'currency' => 'USD',
    		'orderID' => 'TRACK-'.substr(phpversion(),0,strpos(phpversion(),'-')).'-'.date('YmdHi'),
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
    		'description' => 'test auth '.substr(phpversion(),0,strpos(phpversion(),'-')).'-'.date('YmdHi'),
    );
    
    $reflection = new \ReflectionClass( 'Aldrapay\AuthorizationOperation');
    $method = $reflection->getMethod('_buildRequestMessage');
    $method->setAccessible(true);
    $request = $method->invoke($auth, '_buildRequestMessage');

    $this->assertEqual($arr, $request);
  }

  public function test_endpoint() {

    $auth = $this->getTestObjectInstance();

    $reflection = new \ReflectionClass('Aldrapay\AuthorizationOperation');
    $method = $reflection->getMethod('_endpoint');
    $method->setAccessible(true);
    $url = $method->invoke($auth, '_endpoint');

    $this->assertEqual($url, Settings::$gatewayBase . '/transaction/authorize');
  }

  public function test_successAuthorization() {
    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount($amount);
    $auth->setTrackingId($auth->getTrackingId().'-AUTH-OK');
    $cents = $auth->money->getCents();

    $response = $auth->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isSuccess());
    $this->assertEqual($response->getMessage(), 'Authorized');
    $this->assertNotNull($response->getUid());
    $this->assertEqual($response->getStatus(), '10');
    $this->assertEqual($amount, $response->getResponse()->transaction->amount);

    $arResponse = $response->getResponseArray();
    $this->assertEqual($amount, $arResponse['transaction']['amount']);
  }

  public function test_declinedAuthorization() {
    $auth = $this->getTestObject(true);

    $amount = rand(0,10000) / 100;

    $auth->setTrackingId($auth->getTrackingId().'-AUTH-DECL');
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

    $arResponse = $response->getResponseArray();
    $this->assertEqual($amount, $arResponse['transaction']['amount']);
  }

  public function test_failedAuthorization() {
    $auth = $this->getTestObject(true);

    $amount = rand(0,10000) / 100;

    $auth->setTrackingId($auth->getTrackingId().'-AUTH-FAIL');
    $auth->money->setAmount($amount);
    $auth->card->setCardCvc('003');
    $cents = $auth->money->getCents();

    $response = $auth->submit();

    $this->assertTrue($response->isFailed());
    $this->assertEqual('Failed', $response->getMessage());
    $this->assertEqual($response->getStatus(), '3');
  }


  public function test_errorAuthorization() {

    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;

    $auth->setTrackingId($auth->getTrackingId().'-AUTH-ERR');
    $auth->money->setAmount($amount);
    $cents = $auth->money->getCents();
    $auth->card->setCardExpYear(10);

    $response = $auth->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isError());
    $this->assertEqual($response->getMessage(), 'Invalid length of field -> cardExpiryDate | min:4, max:4');
    $this->assertEqual($response->getStatus(), '3');
  }

  protected function getTestObject($threed = false) {

    $transaction = $this->getTestObjectInstance($threed);
    
    $transaction->money->setAmount(12.33);
    $transaction->money->setCurrency('USD');
    $transaction->setDescription('test');
    $transaction->setTrackingId('TRACK-'.substr(phpversion(),0,strpos(phpversion(),'-')).'-'.date('YmdHi'));
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

  protected function getTestObjectInstance($threeds = false) {
    self::authorizeFromEnv($threeds);

    return new AuthorizationOperation();
  }
}
?>
