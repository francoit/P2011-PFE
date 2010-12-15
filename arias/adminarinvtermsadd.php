<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
	echo texttitle($lang['STR_INVOICE_TERMS_ADD']);
	echo '<center>';
	if ($verbal) arinvoicetermsadd($verbal, $discountpercent, $discountdays, $netduedays);
	echo '<form action="adminarinvtermsadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
	formarinvoicetermsadd();
	echo '</table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
	
    echo '</center>';
?>

<?php include('includes/footer.php'); ?>
