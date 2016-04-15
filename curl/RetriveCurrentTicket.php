<?php
include_once "config.php";
include_once "functions.php";

$functions = new Functions;

$ticket = $functions->RetriveTicketCurrent($idTicket =13363, $iduser = false);
// var_dump();
foreach ($ticket as $key => $val) {
	foreach ($val["posts"] as $keys => $vals) {
		foreach ($vals["post"] as $a => $b) {
			$text = preg_replace("'<style[^>]*?>.*?</style>'si","",$b["contents"]);
			$text = strip_tags($text);
			$text = nl2br($text);
			// $text = str_replace("<br />\n\r<br />","",$text);
			$text = preg_replace("^(<br \/>\n<br \/>)^", "", $text);
			$text = preg_replace("^(<br \/>\r<br \/>)^", "", $text);
			// $text = preg_replace('/\s+/', '', $text);
			// $text = preg_replace('@(?:\s+|<br\s*/?\s*>|<p>\s*(?:\s*<br\s*/?\s*>\s*)*\s*</p>|(<p>)\s*(?:\s*<br\s*/?\s*>\s*)+\s*|\s*(?:\s*<br\s*/?\s*>\s*)+\s*(</p>))@i', ' $1$2$3', $text);
			echo "Сообщение от: ".$b["fullname"] . "<br />";
			echo "Есть ли вложения: ".$b["hasattachments"] . "<br />";
			echo "Контент: <div style='width: ; background: red;'>".(trim(($text))) . "</div><br />";
		}
	}
}
?>