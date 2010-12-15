<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?php include('includes/glfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?
        echo '<center>';
        echo texttitle($lang['STR_AP_CHECKING']);
        if ($paid) {
                if (!is_array($paid)) $paid=array($paid);
                foreach ($paid as $data) {
                        $conn->Execute('update chk set cashdate='.sqlprep(createtime('Y-m-d')).' where id='.sqlprep($data));
                };
                echo textsuccess($lang['STR_CHECKS_MARKED_AS_CASH']);
        };
        if ($void) {
                if (!is_array($void)) $void=array($void);
                foreach ($void as $data) {
                        if (!apvoidcheck($data)) echo texterror($lang['STR_ERROR_VOIDING_CHECK']);
                };
                echo textsuccess($lang['STR_CHECK_VOIDED_SUCCESSFULLY']);
        };
        if ($checknumber||$invoicenumber||$vendorid) {
                if (!$checkid) {
                        if ($checknumber) $checkstr=' and chk.checknumber='.sqlprep($checknumber);
                        if ($invoicenumber) $invoicestr=' and apbill.invoicenumber='.sqlprep($invoicenumber);
                        if ($vendorid) $vendorstr=' and apbill.vendorid='.sqlprep($vendorid);
                        if ($cashed==1) $cashstr=" and chk.cashdate>'0000-00-00'";
                        if ($cashed==2) $cashstr=" and chk.cashdate='0000-00-00'";
                        if ($order==1) $orderstr=' order by checkacct.name, chk.checkdate desc, chk.checknumber';
                        if ($order==2) $orderstr=' order by company.companyname, chk.checkdate desc, chk.checknumber';
                        if ($order==3) $orderstr=' order by chk.checknumber';
                        $recordSet = &$conn->Execute('select company.companyname, chk.checknumber, chk.checkdate, chk.amount, chk.id, checkacct.name, chk.cashdate, chk.checkvoid from apbill,apbillpayment,chk,vendor,company,checkacct where apbillpayment.apbillid=apbill.id and apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id and apbill.gencompanyid='.sqlprep($active_company).$checkstr.$invoicestr.$vendorstr.$cashstr.' and chk.checkvoid=0 and apbillpayment.checkid=chk.id and chk.checkaccountid=checkacct.id'.$orderstr);
                        if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_CHECKS_FOUND']));
                        echo '<form name="mainform" method="post" action="apchecking.php"><table border="1"><tr><th>'.$lang['STR_CHECKING_ACCOUNT'].'</th><th>'.$lang['STR_VENDOR'].'</th><th>'.$lang['STR_CHECK_DATE'].'</th><th>'.$lang['STR_CHECK_NUMBER'].'</th><th>'.$lang['STR_AMOUNT'].'</th><th>'.$lang['STR_VOID'].'?</th><th>'.$lang['STR_CASHED_QUESTION_MARK'].'</th></tr>';
                        while (!$recordSet->EOF) {
                                echo '<tr><td>'.$recordSet->fields[5].'</td><td>'.$recordSet->fields[0].'</td><td>'.$recordSet->fields[2].'</td><td><a href="checks.php?apbill=1&endorser=&checknbr='.$recordSet->fields[4].'" target="_new">'.$recordSet->fields[1].'</a></td><td>'.CURRENCY_SYMBOL.checkdec($recordSet->fields[3],2).'</td>';
                                if ($recordSet->fields[6]=='0000-00-00'||$recordSet->fields[7]==0) { //if cashed or voided, don't show checkboxes
                                        echo '<td align="center"><input type="checkbox" name="void[]" value="'.$recordSet->fields[4].'"'.INC_TEXTBOX.'></td><td align="center"><input type="checkbox" name="paid[]" value="'.$recordSet->fields[4].'"'.INC_TEXTBOX.'>';
                                } elseif ($recordSet->fields[7]==1) { //voided, show Y under void column
                                        echo '<td align="center">Y</td><td align="center">N/A';
                                } else {
                                        echo '<td align="center">N</td><td align="center">Y';
                                };
                                echo '</td></tr>';
                                $recordSet->MoveNext();
                        };
                        echo '</table><input type="submit" value="'.$lang['STR_SUBMIT'].'"></form>';
                };
        } else {
                echo '<form action="apchecking.php" method="post" name="mainform"><table>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHECK_NUMBER'].':</td><td><input type="text" name="checknumber" onchange="validateint(this)" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_NUMBER'].':</td><td><input type="text" name="invoicenumber" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
                formapvendorselect('vendorid');
                $recordSet=&$conn->Execute('select count(*) from checkacct where ap=1 and gencompanyid='.sqlprep($active_company));
                if (!$recordSet->EOF) if ($recordSet->fields[0]==1) {
                        $recordSet=&$conn->Execute('select id from checkacct where ap=1 and gencompanyid='.sqlprep($active_company));
                        echo '<input type="hidden" name="checkacctid" value="'.$recordSet->fields[0].'">';
                } else {
                        $recordSet=&$conn->Execute('select id, name from checkacct where ap=1 and gencompanyid='.sqlprep($active_company));
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHECKING_ACCOUNT'].'</td><td><select name="checkacctid"'.INC_TEXTBOX.'>';
                        while (!$recordSet->EOF) {
                                echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                                $recordSet->MoveNext();
                        };
                        echo '</select></td></tr>';
                };
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CASHED_QUESTION_MARK'].':</td><td><select name="cashed"><option value="" selected>'.$lang['STR_ALL'].'<option value="1">'.$lang['STR_SHOW_ONLY_CASHED'].'<option value="2">'.$lang['STR_SHOW_ONLY_NON_CASHED'].'</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ORDER'].':</td><td><select name="order"><option value="1" selected>'.$lang['STR_CHECKING_ACCOUNT'].', '.$lang['STR_DATE'].', '.$lang['STR_CHECK_NUMBER'].'<option value="2">'.$lang['STR_VENDOR'].', '.$lang['STR_DATE'].', '.$lang['STR_CHECK_NUMBER'].'<option value="3">'.$lang['STR_CHECK_NUMBER'].'</select></td></tr>';
                echo '</table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
                
                echo '</center>';
         };
?>

<?php include_once("includes/footer.php"); ?>
