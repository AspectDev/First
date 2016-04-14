<?php
require_once("config.php");
function getDepartmentsTree() {
	$departments_tree = array();
	$all_departments = kyDepartment::getAll()->filterByModule(kyDepartment::MODULE_TICKETS)->filterByType(kyDepartment::TYPE_PUBLIC);

	$top_departments = $all_departments->filterByParentDepartmentId(null)->orderByDisplayOrder();
	foreach ($top_departments as $top_department) {
		/* @var $top_department kyDepartment */
		$departments_tree[$top_department->getId()] = array(
			'department' => $top_department,
			'child_departments' => $all_departments->filterByParentDepartmentId($top_department->getId())->orderByDisplayOrder()
		);
	}

	return $departments_tree;
}
$departments_tree =getDepartmentsTree();
?>
<form method="POST">
	<select name="departament">
	<?php
		foreach ($departments_tree as $department_leaf) {
			$top_department = $department_leaf['department'];
			$child_departments = $department_leaf['child_departments'];
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
	</select>
</form>