<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
	echo '<center>';
    echo texttitle($lang['STR_UNIT_NAME_UPDATE']);
	if ($id) { // if the user has submitted info
		if ($delete) { //if we should be deleting the entry
			if (invunitnamedelete($id)) die(textsuccess($lang['STR_UNIT_NAME_DELETED_SUCCESSFULLY']));
		} elseif ($unitname) { //if we should update the entry
			invunitnameupdate($id, $unitname);
		}; //if we should display more info about the entry that the user can edit
		echo '<form action="admininvunitnameupd.php" method="post"><table>';
		forminvunitnameupdate($id);
		echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'admininvunitnameupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_UNIT_NAME'].'</a>';
	} else { //display unit names, let the user pick one to edit
		echo '<form action="admininvunitnameupd.php" method="post"><table>';
		forminvunitnameselect('id');
		echo '</table><br><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></form>';
        echo '<br><a href="admininvunitnameadd.php">'.$lang['STR_ADD_NEW_UNIT_NAME'].'</a>';
	};
	    
        echo '</center>';
?>

<?php include('includes/footer.php'); ?>
