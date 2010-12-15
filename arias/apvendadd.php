<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript" src="js/validatephone.js">
</script>
<?
     echo '<center>';
     echo texttitle('Vendor Add');
     if (($name&&!$useforpayto||$payto)) {
          echo texttitle('Pay To Info');
     } else {
          echo texttitle('Order From Info');
     };
     if ($payto) {
          $conn->BeginTrans();
          if (!apcompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name, $mailstop)) {
               $conn->RollbackTrans();
               die();
          };
          $recordSet = &$conn->SelectLimit('select id from company where upper(companyname)=upper('.sqlprep($name).') order by id desc',1);
          if (!$recordSet||$recordSet-EOF) {
               $conn->RollbackTrans();
               die(texterror($lang['STR_COMPANY_NOT_FOUND']));
          };
          $companyid=$recordSet->fields[0];
          if (!apvendorupdate($vendorid, $companyid, $orderfromcompanyid, $orderfromname, $paytermsid, $paynone, $defaultglacctid, $defaultbilldescription, $customeraccount, $lastchangedate)) {
               $conn->RollbackTrans();
               die();
          };
          $conn->CommitTrans();
          echo textsuccess($lang['STR_VENDOR_ADDED_SUCCESSFULLY']);
     } elseif ($name) {
          $conn->BeginTrans();
          if (!apcompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name, $mailstop)) {
               $conn->RollbackTrans();
               die();
          };
          $recordSet = &$conn->SelectLimit('select id from company where upper(companyname)=upper('.sqlprep($name).') order by id desc',1);
          if (!$recordSet||$recordSet->EOF) {
               $conn->RollbackTrans();
               die(texterror($lang['STR_COMPANY_NOT_FOUND']));
          };
          $companyid=$recordSet->fields[0];
          if (!apvendoradd($companyid, $companyid, $orderfromname, $paytermsid, $paynone, $defaultglacctid, $defaultbilldescription, $customeraccount)) {
               $conn->RollbackTrans();
               die();
          };
          $conn->CommitTrans();
          echo textsuccess($lang['STR_VENDOR_ADDED_SUCCESSFULLY']);
          if (!$useforpayto) {
               $recordSet = &$conn->SelectLimit('select id,lastchangedate from vendor where orderfromcompanyid='.sqlprep($companyid).' order by id desc',1);
               if (!$recordSet||$recordSet-EOF) die(texterror($lang['STR_VENDOR_NOT_FOUND']));
               $vendorid=$recordSet->fields[0];
               $lastchangedate = $recordSet->fields[1];
               echo '<form action="apvendadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
               echo '<input type="hidden" name="payto" value="1">';
               echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
               echo '<input type="hidden" name="orderfromcompanyid" value="'.$companyid.'">';
               echo '<input type="hidden" name="orderfromname" value="'.$orderfromname.'">';
               echo '<input type="hidden" name="paytermsid" value="'.$paytermsid.'">';
               echo '<input type="hidden" name="paynone" value="'.$paynone.'">';
               echo '<input type="hidden" name="defaultglacctid" value="'.$defaultglacctid.'">';
               echo '<input type="hidden" name="defaultbilldescription" value="'.$defaultbilldescription.'">';
               echo '<input type="hidden" name="customeraccount" value="'.$customeraccount.'">';
               echo '<input type="hidden" name="lastchangedate" value="'.$lastchangedate.'">';
               formapcompanyadd();
               echo '</table><input type="submit" value="Add"></form>';
          }
     } else {
          echo '<form action="apvendadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
          formapcompanyadd();
          formapvendoradd();
          echo '<tr><td>Make Pay To Same As Above</td><td><input type="checkbox" name="useforpayto" value="1" checked></td></tr>';
          echo '</table><br><input type="submit" value="Add"></form>';
     };
          echo '<center>';
?>
<?php include('includes/footer.php'); ?>
