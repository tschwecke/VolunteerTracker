<?php

class Dal {

//	protected static $MYSQL_HOST = "schweckeblogtest.db.9792020.hostedresource.com";
//	protected static $MYSQL_USERNAME = "schweckeblogtest";
//	protected static $MYSQL_PWD = "BlogPwd1";
//	protected static $MYSQL_DB = "schweckeblogtest";
	
	protected static $MYSQL_HOST = "localhost";
	protected static $MYSQL_USERNAME = "root";
	protected static $MYSQL_PWD = "";
	protected static $MYSQL_DB = "volunteerDb";

	public static function execute($procName) {
		$arg_list = func_get_args();

		//Remove the proc name from the arg list to get the list of proc args
		$procArgs = array_splice($arg_list, 1);	

		//Connect to the db
		$mysqli = Dal::createConnection();
		
		//Create the statement
		$stmt = Dal::createStatement($mysqli, $procName, $procArgs);

		//Bind the input parameters
		$argTypes = Dal::constructArgTypeString($procArgs);
		array_unshift($procArgs, $argTypes);
		$method = new ReflectionMethod('mysqli_stmt', 'bind_param'); 
		$method->invokeArgs($stmt, $procArgs);   

		//Execute the statement
		if(!$stmt->execute()) {
			echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
			exit();			
		}

		//Free up all resources
		$stmt->close();
		$mysqli->close();
	}

	public static function executeQuery($procName) {
		$arg_list = func_get_args();

		//Remove the proc name from the arg list to get the list of proc args
		$procArgs = array_splice($arg_list, 1);	

		//Connect to the db
		$mysqli = Dal::createConnection();
		
		//Create the statement
		$stmt = Dal::createStatement($mysqli, $procName, $procArgs);

		//Bind the input parameters
		if(count($procArgs) > 0) {
			$argTypes = Dal::constructArgTypeString($procArgs);
			array_unshift($procArgs, $argTypes);
			$method = new ReflectionMethod('mysqli_stmt', 'bind_param'); 
			$method->invokeArgs($stmt, $procArgs);   
		}
		//Execute the statement
		if(!$stmt->execute()) {
			echo "Execute failed: (" . $mysqli->errno . ") " . $mysqli->error;
			exit();			
		}
		
		//Get the results
		$results = Dal::getResults($stmt);

		//Free up all resources
		$stmt->close();
		$mysqli->close();

		return $results;
	}

	private static function createConnection() {
		$mysqli = new mysqli(Dal::$MYSQL_HOST, Dal::$MYSQL_USERNAME, Dal::$MYSQL_PWD, Dal::$MYSQL_DB);
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			exit();			
		}
		
		return $mysqli;
	}

	private static function createStatement($mysqli, $procName, $procArgs) {
		//Create the statement
		if(!($stmt = $mysqli->stmt_init())) {
			echo "Statement Init failed: (" . $mysqli->errno . ") " . $mysqli->error;
			exit();			
		}

		//Prepare the statement
		$statmentText = Dal::constructStatementText($procName, $procArgs);
		if(!$stmt->prepare($statmentText)) {
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			exit();			
		}
		
		return $stmt;
	}

	private static function constructArgTypeString($procArgs) {
	    	for ($i = 0; $i < count($procArgs); $i++) {
			switch (gettype($procArgs[$i])) {
				case 'boolean':
				case 'integer':
					$argTypes = $argTypes . 'i';
					break;
				case 'string':
					$argTypes = $argTypes . 's';
					break;
				case 'double':
					$argTypes = $argTypes . 'd';
					break;
				default:
					echo 'Unable to handle type ' . gettype($procArgs[$i]) . ' of arg ' . $i. ' for proc ' . $procName;
					exit();			
			}
		}
		
		return $argTypes;
	}

	private static function constructStatementText($procName, $procArgs) {
		$procArgList = '(';
	    	for ($i = 0; $i < count($procArgs); $i++) {
			if($i > 0) {
				$procArgList = $procArgList . ',';
			}
			$procArgList = $procArgList . '?';
		}
		$procArgList = $procArgList . ')';
		
		$statementText = 'CALL ' . $procName . $procArgList;
 
		return $statementText;
	}

	private static function getResults($stmt) {
		//Get the list of fields from the result set
		$result = $stmt->result_metadata();
		$fields = $result->fetch_fields();
		$result->close();

		//Get the results
		$stmt->store_result();

		//This is really hacky but I couldn't find another way to bind a dynamic number of variables
		$out1 = NULL;
		$out2 = NULL;
		$out3 = NULL;
		$out4 = NULL;
		$out5 = NULL;
		$out6 = NULL;
		$out7 = NULL;
		$out8 = NULL;
		$out9 = NULL;
		$out10 = NULL;
		$out11 = NULL;
		$out12 = NULL;
		$out13 = NULL;
		$out14 = NULL;
		$out15 = NULL;
		$out16 = NULL;
		$out17 = NULL;
		$out18 = NULL;
		$out19 = NULL;
		$out20 = NULL;

		$outputVars = array(&$out1, &$out2, &$out3, &$out4, &$out5, &$out6, &$out7, &$out8, &$out9, &$out10, &$out11, &$out12, &$out13, &$out14, &$out15, &$out16, &$out17, &$out18, &$out19, &$out20);
		
		$row = array_splice($outputVars, 0, count($fields));
		$method = new ReflectionMethod('mysqli_stmt', 'bind_result'); 
		$method->invokeArgs($stmt, $row);   

		//Loop through the results and build up the results to send back
		$results = array();
		while ($stmt->fetch()) {
			$resultRow = array();
			for ($i = 0; $i < count($fields); $i++) {
				$resultRow[$fields[$i]->name] = $row[$i];
			}

			array_push($results, $resultRow);
		}

		$stmt->free_result();
	
		return $results;
	}
}
