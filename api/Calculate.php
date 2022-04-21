<?php
namespace calculator_central_test\api;

use calculator_central_test\Objects\ReplyPacket;
use calculator_central_test\Sql\LogRecordSql;

class Calculate extends ReplyPacket
{
	private $logRecordSql;

	public function __construct($api)
	{
		$this->logRecordSql = new LogRecordSql($api);
		parent::__construct($api);

	}

	public function division($receivedParms)
	{
		$num1 = isset($receivedParms["a"]) ? $receivedParms["a"] : null;
		$num2 = isset($receivedParms["b"]) ? $receivedParms["b"] : null;

		if ($num1 === null || $num2 === null)
		{
			return $this->setReplyPkt(400);
		}

		$this->setPayload("a", $num1)
		->setPayload("b", $num2);

		$code = null;
		$msg = "";
		try {
			$response = intdiv($num1, $num2);
			$code = 200;
			$this->setPayload("result", $response);
		}
		catch(\DivisionByZeroError $e)
		{
			$code = $e->getCode();
			$msg = $e->getMessage();
		}
		catch(\ErrorException $e) {
			$code = $e->getCode();
			$msg = $e->getMessage();
		}
		$this->logRecordSql->logCalculation("$num1/$num1", $code, ($code == 200) ? $response : $msg);
		return $this->setReplyPkt($code, $msg);
	}

	public function multiplication($receivedParms)
	{
		$num1 = isset($receivedParms["a"]) ? $receivedParms["a"] : null;
		$num2 = isset($receivedParms["b"]) ? $receivedParms["b"] : null;

		if ($num1 == null || $num2 == null)
		{
			return $this->setReplyPkt(400);
		}

		$this->setPayload("a", $num1)
		->setPayload("b", $num2);

		$code = null;
		$msg = "";
		try {
			$response = $num1 * $num2;
			$code = 200;
			$this->setPayload("result", $response);
		}
		catch(\ErrorException $e) {
			$code = $e->getCode();
			$msg = $e->getMessage();
		}
		$this->logRecordSql->logCalculation("$num1*$num1", $code, ($code == 200) ? $response : $msg);
		return $this->setReplyPkt($code, $msg);
	}

	public function getLogs($receivedParms)
	{
		$page = isset($receivedParms["page"]) ? $receivedParms["page"] : 1;
		$perPage = isset($receivedParms["per_page"]) ? $receivedParms["per_page"] : 10;

		$rows = $this->logRecordSql->getLogs($page, $perPage);
		$logCnt = $this->logRecordSql->getLogsCount();
		return $this->setPayload("current_page", $page)
		->setPayload("data", $rows)
		->setPayload("from", $page)
		->setPayload("per_page", $perPage)
		->setPayload("total", $logCnt)
		->setReplyPkt(200);
	}
}