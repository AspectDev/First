<?php
include_once "config.php";
include_once "functions.php";
$functions = new Functions;

if(isset($_POST) && !empty($_POST)){
	$user = $functions->RetriveUserInfoByEmail("ip@dalli-service.com");
	
	$data = array(
		'fullname' => $user["fullname"],
		'email' => $user["email"],
		'subject' => $_POST["subject"],
		'contents' => $_POST["msgs"],
		'departmentid' => $_POST["departamentid"],
		'ticketstatusid' => 1, // 1-Открытый
		'ticketpriorityid' => $_POST["priorityid"],
		'tickettypeid' => $_POST["typeid"],
		'ignoreautoresponder' => 1,
		'userid'=>$user["id"]);
	$a = $functions->NewTicket($data);
	echo "Спасибо что связались с нами";
}else{	
	$departamentList = $functions->RetriveDepartamentList(false);
	$priorityList = $functions->RetrivePriorityList();
	$priorityList = $functions->RetrivePriorityList();
	$typeList = $functions->RetriveTicketTypeList();
	// var_dump($RetriveTicketTypeList);
?>
<form method="POST">
	<p><label for="departamentid">Выберите департамент</label>
	<select name="departamentid" id="departamentid">
		<?php
		foreach ($departamentList as $val){ ?>
			<option value="<?=$val['id']?>"><?=$val['title']?></option>
		<?php }?>
	</select></p>
	<p><label for="priority">Выберите приоритет</label>
	<select name="priorityid" id="priority">
		<?php
		foreach ($priorityList as $val){ ?>
			<option value="<?=$val['id']?>"><?=$val['title']?></option>
		<?php }?>
	</select></p>
	<p><label for="typeid">Выберите Тип</label>
	<select name="typeid" id="typeid">
		<?php
		foreach ($typeList as $val){ ?>
			<option value="<?=$val['id']?>"><?=$val['title']?></option>
		<?php }?>
	</select></p>
	<p><label for="subject">Тема сообщения</label>
	<input name="subject" id="subject" type="text"></p>
	<p><label for="msgs">Ваше сообщение</label>
	<textarea name="msgs" id="msgs"></textarea></p>
	<input type="submit" name='send' value="Отправить!">
</form>
<?php } ?>