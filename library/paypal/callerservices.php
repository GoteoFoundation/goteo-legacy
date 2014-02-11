<?php
/****************************************************
callerservices.php

This file uses the constants.php to get parameters including the PayPal Webservice Host URL needed
to make an API call and calls the server.

Called by PayReceipt.php, PaymentDetails, etc.,

****************************************************/
require_once 'paypal_config.php' ;
require_once 'log.php';

class CallerServices {
	
	/*
	 * public variables
	 */
	
    
    
    /*
     * Error ID
     */
    public $error_id = '';
    
    /*
     * Error Message  
     */
    public $error_message = '';
    
    /*
     * Result FAILURE or SUCCESS
     */
    public $isSuccess;

    /*
     * Sandbox Email Address
     */
    public $sandBoxEmailAddress;
    
    /*
     * Last Error
     */
    private $LastError;
	
      	
   	/*
   	 * Calls the actual WEB Service and returns the response.
   	 */
   	function call($request,$serviceName) {
		
		$response = null;
		
		try {
			
		    $endpoint=API_BASE_ENDPOINT.$serviceName;
		 
		    $response = call($request, $endpoint, $this->sandBoxEmailAddress);
		    $isFault = false;
			if(empty($response) || trim($response) == '')
	   		{
	   			$isFault = true;
				$fault = new FaultMessage();
				$errorData = new ErrorData();
				$errorData->errorId = 'API Error' ;
		  		$errorData->message = 'response is empty.' ;
		  		$fault->error = $errorData;
				
		  		$this->isSuccess = 'Failure' ;
		  		$this->LastError = $fault;
		        $response = null;
	   			
	   		}
	   		else
	   		{      
		   		$isFault = false;
		   	
		   		$this->isSuccess = 'Success' ;
		        $response = SoapEncoder::Decode($response, $isFault);
				if($isFault)
		        {
		        	$this->isSuccess = 'Failure' ;
		        	$this->LastError = $response ;
		        	$response = null ;
		        }
	        }
		}
		catch(Exception $ex) {
			die('Error occurred in call method');
		}
	   return $response;
	}
	
	
	/*
   	 * Calls the actual WEB Service and returns the response.
   	 */
	function callWebService($request,$serviceName,$simpleXML)
	{
		$response = null;
		
		try {
			
		    $endpoint=API_BASE_ENDPOINT.$serviceName;
		    $response = call($request, $endpoint, $this->sandBoxEmailAddress,$simpleXML);
		}
		catch(Exception $ex) {
			die('Error occurred in callWebService method');
		}
	   return $response;
	}
	

    /*
     * Returns Error ID
     */
    function getErrorId() {
		$errorId  = '';
		if($this->LastError != null) {
		
	     	if(is_array($this->LastError->error))
	        {
	        	$errorId  = $this->LastError->error[0]->errorId ;
	        }
	        else
	        {
	        	$errorId  = $this->LastError->error->errorId ;
	        }
		}
        return $errorId ;

    }

    /*
     * Returns Error Message
     */
    function getErrorMessage() {
    	$errorMessage = '';
    	if($this->LastError != null) {
    		
    		if(is_array($this->LastError->error))
	        {
	        	$errorMessage = $this->LastError->error[0]->message ;
	        }
	        else
	        {
	        	$errorMessage = $this->LastError->error->message ;
	        }
    	}
        return $errorMessage ;

    }
    
    /*
     * Returns Last error
     */
	public function getLastError()
   	{
   		return $this->LastError;
   	}
/*
     * Sets the Last error
     */
	public function setLastError($error)
   	{
   		$this->LastError = $error;
   	}
}

/**
  * call: Function to perform the API call to PayPal using API signature
  * @methodName is name of API  method.
  * @a is  String
  * $serviceName is String
  * returns an associtive array containing the response from the server.
*/

function call($MsgStr, $endpoint, $sandboxEmailAddress = '')
{

    //setting the curl parameters.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$endpoint);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);

    //turning off the server and peer verification(TrustManager Concept)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, !TRUST_ALL_CONNECTION);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, !TRUST_ALL_CONNECTION);
    if(!TRUST_ALL_CONNECTION) {
		
    	curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/Certs/api_cert_chain.crt'); 
    }
    



    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_POST, 1);

    $headers_array = setupHeaders(API_AUTHENTICATION_MODE);
    if(!empty($sandboxEmailAddress)) {
    	$headers_array[] = "X-PAYPAL-SANDBOX-EMAIL-ADDRESS: ".$sandboxEmailAddress;
    }
    	    
    if (API_AUTHENTICATION_MODE == 'ssl') {
    	curl_setopt($ch, CURLOPT_SSLCERT, realpath(SSL_CERTIFICATE_PATH));

    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_array);
    curl_setopt($ch, CURLOPT_HEADER, false);
    

    //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
   //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php
    if(USE_PROXY)
    curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT);

    //setting the MsgStr as POST FIELD to curl
    $conf = array('mode' => 0600, 'timeFormat' => '%X %x');
    $logger = &Log::singleton('file', 'logs/'.date('Ymd').'_invest.log', 'caller', $conf);

    $logger->log('##### PAYPAL call '.date('d/m/Y').' User:'.$_SESSION['user']->id.'#####');
    
    
    if (TRUST_ALL_CONNECTION == true){
    	
    	$log_data='TRUST_ALL_CONNECTION is set to true, Server and peer certificate verification are turned off';
    	$logger->warning($log_data);
    	
    }
    
    $logger->log("request: $MsgStr");

    curl_setopt($ch,CURLOPT_POSTFIELDS,$MsgStr);
    
    if(isset($_SESSION['curl_error_no'])) {
	    unset($_SESSION['curl_error_no']);
    }
    if(isset($_SESSION['curl_error_msg'])) {
	    unset($_SESSION['curl_error_msg']);
    }
    
   
    //getting response from server
    $response = curl_exec($ch);
    $logger->log("response: $response");
    $logger->log('##### END PAYPAL call '.date('d/m/Y').' User:'.$_SESSION['user']->id.'#####');
    $logger->close();
    
    if (curl_errno($ch)) {
        // moving to display page to display curl errors
        die('curl_error: ' . curl_errno($ch) . '<br />' . curl_error($ch));
     } else {
         //closing the curl
            curl_close($ch);
      }

    return $response;
}

function setupHeaders($auth_mode) {
    $headers_arr = array();

	if ($auth_mode == 'ssl' ) {
	   $headers_arr[]="CLIENT_AUTH:  ".'Valid cert';
	} else {
	    $headers_arr[]="X-PAYPAL-SECURITY-SIGNATURE: ".API_SIGNATURE;

	}
	
	$headers_arr[]="X-PAYPAL-SECURITY-USERID:  ".API_USERNAME;
	$headers_arr[]="X-PAYPAL-SECURITY-PASSWORD: ".API_PASSWORD;
	$headers_arr[]="X-PAYPAL-APPLICATION-ID: ".X_PAYPAL_APPLICATION_ID;
	$headers_arr[]="X-PAYPAL-REQUEST-SOURCE: ".SDK_VERSION;
    $headers_arr[]="X-PAYPAL-DEVICE-IPADDRESS: ".X_PAYPAL_DEVICE_IPADDRESS; 
    $headers_arr[] = "X-PAYPAL-REQUEST-DATA-FORMAT: JSON";
    $headers_arr[] = "X-PAYPAL-RESPONSE-DATA-FORMAT: JSON";
	
	return $headers_arr;
}

?>