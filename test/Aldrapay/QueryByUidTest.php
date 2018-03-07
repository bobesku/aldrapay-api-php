<?php
namespace Aldrapay;

class QueryByUidTest extends TestCase {

  public function test_setUid() {
    $q = $this->getTestObjectInstance();

    $q->setUid('TEST-TRACK-1234567');

    $this->assertEqual($q->getUid(), 'TEST-TRACK-1234567');
  }

  public function test_endpoint() {

    $q = $this->getTestObjectInstance();
    $q->setUid('1234');

    $reflection = new \ReflectionClass('Aldrapay\QueryByUid');
    $method = $reflection->getMethod('_endpoint');
    $method->setAccessible(true);
    $url = $method->invoke($q, '_endpoint');

    $this->assertEqual($url, Settings::$gatewayBase . '/transaction/status');
  }

  public function test_queryRequest() {
    $amount = rand(10,40);

    $parent = $this->runParentTransaction($amount);

    $q = $this->getTestObjectInstance();

    $q->setUid($parent->getUid());

    $response = $q->submit();

    $this->assertTrue($response->isValid());
    $this->assertTrue($response->isSuccess());
    $this->assertNotNull($response->getUid());
    $this->assertEqual($parent->getUid(), $response->getUid());
  }

  public function test_queryResponseForUnknownUid() {
    $q = $this->getTestObjectInstance();

    $q->setUid('123456-UNKNOWN');

    $response = $q->submit();

    $this->assertTrue($response->isValid());

    $this->assertEqual($response->getMessage(), 'Failed');
    $this->assertEqual($response->getResponse()->reasonCode, 109);
  }

  protected function runParentTransaction($amount = 10.00, $trackId = null ) {
    self::authorizeFromEnv();

    $transaction = new PaymentOperation();

    $transaction->money->setAmount($amount);
    $transaction->money->setCurrency('USD');
    $transaction->setDescription('test status trx '.substr(self::getCurrentPhpVer(),0,strpos(self::getCurrentPhpVer(),'-')).'-'.date('YmdHi'));
    
    if ($trackId == null)
    	$transaction->setTrackingId('TRACK-'.substr(self::getCurrentPhpVer(),0,strpos(self::getCurrentPhpVer(),'-')).'-'.date('YmdHi'));
   	else
   		$transaction->setTrackingId($trackId);

    $transaction->card->setCardNumber('5453010000066167');
    $transaction->card->setCardHolder('John Doe');
    $transaction->card->setCardExpMonth(1);
    $transaction->card->setCardExpYear(2030);
    $transaction->card->setCardCvc('777');

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

    return $transaction->submit();
  }

  protected function getTestObjectInstance() {
    self::authorizeFromEnv();

    return new QueryByUid();
  }
}
?>
