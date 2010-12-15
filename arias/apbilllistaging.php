<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>


<?
                echo '<center>';
                unset($str);
                if ($detail) {
                    echo texttitle($lang['STR_AP_DETAIL_AGING_REPORT']);
                } else {
                    echo texttitle($lang['STR_AP_AGING_REPORT']);
                };
                echo texttitle(createtime('Y-m-d'));
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
                $timestamp =  mktime($hour, $minute, $second, $month, $day-31, $year);
                $monthago=date("Y-m-d", $timestamp);
                $timestamp =  mktime($hour, $minute, $second, $month, $day-61, $year);
                $twomonthago=date("Y-m-d", $timestamp);
                $timestamp =  mktime($hour, $minute, $second, $month, $day-91, $year);
                $threemonthago=date("Y-m-d", $timestamp);
                if ($detail) {
                        $sumstr1='';
                        $sumstr2='';
                        $groupstr=' group by company.companyname,apbill.total,vendor.id,apbill.invoicenumber,apbill.dateofinvoice,apbill.id,apbill.description';
                        $wherestr=',apbill.invoicenumber,apbill.dateofinvoice,apbill.id,apbill.description';
                } else { //just show summary
                        $sumstr1='sum(';
                        $sumstr2=')';
                        $groupstr=' group by company.companyname,vendor.id,apbill.vendorid';
                        $wherestr='';
                };
                $recordSet = &$conn->Execute('select company.companyname,'.$sumstr1.'apbill.total'.$sumstr2.'-sum(apbillpayment.amount*(apbillpayment.checkvoid!=0)),vendor.id'.$wherestr.' from apbill cross join vendor cross join company left join apbillpayment on apbillpayment.apbillid=apbill.id where apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id and apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company).$groupstr.' order by company.companyname');
                if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_AGING_BILLS_FOUND']));
                echo '<table border="1"><tr><th rowspan="2">'.$lang['STR_VENDOR'].'</th>';
                if ($detail) echo '<th rowspan="2">'.$lang['STR_DESCRIPTION'].'</th><th rowspan="2">'.$lang['STR_INVOICE_NUMBER'].'</th><th rowspan="2">'.$lang['STR_DATE'].'</th>';
                echo '<th rowspan="2">'.$lang['STR_AMOUNT'].'</th><th rowspan="2">'.$lang['STR_DISCOUNT'].'</th><th colspan="4">'.$lang['STR_AGING_BY_INVOICE_DATE'].'</th></tr><tr><th>'.$lang['STR_CURRENT'].'</th><th>31-60</th><th>61-90</th><th>91+</th></tr>';
                while ($recordSet&&!$recordSet->EOF) {
                        $vendid=$recordSet->fields[2];
                        $discount=0;
                        $monthagototal=0;
                        $twomonthagototal=0;
                        $threemonthagototal=0;
                        $overthreemonthagototal='0';
                        if ($detail) $andstr=' and apbill.id='.sqlprep($recordSet->fields[5]);
                        $recordSet2 = &$conn->Execute('select sum(apbill.discountamount) from apbill where apbill.vendorid='.sqlprep($recordSet->fields[2]).' and apbill.discountdate>'.sqlprep($today).$andstr.' and apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company));
                        if ($recordSet2&&!$recordSet2->EOF) if ($recordSet2->fields[0]) $discount=$recordSet2->fields[0];
                        $recordSet2 = &$conn->Execute('select '.$sumstr1.'apbill.total'.$sumstr2.'-sum(apbillpayment.amount*(apbillpayment.checkvoid!=0)) from apbill left join apbillpayment on apbillpayment.apbillid=apbill.id where apbill.vendorid='.sqlprep($recordSet->fields[2]).$andstr.' and apbill.dateofinvoice>'.sqlprep($monthago).' and apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company));
                        if ($recordSet2&&!$recordSet2->EOF) if ($recordSet2->fields[0]) $monthagototal=$recordSet2->fields[0];
                        $recordSet2 = &$conn->Execute('select '.$sumstr1.'apbill.total'.$sumstr2.'-sum(apbillpayment.amount*(apbillpayment.checkvoid!=0)) from apbill left join apbillpayment on apbillpayment.apbillid=apbill.id where apbill.vendorid='.sqlprep($recordSet->fields[2]).$andstr.' and apbill.dateofinvoice>='.sqlprep($twomonthago).' and apbill.dateofinvoice<'.sqlprep($monthago).' and apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company));
                        if ($recordSet2&&!$recordSet2->EOF) if ($recordSet2->fields[0]) $twomonthagototal=$recordSet2->fields[0];
                        $recordSet2 = &$conn->Execute('select '.$sumstr1.'apbill.total'.$sumstr2.'-sum(apbillpayment.amount*(apbillpayment.checkvoid!=0)) from apbill left join apbillpayment on apbillpayment.apbillid=apbill.id where apbill.vendorid='.sqlprep($recordSet->fields[2]).$andstr.' and apbill.dateofinvoice>='.sqlprep($threemonthago).' and apbill.dateofinvoice<'.sqlprep($twomonthago).' and apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company));
                        if ($recordSet2&&!$recordSet2->EOF) if ($recordSet2->fields[0]) $threemonthagototal=$recordSet2->fields[0];
                        $recordSet2 = &$conn->Execute('select '.$sumstr1.'apbill.total'.$sumstr2.'-sum(apbillpayment.amount*(apbillpayment.checkvoid!=0)) from apbill left join apbillpayment on apbillpayment.apbillid=apbill.id where apbill.vendorid='.sqlprep($recordSet->fields[2]).$andstr.' and apbill.dateofinvoice<'.sqlprep($threemonthago).' and apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company));
                        if ($recordSet2&&!$recordSet2->EOF) if ($recordSet2->fields[0]) $overthreemonthagototal=$recordSet2->fields[0];
                        echo '<tr><td>'.$recordSet->fields[0].'</td>';
                        if ($detail) echo '<td>'.$recordSet->fields[6].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td>';
                        echo '<td align="right">'.CURRENCY_SYMBOL.checkdec($recordSet->fields[1],PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($discount,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($monthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($twomonthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($threemonthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($overthreemonthagototal,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                        $vtotalamount+=$recordSet->fields[1];
                        $vtotaldiscount+=$discount;
                        $vtotalmonthagototal+=$monthagototal;
                        $vtotaltwomonthagototal+=$twomonthagototal;
                        $vtotalthreemonthagototal+=$threemonthagototal;
                        $vtotaloverthreemonthagototal+=$overthreemonthagototal;
                        $totalamount+=$recordSet->fields[1];
                        $totaldiscount+=$discount;
                        $totalmonthagototal+=$monthagototal;
                        $totaltwomonthagototal+=$twomonthagototal;
                        $totalthreemonthagototal+=$threemonthagototal;
                        $totaloverthreemonthagototal+=$overthreemonthagototal;
                        $vendorname=$recordSet->fields[0];
                        $recordSet->MoveNext();
                        if ($detail) if ($recordSet->fields[2]<>$vendid||$recordSet->EOF) {
                                echo '<tr><th colspan="4" align="right">'.$vendorname.' '.$lang['STR_TOTAL'].':</th><th align="right">'.CURRENCY_SYMBOL.checkdec($vtotalamount,PREFERRED_DECIMAL_PLACES).'</th><th align="right">'.CURRENCY_SYMBOL.checkdec($vtotaldiscount,PREFERRED_DECIMAL_PLACES).'</th><th align="right">'.CURRENCY_SYMBOL.checkdec($vtotalmonthagototal,PREFERRED_DECIMAL_PLACES).'</th><th align="right">'.CURRENCY_SYMBOL.checkdec($vtotaltwomonthagototal,PREFERRED_DECIMAL_PLACES).'</th><th align="right">'.CURRENCY_SYMBOL.checkdec($vtotalthreemonthagototal,PREFERRED_DECIMAL_PLACES).'</th><th align="right">'.CURRENCY_SYMBOL.checkdec($vtotaloverthreemonthagototal,PREFERRED_DECIMAL_PLACES).'</th></tr>';
                                $vtotalamount=0;
                                $vtotaldiscount=0;
                                $vtotalmonthagototal=0;
                                $vtotaltwomonthagototal=0;
                                $vtotalthreemonthagototal=0;
                                $vtotaloverthreemonthagototal=0;
                        };
                };
                if ($detail) {
                        echo '<tr><td colspan="10">&nbsp;</td></tr><tr><td colspan="4">';
                } else {
                        echo '<tr><td colspan="7">&nbsp;</td></tr><tr><td>';
                };
                echo $lang['STR_TOTAL'].':</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totalamount,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totaldiscount,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totalmonthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totaltwomonthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totalthreemonthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totaloverthreemonthagototal,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                echo '</table>';
                echo '</center>';
?>

<?php include('includes/footer.php'); ?>
