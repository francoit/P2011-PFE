<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //invitemlstprice.php
     echo texttitle($lang['STR_ITEM_LIST_PRICE']);
     echo '<center>';
     if ($order&&$pricelevel) {
          if ($location) {
               $locationstr=' and inventorylocation.id='.sqlprep($location);
               $recordSet = &$conn->Execute('select company.companyname from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.id='.sqlprep($location));
               if (!$recordSet->EOF) {
                     $title=$lang['STR_FOR_LOCATION'].$recordSet->fields[0]." --- ";
               };

          } else {
               $title=$lang['STR_FOR_ALL_LOCATIONS'];
          };
          if ($pricelevel) {
              $recordSet = &$conn->Execute('select description from pricelevel where (companyid='.$active_company.' or companyid=0) and cancel=0 and id='.sqlprep($pricelevel));
              if (!$recordSet->EOF) $title.=$lang['STR_FOR_PRICE_LEVEL'] .$recordSet->fields[0];
          } else {
              die(texterror($lang['STR_HOW_DID_YOU_GET_HERE']));
          };
          echo texttitle($title);
          if ($order=="lci") {
               $recordSet = &$conn->Execute('select item.id, company.companyname, itemcategory.name, item.itemcode, item.description, markupset.costbased, unitname.unitname, item.priceunitsperstockunit from item,itemcategory,inventorylocation,company,unitname,itemlocation left join markupset on itemlocation.markupsetid=markupset.id where company.id=inventorylocation.companyid and inventorylocation.id=itemlocation.inventorylocationid and itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.companyid='.sqlprep($active_company).' and item.priceunitnameid=unitname.id '.$locationstr.' order by company.companyname, itemcategory.name, item.itemcode');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border=0><tr><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_PRICE'].'</th><th>'.$lang['STR_PRICE_UNIT'].'</th></tr>';
               while (!$recordSet->EOF) {
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td>'.CURRENCY_SYMBOL.num_format(invitemprice($recordSet->fields[0], $recordSet->fields[5], $pricelevel,1),2).'</td><td>'.$recordSet->fields[6].'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="li") {
               $recordSet = &$conn->Execute('select item.id, company.companyname, item.itemcode, itemcategory.name, item.description, markupset.costbased, unitname.unitname, item.priceunitsperstockunit from item,itemcategory,inventorylocation,company,unitname,itemlocation left join markupset on itemlocation.markupsetid=markupset.id where company.id=inventorylocation.companyid and inventorylocation.id=itemlocation.inventorylocationid and itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.companyid='.sqlprep($active_company).' and item.priceunitnameid=unitname.id '.$locationstr.' order by company.companyname, item.itemcode');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border=0><tr><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_PRICE'].'</th><th>'.$lang['STR_PRICE_UNIT'].'</th></tr>';
               while (!$recordSet->EOF) {
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td>'.CURRENCY_SYMBOL.num_format(invitemprice($recordSet->fields[0], $recordSet->fields[5], $pricelevel,1),2).'</td><td>'.$recordSet->fields[6].'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="ci") {
               $recordSet = &$conn->Execute('select item.id, itemcategory.name, item.itemcode, company.companyname, item.description, markupset.costbased, unitname.unitname, item.priceunitsperstockunit from item,itemcategory,inventorylocation,company,unitname,itemlocation left join markupset on itemlocation.markupsetid=markupset.id where company.id=inventorylocation.companyid and inventorylocation.id=itemlocation.inventorylocationid and itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.companyid='.sqlprep($active_company).' and item.priceunitnameid=unitname.id'.$locationstr.' order by itemcategory.name, item.itemcode');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border=0><tr><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_PRICE'].'</th><th>'.$lang['STR_PRICE_UNIT'].'</th></tr>';
               while (!$recordSet->EOF) {
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td>'.CURRENCY_SYMBOL.num_format(invitemprice($recordSet->fields[0], $recordSet->fields[5], $pricelevel,1),2).'</td><td>'.$recordSet->fields[6].'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="i") {
               $recordSet = &$conn->Execute('select item.id, item.itemcode, itemcategory.name, company.companyname, item.description, markupset.costbased, unitname.unitname, item.priceunitsperstockunit from item,itemcategory,inventorylocation,company,unitname,itemlocation left join markupset on itemlocation.markupsetid=markupset.id where company.id=inventorylocation.companyid and inventorylocation.id=itemlocation.inventorylocationid and itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.companyid='.sqlprep($active_company).' and item.priceunitnameid=unitname.id'.$locationstr.' order by item.itemcode');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border=0><tr><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_PRICE'].'</th><th>'.$lang['STR_PRICE_UNIT'].'</th></tr>';
               while (!$recordSet->EOF) {
                    $displayprice=invitemprice($recordSet->fields[0], $recordSet->fields[5], $pricelevel,1);

                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td>'.CURRENCY_SYMBOL.num_format($displayprice,2).'</td><td>'.$recordSet->fields[6].'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          };
     } else {
          echo '<form action="invitemlstprice.php" method="post"><table>';
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
          echo '<option value="ci">'.$lang['STR_CATEGORY_ITEM_CODE'].' ';
          echo '<option value="i">'.$lang['STR_ITEM_CODE'].' ';
          echo '</select></td></tr>';
          $recordSet = &$conn->Execute('select count(*) from pricelevel where (companyid='.$active_company.' or companyid=0) and cancel=0');
          if (!$recordSet->EOF) if ($recordSet->fields[0]>1) {
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PRICE_LEVEL'].':</td><td><select name="pricelevel"'.INC_TEXTBOX.'>';
              $recordSet = &$conn->Execute('select id,description from pricelevel where (companyid='.$active_company.' or companyid=0) and cancel=0 order by id');
              while (!$recordSet->EOF) {
                   echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                   $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
          } else {
                $recordSet = &$conn->Execute('select id from pricelevel where (companyid='.$active_company.' or companyid=0) and cancel=0 order by id');
              if (!$recordSet->EOF) echo '<input type="hidden" name="pricelevel" value="'.$recordSet->fields[0].'">';
          };
          echo '</table><input type="submit" value="'.$lang['STR_CREATE_REPORT'].'"></form>';
     };
     echo '</center>';
?>

<?php include('includes/footer.php'); ?>
