<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?
//  Copyright 2001 Noguska (All code unless noted otherwise)
//  Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo texttitle($lang['STR_PAYMENT_TERMS_ADD']);
     echo '<center>';
     if ($verbal) appaytermsadd($verbal, $discountpercent, $discountdays, $netduedays,$discountdayofmonth);
     echo '<form action="adminappaytermsadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
     formappaytermsadd();
     echo '</table><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
     
     echo '</center>';
?>

<?php include('includes/footer.php'); ?>
