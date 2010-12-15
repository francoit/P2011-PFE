<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?

 echo '<center>';
	echo texttitle($lang['STR_SALES_TAX_ADD']);
	if ($taxname) arsalestaxadd($taxname, $taxrate, $taxbase, $glacctid);
	echo '<form action="adminarsalestaxadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
	formarsalestaxadd();
	echo '</table><br><input type="submit" value="Add"></form>';
    echo '</center>';
?>
<?php include('includes/footer.php'); ?>
