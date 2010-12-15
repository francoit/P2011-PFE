<?php include('includes/main.php'); ?>
<?
// Copyright 2003 Free Software Foundation. Authored by Chan Min Wai
?>
<?php   echo '<center>';
     echo texttitle($lang['STR_ITEM_LIST_COMPOSITE']);
     if ($update && $itemid) {
//        echo "\n<br>".$inventorylocationid;
//        echo "\n<br>".$itemid;
          for ($pos=1;$pos<=$cntr;$pos++) {
//             echo "\n<br>".${"comitemid".$pos};
//             echo "\n<br>".${"comitemreqqty".$pos};
               if ($conn->Execute('update itemlocation set onhandqty=onhandqty-'.${"comitemreqqty".$pos}.' where inventorylocationid='.sqlprep($inventorylocationid).' and itemid='.${"comitemid".$pos}) === false) die(texterror($lang['STR_ERROR_UPDATING_ITEM']));         
          }
          unset($undo);
          for ($pos=1;$pos<=$cntr;$pos++) {
               $recordSet = &$conn->Execute('select item.id, item.itemcode, item.description, itemlocation.onhandqty from item, itemlocation where item.id = itemlocation.itemid and itemlocation.inventorylocationid ='.sqlprep($inventorylocationid).' and item.id='.${"comitemid".$pos});
               ${"comitemid".$pos}=$recordSet->fields[0]; //item.id
               ${"comitemcode".$pos}=$recordSet->fields[1]; //item.itemcode
               ${"comitemdesc".$pos}=$recordSet->fields[2]; //item.description
               ${"comitemohqty".$pos}=$recordSet->fields[3]; //itemlocation.onhandqty
//               echo "\n<br>".${"comitemohqty".$pos};
               if (${"comitemohqty".$pos} < 0) {
                    $undo = 1;
               }
          }
          if ($undo) {
               echo texterror('Composit item join Fail, item quantity is Negative');
               echo '<table border="1">';
               echo '<th colspan="3">The New Quantity</th></tr>';
               echo '<tr><th>'.$lang['STR_SUB_ITEM_CODE'].'</th><th>'.$lang['STR_SUB_ITEM_NAME'].'</th><th>'.$lang['STR_QUANTITY'].'</th></tr>';
               for ($pos=1;$pos<=$cntr;$pos++) {
                    if ($conn->Execute('update itemlocation set onhandqty=onhandqty+'.${"comitemreqqty".$pos}.' where inventorylocationid='.sqlprep($inventorylocationid).' and itemid='.${"comitemid".$pos}) === false) die(texterror($lang['STR_ERROR_UPDATING_ITEM'].' Undo Error'));
                    echo "\n".'<tr><td><b>'.${"comitemcode".$pos}.'</b></td><td>'.${"comitemdesc".$pos}.'</td><td>'.${"comitemohqty".$pos}.'</td></tr>';           
               }
               echo '</table>';
          } else { 
               if ($conn->Execute('update itemlocation set onhandqty=onhandqty+'.$assitemqty.' where inventorylocationid='.sqlprep($inventorylocationid).' and itemid='.sqlprep($itemid)) === false) die(texterror($lang['STR_ERROR_UPDATING_ITEM']));          
               echo textsuccess($lang['STR_ORDER_UPDATED_SUCCESSFULLY']);
          }
     }
     if ($itemcode) {
          $recordSet = &$conn->Execute('select distinct compositeitemid.itemcodeid, item.itemcode, item.description, itemlocation.onhandqty, company.companyname from compositeitemid, item, itemlocation, company, inventorylocation where item.companyid='.sqlprep($active_company).' and item.itemcode = '.sqlprep($itemcode).' and itemlocation.inventorylocationid ='.sqlprep($inventorylocationid).' and itemlocation.inventorylocationid = inventorylocation.id and company.id = inventorylocation.companyid and itemlocation.itemid = compositeitemid.itemcodeid and item.id=compositeitemid.itemcodeid and item.cancel=0');
          $itemid = $recordSet->fields[0];
          $itemname = $recordSet->fields[1];
          $itemdesc = $recordSet->fields[2];
          $itemqty = $recordSet->fields[3];
          $locationname = $recordSet->fields[4];
          if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ITEMS_FOUND']));
          $recordSet3 = &$conn->Execute('select compositeitemid.subitemcodeid from compositeitemid where  compositeitemid.itemcodeid='.sqlprep($recordSet->fields[0]));
          $cntr=1;
          while (!$recordSet3->EOF) {
               $recordSet2 = &$conn->Execute('select item.id, item.itemcode, item.description, itemcategory.name, compositeitemid.quantity, unitname.unitname, itemlocation.onhandqty from item, itemcategory, unitname, compositeitemid, itemlocation where compositeitemid.subitemcodeid=item.id and compositeitemid.subitemcodeid = itemlocation.itemid and itemlocation.inventorylocationid ='.sqlprep($inventorylocationid).' and itemcategory.id=item.categoryid and item.id='.sqlprep($recordSet3->fields[0]).' and item.stockunitnameid=unitname.id and compositeitemid.quantity>0 order by item.itemcode');
               if ($recordSet2->EOF) {
                    $recordSet5 = &$conn->Execute('select item.id, item.itemcode, item.description, itemcategory.name, compositeitemid.quantity, unitname.unitname from item, itemcategory, unitname, compositeitemid where compositeitemid.subitemcodeid=item.id and itemcategory.id=item.categoryid and item.stockunitnameid=unitname.id and item.id='.sqlprep($recordSet3->fields[0]));
                    ${"comitemid".$cntr}=$recordSet5->fields[0]; //item.id
                    ${"comitemcode".$cntr}=$recordSet5->fields[1]; //item.itemcode
                    ${"comitemdesc".$cntr}=$recordSet5->fields[2]; //item.description
                    ${"comitemcat".$cntr}=$recordSet5->fields[3]; //itemcategory.name
                    ${"comitemqty".$cntr}=$recordSet5->fields[4]; //compositeitemid.quantity
                    ${"comitemunit".$cntr}=$recordSet5->fields[5]; 
                    ${"comitemohqty".$cntr}=0; //itemlocation.onhandqty
                    ${"comitemspec".$cntr}='<a href=invitemupd1.php?id='.$recordSet5->fields[0].'&&itemcode='.$recordSet5->fields[1].'&&description='.$recordSet5->fields[2].'&&inventorylocationid='.$inventorylocationid.'>Stock Location Need</a>';
                    $comitemmax = 0;
               }
               while (!$recordSet2->EOF) {
                    ${"comitemid".$cntr}=$recordSet2->fields[0]; //item.id
                    ${"comitemcode".$cntr}=$recordSet2->fields[1]; //item.itemcode
                    ${"comitemdesc".$cntr}=$recordSet2->fields[2]; //item.description
                    ${"comitemcat".$cntr}=$recordSet2->fields[3]; //itemcategory.name
                    ${"comitemqty".$cntr}=$recordSet2->fields[4]; //compositeitemid.quantity
                    ${"comitemunit".$cntr}=$recordSet2->fields[5]; 
                    ${"comitemohqty".$cntr}=$recordSet2->fields[6]; //itemlocation.onhandqty
                    $comitemmaxqty = (int) (${"comitemohqty".$cntr}/${"comitemqty".$cntr}); 
                    if (!isset($comitemmax)) $comitemmax = $comitemmaxqty;
                    if ($comitemmax > $comitemmaxqty) $comitemmax = $comitemmaxqty;
                    if ($comitemmax < 0) $comitemmax = 0;
//                    echo "\n<br>".$comitemmaxqty.'--'.$comitemmax;

                    $recordSet2->MoveNext(); 
               };
               $cntr++;
          $recordSet3->MoveNext();
          };
          $cntr--;
//          echo "\n<br>".$comitemmax;
          echo "\n".'<form action="invitemcompfunc.php" method="post" name="mainform"><table border="1">';
          echo "\n".'<input type="hidden" name="cntr" value="'.$cntr.'">';
          echo "\n".'<input type="hidden" name="inventorylocationid" value="'.$inventorylocationid.'">';
          echo "\n".'<input type="hidden" name="itemid" value="'.$itemid.'">';
          for ($pos=1;$pos<=$cntr;$pos++) {
          echo "\n".'<input type="hidden" name="comitemid'.$pos.'" value="'.${"comitemid".$pos}.'">';
          }
          echo "\n".'<th colspan="7"> Assembly '.$itemname.' - '.$itemcode.' on '.$locationname.' Quantity: <input type="text" name="assitemqty" onchange="qtyupdate()" size="5" maxlength="5" value=""'.INC_TEXTBOX.'>/'.$comitemmax.' Max, On Hand ('.$itemqty.')</th></tr>';
          echo "\n".'<tr><th>'.$lang['STR_SUB_ITEM_CODE'].'</th><th>'.$lang['STR_SUB_ITEM_NAME'].'</th><th>'.$lang['STR_CATEGORY'].'</th><th>Require Qty/Pack</th><th>QUANTITY on hand</th><th>Required for assebmly</th></tr>';
          for ($pos=1;$pos<=$cntr;$pos++) {
               echo "\n".'<tr><td><b>'.${"comitemcode".$pos}.'</b></td><td>'.${"comitemdesc".$pos}.'</td><td>'.${"comitemcat".$pos}.'</td><td>'.${"comitemqty".$pos}.' '.${"comitemunit".$pos}.'</td><td>'.${"comitemohqty".$pos}.' '.${"comitemspec".$pos}.'</td><td><input type="text" name="comitemreqqty'.$pos.'" onchange="qtyupdate()" size="5" maxlength="5" value=""'.INC_TEXTBOX.'></td></tr>';           
          }
         
          echo "\n".'</table>';
          if (($comitemmax > 0)) {
               echo '<input type="submit" name="update" value="'.$lang['STR_SUBMIT'].'">';
          } else {
               echo '<br><b>Not Possble to Join Composite item</b>';
          }
          echo "\n".'</form>';
          echo '<script language="JavaScript">'."\n";
          echo '      function qtyupdate() {'."\n";
          echo '            if (document.mainform.assitemqty.value > '.$comitemmax.' || document.mainform.assitemqty.value < 0) {'."\n";
          echo '            document.mainform.assitemqty.value = '.$comitemmax."\n";
          echo '            alert("Quantity Assembly is bigger then possible") }'."\n";
          for ($i=1; $i<$cntr+1; $i++) echo '            document.mainform.comitemreqqty'.$i.'.value =  '.${"comitemqty".$i}.' * eval(document.mainform.assitemqty.value)'."\n";
/*          for ($i=1; $i<$cntr+1; $i++) {
               if ( ${"comitemspec".$cntr} ) {
                    echo '            document.mainform.update.disabled = ture'."\n";
                    echo '            alert("Need Location for item") }'."\n";
                    break;
               }
          }
*/          echo '      }'."\n";
          echo '</script>'."\n";
     } elseif (!$update){
          echo '<form action="invitemcompfunc.php" method="post" name="mainform"><table>';
          $recordSet = &$conn->Execute('select count(*) from inventorylocation,company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' and company.cancel=0');
          if (!$recordSet->EOF) {
               if ($recordSet->fields[0]>1) {
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LOCATION'].':</td><td><select onchange="document.mainform.itemcode.value=\'\'" name="inventorylocationid"'.INC_TEXTBOX.'>';
                    $recordSet = &$conn->Execute('select inventorylocation.id,company.companyname from inventorylocation,company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' and company.cancel=0 order by company.companyname');
                    while (!$recordSet->EOF) {
                         echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                         $recordSet->MoveNext();
                    };
                    echo '</select></td></tr>';
               } elseif ($recordSet->fields[0]==1) {
                    $recordSet = &$conn->SelectLimit('select inventorylocation.id from inventorylocation,company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' and company.cancel=0',1);
                    if (!$recordSet->EOF) echo '<input type="hidden" name="inventorylocationid" value="'.$recordSet->fields[0].'">';
               };
          };
          echo '<br><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CATEGORY'].':</td><td><select name="category"'.INC_TEXTBOX.'><option value="0" selected>ALL';
          $recordSet = &$conn->Execute('select distinct itemcategory.id, itemcategory.name from item, itemcategory where item.companyid='.sqlprep($active_company).' and item.categoryid=itemcategory.id and item.compositeitemyesno=1 order by itemcategory.name');
          while (!$recordSet->EOF) {
              echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
			       $recordSet->MoveNext();
		     };
		     echo '</select>';          
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ITEM_CODE'].':</td><td><input type="text" length="50" maxlength="20" name="itemcode" value=""'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitemcomposite.php?name=itemcode&&inventorylocationid=\' + document.mainform.inventorylocationid.value + \'&&category=\' + document.mainform.category.value + \'\',\'cal\',\'dependent=yes,width=610,height=170,screenX=200,screenY=300,titlebar=yes\');"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a></td></tr>';

          /*echo '<br><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SORT_BY'].':</td><td><select name="order"'.INC_TEXTBOX.'>';
          echo '<br><option value="i">'.$lang['STR_ITEM_CODE'].'';
          echo '<br><option value="ci">'.$lang['STR_CATEGORY_ITEM_CODE'].'';
          echo '</select></td></tr>';*/
          echo '</table><br><br><input type="submit" value="'.$lang['STR_SELECT'].'"></form>';
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
