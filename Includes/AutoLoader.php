<?php
namespace calculator_central_test\Includes;

function autoloadInit($className)
{
	$serverRoot = __DIR__;
	$fileName = "";
	if ($lastNsPos = strrpos($className, '\\'))
	{
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
	$fileName = ltrim($fileName, 'calculator_central_test');
	//echo $fileName."\n";
	if (file_exists($serverRoot."/../".$fileName))
	{
		require_once $serverRoot."/../".$fileName;
	}
}

spl_autoload_register('calculator_central_test\Includes\autoloadInit');