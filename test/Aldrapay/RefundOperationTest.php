<?php
namespace Aldrapay;

class RefundOperationTest extends TestCase {

  public function test_setParentUid() {
    $transaction = $this->getTestObjectInstance();
    $uid = '44444444-1D500CFBC1CAEA888888-00000001';

    $transaction->setParentUid($uid);

    $this->assertEqual($uid, $transaction->getParentUid());
  }

  public function test_buildRequestMessage() {
    $transaction = $this->getTestObject();
    $arr = array(
        'refundAmount' => 16.55,
        'transactionID' => '44444444-1D500CFBC1CAEA888888-00000001',
    );

    $reflection = new \ReflectionClass( 'Aldrapay\RefundOperation' );
    $method = $reflection->getMethod('_buildRequestMessage');
    $method->setAccessible(true);

    $request = $method->invoke($transaction, '_buildRequestMessage');

    $this->assertEqual($arr, $request);
  }

  public function test_endpoint() {

    $auth = $this->getTestObjectInstance();

    $reflection = new \ReflectionClass('Aldrapay\RefundOperation');
    $method = $reflection->getMethod('_endpoint');
    $method->setAccessible(true);
    $url = $method->invoke($auth, '_endpoint');

    $this->assertEqual($url, Settings::$gatewayBase . '/transaction/refund');

  }

  public function test_successRefundRequest() {

  	$amount = rand(10,40);
  	$trackIdSuccess = 'TRACK-'.date('YmdHi').'-REFUND-OK';
  	$parent = $this->runParentTransaction($amount, $trackIdSuccess);

    $transaction = $this->getTestObjectInstance();

    $transaction->money->setAmount($amount);
    $transaction->setParentUid($parent->getUid());

    $t_response = $transaction->submit();

    $this->assertTrue($t_response->isValid());
    $this->assertTrue($t_response->isSuccess());
    $this->assertNotNull($t_response->getUid());
    $this->assertEqual($t_response->getMessage(),'Approved');
    $this->assertNotNull($t_response->getResponse()->refundTransactionID);
    $this->assertNotIdentical($t_response->getResponse()->refundTransactionID,$parent->getUid());
  }

  public function test_errorRefundRequest() {
    
  	$amount = rand(10,40);
  	$trackIdError = 'TRACK-'.date('YmdHi').'-REFUND-ERR';
  	$parent = $this->runParentTransaction($amount, $trackIdError);

    $transaction = $this->getTestObjectInstance();

    $transaction->money->setAmount($amount + 100);
    $transaction->setParentUid($parent->getUid());

    $t_response = $transaction->submit();

    $this->assertTrue($t_response->isValid());
    $this->assertTrue($t_response->isError());
  }

  protected function runParentTransaction($amount = 10.00, $trackId = null ) {
    self::authorizeFromEnv();

    $transaction = new PaymentOperation();

    $transaction->money->setAmount($amount);
    $transaction->money->setCurrency('USD');
    $transaction->setDescription('test payment refund');
    
    if ($trackId == null)
    	$transaction->setTrackingId('TRACK-'.date('YmdHi'));
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

  protected function getTestObject() {
    $transaction = $this->getTestObjectInstance();

    $transaction->setParentUid('44444444-1D500CFBC1CAEA888888-00000001');
    
    $transaction->money->setAmount(16.55);

    return $transaction;

  }

  protected function getTestObjectInstance() {
    self::authorizeFromEnv();

    return new RefundOperation();
  }
}
?>
