<?php
namespace calculator_central_test\Singletons;

class PayloadHandler
{
	private $payload;

	private static $instance = null;

	private function __construct()
	{
		$this->payload = (object)[];
	}

	public static function getSingleton()
	{
		if (self::$instance === null)
		{
			self::$instance = new PayloadHandler();
		}
		return self::$instance;
	}

	public function getPayload()
	{
		return $this->payload;
	}

	public function setPayload($key, $value)
	{
		$this->payload->$key = $value;
	}

	public function emptyPayload()
	{
		$this->payload = (object)[];
	}
}