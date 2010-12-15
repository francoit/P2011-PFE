<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>


<?
	echo texttitle($lang['STR_TAX_EXEMPTION_UPDATE']);
    echo '<center>';
	if ($id) { //if tax exemption selected
		if ($delete) { //if tax exemption should be deleted
			if (artaxexemptiondelete($id)) die(); //OK
		} elseif ($exemptname) { //if tax exemption should be updated
			artaxexemptionupdate($id, $exemptname);
		};
		//edit tax exemption
		echo '<form action="adminartaxexupd.php" method="post"><input type="hidden" name="id" value="'.$id.'"><table>';
		formartaxexemptionupdate($id);
		echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form><a href="javascript:confirmdelete(\'adminartaxexupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_TAX_EXEMPTION'].'</a>';
	} else { //select tax exemption
		echo '<form action="adminartaxexupd.php" method="post"><table>';
		if (formartaxexemptionselect('id')) echo '<center><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></center>';
		echo '</table></form><br><a href="adminartaxexadd.php">'.$lang['STR_ADD_NEW_TAX_EXEMPTION'].'</a>';
	};
	 
        echo '</center>';
?>

<?php include('includes/footer.php'); ?>
