<?php include('includes/main.php'); ?>
<?php include('includes/glfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?
	echo texttitle($lang['STR_ACCOUNT_TYPE_ADD']);
	echo '<center>';
	if ($accounttype) glaccounttypeadd($accounttype);
	echo '<form action="adminglaccttypeadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table><tr><td>'.$lang['STR_GENERAL_LEDGER_ACCOUNT_TYPE'].':</td><td><input type="text" name="accounttype" size="30"></td></tr></table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
	echo '<br><br><a href="javascript:doNothing()" onclick="top.newWin = window.open (\'helpadminglaccttypeadd.php?name='.$name.'\',\'cal\',\'dependent=yes,width=420,height=460,screenX=200,screenY=300,titlebar=yes,scrollbars=1,resizable=1\')"><img src="images/opera.jpg" width="40" border="0" alt="Account Type Add"></a>';
	echo '</center>';
?>

<?php include('includes/footer.php'); ?>
