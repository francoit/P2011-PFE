<?php include('includes/main.php'); ?>
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
     echo texttitle($lang['STR_INVOICE_SUMMARY']);
     if ($report) { //show summary
          unset($openstr);
          if ($open) $openstr=' and arinvoice.status<2';
          unset($datestr);
          if ($bgdate) $datestr.=' and arinvoice.invoicedate>='.sqlprep($bgdate);
          if ($eddate) $datestr.=' and arinvoice.invoicedate<='.sqlprep($eddate);
          if ($order==1) { //invoice number
              $orderstr.=' order by arinvoice.invoicenumber';
          } elseif($order==2) { //customer
              $orderstr.=' order by company.companyname, arinvoice.invoicenumber';
          } elseif($order==3) { //salesman
              $orderstr.=' order by arinvoice.salesmanid, arinvoice.invoicenumber';
          };
                  $recordSet = $conn->Execute("select arinvoice.invoicenumber,count(arinvoicedetail.id),sum(arinvoicedetailcost.cost),sum(arinvoicedetail.totalprice),sum(arinvoicetaxdetail.taxamount),arinvoice.shipcost,arinvoice.invoicetotal,sum(arinvoicepaymentdetail.amount),arinvoice.duedate,arinvoice.status,company.companyname,salescomp.companyname,customer.id,salescomp.id from arinvoice cross join arinvoicedetail left join arinvoicedetailcost on arinvoice.id=arinvoicedetailcost.invoiceid left join arinvoicetaxdetail on arinvoice.id=arinvoicetaxdetail.invoiceid left join arinvoicepaymentdetail on arinvoicepaymentdetail.invoiceid=arinvoice.id left join company on company.id=arinvoice.orderbycompanyid left join salesman on arinvoice.salesmanid=salesman.id left join company as salescomp on salesman.companyid=salescomp.id left join customer on customer.companyid=arinvoice.orderbycompanyid where arinvoice.id=arinvoicedetail.invoiceid and arinvoice.cancel=0 and arinvoice.gencompanyid=".sqlprep($active_company).$openstr.$datestr." group by arinvoice.id,arinvoice.invoicenumber,arinvoice.shipcost,arinvoice.invoicetotal,arinvoice.duedate,arinvoice.status,company.companyname,salescomp.companyname,customer.id,salescomp.id".$orderstr);
                  if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_INVOICES_FOUND']));
                  echo '<table border="1">';
                  if ($order==3) {
                       echo '<tr><th colspan="12">'.$lang['STR_SALESMAN'].'</th></tr>';
                  } else {
                       echo '<tr><td></td><th colspan="11">'.$lang['STR_CUSTOMER'].'</th></tr>';
                  };
                  echo '<tr><th>'.$lang['STR_INVOICE_NUMBER'].'</th><th>'.$lang['STR_NUMBER_LINE_ITEMS'].'</th><th>'.$lang['STR_COST'].'</th><th>'.$lang['STR_SUBTOTAL'].'</th><th>'.$lang['STR_TAX'].'</th><th>'.$lang['STR_SHIPPING'].'</th><th>'.$lang['STR_TOTAL'].'</th><th>'.$lang['STR_PAID'].'</th><th>'.$lang['STR_BALANCE'].'</th><th>'.$lang['STR_PROFIT'].'</th><th>'.$lang['STR_DUE_DATE'].'</th><th>'.$lang['STR_STATUS'].'</th></tr>';
                  if ($report>1) {
                      echo '<tr><td colspan="2"></td><th colspan="3">'.$lang['STR_DESCRIPTION'].'</th><th colspan="2">'.$lang['STR_QTY'].'</th><th>'.$lang['STR_PRICE'].'</th><th>'.$lang['STR_SALES_GL_ACCOUNT'].'</th><th colspan="4"></th></tr>';
                      $recordSet2 = $conn->Execute("select arinvoice.invoicenumber,arinvoicedetail.description, arinvoicedetail.qty, unitname.unitname, arinvoicedetail.qty/arinvoicedetail.qtyunitperpriceunit*arinvoicedetail.priceach, glaccount.name, glaccount.description from arinvoice,arinvoicedetail left join unitname on unitname.id=arinvoicedetail.qtyunitnameid left join glaccount on glaccount.id=arinvoicedetail.glaccountid left join company on company.id=arinvoice.orderbycompanyid where arinvoice.id=arinvoicedetail.invoiceid and arinvoice.cancel=0 and arinvoice.gencompanyid=".sqlprep($active_company).$openstr.$datestr." group by arinvoice.id, arinvoicedetail.id".$orderstr);
                  };
                  if ($report>2) {
                      echo '<tr><th colspan="2"></th><th>'.$lang['STR_COST'].'</th><th colspan="4">'.$lang['STR_COST_GL_ACCOUNT'].'</th><th colspan="5"></th></tr>';
                      $recordSet3 = $conn->Execute("select arinvoice.invoicenumber,arinvoicedetailcost.cost, glaccount.name, glaccount.description from arinvoice,arinvoicedetailcost left join glaccount on arinvoicedetailcost.costglaccountid=glaccount.id left join company on company.id=arinvoice.orderbycompanyid where arinvoice.id=arinvoicedetailcost.invoiceid and arinvoice.cancel=0 and arinvoice.gencompanyid=".sqlprep($active_company).$openstr.$datestr." group by arinvoice.id, arinvoicedetailcost.id ".$orderstr.", arinvoicedetailcost.cost");
                  };
                  while (!$recordSet->EOF) {

                      if ($order==3&&($recordSet->fields[13]<>$oldsalesmanid||!$oldsalesmanid)) {
                          if ($firsttime==1&&$recordSet->fields[13]<>$oldsalesmanid) {
                              if ($salescost==0) {
                                  $properc='100.0';
                              } else {
                                  $properc=num_format(($salesst/$salescost)*100,1);
                              };
                              $oldsales=$oldsalesman;
                              if (trim($oldsales)=="") {
                                   $oldsales="??unknown??" ;
                              };
                              echo '<tr><td>'.$oldsales.' '.$lang['STR_TOTAL'].'</td><td>'.$saleslineitem.'</td><td>'.CURRENCY_SYMBOL.num_format($salescost,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salesst,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salestax,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salesship,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salesal,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salespd,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salesal-$salespd,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salesst-$salescost,PREFERRED_DECIMAL_PLACES).' ('.$properc.'%)</td><td colspan="2"></td></tr>';
                              unset($saleslineitem);
                              unset($salescost);
                              unset($salesst);
                              unset($salestax);
                              unset($salesship);
                              unset($salesal);
                              unset($salespd);
                          };
                          if ($recordSet->fields[13]<>$oldsalesmanid||!$firsttime) {
                              $sales=$recordSet->fields[11];
                              if (trim($sales)=="") $sales="??unknown??";
                              echo '<tr><th colspan="12">'.$sales.'</th></tr>';
                              $oldsalesman=$recordSet->fields[11];
                              $oldsalesmanid=$recordSet->fields[13];
                              $firsttime=1;
                          };
                      };
                      if ($order==3) {
                          $saleslineitem+=$recordSet->fields[1];
                          $salescost+=$recordSet->fields[2];
                          $salesst+=$recordSet->fields[3];
                          $salestax+=$recordSet->fields[4];
                          $salesship+=$recordSet->fields[5];
                          $salesal+=$recordSet->fields[6];
                          $salespd+=$recordSet->fields[7];
                      };
                      if ($order==2&&$recordSet->fields[12]<>$oldcustomerid) {
                          if (isset($oldcustomerid)) {
                              if ($custcost==0) {
                                  $properc='100.0';
                              } else {
                                  $properc=num_format(($custst/$custcost)*100,1);
                              };
                              $oldcust=$oldcustomer;
                              if (trim($oldcust)=="") $oldcust="??unknown??";
                              echo '<tr><td>'.$oldcust.' Total</td><td>'.$custlineitem.'</td><td>'.CURRENCY_SYMBOL.num_format($custcost,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custst,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custtax,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custship,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custal,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custpd,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custal-$custpd,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custst-$custcost,PREFERRED_DECIMAL_PLACES).' ('.$properc.'%)</td><td colspan="2"></td></tr>';
                              unset($custlineitem);
                              unset($custcost);
                              unset($custst);
                              unset($custtax);
                              unset($custship);
                              unset($custal);
                              unset($custpd);
                          };
                      };
                      if ($order==2) {
                          $custlineitem+=$recordSet->fields[1];
                          $custcost+=$recordSet->fields[2];
                          $custst+=$recordSet->fields[3];
                          $custtax+=$recordSet->fields[4];
                          $custship+=$recordSet->fields[5];
                          $custal+=$recordSet->fields[6];
                          $custpd+=$recordSet->fields[7];
                      };
                      if ($order<>3&&$recordSet->fields[12]<>$oldcustomerid) {
                          $custurl=$recordSet->fields[12];
                          $cust=$recordSet->fields[10];
                          if (trim($cust)=="") $cust="??unknown??";
                          echo '<tr><td></td><th colspan="11"><a href="arinvoicerepcust.php?customerid='.$custurl.'">'.$cust.'</a></th></tr>';
                          $oldcustomer=$recordSet->fields[10];
                          $oldcustomerid=$recordSet->fields[12];
                      };
                      if ($recordSet->fields[2]==0) {
                          $properc='100.0';
                      } else {
                          $properc=num_format(($recordSet->fields[3]/$recordSet->fields[2])*100,1);
                      };
                      echo '<tr><th><a href="arinvoiceupd.php?invoicenumber='.$recordSet->fields[0].'">'.$recordSet->fields[0].'</a></th><td>'.$recordSet->fields[1].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[2],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[3],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[4],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[5],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[6],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[7],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[6]-$recordSet->fields[7],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[3]-$recordSet->fields[2],PREFERRED_DECIMAL_PLACES).' ('.$properc.'%)</td><td>'.$recordSet->fields[8].'</td><td>'.shstatus($recordSet->fields[9]).'</td></tr>';
                      $totinv++;
                      $totlineitem+=$recordSet->fields[1];
                      $totcost+=$recordSet->fields[2];
                      $totst+=$recordSet->fields[3];
                      $tottax+=$recordSet->fields[4];
                      $totship+=$recordSet->fields[5];
                      $total+=$recordSet->fields[6];
                      $totpd+=$recordSet->fields[7];
                      if ($report>1) {
                          while (!$recordSet2->EOF&&$recordSet2&&$recordSet2->fields[0]==$recordSet->fields[0]) {
                              echo '<tr><td colspan="2"></td><td colspan="3">'.$recordSet2->fields[1].'</td><td>'.$recordSet2->fields[2].'</td><td>'.$recordSet2->fields[3].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet2->fields[4],PREFERRED_DECIMAL_PLACES).'</td><td>'.$recordSet2->fields[5].' - '.$recordSet2->fields[6].'</td><td colspan="4"></td></tr>';
                              $recordSet2->MoveNext();
                          };
                      };
                      if ($report>2) {
                          while (!$recordSet3->EOF&&$recordSet3&&$recordSet3->fields[0]==$recordSet->fields[0]) {
                              echo '<tr><td colspan="2"></td><td>'.CURRENCY_SYMBOL.num_format($recordSet3->fields[1],PREFERRED_DECIMAL_PLACES).'</td><td colspan="4">'.$recordSet3->fields[2].' - '.$recordSet3->fields[3].'</td><td colspan="5"></td></tr>';
                              $recordSet3->MoveNext();
                          };
                      };
                      $recordSet->MoveNext();
                  };
                  if ($order==3) {
                      if ($salescost==0) {
                          $properc='100.0';
                      } else {
                          $properc=num_format(($salesst/$salescost)*100,1);
                      };
                      $oldsales=$oldsalesman;
                      if (trim($oldsales)=="") {
                           $oldsales="??unknown??" ;
                      };

                      echo '<tr><td>'.$oldsales.' '.$lang['STR_TOTAL'].'</td><td>'.$saleslineitem.'</td><td>'.CURRENCY_SYMBOL.num_format($salescost,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salesst,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salestax,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salesship,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salesal,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salespd,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salesal-$salespd,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($salesst-$salescost,PREFERRED_DECIMAL_PLACES).' ('.$properc.'%)</td><td colspan="2"></td></tr>';
                  };
                  if ($order==2) {
                      if ($custcost==0) {
                          $properc='100.0';
                      } else {
                          $properc=num_format(($custst/$custcost)*100,1);
                      };
                      $oldcust=$oldcustomer;
                      if (trim($oldcust)=="") {
                           $oldcust="??unknown??" ;
                      };
                      echo '<tr><td>'.$oldcust.' '.$lang['STR_TOTAL'].'</td><td>'.$custlineitem.'</td><td>'.CURRENCY_SYMBOL.num_format($custcost,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custst,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custtax,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custship,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custal,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custpd,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custal-$custpd,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($custst-$custcost,PREFERRED_DECIMAL_PLACES).' ('.$properc.'%)</td><td colspan="2"></td></tr>';
                  };
                  echo '<tr><td colspan="13">&nbsp;</td></tr>';
                  if ($totcost==0) {
                      $properc='100.0';
                  } else {
                      $properc=num_format(($totst/$totcost)*100,1);
                  };
                  echo '<tr><td>Totals: '.$totinv.'</td><td>'.$totlineitem.'</td><td>'.CURRENCY_SYMBOL.num_format($totcost,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($totst,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($tottax,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($totship,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($total,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($totpd,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($total-$totpd,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.num_format($totst-$totcost,PREFERRED_DECIMAL_PLACES).' ('.$properc.'%)</td><td colspan="2"></td></tr>';
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
          $timestamp =  mktime($hour, $minute, $second, $month, $day, $year);
          $eddate=date("Y-m-d", $timestamp);
          $timestamp =  mktime($hour, $minute, $second, $month-1, $day, $year);
          $bgdate=date("Y-m-d", $timestamp);
          echo '<form action="arinvoicesum.php" method="post" name="mainform"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_PERIOD'].':</td><td><input type="text" name="bgdate" onchange="formatDate(this)" value="'.$bgdate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.bgdate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_PERIOD'].':</td><td><input type="text" name="eddate" onchange="formatDate(this)" value="'.$eddate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.eddate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHOW_INVOICES'].':</td><td><select name="open"'.INC_TEXTBOX.'><option value="0">'.$lang['STR_ALL'].'<option value="1">'.$lang['STR_OPEN'].'</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_REPORT'].':</td><td><select name="report"'.INC_TEXTBOX.'><option value="1">'.$lang['STR_SUMMARY_TOTALS_BY_INVOICE'].'<option value="2">'.$lang['STR_LINE_ITEM_DETAIL'].'<option value="3">'.$lang['STR_GL_ACCOUNTS_AMOUNT_DETAIL'].'</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ORDER_BY'].':</td><td><select name="order"'.INC_TEXTBOX.'><option value="1">'.$lang['STR_INVOICE_NUMBER'].'<option value="2">'.$lang['STR_CUSTOMER'].'<option value="3">'.$lang['STR_SALES_PERSON'].'</select></td></tr>';
//          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHOW_COST_ANALYSIS'].':</td><td><input type="checkbox" name="cost" value="1"'.INC_TEXTBOX.'></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
     };
          function shstatus($stat) {
              switch ($stat) {
                  case 0:
                       return 'Unposted';
                       break;
                  case 1:
                       return 'Unpaid';
                       break;
                  case 2:
                       return 'Paid';
                       break;
              };
          };

          
echo '</center>';

?>

<?php include_once("includes/footer.php"); ?>
