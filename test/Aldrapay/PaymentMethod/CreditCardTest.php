<?php
namespace Aldrapay\PaymentMethod;

class CreditCardTest extends \Aldrapay\TestCase {

  public function test_getName() {
    $cc = $this->getTestObject();

    $this->assertEqual($cc->getName(), 'credit_card');
  }

  public function test_getParamsArray() {
    $cc = $this->getTestObject();

    $this->assertEqual($cc->getParamsArray(), array());
  }

  public function getTestObject() {
    return new CreditCard;
  }
}
