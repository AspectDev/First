<?php
include_once "config.php";
include_once "functions.php";

$functions = new Functions;
$user = $functions->RetriveUserInfoByEmail("rufionov@gmail.com");
var_dump($user);
$data = array(
	'ticketid' => 13499,
	'contents' => 'DEMO MODE',
	"userid" =>$user["id"]
	);
$ticket = $functions->ReplyTicket($data);
var_dump($ticket);
?>