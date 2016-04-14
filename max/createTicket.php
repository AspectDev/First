<?php
/**
*	Создание заявки от конкретного пользователя в конкретный департамент с конкретным приоритетом.
**/
$email = "rufionov@gmail.com";
$prioritet = "Нормальный";
require_once("config.php");

$department = kyDepartment::getAll()->filterByModule(kyDepartment::MODULE_TICKETS)->first();
$priority = kyTicketPriority::getAll()->filterByTitle($prioritet)->first();
$user = KyUser::getAll()->filterByEmail($email)->first();
$ticket = $user
    ->newTicket(
        $department,
        "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Numquam cum facilis pariatur inventore optio tempore dolorum quia, cupiditate, vitae doloremque, sunt sapiente, omnis quae magni ratione minus atque incidunt amet.",
        "ТЕСТОВАЯ ЗАЯВКА от настоящего клиента")
    ->setPriority($priority)
    ->create();
