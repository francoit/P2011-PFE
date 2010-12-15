<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php   echo '<center>';
     echo texttitle($lang['STR_ITEM_LIST_DISTRIBUTION']);
     if ($order) {
          if ($location) {
               $locationstr=' and inventorylocation.id='.sqlprep($location);
               $recordSet = &$conn->Execute('select company.companyname from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.id='.sqlprep($location));
               if (!$recordSet->EOF) echo texttitle($lang['STR_FOR_LOCATION']  .$recordSet->fields[0]);
          } else {
               echo texttitle($lang['STR_FOR_ALL_LOCATIONS']);
          };
          if ($order=="lci") {
               $attrstr = 'company.companyname, itemcategory.name, item.itemcode, item.description';
               $orderstr = 'company.companyname, itemcategory.name, item.itemcode';
               $tablestr = '<th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th>';
          } elseif ($order=="li") {
               $attrstr = 'company.companyname, item.itemcode, item.description, itemcategory.name';
               $orderstr = 'company.companyname, item.itemcode';
               $tablestr = '<th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_CATEGORY'].'</th>';
          } elseif ($order=="cil") {
               $attrstr = 'itemcategory.name, item.itemcode, item.description, company.companyname';
               $orderstr = 'itemcategory.name, item.itemcode, company.companyname';
               $tablestr = '<th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_LOCATION'].'</th>';
          } elseif ($order=="il") {
               $attrstr = 'item.itemcode, item.description, company.companyname, itemcategory.name';
               $orderstr = 'item.itemcode, company.companyname';
               $tablestr = '<th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_CATEGORY'].'</th>';
          };
          $recordSet = &$conn->Execute('select '.$attrstr.', itemlocation.onhandqty, unitname.unitname, extract(month from now()), itemcategory.seasonbegin1, itemcategory.seasonbegin2, itemcategory.seasonbegin3, itemcategory.seasonbegin4, itemcategory.seasonend1, itemcategory.seasonend2, itemcategory.seasonend3, itemcategory.seasonend4, itemlocation.maxstocklevelseason1, itemlocation.minstocklevelseason1, itemlocation.orderqtyseason1, itemlocation.maxstocklevelseason2, itemlocation.minstocklevelseason2, itemlocation.orderqtyseason2, itemlocation.maxstocklevelseason3, itemlocation.minstocklevelseason3, itemlocation.orderqtyseason3, itemlocation.maxstocklevelseason4, itemlocation.minstocklevelseason4, itemlocation.orderqtyseason4 from item,inventorylocation,itemcategory,company,unitname,itemlocation where itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.stockunitnameid=unitname.id and itemlocation.inventorylocationid=inventorylocation.id and company.id=inventorylocation.companyid and item.companyid='.sqlprep($active_company).$locationstr.' order by '.$orderstr);
          if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
          echo '<table border="1"><tr>'.$tablestr.'<th>'.$lang['STR_QTY_ON_HAND'].'</th><th>'.$lang['STR_MAX_STOCK_LEVEL'].'</th><th>'.$lang['STR_MIN_STOCK_LEVEL'].'</th><th>'.$lang['STR_ORDER_QTY'].'</th><th>'.$lang['STR_STOCK_UNITS'].'</th></tr>';
          while (!$recordSet->EOF) {
               $month = $recordSet->fields[6];
               if ($month < $recordSet->fields[7]) {
                    $beginseason = 0;
               } else if ($month < $recordSet->fields[8]) {
                    $beginseason = 1;
               } else if ($month < $recordSet->fields[9]) {
                    $beginseason = 2;
               } else {
                    $beginseason = 3;
               };
               if ($month < $recordSet->fields[11]) {
                    $endseason = 0;
               } else if ($month < $recordSet->fields[12]) {
                    $endseason = 1;
               } else if ($month < $recordSet->fields[13]) {
                    $endseason = 2;
               } else {
                    $endseason = 3;
               };
               if ($beginseason > $endseason) {
                    $useseason = $endseason;
               } else {
                    $useseason = $beginseason;
               };
               echo '<tr><td><b>'.rtrim($recordSet->fields[0]).'</b></td><td>'.rtrim($recordSet->fields[1]).'</td><td>'.rtrim($recordSet->fields[2]).'</td><td>'.rtrim($recordSet->fields[3]).'</td><td align="right">'.$recordSet->fields[4].'</td><td align="right">'.$recordSet->fields[15+($useseason*3)].'</td><td align="right">'.$recordSet->fields[16+($useseason*3)].'</td><td align="right">'.$recordSet->fields[17+($useseason*3)].'</td><td>'.rtrim($recordSet->fields[5]).'</td></tr>';
               $recordSet->MoveNext();
          };
          echo '</table>';
     } else {
          echo '<form action="invitemlstdist.php" method="post" name="mainform"><table>';
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
          echo '<option value="lci">'.$lang['STR_LOCATION_CATEGORY_ITEM_CODE'].' ';
          echo '<option value="li">'.$lang['STR_LOCATION_ITEM_CODE'].' ';
          echo '<option value="cil">'.$lang['STR_CATEGORY_ITEM_CODE_LOCATION'].'';
          echo '<option value="il">'.$lang['STR_ITEM_CODE_LOCATION'].' ';
          echo '</select></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_CREATE_REPORT'].'"></form>';
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
