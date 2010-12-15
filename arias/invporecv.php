<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
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
     echo texttitle($lang['STR_INVENTORY_PO_RECEIVED']);
     if ($receivepo||$showlist||$submit) {
          if ($vendorid) $vendorstr=' and vendor.id='.sqlprep($vendorid);
          if ($ponumber) $ponumberstr=' and invpo.ponumber='.sqlprep($ponumber);
          if ($requisition) $requisitionstr=' and invpo.requisition='.sqlprep($requisition);
          if ($duedate) $duedatestr=' and invpo.duedate='.sqlprep($duedate);
          if ($ordernumber) $ordernumberstr=' and invpo.ordernumber='.sqlprep($ordernumber);
          if ($locationid) $locationidstr=' and inventorylocation.id='.sqlprep($locationid);
          if ($carrierserviceid) $carrierstr=' and carrierservice.id='.sqlprep($carrierserviceid);
          if ($invpoid) $invpostr=' and invpo.id='.sqlprep($invpoid);
          $recordSet = &$conn->Execute('select count(*) from invpo,vendor,company as vcompany,company as icompany,inventorylocation,company as ccompany,carrier,carrierservice where inventorylocation.companyid=icompany.id and invpo.complete=0 and invpo.locationid=inventorylocation.id and invpo.carrierserviceid=carrierservice.id and carrier.id=carrierservice.carrierid and ccompany.id=carrier.companyid and invpo.vendorid=vendor.id and vendor.orderfromcompanyid=vcompany.id'.$vendorstr.$ponumberstr.$requisitionstr.$ordernumberstr.$duedatestr.$locationidstr.$carrierstr.$invpostr);
          if ($recordSet->fields[0]>1) {
               $recordSet = &$conn->Execute('select invpo.ponumber,vcompany.companyname,invpo.duedate,icompany.companyname,ccompany.companyname,carrierservice.description,invpo.requisition,invpo.ordernumber,invpo.id,invpo.complete from invpo,vendor,company as vcompany,company as icompany,inventorylocation,company as ccompany,carrier,carrierservice where inventorylocation.companyid=icompany.id and invpo.locationid=inventorylocation.id and invpo.complete=0 and invpo.carrierserviceid=carrierservice.id and carrier.id=carrierservice.carrierid and ccompany.id=carrier.companyid and invpo.vendorid=vendor.id and vendor.orderfromcompanyid=vcompany.id'.$vendorstr.$ponumberstr.$requisitionstr.$ordernumberstr.$duedatestr.$locationidstr.$carrierstr.$invpostr.' and vendor.gencompanyid='.sqlprep($active_company).' order by invpo.duedate');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_POS_FOUND']));
               echo '<table border="1"><tr><th>'.$lang['STR_PO_NUMBER'].'</th><th>'.$lang['STR_VENDOR_NAME'].'</th><th>'.$lang['STR_DUE_DATE'].'</th><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_SHIPPING_METHOD'].'</th><th>'.$lang['STR_REQUISITION_NUMBER'].'</th><th>'.$lang['STR_ORDER_NUMBER'].'</th></tr>';
               while (!$recordSet->EOF) {
                    if (!$recordSet->fields[9]) $link = '<a href="invporecv.php?submit=1&invpoid='.$recordSet->fields[8].'">';
                    echo '<tr><td>'.$link.$recordSet->fields[0].'</a></td><td>'.$recordSet->fields[1].'</td><td><nobr>'.$recordSet->fields[2].'</nobr></td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].' - '.$recordSet->fields[5].'</td><td>'.$recordSet->fields[6].'</td><td>'.$recordSet->fields[7].'</td></tr>';
                    $recordSet->MoveNext();
                    unset($link);
               };
               echo '</table>';
          } elseif ($recordSet->fields[0]==1) {
               if (!$receivepo) {
                    $recordSet = &$conn->Execute('select invpo.ponumber,vcompany.companyname,invpo.duedate,icompany.companyname,ccompany.companyname,carrierservice.description,invpo.requisition,invpo.ordernumber,invpo.id,invpo.vendorid,invpo.tracknumber,invpo.locationid from invpo,vendor,company as vcompany,company as icompany,inventorylocation,company as ccompany,carrier,carrierservice where inventorylocation.companyid=icompany.id and invpo.complete=0 and invpo.locationid=inventorylocation.id and invpo.carrierserviceid=carrierservice.id and carrier.id=carrierservice.carrierid and ccompany.id=carrier.companyid and invpo.vendorid=vendor.id and vendor.orderfromcompanyid=vcompany.id'.$vendorstr.$ponumberstr.$requisitionstr.$ordernumberstr.$duedatestr.$locationidstr.$carrierstr.$invpostr.' and vendor.gencompanyid='.sqlprep($active_company).' order by invpo.duedate');
                    if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_POS_FOUND'])); //shouldnt ever happen, as the if parent statement checks for this
                    $invpoid=$recordSet->fields[8];
                    $track=$recordSet->fields[10];
                    echo '<form action="invporecv.php" method="post" name="mainform">';
                    echo '<input type="hidden" name="invpoid" value="'.$recordSet->fields[8].'">';
                    echo '<input type="hidden" name="vendorid" value="'.$recordSet->fields[9].'">';
                    echo '<input type="hidden" name="locationid" value="'.$recordSet->fields[11].'">';
                    echo '<table border="1"><tr><th>'.$lang['STR_PO_NUMBER'].'</th><th>'.$lang['STR_VENDOR_NAME'].'</th><th>'.$lang['STR_DUE_DATE'].'</th><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_SHIPPING_METHOD'].'</th><th>'.$lang['STR_REQUISITION_NUMBER'].'</th><th>'.$lang['STR_ORDER_NUMBER'].'</th></tr>';
                    echo '<tr><td>'.$recordSet->fields[0].'</td><td>'.$recordSet->fields[1].'</td><td><nobr>'.$recordSet->fields[2].'</nobr></td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].' - '.$recordSet->fields[5].'</td><td>'.$recordSet->fields[6].'</td><td>'.$recordSet->fields[7].'</td></tr>';
                    echo '</table>';
                    $recordSet = &$conn->Execute('select invpodetail.id, item.itemcode, item.description, itemvendor.vordernumber, invpodetail.itemqty, invpodetail.itemprice, item.id, invpodetail.unitperpack from item cross join invpodetail left join itemvendor on itemvendor.itemid=item.id and itemvendor.vendorid='.sqlprep($recordSet->fields[9]).' where invpodetail.itemid=item.id and item.companyid='.sqlprep($active_company).' and invpodetail.invpoid='.sqlprep($recordSet->fields[8]));
                    echo '<table border="1"><tr><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_DESCRIPTION'].'</th><th>'.$lang['STR_VENDOR'].'<br>'.$lang['STR_ITEM_CODE'].'</th><th><font size="-1">'.$lang['STR_QUANTITY'].'<br>'.$lang['STR_ORDERED'].'</font></th><th><font size="-1">'.$lang['STR_QUANTITY'].'<br>'.$lang['STR_PREVIOUSLY'].'<br>'.$lang['STR_RECEIVED'].'</font></th><th><font size="-1">'.$lang['STR_QUANTITY'].'<br>'.$lang['STR_RECEIVED'].'</font></th><th>'.$lang['STR_PRICE'].'</th></tr>';
                    while (!$recordSet->EOF) {
                         $quantity=($recordSet->fields[4]*$recordSet->fields[7]);
                         $recordSet2 = &$conn->Execute('select sum(invreceive.itemqty) from invreceive where invreceive.invpoid='.sqlprep($invpoid).' and invreceive.itemid='.sqlprep($recordSet->fields[6]));
                         if (!$recordSet2->EOF&&$recordSet2->fields[0]) $quantity=$quantity-$recordSet2->fields[0];
                         echo '<input type="hidden" name="itemid'.$recordSet->fields[0].'" value='.$recordSet->fields[6].'>';
                         echo '<tr><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td align="right">'.checkdec(($recordSet->fields[4]*$recordSet->fields[7]),PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.checkdec($recordSet2->fields[0],PREFERRED_DECIMAL_PLACES).'</td><td align="right"><input type="text" name="quantity'.$recordSet->fields[0].'" value="'.checkdec($quantity,0).'" size="10" maxlength="10"'.INC_TEXTBOX.'></td><td align="right"><input type="text" name="price'.$recordSet->fields[0].'" value="'.checkdec($recordSet->fields[5],PREFERRED_DECIMAL_PLACES).'" size="10" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                         $recordSet->MoveNext();
                    };
                    echo '</table>';
                    echo ''.$lang['STR_TRACKING_NUMBER'].' :&nbsp;&nbsp;<input type="text" name="track" value="'.$track.'"'.INC_TEXTBOX.'><br>';
                    echo ''.$lang['STR_PO_COMPLETE'].' :&nbsp;&nbsp;<input type="checkbox" name="complete" value="1" checked'.INC_TEXTBOX.'><br>';
                    echo '<input type="submit" name="receivepo" value="'.$lang['STR_RECEIVE_PO'].'"></form>';
               } else { //code to mark po as received, and do other stuff
                    $recordSet = &$conn->Execute('select invpodetail.id from invpodetail where invpodetail.invpoid='.sqlprep($invpoid));
                    while (!$recordSet->EOF) {
                         //update receive file
                         if (${"quantity".$recordSet->fields[0]}) {
                             if ($conn->Execute('insert into invreceive (recsource,invpoid,receivedate,itemid,vendorid,locationid,itemqty,itemprice,track,receiveunitnameid,entrydate,entryuserid,lastchangeuserid,gencompanyid) values (1,'.sqlprep($invpoid).', NOW(), '.sqlprep(${"itemid".$recordSet->fields[0]}).', '.sqlprep($vendorid).', '.sqlprep($locationid).', '.sqlprep(${"quantity".$recordSet->fields[0]}).', '.sqlprep(${"price".$recordSet->fields[0]}).', '.sqlprep($track).', 1, NOW(), '.sqlprep($userid).', '.sqlprep($userid).', '.sqlprep($active_company).')') === false) echo die(texterror($lang['STR_ERROR_UPDATING_PO']));
                             if ($conn->Execute('update itemlocation set onhandqty=onhandqty+'.${"quantity".$recordSet->fields[0]}.' where itemid='.sqlprep(${"itemid".$recordSet->fields[0]}).' and inventorylocationid='.sqlprep($locationid)) === false) echo texterror($lang['STR_ERROR_UPDATING_ON_HAND_QUANTITY']);
                         };
                         $recordSet->MoveNext();
                    };
                    if ($complete) if ($conn->Execute("update invpo set complete='1' where id=".sqlprep($invpoid)) === false) echo die(texterror($lang['STR_ERROR_UPDATING_PO']));
                    echo textsuccess($lang['STR_PO_RECEIVED_SUCCESSFULLY']);
               };
          } else {
               echo texterror($lang['STR_NO_MATCHING_POS_FOUND']);
          };
     } else {
          echo '<form action="invporecv.php" method="post" name="mainform"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PO_NUMBER'].' :</td><td><input type="text" name="ponumber" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          $recordSet = &$conn->Execute('select count(*) from vendor, company where vendor.gencompanyid='.sqlprep($active_company).' and vendor.orderfromcompanyid=company.id and vendor.cancel=0');
          if (!$recordSet->EOF) {
                //echo ''.$lang['STR_VENDOR_COUNT'].'='.$recordSet->fields[0];
                if ($recordSet->fields[0]>1) {
                     formapvendorselect('vendorid');
                } else if ($recordSet->fields[0]>0) {
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
          $recordSet = &$conn->Execute('select count(*) from carrierservice,carrier,company where carrierservice.carrierid=carrier.id and carrier.companyid=company.id and company.cancel=0');
          if (!$recordSet->EOF) {
            if ($recordSet->fields[0]>1) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CARRIER_SERVICE'].' :</td><td><select name="carrierserviceid"'.INC_TEXTBOX.'><option value="0">';
               $recordSet = &$conn->Execute('select carrierservice.id,company.companyname,carrierservice.description from carrierservice,carrier,company where carrierservice.carrierid=carrier.id and carrier.companyid=company.id and company.cancel=0 order by company.companyname,carrierservice.description');
               while (!$recordSet->EOF) {
                    echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
                    $recordSet->MoveNext();
               };
               echo '</select></td></tr>';
            } elseif ($recordSet->fields[0]>0) {
               $recordSet = &$conn->Execute('select carrierservice.id,company.companyname,carrierservice.description from carrierservice,carrier,company where carrierservice.carrierid=carrier.id and carrier.companyid=company.id and company.cancel=0 order by company.companyname,carrierservice.description');
                  if (!$recordSet->EOF) {
                        $carrierserviceid=$recordSet->fields[0];
                        $recordSet->MoveNext();
                  };
                  echo '<input type="hidden" name="carrierserviceid" value="'.$carrierserviceid.'">';
            }
          };
          $recordSet = &$conn->Execute('select count(*) from inventorylocation,company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company));
          if (!$recordSet->EOF) {
            //echo 'location count='.$recordSet->fields[0];
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
                        $recordSet->MoveNext();
                  };
                  echo '<input type="hidden" name="locationid" value="'.$locationid.'">';
            } else {
                  die (texterror($lang['STR_NO_LOCATIONS_IN_FILE']));
            };
          } else {
             die (texterror($lang['STR_NO_LOCATIONS_IN_FILE']));
          };

          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DUE_DATE'].' :</td><td><input type="text" name="duedate" onchange="formatDate(this)" size="30" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.duedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_REQUISITION_NUMBER'].' :</td><td><input type="text" name="requisition" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VENDOR_ORDER_NUMBER'].' :</td><td><input type="text" name="ordernumber" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '</table><input type="submit" name="showlist" value="'.$lang['STR_SHOW_LIST'].'"></form>';
          echo '</center>';
     };

?>

<?php include('includes/footer.php'); ?>
