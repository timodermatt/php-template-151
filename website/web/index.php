<?php
use LucStr\Factory;

error_reporting(E_ALL);
session_start();

$loader = require_once("../vendor/autoload.php");
LucStr\MessageHandler::Initialize();
$factory = Factory::crateFromInitFile(__DIR__ . "/../config.ini");

$uri_parts = strtok($_SERVER["REQUEST_URI"],'?');
$controllername = strtok($uri_parts,'/');
$actionname = strtok('/');
if(empty($controllername)){
	$controllername = "Index";
	$actionname = "Index";
}
if(empty($actionname)){
	$actionname = "Index";
}

$GLOBALS["controllername"] = $controllername;
$GLOBALS["actionname"] = $actionname;

$controllerlocation = "LucStr\\Controller\\" . $controllername . "Controller";
$controller = new $controllerlocation($factory);

$controller->executeAction($actionname);