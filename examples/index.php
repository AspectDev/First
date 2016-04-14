<?php
require_once("../kyIncludes.php");
define('BASE_URL', 'https://office.dalli-service.com/api/index.php?');
define('API_KEY', '9810c632-a6ef-b954-19c6-71ea515664ab');
define('SECRET_KEY', 'ODU1MzRlYzEtOTc5My0wOTk0LTU1ODAtZGViODdmYmU0NTY3NDU5OTg1Y2QtMmFiZi1jZWY0LTgxMTMtNjc1ZDZmM2Y4MDA1');
set_time_limit(0);
require_once("../kyIncludes.php");
function initKayako() {
	$config = new kyConfig(BASE_URL, API_KEY, SECRET_KEY);
	$config->setDebugEnabled(true);
	kyConfig::set($config);
	return $config;
}
$q = initKayako();

$department = kyDepartment::getAll()->filterByModule(kyDepartment::MODULE_TICKETS)->first();

// $prioritet = kyTicketPriority::getAll()->filterByTitle("Нормальный")->first();
// $user = KyUser::getAll()->filterByEmail("rufionov@gmail.com")->first();
// $ticket = $user
//     ->newTicket(
//         $department,
//         "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Numquam cum facilis pariatur inventore optio tempore dolorum quia, cupiditate, vitae doloremque, sunt sapiente, omnis quae magni ratione minus atque incidunt amet.",
//         "ТЕСТОВАЯ ЗАЯВКА от настоящего клиента")
//     ->setPriority($prioritet)
//     ->create();
// var_dump($department->getAvailableFilterMethods());
$ticketList = kyTicket::getAll($department)->filterByEmail("rufionov@gmail.com")->first();
var_dump($ticketList);