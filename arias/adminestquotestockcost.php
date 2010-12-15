<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
   $recordSet=&$conn->Execute('select estquotesubstock.name, estquotegenstock.name from estquotesubstock,estquotegenstock where estquotesubstock.id='.sqlprep($substockid).' and estquotesubstock.estquotegenstockid=estquotegenstock.id');
   if (!$recordSet->EOF) {
         $displayname=$recordSet->fields[0].' - '.$recordSet->fields[1];
         $name=$recordSet->fields[1];
   };
        echo texttitle('Stock Cost Update');
        echo texttitle($displayname);
                 if ($submit) {
                     $recordSet=&$conn->Execute('delete from estquotesubstockcost where substockid='.sqlprep($substockid));
                     for ($i=1; $i<=$counter; $i++) {
                         if (${"cost".$i}) {
                             $recordSet2=&$conn->SelectLimit('select id from item where itemcode='.sqlprep(${"itemcode".$i}).' and companyid='.sqlprep($active_company).' and cancel=0',1);
                             if ($recordSet2&&!$recordSet2->EOF) ${"itemid".$i}=$recordSet2->fields[0];
                             $recordSet=&$conn->Execute('insert into estquotesubstockcost (substockid,stocktype,length,width,cost,costhow,itemid) values ('.sqlprep($substockid).', '.sqlprep(${"stocktype".$i}).', '.sqlprep(${"length".$i}).', '.sqlprep(${"width".$i}).', '.sqlprep(${"cost".$i}).', '.sqlprep(${"costhow".$i}).', '.sqlprep(${"itemid".$i}).')');
                         };
                     };
                 };
                 //edit existing
                 echo '<form action="adminestquotestockcost.php" method="post" name="mainform">';
                 echo '<input type="hidden" name="genstockid" value="'.$genstockid.'">';
                 echo '<input type="hidden" name="substockid" value="'.$substockid.'">';
                 echo '<table>';
                 echo '<tr><th>Roll/Sheet/Ea</th><th colspan="2">Width x Length</th><th colspan="2">Cost</th><th>Inventory Item</th></tr>';
                 $i=1;
                 $recordSet=&$conn->Execute('select id,stocktype,length,width,cost,costhow,itemid from estquotesubstockcost where substockid='.sqlprep($substockid).' order by cost');
                 while (!$recordSet->EOF) {
                     echo '<tr><td><select name="stocktype'.$i.'"'.INC_TEXTBOX.'><option value="'.SD_ROLL.'"'.checkequal($recordSet->fields[1],SD_ROLL,' selected').'>Roll<option value="'.SD_SHEET.'"'.checkequal($recordSet->fields[1],SD_SHEET,' selected').'>Sheet<option value="'.SD_EACH.'"'.checkequal($recordSet->fields[1],SD_EACH,' selected').'>Each';
                     echo '</select></td><td><input type="text" name="width'.$i.'" size="5" maxlength="15" value="'.checkdec($recordSet->fields[3],1).'" onchange="validatenum(this)"'.INC_TEXTBOX.'></td><td><input type="text" name="length'.$i.'" size="5" maxlength="15" value="'.checkdec($recordSet->fields[2],1).'" onchange="validatenum(this)"'.INC_TEXTBOX.'></td><td><input type="text" name="cost'.$i.'" size="5" maxlength="15" value="'.checkdec($recordSet->fields[4],PREFERRED_DECIMAL_PLACES).'" onchange="validatenum(this)"'.INC_TEXTBOX.'></td>';
                     echo '<td><select name="costhow'.$i.'"'.INC_TEXTBOX.'>';
                            echo '<option value="'.SD_PER_M.'"'.checkequal($recordSet->fields[5],SD_PER_M,' selected').'>Per M';
                            echo '<option value="'.SD_PER_LB.'"'.checkequal($recordSet->fields[5],SD_PER_LB,' selected').'>Per LB';
                            echo '<option value="'.SD_PER_EACH.'"'.checkequal($recordSet->fields[5],SD_PER_EACH,' selected').'>Each';
                            echo '<option value="'.SD_PER_MSI.'"'.checkequal($recordSet->fields[5],SD_PER_MSI,' selected').'>MSI';
                            echo '</select>';
                     unset($itemid);
                     $recordSet2=&$conn->SelectLimit('select itemcode from item where id='.sqlprep($recordSet->fields[6]).' and companyid='.sqlprep($active_company).' and cancel=0',1);
                     if ($recordSet2&&!$recordSet2->EOF) $itemcode=$recordSet2->fields[0];
                     echo '</td><td><input type="text" name="itemcode'.$i.'" value="'.$itemcode.'" size="30" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode'.$i.'\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="'.IMAGE_ITEM_LOOKUP.'" border="0" alt="Item Lookup"></a></td></tr>';
                     $recordSet->MoveNext();
                     $i++;
                 };
                 echo '<tr><td><select name="stocktype'.$i.'"'.INC_TEXTBOX.'><option value="'.SD_ROLL.'">Roll<option value="'.SD_SHEET.'">Sheet<option value="'.SD_EACH.'">Each';
                 echo '</select></td><td><input type="text" name="width'.$i.'" size="5" maxlength="15" onchange="validatenum(this)"'.INC_TEXTBOX.'></td><td><input type="text" name="length'.$i.'" size="5" maxlength="15" onchange="validatenum(this)"'.INC_TEXTBOX.'></td><td><input type="text" name="cost'.$i.'" size="5" maxlength="15" onchange="validatenum(this)"'.INC_TEXTBOX.'></td>';
                 echo '<td><select name="costhow'.$i.'"'.INC_TEXTBOX.'><option value="'.SD_PER_M.'">Per M<option value="'.SD_PER_LB.'">Per LB<option value="'.SD_PER_EACH.'">Each</select>';
                 echo '</td><td><input type="text" name="itemcode'.$i.'" size="30" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode'.$i.'\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="'.IMAGE_ITEM_LOOKUP.'" border="0" alt="Item Lookup"></a></td></tr>';
                 echo '<input type="hidden" name="counter" value="'.$i.'">';
                 echo '</table><input type="submit" name="submit" value="Save Changes"><br><br>';
                 echo '<a href="estquotestockupd.php?substockid='.$substockid.'&&genstockid='.$genstockid.'&&updated=2&&name='.$name.'">Return to Sub Stock Edit</a><br>';

?>
<?php include('includes/footer.php'); ?>
