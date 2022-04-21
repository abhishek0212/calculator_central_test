<?php
namespace calculator_central_test\Objects;

use calculator_central_test\Singletons\PayloadHandler;

class ReplyPacket extends Logger
{
	public $code = null;
	public $msg = null;
	public $payload;
	public $api;

	public $payloadHandler;

	public $_content_type = "application/json";

	public function __construct($api)
	{
		$this->api = $api;
		$this->payloadHandler = PayloadHandler::getSingleton();
		parent::__construct($api);
	}

	public function setReplyPkt($code, $msg = "")
	{
		$this->code = $code;
		$this->msg = $msg;
		$this->setHeaders();
		return $this;
	}

	public function getReplyPkt()
	{
		if ($this->code == null)
		{
			$this->code = 499;
		}

		if ($this->code != 200 && $this->msg == null)
		{
			$this->msg = "Oops, the server didn't respond with the data we wanted";
		}

		// Create the return packet and cast as an object
		$replyPkt = [
			"code" => $this->code,
			"msg" => $this->msg
		];

		if (count((array)$this->payloadHandler->getPayload()) != 0)
		{
			$replyPkt["payload"] = $this->payloadHandler->getPayload();
		}

		return (object)$replyPkt;
	}

	public function setPayload($key, $value)
	{
		$this->payloadHandler->setPayload($key, $value);
		return $this;
	}

	private function getStatusMsg($code)
	{
		$status = array(
			100 => 'Continue',
			101 => 'Switching Protocols',
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			306 => '(Unused)',
			307 => 'Temporary Redirect',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			1001 => 'Api Limit Crossed');
		return ($status[$code]) ? $status[$code] : $status[500];
	}

	private function setHeaders()
	{
		$this->msg = $this->msg ? $this->msg : $this->getStatusMsg($this->code);
		header("HTTP/1.1 " . $this->code . " " . $this->msg);
		header("Content-Type:" . $this->_content_type);
	}
}