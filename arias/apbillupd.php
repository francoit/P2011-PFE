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
<?php   echo '<center>';
     echo texttitle($lang['STR_BILL_UPDATE']);
     if ($delete&&$apbillid) {
          $recordSet=$conn->Execute('select total,invoicenumber,description from apbill where cancel=0 and id='.sqlprep($apbillid));
          if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_COULD_NOT_FIND_BILL']));
          //read old values before update any
          $oldtotal=$recordSet->fields[0];
          $oldinvoice=$recordSet->fields[1];
          $olddescription=$recordSet->fields[2];
          $recordSet2=$conn->SelectLimit('select id, status from gltransvoucher where voucher='.sqlprep($oldinvoice).' and companyid='.sqlprep($active_company).' and cancel=0 and wherefrom=1 and substring(description,6)='.sqlprep($olddescription).' order by lastchangedate desc',1);
          if ($recordSet2&&!$recordSet2->EOF) {
                $oldglid=$recordSet2->fields[0];
                $voucherstatus=$recordSet2->fields[1];
          };
          if ($voucherstatus==0) {
                    //not yet posted, so delete old transactions completely
                    gltransactiondelete($oldglid) ;
                    // delete voucher also
                    gltransvoucherdelete($oldglid);
          } else {
                    //has been posted, so must post opposite of old
                    $recordSet=$conn->Execute('select amount,glaccountid from gltransaction where voucherid='.sqlprep($oldglid));
                    while ($recordSet&&!$recordSet->EOF) {
                          $namount=$recordSet->fields[0];
                          $namount=-$namount;
                          $nid=$recordSet->fields[1];
                          gltransactionadd($oldglid,$namount,$nid);
                          $recordSet->MoveNext();
                    };
          };
          $conn->Execute('update invreceive set apbillid=0,passtoap=0,itemqtyused=0 where apbillid='.sqlprep($apbillid));
          $conn->Execute('update apbill set cancel=1,canceldate=NOW(),canceluserid='.sqlprep($userid).' where id='.sqlprep($apbillid));
          echo textsuccess($lang['STR_BILL_DELTED_SUCCESSFULLY']);
     };
     if ($apbillid||$invoicenumber||$vendorid||$bgdateofinvoice||$eddateofinvoice||$bgduedate||$edduedate) {
          if (!$apbillid) {
               if ($invoicenumber) $invoicestr=' and apbill.invoicenumber='.sqlprep($invoicenumber);
               if ($vendorid) $vendorstr=' and apbill.vendorid='.sqlprep($vendorid);
               if ($bgdateofinvoice&&$eddateofinvoice) $dateofinvstr=' and apbill.dateofinvoice>='.sqlprep($bgdateofinvoice).' and apbill.dateofinvoice<='.sqlprep($eddateofinvoice);
               if ($bgduedate&&$edduedate) $duedatestr=' and apbill.duedate>='.sqlprep($bgduedate).' and apbill.duedate<='.sqlprep($edduedate);
               $recordSet = &$conn->Execute('select count(*) from apbill where apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company).$invoicestr.$vendorstr.$dateofinvstr.$duedatestr);
               if ($recordSet&&!$recordSet->EOF) {
                     if ($recordSet->fields[0]==1) {
                          $recordSet = &$conn->Execute('select apbill.id from apbill where apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company).$invoicestr.$vendorstr.$dateofinvstr.$duedatestr);
                          if (!$recordSet->EOF) $apbillid=$recordSet->fields[0];
                     } elseif ($recordSet->fields[0]>1) {
                       $recordSet = &$conn->Execute('select apbill.id,apbill.invoicenumber,apbill.description,apbill.entrydate,apbill.duedate,apbill.total,company.companyname from apbill,vendor,company where apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id and apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company).$invoicestr.$vendorstr.$dateofinvstr.$duedatestr.' order by apbill.id');
                       echo '<table border="1">';
                       echo '<tr><th>'.$lang['STR_INVOICE_NUMBER'].'</th><th>'.$lang['STR_VENDOR'].'</th><th>'.$lang['STR_DESCRIPTION'].'</th><th>'.$lang['STR_ENTRY_DATE'].'</th><th>'.$lang['STR_DUE_DATE'].'</th><th>'.$lang['STR_TOTAL'].'</th></tr>';
                       while (!$recordSet->EOF) {
                         echo '<tr><td><a href="apbillupd.php?apbillid='.$recordSet->fields[0].'">'.$recordSet->fields[1].'</a></td><td>'.$recordSet->fields[6].'</td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td>'.CURRENCY_SYMBOL.$recordSet->fields[5].'</td></tr>';
                         $recordSet->MoveNext();
                       };
                       echo '</table>';
                     } else {
                         die(texterror($lang['STR_NO_MATCHING_BILLS_FOUND']));
                     };
               } else {
                     die(texterror($lang['STR_NO_MATCHING_BILLS_FOUND']));
               };
          };
          if ($finishupdatemainbill) {
               if ($submit=="Complete Bill") {
                   //read general information first
                   $recordSet=&$conn->Execute('select payable,interestexpense,usetransactiondate from apcompany where id='.sqlprep($active_company));
                    if ($recordSet&&!$recordSet->EOF) {
                         $payable=$recordSet->fields[0];
                         $interestexpense=$recordSet->fields[1];
                         $usetransactiondate=$recordSet->fields[2];
                    };
                    //create post date
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

                    $postdate=$dateofinvoice;
                    if (!$usetransactiondate) $postdate=$today;

                  // need to check to see if any posting needs to be changed as a result
                  // of this change: change to the total amount requires a change
                  // in gltransvoucher for payables account (one entry reversing old,
                  // new entry to put in correction.
                  $recordSet=$conn->Execute('select total,invoicenumber,description from apbill where id='.sqlprep($apbillid));
                  if ($recordSet&&!$recordSet->EOF) {
                        //read old values before update any
                        $oldtotal=$recordSet->fields[0];
                        $oldinvoice=$recordSet->fields[1];
                        $olddescription=$recordSet->fields[2];
                        $recordSet2=$conn->SelectLimit('select id, status from gltransvoucher where voucher='.sqlprep($oldinvoice).' and companyid='.sqlprep($active_company).' and cancel=0 and wherefrom=1 and substring(description,6)='.sqlprep($olddescription).' order by lastchangedate desc',1);
                        if (!$recordSet2->EOF) {
                              $oldglid=$recordSet2->fields[0];
                              $voucherstatus=$recordSet2->fields[1];
                        };
                  } else {
                    die (TextError($lang['STR_COULD_NOT_FIND_BILL']));
                  };
                  if (billupd($apbillid,$invoicenumber,$total,$description,$dateofinvoice,$duedate,$discountamount,$discountdate,$vendorid,1,0)) {
                      //now update payables for changes
                      if ($voucherstatus==0) {
                           //not yet posted, so delete old transactions completely
                          gltransactiondelete($oldglid) ;
                          // delete voucher also
                          gltransvoucherdelete($oldglid);
                      } else {
                           //has been posted, so must post opposite of old
                           $recordSet=$conn->Execute('select amount,glaccountid from gltransaction where voucherid='.sqlprep($oldglid));
                           while ($recordSet&&!$recordSet->EOF) {
                                    $namount=$recordSet->fields[0];
                                    $namount=-$namount;
                                    $nid=$recordSet->fields[1];
                                    gltransactionadd($oldglid,$namount,$nid);
                                    $recordSet->MoveNext();
                           };
                      };
                      // create new voucher entry
                      $voucherid=gltransvoucheradd($invoicenumber,$description,$postdate,1);
                      if (!$voucherid) die(texterror($lang['STR_DID_NOT_GET_A_NEW_VOUCHER_ID']));
                      $fail=gltransactionadd($voucherid,-$total, $payable);
                      for ($i=1; $i<=$maxid; $i++) {
                               if (${"detailid".$i}>0) { // this is an existing entry, not a new line-item
                                    $recordSet=$conn->Execute('select amount, glaccountid, invreceiveid from apbilldetail where id='.sqlprep(${"detailid".$i}));
                                    if (!$recordSet->EOF) { //first update detail record to new amount/account
                                        $id=billdetailupd($apbillid,${"amount".$i},${"glaccountid".$i},${"detailid".$i});
                                    };
                               } else { // this is a new entry, need to create new entry only if not a zero amount
                                    if (!${"amount".$i}==0){
                                         $id=billdetailadd($apbillid,$amount,$glaccountid,0);
                                    };
                               };
                               if (!${"amount".$i}==0){ //now add transaction
                                      $fail=gltransactionadd($voucherid,${"amount".$i},${"glaccountid".$i});
                               };
                         };
                         echo textsuccess($lang['STR_BILL_UPDATED_SUCCESSFULLY']);
                  };
               } else {
                    echo '<form action="apbillupd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
                    echo '<input type="hidden" name="apbillid" value="'.$apbillid.'">';
                    echo '<input type="hidden" name="finishupdatemainbill" value="1">';
                    echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
                    echo '<input type="hidden" name="invoicenumber" value="'.$invoicenumber.'">';
                    echo '<input type="hidden" name="dateofinvoice" value="'.$dateofinvoice.'">';
                    echo '<input type="hidden" name="duedate" value="'.$duedate.'">';
                    echo '<input type="hidden" name="description" value="'.$description.'">';
                    echo '<input type="hidden" name="discountamount" value="'.$discountamount.'">';
                    echo '<input type="hidden" name="discountdate" value="'.$discountdate.'">';
                    echo '<table><tr><th>'.$lang['STR_AMOUNT'].'</th><th>'.$lang['STR_GL_ACCOUNT'].'</th></tr>';
                    $addtotal=0;
                    if ($maxid>0) {
                        for ($i;$i<=$maxid;$i++) {
                              $addtotal+=${"amount".$i};
                        };
                    } else {
                      $i=0;
                      $recordSet = &$conn->Execute("select apbilldetail.amount, apbilldetail.glaccountid, apbilldetail.invreceiveid, apbilldetail.id from apbilldetail where apbilldetail.apbillid=".sqlprep($apbillid)." order by apbilldetail.id");
                      while (!$recordSet->EOF) {
                               $i++;
                               $maxid=$i+1;
                               ${"amount".$i} =checkdec($recordSet->fields[0],PREFERRED_DECIMAL_PLACES);
                               ${"glaccountid".$i}=$recordSet->fields[1];
                               ${"detailid".$i}=$recordSet->fields[3];
                               $addtotal+=$recordSet->fields[0];
                               $recordSet->MoveNext();
                      };
                    };
                    $i=1;
                    for ($i;$i<=$maxid;$i++) {
                         echo '<tr><td><input type="text" name="amount'.$i.'" onchange="validatenum(this)" size="15" maxlength="20" value="'.${"amount".$i}.'"'.INC_TEXTBOX.'></td>';
                         echo '<input type="hidden" name="detailid'.$i.'" value="'.${"detailid".$i}.'">';
                         echo '<td><select name="glaccountid'.$i.'"'.INC_TEXTBOX.'>';
                         $recordSet2 = &$conn->Execute('select glaccount.id,glaccount.name, glaccount.description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
                         while (!$recordSet2->EOF) {
                              echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],${"glaccountid".$i}," selected").'>'.$recordSet2->fields[1].' - '.$recordSet2->fields[2]."\n";
                              $recordSet2->MoveNext();
                         };
                         echo '</select></td></tr>';
                    };
                    echo '<tr><td><div align="right"><b>'.$lang['STR_CURRENT_TOTAL'].':</b></div></td><td>'.CURRENCY_SYMBOL.checkdec($addtotal,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                    echo '<tr><td><div align="right"><b>'.$lang['STR_NEEDED_TOTAL'].':</b></div></td><td><input type="text" size="10" maxlength="15" name="total" onchange="validatenum(this)" value="'.checkdec($total,PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td><div align="right"><b>'.$lang['STR_DIFFERENCE'].':</b></div></td><td>'.CURRENCY_SYMBOL.checkdec($total-$addtotal,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                    echo '<input type="hidden" name="maxid" value="'.$maxid.'">';
                    echo '</table><input type="submit" name="submit" value="'.$lang['STR_SAVE_AND_REDISPLAY'].'">';
                    echo checkequal($total,$addtotal,'<input type="submit" name="submit" value="'.$lang['STR_COMPLETE_BILL'].'">');
                    echo '</form>';
               };
          } elseif ($apbillid) {
               $recordSet = &$conn->Execute('select apbill.id,apbill.invoicenumber,apbill.description,apbill.dateofinvoice,apbill.duedate,apbill.total,apbill.vendorid,apbill.discountamount,apbill.discountdate,company.companyname from apbill,vendor,company where apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id and apbill.cancel=0 and apbill.complete=0 and apbill.gencompanyid='.sqlprep($active_company).' and apbill.id='.sqlprep($apbillid));
               if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_BILL_ID_NUMBER'] .$apbillid. $lang['STR_NOT_FOUND']));
               echo '<form action="apbillupd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><input type="hidden" name="finishupdatemainbill" value="1"><input type="hidden" name="apbillid" value="'.$apbillid.'"><table>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_NUMBER'].':</td align="'.TABLE_LEFT_SIDE_ALIGN.'"><td><input type="text" name="invoicenumber" size="30" maxlength="20" value="'.$recordSet->fields[1].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VENDOR_NUMBER'].':</td><td><input type="text" length="20" maxsize="30" name="vendorid" onchange="validateint(this)" value="'.$recordSet->fields[6].'"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupvendor.php?name='.$name.'\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="'.IMAGE_VENDOR_LOOKUP.'" border="0" alt="Vendor Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'apvendadd.php\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="'.IMAGE_VENDOR_ADD.'" border="0" alt="Vendor Add"></a><font size="-1"> ('.$recordSet->fields[9].')</font></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TOTAL'].':</td align="'.TABLE_LEFT_SIDE_ALIGN.'"><td><input type="text" name="total" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[5].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DATE_OF_INVOICE'].': </td><td><input type="text" name="dateofinvoice" onchange="formatDate(this)" size="30" value="'.$recordSet->fields[3].'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.dateofinvoice\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DUE_DATE'].': </td><td><input type="text" name="duedate" onchange="formatDate(this)" size="30" value="'.$recordSet->fields[4].'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.duedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DESCRIPTION'].':</td align="'.TABLE_LEFT_SIDE_ALIGN.'"><td><input type="text" name="description" size="30" maxlength="20" value="'.$recordSet->fields[2].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_AMOUNT'].':</td align="'.TABLE_LEFT_SIDE_ALIGN.'"><td><input type="text" name="discountamount" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[7].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_DATE'].': </td><td><input type="text" name="discountdate" onchange="formatDate(this)" size="30" value="'.$recordSet->fields[8].'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.discountdate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
               echo '</table><input type="submit" name="submit" value="'.$lang['STR_GO_TO_DETAILS'].'"></form>';
               $recordSet = &$conn->Execute('select count(*) from apbillpayment where apbillid='.sqlprep($apbillid));
               if ($recordSet&&!$recordSet->EOF) if (!$recordSet->fields[0]) echo '<a href="javascript:confirmdelete(\'apbillupd.php?delete=1&apbillid='.$apbillid.'\')">Delete this bill</a>';


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
          $timestamp =  mktime($hour, $minute, $second, $month-1, $day, $year);
          $monthago=date("Y-m-d", $timestamp);
          echo '<form action="apbillupd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_NUMBER'].':</td align="'.TABLE_LEFT_SIDE_ALIGN.'"><td><input type="text" name="invoicenumber" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          formapvendorselect('vendorid');
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_DATE_OF_INVOICE'].': </td><td><input type="text" name="bgdateofinvoice" onchange="formatDate(this)" size="30" value="'.$monthago.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.bgdateofinvoice\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_DATE_OF_INVOICE'].': </td><td><input type="text" name="eddateofinvoice" onchange="formatDate(this)" size="30" value="'.$today.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.eddateofinvoice\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_DUE_DATE'].': </td><td><input type="text" name="bgduedate" onchange="formatDate(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.bgduedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_DUE_DATE'].': </td><td><input type="text" name="edduedate" onchange="formatDate(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.edduedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '</table><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
          
          echo '</center>';
       };
?>

<?php include('includes/footer.php'); ?>
