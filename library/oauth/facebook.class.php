<?
/*
 *  Copyright (C) 2012 Platoniq y Fundación Goteo (see README for details)
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

class Facebook {

	private $consumer_key;
	private $consumer_secret;

	private $db;
	private $authorize_url = "https://graph.facebook.com/oauth/authorize";
	private $access_url = "https://graph.facebook.com/oauth/access_token";

	private $callback_url;

	public function __construct($key, $secret, $callback = null){
		$this->consumer_key = $key;
		$this->consumer_secret = $secret;
		$this->callback_url = $callback;
	}

	public function start($url=false,$scope="publish_stream"){
		$auth_url = $this->authorize_url . "?client_id=".$this->consumer_key."&redirect_uri=".urlencode($this->callback_url)."&scope=$scope";
		if($url) return $auth_url;
		else Header("Location: $auth_url");
	}

	public function callback(){
		//We were passed these through the callback.

		$access_url = $this->access_url . "?client_id=".$this->consumer_key."&redirect_uri=".urlencode($this->callback_url)."&client_secret=".$this->consumer_secret."&code=".$_GET['code'];
		$after_access_request = $this->httpRequest($access_url);
		parse_str($after_access_request,$access_tokens);

		$oauth_token = $access_tokens['access_token'];
		return $oauth_token;

	}

	public function httpRequest($urlreq, $method = null)
	{
		$ch = curl_init();

		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, "$urlreq");
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		if($method == "POST"){
	       curl_setopt($ch, CURLOPT_POST, 1);
	       curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	    }
		// grab URL and pass it to the browser
		$request_result = curl_exec($ch);

		// close cURL resource, and free up system resources
		curl_close($ch);

		return $request_result;
	}

	public function makeRequest($token, $url, $method = "GET", $params = null) {
       $token = array("access_token" => $token);
       if(is_array($params)){
        $params = array_merge($params, $token);
       } else {
            $params = $token;
       }
       ksort($params);

       if($method == "GET"){
            foreach($params as $key => $value){
                $url_params[] = $key . '='. ($value);
            }
            $url .= '?'.implode('&',$url_params);
       }
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

       if($method == "POST"){
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
       }
       $response = curl_exec($ch);
       curl_close($ch);
       return $response;
	}
}
