<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?
                echo '<center>';
                unset($str);
                if ($detail) $str='Detail ';
                echo texttitle($lang['STR_AR_INVOICE_DETAIL_AGING_REPORT']  .$companyname);
                echo texttitle(createtime('Y-m-d'));
                $timestamp=time();
                $date_time_array=getdate($timestamp);
                $hours=$date_time_array["hours"];
                $minutes=$date_time_array["minutes"];
                $seconds=$date_time_array["seconds"];
                $month=$date_time_array["mon"];
                $day=$date_time_array["mday"];
                $year=$date_time_array["year"];
                $timestamp=mktime($hour, $minute, $second, $month, $day, $year);
                $today=date("Y-m-d", $timestamp);
                $timestamp=mktime($hour, $minute, $second, $month, $day-31, $year);
                $monthago=date("Y-m-d", $timestamp);
                $timestamp=mktime($hour, $minute, $second, $month, $day-61, $year);
                $twomonthago=date("Y-m-d", $timestamp);
                $timestamp=mktime($hour, $minute, $second, $month, $day-91, $year);
                $threemonthago=date("Y-m-d", $timestamp);
//              if ($detail) {
                        $sumstr1='';
                        $sumstr2='';
                        $groupstr=' group by company.companyname,arinvoice.invoicetotal,arinvoice.orderbycompanyid,arinvoice.invoicenumber,arinvoice.invoicedate,arinvoice.id';
                        $wherestr=',arinvoice.invoicenumber,arinvoice.invoicedate,arinvoice.id';
                        $groupstr2=' group by arinvoice.invoicetotal';
