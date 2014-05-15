<?php

class Config {
	protected static $config;

	public static function load($fileName) {
		Config::$config = parse_ini_file($fileName);
	}

	public static function get($key) {
		$value = Config::$config[$key];
		return $value;
	}
}
