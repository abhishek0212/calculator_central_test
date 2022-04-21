<?php
namespace calculator_central_test\Objects;

use calculator_central_test\Singletons\MySqliConn;


class MySqliWrapper extends ReplyPacket
{

	private $lastError;
	private static $mySqli;

	public function __construct($api)
	{
		parent::__construct($api);
	}

	public function getMysqli()
	{
		self::$mySqli = MySqliConn::getSingleton();
		if ( ! self::$mySqli)
		{
			$err = (self::$mySqli) ? self::$mySqli->connect_errno : "Unknown";
			$this->logError("SQL Connection Error: Errno - " . $err);
			parent::setReplyPkt(480, "Could not connect to sql server");
			return false;
		}
		return self::$mySqli;
	}


	public function sendSQLCommand($sql, $bind_params = array())
	{
		$errMsg = "";
		$isSuccess = true;
		$mysqli = $this->getMysqli();

		if ( ! $mysqli)
		{
			$this->logError("Could not connect to sql server in sendSqlCommand");
			return false;
		}
		try
		{
			$mySqliStmt = $mysqli->prepare($sql);
			if ( ! $mySqliStmt)
			{
				$errMsg = "[Error 482] - SQL Prepare failed - Error: {$mysqli->error}";
				$isSuccess = false;
			}
			if ($isSuccess && count($bind_params) > 0)
			{
				if ( ! $mySqliStmt->bind_param(...$bind_params))
				{
					$errMsg = "[Error code 483] - SQL Bind Error: Errno - {$mySqliStmt->error}";
					$isSuccess = false;
				}
			}
			if ($isSuccess &&  ! $mySqliStmt->execute())
			{
				$errMsg = "[Error code 484] - SQL Execute Error: Errno - " . $mySqliStmt->errno . ", Error - " . $mySqliStmt->error;
				$isSuccess = false;
			}
		}
		catch (\mysqli_sql_exception $e)
		{
			$isSuccess = false;
			$this->logError("Mysqli mysql exception: ".json_encode($e));
		}
		if ( ! $isSuccess)
		{
			$sql = preg_replace('/\s+/', ' ', $sql);
			$this->logError("{$errMsg}: Mysqli failure: sql: {$sql}");
			return false;
		}
		return $mySqliStmt;
	}

	public function getLastError()
	{
		return $this->lastError;
	}

	public function getLastInsertId()
	{
		return self::$mySqli->insert_id;
	}

	public function getAffectedRows()
	{
		return self::$mySqli->affected_rows;
	}
}
?>