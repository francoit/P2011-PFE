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
     function checkprice(cost1,cost2,cost3,cost4,qty1,qty2,qty3,vconv) {
          if (vconv<=0) {
              vconv=1;
          };
          document.mainform.itemprice.value=cost1;
          if  (document.mainform.itemqty.value>qty1*vconv&&cost2>0) {
               document.mainform.itemprice.value=cost2;
          }
          if (document.mainform.itemqty.value>qty2*vconv&&cost3>0) {
              document.mainform.itemprice.value=cost3;
          }
          if (document.mainform.itemqty.value>qty3*vconv&&cost4>0) {
              document.mainform.itemprice.value=cost4;
          }
     }
</script>
<?
     echo '<center>';
     if (!$vendorid) {
          $recordSet = &$conn->Execute('select count(*) from company,vendor where company.id=vendor.orderfromcompanyid and vendor.gencompanyid='.sqlprep($active_company));
          if (!$recordSet->EOF) if ($recordSet->fields[0]==1) { //if only one vendor, don't prompt
               $recordSet = &$conn->Execute('select vendor.id from company,vendor where company.id=vendor.orderfromcompanyid and vendor.gencompanyid='.sqlprep($active_company).' order by company.companyname');
               if (!$recordSet->EOF) $vendorid=$recordSet->fields[0];
          };
     };
     echo texttitle($lang['STR_RECEIVE_ITEMS_INTO_INVENTORY']);
     if ($itemcode&&!$id) { //if user entered an itemcode to update, get the id and use it for reference
         $recordSet = &$conn->Execute('select id from item where itemcode='.sqlprep($itemcode).' and companyid='.sqlprep($active_company));
         if (!$recordSet->EOF) {
             $id=$recordSet->fields[0];
             $itemcode=''; //because we check for itemcode later on, for updates
         };
     };
     if (!$today) $today=createtime("Y-m-d");
     if ($action=="New Vendor") {
           $vendorid="";
           $id="";
     } elseif ($action=="New Item Same Vendor") {
         echo 'new item same vendor';
           $id="";
           $itemcode="";
     };
     if ($vendorid) { //have selected a vendor
          if ($id) { // have selected item
               if ($itemcode&&$itemqty) { //have received item - update the item entry
                    if ($vitemconversion<=0) $vitemconversion=1;
                    if ($noitemvendor) { //insert itemvendor
                        checkpermissions('inv');
                        itemVendorAddUpdate(1, 0, $vendorid, $id, $vordernumber, $recunitnameid, $conversion, $vitemcost1, $vitemqty1, $vitemcost2, $vitemqty2, $vitemcost3, $vitemqty3, $vitemcost4, 0);
                    } else { //update itemvendor pricing
                        if (!invitemvendorqtypricingupdate($id, $vendorid, $itemqty, $itemprice)) echo texterror($lang['STR_ERROR_UPDATING_VENDOR_QUANTITY_PRICING']);
                    };

                    //add receipt to invreceive, recsource=0 (no PO issued)
                    if (!ReceiveAddUpdate(1,"",$id,$vendorid,0,$invpoid,$today,$itemqty,$itemprice,$conversion,$track,$recunitnameid,$lastchangedate,$glacct,$composit,$locationid)) die(texterror($lang['STR_RECEIVE_ADD_UPDATE_FAILED'])); //had a problem, do not continue
                    $recordSet2 = &$conn->SelectLimit('select id from invreceive where vendorid='.sqlprep($vendorid).' and itemid='.sqlprep($id).' order by lastchangedate desc',1);
                    if (!$recordSet2->EOF) $invreceiveid=$recordSet2->fields[0];

                        //update gl journal with figures
                    if ($conn->Execute('insert into gltransvoucher (voucher,description,wherefrom,companyid,standardset,entrydate,lastchangeuserid,entryuserid) values ('.sqlprep('itemrecv'.$invreceiveid).",".sqlprep($lang['STR_ITEM_RECEIVED_NO_PO']).",".sqlprep(moduleidfromnameshort('inv')).",".sqlprep($active_company).",0,NOW(),".sqlprep($userid).",".sqlprep($userid).")") === false) {
                         echo texterror($lang['STR_ERROR_ADDING_VOUCHER_TO_MAIN_DATABASE']);
                    } else {
                      $recordSet2 = &$conn->SelectLimit('select cash from invcompany where id='.sqlprep($active_company),1);
                      if (!$recordSet2->EOF) {
                          $recordSet3 = &$conn->SelectLimit('select id from gltransvoucher where voucher='.sqlprep('itemrecv'.$invreceiveid).' order by lastchangedate desc',1);
                          if (!$recordSet3->EOF) $voucherid=$recordSet3->fields[0];
                          if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($glacct).", ".sqlprep($voucherid).", ".sqlprep($itemqty*$itemprice).')') === false) echo texterror("Error adding gl voucher detail.");
                          $revglacct=$recordSet2->fields[0];
                          if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($revglacct).", ".sqlprep($voucherid).", ".sqlprep(0-($itemqty*$itemprice)).')') === false) echo texterror("Error adding gl voucher detail.");
                      } else {
                          echo texterror($lang['STR_NO_DEFAULT_GL_ACCOUNT_SELECTED']);
                      };
                    };

                    //if itemlocation does not exist, set it up first
                    $recordSet2=&$conn->Execute('select id from itemlocation where inventorylocationid='.sqlprep($locationid).' and itemlocation.itemid='.sqlprep($id));
                    if ($recordSet2->EOF) { //does not exist
                         invitemlocationaddupdate(1,$itemid,$locationid,0,0,0,0,0,0,0,0,0,0,0,0,0,0,$id);
                         $recordSet2=&$conn->Execute('select id from itemlocation where inventorylocationid='.sqlprep($locationid).' and itemlocation.itemid='.sqlprep($id));
                         if (!$recordSet2->EOF) $itemlocationid=$recordSet->fields[0];
                         echo textsuccess($lang['STR_ADDED_ITEM_LOCATION_SUCCESSFULLY']);
                    };

                    //update on hand quantity for item received
                    if ($conn->Execute('update itemlocation set itemlocation.onhandqty=itemlocation.onhandqty+'.$itemqty.' where itemlocation.inventorylocationid='.sqlprep($locationid).' and itemlocation.itemid='.sqlprep($id)) === false) echo texterror("Error updating item on hand quantity.");
                    //update composite item on-hand figures where the item received is a main item
                    $recordSet=&$conn->Execute('select compositeitemid.subitemcodeid,compositeitemid.quantity from compositeitemid where compositeitemid.itemcodeid='.sqlprep($id));
                    while (!$recordSet->EOF) {
                             //if itemlocation does not exist, set it up first
                             $recordSet2=&$conn->Execute('select id from itemlocation where inventorylocationid='.sqlprep($locationid).' and itemlocation.itemid='.sqlprep($id));
                             if ($recordSet2->EOF) invitemlocationaddupdate(1,$recordSet->fields[0],$locationid,0,0,0,0,0,0,0,0,0,0,0,0,0,0,$id);
                      if ($conn->Execute('update itemlocation set itemlocation.onhandqty=itemlocation.onhandqty+'.($itemqty*$recordSet->fields[1]).' where itemlocation.id='.sqlprep($locationid).' and itemlocation.itemid='.sqlprep($recordSet->fields[0])) === false) echo texterror("Error updating composite sub item on hand quantity.");
                      $recordSet->MoveNext();
                    };

                    if (!invitemcompositeparentquantityupdate($id, $locationid)) echo texterror($lang['STR_ERROR_ADDING_COMPOSITE_PARENT_ITEM_ON_HAND_QUANTITY']);

                    //update first, mid & last cost figures for item location

                    $lprice=$itemprice/$vitemconversion;
                    if (!invitemlocationfirstmidlastupdate($id,$locationid,$itemqty,$lprice)) echo texterror($lang['STR_ERROR_UPDATING_COST_FIGURES_FOR_LOCATION']);
                     echo textsuccess($lang['STR_ITEMS_RECEIVED_OK']);

                     echo '<form action="invitemrecadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table border=1>';
                     echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
                     echo '<input type="hidden" name="track" value="'.$track.'">';
                     echo '<input type="hidden" name="locationid" value="'.$locationid.'">';
                     $recordSet = &$conn->Execute('select count(*) from company,vendor where company.id=vendor.orderfromcompanyid and vendor.gencompanyid='.sqlprep($active_company));
                     if (!$recordSet->EOF) if ($recordSet->fields[0]>1) echo '<input type="submit" name="action" value="'.$lang['STR_NEW_VENDOR'].'">';
                     echo '<input type="submit" name="action" value="'.$lang['STR_NEW_ITEM_SAME_VENDOR'].'"></form>';

               } else {  //get details about receipt from user
                 //read unit name from item. Then read from itemvendor if it exists
                 $recordSet=&$conn->Execute('select sunit.unitname as stockunit, punit.unitname as priceunit, item.priceunitsperstockunit,itemvendor.vordernumber,vunit.unitname, itemvendor.vitemconversion, itemvendor.vitemcost1,itemvendor.vitemqty1,itemvendor.vitemcost2,itemvendor.vitemqty2,itemvendor.vitemcost3,itemvendor.vitemqty3,itemvendor.vitemcost4,company.companyname,item.itemcode,item.description,item.inventoryglacctid,item.compositeitemyesno,sunit.id,punit.id,vunit.id from item,itemvendor,vendor,company,unitname as punit,unitname as sunit,unitname as vunit where itemvendor.vitemunitnameid=vunit.id and item.stockunitnameid=sunit.id and item.priceunitnameid=punit.id and company.id=vendor.orderfromcompanyid and vendor.gencompanyid='.sqlprep($active_company).' and vendor.id=itemvendor.vendorid and item.id=itemvendor.itemid and itemvendor.vendorid='.sqlprep($vendorid).' and item.id='.sqlprep($id));
                 if (!$recordSet->EOF) {
                         $noitemvendor=0;
                         $stockunitname=$recordSet->fields[0];
                         $priceunitname=$recordSet->fields[1];
                         $priceunitperstockunit=$recordSet->fields[2];
                         $vordernumber=$recordSet->fields[3];
                         $vitemunitname=$recordSet->fields[4];
                         $vitemconversion=$recordSet->fields[5];
                         $vitemcost1=$recordSet->fields[6];
                         $vitemqty1=$recordSet->fields[7];
                         $vitemcost2=$recordSet->fields[8];
                         $vitemqty2=$recordSet->fields[9];
                         $vitemcost3=$recordSet->fields[10];
                         $vitemqty3=$recordSet->fields[11];
                         $vitemcost4=$recordSet->fields[12];
                         $vendorname=$recordSet->fields[13];
                         $itemcode=$recordSet->fields[14];
                         $itemdescription=$recordSet->fields[15];
                         $glacct=$recordSet->fields[16];
                         $composit=$recordSet->fields[17];
                         $priceunitnameid=$recordSet->fields[18];
                         $stockunitnameid=$recordSet->fields[19];
                         $vitemunitnameid=$recordSet->fields[20];
                 } else { //itemvendor doesn't exist (or query returned no rows for another reason, but we test for error on the next query too, so we should be ok)
                      $noitemvendor=1;
                      $recordSet=&$conn->Execute('select sunit.unitname, punit.unitname, item.priceunitsperstockunit,item.itemcode,item.description,item.inventoryglacctid,item.compositeitemyesno,company.companyname,sunit.id,punit.id from item,vendor,company,unitname as sunit,unitname as punit where item.stockunitnameid=sunit.id and item.priceunitnameid=punit.id and item.id='.sqlprep($id).' and company.id=vendor.orderfromcompanyid and vendor.gencompanyid='.sqlprep($active_company).' and vendor.id='.sqlprep($vendorid));
                      if (!$recordSet->EOF) {
                               $stockunitname=$recordSet->fields[0];
                               $priceunitname=$recordSet->fields[1];
                               $priceunitperstockunit=$recordSet->fields[2];
                               $itemcode=$recordSet->fields[3];
                               $itemdescription=$recordSet->fields[4];
                               $glacct=$recordSet->fields[5];
                               $composit=$recordSet->fields[6];
                               $vendorname=$recordSet->fields[7];
                               $stockunitnameid=$recordSet->fields[8];
                               $priceunitnameid=$recordSet->fields[9];
                               $vitemcost1=0;
                               $vitemqty1=0;
                               $vitemcost2=0;
                               $vitemqty2=0;
                               $vitemcost3=0;
                               $vitemqty3=0;
                               $vitemcost4=0;
                     } else {
                               die(texterror($lang['STR_COULD_NOT_LOOK_UP_ITEM']));
                     };
                 };
                 if (!$vitemcost1) $vitemcost1=0;
                 $conversion=$priceunitperstockunit;
                 if ($vitemunitname) {
                        $punitname=$vitemunitname;
                        $recunitnameid=$vitemunitnameid;
                        $conversion=$vitemconversion;
                 } else {
                        $punitname=$priceunitname;
                        $recunitnameid=$priceunitnameid;
                        $conversion=$priceunitsperstockunit;
                 };
                 if ($conversion==0) $conversion=1; //check for divide by 0
                 $conversion=1/$conversion;
                 if ($conversion<=0) $conversion=1;
                 $sunitname=$stockunitname;
                 echo texttitle($lang['STR_VENDOR']  .$vendorname);
                 echo '<form action="invitemrecadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table border=1>';
                 echo '<tr><th colspan="3">'.$lang['STR_ITEM'].': '.$itemcode." - ".$itemdescription.'</th></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VENDOR_PRODUCT_CODE'].':</td><td><input type="text" name="vordernumber" value="'.$vordernumber.'" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_QUANTITY'].' '.$sunitname.' received:</td><td><input type="text" name="itemqty" onchange="valudatenum(this)" size="30" maxlength="20" onChange="checkprice('.$vitemcost1.','.$vitemcost2.','.$vitemcost3.','.$vitemcost4.','.$vitemqty1.','.$vitemqty2.','.$vitemqty3.', '.$vitemconversion.')"'.INC_TEXTBOX.'></td></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PRICE_PER'].' '.$punitname.':</td><td><input type="text" name="itemprice" onchange="valudatenum(this)" value="'.$vitemcost1.'" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
                 echo '<tr><th align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COST_PER'].' '.$punitname.'</th><th>'.$lang['STR_UP_TO_THIS_QUANTITY_OF'].' '.$punitname.'</th><tr>';
                 if (!$noitemvendor) {
                      echo '<tr><td>'.$vitemcost1.'</td><td>'.$vitemqty1.'</td></tr>';
                      echo '<tr><td>'.$vitemcost2.'</td><td>'.$vitemqty2.'</td></tr>';
                      echo '<tr><td>'.$vitemcost3.'</td><td>'.$vitemqty3.'</td></tr>';
                      echo '<tr><td>'.$vitemcost4.'</td><td>'.$lang['STR_AND_UP'].'</td></tr>';
                 } else {
                      echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"><input type="text" name="vitemcost1" onchange="validatenum(this)" value="0"'.INC_TEXTBOX.'></td><td><input type="text" name="vitemqty1" onchange="validatenum(this)" value="0"'.INC_TEXTBOX.'></td></tr>';
                      echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"><input type="text" name="vitemcost2" onchange="validatenum(this)" value="0"'.INC_TEXTBOX.'></td><td><input type="text" name="vitemqty2" onchange="validatenum(this)" value="0"'.INC_TEXTBOX.'></td></tr>';
                      echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"><input type="text" name="vitemcost3" onchange="validatenum(this)" value="0"'.INC_TEXTBOX.'></td><td><input type="text" name="vitemqty3" onchange="validatenum(this)" value="0"'.INC_TEXTBOX.'></td></tr>';
                      echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"><input type="text" name="vitemcost4" onchange="validatenum(this)" value="0"'.INC_TEXTBOX.'></td><td>and up</td></tr>';
                 };
                 echo '<input type="hidden" name="id" value="'.$id.'">';
                 echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
                 echo '<input type="hidden" name="noitemvendor" value="'.$noitemvendor.'">';
                 echo '<input type="hidden" name="itemcode" value="'.$itemcode.'">';
                 echo '<input type="hidden" name="conversion" value="'.$conversion.'">';
                 echo '<input type="hidden" name="recunitnameid" value="'.$recunitnameid.'">';
                 echo '<input type="hidden" name="glacct" value="'.$glacct.'">';
                 echo '<input type="hidden" name="composit" value="'.$composit.'">';
                 echo '<input type="hidden" name="track" value="'.$track.'">';
                 echo '<input type="hidden" name="locationid" value="'.$locationid.'">';
                 echo '<input type="hidden" name="vitemconversion" value="'.$vitemconversion.'">';
                 echo '<tr><td><input type="submit" value="'.$lang['STR_SAVE_DATA'].'"></td></tr></form>';
               };
          } else { //display items, let the user pick one to edit
            echo '<form action="invitemrecadd.php" method="post" name="mainform"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ITEM_CODE'].':</td><td><input type="text" name="itemcode" size="30" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a></td></tr>';
            echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
            echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DATE_RECEIVED'].':</td><td><input type="text" name="today" onchange="formatDate(this)" value="'.$today.'" '.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.todate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
            echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TRACKING_NUMBER_BOL_OR_OTHER'].'</td><td><input type="text" name="track" value="'.$track.'" '.INC_TEXTBOX.'></td></tr>';
            $recordSet = &$conn->Execute('select count(*) from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company));
            if (!$recordSet->EOF) if ($recordSet->fields[0]>1) {
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_RECEIVE_LOCATION'].':</td><td><select name="locationid" '.INC_TEXTBOX.'>';
                $recordSet = &$conn->Execute('select inventorylocation.id, company.companyname from inventorylocation, company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
                echo '<option value="">'."\n";
                while (!$recordSet->EOF) {
                     echo '<option value="'.$recordSet->fields[0].'"'.checkequal($locationid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1]."\n";
                     $recordSet->MoveNext();
                };
            } else {
                       $recordSet = &$conn->Execute('select inventorylocation.id from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
                if (!$recordSet->EOF) echo '<input type="hidden" name="locationid" value="'.$recordSet->fields[0].'">';
            };
            echo '</table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
          };
     } else { //display vendors, let user pick one
          echo '<form action="invitemrecadd.php" method="post" name="mainform"><table>';
          formapvendorselect('vendorid');
          echo '</table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
          
          
          echo '</center>';
     };

?>

<?php include('includes/footer.php'); ?>
