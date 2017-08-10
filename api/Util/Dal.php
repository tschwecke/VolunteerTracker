<?php

require_once 'Util/Config.php';

class Dal {

//	protected static $MYSQL_HOST = Config::get('MYSQL_HOST');
//	protected static $MYSQL_USERNAME = Config::get('MYSQL_USERNAME');
//	protected static $MYSQL_PWD = Config::get('MYSQL_PWD');
//	protected static $MYSQL_DB = Config::get('MYSQL_DB');

//	protected static $MYSQL_HOST = "localhost";
//	protected static $MYSQL_USERNAME = "root";
//	protected static $MYSQL_PWD = "";
//	protected static $MYSQL_DB = "volunteerDb";

	public static function execute($procName) {
		$arg_list = func_get_args();

		//Remove the proc name from the arg list to get the list of proc args
		$procArgs = array_splice($arg_list, 1);

		//Connect to the db
		$mysqli = Dal::createConnection();

		//Create the statement
		$stmt = Dal::createStatement($mysqli, $procName, $procArgs);

		//Bind the input parameters
		Dal::bindParams($stmt, $procArgs);

		//Execute the statement
		if(!$stmt->execute()) {
			throw new Exception("Execute failed: (" . $mysqli->errno . ") " . $mysqli->error);
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
		Dal::bindParams($stmt, $procArgs);

		//Execute the statement
		if(!$stmt->execute()) {
			throw new Exception("Execute failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}

		//Get the results
		$results = Dal::getResults($stmt);

		//Free up all resources
		$stmt->close();
		$mysqli->close();

		return $results;
	}

	public static function createConnection() {
		$mysqli = new mysqli(Config::get('MYSQL_HOST'), Config::get('MYSQL_USERNAME'), Config::get('MYSQL_PWD'), Config::get('MYSQL_DB'));
//		$mysqli = new mysqli(Dal::$MYSQL_HOST, Dal::$MYSQL_USERNAME, Dal::$MYSQL_PWD, Dal::$MYSQL_DB);
		if ($mysqli->connect_errno) {
			throw new Exception("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
		}

		return $mysqli;
	}

	private static function createStatement($mysqli, $procName, $procArgs) {
		//Create the statement
		if(!($stmt = $mysqli->stmt_init())) {
			throw new Exception("Statement Init failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}

		//Prepare the statement
		$statmentText = Dal::constructStatementText($procName, $procArgs);
		if(!$stmt->prepare($statmentText)) {
			throw new Exception("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
		}

		return $stmt;
	}

	private static function bindParams($stmt, $procArgs) {
		$argTypes = Dal::constructArgTypeString($procArgs);

		//Couldn't get reflection to work once we switched to the new hosting provider,
		//so we have to do this the ugly way
		switch (count($procArgs)) {
			case 1:
				$stmt->bind_param($argTypes, $procArgs[0]);
				break;
			case 2:
				$stmt->bind_param($argTypes, $procArgs[0], $procArgs[1]);
				break;
			case 3:
				$stmt->bind_param($argTypes, $procArgs[0], $procArgs[1], $procArgs[2]);
				break;
			case 4:
				$stmt->bind_param($argTypes, $procArgs[0], $procArgs[1], $procArgs[2], $procArgs[3]);
				break;
			case 5:
				$stmt->bind_param($argTypes, $procArgs[0], $procArgs[1], $procArgs[2], $procArgs[3], $procArgs[4]);
				break;
			case 6:
				$stmt->bind_param($argTypes, $procArgs[0], $procArgs[1], $procArgs[2], $procArgs[3], $procArgs[4], $procArgs[5]);
				break;
			case 7:
				$stmt->bind_param($argTypes, $procArgs[0], $procArgs[1], $procArgs[2], $procArgs[3], $procArgs[4], $procArgs[5], $procArgs[6]);
				break;
			case 8:
				$stmt->bind_param($argTypes, $procArgs[0], $procArgs[1], $procArgs[2], $procArgs[3], $procArgs[4], $procArgs[5], $procArgs[6], $procArgs[7]);
				break;
			case 9:
				$stmt->bind_param($argTypes, $procArgs[0], $procArgs[1], $procArgs[2], $procArgs[3], $procArgs[4], $procArgs[5], $procArgs[6], $procArgs[7], $procArgs[8]);
				break;
			case 10:
				$stmt->bind_param($argTypes, $procArgs[0], $procArgs[1], $procArgs[2], $procArgs[3], $procArgs[4], $procArgs[5], $procArgs[6], $procArgs[7], $procArgs[8], $procArgs[9]);
				break;
		}
	}

	private static function constructArgTypeString($procArgs) {
		$argTypes = '';

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
					throw new Exception('Unable to handle type ' . gettype($procArgs[$i]) . ' of arg ' . $i. ' for proc ' . $procName);
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

		//$stmt->bind_result($out1, $out2, $out3, $out4, $out5, $out6, $out7, $out8, $out9, $out10, $out11, $out12, $out13, $out14, $out15, $out16, $out17, $out18, $out19, $out20);


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
