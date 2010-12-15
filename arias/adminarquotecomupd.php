<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
    echo '<center>';
	echo texttitle($lang['STR_QUOTE_COMMENT_UPDATE']);
	if ($id) { //if quote comment selected
		if ($delete) { //if quote comment should be deleted
			if (arquotecommentdelete($id)) die(); //OK
		} elseif ($comment) { //if quote comment should be updated
			arquotecommentupdate($id, $comment);
		};
		//edit quote comment
		echo '<form action="adminarquotecomupd.php" method="post"><br><input type="hidden" name="id" value="'.$id.'"><table>';
		formarquotecommentupdate($id);
		echo '</table><br><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form><a href="javascript:confirmdelete(\'adminarquotecomupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_QUOTE_COMMENT'].'</a>';
	} else { //select quote comment
		echo '<form action="adminarquotecomupd.php" method="post"><table>';
		if (formarquotecommentselect('id')) echo '<br><center><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></center><br>';
		echo '</table></form><br><a href="adminarquotecomadd.php">'.$lang['STR_ADD_NEW_QUOTE_COMMENT'].'</a>';
	};
	
        echo '</center>';
?>

<?php include('includes/footer.php'); ?>
