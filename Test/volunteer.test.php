<?php
require_once 'PHPUnit/Autoload.php';
require_once 'util.php';

class VolunteerTest extends PHPUnit_Framework_TestCase
{
	public function testGetById() {
		//Arange
		$url = 'http://127.0.0.1/volunteers/1';
		$accessToken = getAccessToken("admin@admin.com", "admin123");		 

		// Act
		$volunteer = get($url, $accessToken);

		//Assert
		$this->assertNotNull($volunteer);
		$this->assertEquals(1, $volunteer->id);
		$this->assertEquals('Admin', $volunteer->firstName);
		$this->assertEquals('Admin', $volunteer->lastName);
		$this->assertEquals('admin@admin.com', $volunteer->emailAddress);
		$this->assertEquals(3, $volunteer->roleId);
	}

	public function testGetAll() {
		//Arange
		$url = 'http://127.0.0.1/volunteers';
		$accessToken = getAccessToken("admin@admin.com", "admin123");		 
		 
		// Act
		$volunteers = get($url, $accessToken);

		//Assert
		$this->assertEquals(1, count($volunteers));
		$volunteer = $volunteers[0];
		$this->assertNotNull($volunteer);
		$this->assertEquals(1, $volunteer->id);
		$this->assertEquals('Admin', $volunteer->firstName);
		$this->assertEquals('Admin', $volunteer->lastName);
		$this->assertEquals('admin@admin.com', $volunteer->emailAddress);
		$this->assertEquals(3, $volunteer->roleId);
	}

	public function testCreate() {
		//Arange
		$url = 'http://127.0.0.1/volunteers';
		$accessToken = getAccessToken("admin@admin.com", "admin123");	
		$postData = array('firstName'=>'testFirst', 'lastName'=>'testLast', 'emailAddress'=>'testEmail@email.com', 'password'=>'testPwd');
		
		//Act
		$volunteer = post($url, $postData, $accessToken);

		$this->assertNotNull($volunteer);
		$this->assertEquals('testFirst', $volunteer->firstName);
		$this->assertEquals('testLast', $volunteer->lastName);
		$this->assertEquals('testEmail@email.com', $volunteer->emailAddress);
		$this->assertEquals(1, $volunteer->roleId);
	}
}
?>
