<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>


<script language="JavaScript">
      var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?
        echo '<center>';
        if ($detail==1) $str='Detail ';
        echo texttitle($lang['STR_AP'] .$str. $lang['STR_DISCOUNT_AGING_REPORT']);
        if ($discountdate) {
                echo texttitle($discountdate);
                $timestamp =  time();
                $date_time_array =  getdate($timestamp);
                $hours =  $date_time_array["hours"];
                $minutes =  $date_time_array["minutes"];
                $seconds =  $date_time_array["seconds"];
                $month =  $date_time_array["mon"];
                $day =  $date_time_array["mday"];
                $year =  $date_time_array["year"];
                $timestamp =  mktime($hours, $minutes, $seconds, $month, $day, $year);
                $today=date("Y-m-d", $timestamp);
                $timestamp =  mktime($hours, $minutes, $seconds, $month, $day-31, $year);
                $monthago=date("Y-m-d", $timestamp);
                $timestamp =  mktime($hours, $minutes, $seconds, $month, $day-61, $year);
                $twomonthago=date("Y-m-d", $timestamp);
                $timestamp =  mktime($hours, $minutes, $seconds, $month, $day-91, $year);
                $threemonthago=date("Y-m-d", $timestamp);
                if ($detail) {
                        $sumstr1='';
                        $sumstr2='';
                        $groupstr=' group by apbill.id';
                        $wherestr=',apbill.description';
                } else { //just show summary
                        $sumstr1='sum(';
                        $sumstr2=')';
                        $groupstr=' group by apbill.vendorid';
                };
                $recordSet = &$conn->Execute('select company.companyname,'.$sumstr1.'apbill.total'.$sumstr2.'-sum(apbillpayment.amount*(apbillpayment.checkvoid!=0)),'.$sumstr1.'apbill.discountamount'.$sumstr2.',vendor.id,apbill.invoicenumber,apbill.dateofinvoice,apbill.discountdate,apbill.id '.$wherestr.' from apbill,vendor,company left join apbillpayment on apbillpayment.apbillid=apbill.id where apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id  and apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company).$groupstr.' order by company.companyname');
                if ($recordSet->EOF) die(texterror($lang['STR_NO_AGING_BILLS_FOUND']));
                echo '<table border="1"><tr><th rowspan="2">'.$lang['STR_VENDOR'].'</th>';
                if ($detail) echo '<th rowspan="2">'.$lang['STR_DESCIPTION'].'</th><th rowspan="2">'.$lang['STR_INVOICE_NUMBER'].'</th><th rowspan="2" nowrap>Discount<br>Date</th>';
                echo '<th rowspan="2">'.$lang['STR_TOTAL'].'</th><th rowspan="2">'.$lang['STR_DISCOUNT'].'</th><td colspan="2" align="center">'.$lang['STR_AGING_BY'].$discountdate.'</td></tr><tr><th>'.$lang['STR_WITHIN_DISCOUNT_PERIOD'].'</th><th>'.$lang['STR_BEYOND_DISCOUNT_PERIOD'].'</th></tr>';
                while (!$recordSet->EOF) {
                        $vendid=$recordSet->fields[3];
                        if ($detail) $andstr=' and apbill.id='.sqlprep($recordSet->fields[8]);
                        $discount=0;
                        $nodiscount=0;
                        $billdiscountdate=$recordSet->fields[6];
                        $recordSet2 = &$conn->Execute('select apbill.discountamount from apbill where  apbill.id='.sqlprep($recordSet->fields[7]).' and  apbill.discountdate>'.sqlprep($today).' and apbill.discountdate<='.sqlprep($discountdate).' and apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company));
                        if (!$recordSet2->EOF) if ($recordSet2->fields[0]) $discount=$recordSet2->fields[0];
                        $recordSet2 = &$conn->Execute('select apbill.discountamount from apbill where apbill.id='.sqlprep($recordSet->fields[7]).' and  apbill.discountdate<='.sqlprep($discountdate).' and apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company));
                        if (!$recordSet2->EOF) if ($recordSet2->fields[0]) $nodiscount=$recordSet2->fields[0];
                        echo '<tr><td>'.$recordSet->fields[0].'</td>';
                        if ($detail) {
                             echo '<td>'.$recordSet->fields[8].'</td>';
                             echo '<td nowrap>'.$recordSet->fields[4].'</td>';
                             echo '<td nowrap>'.$recordSet->fields[6].'</td>';
                        };
                        echo '<td align="right">'.CURRENCY_SYMBOL.checkdec($recordSet->fields[1],PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($recordSet->fields[2],PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($discount,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($nodiscount,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                        $vtotalamount+=$recordSet->fields[1];
                        $vtotaldis+=$recordSet->fields[2];
                        $vtotaldiscount+=$discount;
                        $vtotalnodiscount+=$nodiscount;
                        $totalamount+=$recordSet->fields[1];
                        $totaldis+=$recordSet->fields[2];
                        $totaldiscount+=$discount;
                        $totalnodiscount+=$nodiscount;
                        $vendorname=$recordSet->fields[0];
                        $recordSet->MoveNext();
                        if ($detail) if ($recordSet->fields[3]<>$vendid||$recordSet->EOF) {
                                echo '<tr><th colspan="4" align="right">'.$lang['STR_TOTAL_FOR'].' '.$vendorname.':</th><th align="right">'.CURRENCY_SYMBOL.checkdec($vtotalamount,PREFERRED_DECIMAL_PLACES).'</th><th align="right">'.CURRENCY_SYMBOL.checkdec($vtotaldis,PREFERRED_DECIMAL_PLACES).'</th><th align="right">'.CURRENCY_SYMBOL.checkdec($vtotaldiscount,PREFERRED_DECIMAL_PLACES).'</th><th align="right">'.CURRENCY_SYMBOL.checkdec($vtotalnodiscount,PREFERRED_DECIMAL_PLACES).'</th></tr>';
                                $vtotalamount=0;
                                $vtotaldis=0;
                                $vtotaldiscount=0;
                                $vtotalnodiscount=0;
                        };
                };
                if ($detail) {
                        echo '<tr><td colspan="8">&nbsp;</td></tr><tr><td colspan="4">';
                } else {
                        echo '<tr><td colspan="5">&nbsp;</td></tr><tr><td colspan="1">';
                };

                echo ''.$lang['STR_TOTAL'].':</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totalamount,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totaldis,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totaldiscount,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totalnodiscount,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                echo '</table>';
        } else {
                $timestamp =  time();
                $date_time_array =  getdate($timestamp);
                $hours =  $date_time_array["hours"];
                $minutes =  $date_time_array["minutes"];
                $seconds =  $date_time_array["seconds"];
                $month =  $date_time_array["mon"];
                $day =  $date_time_array["mday"];
                $year =  $date_time_array["year"];
                $timestamp =  mktime($hour, $minute, $second, $month, $day+7, $year);
                $weeknow=date("Y-m-d", $timestamp);
                echo '<form action="apbilllistdiscaging.php" method="post" name="mainform"><table>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_CUTOFF_DATE'].': </td><td><input type="text" name="discountdate" onchange="formatDate(this)" size="30" value="'.$weeknow.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.discountdate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHOW_DETAILS'].': </td><td><input type="checkbox" name="detail" value="1"'.INC_TEXTBOX.'></td></tr>';
                echo '</table><br><input type="submit" name="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
                echo '<br><br><a href="javascript:doNothing()" onclick="top.newWin = window.open (\'helpapbilllistdiscaging.php?name='.$name.'\',\'cal\',\'dependent=yes,width=420,height=460,screenX=200,screenY=300,titlebar=yes,scrollbars=1,resizable=1\')"><img src="images/opera.jpg" width="40" border="0" alt="AP Discount Aging Report Help"></a>';
                echo '</center>';
         };
?>

<?php include('includes/footer.php'); ?>
