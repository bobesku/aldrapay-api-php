<?php
namespace Aldrapay;

class QueryByUid extends ApiAbstract {
  protected $_uid;

  protected function _endpoint() {
    return Settings::$gatewayBase . '/transaction/status' . $this->getUid();
  }
  public function setUid($uid) {
    $this->_uid = $uid;
  }
  public function getUid() {
    return $this->_uid;
  }
  protected function _buildRequestMessage() {
    return '';
  }
}
?>
