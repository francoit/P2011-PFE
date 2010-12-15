<?php include('includes/main.php'); ?>
<?php include('includes/genfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript" src="js/validatephone.js">
</script>
<?
	echo '<center>';
	echo texttitle($lang['STR_COMPANY_UPDATE']);
	if ($id) {
        if (!DEMO_MODE) {
		  if ($delete) {
			if (gencompanydelete($id)) die(textsuccess(''));
		  } elseif ($name) {
			gencompanyupdate($id, $address1, $address2, $city, $state, $zip, $country, $phone1, $phone2, $phone3, $phone4, $email, $web, $name);
		  };
        } else {
            echo textsuccess($lang['STR_CANNOT_UPDATE_COMPANIES_WHILE_IN_DEMO_MODE']);
        };
		echo '<form action="gencompanyupd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
		echo '<input type="hidden" name="id" value="'.$id.'">';
		formgencompanyupdate($id);
			echo '</table><br><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <br><a href="javascript:confirmdelete(\'gencompanyupd.php?delete=1&id='.$id.'\')">Delete this Company</a>';
	} else {
            echo '<form action="gencompanyupd.php" method="post"><table>';
		formgencompanyselect('id');
		echo '</table><br><input type="submit" value="'.$lang['STR_EDIT_SELECTED_COMPANY'].'"></form>';
       		echo '<br><a href="gencompanyadd.php">'.$lang['STR_ADD_NEW_COMPANY'].'</a>';
	};
	
        echo '</center>';

?>

<?php include('includes/footer.php'); ?>
