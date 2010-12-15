<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo texttitle($lang['STR_ORDER_UPDATE']);
     echo '<center>';
     if ($ordernumber&&$submit) { //update order
          $recordSet = &$conn->Execute('select custoritemglacct from invcompany where id='.sqlprep($active_company));
          if (!$recordSet->EOF) $custoritem=$recordSet->fields[0];

          $recordSet = &$conn->SelectLimit("select id from arorder where ordernumber=".sqlprep($ordernumber)." order by entrydate desc",1);
          if ($recordSet&&!$recordSet->EOF) $orderid=$recordSet->fields[0];
          checkpermissions('ar');
          $conn->Execute("update arorder set duedate=".sqlprep($duedate).", shiptocompanyid=".sqlprep($shiptocompanyid)." where id=".sqlprep($orderid));
          $conn->Execute("delete from arorderdetail where orderid=".sqlprep($orderid));
          for ($i=1; ${"itemcode".$i}; $i++) {
               if (${"itemqtyorder".$i}>0) { //if quantity isn't 0
                       if (${"itemglaccountid".$i}==0) {
                           if ($custoritem==0) {
                              $recordSet = &$conn->Execute('select glaccount.id,glaccount.name, glaccount.description, customer.salesglacctid from glaccount,customer where customer.companyid='.sqlprep($customerid).' and glaccount.accounttypeid=50 and (glaccount.companyid=0 or glaccount.companyid='.sqlprep($active_company).') and customer.salesglacctid=glaccount.id');
                           } else {
                              $recordSet = &$conn->Execute('select glaccount.id,glaccount.name, glaccount.description, item.salesglacctid from glaccount,item where item.itemcode='.sqlprep(${"itemcode".$i}).' and glaccount.accounttypeid=50 and (glaccount.companyid=0 or glaccount.companyid='.sqlprep($active_company).')  and item.salesglacctid=glaccount.id');
                           };
                           if (!$recordSet->EOF) {
                             ${"itemglaccountid".$i}=$recordSet->fields[0];
                           };
                        };
                    $recordSet = &$conn->Execute('select id from item where itemcode='.sqlprep(${"itemcode".$i}).' and item.companyid='.sqlprep($active_company));
                    if ($recordSet&&!$recordSet->EOF) ${"itemid".$i}=$recordSet->fields[0];
                    if ($conn->Execute("insert into arorderdetail (orderid,itemid,linenumber,qtyorder,glaccountid,taxflag,costeach,priceach,entrydate,entryuserid,lastchangeuserid) VALUES (".sqlprep($orderid).", ".sqlprep(${"itemid".$i}).", ".sqlprep($i).", ".sqlprep(${"itemqtyorder".$i}).", ".sqlprep(${"itemglaccountid".$i}).", ".sqlprep(${"itemtaxflag".$i}).", ".sqlprep(${"costeach".$i}).", ".sqlprep(${"itempriceach".$i}).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).")") === false) die(texterror($lang['STR_ERROR_INSERTING_ORDER_DETAILS']('.$i.')));
                    if ($notes) { //one of these will update notes.  i think this method will be more efficient than doing a select first on high transaction volume databases, as the database will still spend the same time locking to allow 1 write as it will with 2 consecutives
                         $conn->Execute("update arordernotes set note=".sqlprep($notes).", lastchangeuserid=".sqlprep($userid)." where orderid=".sqlprep($orderid));
                         $conn->Execute("insert into arordernotes (orderid,note,lastchangeuserid) VALUES (".sqlprep($orderid).", ".sqlprep($notes).", ".sqlprep($userid).")");
                    } else {
                         $conn->Execute("delete from arordernotes where orderid=".sqlprep($orderid));
                    };
               };
          };
          $conn->Execute("delete from arorderdetail where itemid='0' and orderid=".sqlprep($orderid)); //just to make sure there aren't are stragglers
          echo textsuccess($lang['STR_ORDER_UPDATED_SUCCESSFULLY']);
     };
     if ($customerid||$ponumber||$ordernumber||$notes) { //if the user has submitted initial info
          if ($ordernumber) $orderstr=' and arorder.ordernumber='.sqlprep($ordernumber);
          if ($ponumber) $ponumberstr=' and arorder.ponumber='.sqlprep($ponumber);
          if ($customerid) $customeridstr=' and customer.id='.sqlprep($customerid);
          if ($notes) {
              $notesstr1=', arordernotes';
              $notesstr2=' and arordernotes.orderid=arorder.id and arordernotes.note like '.sqlprep('%'.$notes.'%');
          };
          $recordSet = &$conn->Execute("select count(distinct arorder.id) from arorder,customer, company as ordercompany, company as shiptocompany".$notesstr1." where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) ".$notesstr2." and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr." and arorder.companyid=".sqlprep($active_company));
          if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]>1) { //if there is more than one order matching criteria
               echo '<table border=0><tr><th>'.$lang['STR_ORDER_NUMBER'].'</th><th>'.$lang['STR_PO_NUMBER'].'</th><th>'.$lang['STR_ORDER_BY'].'</th><th>'.$lang['STR_SHIP_TO'].'</th><th>'.$lang['STR_STATUS'].'</th></tr>';
               $recordSet = &$conn->Execute("select distinct arorder.ordernumber, arorder.ponumber, arorder.orderbycompanyid, ordercompany.companyname, arorder.shiptocompanyid, shiptocompany.companyname, arorder.status from arorder,customer, company as ordercompany, company as shiptocompany ".$notesstr1." where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) ".$notesstr2." and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr." and arorder.companyid=".sqlprep($active_company)." order by arorder.entrydate desc");
               while ($recordSet&&!$recordSet->EOF) {
                    if ($recordSet->fields[6]) {
                         $statusstr='<font color="#FF0000">'.$lang['STR_CLOSED'].'</font>';
                    } else {
                         $statusstr='<font color="#00FF00">'.$lang['STR_OPEN'].'</font>';
                    };
                    echo '<tr><td><a href="arordupd.php?ordernumber='.$recordSet->fields[0].'">'.$recordSet->fields[0].'</a></td><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[5].'</td><td>'.$statusstr.'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } else {
               $recordSet = &$conn->Execute("select distinct arorder.id, arorder.ordernumber, arorder.ponumber, arorder.orderbycompanyid, ordercompany.companyname, ordercompany.address1, ordercompany.address2, ordercompany.city, ordercompany.state, ordercompany.zip, ordercompany.phone1, ordercompany.phone1comment, arorder.shiptocompanyid, shiptocompany.companyname, shiptocompany.address1, shiptocompany.address2, shiptocompany.city, shiptocompany.state, shiptocompany.zip, shiptocompany.phone1, shiptocompany.phone1comment,arorder.status,arorder.duedate,ordercompany.id,shiptocompany.id,arorder.entrydate from arorder,customer, company as ordercompany, company as shiptocompany ".$notesstr1." where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) ".$notesstr2." and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr." and arorder.companyid=".sqlprep($active_company)." order by arorder.entrydate desc");
               if ($recordSet&&!$recordSet->EOF) {
                    if ($recordSet->fields[21]) $orderclose=1;
                    echo '<form action="arordupd.php" method="post" name="mainform"><table border=0>';
                    echo '<input type="hidden" name="ordernumber" value="'.$recordSet->fields[1].'">';
                    if ($customerid) echo '<input type="hidden" name="customerid" value="'.$customerid.'">';
                    echo '<table width="100%"><tr><td align="left" valign="top">';
                    if ($recordSet->fields[3]==$recordSet->fields[12]) {
                         echo '     <table border=0><tr><th>'.$lang['STR_ORDER_BY'].' &<br>'.$lang['STR_SHIP_TO'].'</th></tr>';
                         echo '     <tr><td>'.$recordSet->fields[4].'</td></tr>';
                         if ($recordSet->fields[5]) echo '     <tr><td>'.$recordSet->fields[5].'</td></tr>';
                         if ($recordSet->fields[6]) echo '     <tr><td>'.$recordSet->fields[6].'</td></tr>';
                         if ($recordSet->fields[7]||$recordSet->fields[8]||$recordSet->fields[9]) echo '     <tr><td>'.$recordSet->fields[7].', '.$recordSet->fields[8].' '.$recordSet->fields[9].'</td></tr>';
                         if ($recordSet->fields[10]) echo '     <tr><td>'.$recordSet->fields[10].'</td></tr>';
                         if ($recordSet->fields[11]) echo '     <tr><td>'.$recordSet->fields[11].'</td></tr>';
                    } else {
                         echo '     <table border=0><tr><th>'.$lang['STR_ORDER_BY'].'</th><th>'.$lang['STR_SHIP_TO'].'</th></tr>';
                         echo '     <tr><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[13].'</td></tr>';
                         if ($recordSet->fields[5]||$recordSet->fields[14]) echo '     <tr><td>'.$recordSet->fields[5].'</td><td>'.$recordSet->fields[14].'</td></tr>';
                         if ($recordSet->fields[6]||$recordSet->fields[15]) echo '     <tr><td>'.$recordSet->fields[6].'</td><td>'.$recordSet->fields[15].'</td></tr>';
                         if ($recordSet->fields[7]||$recordSet->fields[8]||$recordSet->fields[9]||$recordSet->fields[16]||$recordSet->fields[17||$recordSet->fields[18]]) echo '     <tr><td>'.$recordSet->fields[7].', '.$recordSet->fields[8].' '.$recordSet->fields[9].'</td><td>'.$recordSet->fields[16].', '.$recordSet->fields[17].' '.$recordSet->fields[18].'</td></tr>';
                         if ($recordSet->fields[10]||$recordSet->fields[19]) echo '     <tr><td>'.$recordSet->fields[10].'</td><td>'.$recordSet->fields[19].'</td></tr>';
                         if ($recordSet->fields[11]||$recordSet->fields[20]) echo '     <tr><td>'.$recordSet->fields[11].'</td><td>'.$recordSet->fields[20].'</td></tr>';
                         $shiptostr='</td><td>';
                    };
                    $recordSet2 = &$conn->Execute("select count(*) from company,shipto where shipto.shiptocompanyid=company.id and shipto.companyid=".sqlprep($recordSet->fields[23]));
                    if ($recordSet2&&!$recordSet2->EOF) if ($recordSet2->fields[0]>1) {
                            echo '<tr><td>'.$lang['STR_CHANGE_SHIP_TO'].':'.$shiptostr;
                            echo '<select name="shiptocompanyid">';
                            $recordSet2 = &$conn->Execute("select company.id,company.companyname,company.address1,company.city,company.state,company.country from company,shipto where company.id=shipto.shiptocompanyid and shipto.companyid=".sqlprep($recordSet->fields[23])." and shipto.cancel=0 and company.cancel=0 order by company.companyname,company.country,company.address1,company.city,company.state");
                            while (!$recordSet2->EOF) {
                                       echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$recordSet->fields[24],' selected').'>'.rtrim($recordSet2->fields[1]).' - '.rtrim($recordSet2->fields[2]).', '.rtrim($recordSet2->fields[3]).', '.rtrim($recordSet2->fields[4]).' '.rtrim($recordSet2->fields[5]);
                                 $recordSet2->MoveNext();
                            };
                            echo '</select><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'arcustupd.php?customerid='.$customerid.'&shipto=1&shiptoselected=1\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="images/addbutton.png" border="0" alt="Ship To Add"></a></td></tr>';
                    } else {
                            echo '<input type="hidden" name="shiptocompanyid" value="'.$recordSet->fields[24].'">';
                    };
                    echo '     </table>';
                    echo '</td><td align="right" valign="top">';
                    echo '     <table border=0><tr><th>'.$lang['STR_ORDER_NUMBER'].'</th><th>'.$lang['STR_PO_NUMBER'].'</th></tr>';
                    echo '     <tr><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[2].'</td></tr>';
                    if ($orderclose&&!$printable) {
                         echo '<tr><td bgcolor="#FF0000" colspan="2">Due: '.$recordSet->fields[22].'</td></tr>';
                    } elseif ($printable) {
                         echo '<tr><td colspan="2">Due: '.$recordSet->fields[22].'</td></tr>';
                    } else {
                         echo '<tr><td colspan="2"><input type="text" name="duedate" value="'.$recordSet->fields[22].'"></td></tr>';
                    };
                    echo '</table>';
                    echo '</td></tr></table>';
                    if ($orderclose&&!$printable) echo '<font color="#FF0000"><center>'.$lang['STR_THIS_ORDER_HAS_BEEN_FULFILLED'].'</center></font><br>';
                    if ($orderclose&&$printable) echo '<center>'.$lang['STR_THIS_ORDER_HAS_BEEN_FULFILLED'].'</center><br>';
                    $recordSet2 = &$conn->Execute("select note from arordernotes where orderid=".sqlprep($recordSet->fields[0]));
                    echo '<table><tr><td>'.$lang['STR_NOTES'].':</td><td><textarea name="notes" rows="3" cols="25">';
                    if (!$recordSet2->EOF) echo $recordSet2->fields[0];
                    echo '</textarea></td></tr></table>';
                    echo '<table border=0 width="100%">';
                    $i=1;
                    $recordSet2 = &$conn->Execute("select arorderdetail.itemid,arorderdetail.qtyorder,arorderdetail.glaccountid,arorderdetail.taxflag,arorderdetail.priceach,item.itemcode from arorderdetail,item where arorderdetail.itemid=item.id and arorderdetail.orderid=".sqlprep($recordSet->fields[0])." and item.companyid=".sqlprep($active_company)." order by arorderdetail.linenumber");
                    if ($recordSet2&&!$recordSet2->EOF) {
                         echo '<tr><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_QUANTITY'].'</th><th>'.$lang['STR_PRICE_UNIT'].'</th>';
                         echo '<th>'.$lang['STR_TAXABLE'].'<input type="checkbox" checked></th></tr>';
                    };
                    while ($recordSet2&&!$recordSet2->EOF) {
                         echo '<tr><td><input type="text" name="itemcode'.$i.'" size="15" maxlength="20" value="'.$recordSet2->fields[5].'"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode'.$i.'&&inventorylocationid='.$inventorylocationid.'\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'invitemadd.php\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="images/addbutton.png" border="0" alt="Item Add"></a>';
                         $recordSet3 = &$conn->Execute("select item.description from item where item.id=".sqlprep($recordSet2->fields[0]));
                         if (!$recordSet3->EOF) echo ' '.$recordSet3->fields[0];
                         if ($inventorylocationid) $locationstr=" and itemlocation.inventorylocationid=".sqlprep($inventorylocationid);
                         if (!$recordSet->fields[21]) { //don't display on hand qty's if the order isn't open
                              $recordSet3 = &$conn->Execute("select max(itemlocation.onhandqty*item.priceunitsperstockunit) from itemlocation,item where item.id=".sqlprep($recordSet2->fields[0])." and itemlocation.itemid=".sqlprep($recordSet2->fields[0]).$locationstr);
                              if ($recordSet3&&!$recordSet3->EOF) {
                                   if (!is_null($recordSet3->fields[0])) {
                                        if ($recordSet2->fields[1]>$recordSet3->fields[0]) {
                                             $colorbgstr='<font color="#FF0000">';
                                             $coloredstr='</font>';
                                        };
                                        $onhandstr='<font size="-1">('.$colorbgstr.$recordSet3->fields[0].$coloredstr.')</font>';
                                   };
                              } else {
                                   unset($onhandstr);
                              };
                         };
                         echo '</td><td><input type="text" name="itemqtyorder'.$i.'" onchange="validatenum(this)" size="10" maxlength="10" value="'.$recordSet2->fields[1].'"'.INC_TEXTBOX.'>'.$onhandstr.'</td><td><input type="text" name="itempriceach'.$i.'" onchange="validatenum(this)" size="10" maxlength="15" value="'.checkdec($recordSet2->fields[4],PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td>';
                         echo '<input type="hidden" name="itemglaccountid'.$i.'" value='.${"itemglaccountid".$i}.'>';
                         if ($recordSet2->fields[3]) ${"taxstr".$i} = ' checked';
                         echo '<td align="center"><input type="checkbox" name="itemtaxflag'.$i.'" value="1"'.${"taxstr".$i}.INC_TEXTBOX.'></td></tr>';
                         $i++;
                         $total+=$recordSet2->fields[1]*$recordSet2->fields[4];
                         $recordSet2->MoveNext();
                    };
                    if (!$orderclose) { //create blank entry line, for adding an item
                        echo '<tr><td><input type="text" name="itemcode'.$i.'" size="15" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode'.$i.'&&inventorylocationid='.$inventorylocationid.'\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'invitemadd.php\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="images/addbutton.png" border="0" alt="Item Add"></a>';
                        echo '</td><td><input type="text" name="itemqtyorder'.$i.'" onchange="validatenum(this)" size="10" maxlength="10"'.INC_TEXTBOX.'></td><td><input type="text" name="itempriceach'.$i.'" onchange="validatenum(this)" size="10" maxlength="15"'.INC_TEXTBOX.'></td>';
                        echo '<td align="center"><input type="checkbox" name="itemtaxflag'.$i.'" value="1"'.INC_TEXTBOX.'></td></tr>';
                    };
                    echo '<tr><td colspan="2"><div align="right"><b>'.$lang['STR_TOTAL'].':</b></div></td><td>'.CURRENCY_SYMBOL.num_format($total,2).'</td></tr>';
                    echo '</table>';
                    if (!$recordSet->fields[21]) echo '<input type="submit" name="submit" value="'.$lang['STR_SAVE_CHANGES'].'"><input type="reset" value="'.$lang['STR_RESET'].'">';
                    echo '</form> <br><a href="arordpicktick.php?printable=1&ordernumber='.$ordernumber.'">'.$lang['STR_PICK_LIST'].'</a>';
               } else {
                    die(texterror($lang['STR_NO_MATCHING_ORDERS_FOUND']));
               };
          };
     } else {

          echo '<form action="arordupd.php" method="post" name="mainform"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ORDER_NUMBER'].':</td><td><input type="text" name="ordernumber" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PURCHASE_ORDER'].':</td><td><input type="text" name="ponumber" size="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_NUMBER'].':</td><td><input type="text" name="customerid" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupcustomer.php?name=customerid\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Customer Lookup"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NOTES'].':</td><td><input type="text" name="notes" size="30"'.INC_TEXTBOX.'></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_SEARCH'].'"></form>';
     };
       
          echo '<center>';
?>
<?php include_once("includes/footer.php"); ?>
