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
<?php   echo '<center>';
     echo texttitle($lang['STR_ADD_INVENTORY_PO']);
     if ($vendorid&&$locationid&&$ponumber&&!$completepo&&!$updatepo) { //if the user has submitted initial info
          $recordSet = &$conn->Execute('select vendor.id,company.companyname from vendor, company where vendor.id='.sqlprep($vendorid).' and vendor.gencompanyid='.sqlprep($active_company).' and vendor.orderfromcompanyid=company.id and vendor.cancel=0');
          if ($recordSet->EOF) die(texterror($lang['STR_VENDOR_NOT_FOUND']));
          echo texttitle('<font size="-1">'.$lang['STR_VENDOR'].': '.$recordSet->fields[1].' - '.$lang['STR_PO_NUMBER'].' '.$ponumber.'</font>');
          echo '<form action="invpoadd.php" method="post" name="mainform"><table border="1">';
          echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
          echo '<input type="hidden" name="locationid" value="'.$locationid.'">';
          echo '<input type="hidden" name="invpodate" value="'.$duedate.'">';
          echo '<input type="hidden" name="duedate" value="'.$duedate.'">';
          echo '<input type="hidden" name="ponumber" value="'.$ponumber.'">';
          echo '<input type="hidden" name="carrierserviceid" value="'.$carrierserviceid.'">';
          echo '<input type="hidden" name="contact" value="'.$contact.'">';
          echo '<input type="hidden" name="requisition" value="'.$requisition.'">';
          echo '<input type="hidden" name="ordernumber" value="'.$ordernumber.'">';
          echo '<input type="hidden" name="tracknumber" value="'.$tracknumber.'">';
          echo '<input type="hidden" name="currencyid" value="'.$currencyid.'">';
          echo '<input type="hidden" name="notes" value="'.$notes.'">';
          for ($i=1; ${"itemcode".$i}; $i++) { //check fields that should be checked
               $recordSet = &$conn->Execute('select id,priceunitsperstockunit from item where itemcode='.sqlprep(${"itemcode".$i}));
               if (!$recordSet->EOF) {
                    ${"itemid".$i}=$recordSet->fields[0];
                    $ppsu=$recordSet->fields[1];
                    if ($ppsu<=0) $ppsu=1;
                    if (${"itemqtyorder".$i}=="") ${"itemqtyorder".$i}=1;
                    if (${"itempriceach".$i}=="") {
                         $recordSet1 = &$conn->Execute('select markupsetid from itemlocation where itemid='.sqlprep(${"itemid".$i}).' and inventorylocationid='.sqlprep($locationid));
                         if (!$recordSet1->EOF) ${"markupset".$i}=$recordSet1->fields[0];
                         ${"itempriceach".$i}=invitemprice(${"itemid".$i}, ${"markupset".$i}, $pricelevelid, ${"itemqtyorder".$i})*$ppsu;
                    };
               } else {
                    ${"itemqtyorder".$i}=0;
               };
          };
          echo '<tr><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_UNIT_PER_PACKAGE'].'</th><th>'.$lang['STR_QUANTITY'].'</th><th>'.$lang['STR_PRICE_PER_PACKAGE'].'</th></tr>';
          echo '<tr><td><input type="text" name="itemcode'.$i.'" size="15" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode'.$i.'&&inventorylocationid='.$locationid.'\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'invitemadd.php?locationid='.$locationid.'\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="images/addbutton.png" border="0" alt="Vendor Add"></a></td><td><input type="text" name="unitperpack'.$i.'" size="10" maxlength="10"'.INC_TEXTBOX.'></td><td><input type="text" name="itemqtyorder'.$i.'" size="10" maxlength="10"'.INC_TEXTBOX.'></td><td><input type="text" name="itempriceach'.$i.'" size="10" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
          for ($i=1; ${"itemcode".$i}; $i++) { //pass prev submitted items to next form
               if ($i==1) echo '<tr><td colspan="3">&nbsp;</td></tr>';
               if (${"itemqtyorder".$i}) { //if quantity isn't 0
                    echo '<tr><td><input type="text" name="itemcode'.$i.'" size="15" maxlength="20" value="'.${"itemcode".$i}.'"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode'.$i.'&&inventorylocationid='.$locationid.'\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a>';
                    $recordSet = &$conn->Execute("select item.description from item where item.id=".sqlprep(${"itemid".$i}));
                    if (!$recordSet->EOF) echo ' '.$recordSet->fields[0];
                    echo '</td><td><input type="text" name="unitperpack'.$i.'" onchange="validatenum(this)" size="10" maxlength="10" value="'.${"unitperpack".$i}.'"'.INC_TEXTBOX.'></td><td><input type="text" name="itemqtyorder'.$i.'" onchange="validatenum(this)" size="10" maxlength="10" value="'.${"itemqtyorder".$i}.'"'.INC_TEXTBOX.'></td><td><input type="text" name="itempriceach'.$i.'" onchange="validatenum(this)" size="10" maxlength="15" value="'.checkdec(${"itempriceach".$i},PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td></tr>';
                    $total+=${"itemqtyorder".$i}*${"itempriceach".$i};
               };
          };
          $currencysymbol = &$conn->Execute('select currency.id,currency.iso4217 from currency where id='.sqlprep($currencyid));
          echo '<tr><td colspan="3"><div align="right"><b>'.$lang['STR_TOTAL'].'('.$currencysymbol->fields[1].'):</b></div></td><td>'.checkdec($total,PREFERRED_DECIMAL_PLACES).'</td></tr>';
          echo '</table><input type="submit" name="additemtopo" value="'.$lang['STR_ADD_LINE_ITEM_TO_PO'].'">';
          for ($i=1; ${"itemid".$i}; $i++) {
               if (${"itemid".$i}&&${"itemqtyorder".$i}) $canclose=1;
          };
          if ($canclose) echo '<input type="submit" name="completepo" value="'.$lang['STR_COMPLETE_PO'].'"></form>';
     } elseif ($vendorid&&$locationid&&$ponumber&&$completepo) { //finish stuff
          checkpermissions('inv');
//          if ($conn->Execute('insert into invpo (vendorid,ponumber,duedate,locationid,carrierserviceid,tracknumber,contact,requisition,ordernumber,currencyid,entrydate,entryuserid,lastchangeuserid,gencompanyid) values ('.sqlprep($vendorid).', '.sqlprep($ponumber).', '.sqlprep($duedate).', '.sqlprep($locationid).', '.sqlprep($carrierserviceid).', '.sqlprep($tracknumber).', '.sqlprep($contact).', '.sqlprep($requisition).', '.sqlprep($ordernumber).', '.sqlprep($currencyid).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).', '.sqlprep($active_company).')') === false) {
          if ($conn->Execute('insert into invpo (vendorid,ponumber,duedate,locationid,carrierserviceid,tracknumber,contact,requisition,ordernumber,entrydate,entryuserid,lastchangeuserid,gencompanyid) values ('.sqlprep($vendorid).', '.sqlprep($ponumber).', '.sqlprep($duedate).', '.sqlprep($locationid).', '.sqlprep($carrierserviceid).', '.sqlprep($tracknumber).', '.sqlprep($contact).', '.sqlprep($requisition).', '.sqlprep($ordernumber).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).', '.sqlprep($active_company).')') === false) {
               die(texterror($lang['STR_ERROR_INSERTING_INVENTORY_PO']));
          } else {
               $recordSet = &$conn->SelectLimit('select id from invpo where ponumber='.sqlprep($ponumber).' order by entrydate desc',1);
               if (!$recordSet->EOF) $invpoid=$recordSet->fields[0];
               for ($i=1; ${"itemcode".$i}; $i++) {
                    $recordSet2 = &$conn->Execute('select id from item where itemcode='.sqlprep(${"itemcode".$i}).' and item.companyid='.sqlprep($active_company));
                    if (!$recordSet2->EOF) ${"itemid".$i}=$recordSet2->fields[0];
                    if ($conn->Execute('insert into invpodetail (invpoid,itemid,unitperpack,itemqty,itemprice) values ('.sqlprep($invpoid).', '.sqlprep(${"itemid".$i}).', '.sqlprep(${"unitperpack".$i}).', '.sqlprep(${"itemqtyorder".$i}).', '.sqlprep(${"itempriceach".$i}).')') === false) echo texterror($lang['STR_ERROR_INSERTING_INV_PO_DETAIL'] .$i);
               };
               if ($conn->Execute("insert into invponotes (invpoid,note,lastchangeuserid) VALUES (".sqlprep($invpoid).", ".sqlprep($notes).", ".sqlprep($userid).")") === false) echo texterror('Error inserting inventory notes');
               echo textsuccess($lang['STR_INVENTORY_PO_ADDED_SUCCESSFULLY']);
               echo '<br><a href="javascript:doNothing()" onclick="top.newWin = window.open (\'invpoview.php?printable=1&invpoid='.$invpoid.'\',\'print\',\'dependent=yes,width=800,height=650,screenX=10,screenY=100,titlebar=yes,scrollbars=1,resizable=0\')">'.$lang['STR_PRINT_THIS_PO'].'</a>';
          };
          echo texttitle($lang['STR_UPDATE_GENERAL_PO_INFO']);
          echo '<form action="invpoadd.php" method="post" name="mainform"><table>';
          echo '<input type="hidden" name="invpoid" value="'.$invpoid.'">';
          echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
          echo '<input type="hidden" name="locationid" value="'.$locationid.'">';
          echo '<input type="hidden" name="duedate" value="'.$duedate.'">';
          echo '<input type="hidden" name="ponumber" value="'.$ponumber.'">';
          echo '<input type="hidden" name="carrierserviceid" value="'.$carrierserviceid.'">';
          echo '<input type="hidden" name="contact" value="'.$contact.'">';
          echo '<input type="hidden" name="requisition" value="'.$requisition.'">';
          echo '<input type="hidden" name="ordernumber" value="'.$ordernumber.'">';
          echo '<input type="hidden" name="tracknumber" value="'.$tracknumber.'">';
          echo '<input type="hidden" name="currencyid" value="'.$currencyid.'">';
          forminvpoupdate($vendorid,$contact,$ordernumber,$ponumber,$duedate,$requisition,$locationid,$carrierserviceid,$tracknumber,$currencyid);
          echo '</table><input type="submit" name="updatepo" value="'.$lang['STR_UPDATE_PO'].'"></form>';
     } elseif ($vendorid&&$locationid&&$ponumber&&$updatepo) { //finish stuff
          checkpermissions('inv');
          if ($conn->Execute('update invpo set vendorid='.sqlprep($vendorid).',ponumber='.sqlprep($ponumber).',duedate='.sqlprep($duedate).',locationid='.sqlprep($locationid).',carrierserviceid='.sqlprep($carrierserviceid).',tracknumber='.sqlprep($tracknumber).',contact='.sqlprep($contact).',requisition='.sqlprep($requisition).',ordernumber='.sqlprep($ordernumber).',currencyid='.sqlprep($currencyid).' where id='.sqlprep($invpoid)) === false) {
               die(texterror($lang['STR_ERROR_UPDATING_INVENTORY_PO']));
          } else {
               echo textsuccess($lang['STR_PO_UPDATED_SUCCESSFULLY']);
               echo '<br><a href="javascript:doNothing()" onclick="top.newWin = window.open (\'invpoview.php?printable=1&invpoid='.$invpoid.'\',\'print\',\'dependent=yes,width=800,height=400,screenX=10,screenY=100,titlebar=yes,scrollbars=1,resizable=0\')">'.$lang['STR_PRINT_THIS_PO'].'</a>';
          };
     } else { //display vendors, let user pick one
          echo '<form action="invpoadd.php" method="post" name="mainform"><table>';
          formapvendorselect('vendorid');
          forminvpoadd();
          //defcurrency('DEFAULT_CURRENCY');
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NOTES'].':</td><td><textarea name="notes" rows="3" cols="25"></textarea></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
          
          echo '</center>';
     };

?>

<?php include('includes/footer.php'); ?>
