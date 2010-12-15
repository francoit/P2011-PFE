<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript" src="js/validatephone.js">
</script>
<?
    echo '<center>';
    echo texttitle($lang['STR_CUSTOMER_ADD']);
    if ($name) {
               $conn->BeginTrans();
               if (!arcompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name,$mailstop)) {
                       $conn->RollbackTrans();
                       die();
               };
               $recordSet = &$conn->SelectLimit('select id from company where companyname='.sqlprep($name).' order by id desc', 1);
               if (!$recordSet||$recordSet->EOF) {
                       $conn->RollbackTrans();
                       die(texterror($lang['STR_COMPANY_NOT_FOUND']));
               };
               $companyid=$recordSet->fields[0];
               $shiptocompanyid=$recordSet->fields[0];
               if (!arcustomeradd($companyid, $taxexemptid, $creditlimit, $salesglacctid, $salesmanid, $servicerepid, $invoicetermsid, $quotecommentid, $interest, $billtoattnname, $quoteattnname, $chargecode, $salestaxnum)) {
                       $conn->RollbackTrans();
                       die();
               };
               $recordSet = &$conn->SelectLimit('select id from customer where companyid='.sqlprep($companyid).' order by id desc', 1);
               if (!$recordSet||$recordSet->EOF) {
                       $conn->RollbackTrans();
                       die(texterror($lang['STR_CUSTOMER_NOT_FOUND']));
               };
               $customerid=$recordSet->fields[0];
               if (!arshiptoadd($companyid, $shiptocompanyid, $defaultshipvia)) {
                       $conn->RollbackTrans();
                       die();
               };
               for ($taxcounter=1;$taxcounter<=MAX_CUSTOMER_SALESTAX;$taxcounter++) {
                       if (${"taxid".$taxcounter}+${"taxrecid".$taxcounter}>0) { //only do something if either a tax rate chosen, or previous tax rate entered.
                               if (!arcustomertaxadd(${"taxid".$taxcounter},$customerid,${"taxrecid".$taxcounter})) {
                                       $conn->RollbackTrans();
                                       die();
                               };
                       };
               };
               $conn->CommitTrans();
               echo textsuccess($lang['STR_CUSTOMER_ADDED_SUCCESSFULLY']);
    };
    echo '<form action="arcustadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
    formarcustomeradd();
    echo '</table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
    echo '</center>';
?>
<?php include('includes/footer.php'); ?>
