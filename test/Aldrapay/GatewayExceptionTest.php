<?php
namespace Aldrapay;

class GatewayTransportExceptionTest extends TestCase {

  private $_apiBase;
	
  function setUp() {
    $this->_apiBase = Settings::$gatewayBase;
    Settings::$gatewayBase = 'https://thedomaindoesntexist.Aldrapaynotexist.com';
  }

  function tearDown() {
    Settings::$gatewayBase = $this->_apiBase;
  }

  public function test_networkIssuesHandledCorrectly() {
    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount($amount);
    $cents = $auth->money->getCents();
	
    $this->setUp();
    $response = $auth->submit();
    $this->tearDown();
    
    $this->assertTrue($response->isValid());
  }
  
  public function test_processingIssuesHandledCorrectly() {
    $auth = $this->getTestObject();

    $amount = rand(0,10000) / 100;

    $auth->money->setAmount($amount);
    $cents = $auth->money->getCents();

    $response = $auth->submit();
    
    $this->assertTrue($response->isError());
    $this->assertEqual($response->getMessage(), 'Missing parameter -> merchantID');
  }

  protected function getTestObject($threed = false) {

    $transaction = $this->getTestObjectInstance($threed);

    $transaction->money->setAmount(12.33);
    $transaction->money->setCurrency('EUR');
    $transaction->setDescription('test');
    $transaction->card->setCardNumber('4200000000000000');
    $transaction->customer->setFirstName('John');
    $transaction->customer->setLastName('Doe');
    $transaction->customer->setAddress('Demo str 12');
    $transaction->customer->setCity('Riga');
    $transaction->customer->setZip('LV-1082');

    return $transaction;
  }

  protected function getTestObjectInstance($threed = false) {
    self::authorizeFromEnv($threed);

    return new AuthorizationOperation();
  }


}
?>
