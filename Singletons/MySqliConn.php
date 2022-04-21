<?php
namespace calculator_central_test\Singletons;
//$conf = require_once($_SERVER['DOCUMENT_ROOT']."/conf.php");
use calculator_central_test\Objects\Logger;
use calculator_central_test\Singletons\ConfLoader;

class MySqliConn
{

	private static $mySqli = null;

	public static function getSingleton()
	{
		if (self::$mySqli == null)
		{
			$conf = ConfLoader::getConf();
			self::$mySqli = self::sqlConnect($conf["DB_HOST"], $conf["DB_USER"], $conf["DB_PASS"], $conf["DEFAULT_DB"], $conf["DB_PORT"]);
		}
		return self::$mySqli;
	}

	public static function setSqlServer($server, $user, $pass, $defaultDb, $defaultPort)
	{
		if (self::$mySqli !== null)
		{
			self::resetConnection();
		}
		self::$mySqli = self::sqlConnect($server, $user, $pass, $defaultDb, $defaultPort);
	}

	public static function sqlConnect($server, $user, $pass, $defaultDb, $defaultPort)
	{
		$mySqli = null;
		try
		{
			$mySqli = new \mysqli();
			$mySqli->set_opt(MYSQLI_OPT_CONNECT_TIMEOUT, 3);
			$mySqli->set_opt(MYSQLI_OPT_READ_TIMEOUT, 3);
			$mySqli->connect($server, $user, $pass, $defaultDb, $defaultPort);
			if ( ! $mySqli)
			{
				$logger = new Logger();
				$logger->logError("mysqli::connect() failed - could not connect to server: " . $mySqli->error);
				return null;
			}
			if ( ! $mySqli->set_charset("utf8"))
			{
				$logger = new Logger();
				$logger->logError("mysqli::set_charset() failed - couldn't set utf8");
				return null;
			}
		}
		catch (\Exception $e)
		{
			$logger = new Logger();
			$logger->logError($e);
			return null;
		}
		return $mySqli;
	}

	public static function resetConnection()
	{
		if (self::$mySqli !== null)
		{
			self::$mySqli->close();
			self::$mySqli = null;
		}
	}
}
