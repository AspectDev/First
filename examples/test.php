<?php
set_time_limit(0);
// error_reporting(-1);
// define('BASE_URL', 'https://office.dalli-service.com/api/index.php?');
// define('API_KEY', '9810c632-a6ef-b954-19c6-71ea515664ab');
// define('SECRET_KEY', 'ODU1MzRlYzEtOTc5My0wOTk0LTU1ODAtZGViODdmYmU0NTY3NDU5OTg1Y2QtMmFiZi1jZWY0LTgxMTMtNjc1ZDZmM2Y4MDA1');


define('BASE_URL', 'http://dev.dalli-service.com/api/index.php?');
define('API_KEY', 'aff40586-b89a-1b44-b592-8e271abb4aba');
define('SECRET_KEY', 'ODkxZjBhZjctNzJiNy0xMTU0LTI5ZDktMGQ4ZGY1NzZkOGYzMmE5Mjc2N2QtN2FhOC04ZWE0LTlkMzEtNTA0ZGYzMWRjMWRj');
set_time_limit(0);
require_once("../kyIncludes.php");

/**
 * Initializes the client.
 */
function initKayako() {
	$config = new kyConfig(BASE_URL, API_KEY, SECRET_KEY);
	$config->setDebugEnabled(true);
	kyConfig::set($config);
	return $config;
}
$q = initKayako();






/* Массивы */
$userList = KyUser::getAll(); // Все пользователи
$orgList = kyUserOrganization::getAll(); // Все организации

/* Функции */
function CheckExistUserByEmail($userList,$email){
	return $userList->filterByEmail($email)->first();
}

function CheckExistOrgByName($orgList,$title){
	return $orgList->filterByName($title)->first();
}
function RegisterOrg($company,$address,$phone){
	$org = new kyUserOrganization;
	$org->setName($company);
	$org->setAddress($address);
	$org->setPhone($phone);
	$org->setCountry("Russian Federation");
	$org->setType("restricted");
	$create_organization = $org->create();
}

function RegisterUser($name,$email,$objComp){
	// $userGroup = kyUserGroup::getAll()->filterByTitle("Зарегистрированный")->first();
	$userGroup = kyUserGroup::getAll()->filterByTitle("Registered")->first();
	$user = $userGroup->newUser($name, $email, "reSIned5s")->setUserOrganization($objComp)->setSalutation(kyUser::SALUTATION_MR)->setSendWelcomeEmail(false)->create();
}
/* Соединение с БД.*/
$mysqli = new mysqli("localhost", "root", "1234", "DS_control");
$mysqli->set_charset("utf8");
$quer = $mysqli->query("SELECT * FROM clients WHERE date_p IS NULL");



/* Цикл перебора всех действующих клиентов */
while ($res = $quer->fetch_assoc()) {
	// Дальнейшие действия проводим только при условии что компании нет в DS Ticket
	if(!CheckExistOrgByName($orgList,$res["company"])){
		RegisterOrg($res["company"],$res["full_add"],$res["phone"]);
		// Разбиваем список email по запятой и пробелу
		$emails = explode(", ",$res["mail"]);
		$org = CheckExistOrgByName($orgList,$res["company"]);
		foreach ($emails as $email) {
			if(!CheckExistUserByEmail($userList,"rufionov@gmail.com")){
				RegisterUser($res["company"],$email,$org);
			}
		}
	}
}




















// 
// $registered_user_group = kyUserGroup::getAll()->filterByTitle("Зарегистрированный")->first();
// $user_organization = kyUserOrganization::getAll()->first();
// $user = $registered_user_group
//     ->newUser("DEMO", "v@TEST.RU", "qwerty123")
//     ->setUserOrganization("TEST") // Пихаем юзверя в организацию.
//     ->setSalutation(kyUser::SALUTATION_MR) // ХЗ Что это. Но без нее не работает =)
//     ->setSendWelcomeEmail(false) //sendwelcomeemail
//     ->create();
// var_dump($user);
// var_dump(kyUserOrganization::getAll()); // Достать все организации
// var_dump($priority_urgent = kyTicketPriority::getAll()->filterByTitle("Нормальный")->first()); // Приоритет Нормальный


// $prioritet = kyTicketPriority::getAll()->filterByTitle("Нормальный")->first(); // Приоритет
// $user = KyUser::getAll()->filterByEmail("info@dalli-service.com")->first(); //Пользователь
// $department = kyDepartment::getAll()->filterByTitle("IT отдел")->filterByModule(kyDepartment::MODULE_TICKETS)->first(); // Департамент
// var_dump($department);
// $ticket = $user
//     ->newTicket(
//         $department,
//         "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Numquam cum facilis pariatur inventore optio tempore dolorum quia, cupiditate, vitae doloremque, sunt sapiente, omnis quae magni ratione minus atque incidunt amet.",
//         "ТЕСТОВАЯ ЗАЯВКА от настоящего клиента")
//     ->setPriority($prioritet)
//     ->create();
// var_dump($ticket);


