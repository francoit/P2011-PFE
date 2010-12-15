<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php   echo '<center>';
     echo texttitle($lang['STR_ITEM_LIST_COMPOSITE']);
     if ($itemcode||$order) {
          if ($itemcode<>"All"&&$itemcode<>"") $itemstr=' and item.itemcode='.sqlprep($itemcode);
          if ($order=="i") {
               $orderstr = ' order by item.itemcode';
          } elseif ($order=="ci") {
               $orderstr = ' order by item.categoryid, item.itemcode';
          };
          $recordSet = &$conn->Execute('select distinct compositeitemid.itemcodeid, item.itemcode, item.description from compositeitemid, item where item.companyid='.sqlprep($active_company).' and item.id=compositeitemid.itemcodeid and item.cancel=0'.$itemstr.$orderstr);
          if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
          echo '<table border="1">';
          while (!$recordSet->EOF) {
               echo '<th colspan="6">Item '.$recordSet->fields[1].' - '.$recordSet->fields[2].'</th></tr>';
               echo '<tr><th>'.$lang['STR_SUB_ITEM_CODE'].'</th><th>'.$lang['STR_SUB_ITEM_NAME'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_QUANTITY'].'</th><th>'.$lang['STR_UNIT_SIZE'].'</th></tr>';
               $recordSet2 = &$conn->Execute('select distinct item.id, item.itemcode, item.description, itemcategory.name, compositeitemid.quantity, unitname.unitname from item,itemcategory,unitname,compositeitemid where compositeitemid.subitemcodeid=item.id and itemcategory.id=item.categoryid and compositeitemid.itemcodeid='.sqlprep($recordSet->fields[0]).' and item.stockunitnameid=unitname.id and compositeitemid.quantity>0 order by item.itemcode');
               while (!$recordSet2->EOF) {
                    echo '<tr><td><b>'.$recordSet2->fields[1].'</b></td><td>'.$recordSet2->fields[2].'</td><td>'.$recordSet2->fields[3].'</td><td>'.$recordSet2->fields[4].'</td><td>'.$recordSet2->fields[5].'</td></tr>';
                    $recordSet2->MoveNext();
               };
               $recordSet->MoveNext();
               echo '<tr><td colspan="6">&nbsp;</td></tr>';
          };
          echo '</table>';
     } else {
          echo '<form action="invitemlstcomp.php" method="post" name="mainform"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ITEM_CODE'].':</td><td><input type="text" length="30" maxlength="20" name="itemcode" value="All"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitemcomposite.php?name=itemcode\',\'cal\',\'dependent=yes,width=610,height=170,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a></td></tr>';
          echo '<br><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SORT_BY'].':</td><td><select name="order"'.INC_TEXTBOX.'>';
          echo '<br><option value="i">'.$lang['STR_ITEM_CODE'].'';
          echo '<br><option value="ci">'.$lang['STR_CATEGORY_ITEM_CODE'].'';
          echo '</select></td></tr>';
          echo '</table><br><br><input type="submit" value="'.$lang['STR_CREATE_REPORT'].'"></form>';
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
