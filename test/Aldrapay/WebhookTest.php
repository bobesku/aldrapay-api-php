<?php
namespace Aldrapay;

class WebhookTest extends TestCase {

  public function test_WebhookIsSentWithCorrectCredentials() {
   
    $w = $this->getTestObjectInstance();
    parse_str($this->webhookMessage(), $testWebhookMessage);
    
    $reflection = new \ReflectionClass('Aldrapay\Webhook');
    $property = $reflection->getProperty('_responseArray');
    $property->setAccessible(true);
    $property->setValue($w,$testWebhookMessage);

    $this->assertTrue($w->isAuthorized());
  }
    
  public function test_WebhookIsSentWithIncorrectCredentials() {

    $w = $this->getTestObjectInstance();
    parse_str($this->webhookMessage('failed', '1234567890123456789012345678901234567890'), $testWebhookMessage);

    $reflection = new \ReflectionClass('Aldrapay\Webhook');
    $property = $reflection->getProperty('_responseArray');
    $property->setAccessible(true);
    $property->setValue($w,$testWebhookMessage);

    $this->assertFalse($w->isAuthorized());
  }

  public function test_RequestIsValidAndItIsSuccess() {
    
  	$w = $this->getTestObjectInstance();
  	parse_str($this->webhookMessage(), $testWebhookMessage);

    $reflection = new \ReflectionClass('Aldrapay\Webhook');
    $property = $reflection->getProperty('_responseArray');
    $property->setAccessible(true);
    $property->setValue($w,$testWebhookMessage);

    $property2 = $reflection->getProperty('_response');
    $property2->setAccessible(true);
    $property2->setValue($w,json_decode(json_encode($testWebhookMessage)));

    $this->assertTrue($w->isValid());
    $this->assertTrue($w->isSuccess());
    $this->assertNotNull($w->getUid());
    $this->assertEqual($w->getStatus(), '1');
  }


  public function test_RequestIsValidAndItIsDeclined() {

  	$w = $this->getTestObjectInstance();
  	parse_str($this->webhookMessage('failed'), $testWebhookMessage);

    $reflection = new \ReflectionClass('Aldrapay\Webhook');
    $property = $reflection->getProperty('_responseArray');
    $property->setAccessible(true);
    $property->setValue($w,$testWebhookMessage);
    
    $property2 = $reflection->getProperty('_response');
    $property2->setAccessible(true);
    $property2->setValue($w,json_decode(json_encode($testWebhookMessage)));

    $this->assertTrue($w->isValid());
    $this->assertTrue($w->isDeclined());
    $this->assertNotNull($w->getUid());
    $this->assertEqual($w->getStatus(), '2');

  }


  protected function getTestObjectInstance() {
    self::authorizeFromEnv();

    return new Webhook();
  }

  private function webhookMessage($status = 'successful', $pSign = null ) {
  	
    if ($status == 'successful') {
      $responseCode = '1';
      $reasonCode = '1';
      
	  if ($pSign == null)
	  	$pSign = '0a19546e8c42bf5dd17aef7e8c04cd0e8e9ad1f4';
	  
    }else{
      $responseCode = '2';
      $reasonCode = '2';

	  if ($pSign == null)
	  	$pSign = 'b2f3483989d3e36f63c9737b8015d08005bbd5cd';
    }

    return <<<EOD
responseCode={$responseCode}&reasonCode={$reasonCode}&transactionID=20180307-015F95668EF6280D447E-37AD3938DC130CB20206&amount=10.00&currency=USD&orderID=118_44664e5619fc51c0f45992309992589b&executed=2018-03-07+00%3A13%3A39&pSign={$pSign}
EOD;
  }
}
?>