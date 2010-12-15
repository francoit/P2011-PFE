<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?php include('includes/glfunctions.php'); ?>
<?php include('includes/prfunctions.php'); ?>
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
     echo texttitle($lang['STR_INVENTORY_PO_PASS_TO_PAYABLES']);
     if ($showlist) {
          if (!$dateofinvoice||!$invoicenumber||!$vendorid) die(texterror($lang['STR_MUST_HAVE_A_VENDOR_INVOICE_DATE_AND_INVOICE_NUMBER']));
          if ($vendorid) $vendorstr=' and invreceive.vendorid='.sqlprep($vendorid);
          if ($locationid) $locationidstr=' and invreceive.locationid='.sqlprep($locationid);
          echo '<form action="invpotoap.php" method="post" name="mainform">';
          $recordSet = &$conn->Execute('select count(*) from invreceive,invpo,item where invreceive.passtoap=0 and item.id=invreceive.itemid and invreceive.gencompanyid='.sqlprep($active_company).$vendorstr.$locationidstr.' and invpo.id=invreceive.invpoid');
          if ($recordSet->fields[0]>0) {
               $recordSet1 = &$conn->Execute('select  invreceive.itemid,invreceive.itemqty,invreceive.itemprice,invreceive.conversion,invreceive.receivedate,invreceive.invpoid,invpo.ponumber,item.itemcode,item.description,invreceive.id,item.inventoryglacctid from invreceive,invpo,item where invreceive.passtoap=0 and item.id=invreceive.itemid and invreceive.gencompanyid='.sqlprep($active_company).$vendorstr.$locationidstr.' and invpo.id=invreceive.invpoid order by item.itemcode, invreceive.receivedate');
               echo '<table border="1"><tr><th>'.$lang['STR_INCLUDE_QUESTION_MARK'].'</th><th>'.$lang['STR_PO_NUMBER'].'</th><th>'.$lang['STR_ITEM'].'</th><th>'.$lang['STR_RECEIVED'].'</th><th>'.$lang['STR_QTY'].'</th><th>'.$lang['STR_PRICE_PER_UNIT'].'</th></tr>';
               $dcount=0;
               while (!$recordSet1->EOF) {
                    $dcount+=1;
                    echo '<tr><td><input type="checkbox"  name="check'.$dcount.'" value="1" onclick="POsum()"'.checkequal(${"check".$dcount},1,' checked').'></td>'; // onChange="addtotal(this)"></td>';
                    echo '<input type="hidden" name="invreceiveid'.$dcount.'" value="'.$recordSet1->fields[9].'">';
                    echo '<input type="hidden" name="glacctid'.$dcount.'" value="'.$recordSet1->fields[10].'">';
                    echo '<input type="hidden" name="conversion'.$dcount.'" value="'.$recordSet1->fields[3].'">';
                    echo '<input type="hidden" name="invpoid'.$dcount.'" value="'.$recordSet1->fields[5].'">';
                    echo '<td>'.$recordSet1->fields[6].'</td><td>'.$recordSet1->fields[7].' - '.$recordSet1->fields[8].'</td>';
                    echo '<td>'.$recordSet1->fields[4].'</td><td><input type="text" name="itemqty'.$dcount.'" onchange="validatenum(this)" value="'.checkdec($recordSet1->fields[1],0).'" onchange="POsum()" size="20" maxlength="20" '.INC_TEXTBOX.'></td>';
                    echo '<td><nobr><input type="text" name="price'.$dcount.'" onchange="validatenum(this)" value="'.checkdec($recordSet1->fields[2],PREFERRED_DECIMAL_PLACES).'" onchange="POsum()" size="20" maxlength="20" '.INC_TEXTBOX.'></nobr></td></tr>';
                    $recordSet1->MoveNext();
               };
            echo '<input type="hidden" name="invoicenumber" value="'.$invoicenumber.'">';
            echo '<input type="hidden" name="dateofinvoice" value="'.$dateofinvoice.'">';
            echo '<input type="hidden" name="description" value="'.$dateofinvoice.'">';
            echo '<script language="JavaScript">'."\n";
            echo '      function POsum() {'."\n";
            echo '            numb = "0"'."\n";
            for ($i=1; $i<=$dcount; $i++) echo '          numb = eval(numb) + round(eval(document.mainform.itemqty'.$i.'.value) * eval(document.mainform.price'.$i.'.value) * eval(document.mainform.check'.$i.'.checked),'.PREFERRED_DECIMAL_PLACES.")\n";
            echo '            document.mainform.billtotal.value="'.CURRENCY_SYMBOL.'" + numb'."\n";
            echo '      }'."\n";
            echo '      function round(number,X) {'."\n";
            echo '            X = (!X ? 0 : X);'."\n";
            echo '            return Math.round(number*Math.pow(10,X))/Math.pow(10,X);'."\n";
            echo '      }'."\n";
            echo '</script>'."\n";
            echo '<td>Total '.CURRENCY_SYMBOL.' selected:</td><td colspan="2"><input type="text" disabled name="billtotal" value="'.CURRENCY_SYMBOL.'0" onchange="POsum()" size="20" maxlength="20" '.INC_TEXTBOX.'></td></tr></table>';
            echo '<input type="hidden" name="dcount" value="'.$dcount.'">';
            echo '<input type="hidden" name="locationid" value="'.$locationid.'">';
            echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
            echo '<input type="submit" name="endselections" value="'.$lang['STR_END_SELECTIONS'].'"></form>';
          } else {
            //none match
            echo texterror($lang['STR_NO_RECEIPTS_FOUND_FOR_THIS_VENDOR']);
          };
     } else if ($endselections) {
          $billtotal=0;
          for ($d=1; $d<=$dcount; $d++) {
               if (${"check".$d}==1) {
                  $billtotal+=${"price".$d}*${"itemqty".$d};
               };
          };

          // now ask for header bill info (invoice#, invoice date, freight
          // additional, sales tax additional, etc.
          echo '<form action="invpotoap.php" method="post" name="mainform"><table>';
          $recordSet = &$conn->Execute('select vendor.defaultbilldescription, vendor.defaultglacctid, vendor.customeraccount, vendor.paytocompanyid, company.companyname, invoiceterms.discountpercent, invoiceterms.discountdayofmonth, invoiceterms.discountdays, invoiceterms.netduedays, invoiceterms.verbal, vendor.paytermsid from vendor,invoiceterms,company where vendor.id='.sqlprep($vendorid).' and vendor.paytocompanyid=company.id and vendor.paytermsid=invoiceterms.id');
          if (!$recordSet->EOF) {
                //read in terms info for calculating discount, net, etc.
                $defaultbilldescription=$recordSet->fields[0];
                $defaultglacctid=$recordSet->fields[1];
                $customeraccount=$recordSet->fields[2];
                $payvendorid=$recordSet->fields[3];
                $vendorname=$recordSet->fields[4];
                $discountpercent=$recordSet->fields[5];
                $discountdayofmonth=$recordSet->fields[6];
                $discountdays=$recordSet->fields[7];
                $netduedays=$recordSet->fields[8];
                $verbalterms=$recordSet->fields[9];
                $vendorpaytermsid=$recordSet->fields[10];
                $prepaid=0;
                $discountamount=$billtotal*$discountpercent/100;
                $discountdate=billdiscountdate($vendorpaytermsid,$dateofinvoice);
                $duedate=billduedate($vendorpaytermsid,$dateofinvoice);
                //display vendor name and terms at top of screen
                echo '<tr><th colspan="2">'.$lang['STR_VENDOR'].': '.$vendorname.'</th></tr>';
                echo '<tr><th colspan="2">'.$lang['STR_TERMS'].': '.$verbalterms.'</th></tr> ';
                echo '<tr><th colspan="2">'.$lang['STR_INVOICE_DATE'].': '.$dateofinvoice.'</th></tr>';
                echo '<tr><th colspan="2">'.$lang['STR_INVOICE_NUMBER'].': '.$invoicenumber.'</th></tr><tr><td></td></tr>';
                echo '<input type="hidden" name="dateofinvoice" value="'.$dateofinvoice.'">';
                echo '<input type="hidden" name="description" value="'.$description.'">';
                echo '<input type="hidden" name="invoicenumber" value="'.$invoicenumber.'">';
                echo '<input type="hidden" name="dcount" value="'.$dcount.'">';
                echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
                for ($d=1; $d<=$dcount; $d++) {
                      echo '<input type="hidden" name="check'.$d.'" value="'.${"check".$d}.'">';
                      echo '<input type="hidden" name="price'.$d.'" value="'.${"price".$d}.'">';
                      echo '<input type="hidden" name="glacctid'.$d.'" value="'.${"glacctid".$d}.'">';
                      echo '<input type="hidden" name="itemqty'.$d.'" value="'.${"itemqty".$d}.'">';
                      echo '<input type="hidden" name="invreceiveid'.$d.'" value="'.${"invreceiveid".$d}.'">';
                      echo '<input type="hidden" name="conversion'.$d.'" value="'.${"conversion".$d}.'">';
                      echo '<input type="hidden" name="invpoid'.$d.'" value="'.${"invpoid".$d}.'">';
               };

                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"><b>'.$lang['STR_TOTAL_PURCHASE_ORDERS_RECEIVED'].':</b></td><th>'.number_format($billtotal,2).'</th></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FREIGHT'].':</td><td><input type="text" name="freight" onchange="validatenum(this)" value="'.number_format($freight,2).'" size="20" maxlength="20" '.INC_TEXTBOX.'>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX'].':</td><td><input type="text" name="tax" onchange="validatenum(this)" value="'.number_format($tax,2).'" size="20" maxlength="20" '.INC_TEXTBOX.'> ';
                echo '<tr></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_DATE'].':</td><td><input type="text" name="discountdate" onchange="formatDate(this)" value="'.$discountdate.'" size="20" maxlength="20" '.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.discountdate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="images/calendar.gif" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DUE_DATE'].':</td><td><input type="text" name="duedate" onchange="formatDate(this)" value="'.$duedate.'" size="20" maxlength="20" '.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.duedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="images/calendar.gif" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_AMOUNT'].':</td><td><input type="text" name="discountamount" onchange="validatenum(this)" value="'.number_format($discountamount,2).'" size="20" maxlength="20" '.INC_TEXTBOX.'> ';
                $a="unchecked" ;
                if ($prepaid==1) $a="checked";
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PRE_PAID'].':</td><td><input type="checkbox" name="prepaid" value="'.$prepaid.'" '.$a.' </td></tr>';
                echo '</table>';
                echo '<input type="submit" name="saveinvoice" value="'.$lang['STR_SAVE_INVOICE'].'"></form>';

          } else {
              die (texterror($lang['STR_VENDOR_DATA_NOT_FOUND']));
          };



     } else if ($saveinvoice) {
                    //read general ap posting accounts first
                    $recordSet=&$conn->Execute('select payable,interestexpense,usetransactiondate from apcompany where id='.sqlprep($active_company));
                    if (!$recordSet->EOF) {
                         $payable=$recordSet->fields[0];
                         $interestexpense=$recordSet->fields[1];
                         $usetransactiondate=$recordSet->fields[2];
                    };
                    $recordSet=&$conn->Execute('select freight,tax from invcompany where id='.sqlprep($active_company));
                    if (!$recordSet->EOF) {
                         $glfreight=$recordSet->fields[0];
                         $gltax=$recordSet->fields[1];
                    };
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
           // add up invoice totals
          $billtotal=0;
          for ($d=1; $d<=$dcount; $d++) {
               if (${"check".$d}==1) {
                  $billtotal+=${"price".$d}*${"itemqty".$d};
               };
          };
          $billtotal+=$tax+$freight;
           // save to apbill
           $wherefrom=1;
           $apbillid=billadd($invoicenumber,$billtotal,$description,$dateofinvoice,$duedate,$discountamount,$discountdate,$vendorid,$wherefrom,$cancel);
           if ($apbillid) {
              //first add general information to gltransvoucher
              $voucherid=gltransvoucheradd($invoicenumber,$description,$postdate,1);
              if (!$voucherid) {
                 billdeleteadd($apbillid);
                 die(texterror($lang['STR_ERROR_ADDING_GLTRANSVOUCHER_RECORD_-_BILL_ENTRY_CANCELLED']));
              };
              //write info to gltransaction file for payables side of post
              $fail=gltransactionadd($voucherid, -$billtotal,$payable);
              if (!$fail) {
                 //need to remove all gl transactions!
                 gltransvoucherdelete($voucherid);
                 billdeleteadd($apbillid);
                 die(texterror($lang['STR_ERROR_ADDING_GL_TRANSACTION_DETAILS_BILL_ENTRY_CANCELLED']));
              };
           } else {
              if (!$apbillid) die(texterror($lang['STR_ERROR_CREATING_PAYABLES_INVOICE_ENTRY_ENTRIES_CANCELLED']));
           };
           // save to apbilldetail
           for ($d=1; $d<=$dcount; $d++) {
               if (${"check".$d}==1) {  // only those items checked!
                  if (!billdetailadd($apbillid,${"price".$d}*${"itemqty".$d},${"glacctid".$d},${"invreceiveid".$d})) {
                     //need to remove all gl transactions!
                     gltransactiondelete($voucherid);
                     gltransvoucherdelete($voucherid);
                     billdeleteadd($apbillid);
                     billdetetebybillid($apbillid);
                     die(texterror($lang['STR_ERROR_ADDING_BILL_DETAILS_BILL_ENTRY_CANCELLED']));
                  } else {
                     //need to write info to gltransaction file for details
                     $fail=gltransactionadd($voucherid, ${"price".$d}*${"itemqty".$d},${"glacctid".$d});
                     if (!$fail) {
                              //need to remove all gl transactions!
                              gltransactiondelete($voucherid);
                              gltransvoucherdelete($voucherid);
                              billdeleteadd($apbillid);
                              billdeletebybillid($apbillid);
                              die(texterror($lang['STR_ERROR_ADDING_GL_TRANSACTION_DETAILS_BILL_ENTRY_CANCELLED']));
                     };
                  };
                  // update flag in invreceive file to show it has been billed and correct price, qty
                  if ($conn->Execute('update invreceive set itemqtyused='.sqlprep(${"itemqty".$d}).', itemprice='.sqlprep(${"price".$d}).', passtoap=1, apbillid='.sqlprep($apbillid).' where id='.sqlprep(${"invreceiveid".$d})) == false) {
                     echo texterror("Error receive record.");
                     gltransactiondelete($voucherid);
                     gltransvoucherdelete($voucherid);
                     billdeleteadd($apbillid);
                     billdeletebybillid($apbillid);
                     if ($conn->Execute('update invreceive set itemqtyused=0,apbillid=null where apbillid='.sqlprep($apbillid))==false) {
                     };
                     die(texterror($lang['STR_ERROR_UPDATING_RECEIVE_ERROR_BILL_ENTRY_CANCELLED']));
                   };
               };
               //save freight to apbilldetail
               if (!$freight==0) {
                 if (!billdetailadd($apbillid,$freight,$glfreight,0)) {
                     //need to remove all gl transactions!
                     gltransactiondelete($voucherid);
                     gltransvoucherdelete($voucherid);
                     billdeleteadd($apbillid);
                     billdetetebybillid($apbillid);
                     die(texterror($lang['STR_ERROR_ADDING_BILL_DETAILS_BILL_ENTRY_CANCELLED']));
                 } else {
                     //write info to gltransaction file for freight expense
                     $fail=gltransactionadd($voucherid, $freight,$glfreight);
                     if (!$fail) {
                          //need to remove all gl transactions!
                          gltransactiondelete($voucherid);
                          gltransvoucherdelete($voucherid);
                          billdeleteadd($apbillid);
                          die(texterror($lang['STR_ERROR_ADDING_GL_TRANSACTION_DETAILS_BILL_ENTRY_CANCELLED']));
                     };
                 };

               };
               //save tax to apbilldetail
               if (!$tax==0) {
                 if (!billdetailadd($apbillid,$tax,$gltax,0)) {
                     //need to remove all gl transactions!
                     gltransactiondelete($voucherid);
                     gltransvoucherdelete($voucherid);
                     billdeleteadd($apbillid);
                     billdetetebybillid($apbillid);
                     die(texterror($lang['STR_ERROR_ADDING_BILL_DETAILS_BILL_ENTRY_CANCELLED']));
                 } else {
                     //write info to gltransaction file for tax expense
                     $fail=gltransactionadd($voucherid, $tax,$gltax);
                     if (!$fail) {
                          //need to remove all gl transactions!
                          gltransactiondelete($voucherid);
                          gltransvoucherdelete($voucherid);
                          billdeleteadd($apbillid);
                          die(texterror($lang['STR_ERROR_ADDING_GL_TRANSACTION_DETAILS_BILL_ENTRY_CANCELLED']));
                     };
                 };
               };

           };

           // if paid flag set, then need to ask payment details AFTER save

     } else {
          echo '<form action="invpotoap.php" method="post" name="mainform"><table>';
          $recordSet = &$conn->Execute('select count(*) from vendor, company where vendor.gencompanyid='.sqlprep($active_company).' and vendor.orderfromcompanyid=company.id and vendor.cancel=0');
          if (!$recordSet->EOF) {
                if ($recordSet->fields[0]>1) {
                     formapvendorselect('vendorid');
                } elseif ($recordSet->fields[0]>0) {
                  $recordSet = &$conn->Execute('select vendor.id from vendor, company where vendor.gencompanyid='.sqlprep($active_company).' and vendor.orderfromcompanyid=company.id and vendor.cancel=0 order by company.companyname');
                  if (!$recordSet->EOF) {
                        $vendorid=$recordSet->fields[0];
                        $recordSet->MoveNext() ;
                  };
                  echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
                } else {
                  die (texterror($lang['STR_NO_VENDORS_IN_FILE']));
                };
          } else {
                  die (texterror($lang['STR_NO_VENDORS_IN_FILE']));
          };
          $recordSet = &$conn->Execute('select count(*) from inventorylocation,company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company));
          if (!$recordSet->EOF) {
            if ($recordSet->fields[0]>1) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LOCATION'].':</td><td><select name="locationid"'.INC_TEXTBOX.'><option value="0">';
               $recordSet = &$conn->Execute('select inventorylocation.id,company.companyname from inventorylocation,company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
               while (!$recordSet->EOF) {
                    echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                    $recordSet->MoveNext();
               };
               echo '</select></td></tr>';
            } elseif ($recordSet->fields[0]>0) {
                  $recordSet = &$conn->Execute('select inventorylocation.id,company.companyname from inventorylocation,company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
                  if (!$recordSet->EOF) {
                        $locationid=$recordSet->fields[0];
                        $recordSet->MoveNext() ;
                  };
                  echo '<input type="hidden" name="locationid" value="'.$locationid.'">';
            } else {
                  die (texterror($lang['STR_NO_LOCATIONS_IN_FILE']));
            };
          } else {
             die (texterror($lang['STR_NO_LOCATIONS_IN_FILE']));
          };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_NUMBER'].':</td><td><input type="text" name="invoicenumber" value="'.$invoicenumber.'" size="20" maxlength="20" '.INC_TEXTBOX.'> ';
          if (!$dateofinvoice) $dateofinvoice=createtime('Y-m-d');
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_DATE'].':</td><td><input type="text" name="dateofinvoice" value="'.$dateofinvoice.'" size="20" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.dateofinvoice\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="images/calendar.gif" border="0" alt="Display Calendar"></a></td></tr>';
          echo '</table><input type="submit" name="showlist" value="'.$lang['STR_SHOW_LIST'].'"></form>';
          echo '</center>';
     };

?>
<?php include('includes/footer.php'); ?>
