<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<script language="JavaScript">
      var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?
        echo '<center>';
        echo texttitle($lang['STR_AP_CHECK_LIST']);
        if ($new) {
             unset($new);
             unset($checknumber);
             unset($invoicenumber);
             unset($vendorid);
             unset($continue);
        };
        if ($checknumber||$invoicenumber||$vendorid||$continue) {
                if (!$checkid) {
                        if ($checknumber) $checkstr=' and chk.checknumber='.sqlprep($checknumber);
                        if ($invoicenumber) $invoicestr=' and apbill.invoicenumber='.sqlprep($invoicenumber);
                        if ($vendorid) $vendorstr=' and apbill.vendorid='.sqlprep($vendorid);
                        if ($cashed==1) $cashstr=" and chk.cashdate>'0000-00-00'";
                        if ($cashed==2) $cashstr=" and chk.cashdate='0000-00-00'";
                        if (!$voids) $voidstr=" and chk.checkvoid='0'";
                        $datestr=' and chk.checkdate>='.sqlprep($bgdateofcheck).' and chk.checkdate<='.sqlprep($eddateofcheck);
                        if ($order==1) $orderstr=' order by checkacct.name, chk.checkdate desc, chk.checknumber';
                        if ($order==2) $orderstr=' order by company.companyname, chk.checkdate desc, chk.checknumber';
                        if ($order==3) $orderstr=' order by chk.checknumber';
                        if ($order==4) $orderstr=' order by chk.checkdate,company.companyname';
                        if ($order==5) $orderstr=' order by chk.amount,chk.checkdate';
                        $recordSet = &$conn->Execute('select distinct chk.id, company.companyname, chk.checknumber, chk.checkdate, chk.amount,  checkacct.name, chk.cashdate,chk.checkvoid from apbill,apbillpayment,chk,vendor,company,checkacct where apbillpayment.apbillid=apbill.id and apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id and apbill.gencompanyid='.sqlprep($active_company).$checkstr.$invoicestr.$vendorstr.$cashstr.$voidstr.$datestr.' and apbillpayment.checkid=chk.id and chk.checkaccountid=checkacct.id'.$orderstr);
                        if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_CHECKS_FOUND']));
                        $passstr='checknumber='.$checknumber.'&&invoicenumber='.$invoicenumber.'&&vendorid='.$vendorid.'&&continue='.$continue.'&&voids='.$voids.'&&cashed='.$cashed.'&&bgdateofcheck='.$bgdateofcheck.'&&eddateofcheck='.$eddateofcheck;
                        echo '<form name="mainform" method="post" action="apchecklist.php"><table border="1">';
                        echo '<tr><th><a href="apchecklist.php?'.$passstr.'&&order=1">'.$lang['STR_CHECKING_ACCOUNT'].'</a></th><th><a href="apchecklist.php?'.$passstr.'&&order=2">Vendor</a></th><th><a href="apchecklist.php?'.$passstr.'&&order=4">Check Date</a></th><th><a href="apchecklist.php?'.$passstr.'&&order=3">Check #</a></th><th><a href="apchecklist.php?'.$passstr.'&&order=5">Amount</a></th><th>Void?</th><th>Cashed?</th></tr>';
                        while (!$recordSet->EOF) {
                                $allcheckstr.='checknbr[]='.$recordSet->fields[0].'&';
                                echo '<tr><td>'.$recordSet->fields[5].'</td><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[3].'</td><td><a href="checks.php?apbill=1&endorser=&checknbr='.$recordSet->fields[0].'" target="_new">'.$recordSet->fields[2].'</a></td><td>'.CURRENCY_SYMBOL.checkdec($recordSet->fields[4],2).'</td>';
                                if ($recordSet->fields[7]==1) { //voided, show Y under void column
                                        echo '<td align="center">Y</td><td align="center">n/a';
                                } elseif ($recordSet->fields[6]=='0000-00-00') { //if cashed or voided, don't show checkboxes
                                        echo '<td align="center">N</td><td align="center">N';
                                } else {
                                        echo '<td align="center">n/a</td><td align="center">Y';
                                };
                                echo '</td></tr>';
                                if ($recordSet->fields[7]!=1) {
                                     $tot+=$recordSet->fields[4];
                                };
                                $recordSet->MoveNext();
                        };
                        echo '<tr><th colspan="4" align="right">'.$lang['STR_TOTAL_ALL_VALID_CHECKS'].':</th><th>'.CURRENCY_SYMBOL.checkdec($tot,PREFERRED_DECIMAL_PLACES).'</th></tr>';
                        echo '</table><input type="submit" name="new" value="'.$lang['STR_NEW_REPORT'].'"></form>';
                        echo '<a target="_new" href="checks.php?apbill=1&'.$allcheckstr.'">'.$lang['STR_PRINT_ALL_CHECKS'].'</a>';
                        unset($continue);
                };
        } else {
                echo '<form action="apchecklist.php" method="post" name="mainform"><table>';
                echo '<input type="hidden" name="print" value="1">';
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
                $timestamp =  time();
                $date_time_array =  getdate($timestamp);
                $hours =  $date_time_array["hours"];
                $minutes =  $date_time_array["minutes"];
                $seconds =  $date_time_array["seconds"];
                $month =  $date_time_array["mon"];
                $day =  $date_time_array["mday"];
                $year =  $date_time_array["year"];
                $timestamp =  mktime($hour, $minute, $second, $month, $day, $year);
                $today=date("Y-m-d", $timestamp);
                $timestamp =  mktime($hour, $minute, $second, $month-1, $day, $year);
                $monthago=date("Y-m-d", $timestamp);
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CASHED_QUESTION_MARK'].':</td><td><select name="cashed"><option value="" selected>All<option value="1">Show only cashed<option value="2">Show only non-cashed</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INCLUDE_VOIDS'].'</td><td><input type="checkbox" name="voids" value="1"></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ORDER'].':</td><td><select name="order"><option value="1" selected>'.$lang['STR_CHECKING_ACCOUNT_DATE_CHECK_NUMBER'].'<option value="2">'.$lang['STR_VENDOR_DATE_CHECK_NUMBER'].'<option value="3">'.$lang['STR_CHECK_NUMBER'].'</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_DATE_OF_CHECKS'].': </td><td><input type="text" name="bgdateofcheck"onchange="formatDate(this)" size="30" value="'.$monthago.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.bgdateofcheck\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_DATE_OF_CHECKS'].': </td><td><input type="text" name="eddateofcheck" onchange="formatDate(this)" size="30" value="'.$today.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.eddateofcheck\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '</table><br><input type="submit" name="continue" value="'.$lang['STR_CONTINUE'].'"></form>';
                
                echo '</center>';
        };
?>

<?php include_once("includes/footer.php"); ?>
