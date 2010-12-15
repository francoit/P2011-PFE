<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?php include('includes/defines.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript" src="js/validatephone.js">
</script>
<?
     echo '<center>';
     echo texttitle($lang['STR_VENDOR_UPDATE']);
     if ($id) { //if user has selected a company
          if ($delete) {
               $recordSet = &$conn->Execute('select vendor.paytocompanyid, vendor.orderfromcompanyid from vendor where vendor.id='.sqlprep($id));
               if (!$recordSet->EOF) if (apcompanydelete($recordSet->fields[0])&&apcompanydelete($recordSet->fields[1])) apvendordelete($id);
          } elseif ($location==1) {
               $recordSet = &$conn->Execute('select vendor.paytocompanyid, vendor.orderfromcompanyid, company.companyname, company.lastchangedate from vendor, company where company.id=vendor.orderfromcompanyid and vendor.id='.sqlprep($id));
               if (!$recordSet->EOF) {
                    echo texttitle($lang['STR_GENERAL_PAYABLES_INFO_FOR'] .$recordSet->fields[2]);
                    if ($company) {
                         if (apvendorupdate($id, $paytocompanyid, $orderfromcompanyid, $orderfromname, $paytermsid, $paynone, $defaultglacctid, $defaultbilldescription, $customeraccount,$lastchangevendordate)){
                              die(textsuccess($lang['STR_VENDOR_UPDATED_SUCCESSFULLY']));
                         };
                    };
                    echo '<form action="apvendupd.php" method="post"><table>';
                    echo '<input type="hidden" name="location" value="'.$location.'">';
                    echo '<input type="hidden" name="id" value="'.$id.'">';
                    echo '<input type="hidden" name="paytocompanyid" value="'.$recordSet->fields[0].'">';
                    echo '<input type="hidden" name="orderfromcompanyid" value="'.$recordSet->fields[1].'">';
                    echo '<input type="hidden" name="company" value="'.$recordSet->fields[2].'">';
                    formapvendorupdate($id);
                    echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'apvendupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_VENDOR'].'</a>';
               } else {
                   die(texterror($lang['STR_VENDOR_NOT_FOUND']));
               };
          } elseif ($location==2||$location==3) {
               if ($name) {
                    if (apcompanyupdate($id, $address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name,$mailstop,$lastchangedate)) echo textsuccess($lang['STR_VENDOR_UPDATED_SUCCESSFULLY']);
                    die();
               } else {
                    if ($location==2) {
                         $recordSet = &$conn->Execute('select vendor.orderfromcompanyid, company.companyname, vendor.paytocompanyid, company.lastchangedate from vendor, company where company.id=vendor.orderfromcompanyid and vendor.id='.sqlprep($id));
                         $display=$lang['STR_ORDER_FROM_INFORMATION_FOR'];
                    } elseif ($location==3) {
                         $recordSet = &$conn->Execute('select vendor.paytocompanyid, company.companyname, vendor.orderfromcompanyid, company.lastchangedate from vendor, company where company.id=vendor.paytocompanyid and vendor.id='.sqlprep($id));
                         $display=$lang['STR_PAYMENT_INFORMATION_FOR'];
                    };
                    if (!$recordSet->EOF) {
                         echo texttitle($display.$recordSet->fields[1]);
                         echo '<form action="apvendupd.php" method="post"><table>';
                         echo '<input type="hidden" name="location" value="'.$location.'">';
                         echo '<input type="hidden" name="id" value="'.$recordSet->fields[0].'">';
                         echo '<input type="hidden" name="name" value="'.$recordSet->fields[1].'">';
                         if ($location==2) {
                              echo '<input type="hidden" name="orderfromcompanyid" value="'.$recordSet->fields[0].'">';
                              echo '<input type="hidden" name="paytocompanyid" value="'.$recordSet->fields[2].'">';
                         } else {
                              echo '<input type="hidden" name="paytocompanyid" value="'.$recordSet->fields[0].'">';
                              echo '<input type="hidden" name="orderfromcompanyid" value="'.$recordSet->fields[2].'">';
                         };
                         echo '<input type="hidden" name="lastchangedate" value="'.$recordSet->fields[3].'">';
                         formapcompanyupdate($recordSet->fields[0]);
                         echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'apvendupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_VENDOR'].'</a>';
                    } else {
                         die(texterror($lang['STR_VENDOR_NOT_FOUND']));
                    };
               };
          };
     } else { //let user pick vendor
          echo '<form action="apvendupd.php" method="post" name="mainform"><table>';
          formapvendorselect('id');
          echo '</table><table border="1"><tr><th colspan="2">'.$lang['STR_EDIT'].':</th></tr>';
          echo '<tr><td>'.$lang['STR_GENERAL_INFO'].'</td><td><input type="radio" name="location" value="1" checked></td></tr>';
          echo '<tr><td>'.$lang['STR_ORDER_FROM_INFO'].'</td><td><input type="radio" name="location" value="2"></td></tr>';
          echo '<tr><td>'.$lang['STR_PAY_TO_INFO'].'</td><td><input type="radio" name="location" value="3"></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_SELECT'].'"></form>';
          echo '<center><br><a href="apvendadd.php">'.$lang['STR_ADD_NEW_VENDOR'].'</a></center>';
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
