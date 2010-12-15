<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>


<?
	echo texttitle($lang['STR_TAX_EXEMPTION_ADD']);
	echo '<center>';
	if ($exemptname) artaxexemptionadd($exemptname);
	echo '<form action="adminartaxexadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
	echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_EXEMPTION_REASON'].':</td><td><input type="text" name="exemptname" size="30" maxlength="30"></td></tr>';
	echo '</table><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
	echo '</center>';
?>

<?php include('includes/footer.php'); ?>
