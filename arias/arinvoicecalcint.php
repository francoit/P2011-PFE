<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php //arinvoicecalcint.php
   if ($submit) {
         for ($i=1; ${'invoiceid'.$i}; $i++) {
             checkpermissions('ar');
             $recordSet = &$conn->Execute('update arinvoice set arinvoice.accruedinterest=arinvoice.accruedinterest+'.sqlprep(${'interest'.$i}).' where id='.sqlprep(${'invoiceid'.$i}));
         };
         echo textsuccess($lang['STR_INTEREST_APPLIED_SUCCESSFULLY']);
   };
                echo texttitle($lang['STR_AR_CALCULATE_INTEREST'] - $companyname);
                echo texttitle(createtime('Y-m-d'));
                $recordSet = &$conn->Execute('select arinvoice.id, arinvoice.invoicenumber, company.companyname, arinvoice.invoicetotal, sum(arinvoicepaymentdetail.amount), arinvoice.invoicedate, arinvoice.duedate, arinvoice.accruedinterest, floor((UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(arinvoice.invoicedate))/2592000)*(arcompany.interestrate/100*(arinvoice.invoicetotal-sum(arinvoicepaymentdetail.amount)))+arcompany.servicecharge-arinvoice.accruedinterest as ainterest from arinvoice,company,customer,arcompany left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where arinvoice.orderbycompanyid=company.id and customer.companyid=arinvoice.orderbycompanyid and arinvoice.cancel=0 and arinvoice.status<2 and arinvoice.status>=0 and customer.interest=1 and arinvoice.duedate<NOW() and date_format(date_add(arinvoice.invoicedate, INTERVAL 30 DAY), \'%Y-%m-%d\')<=NOW() and arcompany.id='.sqlprep($active_company).' and arinvoice.datelastinterestcalc<NOW() group by arinvoicepaymentdetail.invoiceid having ainterest>0 order by arinvoice.invoicenumber');
                if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_INVOICES_FOUND']));
                echo '<form method="post" action="arinvoicecalcint.php" name="mainform">';
                echo '<table border="1"><tr><th>'.$lang['STR_INVOICE'].'</th><th>'.$lang['STR_CUSTOMER'].'</th><th>'.$lang['STR_INVOICE_TOTAL'].' $</th><th>'.$lang['STR_PAYMENTS'].'</th><th>'.$lang['STR_INVOICE_DATE'].'</th><th>'.$lang['STR_DUE_DATE'].'</th><th>'.$lang['STR_CURRENT_INTEREST'].'</th><th>'.$lang['STR_NEW_INTEREST'].'</th><th>'.$lang['STR_TOTAL_INTEREST'].'</th></tr>';
                $i=1;
                while ($recordSet&&!$recordSet->EOF) {
                    echo '<input type="hidden" name="invoiceid'.$i.'" value="'.$recordSet->fields[0].'">';
                    echo '<input type="hidden" name="interest'.$i.'" value="'.num_format($recordSet->fields[8],PREFERRED_DECIMAL_PLACES).'">';
                    echo '<tr><td><a href="arinvoiceupd.php?invoicenumber='.$recordSet->fields[1].'">'.$recordSet->fields[1].'</a></td><td>'.$recordSet->fields[2].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[3],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[4],PREFERRED_DECIMAL_PLACES).'</td><td>'.$recordSet->fields[5].'</td><td>'.$recordSet->fields[6].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[7],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[8],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[7]+$recordSet->fields[8],PREFERRED_DECIMAL_PLACES).'</td></tr>';
                    $totalc+=$recordSet->fields[7];
                    $totaln+=$recordSet->fields[8];
                    $i++;
                    $recordSet->MoveNext();
                };
                echo '<tr><td colspan="6" align="right">'.$lang['STR_TOTAL_INTEREST'].':</td><td>'.CURRENCY_SYMBOL.num_format($totalc,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($totaln,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($totaln+$totalc,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                echo '</table>';
                echo '<input type="submit" value="'.$lang['STR_APPLY_INTEREST'].'" name="submit"></form>';
?>

<?php include_once("includes/footer.php"); ?>
