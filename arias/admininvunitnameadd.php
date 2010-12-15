<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center>';
     echo texttitle($lang['STR_UNIT_NAME_ADD']);
     if ($unitname) invunitnameadd($unitname);
     echo '<form action="admininvunitnameadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table><tr><td>'.$lang['STR_INVENTORY_UNIT_NAME'].':</td><td><input type="text" name="unitname" size="30" maxlength="10"></td></tr></table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
				echo '<br><br><a href="javascript:doNothing()" onclick="top.newWin = window.open (\'helpadmininvunitnameadd.php?name='.$name.'\',\'cal\',\'dependent=yes,width=420,height=460,screenX=200,screenY=300,titlebar=yes,scrollbars=1,resizable=1\')"><img src="images/opera.jpg" width="40" border="0" alt="Add Inventory Unit"></a>';
     echo '</center>';
?>
<?php include('includes/footer.php'); ?>
