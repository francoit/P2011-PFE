<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
     var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<script language="JavaScript">
     function checkprice(cost1,cost2,cost3,cost4,qty1,qty2,qty3) {
          document.mainform.itemprice.value=cost1;
          if  (document.mainform.itemqty.value>qty1&&cost2>0) {
               document.mainform.itemprice.value=cost2;
          }
          if (document.mainform.itemqty.value>qty2&&cost3>0) {
              document.mainform.itemprice.value=cost3;
          }
          if (document.mainform.itemqty.value>qty3&&cost4>0) {
              document.mainform.itemprice.value=cost4;
          }
     }
</script>
<?
     echo '<center>';
     echo texttitle($lang['STR_UPDATE_INVENTORY_PO']);
     if ($invpoid&&$delete) { //delete po
               if ($conn->Execute('update invpo set cancel=1, canceldate=NOW(), canceluserid='.sqlprep($userid).' where id='.sqlprep($invpoid)) === false) {
                    die(texterror($lang['STR_ERROR_DELETING_INVENTORY_PO']));
               } else {
                    echo textsuccess($lang['STR_INVENTORY_PO_UPDATED_SUCCESSFULLY']);
                    unset($invpoid);
               };
     };
     if ($first) { //test to see if our search only pulled back one po, and if so, go ahead and select it
          if ($vendorid) $vendorstr=' and vendor.id='.sqlprep($vendorid);
          if ($ponumber) $ponumberstr=' and invpo.ponumber='.sqlprep($ponumber);
          if ($requisition) $requisitionstr=' and invpo.requisition='.sqlprep($requisition);
          if ($duedate) $duedatestr=' and invpo.duedate='.sqlprep($duedate);
          if ($ordernumber) $ordernumberstr=' and invpo.ordernumber='.sqlprep($ordernumber);
          if ($locationid) $locationidstr=' and inventorylocation.id='.sqlprep($locationid);
          if ($carrierserviceid) $carrierstr=' and carrierservice.id='.sqlprep($carrierserviceid);
//  The following lines of code are Copyright 2002, 2003 Free Software Foundation
//  Added by Chan Min Wai on 18/06/2003 13:07:45 
          if ($notes) {
              $notesstr1=', invponotes';
              $notesstr2=' and invponotes.invpoid=invpo.id and invponotes.note like '.sqlprep('%'.$notes.'%');
          };
          $recordSet = &$conn->Execute('select count(*) from invpo,vendor,company as vcompany,company as icompany,inventorylocation,company as ccompany,carrier,carrierservice".$notesstr1." where invpo.complete=0 and inventorylocation.companyid=icompany.id ".$notesstr2." and invpo.locationid=inventorylocation.id and invpo.carrierserviceid=carrierservice.id and carrier.id=carrierservice.carrierid and ccompany.id=carrier.companyid and invpo.vendorid=vendor.id and invpo.cancel=0 and vendor.orderfromcompanyid=vcompany.id'.$vendorstr.$ponumberstr.$requisitionstr.$ordernumberstr.$duedatestr.$locationidstr.$carrierstr);
//  End Copyright 2002, 2003 Free Software Foundation
          if (!$recordSet->EOF) if ($recordSet->fields[0]==1) {
               $recordSet = &$conn->Execute('select inventorylocation.id,vendor.id,carrierservice.id,invpo.id from invpo,vendor,company as vcompany,company as icompany,inventorylocation,company as ccompany,carrier,carrierservice where invpo.complete=0 and inventorylocation.companyid=icompany.id and invpo.locationid=inventorylocation.id and invpo.carrierserviceid=carrierservice.id and carrier.id=carrierservice.carrierid and ccompany.id=carrier.companyid and invpo.vendorid=vendor.id and invpo.cancel=0 and vendor.orderfromcompanyid=vcompany.id'.$vendorstr.$ponumberstr.$requisitionstr.$ordernumberstr.$duedatestr.$locationidstr.$carrierstr.' order by invpo.duedate desc');
               if (!$recordSet->EOF) {
                    unset($submit);
               };
          };
     };
     if ($invpoid&&($updatepo||$updatepodetails)) { //finish stuff
          checkpermissions('inv');
          if ($updatepo) {
//  The following lines of code are Copyright 2002, 2003 Free Software Foundation
//  Added by Chan Min Wai on 18/06/2003 13:07:45 
//               if ($conn->Execute('update invpo set vendorid='.sqlprep($vendorid).',ponumber='.sqlprep($ponumber).',duedate='.sqlprep($duedate).',locationid='.sqlprep($locationid).',carrierserviceid='.sqlprep($carrierserviceid).',tracknumber='.sqlprep($tracknumber).',contact='.sqlprep($contact).',requisition='.sqlprep($requisition).',ordernumber='.sqlprep($ordernumber).',currencyid='.sqlprep($currencyid).' where id='.sqlprep($invpoid)) === false) {
               if ($conn->Execute('update invpo set vendorid='.sqlprep($vendorid).',ponumber='.sqlprep($ponumber).',duedate='.sqlprep($duedate).',locationid='.sqlprep($locationid).',carrierserviceid='.sqlprep($carrierserviceid).',tracknumber='.sqlprep($tracknumber).',contact='.sqlprep($contact).',requisition='.sqlprep($requisition).',ordernumber='.sqlprep($ordernumber).' where id='.sqlprep($invpoid)) === false) {
                    die(texterror($lang['STR_ERROR_UPDATING_PO']));
               } else {
                    if ($notes) { //one of these will update notes.  i think this method will be more efficient than doing a select first on high transaction volume databases, as the database will still spend the same time locking to allow 1 write as it will with 2 consecutives
                         if ($conn->Execute('update invponotes set note='.sqlprep($notes).', lastchangeuserid='.sqlprep($userid).', where invpoid='.sqlprep($invpoid)) === false) {
                              $conn->Execute('delete from invponotes where invpoid='.sqlprep($invpoid));
                              $conn->Execute('insert into invponotes (invpoid,note,lastchangeuserid) VALUES ('.sqlprep($invpoid).', '.sqlprep($notes).', '.sqlprep($userid).')'); 
                         } else {
                              die(texterror($lang['STR_ERROR_UPDATING_PO']));
                         }
                    } else {
                         $conn->Execute('delete from invponotes where invpoid='.sqlprep($invpoid));
                    }
//  End Copyright 2002, 2003 Free Software Foundation
                    echo textsuccess($lang['STR_GENERAL_PO_INFO_UPDATED_SUCCESSFULLY']);
               };
          } elseif ($updatepodetails) {
               $conn->Execute('delete from invpodetail where invpoid='.sqlprep($invpoid));
               for ($i=1;${"itemqtyorder".$i};$i++) {
                    $recordSet = &$conn->Execute("select item.id from item where item.itemcode=".sqlprep(${"itemcode".$i}).' and item.companyid='.sqlprep($active_company));
                    if (!$recordSet->EOF) {
                         if ($conn->Execute('insert into invpodetail (invpoid,itemid,itemqty,itemprice) values ('.sqlprep($invpoid).', '.sqlprep($recordSet->fields[0]).', '.sqlprep(${"itemqtyorder".$i}).', '.sqlprep(${"itempriceach".$i}).')') === false) {
                              die(texterror($lang['STR_ERROR_UPDATING_INVENTORY_PO_DETAILS']));
                         } else {
                              echo textsuccess($lang['STR_PO_DETAILS_UPDATED_SUCCESSFULLY']);
                         };
                    };
               };
               //echo '<br><a href="javascript:doNothing()" onclick="top.newWin = window.open (\'invpoview.php?printable=1&invpoid='.$invpoid.'\',\'print\',\'dependent=yes,width=800,height=400,screenX=10,screenY=100,titlebar=yes,scrollbars=1,resizable=0\')">'.$lang['STR_PRINT_THIS_PO'].'</a>';
               echo '<br><a href="javascript:doNothing()" onclick="top.newWin = window.open (\'invpoview.php?printable=1&invpoid='.$invpoid.'\',\'print\',\'dependent=yes,width=800,height=400,screenX=10,screenY=100,titlebar=yes,scrollbars=1,resizable=0\')">'.$lang['STR_PRINT_THIS_PO'].'</a>';
          };
          echo '<form action="invpoupd.php" method="post" name="mainform"><table border="1">';
          echo '<tr><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_UNIT_PER_PACKAGE'].'</th><th>'.$lang['STR_QUANTITY'].'</th><th>'.$lang['STR_PRICE_PER_PACKAGE'].'</th></tr>';
          $i=1;
          $recordSet = &$conn->Execute('select invpodetail.itemqty, invpodetail.itemprice, item.itemcode, item.description, invpodetail.itemid, invpodetail.unitperpack from invpodetail,item where item.id=invpodetail.itemid and invpodetail.invpoid='.sqlprep($invpoid).' and item.companyid='.sqlprep($active_company).' order by invpodetail.id');
          while (!$recordSet->EOF) {
               echo '<tr><td><input type="text" name="itemcode'.$i.'" size="15" maxlength="20" value="'.$recordSet->fields[2].'"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode'.$i.'\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="'.IMAGE_ITEM_LOOKUP.'" border="0" alt="Item Lookup"></a>'.$recordSet->fields[3];
               echo '</td><td><input type="text" name="unitperpack'.$i.'" onchange="validatenum(this)" size="10" maxlength="10" value="'.checkdec($recordSet->fields[5],0).'"'.INC_TEXTBOX.'></td><td><input type="text" name="itemqtyorder'.$i.'" onchange="validatenum(this)" size="10" maxlength="10" value="'.checkdec($recordSet->fields[0],0).'"'.INC_TEXTBOX.'></td><td><input type="text" name="itempriceach'.$i.'" onchange="validatenum(this)" size="10" maxlength="15" value="'.checkdec($recordSet->fields[1],PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td></tr>';
               $total+=$recordSet->fields[0]*$recordSet->fields[1];
               $i++;
               $recordSet->MoveNext();
          };
          echo '<tr><td><input type="text" name="itemcode'.$i.'" size="15" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode'.$i.'\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="'.IMAGE_ITEM_LOOKUP.'" border="0" alt="Item Lookup"></a></td><td><input type="text" name="unitperpack'.$i.'" size="10" maxlength="10"'.INC_TEXTBOX.'></td><td><input type="text" name="itemqtyorder'.$i.'" onchange="validatenum(this)" size="10" maxlength="10"'.INC_TEXTBOX.'></td><td><input type="text" name="itempriceach'.$i.'" onchange="validatenum(this)" size="10" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td colspan="3"><div align="right"><b>'.$lang['STR_TOTAL'].':</b></div></td><td>'.CURRENCY_SYMBOL.num_format($total,2).'</td></tr>';
          echo '</table><input type="hidden" name="invpoid" value="'.$invpoid.'"><input type="submit" name="updatepodetails" value="'.$lang['STR_UPDATE_PO_DETAILS'].'">';
     } elseif ($invpoid&&!$updatepo&&!$updatepodetails) {
//          $recordSet = &$conn->Execute('select invpo.id,invpo.ponumber,invpo.contact,vcompany.id,invpo.duedate,icompany.id,ccompany.id,carrierservice.id,invpo.requisition,invpo.ordernumber,invpo.tracknumber,invpo.vendorid,invpo.currencyid from invpo,vendor,company as vcompany,company as icompany,inventorylocation,company as ccompany,carrier,carrierservice where inventorylocation.companyid=icompany.id and invpo.cancel=0 and invpo.locationid=inventorylocation.id and invpo.carrierserviceid=carrierservice.id and carrier.id=carrierservice.carrierid and ccompany.id=carrier.companyid and invpo.vendorid=vendor.id and vendor.orderfromcompanyid=vcompany.id and invpo.id='.sqlprep($invpoid).' order by invpo.duedate desc');     
          $recordSet = &$conn->Execute('select invpo.id,invpo.ponumber,invpo.contact,vcompany.id,invpo.duedate,icompany.id,ccompany.id,carrierservice.id,invpo.requisition,invpo.ordernumber,invpo.tracknumber,invpo.vendorid from invpo,vendor,company as vcompany,company as icompany,inventorylocation,company as ccompany,carrier,carrierservice where inventorylocation.companyid=icompany.id and invpo.cancel=0 and invpo.locationid=inventorylocation.id and invpo.carrierserviceid=carrierservice.id and carrier.id=carrierservice.carrierid and ccompany.id=carrier.companyid and invpo.vendorid=vendor.id and vendor.orderfromcompanyid=vcompany.id and invpo.id='.sqlprep($invpoid).' order by invpo.duedate desc');
          if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_PO_FOUND']));
          echo texttitle($lang['STR_UPDATE_GENERAL_PO_NUMBER'].$recordSet->fields[1].$lang['STR_INFO']);
          echo '<form action="invpoupd.php" method="post" name="mainform"><table>';
          echo '<input type="hidden" name="invpoid" value="'.$invpoid.'">';
          forminvpoupdate($recordSet->fields[11],$recordSet->fields[2],$recordSet->fields[9],$recordSet->fields[1],$recordSet->fields[4],$recordSet->fields[8],$recordSet->fields[5],$recordSet->fields[7],$recordSet->fields[10]);

          $recordSet2 = &$conn->Execute("select note from invponotes where invpoid=".sqlprep($recordSet->fields[0]));
          echo '<tr><td>'.$lang['STR_NOTES'].':</td><td><textarea name="notes" rows="3" cols="25">';
          if (!$recordSet2->EOF) echo $recordSet2->fields[0];
          echo '</textarea></td></tr>';

          echo '</table><input type="submit" name="updatepo" value="'.$lang['STR_UPDATE_PO'].'"></form>';
          $recordSet = &$conn->Execute('select count(*) from invreceive where invpoid='.sqlprep($invpoid));
          if (!$recordSet->fields[0]) echo '<a href="javascript:confirmdelete(\'invpoupd.php?delete=1&invpoid='.$invpoid.'\')">Delete this PO</a>';
     } elseif ($first) {
          if ($vendorid) $vendorstr=' and vendor.id='.sqlprep($vendorid);
          if ($ponumber) $ponumberstr=' and invpo.ponumber='.sqlprep($ponumber);
          if ($requisition) $requisitionstr=' and invpo.requisition='.sqlprep($requisition);
          if ($duedate) $duedatestr=' and invpo.duedate='.sqlprep($duedate);
          if ($ordernumber) $ordernumberstr=' and invpo.ordernumber='.sqlprep($ordernumber);
          if ($locationid) $locationidstr=' and inventorylocation.id='.sqlprep($locationid);
          if ($carrierserviceid) $carrierstr=' and carrierservice.id='.sqlprep($carrierserviceid);
//  The following lines of code are Copyright 2002, 2003 Free Software Foundation
//  Added by Chan Min Wai on 18/06/2003 13:07:45 
          if ($notes) {
              $notesstr1=', invponotes';
              $notesstr2=' and invponotes.invpoid=invpo.id and invponotes.note like '.sqlprep('%'.$notes.'%');
          };
//  End Copyright 2002, 2003 Free Software Foundation
          $recordSet = &$conn->Execute('select invpo.ponumber,vcompany.companyname,invpo.duedate,icompany.companyname,ccompany.companyname,carrierservice.description,invpo.requisition,invpo.ordernumber,invpo.id from invpo,vendor,company as vcompany,company as icompany,inventorylocation,company as ccompany,carrier,carrierservice'.$notesstr1.' where invpo.complete=0 and inventorylocation.companyid=icompany.id '.$notesstr2.' and invpo.locationid=inventorylocation.id and invpo.carrierserviceid=carrierservice.id and invpo.cancel=0 and carrier.id=carrierservice.carrierid and ccompany.id=carrier.companyid and invpo.vendorid=vendor.id and vendor.orderfromcompanyid=vcompany.id'.$vendorstr.$ponumberstr.$requisitionstr.$ordernumberstr.$duedatestr.$locationidstr.$carrierstr.' order by invpo.duedate desc');
          if ($recordSet->EOF) die(texterror($lang['STR_NO_ENTRIES_FOUND']));
          echo '<table border=1><tr><th>'.$lang['STR_PO_NUMBER'].'</th><th>'.$lang['STR_VENDOR_NAME'].'</th><th>'.$lang['STR_DUE_DATE'].'</th><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_CARRIER'].'</th><th>'.$lang['STR_CARRIER_SERVICE'].'</th><th>'.$lang['STR_REQUISITION_NUMBER'].'</th><th>'.$lang['STR_ORDER_NUMBER'].'</th></tr>';
          while (!$recordSet->EOF) {
               echo '<tr><td><a href="invpoupd.php?invpoid='.$recordSet->fields[8].'">'.$recordSet->fields[0].'</a></td><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[5].'</td><td>'.$recordSet->fields[6].'</td><td>'.$recordSet->fields[7].'</td>';
               $recordSet->MoveNext();
          };
          echo '</table>';
     } else {
          echo '<form action="invpoupd.php" method="post" name="mainform"><table>';
          echo '<input type="hidden" name="first" value="1">';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PO_NUMBER'].':</td><td><input type="text" name="ponumber" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          formapvendorselect('vendorid');
          $recordSet = &$conn->Execute('select count(*) from carrierservice,carrier,company where carrierservice.carrierid=carrier.id and carrier.companyid=company.id and company.cancel=0');
          if (!$recordSet->EOF) if ($recordSet->fields[0]>1) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CARRIER_SERVICE'].':</td><td><select name="carrierserviceid"'.INC_TEXTBOX.'><option value="0">';
               $recordSet = &$conn->Execute('select carrierservice.id,company.companyname,carrierservice.description from carrierservice,carrier,company where carrierservice.carrierid=carrier.id and carrier.companyid=company.id and company.cancel=0 order by company.companyname,carrierservice.description');
               while (!$recordSet->EOF) {
                    echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
                    $recordSet->MoveNext();
               };
               echo '</select></td></tr>';
          };
          $recordSet = &$conn->Execute('select count(*) from inventorylocation,company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company));
          if (!$recordSet->EOF) if ($recordSet->fields[0]>1) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LOCATION'].':</td><td><select name="locationid"'.INC_TEXTBOX.'><option value="0">';
               $recordSet = &$conn->Execute('select inventorylocation.id,company.companyname from inventorylocation,company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
               while (!$recordSet->EOF) {
                    echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                    $recordSet->MoveNext();
               };
               echo '</select></td></tr>';
          };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DUE_DATE'].':</td><td><input type="text" name="duedate" onchange="formatDate(this)" size="30" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.duedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_REQUISITION_NUMBER'].':</td><td><input type="text" name="requisition" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VENDOR_ORDER_NUMBER'].':</td><td><input type="text" name="ordernumber" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
//  The following lines of code are Copyright 2002, 2003 Free Software Foundation
//  Added by Chan Min Wai on 18/06/2003 13:07:45 
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NOTES'].':</td><td><input type="text" name="notes" size="30"'.INC_TEXTBOX.'></td></tr>';
//  End Copyright 2002, 2003 Free Software Foundation
          echo '</table><input type="submit" name="submit" value="'.$lang['STR_SHOW_LIST'].'">';
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
