<?php include('includes/main.php'); ?>

<script language="JavaScript">
     var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?php    echo '<center>';
        echo texttitle($lang['STR_AR_INVOICE_PAYMENT_HISTORY_REPORT']);
        echo texttitle(createtime('Y-m-d'));
        if ($submit) {
                unset($custstr);
                if ($customerid) $custstr=' and customer.id='.sqlprep($customerid);
                if ($bgdate) $bgstr=' and arinvoicepaymentdetail.datereceived>='.sqlprep($bgdate);
                if ($eddate) $edstr=' and arinvoicepaymentdetail.datereceived<='.sqlprep($eddate);
                if ($order) {
                    $orderstr=' order by arinvoicepaymentdetail.datereceived, company.companyname, gltransvoucher.voucher';
                } else {
                    $orderstr=' order by company.companyname, arinvoicepaymentdetail.datereceived, gltransvoucher.voucher ';
                };
                $recordSet = &$conn->Execute('select company.companyname,gltransvoucher.voucher,arinvoice.invoicenumber,arinvoicepaymentdetail.datereceived,sum(arinvoicepaymentdetail.amount),arinvoice.invoicetotal,sum(a.amount),arinvoicepaymentdetail.paymeth,arinvoicepaymentdetail.interest from customer cross join company cross join gltransvoucher cross join arinvoice cross join arinvoicepaymentdetail left join arinvoicepaymentdetail as a on a.datereceived>arinvoicepaymentdetail.datereceived and arinvoice.id=a.invoiceid where customer.companyid=company.id and company.id=arinvoice.orderbycompanyid and arinvoice.id=arinvoicepaymentdetail.invoiceid and arinvoicepaymentdetail.voucherid=gltransvoucher.id and arinvoice.cancel=0 and arinvoice.gencompanyid='.sqlprep($active_company).$custstr.$bgstr.$edstr.' group by arinvoice.id,company.companyname,gltransvoucher.voucher,arinvoice.invoicenumber,arinvoicepaymentdetail.datereceived,arinvoice.invoicetotal,arinvoicepaymentdetail.paymeth,arinvoicepaymentdetail.interest'.$orderstr);
                if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_INVOICE_PAYMENTS_FOUND']));
                echo '<table border="1"><tr><th>'.$lang['STR_CUSTOMER'].'</th><th>'.$lang['STR_VOUCHER'].'</th><th>'.$lang['STR_DATE'].'</th><th>'.$lang['STR_INVOICE_NUMBER'].'</th><th>'.$lang['STR_BEGIN'].' '.CURRENCY_SYMBOL.'</th><th>'.$lang['STR_AMOUNT'].' '.CURRENCY_SYMBOL.'</th><th>'.$lang['STR_INTEREST'].'</th><th>'.$lang['STR_METHOD'].'</th></tr>';
                while ($recordSet&&!$recordSet->EOF) {
                    if ($order) {
                        $new=$recordSet->fields[3];
                    } else {
                        $new=$recordSet->fields[0];
                    };
                    if (!$old==$new) {
                        if (isset($old)) {
                            echo '<tr><td colspan="5">'.$old.' '.$lang['STR_TOTAL'].':</td><td>'.CURRENCY_SYMBOL.num_format($atotal,PREFERRED_DECIMAL_PLACES).'</td><td colspan="2"></td></tr>';
                            $atotal=0;
                        };
                        $old=$new;
                    };
                    echo '<tr><td>'.$recordSet->fields[0].'</td><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[2].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[5]-$recordSet->fields[6],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[4],PREFERRED_DECIMAL_PLACES).'</td><td>'.checkequal($recordSet->fields[8],1,'Y').'</td><td>'.method($recordSet->fields[7]).'</td></tr>';
                    $atotal+=$recordSet->fields[4]+$recordSet->fields[8];
                    ${'paymethtot'.$recordSet->fields[7]}+=$recordSet->fields[4]+$recordSet->fields[8];
                    $recordSet->MoveNext();
                };
                echo '<tr><td colspan="5">'.$old.' '.$lang['STR_TOTAL'].':</td><td>'.CURRENCY_SYMBOL.num_format($atotal,PREFERRED_DECIMAL_PLACES).'</td><td colspan="2"></td></tr>';
                echo '<tr><td colspan="8">&nbsp;</td></tr>';
                for ($i=1; $i<=4; $i++) echo '<tr><td colspan="5">Total for all '.method($i).' Vouchers:</td><td>'.CURRENCY_SYMBOL.num_format(${'paymethtot'.$i},PREFERRED_DECIMAL_PLACES).'</td><td colspan="2"></td></tr>';
                echo '</table>';
        } else {
          $timestamp=time();
          $date_time_array=getdate($timestamp);
          $hours=$date_time_array["hours"];
          $minutes=$date_time_array["minutes"];
          $seconds=$date_time_array["seconds"];
          $month=$date_time_array["mon"];
          $day=$date_time_array["mday"];
          $year=$date_time_array["year"];
          $timestamp=mktime($hour, $minute, $second, $month, $day, $year);
          $eddate=date("Y-m-d", $timestamp);
          $timestamp=mktime($hour, $minute, $second, $month-1, $day, $year);
          $bgdate=date("Y-m-d", $timestamp);
          echo '<form action="arinvoicereppay.php" method="post" name="mainform"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_PERIOD'].':</td><td><input type="text" name="bgdate" onchange="formatDate(this)" value="'.$bgdate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.bgdate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_PERIOD'].':</td><td><input type="text" name="eddate" onchange="formatDate(this)" value="'.$eddate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.eddate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ORDER_BY'].':</td><td><select name="order"'.INC_TEXTBOX.'><option value="0">'.$lang['STR_CUSTOMER'].'<option value="1">'.$lang['STR_DATE'].'</select></td></tr>';
          echo '</table><br><input type="submit" name="submit" value="'.$lang['STR_CONTINUE'].'"></form>';

        };
        function method($paymeth) {
            switch ($paymeth) {
                case 1:
                     return $lang['STR_CASH'];
                     break;
                case 2:
                     return $lang['STR_CHECK'];
                     break;
                case 3:
                     return $lang['STR_CREDIT_CARD'];
                     break;
                case 4:
                     return $lang['STR_OTHER'];
                     break;
            };
        };
        
        echo '</center>';
?>

<?php include_once("includes/footer.php"); ?>
