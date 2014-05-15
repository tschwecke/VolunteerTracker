<?php

function get($url, $accessToken) {
	// Initializing curl
	$ch = curl_init($url);
	 
	// Configuring curl options
	$options = array(
	CURLOPT_RETURNTRANSFER => true,
	//CURLOPT_USERPWD => $username . ":" . $password,   // authentication
	CURLOPT_HTTPHEADER => array('X-Authentication: ' . $accessToken) ,
	//CURLOPT_POSTFIELDS => $json_string
	);
	 
	// Setting curl options
	curl_setopt_array( $ch, $options );
	 
	$result =  curl_exec($ch); // Getting jSON result string

	$object = json_decode($result);

	return $object;
}	

function post($url, $postData, $accessToken) {
	// Initializing curl
	$ch = curl_init($url);
	
	$json_string = json_encode($postData);
	 
	// Configuring curl options
	$options = array(
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_HTTPHEADER => array('Content-type: application/json', 'X-Authentication: ' . $accessToken) ,
	CURLOPT_POSTFIELDS => $json_string
	);
	 
	// Setting curl options
	curl_setopt_array( $ch, $options );
	 
	$result =  curl_exec($ch); // Getting jSON result string

	$object = json_decode($result);

	return $object;
}	

function getAccessToken($email, $pwd) {
	$url = 'http://127.0.0.1/accessToken';
	$postData = array('emailAddress'=>$email, 'password'=>$pwd);
	$token = post($url, $postData, "test");

	return $token->access_token;
}

?>
