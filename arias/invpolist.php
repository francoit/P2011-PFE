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
<?php //these statement build to reload url if users selects a different sort order
if ($printable) $urlstr='printable='.$printable.'&';
if ($vendorid) $urlstr.='vendorid='.$vendorid.'&';
if ($ponumber) $urlstr.='ponumber='.$ponumber.'&';
if ($requisition) $urlstr.='requisition='.$requisition.'&';
if ($duedate) $urlstr.='duedate='.$duedate.'&';
if ($ordernumber) $urlstr.='ordernumber='.$ordernumber.'&';
if ($itemcode) $urlstr.='itemcode='.$itemcode.'&';
if ($locationid) $urlstr.='locationid='.$locationid.'&';
if ($carrierserviceid) $urlstr.='carrierserviceid='.$carrierserviceid.'&';
if ($showord) $urlstr.='showord='.$showord.'&';
if ($pend) $urlstr.='pend='.$pend.'&';
if ($recv) $urlstr.='recv='.$recv.'&';
if ($submit) $urlstr.='submit='.$submit.'&';
?>
<script language="JavaScript">
function loca()
{
     location.href = 'invpolist.php?<?=$urlstr;?>order='+document.mainform.order.value;
}
</script>
<script language="JavaScript">
     function checkChoice(i) {
          if (document.mainform.pend.checked == false) {
               if (document.mainform.recv.checked == false) {
                    if (i=="1") {
                         document.mainform.recv.checked = true;
                    } else {
                         document.mainform.pend.checked = true;
                    }
               }
          }
     }
