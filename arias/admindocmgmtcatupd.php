<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?

	echo texttitle($lang['STR_DOCUMENT_MANAGER_CATEGORY_UPDATE']);
	echo '<center>';
	if ($id) { // if the user has submitted info
		if ($delete) { //if we should be deleting the entry
			$conn->Execute('delete from docmgmtcategory where id='.sqlprep($id));
			die(textsuccess($lang['STR_CATEGORY_DELETED_SUCCESSFULLY']));
		} elseif ($name) { //if we should update the entry
			$conn->Execute('update docmgmtcategory set name='.sqlprep($name).' where id='.sqlprep($id));
			echo textsuccess($lang['STR_CATEGORY_UPDATED_SUCCESSFULLY']);
		}; //if we should display more info about the entry that the user can edit
		echo '<form action="admindocmgmtcatupd.php" method="post"><table><input type="hidden" name="id" value="'.$id.'">';
		$recordSet=&$conn->Execute('select name from docmgmtcategory where id='.sqlprep($id));
		if ($recordSet->EOF) die(texterror($lang['STR_CATEGORY_NOT_FOUND']));
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CATEGORY_NAME'].': </td><td><input type="text" name="name" value="'.$recordSet->fields[0].'" size="30" maxlength="255" '.INC_TEXTBOX.'> ';
		echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'admindocmgmtcatupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_CATEGORY'].'</a>';
	} else { //display categories, let the user pick one to edit
		echo '<form action="admindocmgmtcatupd.php" method="post" name="mainform"><table>';
		$recordSet=&$conn->Execute('select id,name from docmgmtcategory');
		if ($recordSet->EOF) die(texterror($lang['STR_CATEGORY_NOT_FOUND']));
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CATEGORY_NAME'].': </td><td><select name="id"'.INC_TEXTBOX.'>';
		while (!$recordSet->EOF) {
			echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1];
			$recordSet->MoveNext();
		};
		echo '</select></td></tr></table><br><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></form>';
        echo '<br><a href="admindocmgmtcatadd.php">'.$lang['STR_ADD_NEW_CATEGORY'].'</a>';
	};
	 
        echo '</center>';
?>

<?php include('includes/footer.php'); ?>
