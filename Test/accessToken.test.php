<?php
require_once 'PHPUnit/Autoload.php';
require_once 'util.php';

class AccessTokenTest extends PHPUnit_Framework_TestCase
{
	public function testGetAccessToken()
	{
		//Arange
		$url = 'http://127.0.0.1/accessToken';
		$postData = array('emailAddress'=>'admin@admin.com', 'password'=>'admin123');
		 
		// Act
		$token = post($url, $postData);

		//Assert
		$this->assertNotNull($token);
	}
}
?>
