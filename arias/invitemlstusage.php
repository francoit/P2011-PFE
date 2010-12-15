<?php include('includes/main.php'); ?>
<?php include_once('includes/apfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     //Center everything
     echo '<center>';
     echo texttitle($lang['STR_ITEM_LIST_USAGE']);
     if ($period) {
          switch ($period) {
               case 1: //currently not an option - formatting issues
                    $period='WEEK';
                    $datelen=10;
                    break;
               case 3:
                    $period='YEAR';
                    $datelen=4;
                    break;
               default:
                    $period='MONTH';
                    $datelen=7;
          };
          if ($vendor) {
               $vendorstr=' and company.id=vendor.orderfromcompanyid and itemvendor.itemid=item.id and vendor.id='.sqlprep($vendor);
               $vendorstr2=', company, vendor, itemvendor';
          };
          if ($category) $categorystr=' and itemcategory.id='.sqlprep($category);
          if ($itemcode!="All"&&$itemcode!="") $itemcodestr=' and item.itemcode='.sqlprep($itemcode);

          if ($pending) {
               $selstr='select substring(arorder.entrydate,1,'.$datelen.'), itemcategory.name, item.itemcode, item.description, sum(arorderdetail.qtyorder), sum(arorderdetail.priceach*arorderdetail.qtyorder), itemcategory.id from item,itemcategory, arorder, arorderdetail'.$vendorstr2.' where arorder.id=arorderdetail.orderid and arorder.cancel=0 and arorderdetail.itemid=item.id and item.companyid='.sqlprep($active_company).' and itemcategory.id=item.categoryid'.$vendorstr.$categorystr.$itemcodestr.'                                                                          group by '.$period.'(arorder.entrydate), item.id order by '.$period.'(arorder.entrydate) desc, itemcategory.name, item.itemcode';
          } else {
               $selstr='select substring(arorder.entrydate,1,'.$datelen.'), itemcategory.name,item.itemcode, item.description, sum(arordershipdetail.shipqty), sum(arordershipdetail.shipqty*arorderdetail.priceach),itemcategory.id from arorder,item,itemcategory,arorderdetail,arordershipdetail'.$vendstr2.' where  arorder.id=arorderdetail.orderid  and arorder.cancel=0 and arorderdetail.itemid=item.id and item.companyid='.sqlprep($active_company).'  and itemcategory.id=item.categoryid'.$vendorstr.$categorystr.$itemcodestr.' and arordershipdetail.orderdetailid=arorderdetail.id group by '.$period.'(arordershipdetail.entrydate),item.id order by '.$period.'(arordershipdetail.entrydate) desc, itemcategory.name, item.itemcode';

          };
          $recordSet = &$conn->Execute($selstr);
          if ($recordSet->EOF) die(texterror($lang['STR_NO_ENTIES_FOUND_MATCHING_CRITERIA']));
          if ($pending) {
               echo texttitle($lang['STR_ORDERS_RECEIVED_FOR_PERIOD']);
          } else {
               echo texttitle($lang['STR_NO_ORDERS_SHIPPED_FOR_PERIOD']);
          };
          echo '<table border="1"><tr><th>'.$lang['STR_PERIOD'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_QTY_SOLD'].'</th><th>'.$lang['STR_DOLLAR_SOLD'].'</th></tr>';
          $oldcat=$recordSet->fields[6];
          while (!$recordSet->EOF) {
               if ($catsub&&$recordSet->fields[6]!=$oldcat) {
                    echo '<tr><td colspan="4" align="right">'.$lang['STR_CATEGORY_TOTAL'].':</td><td>'.$qtysubtotal.'</td><td>'.CURRENCY_SYMBOL.num_format($dolsubtotal,2).'</td></tr><tr><td colspan="6">&nbsp;</td></tr>';
                    $qtysubtotal=0;
                    $dolsubtotal=0;
                    $oldcat=$recordSet->fields[6];
               };
               echo '<tr><td><b>'.$recordSet->fields[0].'</b></td><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td>';
               if ($pending) {
                    echo '<td>'.$recordSet->fields[4].'</td>';
               } else {
                    echo '<td><a href="arordershipitemlst.php?itemcode='.$recordSet->fields[2].'&begindate='.$recordSet->fields[0].'&enddate='.$recordSet->fields[0].'"> '.$recordSet->fields[4].'</td>';
               };
               echo '<td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[5],2).'</td></tr>';
               $qtysubtotal+=$recordSet->fields[4];
               $dolsubtotal+=$recordSet->fields[5];
               $recordSet->MoveNext();
          };
          if ($catsub) echo '<tr><td colspan="4" align="right">'.$lang['STR_CATEGORY_TOTAL'].':</td><td>'.$qtysubtotal.'</td><td>'.CURRENCY_SYMBOL.num_format($dolsubtotal,2).'</td></tr>';
          echo '</table>';
     } else {
          echo '<form action="invitemlstusage.php" method="post" name="mainform"><table><input type="hidden" name="printable" value="1">';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ITEM_CODE'].':</td><td><input type="text" name="itemcode" value="All"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CATEGORY'].':</td><td><select name="category"'.INC_TEXTBOX.'><option value="0">All';
          $recordSet = &$conn->Execute('select itemcategory.id, itemcategory.name from itemcategory order by itemcategory.name');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          formapvendorselect('vendor');
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PERIOD'].':</td><td><select name="period"'.INC_TEXTBOX.'>';
          echo '<option value="2" selected>'.$lang['STR_MONTH'].'';
          echo '<option value="3">'.$lang['STR_YEAR'].'';
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CATEGORY_SUB_TOTALS'].':</td><td><input type="checkbox" name="catsub" value="1" checked></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_USE_ORDER_RECEIVE_DATE'].':</td><td><input type="checkbox" name="pending" value="1"></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_CREATE_REPORT'].'"></form>';
          //Help icon
          
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