//              } else { //just show summary
//                      $sumstr1='sum(';
//                      $sumstr2=')';
//                      $groupstr=' group by company.companyname,arinvoice.orderbycompanyid';
//                      $wherestr='';
//                      $groupstr2='';
//              };
                $recordSet = &$conn->Execute('select company.companyname,'.$sumstr1.'arinvoice.invoicetotal'.$sumstr2.',sum(arinvoicepaymentdetail.amount),arinvoice.orderbycompanyid'.$wherestr.' from arinvoice left join company on arinvoice.orderbycompanyid=company.id left join arinvoicepaymentdetail on arinvoicepaymentdetail.invoiceid=arinvoice.id where arinvoice.cancel=0 and arinvoice.status>=0 and arinvoice.status<2 and arinvoice.gencompanyid='.sqlprep($active_company).$groupstr.' order by company.companyname');
                if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_AGING_INVOICES_FOUND']));
                echo '<table border="1"><tr><th rowspan="2">'.$lang['STR_CUSTOMER'].'</th>';
                if ($detail) echo '<th rowspan="2">'.$lang['STR_INVOICE_NUMBER'].'</th><th rowspan="2">'.$lang['STR_DATE'].'</th>';
                echo '<th rowspan="2">'.$lang['STR_AMOUNT'].'</th><th rowspan="2">'.$lang['STR_DISCOUNT'].'</th><th colspan="4">'.$lang['STR_AGING_BY_INVOICE_DATE'].'</th></tr><tr><th>'.$lang['STR_CURRENT'].'</th><th>31-60</th><th>61-90</th><th>91+</th></tr>';
                while ($recordSet&&!$recordSet->EOF) {
                        $companyname=$recordSet->fields[0];
                        $companyid=$recordSet->fields[3];
                        $amount=$recordSet->fields[1];
                        if ($recordSet->fields[2]) $amount-=$recordSet->fields[2];
                        $discount=0;
                        $monthagototal=0;
                        $twomonthagototal=0;
                        $threemonthagototal=0;
                        $overthreemonthagototal=0;
//                      if ($detail)
                        $andstr=' and arinvoice.id='.sqlprep($recordSet->fields[6]);
                        $recordSet2 = &$conn->Execute('select sum(arinvoice.discountamount) from arinvoice where arinvoice.orderbycompanyid='.sqlprep($companyid).' and arinvoice.discountdate>'.sqlprep($today).$andstr.' and arinvoice.cancel=0 and arinvoice.status>=0 and arinvoice.status<2 and arinvoice.gencompanyid='.sqlprep($active_company));
                        if ($recordSet2&&!$recordSet2->EOF) if ($recordSet2->fields[0]) $discount=$recordSet2->fields[0];
                        $recordSet2 = &$conn->Execute('select '.$sumstr1.'arinvoice.invoicetotal'.$sumstr2.',sum(arinvoicepaymentdetail.amount) from arinvoice left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where arinvoice.orderbycompanyid='.sqlprep($companyid).$andstr.' and arinvoice.invoicedate>='.sqlprep($monthago).' and arinvoice.cancel=0 and arinvoice.status>=0 and arinvoice.status<2 and arinvoice.gencompanyid='.sqlprep($active_company).$groupstr2);
                        if (!$recordSet2) die(texterror($lang['STR_INVOICE_NOT_FOUND']));
                        if (!$recordSet2->EOF&&$recordSet2->fields[0]) {
                                $monthagototal=$recordSet2->fields[0];
                                if ($recordSet2->fields[1]) $monthagototal-=$recordSet2->fields[1];
                        };
                        $recordSet2 = &$conn->Execute('select '.$sumstr1.'arinvoice.invoicetotal'.$sumstr2.',sum(arinvoicepaymentdetail.amount) from arinvoice left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where arinvoice.orderbycompanyid='.sqlprep($companyid).$andstr.' and arinvoice.invoicedate>='.sqlprep($twomonthago).' and arinvoice.invoicedate<'.sqlprep($monthago).' and arinvoice.cancel=0 and arinvoice.status>=0 and arinvoice.status<2 and arinvoice.gencompanyid='.sqlprep($active_company).$groupstr2);
                        if (!$recordSet2) die(texterror($lang['STR_INVOICE_NOT_FOUND']));
                        if (!$recordSet2->EOF&&$recordSet2->fields[0]) {
                                $twomonthagototal=$recordSet2->fields[0];
                                if ($recordSet2->fields[1]) $twomonthagototal-=$recordSet2->fields[1];
                        };
                        $recordSet2 = &$conn->Execute('select '.$sumstr1.'arinvoice.invoicetotal'.$sumstr2.',sum(arinvoicepaymentdetail.amount) from arinvoice left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where arinvoice.orderbycompanyid='.sqlprep($companyid).$andstr.' and arinvoice.invoicedate>='.sqlprep($threemonthago).' and arinvoice.invoicedate<'.sqlprep($twomonthago).' and arinvoice.cancel=0 and arinvoice.status>=0 and arinvoice.status<2 and arinvoice.gencompanyid='.sqlprep($active_company).$groupstr2);
                        if (!$recordSet2) die(texterror($lang['STR_INVOICE_NOT_FOUND']));
                        if (!$recordSet2->EOF&&$recordSet2->fields[0]) {
                                $threemonthagototal=$recordSet2->fields[0];
                                if ($recordSet2->fields[1]) $threemonthagototal-=$recordSet2->fields[1];
                        };
                        $recordSet2 = &$conn->Execute('select '.$sumstr1.'arinvoice.invoicetotal'.$sumstr2.',sum(arinvoicepaymentdetail.amount) from arinvoice left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where arinvoice.orderbycompanyid='.sqlprep($companyid).$andstr.' and arinvoice.invoicedate<'.sqlprep($threemonthago).' and arinvoice.cancel=0 and arinvoice.status>=0 and arinvoice.status<2 and arinvoice.gencompanyid='.sqlprep($active_company).$groupstr2);
                        if (!$recordSet2) die(texterror($lang['STR_INVOICE_NOT_FOUND']));
                        if (!$recordSet2->EOF&&$recordSet2->fields[0]) {
                                $overthreemonthagototal=$recordSet2->fields[0];
                                if ($recordSet2->fields[1]) $overthreemonthagototal-=$recordSet2->fields[1];
                        };
                        if ($detail) {
                                echo '<tr><td>'.$companyname.'</td><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[5].'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($amount,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($discount,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($monthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($twomonthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($threemonthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($overthreemonthagototal,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                        };
                        $vtotalamount+=$amount;
                        $vtotaldiscount+=$discount;
                        $vtotalmonthagototal+=$monthagototal;
                        $vtotaltwomonthagototal+=$twomonthagototal;
                        $vtotalthreemonthagototal+=$threemonthagototal;
                        $vtotaloverthreemonthagototal+=$overthreemonthagototal;
                        $totalamount+=$amount;
                        $totaldiscount+=$discount;
                        $totalmonthagototal+=$monthagototal;
                        $totaltwomonthagototal+=$twomonthagototal;
                        $totalthreemonthagototal+=$threemonthagototal;
                        $totaloverthreemonthagototal+=$overthreemonthagototal;
                        $recordSet->MoveNext();
                        if ($recordSet->fields[3]<>$companyid||$recordSet->EOF) {
                                if ($detail) {
                                        echo '<tr><td colspan="3">&nbsp;'.$lang['STR_CURRENT_TOTAL'].':</td>';
                                } else {
                                        echo '<tr><td>'.$companyname.'</td>';
                                };
                                echo '<td align="right">'.CURRENCY_SYMBOL.checkdec($vtotalamount,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($vtotaldiscount,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($vtotalmonthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($vtotaltwomonthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($vtotalthreemonthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($vtotaloverthreemonthagototal,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                                if ($detail) {
                                        echo '<tr><td colspan="9">&nbsp;</td></tr>';
                                };
                                $vtotalamount=0;
                                $vtotaldiscount=0;
                                $vtotalmonthagototal=0;
                                $vtotaltwomonthagototal=0;
                                $vtotalthreemonthagototal=0;
                                $vtotaloverthreemonthagototal=0;
                        };
                };
                if ($detail) {
                        echo '<tr><td colspan="3">';
                } else {
                        echo '<tr><td colspan="7">&nbsp;</td></tr><tr><td>';
                };
                echo ''.$lang['STR_TOTAL'].':</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totalamount,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totaldiscount,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totalmonthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totaltwomonthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totalthreemonthagototal,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($totaloverthreemonthagototal,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                echo '</table>';
echo '</center>';
?>

<?php include_once("includes/footer.php"); ?>
