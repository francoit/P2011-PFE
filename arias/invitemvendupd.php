<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
     function checkvalue() {
          var f1 = document.mainform;

          if (f1.vitemcost1.value<=0) {
               alert($lang['STR_PLEASE_SPECIFY_A_COST']);
               return false;
          }

          if (f1.vitemconversion.value<=0) {
               f1.vitemconversion.value=1;
          }
          if (f1.vitemqty1.value=="") {
               f1.vitemqty1.value="0";
          }
          if (f1.vitemcost2.value=="") {
               f1.vitemcost2.value="0";
          }
          if (f1.vitemqty2.value=="") {
               f1.vitemqty2.value="0";
          }
          if (f1.vitemcost3.value=="") {
               f1.vitemcost3.value="0";
          }
          if (f1.vitemqty3.value=="") {
               f1.vitemqty3.value="0";
          }
          if (f1.vitemcost4.value=="") {
               f1.vitemcost4.value="0";
          }

          return true;
     }
</script>
<?
     echo '<center>';
     echo texttitle($lang['STR_ITEM_VENDOR_UPDATE_ADD']);
     if ($itemcode&&!$id) { //if user entered an itemcode to update, get the id and use it for reference
         $recordSet = &$conn->Execute('select id from item where itemcode='.sqlprep($itemcode).' and companyid='.sqlprep($active_company));
         if ($recordSet&&!$recordSet->EOF) {
             $id=$recordSet->fields[0];
             $itemcode=''; //because we check for itemcode later on, for updates
         };
     };
     if (!$vendorid) { //set vendor if only one
         $recordSet = &$conn->Execute('select count(*) from vendor,company where vendor.cancel=0 and vendor.gencompanyid='.sqlprep($active_company).' and company.id=vendor.orderfromcompanyid');
         if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]==1) {
             $recordSet = &$conn->Execute('select vendor.id from vendor,company where vendor.cancel=0 and vendor.gencompanyid='.sqlprep($active_company).' and company.id=vendor.orderfromcompanyid order by company.companyname');
             if ($recordSet&&!$recordSet->EOF) $vendorid=$recordSet->fields[0];
         };
     };
     if ($id) { // if the user has selected an item
       if ($vendorid) { //user has selected a vendor for the item
          if ($delete) { //if we should be deleting the entry
               if(itemVendorDelete($vitemid,$delete-1)) echo textsuccess($lang['STR_ITEM_VENDOR_INFORMATION_DELETED_SUCCESSFULLY']); // $delete=1 to delete, delete=2 to activate
          } elseif ($addnew=="Save") { //submitted values
               if ($vitemid) { //update the item entry
                     $AddUpdate=0; // Set to 0 for UPDATE (1=add)
               } else {
                     $AddUpdate=1;
               };
               itemVendorAddUpdate($AddUpdate,$vitemid,$vendorid,$id, $vordernumber,$vitemunitnameid, $vitemconversion, $vitemcost1, $vitemqty1, $vitemcost2, $vitemqty2, $vitemcost3, $vitemqty3,$vitemcost4,$lastchangedate);
               echo '<br><br><a href="invitemvendupd.php">'.$lang['STR_SELECT_ANOTHER_ITEM'].'</a><br><br>';
               echo '<br><br><a href="invitemvendupd.php?id='.$id.'">'.$lang['STR_ADD_UPDATE_ANOTHER_VENDOR_SAME_ITEM'].'</a><br><br>';
          } else { // display more info about the entry that the user can edit
               echo '<form action="invitemvendupd.php" method="post" name="mainform" onsubmit="return checkvalue()"><table><input type="hidden" name="id" value="'.$id.'">';
               echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
               $recordSet = &$conn->Execute('select itemvendor.id,itemvendor.vordernumber,itemvendor.vitemunitnameid,itemvendor.vitemconversion,itemvendor.vitemcost1,itemvendor.vitemqty1,itemvendor.vitemcost2,itemvendor.vitemqty2,itemvendor.vitemcost3,itemvendor.vitemqty3,itemvendor.vitemcost4,item.stockunitnameid,item.priceunitnameid,item.itemcode,item.description,company.companyname,itemvendor.lastchangedate  from itemvendor,item,vendor,company where company.id=vendor.orderfromcompanyid and itemvendor.itemid=item.id and itemvendor.itemid='.sqlprep($id).' and itemvendor.vendorid='.sqlprep($vendorid).' and vendor.id=itemvendor.vendorid and itemvendor.cancel=0');
               if ($recordSet&&!$recordSet->EOF) {
                     $vitemid=$recordSet->fields[0];
                     $vordernumber=rtrim($recordSet->fields[1]);
                     $vitemunitnameid=$recordSet->fields[2];  //unit name of purchase site
                     $vitemconversion=$recordSet->fields[3];  //conversion from purchase unit to sell price unit
                     $vitemcost1=num_format($recordSet->fields[4],PREFERRED_DECIMAL_PLACES);
                     $vitemqty1=$recordSet->fields[5];
                     $vitemcost2=num_format($recordSet->fields[6],PREFERRED_DECIMAL_PLACES);
                     $vitemqty2=$recordSet->fields[7];
                     $vitemcost3=num_format($recordSet->fields[8],PREFERRED_DECIMAL_PLACES);
                     $vitemqty3=$recordSet->fields[9];
                     $vitemcost4=num_format($recordSet->fields[10],PREFERRED_DECIMAL_PLACES);
                     $recordSet2=&$conn->Execute('select unitname from unitname where id='.sqlprep($vitemunitnameid));
                     if ($recordSet2&&!$recordSet2->EOF) $purchaseunitname=rtrim($recordSet2->fields[0]);
                     $recordSet2=&$conn->Execute('select unitname from unitname where id='.sqlprep($recordSet->fields[12]));
                     if ($recordSet2&&!$recordSet2->EOF) $priceunitname=rtrim($recordSet2->fields[0]);
                     $recordSet2=&$conn->Execute('select unitname from unitname where id='.sqlprep($recordSet->fields[11]));
                     if ($recordSet2&&!$recordSet2->EOF) $stockunitname=rtrim($recordSet2->fields[0]);
                     $itemcode=rtrim($recordSet->fields[13]);
                     $itemdescription=rtrim($recordSet->fields[14]);
                     $vendorname=rtrim($recordSet->fields[15]);
                     $lastchangedate=$recordSet->fields[16];
               } else {
                     //new entry, so read vendor name and item info from those files
                     $recordSet=&$conn->Execute('select item.stockunitnameid,item.priceunitnameid,item.itemcode,item.description from item where item.id='.sqlprep($id));
                     if ($recordSet&&!$recordSet->EOF) {
                           $itemcode=rtrim($recordSet->fields[2]);
                           $itemdescription=rtrim($recordSet->fields[3]);
                           $recordSet2=&$conn->Execute('select unitname from unitname where id='.sqlprep($recordSet->fields[1]));
                           if ($recordSet2&&!$recordSet2->EOF) $priceunitname=rtrim($recordSet2->fields[0]);
                           $recordSet2=&$conn->Execute('select unitname from unitname where id='.sqlprep($recordSet->fields[0]));
                           if ($recordSet2&&!$recordSet2->EOF) $stockunitname=rtrim($recordSet2->fields[0]);
                      };
                      $recordSet=&$conn->Execute('select company.companyname from company,vendor where vendor.id='.sqlprep($vendorid).' and vendor.orderfromcompanyid=company.id');
                      if ($recordSet&&!$recordSet->EOF) $vendorname=rtrim($recordSet->fields[0]);
               };
               echo '<tr><th colspan="2" align="center">'.$lang['STR_ITEM'].': '.$itemcode." - ".$itemdescription.'</th></tr>';
               echo '<tr><th colspan="2" align="center">'.$lang['STR_VENDOR'].': '.$vendorname.'</th></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VENDOR_PRODUCT_CODE'].':</td><td><input type="text" name="vordernumber" value="'.$vordernumber.'" size="30" maxlength="20" '.INC_TEXTBOX.'></td></tr>';
               if ($cancel) echo '<th>'.$lang['STR_VENDOR_ITEM_DELETED'].'</th></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PURCHASE_UNIT'].':</td><td><select name="vitemunitnameid">';
               $recordSet = &$conn->Execute('select id,unitname from unitname order by unitname');
               while ($recordSet&&!$recordSet->EOF) {
                              echo '<option value="'.$recordSet->fields[0].'"'.checkequal($vitemunitnameid,$recordSet->fields[0]," selected").'>'.rtrim($recordSet->fields[1])."\n";
                              $recordSet->MoveNext();
               };
               echo '</select></td></tr>';
               echo '<tr><td>'.$lang['STR_NUMBER_OF'].' '.$stockunitname.' '.$lang['STR_PER_PURCHASE_UNIT'].':</td><td><input type="text" name="vitemconversion" value="'.$vitemconversion.'" size="30" maxlength="20" '.INC_TEXTBOX.'></td></tr>';
               echo '<tr><th>Cost</th><th>'.$lang['STR_UP_TO_THIS_QUANTITY_OF_PURCHASE_UNITS'].'</th></tr>';
               echo '<tr><td><input type="text" name="vitemcost1" onchange="validatenum(this)" value="'.$vitemcost1.'" size="30" maxlength="20" '.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="vitemqty1" onchange="validatenum(this)" value="'.$vitemqty1.'" size="30" maxlength="20" '.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td><input type="text" name="vitemcost2" onchange="validatenum(this)" value="'.$vitemcost2.'" size="30" maxlength="20" '.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="vitemqty2" onchange="validatenum(this)" value="'.$vitemqty2.'" size="30" maxlength="20" '.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td><input type="text" name="vitemcost3" onchange="validatenum(this)" value="'.$vitemcost3.'" size="30" maxlength="20" '.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="vitemqty3" onchange="validatenum(this)" value="'.$vitemqty3.'" size="30" maxlength="20" '.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td><input type="text" name="vitemcost4" onchange="validatenum(this)" value="'.$vitemcost4.'" size="30" maxlength="20" '.INC_TEXTBOX.'></td>';
               echo '<th>'.$lang['STR_OVER_ABOVE_QUANTITIES'].'</th></tr>';
               echo '<input type="hidden" name="lastchangedate" value="'.$lastchangedate.'">';
               echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
               echo '<input type="hidden" name="id" value="'.$id.'">';
               echo '<input type="hidden" name="vitemid" value="'.$vitemid.'">';
               echo '</select></td></tr></table><input type="submit" name="addnew" value="'.$lang['STR_SAVE'].'"></form>';
               if ($cancel) {
                        echo '<a href="invitemvendupd.php?delete=1&id='.$id.'&vitemid='.$vitemid.'&vendorid='.$vendorid.'">'.$lang['STR_ACTIVATE_VENDOR_FOR_THIS_ITEM'].'</a>';
               } else {
                        echo '<a href="javascript:confirmdelete(\'invitemvendupd.php?delete=2&id='.$id.'&vitemid='.$vitemid.'&vendorid='.$vendorid.'\')">'.$lang['STR_DELETE_VENDOR_FOR_THIS_ITEM'].'</a>';
               };
          };

       } else { //display vendors, let user pick one to edit
          $recordSet=&$conn->Execute('select itemcode,description from item where id='.sqlprep($id));
          if ($recordSet&&!$recordSet->EOF) echo texttitle($lang['STR_ITEM'] .rtrim($recordSet->fields[0])." - ".rtrim($recordSet->fields[1]));
          echo '<form action="invitemvendupd.php" method="post" name="mainform"><table>';
          formapvendorselect('vendorid');
          echo '<input type="hidden" name="id" value="'.$id.'">';
          echo '</table><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></form>';
       };

     } else { //display items, let the user pick one to edit
          echo '<form action="invitemvendupd.php" method="post" name="mainform"><table><tr><td>'.$lang['STR_ITEM_CODE'].':</td><td><input type="text" name="itemcode" size="30" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a></td></tr></table><br><input type="submit" value="'.$lang['STR_EDIT_VENDORS_FOR_THIS_ITEM'].'"></form>';
         
         echo '</center>';
     };
?>
<?php include('includes/footer.php'); ?>
