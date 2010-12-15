<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?php include('includes/glfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php   echo '<center>';
     echo texttitle($lang['STR_INVOICE_PAYMENTS']);
     if ($invoicenumber&&$delete) { //delete invoice
          checkpermissions('ar');
          $recordSet = &$conn->SelectLimit('select id from arinvoice where invoicenumber='.sqlprep($invoicenumber).' and cancel=0 and gencompanyid='.sqlprep($active_company).' and status<1 order by entrydate desc',1);
          if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_INVOICE_NUMBER'] .$invoicenumber. $lang['STR_NOT_FOUND_COULD_NOT_DELETE']));
          $invoiceid=$recordSet->fields[0];
          if ($conn->Execute('update arinvoice set cancel=1, canceluserid='.sqlprep($userid).',canceldate=NOW(),lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($invoiceid)) === false) die(texterror('Error deleting invoice.'));
          echo textsuccess($lang['STR_ERROR_DELETING_INVOICE']);
     };
     if ($invoiceid1&&$datereceived) { //update invoice
          checkpermissions('ar');
          $conn->BeginTrans();
          $recordSet = &$conn->Execute('select cash,checking,receivables,interest from arcompany where id='.sqlprep($active_company));
          if (!$recordSet||$recordSet->EOF) {
               $conn->RollbackTrans();
               die(texterror($lang['STR_ERROR_RETRIEVING_GL_ACCOUNTS_FROM_ARCOMPANY']));
          } else {
               $cashgl=$recordSet->fields[0];
               $checkgl=$recordSet->fields[1];
               $argl=$recordSet->fields[2];
               $intgl=$recordSet->fields[3];
          };
          switch ($paymeth) {
              case 1:
                   $plusgl=$cashgl;
                   break;
              case 2:
                   $plusgl=$checkgl;
                   break;
              case 3:
                   $plusgl=$checkgl;
                   break;
              case 4:
                   $plusgl=$checkgl;
                   break;
              default:
                   $plusgl=$checkgl;
                   break;
          };
          if ($conn->Execute('insert into gltransvoucher (voucher,description,wherefrom,companyid,entrydate,lastchangeuserid,entryuserid) values ('.sqlprep('invoicepay'.$voucher).', '.sqlprep('AR Invoice Payment').','.sqlprep(moduleidfromnameshort('ar')).', '.sqlprep($active_company).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).')') === false) {
               $conn->RollbackTrans();
               die(texterror($lang['STR_ERROR_ADDING_VOUCHER_TO_DATABASE']));
          };
          $recordSet = &$conn->SelectLimit('select id from gltransvoucher where voucher='.sqlprep('invoicepay'.$voucher).' and companyid='.sqlprep($active_company).' order by lastchangedate desc',1);
          if (!$recordSet||$recordSet->EOF) {
               $conn->RollbackTrans();
               die(texterror($lang['STR_ERROR_RETRIEVING_VOUCHER_FROM_DATABASE']));
          };
          $voucherid=$recordSet->fields[0];
          for ($i=1; ${"invoiceid".$i}; $i++) {
              if (${"amount".$i}>0) { //if amount isn't 0
                  if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($plusgl).', '.$voucherid.', '.sqlprep(${"amount".$i}).')') === false) {
                      $conn->RollbackTrans();
                      die(texterror($lang['STR_ERROR_ADDING_VOUCHER_DETAIL_TO_DATABASE']));
                  };
                  if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($argl).', '.$voucherid.', '.sqlprep(inv(${"amount".$i})).')') === false) {
                      $conn->RollbackTrans();
                      die(texterror($lang['STR_ERROR_ADDING_VOUCHER_DETAIL_TO_DATABASE']));
                  };
                  if ($conn->Execute("insert into arinvoicepaymentdetail (invoiceid, amount, voucherid, datereceived, paymeth, interest) values (".sqlprep(${'invoiceid'.$i}).", ".sqlprep(${'amount'.$i}).", ".sqlprep($voucherid).", ".sqlprep($datereceived).", ".sqlprep($paymeth).",0)") === false) {
                      $conn->RollbackTrans();
                      die(texterror($lang['STR_ERROR_ADDING_PAYMENT']));
                  };
              };
              if (${"interest".$i}>0) { //if amount isn't 0
                  if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($intgl).', '.$voucherid.', '.sqlprep(${"interest".$i}).')') === false) {
                      $conn->RollbackTrans();
                      die(texterror($lang['STR_ERROR_ADDING_VOUCHER_DETAIL_TO_DATABASE']));
                  };
                  if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($argl).', '.$voucherid.', '.sqlprep(inv(${"interest".$i})).')') === false) {
                      $conn->RollbackTrans();
                      die(texterror($lang['STR_ERROR_ADDING_VOUCHER_DETAIL_TO_DATABASE']));
                  };
                  if ($conn->Execute("insert into arinvoicepaymentdetail (invoiceid, amount, voucherid, datereceived, paymeth, interest) values (".sqlprep(${'invoiceid'.$i}).", ".sqlprep(${'interest'.$i}).", ".sqlprep($voucherid).", ".sqlprep($datereceived).", ".sqlprep($paymeth).",1)") === false) {
                      $conn->RollbackTrans();
                      die(texterror($lang['STR_ERROR_ADDING_PAYMENT']));
                  };
              };
              $recordSet = &$conn->SelectLimit('select arinvoice.invoicetotal-sum(arinvoicepaymentdetail.amount) from arinvoice left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where arinvoice.id='.sqlprep(${'invoiceid'.$i}).' and arinvoice.gencompanyid='.sqlprep($active_company).' group by arinvoice.id,arinvoice.invoicetotal',1);
              if ($recordSet&&$recordSet->fields[0]<=0) { //if invoice is completely paid
                  if ($conn->Execute("update arinvoice set status=2 where id=".sqlprep(${'invoiceid'.$i})) === false) {
                      $conn->RollbackTrans();
                      die(texterror($lang['STR_ERROR_UPDATING_INVOICE']));
                  };
              };
          };
          $conn->CommitTrans();
          echo textsuccess($lang['STR_INVOICE_PAYMENTS_ADDED_SUCCESSFULLY']);
     };
     if ($voucher&&$paymeth&&$amount&&$customerid&&$datereceived) { //if the user has submitted initial info
          $recordSet = &$conn->Execute("select arinvoice.id,arinvoice.invoicenumber,arinvoice.duedate,arinvoice.invoicetotal,sum(arinvoicepaymentdetail.amount),arinvoice.accruedinterest,company.companyname from arinvoice cross join customer cross join company left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where customer.companyid=company.id and arinvoice.orderbycompanyid=customer.companyid and customer.id=".sqlprep($customerid).' and arinvoice.cancel=0 and arinvoice.status<2 and arinvoice.status>0 and arinvoice.gencompanyid='.sqlprep($active_company).' group by arinvoice.id,arinvoice.invoicenumber,arinvoice.duedate,arinvoice.invoicetotal,arinvoice.accruedinterest,company.companyname order by arinvoice.duedate,arinvoice.invoicenumber');
          if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_INVOICES_FOUND']));
          $amountleft=$amount;
          echo texttitle('<font size="-1">'.$recordSet->fields[6].' - '.CURRENCY_SYMBOL.num_format($amount,PREFERRED_DECIMAL_PLACES).'</font>');
          echo '<form action="arinvoicepay.php" method="post" name="mainform"><table border="1">';
          echo '<input type="hidden" name="datereceived" value="'.$datereceived.'">';
          echo '<input type="hidden" name="customerid" value="'.$customerid.'">';
          echo '<input type="hidden" name="voucher" value="'.$voucher.'">';
          echo '<input type="hidden" name="paymeth" value="'.$paymeth.'">';
          echo '<tr><th>'.$lang['STR_INVOICE_NUMBER'].'</th><th>'.$lang['STR_BALANCE'].'</th><th>'.$lang['STR_DUE_DATE'].'</th><th>'.$lang['STR_AMOUNT_PAID'].'</th><th>Interest</th></tr>';
          $i=1;
          while (!$recordSet->EOF) {
              $balance=$recordSet->fields[3];
              if ($recordSet->fields[4]) $balance-=$recordSet->fields[4];
              if ($interest) $balance+=$recordSet->fields[5];
              echo '<input type="hidden" name="invoiceid'.$i.'" value="'.$recordSet->fields[0].'">';
              echo '<tr><td>'.$recordSet->fields[1].'</td>';
              echo '<td>'.CURRENCY_SYMBOL.num_format($balance,PREFERRED_DECIMAL_PLACES).'</td>';
              echo '<td>'.$recordSet->fields[2].'</td>';
              if (!${'amount'.$i}) {
                  if ($balance>=$amountleft) {
                      ${'amount'.$i}=$amountleft;
                      $amountleft=0;
                  } else {
                      ${'amount'.$i}=$balance;
                      $amountleft-=$balance;
                  };
              };
              $tot+=${'amount'.$i};
              $tot+=${'interest'.$i};
              echo '<td><input type="text" size="12" name="amount'.$i.'" value="'.num_format(${'amount'.$i},PREFERRED_DECIMAL_PLACES).'" onchange="chk(this);validatenum(this);upd()"'.INC_TEXTBOX.'></td>';
              echo '<td><input type="text" size="8" name="interest'.$i.'" value="'.num_format(${'interest'.$i},PREFERRED_DECIMAL_PLACES).'" onchange="chk(this);validatenum(this);upd()"'.INC_TEXTBOX.'></td></tr>';
              $i++;
              $recordSet->MoveNext();
          };
          echo '</table>';
          echo '<table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAYMENT_RECEIVED'].':</td><td><input type="text" disabled size="12" name="amount" value="'.CURRENCY_SYMBOL.num_format($amount,PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AMOUNT_PAID'].':</td><td><input type="text" disabled size="12" name="tot" value="'.CURRENCY_SYMBOL.num_format($tot,PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_YET_TO_APPLY'].':</td><td><input type="text" disabled size="12" name="yet" value="'.CURRENCY_SYMBOL.num_format($amount-$tot,PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td></tr></table>';
          echo '<input type="submit" value="'.$lang['STR_COMPLETE_PAYMENT'].'"></form>';

          echo '<script language="JavaScript">'."\n";
          echo ' function upd() {'."\n";
          echo '    var amount;'."\n";
          echo '    var tot;'."\n";
          echo '    var yet;'."\n";
          echo '    amount = '.$amount.';'."\n";
          echo '    tot = 0;'."\n";
          echo '    yet = 0;'."\n";
          for ($j=1; $j<$i; $j++) {
              echo '    tot = tot + eval(document.mainform.amount'.$j.'.value);'."\n";
              echo '    tot = tot + eval(document.mainform.interest'.$j.'.value);'."\n";
          };
          echo '    yet = amount - tot;'."\n";
          echo '    document.mainform.tot.value = "'.CURRENCY_SYMBOL.'" + round(eval(tot) * 100)/100;'."\n";
          echo '    document.mainform.yet.value = "'.CURRENCY_SYMBOL.'" + round(eval(yet) * 100)/100;'."\n";
          echo ' }'."\n";
          echo ' function round(number,X) {'."\n";
          echo '    X = (!X ? 0 : X);'."\n";
          echo '    return Math.round(number*Math.pow(10,X))/Math.pow(10,X);'."\n";
          echo ' }'."\n";
          echo ' function chk(field) {'."\n";
          echo '    field.value = (!field.value ? 0 : field.value);'."\n";
          echo ' }'."\n";
          echo '</script>'."\n";
     } else {
          echo '<form action="arinvoicepay.php" method="post" name="mainform"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_NUMBER'].':</td><td><input type="text" name="customerid" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupcustomer.php?name=customerid\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Customer Lookup"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AMOUNT'].':</td><td><input type="text" name="amount" onchange="validatenum(this)" size="30"'.INC_TEXTBOX.'>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAYMENT_METHOD'].':</td><td><select name="paymeth"'.INC_TEXTBOX.'><option value="1">'.$lang['STR_CASH'].'<option value="2">'.$lang['STR_CHECK'].'<option value="3">'.$lang['STR_CREDIT_CARD'].'<option value="4">'.$lang['STR_OTHER'].'</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DATE_RECEIVED'].':</td><td><input type="text" name="datereceived" onchange="formatDate(this)" size="30" value="'.createtime('Y-m-d').'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.datereceived\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VOUCHER_NUMBER'].':</td><td><input type="text" name="voucher" size="30" maxlength="20"'.INC_TEXTBOX.'>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INCLUDE_PRIOR_INTEREST'].':</td><td><input type="checkbox" name="interest" value="1" checked'.INC_TEXTBOX.'></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_APPLY_PAYMENT'].'"></form>';
     };
          
          echo '</center>';
?>

<?php include_once("includes/footer.php"); ?>
