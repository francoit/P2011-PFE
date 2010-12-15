<?php include('includes/main.php'); ?>
<?php include('includes/genfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
        echo '<center>';
        echo texttitle($lang['STR_USER_ADD']);
        if ($name&&!DEMO_MODE) genuseradd($name, $newpassword, $newpassword2, $raccessap, $raccessar, $raccessgl, $raccesspay, $raccessinv, $raccessest, $raccessfix, $raccessimp, $waccessap, $waccessar, $waccessgl, $waccesspay, $waccessinv, $waccessest, $waccessfix, $waccessimp, $saccessap, $saccessar, $saccessgl, $saccesspay, $saccessinv, $saccessest, $saccessfix, $saccessimp, $supervisoro, $active, $stylesheetid, $dlanguage);
        if (DEMO_MODE) echo $lang['STR_ADD_USER_DISABLED_IN_DEMO_MODE'].'<br>';
        echo '<form action="genuseradd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
        formgenuseradd();
        echo '</table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
        
        echo '</center>';
?>

<?php include('includes/footer.php'); ?>
