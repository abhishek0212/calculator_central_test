<?php
namespace calculator_central_test\Objects;

class Logger
{
	public function __construct()
	{
		$this->logDir = "/var/app/logs/log-".date("d-m-Y").".txt";
	}

	public function logError($text)
	{
		return file_put_contents($this->logDir, "\n".$text, FILE_APPEND | LOCK_EX);
	}
}