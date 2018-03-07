<?php
namespace Aldrapay;

class GatewayTransport {

    public static function submit($merchant_id, $pass_code, $host, $t_request) {
		
    	error_log('GatewayTransport/submit init');
    	
    	$pSign = ['pSign' => hash(Settings::$pSignAlgorithm, Settings::$passCode.Settings::$merchantId.implode('',array_values($t_request)))];
    	$t_request = array_merge(array('merchantID' => $merchant_id), $t_request, $pSign);
    	
        $process = curl_init($host);
        $requestHttpPost = http_build_query($t_request);

        Logger::getInstance()->write("Request to $host", Logger::DEBUG, get_class() );
        Logger::getInstance()->write("with Merchant Id " . Settings::$merchantId . " & Pass Code " . Settings::$passCode, Logger::DEBUG, get_class() );
        if (!empty($requestHttpPost))
          Logger::getInstance()->write("with message " .  $requestHttpPost, Logger::DEBUG, get_class());

        if (!empty($t_request)) {
          //curl_setopt($process, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-type: application/json'));
          curl_setopt($process, CURLOPT_POST, 1);
          curl_setopt($process, CURLOPT_POSTFIELDS, $requestHttpPost);
        }
        curl_setopt($process, CURLOPT_URL, $host);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($process, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($process);
    	error_log('GatewayTransport/submit curl executed');
        $error = curl_error($process);
        curl_close($process);

        if ($response === false) {
	    	error_log('GatewayTransport/submit curl error: '.$error);
          throw new \Exception("cURL error " . $error);
        }

        Logger::getInstance()->write("Response $response", Logger::DEBUG, get_class() );
        return $response;
    }
    
    public static function submitGetParams($merchant_id, $pass_code, $host, $t_request) {

    	$pSign = ['pSign' => hash(Settings::$pSignAlgorithm, Settings::$passCode.Settings::$merchantId.implode('',array_values($t_request)))];
    	$t_request = array_merge(array('merchantID' => $merchant_id), $t_request, $pSign);
    	
        return $t_request;
    }
}
?>
