<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php   echo '<center>';

     echo texttitle($lang['STR_ITEM_LIST_STATUS']);
     if ($order) {
          if (!$userid) die(texterror($lang['STR_USER_ID_NOT_FOUND']));
          $conn->Execute('drop table invitemliststatus'.$userid);
          $conn->Execute('create table invitemliststatus'.$userid.' (companycompanyname char(50), inventorylocationid double,itemcategoryname char(50), itemitemcode char(50), itemitemname char(100), itemqtyonhand double, itemqtyordered double, itemqtycommitted double, itemqtyavailable double, itemqtyonorder double)');
          if ($location) {
               $locationstr=' and inventorylocation.id='.sqlprep($location);
               $recordSet = &$conn->Execute('select company.companyname from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.id='.sqlprep($location));
               if (!$recordSet->EOF) {
                     echo texttitle($lang['STR_FOR_LOCATION'] .$recordSet->fields[0]);
               };
          } else {
               echo texttitle($lang['STR_ALL_LOCATIONS']);
          };
          $recordSet = &$conn->Execute('select company.companyname, inventorylocation.id, itemcategory.name, item.itemcode, item.description, itemlocation.onhandqty as onhand from item,itemcategory, inventorylocation, company, itemlocation where company.id=inventorylocation.companyid and inventorylocation.id=itemlocation.inventorylocationid and itemlocation.itemid=item.id and item.companyid='.sqlprep($active_company).' and itemcategory.id=item.categoryid'.$locationstr.' group by itemlocation.id order by company.companyname, itemcategory.name, item.itemcode');
          while (!$recordSet->EOF) {
               $conn->Execute('insert into invitemliststatus'.$userid.' (companycompanyname, inventorylocationid, itemcategoryname, itemitemcode, itemitemname, itemqtyonhand) values ('.sqlprep($recordSet->fields[0]).', '.sqlprep($recordSet->fields[1]).', '.sqlprep($recordSet->fields[2]).', '.sqlprep($recordSet->fields[3]).', '.sqlprep($recordSet->fields[4]).', '.sqlprep($recordSet->fields[5]).')');

          $conn->Execute('update invitemliststatus'.$userid.' set itemqtyordered=0 where itemqtyordered is null');
          $conn->Execute('update invitemliststatus'.$userid.' set itemqtycommitted=0 where itemqtycommitted is null');
          $conn->Execute('update invitemliststatus'.$userid.' set itemqtyavailable=0 where itemqtyavailable is null');
          $conn->Execute('update invitemliststatus'.$userid.' set itemqtyonorder=0 where itemqtyonorder is null'); 
          
          // Here come another problem, If an item is never been order before it will not be in the item list 
          // and there will never a time that it will show up on the item status with value...
          // BRB Going into AR to decrease onhandqty
          
          $recordSet2 = &$conn->Execute('select item.itemcode, sum(arorderdetail.qtyorder), arorder.inventorylocationid from item,arorderdetail,arorder where item.itemcode='.sqlprep($recordSet->fields[3]).' and arorderdetail.itemid=item.id and arorderdetail.orderid=arorder.id group by item.id,arorder.inventorylocationid');
          while (!$recordSet2->EOF) {
               $temrecordSet2 = &$conn->Execute('select item.itemcode, sum(arordershipdetail.shipqty), arordership.locationid from item,arorderdetail,arordershipdetail,arordership where item.itemcode='.sqlprep($recordSet->fields[3]).' and arorderdetail.itemid=item.id and arordershipdetail.orderdetailid=arorderdetail.id and arordershipdetail.ordershipid=arordership.id group by item.id');
               while (!$temrecordSet2->EOF) {
                    if ($temrecordSet2->fields[0]==$recordSet2->fields[0] && $temrecordSet2->fields[2]==$recordSet2->fields[2]) {
                         $temparshipqty = $temrecordSet2->fields[1];
                         $temparlocationid = $recordSet2->fields[2];
                         break;
                    } else {
                         $temparshipqty = 0;
                         $temparlocationid = $recordSet2->fields[2];
                         //break;
                    }
                    $temrecordSet2->MoveNext();
               };
/*               if (!$recordSet->fields[1]) {
                    $itemqtyordered = 0;
               } else { 
                    $itemqtyordered = $recordSet->fields[1];
               }
               echo $itemqtyordered;
               $itemqtycommitted = $itemqtyordered - $temparshipqty;
               echo $itemqtycommitted;
               */
               //$conn->Execute('update invitemliststatus'.$userid.' set itemqtyordered='.$recordSet->fields[1].', itemqtycommitted = (itemqtyordered - temparshipqty) , itemqtyavailable=(itemqtyonhand-itemqtycommitted) where itemitemcode='.sqlprep($recordSet->fields[0]).' and inventorylocationid='.$temparlocationid);
               $conn->Execute('update invitemliststatus'.$userid.' set itemqtyordered='.sqlprep($recordSet2->fields[1]).', itemqtycommitted=('.sqlprep($recordSet2->fields[1]).'-'.$temparshipqty.'), itemqtyavailable=(itemqtyonhand-itemqtycommitted) where itemitemcode='.sqlprep($recordSet2->fields[0]).' and inventorylocationid='.$temparlocationid);
               $recordSet2->MoveNext();
          };
                         $recordSet->MoveNext();
          };
          //$conn->Execute('update invitemliststatus'.$userid.' set itemqtyordered=0 where itemqtyordered is null');
          
          // This SQL script have cause a Problem on Commited item, as only shipped item will be have a value
          // and other didn't, this make that the commited isn't the right value before any shipment is made
          
/*          $recordSet = &$conn->Execute('select item.itemcode, sum(arordershipdetail.shipqty) from item,arorderdetail,arordershipdetail where arorderdetail.itemid=item.id and arordershipdetail.orderdetailid=arorderdetail.id group by item.id');
          while (!$recordSet->EOF) {
          if (isset($recordSet->fields[1])) {
               $temparshipqty = $recordSet->fields[1];
          } else {
               $temparshipqty =0;
          }
               $conn->Execute('update invitemliststatus'.$userid.' set itemqtycommitted=(itemqtyordered-'.$temparshipqty.'), itemqtyavailable=(itemqtyonhand-itemqtycommitted) where itemitemcode='.sqlprep($recordSet->fields[0]));
               $recordSet->MoveNext();
          };*/
          $recordSet = &$conn->Execute('select item.itemcode, sum(invpodetail.itemqty*invpodetail.unitperpack), invpo.locationid from item,invpodetail,invpo where invpodetail.itemid=item.id and invpodetail.invpoid=invpo.id and invpo.complete=0 and invpo.cancel=0 group by item.id');
          while (!$recordSet->EOF) {
               $conn->Execute('update invitemliststatus'.$userid.' set itemqtyonorder='.sqlprep($recordSet->fields[1]).' where itemitemcode='.sqlprep($recordSet->fields[0]).' and inventorylocationid='.sqlprep($recordSet->fields[2]));
               $recordSet->MoveNext();
          };
          
          //$conn->Execute('update invitemliststatus'.$userid.' set itemqtycommitted=0 where itemqtycommitted is null');
          //$conn->Execute('update invitemliststatus'.$userid.' set itemqtyavailable=0 where itemqtyavailable is null');
          //$conn->Execute('update invitemliststatus'.$userid.' set itemqtyonorder=0 where itemqtyonorder is null');
          if ($order=="lci") {
               $recordSet = &$conn->Execute('select stat.companycompanyname, stat.companycompanyname, stat.itemcategoryname, stat.itemitemcode, stat.itemitemname, stat.itemqtyonhand, stat.itemqtycommitted, stat.itemqtyavailable, stat.itemqtyonorder from invitemliststatus'.$userid.' as stat order by stat.companycompanyname, stat.itemcategoryname, stat.itemitemcode');
               if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_QTY_ON_HAND'].'</th><th>'.$lang['STR_QTY_COMMITTED'].'</th><th>'.$lang['STR_QTY_AVAILABLE'].'</th><th>'.$lang['STR_QTY_ON_ORDER'].'</th></tr>';
               while (!$recordSet->EOF) {
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td style="text-align: right;">'.$recordSet->fields[5].'</td><td style="text-align: right;">';
                    if ($recordSet->fields[6] <> 0) {
                        echo '<a href="arorderitemlst.php?itemcode='.$recordSet->fields[3].'">'.$recordSet->fields[6].'</a>';
                    } else {
                        echo $recordSet->fields[6];
                    }
                    echo '</td><td style="text-align: right;">'.$recordSet->fields[8].'</td><td style="text-align: right;">';
                    if ($recordSet->fields[8] <> 0) {
                        echo '<a href="invpolist.php?itemcode='.$recordSet->fields[3].'&pend=1&submit=1">'.$recordSet->fields[8].'</a>';
                    } else {
                        echo $recordSet->fields[8];
                    }
                    echo '</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="li") {
               $recordSet = &$conn->Execute('select stat.companycompanyname, stat.companycompanyname, stat.itemitemcode, stat.itemitemname, stat.itemcategoryname, stat.itemqtyonhand, stat.itemqtycommitted, stat.itemqtyavailable, stat.itemqtyonorder from invitemliststatus'.$userid.' as stat order by stat.companycompanyname, stat.itemitemcode');
               if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th>'.$lang['STR_LOCATION'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_QTY_ON_HAND'].'</th><th>'.$lang['STR_QTY_COMMITTED'].'</th><th>'.$lang['STR_QTY_AVAILABLE'].'</th><th>'.$lang['STR_QTY_ON_ORDER'].'</th></tr>';
               while (!$recordSet->EOF) {
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td style="text-align: right;">'.$recordSet->fields[5].'</td><td style="text-align: right;">';
                    if ($recordSet->fields[6] <> 0) {
                        echo '<a href="arorderitemlst.php?itemcode='.$recordSet->fields[2].'">'.$recordSet->fields[6].'</a>';
                    } else {
                        echo $recordSet->fields[6];
                    }
                    echo '</td><td style="text-align: right;">'.$recordSet->fields[7].'</td><td style="text-align: right;">';
                    if ($recordSet->fields[8] <> 0) {
                        echo '<a href="invpolist.php?itemcode='.$recordSet->fields[3].'&pend=1&submit=1">'.$recordSet->fields[8].'</a>';
                    } else {
                        echo $recordSet->fields[8];
                    }
                    echo '</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="ci") {
               $recordSet = &$conn->Execute('select stat.companycompanyname, stat.itemcategoryname, stat.itemitemcode, stat.itemitemname, sum(stat.itemqtyonhand), sum(stat.itemqtycommitted), sum(stat.itemqtyonorder) from invitemliststatus'.$userid.' as stat group by stat.itemitemcode order by stat.itemcategoryname, stat.itemitemcode');
               if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_QTY_ON_HAND'].'</th><th>'.$lang['STR_QTY_COMMITTED'].'</th><th>'.$lang['STR_QTY_AVAILABLE'].'</th><th>'.$lang['STR_QTY_ON_ORDER'].'</th></tr>';
               while (!$recordSet->EOF) {
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td style="text-align: right;">'.$recordSet->fields[4].'</td><td style="text-align: right;">';
                    if ($recordSet->fields[5] <> 0) {
                        echo '<a href="arorderitemlst.php?itemcode='.$recordSet->fields[2].'">'.$recordSet->fields[5].'</a>';
                    } else {
                        echo $recordSet->fields[5];
                    }
                    echo '</td><td style="text-align: right;">'.($recordSet->fields[4]-$recordSet->fields[5]).'</td><td style="text-align: right;">';
                    if ($recordSet->fields[6] <> 0) {
                        echo '<a href="invpolist.php?itemcode='.$recordSet->fields[2].'&pend=1&submit=1">'.$recordSet->fields[6].'</a>';
                    } else {
                        echo $recordSet->fields[6];
                    }
                    echo '</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } elseif ($order=="i") {
               $recordSet = &$conn->Execute('select stat.companycompanyname, stat.itemitemcode, stat.itemitemname, stat.itemcategoryname, sum(stat.itemqtyonhand), sum(stat.itemqtycommitted), sum(stat.itemqtyonorder) from invitemliststatus'.$userid.' as stat group by stat.itemitemcode order by stat.itemitemcode');
               if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
               echo '<table border="1"><tr><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_ITEM_NAME'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>'.$lang['STR_QTY_ON_HAND'].'</th><th>'.$lang['STR_QTY_COMMITTED'].'</th><th>'.$lang['STR_QTY_AVAILABLE'].'</th><th>'.$lang['STR_QTY_ON_ORDER'].'</th></tr>';
               while (!$recordSet->EOF) {
                    if ($recordSet->fields[5]) $link='<a href="arorderitemlst.php?itemcode='.$recordSet->fields[1].'">';
                    echo '<tr><td><b>'.$recordSet->fields[1].'</b></td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td style="text-align: right;">'.$recordSet->fields[4].'</td><td style="text-align: right;">';
                    if ($recordSet->fields[5] <> 0) {
                        echo '<a href="arorderitemlst.php?itemcode='.$recordSet->fields[1].'">'.$recordSet->fields[5].'</a>';
                    } else {
                        echo $recordSet->fields[5];
                    }
                    echo '</td><td style="text-align: right;">'.($recordSet->fields[4]-$recordSet->fields[5]).'</td><td style="text-align: right;">';
                    if ($recordSet->fields[6] <> 0) {
                        echo '<a href="invpolist.php?itemcode='.$recordSet->fields[1].'&pend=1&submit=1">'.$recordSet->fields[6].'</a>';
                    } else {
                        echo $recordSet->fields[6];
                    }
                    echo '</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          };
          $conn->Execute('drop table invitemliststatus'.$userid);
     } else {
          echo '<form action="invitemlststatus.php" method="post"><table>';
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
          echo '<option value="i">'.$lang['STR_ITEM_CODE'].'';
          echo '<option value="ci">'.$lang['STR_CATEGORY_ITEM_CODE'].'';
          echo '<option value="lci">'.$lang['STR_LOCATION_CATEGORY_ITEM_CODE'].'';
          echo '<option value="li">'.$lang['STR_LOCATION_ITEM_CODE'].'';
          echo '</select></td></tr>';
          echo '</table><input type="submit" value="'.$lang['STR_CREATE_REPORT'].'"></form>';
          echo '<center>';
          
     };
?>

<?php include('includes/footer.php'); ?>
