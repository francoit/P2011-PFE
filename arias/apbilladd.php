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
     echo texttitle($lang['STR_ADD_BILL']);
     if ($invoicenumber) {
          if ($description) {
               if ($submit==$lang['STR_COMPLETE_BILL']) {
                    echo texttitle('<font size="-1">'.$lang['STR_VENDOR'].' '.$vendorname.' - '.$lang['STR_INVOICE_NUMBER'].' '.$invoicenumber.' - '.$lang['STR_DATE'].' '.$dateofinvoice.'</font>');
                    //read general ap posting accounts first
                    $recordSet=&$conn->Execute('select payable,interestexpense,usetransactiondate from apcompany where id='.sqlprep($active_company));
                    if ($recordSet&&!$recordSet->EOF) {
                         $payable=$recordSet->fields[0];
                         $interestexpense=$recordSet->fields[1];
                         $usetransactiondate=$recordSet->fields[2];
                    };
                    $timestamp =  time();
                    $date_time_array =  getdate($timestamp);
                    $hours =  $date_time_array["hours"];
                    $minutes =  $date_time_array["minutes"];
                    $seconds =  $date_time_array["seconds"];
                    $month =  $date_time_array["mon"];
                    $day =  $date_time_array["mday"];
                    $year =  $date_time_array["year"];
                    $timestamp =  mktime($hours, $minutes, $seconds, $month, $day, $year);
                    $today=date("Y-m-d", $timestamp);
                    $postdate=$dateofinvoice;
                    if (!$usetransactiondate) $postdate=$today;
                    $apbillid=billadd($invoicenumber,$total,$description,$dateofinvoice,$duedate,$discountamount,$discountdate,$vendorid,1,0);
                    if ($apbillid) {
                         $conn->BeginTrans();
                         //first add general information to gltransvoucher
                         $voucherid=gltransvoucheradd($invoicenumber,$description,$postdate,1);
                         if (!$voucherid) {
                               billdeleteadd($apbillid);
                               $conn->RollbackTrans();
                               die(texterror($lang['STR_ERROR_ADDING_BILL']));
                         };
                         //need to write info to gltransaction file for payables side of post
                         $fail=gltransactionadd($voucherid, inv($total),$payable);
                         if (!$fail) {
                            //need to remove all gl transactions!
                            gltransvoucherdelete($voucherid);
                            billdeleteadd($apbillid);
                            $conn->RollbackTrans();
                            die(texterror($lang['STR_ERROR_ADDING_BILL']));
                         };
                         for ($i=1; $i<=$maxid; $i++) {
                              if (!${"amount".$i}==0) {
                                 if (!billdetailadd($apbillid,${"amount".$i},${"glaccountid".$i},0)) {
                                      //need to remove all gl transactions!
                                      gltransactiondelete($voucherid);
                                      gltransvoucherdelete($voucherid);
                                      billdeleteadd($apbillid);
                                      billdetetebybillid($apbillid);
                                      $conn->RollbackTrans();
                                      die(texterror($lang['STR_ERROR_ADDING_BILL']));
                                 } else {
                                      //need to write info to gltransaction file for details
                                      $fail=gltransactionadd($voucherid, ${"amount".$i},${"glaccountid".$i});
                                      if (!$fail) {
                                          //need to remove all gl transactions!
                                           gltransactiondelete($voucherid);
                                           gltransvoucherdelete($voucherid);
                                           billdeleteadd($apbillid);
                                           billdeletebybillid($apbillid);
                                           $conn->RollbackTrans();
                                           die(texterror($lang['STR_ERROR_ADDING_BILL']));
                                      };
                                 };
                              };
                         };
                         $conn->CommitTrans();
                         echo textsuccess($lang['STR_BILL_ADDED_SUCCESSFULLY']);
                    };
               } else {
                    echo texttitle('<font size="-1">'.$lang['STR_VENDOR'].' '.$vendorname.' - '.$lang['STR_INVOICE_NUMBER'].$invoicenumber.' - '.$lang['STR_DATE'].' '.$dateofinvoice.'</font>');
                    echo '<form action="apbilladd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
                    echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
                    echo '<input type="hidden" name="vendorname" value="'.$vendorname.'">';
                    echo '<input type="hidden" name="invoicenumber" value="'.$invoicenumber.'">';
                    echo '<input type="hidden" name="dateofinvoice" value="'.$dateofinvoice.'">';
                    echo '<input type="hidden" name="duedate" value="'.$duedate.'">';
                    echo '<input type="hidden" name="description" value="'.$description.'">';
                    echo '<input type="hidden" name="discountamount" value="'.$discountamount.'">';
                    echo '<input type="hidden" name="discountdate" value="'.$discountdate.'">';
                    for ($i=1; isset(${"amount".$i})&&!${"amount".$i}==""; $i++); //do nothing, just increment $i
                    echo '<table><tr><th>'.$lang['STR_AMOUNT'].'</th><th>'.$lang['STR_GL_ACCOUNT'].'</th></tr>';
                    echo '<tr><td><input type="text" name="amount'.$i.'" onchange="validatenum(this)" size="15" maxlength="20"'.INC_TEXTBOX.'></td>';
                    $recordSet = &$conn->Execute("select defaultglacctid from vendor where id=".sqlprep($vendorid));
                    if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]) $defaultgl=$recordSet->fields[0];
                    echo '<td><select name="glaccountid'.$i.'"'.INC_TEXTBOX.'>';
                    $recordSet = &$conn->Execute('select glaccount.id, glaccount.name, glaccount.description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
                    while ($recordSet&&!$recordSet->EOF) {
                         echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],$defaultgl,' selected').'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
                         $recordSet->MoveNext();
                    };
                    echo '</select></td></tr>';
                    $addtotal=0;
                    for ($i=1; isset(${"amount".$i})&&!${"amount".$i}==""; $i++) { //fill in boxes for previous bill details
                         echo '<tr><td><input type="text" name="amount'.$i.'" onchange="validatenum(this)" size="15" maxlength="20" value="'.checkdec(${"amount".$i},PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td>';
                         echo '<td><select name="glaccountid'.$i.'"'.INC_TEXTBOX.'>';
                         $recordSet = &$conn->Execute("select glaccount.id,glaccount.name, glaccount.description from glaccount where (companyid=0 or companyid=".sqlprep($active_company).") order by glaccount.name");
                         while ($recordSet&&!$recordSet->EOF) {
                              echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],${"glaccountid".$i}," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
                              $recordSet->MoveNext();
                         };
                         echo '</select></td></tr>';
                         $addtotal+=${"amount".$i};
                    };
                    echo '<tr><td><div align="right"><b>'.$lang['STR_CURRENT_TOTAL'].':</b></div></td><td>'.CURRENCY_SYMBOL.checkdec($addtotal,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                    echo '<tr><td><div align="right"><b>'.$lang['STR_NEEDED_TOTAL'].':</b></div></td><td><input type="text" size="10" maxlength="15" name="total" value="'.checkdec($total,PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td><div align="right"><b>'.$lang['STR_DIFFERENCE'].':</b></div></td><td>'.CURRENCY_SYMBOL.checkdec($total-$addtotal,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                    echo '<input type="hidden" name="maxid" value="'.$i.'">';
                    echo '</table><input type="submit" value="'.$lang['STR_CONTINUE'].'">';
                    echo checkequal($total,$addtotal,'<input type="submit" name="submit" value="'.$lang['STR_COMPLETE_BILL'].'">');
                    echo '</form>';
               };
          } else {
               $recordSet = &$conn->Execute('select vendor.defaultbilldescription, vendor.defaultglacctid, vendor.customeraccount, vendor.paytocompanyid, company.companyname, invoiceterms.discountpercent, invoiceterms.discountdayofmonth, invoiceterms.discountdays, invoiceterms.netduedays, invoiceterms.verbal, vendor.paytermsid from vendor,invoiceterms,company where vendor.id='.sqlprep($vendorid).' and vendor.paytocompanyid=company.id and vendor.paytermsid=invoiceterms.id');
               if ($recordSet&&!$recordSet->EOF) {
                echo texttitle('<font size="-1">Vendor '.$recordSet->fields[4].' - '.$lang['STR_INVOICE_NUMBER'].''.$invoicenumber.'</font>');
                echo '<form action="apbilladd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
                echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
                //read in terms info for calculating discount, net, etc.
                $defaultbilldescription=$recordSet->fields[0];
                $payvendorid=$recordSet->fields[3];
                $vendorname=$recordSet->fields[4];
                $vendorpaytermsid=$recordSet->fields[10];
                $prepaid=0;
                $discountdate=billdiscountdate($vendorpaytermsid,$dateofinvoice);
                $duedate=billduedate($vendorpaytermsid,$dateofinvoice);
               } else {
                 die (texterror($lang['STR_ERROR_VENDOR_NOT_FOUND']));
               };
               echo '<input type="hidden" name="invoicenumber" value="'.$invoicenumber.'">';
               echo '<input type="hidden" name="vendorname" value="'.$vendorname.'">';
               echo '<input type="hidden" name="total" value="'.$total.'">';
               echo '<input type="hidden" name="dateofinvoice" value="'.$dateofinvoice.'">';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DESCRIPTION'].':</td><td><input type="text" name="description" size="30" maxlength="20" value="'.$defaultbilldescription.'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_AMOUNT'].':</td><td><input type="text" name="discountamount" onchange="validatenum(this)" size="30" maxlength="15" value="'.num_format(billdiscountamount($vendorpaytermsid,$total),2).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_DATE'].': </td><td><input type="text" name="discountdate" onchange="formatDate(this)" size="30" value="'.$discountdate.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.discointdate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DUE_DATE'].': </td><td><input type="text" name="duedate"  onchange="formatDate(this)" size="30" value="'.$duedate.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.duedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
               echo '</table><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
          };
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
          $today=date("Y-m-d", $timestamp);
          $timestamp =  mktime($hour, $minute, $second, $month+1, $day, $year);
          $duedate=date("Y-m-d", $timestamp);
          echo '<form action="apbilladd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_NUMBER'].':</td align="'.TABLE_LEFT_SIDE_ALIGN.'"><td><input type="text" name="invoicenumber" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          formapvendorselect('vendorid');
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TOTAL'].': </td align="'.TABLE_LEFT_SIDE_ALIGN.'"><td><input type="text" name="total" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_DATE'].': </td><td><input type="text" name="dateofinvoice" onchange="formatDate(this)" size="30" value="'.$today.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.dateofinvoice\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
