<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>

<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>


<script language="JavaScript" src="js/validatephone.js">
</script>
<?
     echo texttitle($lang['STR_SHIPPING_CARRIER_ADD']);
     echo '<center>';
     if ($name) {
          $conn->BeginTrans();
          $recordSet = &$conn->SelectLimit('select id from company where companyname='.sqlprep($name).' order by id desc', 1);
          if (!$recordSet||$recordSet->EOF) {
               if (!(arcompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name, $mailstop))) die();
          }
          $recordSet = &$conn->SelectLimit('select id from company where companyname='.sqlprep($name).' order by id desc', 1);
          if ($recordSet&&!($recordSet->EOF)) {
               $companyid=$recordSet->fields[0];
               if (arcarrieradd($companyid, $customernumber, $trackingurlbase, $trackingurlvarname)) {
                    $conn->CommitTrans();
               } else {
                    $conn->RollbackTrans();
               };
           } else {
               $conn->RollbackTrans();
               die($lang['STR_COMPANY_NOT_FOUND']);
           };
     };
     echo '<form action="adminarcarrieradd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
     formarcarrieradd();
     echo '</table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
     echo '</center>';
?>

<?php include('includes/footer.php'); ?>
