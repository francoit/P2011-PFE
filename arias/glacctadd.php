<?php include('includes/main.php'); ?>
<?php include('includes/glfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //GLACCTADD.PHP
	echo '<center>';
	if ($accounttypeid) {
		if ($accounttypeid==40) $companyid=0; // retained earnings type ALWAYS applies to all companies
		$recordSet = &$conn->Execute('select id,description from accounttype where id='.$accounttypeid);
        if ($recordSet&&!$recordSet->EOF) echo texttitle($lang['STR_ACCOUNT_TYPE'] .$recordSet->fields[0]." - ".$recordSet->fields[1]);
		if ($name) { //add account
			glaccountadd($name, $companyid, $description, $accounttypeid, $summaryaccountid);
		} else { //ask for more info
			echo '<form action="glacctadd.php" method="post" name="mainform"><input type="hidden" name="accounttypeid" value="'.$accounttypeid.'"><input type="hidden" name="nonprintable" value="1"><table>';
			formglaccountadd($accounttypeid);
			echo '</table><br><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
		};
	} else { //select which account type
		echo texttitle($lang['STR_ACCOUNT_ADD']);
		echo '<form action="glacctadd.php" method="post"><table>';
		formglaccounttypeselect('accounttypeid');
		echo '</table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
	};
	echo '</center>';
?>

<?php include('includes/footer.php'); ?>
