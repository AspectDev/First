<?php
include_once "config.php";
include_once "functions.php";

$functions = new Functions;

var_dump($functions->RetriveUserInfoByEmail("ip@dalli-service.com"));
?>