</script>
<?
     echo '<center>';
     echo texttitle($lang['STR_INVENTORY_PO_LIST']);
     if ($submit) {
          if ($vendorid) $vendorstr=' and vendor.id='.sqlprep($vendorid);
          if ($ponumber) $ponumberstr=' and invpo.ponumber='.sqlprep($ponumber);
          if ($requisition) $requisitionstr=' and invpo.requisition='.sqlprep($requisition);
          if ($duedate) $duedatestr=' and invpo.duedate='.sqlprep($duedate);
          if ($ordernumber) $ordernumberstr=' and invpo.ordernumber='.sqlprep($ordernumber);
          if ($itemcode) $itemcodestr=' and invpodetail.itemid=item.id and item.itemcode='.sqlprep($itemcode);
          if ($locationid) $locationidstr=' and inventorylocation.id='.sqlprep($locationid);
          if ($carrierserviceid) $carrierstr=' and carrierservice.id='.sqlprep($carrierserviceid);
          if (!$pend||!$recv) {
               if ($pend) $typestr=' and invpo.complete=0';
               if ($recv) $typestr=' and invpo.complete=1';
          };
          switch ($order) {
               case 1: //by item code
                    $orderstr=' order by item.itemcode';
                    break;
               case 2: //by vendor name
                    $orderstr=' order by vcompany.companyname';
                    break;
               case 3: //by item description
                    $orderstr=' order by item.description';
                    break;
               case 4: //by po number
                    $orderstr=' order by invpo.ponumber';
                    break;
               default:
                    $orderstr=' order by invpo.duedate';
                    break;
          };
          if ($showord) { //show POs
               $recordSet = &$conn->Execute('select distinct invpo.ponumber,vcompany.companyname,invpo.duedate,icompany.companyname,ccompany.companyname,carrierservice.description,invpo.requisition,invpo.ordernumber,invpo.id,invpo.complete from invpo,vendor,company as vcompany,company as icompany,inventorylocation,company as ccompany,carrier,carrierservice,item,invpodetail where inventorylocation.companyid=icompany.id and invpo.locationid=inventorylocation.id and invpo.carrierserviceid=carrierservice.id and carrier.id=carrierservice.carrierid and ccompany.id=carrier.companyid and invpo.gencompanyid='.sqlprep($active_company).' and invpo.vendorid=vendor.id and invpodetail.invpoid=invpo.id and vendor.orderfromcompanyid=vcompany.id'.$vendorstr.$ponumberstr.$requisitionstr.$ordernumberstr.$itemcodestr.$duedatestr.$locationidstr.$carrierstr.$typestr.' and vendor.gencompanyid='.sqlprep($active_company).$orderstr);
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_POS_FOUND']));
               echo '<form name="mainform">'.$lang['STR_ORDER'].':&nbsp;&nbsp;<select name="order"'.INC_TEXTBOX.' onChange="loca()"><option value="0"'.checkequal(0,$order,' selected').'>'.$lang['STR_DUE_DATE'].'<option value="2"'.checkequal(2,$order,' selected').'>'.$lang['STR_VENDOR_NAME'].'<option value="4"'.checkequal(4,$order,' selected').'>'.$lang['STR_PO_NUMBER'].'</select></form>';
               echo '<table border=1><tr><th>'.$lang['STR_PO_NUMBER'].'</th><th>'.$lang['STR_VENDOR_NAME'].'</th><th>'.$lang['STR_DUE_DATE'].'</th><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_SHIPPING_METHOD'].'</th><th>'.$lang['STR_REQUISITION_NUMBER'].'</th><th>'.$lang['STR_ORDER_NUMBER'].'</th><th>'.$lang['STR_COMPLETE'].'</th></tr>';
               while (!$recordSet->EOF) {
                    if ($recordSet->fields[9]) {
                           $completestr='<font color="#00FF00">Y</font>';
                    } else {
                           $completestr='<font color="#FF0000">N</font>';
                    };
                    echo '<tr><td><a href="javascript:doNothing()" onclick="top.newWin = window.open (\'invpoview.php?printable=1&invpoid='.$recordSet->fields[8].'\',\'print\',\'dependent=yes,width=800,height=400,screenX=10,screenY=100,titlebar=yes,scrollbars=1,resizable=0\')">'.$recordSet->fields[0].'</a></td><td>'.$recordSet->fields[1].'</td><td><nobr>'.$recordSet->fields[2].'</nobr></td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].' - '.$recordSet->fields[5].'</td><td>'.$recordSet->fields[6].'</td><td>'.$recordSet->fields[7].'</td><td align="center">'.$completestr.'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } else {  //show items
               $recordSet = &$conn->Execute('select invpo.ponumber,vcompany.companyname,invpo.duedate,icompany.companyname,ccompany.companyname,carrierservice.description,invpo.requisition,invpo.ordernumber,invpo.id,item.itemcode,item.description from invpo,vendor,company as vcompany,company as icompany,inventorylocation,company as ccompany,carrier,carrierservice,item,invpodetail where invpodetail.invpoid=invpo.id and item.id=invpodetail.itemid and inventorylocation.companyid=icompany.id and invpo.locationid=inventorylocation.id and item.companyid='.sqlprep($active_company).' and invpo.carrierserviceid=carrierservice.id and carrier.id=carrierservice.carrierid and ccompany.id=carrier.companyid and invpo.gencompanyid='.sqlprep($active_company).' and invpo.vendorid=vendor.id and vendor.orderfromcompanyid=vcompany.id'.$vendorstr.$ponumberstr.$requisitionstr.$ordernumberstr.$itemcodestr.$duedatestr.$locationidstr.$carrierstr.$typestr.' and vendor.gencompanyid='.sqlprep($active_company).$orderstr);
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_PO_ITEMS_FOUND']));
               echo '<form name="mainform">'.$lang['STR_ORDER'].':&nbsp;&nbsp;<select name="order"'.INC_TEXTBOX.' onChange="loca()"><option value="0"'.checkequal(0,$order,' selected').'>'.$lang['STR_DUE_DATE'].'<option value="1"'.checkequal(1,$order,' selected').'>'.$lang['STR_ITEM_CODE'].'<option value="2"'.checkequal(2,$order,' selected').'>'.$lang['STR_VENDOR_NAME'].'<option value="3"'.checkequal(3,$order,' selected').'>'.$lang['STR_ITEM_DESCRIPTION'].'<option value="4"'.checkequal(4,$order,' selected').'>'.$lang['STR_PO_NUMBER'].'</select></form>';
               echo '<table border="1"><tr><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_DESCRIPTION'].'</th><th>'.$lang['STR_PO_NUMBER'].'</th><th>'.$lang['STR_VENDOR_NAME'].'</th><th>'.$lang['STR_DUE_DATE'].'</th><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_SHIPPING_METHOD'].'</th><th>'.$lang['STR_REQUISITION_NUMBER'].'</th><th>'.$lang['STR_ORDER_NUMBER'].'</th></tr>';
               while (!$recordSet->EOF) {
                    echo '<tr><td>'.$recordSet->fields[9].'</td><td>'.$recordSet->fields[10].'</td><td><a href="javascript:doNothing()" onclick="top.newWin = window.open (\'invpoview.php?printable=1&invpoid='.$recordSet->fields[8].'\',\'print\',\'dependent=yes,width=800,height=400,screenX=10,screenY=100,titlebar=yes,scrollbars=1,resizable=0\')">'.$recordSet->fields[0].'</a></td><td>'.$recordSet->fields[1].'</td><td><nobr>'.$recordSet->fields[2].'</nobr></td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].' - '.$recordSet->fields[5].'</td><td>'.$recordSet->fields[6].'</td><td>'.$recordSet->fields[7].'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          };
     } else {
          echo '<form action="invpolist.php" method="post" name="mainform"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PO_NUMBER'].' #:</td><td><input type="text" name="ponumber" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
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
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ORDER_NUMBER'].':</td><td><input type="text" name="ordernumber"  onchange="validateint(this)" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ITEM_CODE'].':</td><td><input type="text" name="itemcode" size="15" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode&&inventorylocationid='.$locationid.'\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHOW_PENDING'].':</td><td><input type="checkbox" name="pend" checked onclick="checkChoice(1)"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHOW_RECEIVED'].':</td><td><input type="checkbox" name="recv" checked onclick="checkChoice(2)"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LIST_SORT_BY'].':</td><td><select name="showord"'.INC_TEXTBOX.'><option value="0">'.$lang['STR_ITEM'].'<option value="1">'.$lang['STR_PO'].'</select></td></tr>';
          echo '<br></table><br><input type="submit" name="submit" value="'.$lang['STR_SHOW_LIST'].'"></form>';
          
          echo '</center>';
     };

?>

<?php include('includes/footer.php'); ?>