// $department = kyDepartment::getAll()->filterByTitle("IT отдел")->filterByModule(kyDepartment::MODULE_TICKETS)->first(); // Департамент
// $ticket = KyTicket::getAll($department)->filterByUserOrganizationName("DS");

// print_r($ticket);



// var_dump($org->filterByName("ТЕСТ")->first());
// print_r(kyUserOrganization::getAvailableFilterMethods());
//



// $user = KyUser::getAll()->filterByEmail("example@dalli-service.com")->first();
// var_dump($user);



// $mysqli = new mysqli("localhost", "root", "1234", "test1");
// $mysqli->set_charset("utf8");
// $quer = $mysqli->query("SELECT * FROM clients");

// $user_group = kyUserGroup::getAll()->filterByTitle("Зарегистрированный")->first();

// $organ = kyUserOrganization::getAll();
// // var_dump($organ);

// // var_dump($user_group);
// while ($res = $quer->fetch_assoc()) {
// 	$date_p = $res["date_p"];
// 	if(strtotime($date_p) <= time() && !empty($date_p) && $date_p != "0000-00-00"){}else{
// 		// $org = new kyUserOrganization;
// 		// $org->setName($res["company"]);
// 		// $org->setAddress($res["full_add"]);
// 		// $org->setPhone($res["phone"]);
// 		// $org->setCountry("Russian Federation");
// 		// $org->setType("restricted");
// 		// $create_organization = $org->create();
// 		// sleep(2);

// // $organcurr = $organ->filterByName("1905.RU")->first();
// 		$res["mail"] = explode(", ", $res["mail"]);
// 		$res["mail"] = $res["mail"][0];
// 	if(KyUser::getAll()->filterByEmail($res["mail"])->first() == NULL && !empty($res["mail"]) && !empty($res["full_comp"])){
// echo ($res["mail"]." - ". $res["company"]);
// 		$user = $user_group->newUser($res["full_comp"], $res["mail"], "reSIned5s")->setUserOrganization($organ->filterByName($res["company"])->first())->setSalutation(kyUser::SALUTATION_MR)->setSendWelcomeEmail(false)->create();
// 	}

// 	}
// }










// $user_group = kyUserGroup::getAll()->filterByTitle("Registered")->first();
// var_dump($user_group);
// echo "<br />";
// echo "<br />";
// echo "<br />";
// $organ = kyUserOrganization::getAll();
// $organ = $organ->filterByName("ТЕСТ")->first();
// var_dump($organ);
// echo "<br />";
// echo "<br />";
// echo "<br />";
// $user = $user_group
// 			->newUser("йцу", "йцу@asd.ru", "reSIned5s")
// 			->setUserOrganization($organ)
// 			->setSalutation(kyUser::SALUTATION_MR)
// 			->setSendWelcomeEmail(false)
// 			->create();
// $user = $user_group
//     ->newUser("йцу", "annos.ying@example.com", "reSIned5s")
//     ->setUserOrganization($organ) //userorganizationid
//     ->setSalutation(kyUser::SALUTATION_MR) //salutation
//     ->setSendWelcomeEmail(false) //sendwelcomeemail
//     ->create();


// var_dump($user);
// echo "<br />";
// echo "<br />";
// echo "<br />";
// $users = KyUser::getAll();
// while ($res = $quer->fetch_assoc()) {
// 	$date_p = $res["date_p"];
// 	if(strtotime($date_p) <= time() && !empty($date_p) && $date_p != "0000-00-00"){}else{
// // 		// $org = new kyUserOrganization;
// // 		// $org->setName($res["company"]);
// // 		// $org->setAddress($res["full_add"]);
// // 		// $org->setPhone($res["phone"]);
// // 		// $org->setCountry("Russian Federation");
// // 		// $org->setType("restricted");
// // 		// $create_organization = $org->create();
// // 		// sleep(2);
// 		$res["mail"] = explode(", ", $res["mail"]);
// 		$res["mail"] = $res["mail"][0];
// 	if(KyUser::getAll()->filterByEmail($res["mail"])->first() == NULL && !empty($res["mail"]) && !empty($res["full_comp"])){
// echo ($res["mail"]." - ". $res["company"]);
// 		$user = $user_group->newUser($res["full_comp"], $res["mail"], "reSIned5s")->setUserOrganization($organ->filterByName($res["company"])->first())->setSalutation(kyUser::SALUTATION_MR)->setSendWelcomeEmail(false)->create();
// 	}

// 	}
// }


// $org = new kyUserOrganization;
// $org->setName("TEST ORG");
// $org->setType("restricted");
// $create_organization = $org->create();
// var_dump($create_organization);