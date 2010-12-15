<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php  echo '<center>';
     echo texttitle($lang['STR_ITEM_LIST_SUMMARY']);
     if ($order) {
          if ($location) {
               $locationstr=' and inventorylocation.id='.sqlprep($location);
               $recordSet = &$conn->Execute('select company.companyname from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.id='.sqlprep($location));
               if (!$recordSet->EOF) echo texttitle($lang['STR_FOR_LOCATIONS'] .$recordSet->fields[0]);
          } else {
               echo texttitle($lang['STR_FOR_ALL_LOCATIONS']);
          };
          if ($order=="i") {
               $recordSet = &$conn->Execute('select item.id, item.itemcode, item.description, itemcategory.name, sum(itemlocation.onhandqty), unitname.unitname, avg(itemlocation.firstcost), sum(itemlocation.firstqty), avg(itemlocation.midcost), sum(itemlocation.midqty), avg(itemlocation.lastcost), sum(itemlocation.lastqty), item.priceunitnameid from item,itemcategory,company,unitname,itemlocation,inventorylocation where inventorylocation.companyid=company.id and itemlocation.inventorylocationid=inventorylocation.id and itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.companyid='.sqlprep($active_company).' and item.stockunitnameid=unitname.id'.$locationstr.' group by item.id, item.itemcode, item.description, itemcategory.name, unitname.unitname, item.priceunitnameid order by item.itemcode');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th rowspan="2">'.$lang['STR_ITEM_CODE'].'</th><th rowspan="2">'.$lang['STR_ITEM_NAME'].'</th><th rowspan="2">'.$lang['STR_CATEGORY'].'</th><th rowspan="2">'.$lang['STR_QTY_ON_HAND'].'</th><th rowspan="2">'.$lang['STR_STOCK_QTY_UNIT'].'</th><th rowspan="2">'.$lang['STR_PRICE_UNIT'].'</th><th colspan="2">'.$lang['STR_FIRST'].'</th><th colspan="2">'.$lang['STR_MID'].'</th><th colspan="2">'.$lang['STR_LAST'].'</th></tr>';
               echo '<tr><th>'.$lang['STR_COST'].'</th><th>'.$lang['STR_QTY'].'</th><th>'.$lang['STR_COST'].'</th><th>'.$lang['STR_QTY'].'</th><th>'.$lang['STR_COST'].'</th><th>'.$lang['STR_QTY'].'</th></tr>';
               while (!$recordSet->EOF) {
                    $recordSet2 = &$conn->Execute('select unitname from unitname where id='.sqlprep($recordSet->fields[12]));
                    if (!$recordSet2->EOF) {
                         $priceunitname=$recordSet2->fields[0];
                    };
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[5].'</td><td>'.$priceunitname.'<td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[6],2).'</td><td>'.$recordSet->fields[7].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[8],2).'</td><td>'.$recordSet->fields[9].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[10],2).'</td><td>'.$recordSet->fields[11].'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="ci") {
               $recordSet = &$conn->Execute('select item.id, itemcategory.name, item.itemcode, item.description, sum(itemlocation.onhandqty), unitname.unitname, avg(itemlocation.firstcost), sum(itemlocation.firstqty), avg(itemlocation.midcost), sum(itemlocation.midqty), avg(itemlocation.lastcost), sum(itemlocation.lastqty), item.priceunitnameid from item,itemcategory,company,unitname,itemlocation,inventorylocation where inventorylocation.companyid=company.id and itemlocation.inventorylocationid=inventorylocation.id and itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.companyid='.sqlprep($active_company).' and item.stockunitnameid=unitname.id'.$locationstr.' group by item.id, itemcategory.name, item.itemcode, item.description, unitname.unitname, item.priceunitnameid order by itemcategory.name, item.itemcode');
               if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th rowspan="2">'.$lang['STR_CATEGORY'].'</th><th rowspan="2">'.$lang['STR_ITEM_CODE'].'</th><th rowspan="2">'.$lang['STR_ITEM_NAME'].'</th><th rowspan="2">'.$lang['STR_QTY_ON_HAND'].'</th><th rowspan="2">'.$lang['STR_STOCK_QTY_UNIT'].'</th><th rowspan="2">'.$lang['STR_PRICE_UNIT'].'</th><th colspan="2">'.$lang['STR_FIRST'].'</th><th colspan="2">'.$lang['STR_MID'].'</th><th colspan="2">'.$lang['STR_LAST'].'</th></tr>';
               echo '<tr><th>'.$lang['STR_COST'].'</th><th>'.$lang['STR_QTY'].'</th><th>'.$lang['STR_COST'].'</th><th>'.$lang['STR_QTY'].'</th><th>'.$lang['STR_COST'].'</th><th>'.$lang['STR_QTY'].'</th></tr>';
               while (!$recordSet->EOF) {
                    $recordSet2 = &$conn->Execute('select unitname from unitname where id='.sqlprep($recordSet->fields[12]));
                    if (!$recordSet2->EOF) {
                         $priceunitname=$recordSet2->fields[0];
                    };
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[5].'</td><td>'.$priceunitname.'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[6],2).'</td><td>'.$recordSet->fields[7].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[8],2).'</td><td>'.$recordSet->fields[9].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[10],2).'</td><td>'.$recordSet->fields[11].'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="cn") {
               $recordSet = &$conn->Execute('select item.id, itemcategory.name, item.description, item.itemcode, sum(itemlocation.onhandqty), unitname.unitname, avg(itemlocation.firstcost), sum(itemlocation.firstqty), avg(itemlocation.midcost), sum(itemlocation.midqty), avg(itemlocation.lastcost), sum(itemlocation.lastqty), item.priceunitnameid from item,itemcategory,company,unitname,itemlocation,inventorylocation where inventorylocation.companyid=company.id and itemlocation.inventorylocationid=inventorylocation.id and itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.companyid='.sqlprep($active_company).' and item.stockunitnameid=unitname.id'.$locationstr.' group by item.id, itemcategory.name, item.description, item.itemcode, unitname.unitname, item.priceunitnameid order by itemcategory.name, item.description, item.itemcode');
               if (!$recordSet||$recordSet->EOF) die(texterror('No matching items found.'));
               echo '<table border="1"><tr><th rowspan="2">'.$lang['STR_CATEGORY'].'</th><th rowspan="2">'.$lang['STR_ITEM_NAME'].'</th><th rowspan="2">'.$lang['STR_ITEM_CODE'].'</th><th rowspan="2">'.$lang['STR_QTY_ON_HAND'].'</th><th rowspan="2">'.$lang['STR_STOCK_QTY_UNIT'].'</th><th rowspan="2">'.$lang['STR_PRICEUNIT'].'</th><th colspan="2">'.$lang['STR_FIRST'].'</th><th colspan="2">'.$lang['STR_MID'].'</th><th colspan="2">'.$lang['STR_LAST'].'</th></tr>';
               echo '<tr><th>'.$lang['STR_COST'].'</th><th>'.$lang['STR_QTY'].'</th><th>'.$lang['STR_COST'].'</th><th>'.$lang['STR_QTY'].'</th><th>'.$lang['STR_COST'].'</th><th>'.$lang['STR_QTY'].'</th></tr>';
               while (!$recordSet->EOF) {
                    $recordSet2 = &$conn->Execute('select unitname from unitname where id='.sqlprep($recordSet->fields[12]));
                    if (!$recordSet2->EOF) {
                         $priceunitname=$recordSet2->fields[0];
                    };
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[5].'</td><td>'.$priceunitname.'<td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[6],2).'</td><td>'.$recordSet->fields[7].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[8],2).'</td><td>'.$recordSet->fields[9].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[10],2).'</td><td>'.$recordSet->fields[11].'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          };
     } else {
          echo '<form action="invitemlstsum.php" method="post"><table>';
          $recordSet = &$conn->Execute('select count(*) from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company));
          if (!$recordSet->EOF) if ($recordSet->fields[0]>1) {
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LOCATION'].':</td><td><select name="location"'.INC_TEXTBOX.'><option value="0">'.$lang['STR_ALL'].' ';
              $recordSet = &$conn->Execute('select inventorylocation.id,company.companyname from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
              while (!$recordSet->EOF) {
                   echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                   $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
          };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SORT_BY'].':</td><br><td><select name="order"'.INC_TEXTBOX.'>';
          echo '<option value="ci"> Category - Item Code';
          echo '<option value="i"> Item Code';
          echo '<option value="cn"> Category - Item Name';
          echo '</select></td></tr>';
          echo '<br></table><br><input type="submit" value="'.$lang['STR_CREATE_REPORT'].'"></form>';
          
     };
          echo '</center>';
?>

<?php include('includes/footer.php'); ?>

