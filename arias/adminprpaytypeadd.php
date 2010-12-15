<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center>';
     echo texttitle($lang['STR_PAYROLL_PAY_TYPE_ADD']);
     if ($name) {
          if ($type==1) $vacation=1; //set correct variable from type select box
          if ($type==2) $sick=1;
          prpaytypeadd($name,$description,$multiplier,$vacation,$sick);
     };
     echo '<form action="adminprpaytypeadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
     formprpaytypeadd();
     echo '</table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
     echo '<br><br><a href="javascript:doNothing()" onclick="top.newWin = window.open (\'helpadminprpaytypeadd.php?name='.$name.'\',\'cal\',\'dependent=yes,width=420,height=460,screenX=200,screenY=300,titlebar=yes,scrollbars=1,resizable=1\')"><img src="images/opera.jpg" width="40" border="0" alt="Payroll Pay Type Add Help"></a>';
     echo '</center>';
?>
<?php include('includes/footer.php'); ?>
