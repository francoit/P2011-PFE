<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?php include('includes/glfunctions.php'); ?>
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
     echo texttitle($lang['STR_PAY_BILLS']);
     if ($invoicenumber||$vendorid||$duedate||$billid) {
          if (!$billid) {
               if ($invoicenumber) $invoicestr=' and apbill.invoicenumber='.sqlprep($invoicenumber);
               if ($vendorid) $vendorstr=' and apbill.vendorid='.sqlprep($vendorid);
               if ($duedate) $duedatestr='and apbill.duedate<='.sqlprep($duedate);
               $recordSet = &$conn->Execute('select count(*) from apbill,vendor,company left join apbillpayment on apbillpayment.apbillid=apbill.id where apbill.paynone=0 and vendor.paynone=0 and apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id and apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company).$invoicestr.$vendorstr.$duedatestr.' and apbill.complete=0');
               $billtot=0;
               if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]==1) { //exactly 1 matching invoice found
                    $recordSet = &$conn->Execute('select apbill.id,apbill.invoicenumber,apbill.description,apbill.entrydate,apbill.duedate,apbill.total,company.companyname,apbill.complete,sum(apbillpayment.amount*(apbillpayment.checkvoid!=0)),apbill.discountdate,apbill.discountamount,vendor.id from apbill,vendor,company left join apbillpayment on apbillpayment.apbillid=apbill.id where apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id and apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company).$invoicestr.$vendorstr.$dateofinvstr.$duedatestr.' and apbill.complete=0 group by apbill.id order by company.companyname, apbill.duedate, apbill.invoicenumber');
                    $payt=array($recordSet->fields[0]);
                    $billid=$payt;
               } else {
                    $recordSet = &$conn->Execute('select apbill.id,apbill.invoicenumber,apbill.description,apbill.entrydate,apbill.duedate,apbill.total,company.companyname,apbill.complete,sum(apbillpayment.amount*(apbillpayment.checkvoid!=0)),apbill.discountdate,apbill.discountamount,vendor.id,apbill.paynone,vendor.paynone from apbill,vendor,company left join apbillpayment on apbillpayment.apbillid=apbill.id where  apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id and apbill.cancel=0 and apbill.complete=0 and vendor.gencompanyid='.sqlprep($active_company).' and apbill.gencompanyid='.sqlprep($active_company).$invoicestr.$vendorstr.$dateofinvstr.$duedatestr.' and apbill.complete=0 group by apbill.id order by company.companyname, apbill.duedate, apbill.invoicenumber');
                    if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_INVOICES_FOUND']));
                    echo '<form action="apbillpay.php" method="post" name="mainform"><table><input type="hidden" name="allowdiscount" value="'.$allowdiscount.'"><input type="hidden" name="checkacctid" value="'.$checkacctid.'"><input type="hidden" name="nonprintable" value="1">';
                    echo '<table border="1"><tr><th>'.$lang['STR_VENDOR'].' <font size="-1">'.$lang['STR_HOLD'].'</font></th><th>'.$lang['STR_DUE_DATE'].'</th><th>'.$lang['STR_INVOICE'].'</th><th>'.$lang['STR_AMOUNT'].'</th><th>'.$lang['STR_DISCOUNT'].'</th><th>'.$lang['STR_PAID'].'</th><th>'.$lang['STR_BILL_ON_HOLD'].'</th><th>'.$lang['STR_PAY_INVOICE'].'</th></tr>';
                    $billid=$recordSet->fields[0];
                    $i=1;
                    while (!$recordSet->EOF) {
                         $i++;
                         if ($duedate) {
                              if ($allowdiscount&&strtotime($recordSet->fields[9])<=strtotime($duedate)) {
                                   $discount=$recordSet->fields[10];
                              } else {
                                   $discount=0;
                              };
                         } else {
                              if ($allowdiscount&&strtotime($recordSet->fields[9])>=strtotime("now")) {
                                   $discount=$recordSet->fields[10];
                              } else {
                                   $discount=0;
                              };
                         };
                         echo '<tr><td>'.$recordSet->fields[6].' <input type="hidden" name="vnd[]" value="'.$recordSet->fields[11].'"><input type="checkbox" name="vndhold[]" value="'.$recordSet->fields[11].'"'.checkequal($recordSet->fields[13],1,' checked').'></td><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[1].'</td><td>'.CURRENCY_SYMBOL.checkdec($recordSet->fields[5],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.checkdec($discount,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.checkdec($recordSet->fields[8],PREFERRED_DECIMAL_PLACES).'</td><td><input type="hidden" name="bill[]" value="'.$recordSet->fields[0].'"><input type="checkbox" name="billhold[]" value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[12],1,' checked').'></td><td><input type="checkbox" name="payt[]" onclick="billsum()" value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[12]+$recordSet->fields[13],0,' checked').'><input type="hidden" name="billid[]" value="'.$recordSet->fields[0].'"><input type="hidden" name="billtotal[]" value="'.($recordSet->fields[5]-$discount-$recordSet->fields[8]).'"></td></tr>';
                         if ($recordSet->fields[12]+$recordSet->fields[13]==0) $billtot+=$recordSet->fields[5]-$discount-$recordSet->fields[8];
                         if ($detail) {
                              $recordSet2 = &$conn->Execute('select apbilldetail.amount, glaccount.name, glaccount.description, apbilldetail.invreceiveid from apbilldetail,glaccount where apbilldetail.glaccountid=glaccount.id and apbilldetail.apbillid='.sqlprep($recordSet->fields[0]));
                              while ($recordSet2&&!$recordSet2->EOF) {
                                   echo '<tr><td colspan="3" align="right">'.$lang['STR_ACCOUNT'].': '.$recordSet2->fields[1].' - '.$recordSet2->fields[2].'</td><td colspan="5" align="right">Amount: '.CURRENCY_SYMBOL.checkdec($recordSet2->fields[0],PREFERRED_DECIMAL_PLACES).'</td></tr>';
                                   $recordSet2->MoveNext();
                              };
                              echo '<tr><td colspan="8" align="right">'.$lang['STR_TOTAL'].': '.CURRENCY_SYMBOL.checkdec($recordSet->fields[5],PREFERRED_DECIMAL_PLACES).'</td></tr>';
                              echo '<tr><td colspan="8">&nbsp;</td></tr>';
                         };
                         $recordSet->MoveNext();
                    };
                    echo '<tr><td colspan="8" align="right">'.$lang['STR_TOTAL'].': <input disabled type="text" name="gtotal" value="'.$billtot.'"></td></tr>';
                    echo '</table><input type="submit" value="'.$lang['STR_CONTINUE'].'">';

                        echo '<script language="JavaScript">'."\n";
                        echo '      function billsum() {'."\n";
                        echo '            numb = "0"'."\n";
                        for ($j=0; $j<$i-1; $j++) echo '          numb = eval(numb) + parseInt(eval(document.mainform.elements['.(7+($j*7)).'].checked) * eval(document.mainform.elements['.(9+($j*7)).'].value) * 100) / 100'."\n";
                        echo '            document.mainform.gtotal.value=eval(parseInt(numb*100)/100)'."\n";
                        echo '      }'."\n";
                        echo '</script>'."\n";
                        echo '</form>';
               };
          };
          if ($billid&&!$final) { //test separate rather then as else on above statement, so we can skip selection screen if search only returns 1 bill.
               if (!is_array($vnd)) $vnd=array($vnd);
               foreach ($vnd as $data) { //clear all vendors
                    $conn->Execute('update vendor set paynone=0 where id='.sqlprep($data));
               };
               if (!is_array($vndhold)) $vndhold=array($vndhold);
               foreach ($vndhold as $data) { //put selected vendors on hold
                    $conn->Execute('update vendor set paynone=1 where id='.sqlprep($data));
               };
               if (!is_array($bill)) $bill=array($bill);
               foreach ($bill as $data) { //clear all bills
                    $conn->Execute('update apbill set paynone=0 where id='.sqlprep($data));
               };
               if (!is_array($billhold)) $billhold=array($billhold);
               foreach ($billhold as $data) { //put selected bills on hold
                    $conn->Execute('update apbill set paynone=1 where id='.sqlprep($data));
               };
               if (!is_array($payt)) $payt=array($payt);
               foreach ($payt as $data) {
               $i = 0;
                    $recordSet = &$conn->Execute('select apbill.id,apbill.invoicenumber,apbill.description,apbill.entrydate,apbill.duedate,apbill.total,company.companyname,apbill.complete,sum(apbillpayment.amount*(apbillpayment.checkvoid!=0)),apbill.discountdate,apbill.discountamount,vendor.id from apbill,vendor,company left join apbillpayment on apbillpayment.apbillid=apbill.id where apbill.paynone=0 and vendor.paynone=0 and apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id and apbill.id='.sqlprep($data).' group by apbill.id');
                    if ($recordSet&&!$recordSet->EOF) {
                         $i++;
                         if ($i==1) { //first pass, start the form.  This allows page to die gracefully if no valid bills were passed, which must be tested for later.
                              echo '<form action="apbillpay.php" method="post" name="mainform"><table border="1"><input type="hidden" name="final" value="1"><input type="hidden" name="nonprintable" value="1">';
                              echo '<tr><th colspan="6" align="center">'.$lang['STR_VENDOR'].'</th></tr><tr><th>'.$lang['STR_DUE_DATE'].'</th><th>'.$lang['STR_INVOICE_NUMBER'].'</th><th>'.$lang['STR_AMOUNT'].'</th><th>'.$lang['STR_DISCOUNT'].'</th><th>'.$lang['STR_PAID'].'</th><th>'.$lang['STR_AMOUNT'].'</th></tr>';
                         };
                         if ($recordSet->fields[11]<>$oldvendorid) { //if we're starting on a new vendor
                              if ($oldvendorid) echo '<tr><td colspan="6">&nbsp;</td></tr>';
                              echo '<tr><td colspan="6">'.$recordSet->fields[11].' - '.$recordSet->fields[6].'</td></tr>';
                         };
                         $oldvendorid=$recordSet->fields[11];
                         if ($allowdiscount&&strtotime($recordSet->fields[9])>=strtotime("now")) {
                              $discount=$recordSet->fields[10];
                         } else {
                              $discount=0;
                         };
                         echo '<input type="hidden" name="billid[]" value="'.$recordSet->fields[0].'"><input type="hidden" name="payvendorid[]" value="'.$recordSet->fields[11].'"><tr><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[1].'</td><td>'.CURRENCY_SYMBOL.checkdec($recordSet->fields[5],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.checkdec($discount,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.checkdec($recordSet->fields[8],PREFERRED_DECIMAL_PLACES).'</td><td><input type="text" name="pay'.$recordSet->fields[0].'[]" value="'.checkdec($recordSet->fields[5]-$recordSet->fields[8]-$discount,PREFERRED_DECIMAL_PLACES).'"></td></tr>';
                         echo '<input type="hidden" name="allowdiscount" value="'.$allowdiscount.'">';
                    };
                    if (!$i) {//if no valid bills got passed
                         die(texterror($lang['STR_NO_VALID_BILLS_SELECTED']));
                    };
               };
               $recordSet=&$conn->Execute('select name, lastchecknumberused, defaultendorser, glaccountid from checkacct where id='.sqlprep($checkacctid));
               if ($recordSet&&!$recordSet->EOF) {
                    $checkacctname=$recordSet->fields[0];
                    $checkacctnum=$recordSet->fields[1];
                    echo '</table><input type="hidden" name="checkacctid" value="'.$checkacctid.'"><input type="hidden" name="checkacctglid" value="'.$recordSet->fields[3].'"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Check Account:</td><td>'.$checkacctname.'</td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ENDORSER'].':</td><td><input type="text" name="endorser" value="'.$recordSet->fields[2].'"></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHECK_NUMBER'].':</td><td><input type="text" name="checknumber" onchange="validateint(this)" value="'.($checkacctnum+1).'"></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHECK_DATE'].':</td><td><input type="text" name="checkdate" onchange="formatDate(this)" value="'.createtime('Y-m-d').'"></td></tr></table>';
               };
               echo '<input type="submit" value="'.$lang['STR_PRINT_CHECK'].'"></form>';
          } elseif ($billid&&$final) { //if ready to print checks
               $recordSet=&$conn->Execute('select payable,interestexpense,discount,discearn,usetransactiondate from apcompany where id='.sqlprep($active_company));
               if ($recordSet&&!$recordSet->EOF) {
                    $payable=$recordSet->fields[0];
                    $interestexpense=$recordSet->fields[1];
                    $discountaccount=$recordSet->fields[2];
                    $discearn=$recordSet->fields[3];
               };
               foreach ($billid as $data) {
                    foreach (${"pay".$data} as $pay) {
                         if ($pay>0) {
                              $recordSet = &$conn->Execute('select apbill.id,apbill.invoicenumber,apbill.description,apbill.entrydate,apbill.duedate,apbill.total,company.companyname,apbill.complete,sum(apbillpayment.amount*(apbillpayment.checkvoid!=0)),apbill.discountdate,apbill.discountamount,vendor.id,apbill.wherefrom from apbill,vendor,company left join apbillpayment on apbillpayment.apbillid=apbill.id where apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id and apbill.id='.sqlprep($data).'group by apbill.id');
                              if ($recordSet->fields[11]<>$oldvendorid) { //if we're starting on a new vendor
                                   if ($oldvendorid) $checknumber++;
                                   $conn->Execute('insert into check (wherefrom,paytype,checkdate,amount,checkaccountid,checknumber,entrydate,entryuserid,lastchangeuserid) values ('.sqlprep($wherefrom).', '.sqlprep($paytype).', '.sqlprep($checkdate).', '.sqlprep($pay).', '.sqlprep($checkacctid).', '.sqlprep($checknumber).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).')');
                                   $recordSet2=&$conn->SelectLimit('select id from check where checkdate='.sqlprep($checkdate).' and amount='.sqlprep($pay).' and checkaccountid='.sqlprep($checkacctid).' and checknumber='.sqlprep($checknumber).' order by entrydate desc',1);
                                   if ($recordSet2&&!$recordSet2->EOF) $checkid=$recordSet2->fields[0];
                                   echo 'Check <a target="_blank" href="checks.php?apbill=1&endorser='.$endorser.'&checknbr='.$checkid.'">#'.$checknumber.'</a><br>';
                                   if ($allowdiscount) {
                                        if (strtotime($recordSet->fields[9])>=strtotime("now")) {
                                             $prediscount=1;
                                             $discount=$recordSet->fields[10];
                                        } else {
                                             $postdiscount=1;
                                             $discountlost=$recordSet->fields[10];
                                             $discount=0;
                                        };
                                   };
                              } else {
                                   $conn->Execute('update check set amount=amount+'.$pay.' where id='.sqlprep($checkid));
                              };
                              if ($recordSet->fields[8]+$pay+$discount>=$recordSet->fields[5]) $conn->Execute('update apbill set complete=1 where id='.sqlprep($data));
                              $oldvendorid=$recordSet->fields[11];
                              $wherefrom=$recordSet->fields[12];
                              $conn->Execute('insert into apbillpayment (apbillid,amount,checkid,entrydate,entryuserid,lastchangeuserid) values ('.sqlprep($data).', '.sqlprep($pay).', '.sqlprep($checkid).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).')');
                              $voucherid=gltransvoucheradd('billpay'.$recordSet->fields[1],$recordSet->fields[2],$checkdate,1);
                              if (!$voucherid) die(texterror($lang['STR_ERROR_ADDING_GLTRANSVOUCHER_RECORD_-_GL_ENTRY_CANCELLED']));
                              $fail1=gltransactionadd($voucherid, $pay,$payable);
                              $fail2=gltransactionadd($voucherid, -$pay,$checkacctglid);
                              if ($prediscount&&$discearn) {
                                   $fail3=gltransactionadd($voucherid, $discount, $payable);
                                   $fail4=gltransactionadd($voucherid, -$discount,$discountaccount);
                              } else if ($postdiscount&&!$discearn) {
                                   $fail3=gltransactionadd($voucherid, +$discount, $discountaccount);
                                   $fail4=gltransactionadd($voucherid, -$discount, $checkacctglid);
                              };
                              if ($fail1==0||$fail2==0||$fail3==0||$fail4==0) { //if any entry failed
                                   gltransvoucherdelete($voucherid);
                                   die(texterror($lang['STR_ERROR_ADDING_GL_TRANSACTION_DETAILS_GL_ENTRY_CANCELLED']));
                              };
                         };
                    };
               };
               $conn->Execute('update checkacct set lastchecknumberused='.sqlprep($checknumber).' where id='.sqlprep($checkacctid));
               echo textsuccess($lang['STR_CHECKS_PRINTED_SUCCESSFULLY']);
          };
     } else {
          echo '<form action="apbillpay.php" method="post" name="mainform"><table><input type="hidden" name="nonprintable" value="1">';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_NUMBER'].':</td><td><input type="text" name="invoicenumber" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          formapvendorselect('vendorid');
          if (!$specific) { //if user wants to pay a specific invoice, don't default due date
               $timestamp = time();
               $date_time_array = getdate($timestamp);
               $hours = $date_time_array["hours"];
               $minutes = $date_time_array["minutes"];
               $seconds = $date_time_array["seconds"];
               $month = $date_time_array["mon"];
               $day = $date_time_array["mday"];
               $year = $date_time_array["year"];
               $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
               $today = date("Y-m-d", $timestamp);
               $timestamp = mktime($hour, $minute, $second, $month, $day+7, $year);
               $weekfn = date("Y-m-d", $timestamp);
          };
             echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DUE_DATE'].': </td><td><input type="text" name="duedate" onchange="formatDate(this)" size="30" value="'.$weekfn.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.duedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          $recordSet=&$conn->Execute('select count(*) from checkacct where ap=1 and gencompanyid='.sqlprep($active_company));
          if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]==1) {
               $recordSet=&$conn->Execute('select id from checkacct where ap=1 and gencompanyid='.sqlprep($active_company));
               echo '<input type="hidden" name="checkacctid" value="'.$recordSet->fields[0].'">';
          } else {
               $recordSet=&$conn->Execute('select id, name from checkacct where ap=1 and gencompanyid='.sqlprep($active_company));
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHECKING_ACCOUNT'].'</td><td><select name="checkacctid"'.INC_TEXTBOX.'>';
               while ($recordSet&&!$recordSet->EOF) {
                    echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                    $recordSet->MoveNext();
               };
               echo '</select></td></tr>';
          };
             echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_APPLY_DISCOUNTS'].': </td><td><input type="checkbox" name="allowdiscount" value="1" checked></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
          
     };
         echo '</center>';
?>

<?php include('includes/footer.php'); ?>
