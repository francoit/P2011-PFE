<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php   echo '<center>';
     echo texttitle($lang['STR_CUSTOMER_ACCOUNT']);
     if ($customerid) { //show summary
                  $recordSet = $conn->Execute("select fiscalbeginmonth from glcompany where id=".sqlprep($active_company));
                  if (!$recordSet||$recordSet->EOF) {
                      $fiscalbeginmonth=1;
                  } else {
                      $fiscalbeginmonth=$recordSet->fields[0];
                  };
                  $recordSet = $conn->SelectLimit("select company.companyname, company.address1, company.address2, company.city, company.state, company.zip, customer.creditlimit, company.id from company,customer where customer.companyid=company.id and customer.gencompanyid=".sqlprep($active_company)." and customer.id=".sqlprep($customerid),1);
                  if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_CUSTOMER_FOUND']));
                  $companyid=$recordSet->fields[7];
                  $recordSet2 = $conn->Execute("select sum(arinvoice.invoicetotal), sum(arinvoicepaymentdetail.amount) from arinvoice left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where arinvoice.invoicedate>=".sqlprep(createtime('Y').'-'.$fiscalbeginmonth.'-01')." and arinvoice.orderbycompanyid=".sqlprep($companyid).' and arinvoice.status>=0');
                  $billedytd=$recordSet2->fields[0];
                  $paidytd=$recordSet2->fields[1];
                  echo '<table width="100%"><tr><td>'.$recordSet->fields[0].'<br>';
                  if ($recordSet->fields[1]) echo $recordSet->fields[1].'<br>';
                  if ($recordSet->fields[2]) echo $recordSet->fields[2].'<br>';
                  if ($recordSet->fields[3]||$recordSet->fields[4]||$recordSet->fields[5]) echo $recordSet->fields[3].', '.$recordSet->fields[4].' '.$recordSet->fields[5].'<br>';
                  if ($recordSet->fields[6]) echo ''.$lang['STR_CREDIT_CARD'].': '.CURRENCY_SYMBOL.num_format($recordSet->fields[6],PREFERRED_DECIMAL_PLACES).'<br>';
                  echo '</td><td>';
                  echo ''.$lang['STR_BILLED_YTD'].': '.CURRENCY_SYMBOL.num_format($recordSet2->fields[0],PREFERRED_DECIMAL_PLACES).'<br>';
                  echo ''.$lang['STR_PAID_YTD'].': '.CURRENCY_SYMBOL.num_format($recordSet2->fields[1],PREFERRED_DECIMAL_PLACES);
                  echo '</td></tr></table>';
                  $recordSet = $conn->Execute("select arinvoice.invoicenumber, arinvoice.invoicedate, arinvoice.duedate, arinvoice.invoicetotal-sum(arinvoicepaymentdetail.amount) from arinvoice left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where arinvoice.orderbycompanyid=".sqlprep($companyid)." and arinvoice.status>=0 group by arinvoice.invoicenumber,arinvoice.invoicedate,arinvoice.duedate,arinvoice.invoicetotal,arinvoice.id");
                  if ($recordSet&&!$recordSet->EOF) {
                      echo '<table border="1"><tr><th>'.$lang['STR_INVOICE_NUMBER'].'</th><th>'.$lang['STR_INVOICE_DATE'].'</th><th>'.$lang['STR_DUE_DATE'].'</th><th>'.$lang['STR_PRINCIPAL_DUE'].'</th></tr>';
                      while (!$recordSet->EOF) {
                          echo '<tr><td><a href="arinvoiceupd.php?invoicenumber='.$recordSet->fields[0].'">'.$recordSet->fields[0].'</a></td><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[2].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[3],PREFERRED_DECIMAL_PLACES).'</td></tr>';
                          $total+=$recordSet->fields[3];
                          $recordSet->MoveNext();
                      };
                      echo '<tr><td colspan="3" align="right">'.$lang['STR_PRINCIPAL'].':</td><td>'.CURRENCY_SYMBOL.num_format($total,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                      echo '</table>';
                  };

     } else {
          echo '<form action="arinvoicerepcust.php" method="post" name="mainform"><table>';
          formarcustomerselect('customerid');
          echo '</table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
     };
     
          echo '</center>';
?>

<?php include_once("includes/footer.php"); ?>
