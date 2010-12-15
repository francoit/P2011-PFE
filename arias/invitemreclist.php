<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo texttitle($lang['STR_RECEIVE_NO_PO_LIST']);
     echo '<center>';
     if ($bgdate&&eddate) { //display list
          if ($vendor) $vendorstr=' and vendor.id='.sqlprep($vendor);
          if ($location) $locationstr=' and inventorylocation.id='.sqlprep($location);
          if ($category) $categorystr=' and itemcategory.id='.sqlprep($category);
          if ($status == withpo) {
                $statusstr = 'and invreceive.recsource=1';
          } else {
                $statusstr = 'and invreceive.recsource=0';
          }
          if ($itemcode!="All"&&$itemcode!="") $itemcodestr=' and item.itemcode='.sqlprep($itemcode);
          $recordSet = &$conn->Execute('select substring(invreceive.receivedate,1,10), inv.companyname, itemcategory.name, item.itemcode, item.description, vend.companyname, invreceive.id, invreceive.itemqty, invreceive.itemprice from item,invreceive,company as inv,company as vend,itemcategory,vendor,inventorylocation where invreceive.cancel=0 and invreceive.vendorid=vendor.id and vend.id=vendor.orderfromcompanyid and inventorylocation.companyid=inv.id and invreceive.locationid=inventorylocation.id and itemcategory.id=item.categoryid and invreceive.itemid=item.id and item.companyid='.sqlprep($active_company).'  and invreceive.receivedate>='.sqlprep($bgdate).' and invreceive.receivedate<='.sqlprep($eddate).$statusstr.$vendorstr.$locationstr.$categorystr.$itemcodestr.' order by invreceive.receivedate, inv.companyname, itemcategory.name, item.itemcode');
          if ($recordSet->EOF) die(texterror($lang['STR_NO_ENTIES_FOUND_MATCHING_CRITERIA']));
          echo '<table border="1"><tr><th>'.$lang['STR_RECV_DATE'].'</th><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_VENDOR'].'</th><th>'.$lang['STR_QUANTITY'].'</th><th>'.$lang['STR_PRICE'].'</th></tr>';
          while (!$recordSet->EOF) {
               $qty=$recordSet->fields[7];
               if (floor($qty)==$qty) $qty=floor($qty);
               echo '<tr><td nowrap><a href="invitemreclist.php?id='.$recordSet->fields[6].'">'.$recordSet->fields[0].'</a></td><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[5].'</td><td>'.$qty.'</td><td align="right">'.CURRENCY_SYMBOL.num_format($recordSet->fields[8],2).'</td></tr>';
               $recordSet->MoveNext();
          };
          echo '</table>';
     } elseif ($id) { //edit or delete
          if ($delete) {
               checkpermissions('inv');
               if ($conn->Execute('update invreceive set cancel=1, canceldate=NOW(), canceluserid='.sqlprep($userid).' where id='.sqlprep($id)) === false) echo texterror($lang['STR_ITEM_RECEIVE_CANCEL_FAILED']);
               $recordSet = &$conn->SelectLimit('select itemqty, locationid, itemid from invreceive where id='.sqlprep($id),1);
               if (!$recordSet->EOF) {
                    $invqty=$recordSet->fields[0];
                    $invlocationid=$recordSet->fields[1];
                    $invitemid=$recordSet->fields[2];
               };
               $recordSet = &$conn->SelectLimit('select id from gltransvoucher where voucher='.sqlprep('itemrecv'.$id),1);
               if (!$recordSet->EOF) if ($conn->Execute('delete from gltransaction where glaccountid='.sqlprep($recordSet->fields[0])) === false) echo texterror("GL Transaction backout failed.");
               if ($conn->Execute('update gltransvoucher set cancel=1, canceldate=NOW(), canceluserid='.sqlprep($userid).' where voucher='.sqlprep('itemrecv'.$id)) === false) echo texterror($lang['STR_GL_TRANSACTION_VOUCHER_BACKOUT_FAILED']);
               if ($conn->Execute('update itemlocation set itemlocation.onhandqty=itemlocation.onhandqty-'.$invqty.' where itemlocation.inventorylocationid='.sqlprep($invlocationid).' and itemlocation.itemid='.sqlprep($invitemid)) === false) echo texterror($lang['STR_ERROR_UPDATING_ITEM_ON_HAND_QUANTITY']);
               $recordSet=&$conn->Execute('select compositeitemid.subitemcodeid,compositeitemid.quantity from compositeitemid where compositeitemid.itemcodeid='.sqlprep($invitemid));
               while (!$recordSet->EOF) {
                    if ($conn->Execute('update itemlocation set itemlocation.onhandqty=itemlocation.onhandqty-'.($invqty*$recordSet->fields[1]).' where itemlocation.id='.sqlprep($invlocationid).' and itemlocation.itemid='.sqlprep($recordSet->fields[0])) === false) echo texterror($lang['STR_ERROR_UPDATING_COMPOSITE_SUB_ITEM_ON_HAND_QUANTITY']);
                    $recordSet->MoveNext();
               };
               if (!invitemcompositeparentquantityupdate($invitemid, $invlocationid)) echo texterror($lang['STR_ERROR_UPDATING_COMPANY_COMPOSITE_PARENT_ITEM_ON_HAND_QUANTITY']);
               echo textsuccess($lang['STR_UNRECEIVE_COMPLETE']);
          } else {
               $recordSet = &$conn->Execute('select substring(invreceive.receivedate,1,10), inv.companyname, itemcategory.name, item.itemcode, item.description, vend.companyname, invreceive.itemqty, invreceive.itemprice, unitname.unitname, invreceive.entrydate, genuser.name from item,invreceive,unitname,genuser,company as inv,company as vend,itemcategory,vendor,inventorylocation where invreceive.entryuserid=genuser.id and unitname.id=invreceive.receiveunitnameid and invreceive.vendorid=vendor.id and vend.id=vendor.orderfromcompanyid and inventorylocation.companyid=inv.id and invreceive.locationid=inventorylocation.id and itemcategory.id=item.categoryid and invreceive.itemid=item.id and item.companyid='.sqlprep($active_company).' and invreceive.id='.sqlprep($id).$statusstr.$vendorstr.$locationstr.$categorystr.$itemcodestr.' order by invreceive.receivedate, inv.companyname, itemcategory.name, item.itemcode');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_ENTIES_FOUND_MATCHING_CRITERIA']));
               echo '<table border="1"><tr><th>'.$lang['STR_RECV_DATE'].'</th><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th></tr><tr><th>'.$lang['STR_QTY'].'</th><th>'.$lang['STR_UNIT_PRICE'].'</th><th>'.$lang['STR_RECV_DATE'].'</th><th colspan="3">'.$lang['STR_VENDOR'].'</th></tr>';
               if (!$recordSet->EOF) echo '<tr><td>'.$recordSet->fields[0].'</td><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td></tr><tr><td>'.checkdec($recordSet->fields[6],0).'</td><td>'.CURRENCY_SYMBOL.checkdec($recordSet->fields[7],PREFERRED_DECIMAL_PLACES).'</td><td>'.$recordSet->fields[8].'</td><td colspan="3">'.$recordSet->fields[5].'</td></tr>';
               echo '</table>';
               echo '<font size="-3">'.$lang['STR_ENTERED_BY'].' '.$recordSet->fields[10].' on '.$recordSet->fields[9].'</font><br>';
               echo '<a href="invitemreclist.php?id='.$id.'&delete=1">'.$lang['STR_UNRECEIVE_THIS_ITEM'].'</a>';
          };
     } else {
          $timestamp = time();
          $date_time_array = getdate($timestamp);
          $hours = $date_time_array["hours"];
          $minutes = $date_time_array["minutes"];
          $seconds = $date_time_array["seconds"];
          $month = $date_time_array["mon"];
          $day = $date_time_array["mday"];
          $year = $date_time_array["year"];
          $timestamp = mktime($hour, $minute, $second, $month-1, $day, $year);
          $bgdate=date("Y-m-d", $timestamp);
          $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
          $eddate=date("Y-m-d", $timestamp);
          echo '<form action="invitemreclist.php" method="post" name="mainform"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ITEM_CODE'].':</td><td><input type="text" name="itemcode" value="'.$lang['STR_ALL'].'"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_DATE'].':</td><td><input type="text" name="bgdate" onchange="formatDate(this)" value="'.$bgdate.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.bgdate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="images/calendar.gif" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_DATE'].':</td><td><input type="text" name="eddate" onchange="formatDate(this)" value="'.$eddate.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.eddate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="images/calendar.gif" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATUS'].':</td><td><input name="status" type="radio" value="withpo" checked>'.$lang['STR_SUBMENU_7_46'].'<br><input name="status" type="radio" value="withoutpo">'.$lang['STR_SUBMENU_7_49'].'</td></tr>';
          //<input type="text" name="eddate" onchange="formatDate(this)" value="'.$eddate.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.eddate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="images/calendar.gif" border="0" alt="Display Calendar"></a></td></tr>';
          $recordSet = &$conn->Execute('select count(*) from company,inventorylocation where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company));
          if (!$recordSet->EOF) if ($recordSet->fields[0]>1) {
                  echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LOCATION'].':</td><td><select name="location"'.INC_TEXTBOX.'><option value="0">'.$lang['STR_ALL'].' ';
                  $recordSet = &$conn->Execute('select inventorylocation.id,company.companyname from company,inventorylocation where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
                  while (!$recordSet->EOF) {
                       echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                       $recordSet->MoveNext();
                  };
                  echo '</select></td></tr>';
          };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CATEGORY'].':</td><td><select name="category"'.INC_TEXTBOX.'><option value="0">'.$lang['STR_ALL'].' ';
          $recordSet = &$conn->Execute('select itemcategory.id, itemcategory.name from itemcategory order by itemcategory.name');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          formapvendorselect('vendor');
          echo '</table><br><input type="submit" name="showlist" value="'.$lang['STR_SHOW_LIST'].'"></form>';
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
