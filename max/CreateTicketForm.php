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
var_dump(getDepartmentsTree());
?>
<form method="POST">
	<?php
		foreach ($departments_tree as $department_leaf) {
			$top_department = $department_leaf['department'];
			$child_departments = $department_leaf['child_departments'];

	?>
			<label>
			<input type="radio" name="department_id" value="<?=$top_department->getId()?>">
			<span><?=$top_department->getTitle()?></span>
			</label>
<?php
					foreach ($child_departments as $child_department) {
						/*@var $child_department kyDepartment */
?>
						<label>
							<input type="radio" name="department_id" value="<?=$child_department->getId()?>">
							<span><?=$child_department->getTitle()?></span>
						</label>
<?php		
			}// child
		} // departaments
?>
</form>