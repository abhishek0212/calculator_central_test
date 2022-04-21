<?php
namespace calculator_central_test\UnitTest;
require_once(__DIR__.'/../Includes/AutoLoader.php');

use calculator_central_test\api\Calculate;
use calculator_central_test\Sql\LogRecordSql;
use PHPUnit\Framework\TestCase;

class TestCalculate extends TestCase
{

	private $calculate;
	private $logRecordSql;

	public function setUp() :void
	{
		$this->calculate = new Calculate("phpUnitTest");
		$this->logRecordSql = new LogRecordSql("phpUnitTest");
	}

	public function testDivisionSuccess()
	{
		$this->calculate->division(["a" => 20, "b" => 2]);
		$ret = $this->calculate->getReplyPkt();
		$this->assertEquals(200, $ret->code);
		$this->calculate->payloadHandler->emptyPayload();
	}

	public function testDivisionByZero()
	{
		$this->calculate->division(["a" => 20, "b" => 0]);
		$ret = $this->calculate->getReplyPkt();
		$this->assertEquals(499, $ret->code);
	}

	public function testDivisionMissingParams()
	{
		$this->calculate->division(["a" => 20]);
		$ret = $this->calculate->getReplyPkt();
		$this->assertEquals(400, $ret->code);
	}

	public function testDivisionNoParams()
	{
		$this->calculate->division([]);
		$ret = $this->calculate->getReplyPkt();
		$this->assertEquals(400, $ret->code);
	}

	public function testMultiplicationSuccess()
	{
		$this->calculate->multiplication(["a" => 20, "b" => 2]);
		$ret = $this->calculate->getReplyPkt();
		$this->assertEquals(200, $ret->code);
		$this->calculate->payloadHandler->emptyPayload();
	}

	public function testMultiplicationNoParams()
	{
		$this->calculate->multiplication([]);
		$ret = $this->calculate->getReplyPkt();
		$this->assertEquals(400, $ret->code);
		$this->calculate->payloadHandler->emptyPayload();
	}

	public function testMultiplicationMissingParams()
	{
		$this->calculate->multiplication(["a" => 20]);
		$ret = $this->calculate->getReplyPkt();
		$this->assertEquals(400, $ret->code);
		$this->calculate->payloadHandler->emptyPayload();
	}

	public function teardown() :void
	{

	}

}