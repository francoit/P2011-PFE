<?php require_once('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
	for ($i=1;${"column".$i};$i++) {
		$columns .= ",".${"column".$i};
	};
	$columns=substr($columns,1);
	$recordSet = &$conn->Execute('select '.$columns.' from '.$table.' order by '.$order);
	if ($recordSet->EOF) die(texterror('No matching items found.'));
	echo '<form><select name="'.$table.'">';
	while (!$recordSet->EOF) {
		echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[0];
		for ($j=1;$j<=$i-2;$j++) {
			echo ' - '.$recordSet->fields[$j];
		};
		echo "\n";
		$recordSet->MoveNext();
	};
?>
<?php require_once('includes/footer.php');?>
