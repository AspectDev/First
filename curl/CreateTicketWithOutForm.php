<?php

include_once "config.php";
include_once "functions.php";

$functions = new Functions;

$url = "e=/Tickets/Ticket";

$subject = "Тема";
$fullname = "MAX";
$email = "ip@wiz-lab.ru";
$contents = "Test test test test";
$departmentid = "1";
$ticketstatusid = "1";
$ticketpriorityid = "1";
$tickettypeid = "1";
$userid = "1";

$post = array('subject' => $subject,
 'fullname' => $fullname,
 'email' => $email,
 'contents' => $contents,
 'departmentid' => $departmentid,
 'ticketstatusid' => $ticketstatusid,
 'ticketpriorityid' => $ticketpriorityid,
 'tickettypeid' => $tickettypeid,
 'ignoreautoresponder' => 1,
 'userid'=>$userid);

$functions->curl($url,$post);
?>