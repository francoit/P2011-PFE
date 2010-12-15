<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php     echo '<center>';
        echo texttitle($lang['STR_AR_INVOICE_SALES_TAX_REPORT']);
        if ($bgdate&&$eddate&&$taxid) {
                echo texttitle($bgdate.' - '.$eddate);
                $recordSet = &$conn->Execute('select arinvoice.id, arinvoice.invoicenumber, arinvoice.entrydate, sum(arinvoicetaxdetail.taxamount), sum(arinvoicedetail.totalprice), sum(arid.totalprice), taxexempt.exemptname, taxexempt.id, salestax.taxname, arinvoice.shipcost from arinvoice,customer,company,salestax,arinvoicetaxdetail left join arinvoicedetail on arinvoice.id=arinvoicedetail.invoiceid and arinvoicedetail.taxflag=1 left join arinvoicedetail as arid on arinvoice.id=arid.invoiceid and arid.taxflag=0 left join taxexempt on customer.taxexemptid=taxexempt.id where arinvoice.entrydate>='.sqlprep($bgdate).' and arinvoice.entrydate<='.sqlprep($eddate).' and arinvoicetaxdetail.taxid='.sqlprep($taxid).' and salestax.id=arinvoicetaxdetail.taxid and arinvoicetaxdetail.invoiceid=arinvoice.id and customer.companyid=arinvoice.orderbycompanyid and company.id=arinvoice.orderbycompanyid group by arinvoice.id order by arinvoice.invoicenumber');
                if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_INVOICE_PAYMENTS_FOUND']));
                echo '<table border="1"><tr><th>'.$lang['STR_INVOICE_NUMBER'].'</th><th>'.$lang['STR_DATE'].'</th><th>'.$lang['STR_TAX'].'</th><th>'.$lang['STR_TAXABLE_DOLLARS'].'</th><th>'.$lang['STR_TAX_EXEMPT_DOLLARS'].'</th><th>'.$lang['STR_REASON'].'</th></tr>';
                while ($recordSet&&!$recordSet->EOF) {
                    echo '<tr><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[2].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[3],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[4],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[5]+$recordSet->fields[9],PREFERRED_DECIMAL_PLACES).'</td><td>'.$recordSet->fields[6].'</td></tr>';
                    $totalt+=$recordSet->fields[3]; //tax
                    $totald+=$recordSet->fields[4]; //total taxable dollars
                    $totale+=$recordSet->fields[5]; //total exempt
                    $totals+=$recordSet->fields[9]; //shipping
                    if ($recordSet->fields[7]) {
                        if (!in_array($recordSet->fields[7],$exmpt)) {
                            $exmpt[]=$recordSet->fields[7];
                            ${'name'.$recordSet->fields[7]}=$recordSet->fields[6];
                        };
                    };
                    ${'total'.$recordSet->fields[7]}=$recordSet->fields[5];
                    $recordSet->MoveNext();
                };
                echo '<tr><td colspan="2">'.$lang['STR_TOTAL_FOR'].' '.$recordSet->fields[8].':</td><td>'.CURRENCY_SYMBOL.num_format($totalt,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($totald,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($totale+$totals,PREFERRED_DECIMAL_PLACES).'</td><td></td></tr>';
                echo '</table><br>';
                foreach ($exmpt as $data) echo ''.$lang['STR_EXEMPT_SALES'].' - '.${'name'.$data}.': '.CURRENCY_SYMBOL.num_format(${'total'.$data},PREFERRED_DECIMAL_PLACES).'<br>';
                if ($totals) echo 'Exempt Shipping: '.CURRENCY_SYMBOL.num_format($totals,PREFERRED_DECIMAL_PLACES).'<br>';
        } else {
          echo texttitle(createtime('Y-m-d'));
          $timestamp=time();
          $date_time_array=getdate($timestamp);
          $hours=$date_time_array["hours"];
          $minutes=$date_time_array["minutes"];
          $seconds=$date_time_array["seconds"];
          $month=$date_time_array["mon"];
          $day=$date_time_array["mday"];
          $year=$date_time_array["year"];
          $timestamp=mktime($hour, $minute, $second, $month-1, 1, $year);
          $bgdate=date("Y-m-d", $timestamp);
          $timestamp=mktime($hour, $minute, $second, $month, 0, $year);
          $eddate=date("Y-m-d", $timestamp);
          echo '<form action="arinvoicereptax.php" method="post" name="mainform"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_PERIOD'].':</td><td><input type="text" name="bgdate" onchange="formatDate(this)" value="'.$bgdate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.bgdate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_PERIOD'].':</td><td><input type="text" name="eddate" onchange="formatDate(this)" value="'.$eddate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.eddate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          formarsalestaxselect('taxid');
          echo '</table><br><input type="submit" name="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
        };
           
          echo '</center>';
?>

<?php include_once("includes/footer.php"); ?>
