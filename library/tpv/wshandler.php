<?php
/*
 *  Copyright (C) 2012 Platoniq y Fundación Fuentes Abiertas (see README for details)
 *	This file is part of Goteo.
 *
 *  Goteo is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Goteo is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with Goteo.  If not, see <http://www.gnu.org/licenses/agpl.txt>.
 *
 */

require_once 'library/paypal/stub.php'; // sí, uso el stub de paypal
require_once 'library/paypal/log.php'; // sí, uso el log de paypal

class WSHandler {
	
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
     * Last Error
     */
    private $LastError;
	
      	
   	/*
   	 * Calls the actual WEB Service and returns the response.
   	 */
   	function callWebService($data, $url) {
		
		$response = null;
		
		try {
			
		    $response = tpvcall($data, $url);
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
  * call: Function to perform the a call to sermepa webservice
  * @methodName is name of API  method.
  * @a is  String
  * $serviceName is String
  * returns an associtive array containing the response from the server.
*/

function tpvcall($data, $endpoint)
{
    //setting the curl parameters.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$endpoint);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //no se exactamente para que es, está en los ejemplos
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");

    // tiene que ser    application/x-www-form-urlencoded
    $the_data = array();
    foreach ($data as $key=>$value) {
        $the_data[] = $key.'='.$value;
    }
    curl_setopt($ch, CURLOPT_POSTFIELDS, implode('&', $the_data)); // datos clave=>valor del POST

    //setting the MsgStr as POST FIELD to curl
    $conf = array('mode' => 0600, 'timeFormat' => '%X %x');
    $logger = &Log::singleton('file', 'logs/'.date('Ymd').'_invest.log', 'caller', $conf);

    $logger->log('##### TPV call '.date('d/m/Y').' #####');
    
    $logger->log("endpoint: $endpoint");
    $logger->log("request: " . implode(' ', $the_data));

    
    if(isset($_SESSION['curl_error_no'])) {
	    unset($_SESSION['curl_error_no']);
    }
    if(isset($_SESSION['curl_error_msg'])) {
	    unset($_SESSION['curl_error_msg']);
    }
    
   
    //getting response from server
    $response = curl_exec($ch);


    $logger->log("response: ".trim(htmlentities($response)));
    $logger->log('##### END TPV call '.date('d/m/Y').' #####');
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
?>