<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php   echo '<center>';
     echo texttitle($lang['STR_ITEM_LIST_VALUATION']);
     if ($order&&$pricelevel) {
          $recordSet = &$conn->Execute('select description from pricelevel where id='.sqlprep($pricelevel));
          if (!$recordSet->EOF) $pricename=$recordSet->fields[0].'<br>';
          $coststr=', itemlocation.firstcost, itemlocation.midcost, itemlocation.lastcost';
          if ($location) {
               $locationstr=' and inventorylocation.id='.sqlprep($location);
               $recordSet = &$conn->Execute('select company.companyname from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.id='.sqlprep($location));
               if (!$recordSet->EOF) echo texttitle($lang['STR_FOR_LOCATION'] .$recordSet->fields[0]);
          } else {
               echo texttitle($lang['STR_FOR_ALL_LOCATIONS']);
          };
         if ($order=="lci") {
               $recordSet = &$conn->Execute('select item.id, company.companyname, itemcategory.name, item.itemcode, item.description, markupset.costbased, itemlocation.onhandqty'.$coststr.', item.priceunitsperstockunit from item,itemcategory,inventorylocation,company,unitname,itemlocation left join markupset on itemlocation.markupsetid=markupset.id where company.id=inventorylocation.companyid and inventorylocation.id=itemlocation.inventorylocationid and itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.companyid='.sqlprep($active_company).' and item.priceunitnameid=unitname.id'.$locationstr.' order by company.companyname, itemcategory.name, item.itemcode');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_QUANTITY'].'</th>';
               echo '<th>'.$pricename.''.$lang['STR_PRICE'].'</th>';
               echo '<th>';
               if ($cost==1) {
                    echo ''.$lang['STR_FIRST'].'<br>';
               } elseif ($cost==2) {
                    echo ''.$lang['STR_MID'].'<br>';
               } else {
                    echo ''.$lang['STR_LAST'].'<br>';
               };
               echo ''.$lang['STR_COST'].'</th>';
               echo '<th>'.$lang['STR_COST'].'<br>'.$lang['STR_VALUE'].'</th></tr>';
               while (!$recordSet->EOF) {
                    $unitprice=invitemprice($recordSet->fields[0], $recordSet->fields[5], $pricelevel,1);
                    $unitprice=$unitprice*$recordSet->fields[10];
                    $pricecost=$recordSet->fields[6+$cost];
                    if ($pricecost==0) {
                         switch ($cost) {
                             case 1:
                                    $pricecost=$recordSet->fields[8];
                                    if ($pricecost==0) {
                                         $pricecost=$recordSet->fields[9];
                                    };
                                    break;
                             case 3:
                                    $pricecost=$recordSet->fields[8];
                                    if ($pricecost==0) {
                                         $pricecost=$recordSet->fields[7];
                                    };
                                    break;
                             default:
                                     $pricecost=$recordSet->fields[9];
                                    if ($pricecost==0) {
                                         $pricecost=$recordSet->fields[7];
                                    };
                         };
                    };
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[6].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[6]*$unitprice,2).'</td><td>'.CURRENCY_SYMBOL.num_format($pricecost,2).'</td><td>'.CURRENCY_SYMBOL.num_format($pricecost*$recordSet->fields[6]*$recordSet->fields[10],2).'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="li") {
               $recordSet = &$conn->Execute('select item.id, company.companyname, item.itemcode, item.description, itemcategory.name, markupset.costbased, itemlocation.onhandqty'.$coststr.', item.priceunitsperstockunit  from item,itemcategory,inventorylocation,company,unitname,itemlocation left join markupset on itemlocation.markupsetid=markupset.id where company.id=inventorylocation.companyid and inventorylocation.id=itemlocation.inventorylocationid and itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.companyid='.sqlprep($active_company).' and item.priceunitnameid=unitname.id'.$locationstr.' order by company.companyname, item.itemcode');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_QUANTITY'].'</th>';
               echo '<th>'.$pricename.''.$lang['STR_PRICE'].'</th>';
               echo '<th>';
               if ($cost==1) {
                    echo ''.$lang['STR_FIRST'].'<br>';
               } elseif ($cost==2) {
                    echo ''.$lang['STR_MID'].'<br>';
               } else {
                    echo ''.$lang['STR_LAST'].'<br>';
               };
               echo ''.$lang['STR_COST'].'</th>';
               echo '<th>'.$lang['STR_COST'].'<br>'.$lang['STR_VALUE'].'</th></tr>';
               while (!$recordSet->EOF) {
                    $unitprice=invitemprice($recordSet->fields[0], $recordSet->fields[5], $pricelevel,1);
                    $unitprice=$unitprice*$recordSet->fields[10];
                    $pricecost=$recordSet->fields[6+$cost];
                    if ($pricecost==0) {
                         switch ($cost) {
                             case 1:
                                    $pricecost=$recordSet->fields[8];
                                    if ($pricecost==0) {
                                         $pricecost=$recordSet->fields[9];
                                    };
                                    break;
                             case 3:
                                    $pricecost=$recordSet->fields[8];
                                    if ($pricecost==0) {
                                         $pricecost=$recordSet->fields[7];
                                    };
                                    break;
                             default:
                                    $pricecost=$recordSet->fields[9];
                                    if ($pricecost==0) {
                                         $pricecost=$recordSet->fields[7];
                                    };
                         };
                    };
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[6].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[6]*$unitprice,2).'</td><td>'.CURRENCY_SYMBOL.num_format($pricecost,2).'</td><td>'.CURRENCY_SYMBOL.num_format($pricecost*$recordSet->fields[6]*$recordSet->fields[10],2).'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="ci") {
               $recordSet = &$conn->Execute('select item.id, itemcategory.name, item.itemcode, item.description, markupset.costbased, itemlocation.onhandqty'.$coststr.', item.priceunitsperstockunit  from item,itemcategory,inventorylocation,company,unitname,itemlocation left join markupset on itemlocation.markupsetid=markupset.id where company.id=inventorylocation.companyid and inventorylocation.id=itemlocation.inventorylocationid and itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.companyid='.sqlprep($active_company).' and item.priceunitnameid=unitname.id'.$locationstr.' group by itemlocation.id order by itemcategory.name, item.itemcode');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_QUANTITY'].'</th>';
               echo '<th>'.$pricename.''.$lang['STR_PRICE'].'</th>';
               echo '<th>';
               if ($cost==1) {
                    echo ''.$lang['STR_FIRST'].'<br>';
               } elseif ($cost==2) {
                    echo ''.$lang['STR_MID'].'<br>';
               } else {
                    echo ''.$lang['STR_LAST'].'<br>';
               };
               echo ''.$lang['STR_COST'].'</th>';
               echo '<th>'.$lang['STR_COST'].'<br>'.$lang['STR_VALUE'].'</th></tr>';
               while (!$recordSet->EOF) {
                    $unitprice=invitemprice($recordSet->fields[0], $recordSet->fields[5], $pricelevel,1);
                    $unitprice=$unitprice*$recordSet->fields[9];
                    $pricecost=$recordSet->fields[5+$cost];
                    if ($pricecost==0) {
                         switch ($cost) {
                             case 1:
                                    $pricecost=$recordSet->fields[7];
                                    if ($pricecost==0) {
                                         $pricecost=$recordSet->fields[8];
                                    };
                                    break;
                             case 3:
                                    $pricecost=$recordSet->fields[7];
                                    if ($pricecost==0) {
                                         $pricecost=$recordSet->fields[6];
                                    };
                                    break;
                             default:
                                     $pricecost=$recordSet->fields[8];
                                    if ($pricecost==0) {
                                         $pricecost=$recordSet->fields[6];
                                    };
                         };
                    };
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[5].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[5]*$unitprice,2).'</td><td>'.CURRENCY_SYMBOL.num_format($pricecost,2).'</td><td>'.CURRENCY_SYMBOL.num_format($pricecost*$recordSet->fields[5]*$recordSet->fields[9],2).'</td></tr>';
              $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="i") {
               $recordSet = &$conn->Execute('select item.id, item.itemcode, item.description, itemcategory.name, markupset.costbased, itemlocation.onhandqty'.$coststr.', item.priceunitsperstockunit  from item,itemcategory,inventorylocation,company,unitname,itemlocation left join markupset on itemlocation.markupsetid=markupset.id where company.id=inventorylocation.companyid and inventorylocation.id=itemlocation.inventorylocationid and itemlocation.itemid=item.id and itemcategory.id=item.categoryid and item.companyid='.sqlprep($active_company).' and item.priceunitnameid=unitname.id'.$locationstr.' group by itemlocation.id order by item.itemcode');
               if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_QUANTITY'].'</th>';
               echo '<th>'.$pricename.''.$lang['STR_PRICE'].'</th>';
               echo '<th>';
               if ($cost==1) {
                    echo ''.$lang['STR_FIRST'].'<br>';
               } elseif ($cost==2) {
                    echo ''.$lang['STR_MID'].'<br>';
               } else {
                    echo ''.$lang['STR_LAST'].'<br>';
               };
               echo ''.$lang['STR_COST'].'</th>';
               echo '<th>'.$lang['STR_COST'].'<br>'.$lang['STR_VALUE'].'</th></tr>';
               while (!$recordSet->EOF) {
                    $unitprice=invitemprice($recordSet->fields[0], $recordSet->fields[5], $pricelevel,1);
                    $unitprice=$unitprice*$recordSet->fields[9];
                    $pricecost=$recordSet->fields[6+$cost];
                    if ($pricecost==0) {
                         switch ($cost) {
                             case 1:
                                    $pricecost=$recordSet->fields[7];
                                    if ($pricecost==0) {
                                         $pricecost=$recordSet->fields[8];
                                    };
                                    break;
                             case 3:
                                    $pricecost=$recordSet->fields[7];
                                    if ($pricecost==0) {
                                         $pricecost=$recordSet->fields[6];
                                    };
                                    break;
                             default:
                                     $pricecost=$recordSet->fields[8];
                                    if ($pricecost==0) {
                                         $pricecost=$recordSet->fields[6];
                                    };
                         };
                    };
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[5].'</td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[5]*$unitprice,2).'</td><td>'.CURRENCY_SYMBOL.num_format($pricecost,2).'</td><td>'.CURRENCY_SYMBOL.num_format($pricecost*$recordSet->fields[5]*$recordSet->fields[9],2).'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          };
     } else {
          echo '<form action="invitemlstval.php" method="post"><table>';
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
          echo '<option value="ci">'.$lang['STR_CATEGORY_ITEM_CODE'].'';
          echo '<option value="i">'.$lang['STR_ITEM_CODE'].'';
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COST'].':</td><td><select name="cost"'.INC_TEXTBOX.'>';
          echo '<option value="1">'.$lang['STR_FIRST'].'';
          echo '<option value="2" selected>'.$lang['STR_MID'].'';
          echo '<option value="3">'.$lang['STR_LAST'].'';
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
          echo '<input type="hidden" name="pricelevel" value="'.$recordSet->fields[0].'">';
      };
          echo '</table><br><input type="submit" value="'.$lang['STR_CREATE_REPORT'].'"></form>';
     };
        
         echo '</center>';
?>

<?php include('includes/footer.php'); ?>
