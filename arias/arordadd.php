<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<script language="JavaScript">
     var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?php //arordadd.php
     if ($custcompanyid) { //if external customer
          $customerid=$custcompanyid; //only allow them to edit their info
     };
     echo texttitle($lang['STR_ORDER_ADD']);
     echo '<center>';
     if ($customerid&&$inventorylocationid&&!$shiptocompanyid) { //if there is only one ship to, don't ask, just set it
          $recordSet = &$conn->Execute('select count(*) from company,shipto,customer where company.id=shipto.shiptocompanyid and shipto.companyid=customer.companyid and customer.id='.sqlprep($customerid).' and shipto.cancel=0 and company.cancel=0');
          if (!$recordSet->EOF) {
               if ($recordSet->fields[0]==1) {
                    $recordSet = &$conn->SelectLimit('select company.id from company,shipto,customer where company.id=shipto.shiptocompanyid and shipto.companyid=customer.companyid and customer.id='.sqlprep($customerid).' and shipto.cancel=0 and company.cancel=0',1);
                    if (!$recordSet->EOF) $shiptocompanyid=$recordSet->fields[0];
               };
          };
     };
     $recordSet = &$conn->Execute('select custoritemglacct from invcompany where id='.sqlprep($active_company));
     if (!$recordSet->EOF) $custoritem=$recordSet->fields[0];

     if ($customerid&&$inventorylocationid&&$shiptocompanyid&&!$completeorder) { //if the user has submitted initial info
          echo '<form action="arordadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table border=0>';
          echo '<input type="hidden" name="customerid" value="'.$customerid.'">';
          echo '<input type="hidden" name="inventorylocationid" value="'.$inventorylocationid.'">';
          echo '<input type="hidden" name="shiptocompanyid" value="'.$shiptocompanyid.'">';
          echo '<input type="hidden" name="duedate" value="'.$duedate.'">';
          echo '<input type="hidden" name="ponumber" value="'.$ponumber.'">';
          echo '<input type="hidden" name="notes" value="'.$notes.'">';
          echo '<input type="hidden" name="pricelevelid" value="'.$pricelevelid.'">';
          for ($i=1; ${"itemcode".$i}; $i++) { //check fields that should be checked
               echo '<input type="hidden" name="costeach'.$i.'" value="'.${"costeach".$i}.'">'; // not used yet
               echo '<input type="hidden" name="itemtaxflag'.$i.'" value="'.${"itemtaxflag".$i}.'">';
               $recordSet = &$conn->Execute('select id,priceunitsperstockunit from item where itemcode='.sqlprep(${"itemcode".$i}).' and item.companyid='.sqlprep($active_company));
               if (!$recordSet->EOF) {
                    ${"itemid".$i}=$recordSet->fields[0];
                    $ppsu=$recordSet->fields[1];
                    if ($ppsu<=0) $ppsu=1;
                    if (${"itemqtyorder".$i}=="") ${"itemqtyorder".$i}=1;
                    if (${"itempriceach".$i}=="") {
                         $recordSet1=&$conn->Execute('select markupsetid from itemlocation where itemid='.sqlprep(${"itemid".$i}).' and inventorylocationid='.sqlprep($inventorylocationid));
                         if (!$recordSet1->EOF) ${"markupset".$i}=$recordSet1->fields[0];
                         ${"itempriceach".$i}=invitemprice(${"itemid".$i}, ${"markupset".$i}, $pricelevelid, ${"itemqtyorder".$i})*$ppsu;
                    };
               } else {
                    ${"itemqtyorder".$i}=0;
               };
          };
          echo '<tr><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_QUANTITY'].'</th><th>'.$lang['STR_PRICE_UNIT'].'</th>';
          echo '<th>'.$lang['STR_TAXABLE'].'<input type="checkbox" checked></th></tr>';
          echo '<input type="hidden" name="costeach'.$i.'" value="0">'; // not used yet
          echo '<tr><td><input type="text" name="itemcode'.$i.'" size="15" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode'.$i.'&&inventorylocationid='.$inventorylocationid.'\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'invitemadd.php\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="images/addbutton.png" border="0" alt="Item Add"></a></td><td><input type="text" name="itemqtyorder'.$i.'" onchange="validatenum(this)" size="10" maxlength="10"'.INC_TEXTBOX.'></td><td><input type="text" name="itempriceach'.$i.'" onchange="validatenum(this)" size="10" maxlength="15"'.INC_TEXTBOX.'></td>';
          $recordSet = &$conn->SelectLimit('select taxexemptid from customer where customer.id='.sqlprep($customerid),1);
          if (!$recordSet->EOF) if ($recordSet->fields[0]) $taxstr=' checked';
          echo '<td align="center"><input type="checkbox" name="itemtaxflag'.$i.'" value="1"'.$taxstr.INC_TEXTBOX.'></td></tr>';
          for ($i=1; ${"itemcode".$i}; $i++) { //pass prev submitted items to next form
               if ($i==1) echo '<tr><td colspan="3">&nbsp;</td></tr>';
               if (${"itemqtyorder".$i}>0) { //if quantity isn't 0
                    if ($inventorylocationid) $locationstr=" and itemlocation.inventorylocationid=".sqlprep($inventorylocationid);
                    $recordSet = &$conn->Execute("select max(itemlocation.onhandqty*item.priceunitsperstockunit) from itemlocation,item where item.id=".sqlprep(${"itemid".$i})." and itemlocation.itemid=".sqlprep(${"itemid".$i}).$locationstr);
                    if (!$recordSet->EOF) {
                          if (!is_null($recordSet->fields[0])&&AR_ORDER_SHOW_ONHAND_QTY) {
                                 if (${"itemqtyorder".$i}>$recordSet->fields[0]) {
                                      $colorbgstr='<font size="-1" color="#FF0000">';
                                      $coloredstr='</font>';
                                 };
                                 $onhandstr='('.$colorbgstr.$recordSet->fields[0].$coloredstr.')';
                          };
                    } else {
                          unset($onhandstr);
                    };
                    echo '<tr><td><input type="text" name="itemcode'.$i.'" size="15" maxlength="20" value="'.${"itemcode".$i}.'"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode'.$i.'&&inventorylocationid='.$inventorylocationid.'\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'invitemadd.php\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="images/addbutton.png" border="0" alt="Item Add"></a>';
                    $recordSet = &$conn->Execute("select item.description from item where item.companyid=".sqlprep($active_company)." and item.id=".sqlprep(${"itemid".$i}));
                    if (!$recordSet->EOF) echo ' '.rtrim($recordSet->fields[0]);
                    echo '</td><td><input type="text" name="itemqtyorder'.$i.'" onchange="validatenum(this)" size="10" maxlength="10" value="'.${"itemqtyorder".$i}.'"'.INC_TEXTBOX.'>'.$onhandstr.'</td><td><input type="text" name="itempriceach'.$i.'" onchange="validatenum(this)" size="10" maxlength="15" value="'.checkdec(${"itempriceach".$i},PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td>';
                    if (${"itemtaxflag".$i}) ${"taxstr".$i} = ' checked';
                    echo '<td align="center"><input type="checkbox" name="itemtaxflag'.$i.'" value="1"'.${"taxstr".$i}.INC_TEXTBOX.'></td></tr>';
                    $total+=${"itemqtyorder".$i}*${"itempriceach".$i};
               };
          };
          echo '<tr><td colspan="2"><div align="right"><b>'.$lang['STR_TOTAL'].':</b></div></td><td>'.CURRENCY_SYMBOL.checkdec($total,PREFERRED_DECIMAL_PLACES).'</td></tr>';
          echo '</table><input type="submit" name="submit" value="'.$lang['STR_ADD_LINE_ITEM_TO_ORDER'].'">';
          for ($i=1; ${"itemid".$i}; $i++) {
                if (${"itemid".$i}&&${"itemqtyorder".$i}) $canclose=1;
          };
          if ($canclose) echo '<input type="submit" name="completeorder" value="'.$lang['STR_COMPLETE_ORDER'].'">';
          echo '</form>';
     } elseif ($customerid&&$inventorylocationid&&$shiptocompanyid&&$completeorder) {
          $recordSet = &$conn->Execute('select company.id from company,customer where company.id=customer.companyid and customer.id='.sqlprep($customerid));
          if (!$recordSet->EOF) $orderbycompanyid=$recordSet->fields[0];
          $recordSet = &$conn->Execute('select max(ordernumber)+1 from arorder');
          if (!$recordSet->EOF) {
               if ($recordSet->fields[0]>=AR_ORDERNUMBER_START) {
                    $ordernumber=$recordSet->fields[0];
               } else {
                    $ordernumber=AR_ORDERNUMBER_START;
               };
          };
          //insert order to sql
          if (!$custcompanyid) checkpermissions('ar');
          if ($conn->Execute("insert into arorder (ordernumber,ponumber,orderbycompanyid,shiptocompanyid,companyid,pricelevelid,inventorylocationid,duedate,entrydate,entryuserid,lastchangeuserid) VALUES (".sqlprep($ordernumber).", ".sqlprep($ponumber).", ".sqlprep($orderbycompanyid).", ".sqlprep($shiptocompanyid).", ".sqlprep($active_company).", ".sqlprep($pricelevelid).", ".sqlprep($inventorylocationid).", ".sqlprep($duedate).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).")") === false) {
               die(texterror($lang['STR_ERROR_INSERTING_ORDER']));
          } else {
               $recordSet = &$conn->SelectLimit("select id from arorder where ponumber=".sqlprep($ponumber)." and orderbycompanyid=".sqlprep($orderbycompanyid)." order by entrydate desc",1);
               if (!$recordSet->EOF) $orderid=$recordSet->fields[0];
               for ($i=1; ${"itemcode".$i}; $i++) {
                    if (${"itemqtyorder".$i}>0) { //if quantity isn't 0
                       if (${"itemglaccountid".$i}==0) {
                           if ($custoritem==0) {
                              $recordSet = &$conn->Execute('select glaccount.id,glaccount.name, glaccount.description, customer.salesglacctid from glaccount,customer where customer.id='.sqlprep($customerid).' and glaccount.accounttypeid=50 and (glaccount.companyid=0 or glaccount.companyid='.sqlprep($active_company).') and customer.salesglacctid=glaccount.id');
                           } else {
                              $recordSet = &$conn->Execute('select glaccount.id,glaccount.name, glaccount.description, item.salesglacctid from glaccount,item where item.itemcode='.sqlprep(${"itemcode".$i}).' and glaccount.accounttypeid=50 and (glaccount.companyid=0 or glaccount.companyid='.sqlprep($active_company).')  and item.salesglacctid=glaccount.id');
                           };
                           if (!$recordSet->EOF) {
                             ${"itemglaccountid".$i}=$recordSet->fields[0];
                           };
                        };

                         $recordSet2 = &$conn->Execute('select id from item where item.companyid='.sqlprep($active_company).' and item.itemcode='.sqlprep(${"itemcode".$i}));
                         if (!$recordSet2->EOF) ${"itemid".$i}=$recordSet2->fields[0];
                         if (!${"itemtaxflag".$i}) ${"itemtaxflag".$i}=0;
                         if ($conn->Execute("insert into arorderdetail (orderid,itemid,linenumber,qtyorder,qtyship,qtybill,glaccountid,taxflag,costeach,priceach,entrydate,entryuserid,lastchangeuserid) VALUES (".sqlprep($orderid).", ".sqlprep(${"itemid".$i}).", ".sqlprep($i).", ".sqlprep(${"itemqtyorder".$i}).", '0', '0', ".sqlprep(${"itemglaccountid".$i}).", ".sqlprep(${"itemtaxflag".$i}).", ".sqlprep(${"costeach".$i}).", ".sqlprep(${"itempriceach".$i}).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).")") === false) {
                              //back out the order if we error
                              $conn->Execute("delete from arorderdetail where orderid=".sqlprep($orderid));
                              $conn->Execute("delete from arorder where id=".sqlprep($orderid));
                              die(texterror($lang['STR_ERROR_INSERTING_ORDER_DETAILS'] ('.$i.')));
                         };
                    };
               };
               if ($conn->Execute("insert into arordernotes (orderid,note,lastchangeuserid) VALUES (".sqlprep($orderid).", ".sqlprep($notes).", ".sqlprep($userid).")") === false) echo texterror('Error inserting order notes');
               echo textsuccess($lang['STR_ORDER_ADDED_SUCCESSFULLY']);
               echo '<br><a href="arordupd.php?ordernumber='.$ordernumber.'">'.$lang['STR_UPDATE_ORDER'].'</a><br>';
               echo '<a href="arordpicktick.php?printable=1&ordernumber='.$ordernumber.'">'.$lang['STR_PICK_LIST'].'</a>';
          };
     } elseif ($customerid&&$inventorylocationid&&!$shiptocompanyid) {
          echo '<form action="arordadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
          echo '<input type="hidden" name="customerid" value="'.$customerid.'">';
          echo '<input type="hidden" name="inventorylocationid" value="'.$inventorylocationid.'">';
          echo '<input type="hidden" name="duedate" value="'.$duedate.'">';
          echo '<input type="hidden" name="ponumber" value="'.$ponumber.'">';
          echo '<input type="hidden" name="notes" value="'.$notes.'">';
          echo '<input type="hidden" name="pricelevelid" value="'.$pricelevelid.'">';
          echo '<tr><td>'.$lang['STR_SHIP_TO'].':</td><td><select name="shiptocompanyid"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select company.id,company.companyname,company.address1,company.city,company.state,company.country from company,shipto,customer where company.id=shipto.shiptocompanyid and shipto.companyid=customer.companyid and customer.id='.sqlprep($customerid).' and shipto.cancel=0 and company.cancel=0 order by company.companyname,company.country,company.address1,company.city,company.state');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2]).', '.rtrim($recordSet->fields[3]).', '.rtrim($recordSet->fields[4]).' '.rtrim($recordSet->fields[5])."\n";
               $recordSet->MoveNext();
          };
          echo '</select><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'arcustupd.php?customerid='.$customerid.'&shipto=1&shiptoselected=1\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="images/addbutton.png" border="0" alt="Ship To Add"></a></td></tr>';
          echo '</table><input type="submit" value="'.$lang['STR_NEXT_SCREEN'].'"></form>';
     } else {
          $timestamp =  time();
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

          echo '<form action="arordadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
          $recordSet = &$conn->Execute('select count(*) from inventorylocation,company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' and company.cancel=0');
          if (!$recordSet->EOF) {
               if ($recordSet->fields[0]>1) {
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LOCATION'].':</td><td><select name="inventorylocationid"'.INC_TEXTBOX.'>';
                    $recordSet = &$conn->Execute('select inventorylocation.id,company.companyname from inventorylocation,company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' and company.cancel=0 order by company.companyname');
                    while (!$recordSet->EOF) {
                         echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                         $recordSet->MoveNext();
                    };
                    echo '</select></td></tr>';
               } elseif ($recordSet->fields[0]==1) {
                    $recordSet = &$conn->SelectLimit('select inventorylocation.id from inventorylocation,company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' and company.cancel=0',1);
                    if (!$recordSet->EOF) echo '<input type="hidden" name="inventorylocationid" value="'.$recordSet->fields[0].'">';
               };
          };
          if (!$custcompanyid) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_NUMBER'].':</td><td><input type="text" name="customerid" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupcustomer.php?name=customerid\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Customer Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'arcustadd.php\',\'cal\',\'dependent=yes,width=800,height=400,screenX=300,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="images/addbutton.png" border="0" alt="Customer Add"></a></td></tr>';
          $recordSet = &$conn->Execute('select count(*) from pricelevel where (companyid=0 or companyid='.sqlprep($active_company).')');
          if (!$recordSet->EOF) if ($recordSet->fields[0]) {
                  echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PRICE_LEVEL'].':</td><td><select name="pricelevelid"'.INC_TEXTBOX.'>';
                  $recordSet = &$conn->Execute('select id,description from pricelevel where (companyid=0 or companyid='.sqlprep($active_company).') order by description');
                  while (!$recordSet->EOF) {
                       echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                       $recordSet->MoveNext();
                  };
                  echo '</select></td></tr>';
          } else {
                  $recordSet = &$conn->Execute('select id from pricelevel where (companyid=0 or companyid='.sqlprep($active_company).') order by description');
                  if (!$recordSet->EOF) echo '<input type="hidden" name="pricelevelid" value="'.$recordSet->fields[0].'">';
          };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PURCHASE_ORDER'].':</td><td><input type="text" name="ponumber" size="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DUE_DATE'].':</td><td><input type="text" name="duedate" onchange="formatDate(this)" value="'.$enddate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.duedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="images/spyglass.png" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NOTES'].':</td><td><textarea name="notes" rows="3" cols="25"></textarea></td></tr>';
          echo '</table><input type="submit" value="'.$lang['STR_ADD'].'"></form>';

     };

          
          echo '</center>';

?>



<?php include_once("includes/footer.php"); ?>
