<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //invmarkupupd.php
     echo texttitle($lang['STR_STANDARD_MARKUP_SET_UPDATE']);
     echo '<center>';
     if ($description) { //have updated data, now save changes
          if ($delete) { //if we should be deleting the entry
               $recordSet=&$conn->Execute("select description, id from pricelevel");
               $counter=0;
               while (!$recordSet->EOF) {
                             $counter++;
                             ${"pricelevel".$counter} = $recordSet->fields[0];
                             ${"levelid".$counter}=$recordSet->fields[1];
                             $recordSet->MoveNext();
               };
               checkpermissions('inv');
               if ($conn->Execute("delete from markupset where id=".sqlprep($id)) === false) {
                          echo texterror($lang['STR_ERROR_DELETING_MARKUP_SET']);
               } else {
                          echo textsuccess($lang['STR_MARKUP_SET_DELETED_SUCCESSFULLY']);
               };
               for ($levelcounter=1;$levelcounter<=$counter;$levelcounter++) { //delete level info
                  if ($conn->Execute('delete from markupsetlevel where markupsetid='.sqlprep($id).' and pricelevelid='.sqlprep(${"levelid".$levelcounter})) === false) echo texterror("Error deleting Markup Set for level.");
               };
               $description="";
               die(textsuccess($lang['STR_MARKUP_SET_DELETED']));
           };
           checkpermissions('inv');
           if ($conn->Execute('update markupset set description='.sqlprep($description).', costbased='.sqlprep($costbased).' where id='.sqlprep($id)) === false) die (texterror("Error updating Standard Markup Set."));
           //loop thru levels & update
           $recordSet=&$conn->Execute('select id from markupset where description='.sqlprep($description));
           if (!$recordSet->EOF) $id=$recordSet->fields[0];
           for ($levelcounter=1;$levelcounter<=$counter;$levelcounter++) {
                $recordSet=&$conn->Execute('select * from markupsetlevel where markupsetid='.sqlprep($id).' and pricelevelid='.sqlprep(${"levelid".$levelcounter}));
                 if ($recordSet->EOF) {  // save now, no match found
                      if ($conn->Execute("insert into markupsetlevel (pricelevelid,markupsetid,markuppercent) VALUES (".sqlprep(${"levelid".$levelcounter}).",".sqlprep($id).",".sqlprep(${"price".$levelcounter}).")") === false) {
                           if ($conn->Execute('update markupsetlevel set pricelevelid='.sqlprep(${"levelid".$levelcounter}).', markupsetid='.sqlprep($id).', markuppercent='.sqlprep(${"price".$levelcounter}).' where markupsetid='.sqlprep($id).' and pricelevelid='.sqlprep(${"levelid".$levelcounter})) === false) echo texterror("Error updating/adding Markup Set for Level.");
                      } else {
                        echo textsuccess($lang['STR_MARKUP_SET_FOR_LEVEL_UPDATED_SUCCESSFULLY']);
                      };
                 };
            };
     } elseif ($id) { //  read current data from file
          $recordSet=&$conn->Execute('select id, description, costbased from markupset where id='.sqlprep($id));
          if (!$recordSet->EOF) {
               //found data record
               $id=$recordSet->fields[0];
               $description=$recordSet->fields[1];
               $costbased=$recordSet->fields[2];
          } else {
               die (texterror($lang['STR_COULD_NOT_FIND_MARKUP_SET']));
          };
          //now read level information
          // now ask for price level info for this set
          $recordSet=&$conn->Execute("select description, id from pricelevel");
          $counter=0;
          while (!$recordSet->EOF) {
                   $counter++;
                   ${"pricelevel".$counter} = $recordSet->fields[0];
                   ${"levelid".$counter}=$recordSet->fields[1];
                   $recordSet->MoveNext();
          };
          for ($levelcounter=1;$levelcounter<=$counter;$levelcounter++) {
                 //read actual price data into array
                 $recordSet=&$conn->Execute('select id, markuppercent from markupsetlevel where markupsetid='.sqlprep($id).' and pricelevelid='.sqlprep(${"levelid".$levelcounter}));
                 if (!$recordSet->EOF) {  // found match, read data
                      ${"price".$levelcounter}=$recordSet->fields[1];
                      //echo 'price='.$recordSet->fields[1];
                 };
           };
           // now ask info for this set
           echo '<form action="invmarkupupd.php" method="post"><input type="hidden" name="nonprintable" value="1">';
           echo '<table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MARKUP_SET_DESCRIPTION'].':<td><input type="text" name="description" value="'.$description.'"'.INC_TEXTBOX.'></td></tr>';
           echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MARKUP_BASED_ON'].':</td><td><select name="costbased"'.INC_TEXTBOX.'>';
                         if ($recordSet2->fields[0]==$recordSet->fields[2]) {
                              echo '<option value="'.$recordSet2->fields[0].'" selected>'.$recordSet2->fields[1]."\n";
                         } else {
                              echo '<option value="'.$recordSet2->fields[0].'">'.$recordSet2->fields[1]."\n";
                         };
           echo '<option value="1"'.checkequal($costbased,1," selected").'>'.$lang['STR_FIRST_COST'].' ';
           echo '<option value="2"'.checkequal($costbased,2," selected").'>'.$lang['STR_MID_COST'].' ';
           echo '<option value="3"'.checkequal($costbased,3," selected").'>'.$lang['STR_LAST_COST'].' ';
           echo '</select></td></tr></table><input type="hidden" name="id" value="'.$id.'">';
           echo '<table border=1><tr><th>'.$lang['STR_PRICE_LEVEL'].'</th><th>'.$lang['STR_MARKUP'].'</th></tr>';
           echo '<input type="hidden" name="id" value="'.$id.'"><input type="hidden" name="description" value="'.$description.'"><input type="hidden" name="counter" value="'.$counter.'">';
           for ($levelcounter=1;$levelcounter<=$counter;$levelcounter++) {
                 echo '<td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.${"pricelevel".$levelcounter}.'</td><td><input type="text" name="price'.$levelcounter.'" onchange="validatenum(this)" size="10" value="'.${"price".$levelcounter}.'"'.INC_TEXTBOX.'></td></tr>';
                 echo '<input type="hidden" name="levelid'.$levelcounter.'" value="'.${"levelid".$levelcounter}.'">';
           };
           echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
           echo '<a href="javascript:confirmdelete(\'invmarkupupd.php?delete=1&id='.$id.'&description='.$description.'\')">'.$lang['STR_DELETE_THIS_MARKUP_SET'].'</a>';
       } else {
            $recordSet = &$conn->Execute('select id, description from markupset order by description');
            if (!$recordSet->EOF) {
                echo '<form action="invmarkupupd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table><tr><td>'.$lang['STR_SELECT_MARKUP_SET'].':</td><td><select name="id"'.INC_TEXTBOX.'>';
                while (!$recordSet->EOF) {
                   echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                   $recordSet->MoveNext();
                };
                echo '</select></table><br><input type="submit" value="'.$lang['STR_EDIT'].'"></form>';
            };
            echo '<br><a href="invmarkupadd.php">'.$lang['STR_ADD_NEW_INVENTORY_MARKUP'].'</a>';
     };
     
     echo '</center>';
?>

<?php include('includes/footer.php'); ?>
