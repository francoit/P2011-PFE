<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?php include('includes/invfunctions.php'); ?>
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
     echo texttitle($lang['STR_INVOICE_ADD']);
     if ($invoicenumber||$customerid) {
         $titlestr='<font size="-1">';
         if ($customerid) {
             $recordSet = &$conn->Execute('select company.companyname,customer.salesmanid,customer.invoicetermsid from company,customer where company.id=customer.companyid and customer.id='.sqlprep($customerid));
             if ($recordSet&&!$recordSet->EOF) {
                  $titlestr.=$recordSet->fields[0];
                  if (!$salesmanid) $salesmanid=$recordSet->fields[1];
                  if (!$invoicetermsid) $invoicetermsid=$recordSet->fields[2];
             };
         };
         if ($invoicenumber) {
             if ($customerid) $titlestr.=' - ';
             $titlestr.=' '.$lang['STR_INVOICE_NUMBER'].' '.$invoicenumber;
         };
         $titlestr.='</font>';
         echo texttitle($titlestr);
     };
     if ($customerid&&$invoicenumber&&!$invoiceid) {
          checkpermissions('ar');
          if ($conn->Execute('insert into arinvoice (invoicenumber,ponumber,wherefrom,orderbycompanyid,shiptocompanyid,status,customerbillcode,invoicetermsid,salesmanid,datelastinterestcalc,gencompanyid,entrydate,entryuserid,lastchangeuserid) values ('.sqlprep($invoicenumber).', '.sqlprep($ponumber).', '.sqlprep(moduleidfromnameshort('ar')).', '.sqlprep($orderbycompanyid).', '.sqlprep($shiptocompanyid).', -1, '.sqlprep($customerbillcode).', '.sqlprep($invoicetermsid).','.sqlprep($salesmanid).', NOW(), '.sqlprep($active_company).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).')') === false) echo texterror('Error inserting invoice');
          $recordSet = &$conn->SelectLimit('select id from arinvoice where status=\'-1\' and invoicenumber='.sqlprep($invoicenumber).' and gencompanyid='.sqlprep($active_company).' and entryuserid='.sqlprep($userid).' order by entrydate desc',1);
          if (!$recordSet||$recordSet->EOF) {
              echo texterror($lang['STR_ERROR_INSERTING_INVOICE']);
              $recordSet = &$conn->Execute('select count(*) from arinvoice where invoicenumber='.sqlprep($invoicenumber).' and gencompanyid='.sqlprep($active_company));
              if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]) {
                  echo texterror($lang['STR_INVOICE_NUMBER'] .$invoicenumber. $lang['STR_IN_USE']);
                  $recordSet = &$conn->Execute('select max(invoicenumber)+1 from arinvoice where gencompanyid='.sqlprep($active_company));
                  if ($recordSet&&!$recordSet->EOF) $invoicenumber=$recordSet->fields[0];
                  $recordSet = &$conn->Execute('select nextinvoicenum from arcompany where id='.sqlprep($active_company));
                  if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]>$invoicenumber) $invoicenumber=$recordSet->fields[0];
              } else { //non-correctable problem
                  die();
              };
          } else {
              $invoiceid=$recordSet->fields[0];
          };
     };
     if ($customerid&&$invoiceid&&$completeinvoice) {
          $timestamp =  strtotime($invoicedate);
          $date_time_array =  getdate($timestamp);
          $hours =  $date_time_array["hours"];
          $minutes =  $date_time_array["minutes"];
          $seconds =  $date_time_array["seconds"];
          $month =  $date_time_array["mon"];
          $day =  $date_time_array["mday"];
          $year =  $date_time_array["year"];
          $modday=$day+AR_ORDER_DUEDATE_MOD;
          $timestamp =  mktime($hour, $minute, $second, $month, $modday, $year);
          $bgdate=date("Y-m-d", $timestamp);
          $recordSet = &$conn->Execute('select verbal,discountdays,discountdayofmonth,netduedays from invoiceterms where id='.sqlprep($invoicetermsid));
          $discmonth = $month;
          $discday = $day;
          $netday=$day;
          if ($recordSet&&!$recordSet->EOF) {
              if ($recordSet->fields[2]) { //discount day of month
                  if ($recordSet->fields[2]<=$day) $discmonth=$date_time_array["mon"]+1;
                  $discday=$recordSet->fields[2];
              } else {
                  $discday=$date_time_array["mday"]+$recordSet->fields[1];
              };
              $netday+=$recordSet->fields[3]; //set due date
          };
          $timestamp =  mktime($hour, $minute, $second, $discmonth, $discday, $year);
          $discdate=date("Y-m-d", $timestamp);
          $timestamp =  mktime($hour, $minute, $second, $month, $netday, $year);
          $netdate=date("Y-m-d", $timestamp);
          $recordSet = &$conn->Execute('select company.id from company,customer where company.id=customer.companyid and customer.id='.sqlprep($customerid));
          if ($recordSet&&!$recordSet->EOF) $orderbycompanyid=$recordSet->fields[0];
               $j=1;
               for ($i=1; ${"description".$i}; $i++) {
                    if (${"qty".$i}>0) { //if quantity isn't 0
                         if (!${"qtyunitperpriceunit".$i}) ${"qtyunitperpriceunit".$i}=1;
                         ${"totalprice".$i}=(${"qty".$i}/${"qtyunitperpriceunit".$i})*${"priceach".$i};
                         $invoicetotal+=${"totalprice".$i};
                         if (${"taxflag".$i}) $totaltaxprice+=${"totalprice".$i};
                         if ($conn->Execute("insert into arinvoicedetail (invoiceid,linenumber,description,qty,qtyunitnameid,glaccountid,taxflag,priceach,priceunitnameid,qtyunitperpriceunit,totalprice,entrydate,entryuserid,lastchangeuserid) VALUES (".sqlprep($invoiceid).", ".sqlprep($j).', '.sqlprep(${"description".$i}).", ".sqlprep(${"qty".$i}).", ".sqlprep(${"qtyunitnameid".$i}).", ".sqlprep(${"glaccountid".$i}).", ".sqlprep(${"taxflag".$i}).", ".sqlprep(${"priceach".$i}).", ".sqlprep(${"priceunitnameid".$i}).", ".sqlprep(${"qtyunitperpriceunit".$i}).", ".sqlprep(${"totalprice".$i}).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).")") === false) echo texterror('Error inserting invoice details. ('.$i.')');
                         $j++;
                    };
               };
               echo '<form action="arinvoiceadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><input type="hidden" name="detailsadded" value="1"><table>';
               echo '<input type="hidden" name="invoiceid" value="'.$invoiceid.'">';
               echo '<input type="hidden" name="customerid" value="'.$customerid.'">';
               echo '<input type="hidden" name="invoicenumber" value="'.$invoicenumber.'">';
               echo '<input type="hidden" name="invoicetotal" value="'.$invoicetotal.'">';
               echo '<input type="hidden" name="invoicedate" value="'.$invoicedate.'">';
               echo '<input type="hidden" name="salesmanid" value="'.$salesmanid.'">';
               echo '<input type="hidden" name="invoicetermsid" value="'.$invoicetermsid.'">';
               $recordSet = &$conn->Execute('select duedate,discountdate from arinvoice where id='.sqlprep($invoiceid));
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DUE_DATE'].':</td><td><input type="text" name="duedate" onchange="formatDate(this)" size="30" value="'.$netdate.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.duedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_DATE'].':</td><td><input type="text" name="discountdate" onchange="formatDate(this)" size="30" value="'.$discdate.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.discountdate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
               if ($totaltaxprice) { //only try to calc taxes if something was taxable
                   $recordSet = &$conn->Execute('select salestax.id,salestax.taxname,salestax.taxrate/100 from salestax,customersalestax,customer where customersalestax.salestaxid=salestax.id and customersalestax.customerid='.sqlprep($customerid).' and salestax.cancel=0 and customer.id='.sqlprep($customerid));
                   $i=1;
                   while ($recordSet&&!$recordSet->EOF) {
                       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX'].' - '.rtrim($recordSet->fields[1]).':<input type="hidden" name="taxid'.$i.'" value="'.$recordSet->fields[0].'"></td><td><input type="text" name="tax'.$i.'" size="30" maxlength="20" onchange="validatenum(this)" value="'.num_format(($totaltaxprice*$recordSet->fields[2]),PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td></tr>';
                       $recordSet->MoveNext();
                       $i++;
                   };
               };

               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHIPPING'].':</td><td><input type="text" name="shipcost" size="30" maxlength="20" onchange="validatenum(this)"'.INC_TEXTBOX.'></td></tr>';
               echo '</table><input type="submit" name="continue" value="'.$lang['STR_CONTINUE'].'"></form>';
     } elseif ($customerid&&$invoiceid&&$detailsadded) {
               for ($i=1; ${"taxid".$i}; $i++) {
                    if (${"tax".$i}>0) { //if tax amount > 0
                         $totaltaxprice+=${"tax".$i};
                         if ($conn->Execute("insert into arinvoicetaxdetail (invoiceid,taxid,taxamount) VALUES (".sqlprep($invoiceid).", ".sqlprep(${"taxid".$i}).', '.sqlprep(${"tax".$i}).")") === false) echo texterror($lang['STR_ERROR_INSERTING_INVOICE_TAX_DETAILS']('.$i.'));
                    };
               };
               if ($conn->Execute('update arinvoice set duedate='.sqlprep($duedate).', discountdate='.sqlprep($discountdate).', shipcost='.sqlprep($shipcost).', invoicetotal='.sqlprep($invoicetotal+$totaltaxprice+$shipcost).', status=0, invoicedate='.sqlprep($invoicedate).' where id='.sqlprep($invoiceid)) === false) die(texterror($lang['STR_ERROR_INSERTING_INVOICE']));
               echo textsuccess($lang['STR_INVOICE_ADDED_SUCCESSFULLY']);
               echo '<a href="arinvoiceaddcogs.php?invoiceid='.$invoiceid.'&customerid='.$customerid.'&invoicenumber='.$invoicenumber.'">'.$lang['STR_ENTER_COST_OF_GOODS'].'</a><br>';
               echo '<a href="arinvoiceview.php?printable=1&post=1&invoicenumber='.$invoicenumber.'">'.$lang['STR_POST_PRINT_THIS_INVOICE'].'</a><br>';
               echo '<a href="arinvoiceadd.php">'.$lang['STR_ADD_NEW_INVOICE'].'</a>';
     } elseif ($customerid&&$invoiceid&&!$completeinvoice&&!$continue) { //if the user has submitted initial info
          if ($duedate&&$discountdate) if ($conn->Execute('update arinvoice set duedate='.sqlprep($duedate).', discountdate='.sqlprep($discountdate).', salesmanid='.sqlprep($salesmanid).' where id='.sqlprep($invoiceid)) === false) echo texterror('Error updating invoice');
          $recordSet = &$conn->SelectLimit('select taxexemptid,salesglacctid from customer where id='.sqlprep($customerid),1);
          if ($recordSet&&!$recordSet->EOF) {
              $taxexempt=$recordSet->fields[0];
              $salesglacctid=$recordSet->fields[1];
          };
          for ($i=1; ${"qty".$i}; $i++) {
          }; //just increment i, nothing else
          echo '<form action="arinvoiceadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table border="1">';
          echo '<input type="hidden" name="invoiceid" value="'.$invoiceid.'">';
          echo '<input type="hidden" name="customerid" value="'.$customerid.'">';
          echo '<input type="hidden" name="invoicenumber" value="'.$invoicenumber.'">';
          echo '<input type="hidden" name="invoicedate" value="'.$invoicedate.'">';
          echo '<input type="hidden" name="invoicetermsid" value="'.$invoicetermsid.'">';
          echo '<input type="hidden" name="salesmanid" value="'.$salesmanid.'">';

          echo '<tr><th>'.$lang['STR_DESCRIPTION'].'</th><th>'.$lang['STR_TAXABLE'].'<input type="checkbox" checked></th><th rowspan="2">'.$lang['STR_QTY_UNIT_PER'].'<br>'.$lang['STR_PRICE_UNIT'].'</th></tr>';
          echo '<tr><th>'.$lang['STR_PRICE'].'</th><th>'.$lang['STR_QUANTITY'].'</th></tr>';
          echo '<tr><td><input type="text" name="description'.$i.'" size="50" maxlength="100"'.INC_TEXTBOX.'></td>';
          if ($taxexempt) $taxstr=' checked';
          echo '<td align="center"><input type="checkbox" name="taxflag'.$i.'" value="1"'.$taxstr.INC_TEXTBOX.'></td><td></td></tr>';
          echo '<tr><td><input type="text" name="priceach'.$i.'" onchange="validatenum(this)" size="10" maxlength="15"'.INC_TEXTBOX.'>';
          echo '<select name="priceunitnameid'.$i.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id,unitname from unitname where cancel=0 order by id');
          while ($recordSet&&!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select>';
          echo '<select name="glaccountid'.$i.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute("select glaccount.id,glaccount.name, glaccount.description, customer.salesglacctid from glaccount,customer where customer.id=".sqlprep($customerid)." and glaccount.accounttypeid='50' and (glaccount.companyid=0 or glaccount.companyid=".sqlprep($active_company).") order by glaccount.name");
          while ($recordSet&&!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],$recordSet->fields[3]," selected").'>'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td>';
          echo '<td><input type="text" name="qty'.$i.'" onchange="validatenum(this)" size="5" maxlength="15"'.INC_TEXTBOX.'>';
          echo '<select name="qtyunitnameid'.$i.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id,unitname from unitname where cancel=0 order by id');
          while ($recordSet&&!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td>';
          echo '<td><input type="text" name="qtyunitperpriceunit'.$i.'" onchange="validatenum(this)" value="1" size="5" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
          for ($i=1; ${"description".$i}; $i++) { //pass prev submitted items to next form
               if ($i==1) echo '<tr><td colspan="2">&nbsp;</td></tr>';
               if (${"qty".$i}>0) { //if quantity isn't 0
                    echo '<tr><td><input type="text" name="description'.$i.'" value="'.${'description'.$i}.'" size="50" maxlength="100"'.INC_TEXTBOX.'></td>';
                    echo '<td align="center"><input type="checkbox" name="taxflag'.$i.'" value="1"'.checkequal(${'taxflag'.$i},1,' checked').INC_TEXTBOX.'></td><td></td></tr>';
                    echo '<tr><td><input type="text" name="priceach'.$i.'" value="'.${'priceach'.$i}.'" onchange="validatenum(this)" size="10" maxlength="15"'.INC_TEXTBOX.'>';
                    echo '<select name="priceunitnameid'.$i.'"'.INC_TEXTBOX.'>';
                    $recordSet = &$conn->Execute('select id,unitname from unitname where cancel=0 order by id');
                    while ($recordSet&&!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'"'.checkequal(${'priceunitnameid'.$i},$recordSet->fields[0],' selected').'>'.rtrim($recordSet->fields[1])."\n";
                        $recordSet->MoveNext();
                    };
                    echo '</select>';
                    echo '<select name="glaccountid'.$i.'"'.INC_TEXTBOX.'>';
                    $recordSet = &$conn->Execute("select glaccount.id,glaccount.name, glaccount.description, customer.salesglacctid from glaccount,customer where customer.id=".sqlprep($customerid)." and glaccount.accounttypeid='50' and (glaccount.companyid=0 or glaccount.companyid=".sqlprep($active_company).") order by glaccount.name");
                    while ($recordSet&&!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],${'glaccountid'.$i}," selected").'>'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2])."\n";
                        $recordSet->MoveNext();
                    };
                    echo '</select></td>';
                    echo '<td><input type="text" name="qty'.$i.'" value="'.${'qty'.$i}.'" onchange="validatenum(this)" size="5" maxlength="15"'.INC_TEXTBOX.'>';
                    echo '<select name="qtyunitnameid'.$i.'"'.INC_TEXTBOX.'>';
                    $recordSet = &$conn->Execute('select id,unitname from unitname where cancel=0 order by id');
                    while ($recordSet&&!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],${'qtyunitnameid'.$i},' selected').'>'.rtrim($recordSet->fields[1])."\n";
                        $recordSet->MoveNext();
                    };
                    echo '</select></td>';
                    echo '<td><input type="text" name="qtyunitperpriceunit'.$i.'" onchange="validatenum(this)" value="'.${'qtyunitperpriceunit'.$i}.'" size="5" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    $total+=(${"qty".$i}/${"qtyunitperpriceunit".$i})*${"priceach".$i};
               };
          };
          echo '<tr><td><div align="right"><b>'.$lang['STR_TOTAL_PRICE'].':</b></div></td><td>'.CURRENCY_SYMBOL.num_format($total,PREFERRED_DECIMAL_PLACES).'</td></tr>';
          echo '</table><input type="submit" name="submit" value="'.$lang['STR_ADD_LINE_ITEM_TO_INVOICE'].'">';
          for ($i=1; ${"description".$i}; $i++) if (${"description".$i}&&${"qty".$i}) $canclose=1;
          if ($canclose) echo '<input type="submit" name="completeinvoice" value="'.$lang['STR_COMPLETE_INVOICE'].'">';
          echo '</form>';
          
          
          
     } elseif (($customerid&&$invoicedate&&!$invoiceid)||$shiprefresh) {
          echo '<form action="arinvoiceadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
          $recordSet = &$conn->Execute('select invoicetermsid,companyid from customer where id='.sqlprep($customerid));
          if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_CUSTOMER_NOT_FOUND']));
          $invoicetermsid=$recordSet->fields[0];
          echo '<input type="hidden" name="customerid" value="'.$customerid.'">';
          echo '<input type="hidden" name="invoicedate" value="'.$invoicedate.'">';
          echo '<input type="hidden" name="orderbycompanyid" value="'.$recordSet->fields[1].'">';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHIP_TO'].':</td><td><select name="shiptocompanyid"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select company.id,company.companyname,company.address1,company.city,company.state,company.country from company,shipto,customer where shipto.shiptocompanyid=company.id and shipto.companyid=customer.companyid and customer.id='.sqlprep($customerid).' and shipto.cancel=0 and company.cancel=0 order by company.companyname,company.country,company.address1,company.city,company.state');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2]).', '.rtrim($recordSet->fields[3]).', '.rtrim($recordSet->fields[4]).' '.rtrim($recordSet->fields[5])."\n";
               $recordSet->MoveNext();
          };
          echo '</select><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'arcustupd.php?customerid='.$customerid.'&shipto=1&shiptoselected=1\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="images/addbutton.png" border="0" alt="Ship To Add"></a></td></tr>';

          $recordSet = &$conn->Execute('select max(invoicenumber)+1 from arinvoice where gencompanyid='.sqlprep($active_company));
          if ($recordSet&&!$recordSet->EOF) $invoicenumber=$recordSet->fields[0];
          $recordSet = &$conn->Execute('select nextinvoicenum from arcompany where id='.sqlprep($active_company));
          if ($recordSet&&!$recordSet->EOF&&$recordSet->fields[0]>$invoicenumber) $invoicenumber=$recordSet->fields[0];
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_NUMBER'].':</td><td><input type="text" name="invoicenumber" size="30" maxlength="20" onchange="validateint(this)" value="'.$invoicenumber.'"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PURCHASE_ORDER'].':</td><td><input type="text" name="ponumber" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_BILL_CODE'].':</td><td><input type="text" name="customerbillcode" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          formarsalesmanselect('salesmanid');
          formarinvoicetermsselect('invoicetermsid');
          echo '</table><br><input type="submit" name="submit" value="'.$lang['STR_NEXT_SCREEN'].'"></form>';
          
     } else {
          echo '<form action="arinvoiceadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_NUMBER'].':</td><td><input type="text" name="customerid" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupcustomer.php?name=customerid\',\'cal\',\'dependent=yes,width=450,height=250,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Customer Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'arcustadd.php\',\'cal\',\'dependent=yes,width=600,height=600,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="images/addbutton.png" border="0" alt="'.$lang['Customer Add'].'"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_DATE'].':</td><td><input type="text" name="invoicedate" onchange="formatDate(this)" size="30" value="'.createtime('Y-m-d').'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.invoicedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '</table><br><input type="submit" name="continue" value="'.$lang['STR_CONTINUE'].'"></form>';
          
     };
echo '</center>';
?>

<?php include_once("includes/footer.php"); ?>
