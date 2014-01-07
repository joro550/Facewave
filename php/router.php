<?php

session_start();
include('Db/include.php');

function __autoload($className) {
    include( getcwd() . '/Classes/' . $className . '.php');
}

$request = json_decode(file_get_contents("php://input"));

if(isset($_POST) && !empty($_POST)) {
	$request = json_encode($_POST);
	$request = json_decode($request);
} else if(isset($_GET) && !empty($_GET)) {
	$request = json_encode($_GET);
	$request = json_decode($request);
}

$website = new Website();
$website->handleRequest($request);
