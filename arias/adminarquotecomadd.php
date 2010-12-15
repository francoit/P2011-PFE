<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>


<?
    echo '<center>';
	echo texttitle($lang['STR_QUOTE_COMMENT_ADD']);
	if ($comment) arquotecommentadd($comment);
	echo '<form action="adminarquotecomadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_QUOTE_COMMENT'].': </td><td><input type="text" name="comment" size="30" maxlength="100"></td></tr>';
        echo '</table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
        echo '</center>';
?>

<?php include('includes/footer.php'); ?>
