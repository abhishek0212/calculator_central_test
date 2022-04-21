<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/Includes/AutoLoader.php');

use calculator_central_test\Objects\ReplyPacket;

const REQUEST_METHOD = ["GET" => "GETAPI.json", "POST" => "POSTAPI.json"];

$file = REQUEST_METHOD[$_SERVER['REQUEST_METHOD']];
$api = $_REQUEST['REQUEST_URI'];

if ( ! $file)
{
	$rplyPkt = new ReplyPacket("cmdError");
	$rplyPkt->logError("Error API cmd: {$api}");
	$rplyPkt->setReplyPkt(401, "Request not found");
	echo json_encode($rplyPkt->getReplyPkt());
	exit(0);
}

$apiList = json_decode(file_get_contents($file), true);

if ( ! array_key_exists("/".$api, $apiList))
{
	$rplyPkt = new ReplyPacket("cmdError");
	$rplyPkt->logError("Error API cmd: {$api}");
	$rplyPkt->setReplyPkt(401, "Request not found");
	echo json_encode($rplyPkt->getReplyPkt());
	exit(0);
}
$apiJSON = $apiList["/".$api];

$apiClass = new $apiJSON["class"]($api);

if(method_exists($apiClass, $apiJSON["method"]) === false)
{
	$rplyPkt->logError("Api doesn't exists");
	$rplyPkt->setReplyPkt(401, "Request not found");
	echo json_encode($rplyPkt->getReplyPkt());
	exit(0);
}

$method = $apiJSON["method"];
$apiClass->$method($_REQUEST);

echo json_encode($apiClass->getReplyPkt());

