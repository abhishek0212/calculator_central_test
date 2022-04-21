<?php
namespace calculator_central_test\Singletons;

use calculator_central_test\Conf;

class ConfLoader
{
	private static $conf = [];
	private const CONF_FILES = ["conf.php"];

	public static function getConf()
	{
		if ( count(self::$conf) == 0)
		{
			self::$conf = self::loadConf(self::CONF_FILES);
		}

		return self::$conf;
	}

	private static function loadConf($files)
	{
		foreach ($files as $file)
		{
			try {
				$conf = include __DIR__ . "/../" . $file;;
				self::$conf = array_merge(self::$conf, $conf);
			}
			catch (\Exception $e)
			{
				error_log($e->getMessage() . " File path:".__DIR__ . "/../" . $file);
				throw $e;
			}
		}
		return self::$conf;
	}

}