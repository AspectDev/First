<?php
// config
$salt = mt_rand();
$signature = base64_encode(hash_hmac('sha256', $salt, "ODkxZjBhZjctNzJiNy0xMTU0LTI5ZDktMGQ4ZGY1NzZkOGYzMmE5Mjc2N2QtN2FhOC04ZWE0LTlkMzEtNTA0ZGYzMWRjMWRj", true));
$apikey = "aff40586-b89a-1b44-b592-8e271abb4aba";





$apiUrl = "http://dev.dalli-service.com/api/index.php?e=/Tickets/Ticket";
$apiKey = "aff40586-b89a-1b44-b592-8e271abb4aba";
$salt = mt_rand();
$secretKey = "ODkxZjBhZjctNzJiNy0xMTU0LTI5ZDktMGQ4ZGY1NzZkOGYzMmE5Mjc2N2QtN2FhOC04ZWE0LTlkMzEtNTA0ZGYzMWRjMWRj";
$signature = base64_encode(hash_hmac('sha256',$salt,$secretKey,true));

$subject = "Test Ticket";
$fullname = "DropDeadDick";
$email = "ip@wiz-lab.ru";
$contents = "Test test test test";
$departmentid = "1";
$ticketstatusid = "1";
$ticketpriorityid = "1";
$tickettypeid = "1";
$userid = "1";

$post_data = array('subject' => $subject,
 'fullname' => $fullname,
 'email' => $email,
 'contents' => $contents,
 'departmentid' => $departmentid,
 'ticketstatusid' => $ticketstatusid,
 'ticketpriorityid' => $ticketpriorityid,
 'tickettypeid' => $tickettypeid,
 'ignoreautoresponder' => 1,
 'userid' => $userid, 
 'apikey' => $apiKey, 
 'salt' => $salt, 
 'signature' => $signature);

$post_data = http_build_query($post_data, '', '&');

$curl = curl_init($apiUrl); 
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_URL, $apiUrl);
curl_setopt($curl, CURLOPT_HEADER, false); 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);

$response = curl_exec($curl);  
curl_close($curl);
var_dump($response);
// $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
// echo "<pre>".print_r($xml, true)."</pre>";
 ?>