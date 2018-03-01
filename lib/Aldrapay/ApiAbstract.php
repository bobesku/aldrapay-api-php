<?php
namespace Aldrapay;

abstract class ApiAbstract {
  protected abstract function _buildRequestMessage();
  protected $_language;

  public function submit() {
    try {
      $response = $this->_remoteRequest();
    } catch (\Exception $e) {
      $msg = $e->getMessage();
      $response = '{ "errors":"' . $msg . '", "message":"' . $msg . '" }';
    }
    return new Response($response);
  }

  protected function _remoteRequest() {
    return GatewayTransport::submit( Settings::$merchantId, Settings::$passCode , $this->_endpoint(), $this->_buildRequestMessage() );
  }

  protected function _endpoint() {
  		
  	switch($this->_getTransactionType()){
  		case 'Authorization':
		    return Settings::$gatewayBase . '/transaction/authorize' ;
  		case 'Payment':
		    return Settings::$gatewayBase . '/transaction/execute' ;
  		case 'Refund':
  		case 'Credit':
		    return Settings::$gatewayBase . '/transaction/refund' ;
  		case 'Capture':
		    return Settings::$gatewayBase . '/transaction/capture' ;
  		case 'Void':
		    return Settings::$gatewayBase . '/transaction/void' ;
  		default:
		    return Settings::$gatewayBase . '/transaction/execute' ;
  	}
		    
  }

  protected function _getTransactionType() {
    list($module,$klass) = explode('\\', get_class($this));
    $klass = str_replace('Operation', '', $klass);
    $klass = strtolower($klass) . 's';
    return $klass;
  }
  public function setLanguage($language_code) {
    if (in_array($language_code, Language::getSupportedLanguages())) {
      $this->_language = $language_code;
    }else{
      $this->_language = Language::getDefaultLanguage();
    }
  }

  public function getLanguage() {
    return $this->_language;
  }
}
?>
