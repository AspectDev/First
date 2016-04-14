<?php
set_time_limit(0); // Вырубаем время выполнения скрипта (пусть крутится, пока не закончит)
// error_reporting(-1); // Полный лог ошибок
define('BASE_URL', 'https://office.dalli-service.com/api/index.php?'); // Ссылка на апи
// define('BASE_URL', 'http://dev.loc/api/index.php?'); // Ссылка на апи
define('API_KEY', '9810c632-a6ef-b954-19c6-71ea515664ab'); // DS
// define('API_KEY', '52823e1d-77d0-deb4-01e5-6ff6beda30c6'); // LOC
define('SECRET_KEY', 'ODU1MzRlYzEtOTc5My0wOTk0LTU1ODAtZGViODdmYmU0NTY3NDU5OTg1Y2QtMmFiZi1jZWY0LTgxMTMtNjc1ZDZmM2Y4MDA1'); // DS
// define('SECRET_KEY', 'NDBkMjU1YzYtZWIzYS04NjQ0LTBkYTgtOWNiMWZmNWY2Y2YwYTQ4ZGU0MjctOTZiYS04ODk0LTJkMDMtYjg4MTQ5ODIzZDgx'); // LOC
require_once("../kyIncludes.php");
function initKayako() {
	$config = new kyConfig(BASE_URL, API_KEY, SECRET_KEY);
	$config->setDebugEnabled(true);
	kyConfig::set($config);
	return $config;
}
$q = initKayako();
