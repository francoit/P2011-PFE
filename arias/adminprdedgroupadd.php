<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center>';
     echo texttitle('Payroll Deduction Group Add');
     if ($name) prdedgroupadd($name);
     echo '<form action="adminprdedgroupadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
     formprdedgroupadd();
     echo '<br></table><input type="submit" value="Add"></form>';
      echo '<br><br><a href="javascript:doNothing()" onclick="top.newWin = window.open (\'helpadminprdedgroupadd.php?name='.$name.'\',\'cal\',\'dependent=yes,width=420,height=460,screenX=200,screenY=300,titlebar=yes,scrollbars=1,resizable=1\')"><img src="images/opera.jpg" width="40" border="0" alt="Payroll Deduction Group Update Help"></a>';
     echo '</center>';
?>
<?php include('includes/footer.php'); ?>
