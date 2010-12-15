<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php   echo '<center>';
     echo texttitle($lang['STR_ITEM_LIST_PHYSICAL']);
     echo '<form action="invitemlstphys.php" method="post" name="mainform"><table>';
     if ($order) {
          if ($maxid) {
               for ($counter=0; $maxid>=$counter; $counter++) {
                    if (isset($$counter)) if ($conn->Execute("update itemlocation set itemlocation.onhandqty=".sqlprep($$counter)." where itemlocation.id=".sqlprep($counter)) === false) echo '<b>'.$lang['STR_ERROR_UPDATING_ITEM'].'</b><br>\n';
               };
          };
          echo '<input type="hidden" name="order" value="'.$order.'">';
          echo '<input type="hidden" name="location" value="'.$location.'">';
          $recordSet = &$conn->Execute('select max(itemlocation.id) from itemlocation');
          if (!$recordSet->EOF) echo '<input type="hidden" name="maxid" value="'.$recordSet->fields[0].'">';
          if ($location) $locationstr=' and inventorylocation.id='.sqlprep($location);
          if ($order=="lci") {
               $recordSet = &$conn->Execute('select itemlocation.id, company.companyname, itemcategory.name, item.itemcode, item.description, itemlocation.onhandqty, unitname.unitname, item.compositeitemyesno from item,inventorylocation,itemcategory,company,unitname,itemlocation where itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.stockunitnameid=unitname.id and item.companyid='.sqlprep($active_company).' and itemlocation.inventorylocationid=inventorylocation.id and company.id=inventorylocation.companyid'.$locationstr.' order by company.companyname, itemcategory.name, item.itemcode');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_QTY_ON_HAND'].'</th><th>'.$lang['STR_STOCK_UNIT'].'</th></tr>';
               while (!$recordSet->EOF) {
/*                    if ($recordSet->fields[6]) {
                        $recordSet2 = &$conn->Execute('select itemlocation.id, company.companyname, itemcategory.name, item.itemcode, item.description, itemlocation.onhandqty, unitname.unitname, item.compositeitemyesno from item,inventorylocation,itemcategory,company,unitname,itemlocation where itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.stockunitnameid=unitname.id and item.companyid='.sqlprep($active_company).' and itemlocation.inventorylocationid=inventorylocation.id and company.id=inventorylocation.companyid'.$locationstr.' order by company.companyname, itemcategory.name, item.itemcode');
                    
*/                    
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td><input type="text" name="'.$recordSet->fields[0].'" onchange="validatenum(this)" size="10" maxlength="10" value="'.$recordSet->fields[5].'"'.INC_TEXTBOX.'></td><td>'.$recordSet->fields[6].'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="li") {
               $recordSet = &$conn->Execute('select itemlocation.id, company.companyname, item.itemcode, item.description, itemcategory.name, itemlocation.onhandqty, unitname.unitname, item.compositeitemyesno from item,inventorylocation,itemcategory,company,unitname,itemlocation where itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.stockunitnameid=unitname.id and item.companyid='.sqlprep($active_company).' and itemlocation.inventorylocationid=inventorylocation.id and company.id=inventorylocation.companyid'.$locationstr.' order by company.companyname, item.itemcode');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_QTY_ON_HAND'].'</th><th>'.$lang['STR_STOCK_UNIT'].'</th></tr>';
               while (!$recordSet->EOF) {
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td><input type="text" name="'.$recordSet->fields[0].'" onchange="validatenum(this)" size="10" maxlength="10" value="'.$recordSet->fields[5].'"'.INC_TEXTBOX.'></td><td>'.$recordSet->fields[6].'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="cil") {
               $recordSet = &$conn->Execute('select itemlocation.id, itemcategory.name, item.itemcode, item.description, company.companyname, itemlocation.onhandqty, unitname.unitname, item.compositeitemyesno from item,inventorylocation,itemcategory,company,unitname,itemlocation where itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.stockunitnameid=unitname.id and item.companyid='.sqlprep($active_company).' and itemlocation.inventorylocationid=inventorylocation.id and company.id=inventorylocation.companyid'.$locationstr.' order by itemcategory.name, item.itemcode, company.companyname');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_QTY_ON_HAND'].'</th><th>'.$lang['STR_STOCK_UNIT'].'</th></tr>';
               while (!$recordSet->EOF) {
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td><input type="text" name="'.$recordSet->fields[0].'" onchange="validatenum(this)" size="10" maxlength="10" value="'.$recordSet->fields[5].'"'.INC_TEXTBOX.'></td><td>'.$recordSet->fields[6].'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="il") {
               $recordSet = &$conn->Execute('select itemlocation.id, item.itemcode, item.description, company.companyname, itemcategory.name, itemlocation.onhandqty, unitname.unitname, item.compositeitemyesno from item,inventorylocation,itemcategory,company,unitname,itemlocation where itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.stockunitnameid=unitname.id and item.companyid='.sqlprep($active_company).' and itemlocation.inventorylocationid=inventorylocation.id and company.id=inventorylocation.companyid'.$locationstr.' order by item.itemcode, company.companyname');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_QTY_ON_HAND'].'</th><th>'.$lang['STR_STOCK_UNIT'].'</th></tr>';
               while (!$recordSet->EOF) {
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td><input type="text" name="'.$recordSet->fields[0].'" onchange="validatenum(this)" size="10" maxlength="10" value="'.$recordSet->fields[5].'"'.INC_TEXTBOX.'></td><td>'.$recordSet->fields[6].'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          };
          echo '<br><input type="submit" value="'.$lang['STR_SUBMIT'].'">';
     } else {
          $recordSet = &$conn->Execute('select count(*) from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company));
          if (!$recordSet->EOF) if ($recordSet->fields[0]>1) {
	          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LOCATION'].':</td><td><select name="location"'.INC_TEXTBOX.'><option value="0">All';
        	  $recordSet = &$conn->Execute('select inventorylocation.id,company.companyname from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
	          while (!$recordSet->EOF) {
        	       echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
	               $recordSet->MoveNext();
        	  };
	          echo '</select></td></tr>';
	  };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SORT_BY'].':</td><td><select name="order"'.INC_TEXTBOX.'>';
          echo '<option value="lci">'.$lang['STR_LOCATION_CATEGORY_ITEM_CODE'].'';
          echo '<option value="li">'.$lang['STR_LOCATION_ITEM_CODE'].'';
          echo '<option value="cil">'.$lang['STR_CATEGORY_ITEM_CODE_LOCATION'].'';
          echo '<option value="il">'.$lang['STR_ITEM_CODE_LOCATION'].'';
          echo '</select></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_CREATE_REPORT_ENTER_COUNTS'].'"></form>';
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
