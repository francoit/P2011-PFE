<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>

<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>


<script language="JavaScript" src="js/validatephone.js">
</script>
<script language="JavaScript">
        function checkChoice(i) {
                if (document.mainform.salesman.checked == false) {
                        if (document.mainform.servicerep.checked == false) {
                                if (i=="1") {
                                        document.mainform.servicerep.checked = true;
                                } else {
                                        document.mainform.salesman.checked = true;
                                }
                        }
                }
        }
</script>
<?
		  echo '<center>';
        echo texttitle($lang['STR_SALES_PERSONNEL_ADD']);
        if ($name) {
                $conn->BeginTrans();
                if (arcompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name, $mailstop)) {
                        $recordSet = &$conn->SelectLimit('select id from company where companyname='.sqlprep($name).' order by id desc', 1);
                        if ($recordSet&&!$recordSet->EOF) {
                                $companyid=$recordSet->fields[0];
                                if (arsalesmanadd($companyid, $payrollid, $commissionrate, $commissionbase, $servicerep, $salesman)) {
                                        $conn->CommitTrans();
                                } else {
                                        $conn->RollbackTrans();
                                };
                        } else {
                                $conn->RollbackTrans();
                                die(texterror($lang['STR_COMPANY_NOT_FOUND']));
                        };
                } else {
                        $conn->RollbackTrans();
                };
        };
        echo '<form action="adminarsalesmanadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
        formarsalesmanadd();
        echo '</table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
        echo '</center>';
?>

<?php include('includes/footer.php'); ?>
