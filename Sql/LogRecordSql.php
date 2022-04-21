<?php
namespace calculator_central_test\Sql;

use calculator_central_test\Objects\MySqliWrapper;

class LogRecordSql extends MySqliWrapper
{
	public function __construct($api)
	{
		parent::__construct($api);
	}

	public function logCalculation($details, $status, $response)
	{
		$sql = "
			INSERT INTO
				logs (details, status, response)
			VALUES
				(?, ?, ?);
		";
		$arr = ["sis", $details, $status, $response];
		$stmt = $this->sendSqlCommand($sql, $arr);

		if ( ! $stmt || $stmt->affected_rows == 0)
		{
			$this->logError("Failed to insert calculation log.");
			return false;
		}
		return true;
	}

	public function getLogs($offSet, $count)
	{
		$sql = "
			SELECT
				*
			FROM
				logs
			WHERE
				1
			LIMIT ?, ?;
		";
		$arr = ["ii", $offSet, $count];
		$stmt = $this->sendSqlCommand($sql, $arr);

		if ( ! $stmt)
		{
			$this->logError("Failed to fetch calculation log.");
			return false;
		}
		return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
	}

	public function getLogsCount()
	{
		$sql = "
			SELECT
				count(*) AS total
			FROM
				logs
			WHERE
				1;
		";
		$stmt = $this->sendSqlCommand($sql, []);

		if ( ! $stmt)
		{
			$this->logError("Failed to fetch calculation log.");
			return false;
		}
		return $stmt->get_result()->fetch_all(MYSQLI_ASSOC)[0]["total"];
	}


}