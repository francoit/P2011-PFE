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
     echo texttitle($lang['STR_COMPANY_ADD']);
     if ($name) gencompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone2, $phone3, $phone4, $email, $web, $name);
     echo '<form action="gencompanyadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
     formgencompanyadd();
     echo '</table><br><br><input type="submit" value="Add Company Now"></form>';
     echo '</center>';
?>

<?php include('includes/footer.php'); ?>
