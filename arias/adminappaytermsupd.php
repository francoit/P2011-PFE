<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
	echo texttitle($lang['STR_PAYMENT_TERMS_UPDATE']);
	echo '<center>';
	if ($id) { // if the user has submitted info
		if ($delete) {
			if (appaytermsdelete($id)) die(textsuccess('Payment terms deleted successfully'));
		} elseif ($verbal) { //if we should update the entry
			appaytermsupdate($id, $verbal, $discountpercent, $discountdays, $netduedays,$discountdayofmonth);
		}; //if we should display more info about the entry that the user can edit
		echo '<form action="adminappaytermsupd.php" method="post"><table><input type="hidden" name="id" value="'.$id.'">';
		formappaytermsupdate($id);
		echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'adminappaytermsupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_PAYMENT_TERMS'].'</a>';
	} else { //display unit names, let the user pick one to edit
		echo '<form action="adminappaytermsupd.php" method="post"><table>';
		formappaytermsselect('id');
		echo '</table><br><input type="submit" value="'.$lang['STR_EDIT'].'"></form>';
        echo '<br><a href="adminappaytermsadd.php">'.$lang['STR_ADD_NEW_PAYMENT_TERMS'].'</a>';
        echo '</center>';
	};
?>

<?php include('includes/footer.php'); ?>
