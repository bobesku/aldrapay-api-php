<?php
namespace Aldrapay\PaymentMethod;

class EmexvoucherTest extends \Aldrapay\TestCase {

  public function test_getName() {
    $emexvoucher = $this->getTestObject();

    $this->assertEqual($emexvoucher->getName(), 'emexvoucher');
  }

  public function test_getParamsArray() {
    $emexvoucher = $this->getTestObject();

    $this->assertEqual($emexvoucher->getParamsArray(), array());
  }

  public function getTestObject() {
    return new Emexvoucher;
  }
}
