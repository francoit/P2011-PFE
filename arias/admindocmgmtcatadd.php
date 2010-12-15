<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>


<?
	echo texttitle($lang['STR_DOCUMENT_MANAGER_CATEGORY_ADD']);
	echo '<center>';
	if ($name) {
		$conn->Execute('insert into docmgmtcategory (name) values ('.sqlprep($name).')');
		echo textsuccess($lang['STR_CATEGORY_ADDED_SUCCESSFULLY']);
	};
	echo '<form action="admindocmgmtcatadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table><tr><td>'.$lang['STR_CATEGORY_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="255"></td></tr></table><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
    echo '</center>';
?>

<?php include('includes/footer.php'); ?>
