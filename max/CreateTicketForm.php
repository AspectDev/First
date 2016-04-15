<?php
session_start();
require_once("config.php");

// Собираем массив с департаментами и их вложенностью
function getDepartmentsTree() {
	$depList = array();
	$all_departments = kyDepartment::getAll()->filterByModule(kyDepartment::MODULE_TICKETS)->filterByType(kyDepartment::TYPE_PUBLIC);
	$top_departments = $all_departments->filterByParentDepartmentId(null)->orderByDisplayOrder();
	foreach ($top_departments as $top_department) {
		$depList[$top_department->getId()] = array(
			'department' => $top_department,
			'child_departments' => $all_departments->filterByParentDepartmentId($top_department->getId())->orderByDisplayOrder()
		);
	}
	return $depList;
} // end func getDepartmentsTree

if(!empty($_POST)){
	$userEmail = $_POST["userEmail"];
	$userName = $_POST["userName"];
	$prioritetId = $_POST["prioritetId"];
	$typeId = $_POST["typeId"];
	$subject = $_POST["subject"];
	$msgs = $_POST["msgs"];
	$department = kyDepartment::get($_POST["departamentId"]);
	$status_id = kyTicketStatus::getAll()->filterByTitle("Open")->first()->getId();
	kyTicket::setDefaults($status_id, $prioritetId, $typeId);
	$ticket = kyTicket::createNewAuto($department, $userName, $userEmail, $msgs, $subject)
		->create()->;
	// $priority = kyTicketPriority::getAll()->filterByTitle("Low")->first();
	// $user = KyUser::getAll()->filterByEmail($userEmail)->first();
	// $ticket = $user
	// ->newTicket(
	// 	$department,
	// 	$msgs,
	// 	$subject)
	// 	->setPriority($priority)
	// 	->create();
		// echo "string";
}

// Отрисовка формы.
	$depList = getDepartmentsTree(); // Берем департаменты.
	$types = kyTicketType::getAll()->filterByType(kyTicketType::TYPE_PUBLIC); // Берем типы
	$prioritet= kyTicketPriority::getAll()->filterByTitle("Low")->first(); // Берем приоритет. default ->getId() = 1
?>
	<form method="POST">
		<select name="departamentId">
			<?php
			foreach ($depList as $dep) {
				$top_department = $dep['department'];
				$child_departments = $dep['child_departments'];
			?>
			<option value="<?=$top_department->getId()?>"><?=$top_department->getTitle()?></option>
			<?php
				foreach ($child_departments as $child_department) {
					?>
					<option value="<?=$child_department->getId()?>">|-<?=$child_department->getTitle()?></option>
					<?php		
				}// child departaments
			} // departaments
			?>
		</select><br>
<?php
	?>
	<input type="text" value="Максим" name="userName"><br>
	<input type="text" value="rufionov@gmail.com" name="userEmail"><br>
	<select name="typeId">
	<?php foreach ($types as $type) {?>
		<option value="<?=$type->getId()?>"><?=$type->getTitle()?></option>
	<?php } ?>
	</select><br>
	<input type="text" value="<?=$prioritet->getId()?>" name="prioritetId"><br>
	<input type="text" placeholder="Тема сообщения" value="demo ticket subject" name="subject"><br>
	<textarea name="msgs" cols="30" rows="10" placeholder="Введите ваш вопрос">demo ticket msgs</textarea><br>
	<input type="submit">
	</form>