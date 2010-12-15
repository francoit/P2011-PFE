<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //invitemcatupd.php
     echo texttitle($lang['STR_ITEM_CATEGORY_UPDATE']);
     echo '<center>';
     if ($id) { // if the user has submitted info
          if ($delete) { //if we should be deleting the entry
               checkpermissions('inv');
               if ($conn->Execute("delete from itemcategory where id=".sqlprep($id)) === false) {
                          echo texterror($lang['STR_ERROR_DELETING_ITEM_CATEGORY']);
               } else {
                          echo textsuccess($lang['STR_ITEM_CATEGORY_DELTED_SUCCESSFULLY']);
               };
          } elseif ($name) { //if we should update the entry
               checkpermissions('inv');
               if ($seasonbegin1==0) {
                    $seasonname1="all";
                    $seasonbegin1=1;
                    $seasonend1=12;
                    $seasonname2="";
                    $seasonbegin2=0;
                    $seasonend2=0;
                    $seasonname3="";
                    $seasonbegin3=0;
                    $seasonend3=0;
                    $seasonname4="";
                    $seasonbegin4=0;
                    $seasonend4=0;
               };
               if ($conn->Execute("update itemcategory set name=".sqlprep($name).", seasonname1=".sqlprep($seasonname1).", seasonname2=".sqlprep($seasonname2).", seasonname3=".sqlprep($seasonname3).", seasonname4=".sqlprep($seasonname4).", seasonbegin1=".sqlprep($seasonbegin1).", seasonbegin2=".sqlprep($seasonbegin2).", seasonbegin3=".sqlprep($seasonbegin3).", seasonbegin4=".sqlprep($seasonbegin4).", seasonend1=".sqlprep($seasonend1).", seasonend2=".sqlprep($seasonend2).", seasonend3=".sqlprep($seasonend3).", seasonend4=".sqlprep($seasonend4)." where id=".sqlprep($id)) === false) {
                          echo texterror($lang['STR_ERROR_UPDATING_ITEM_CATEGORY']);
               } else {
                          echo textsuccess($lang['STR_ITEM_CATEGORY_UPDATED_SUCCESSFULLY']);
               };
          } else {
          //display more info about the entry that the user can edit
               echo '<form action="invitemcatupd.php" method="post"><table><tr><td>'.$lang['STR_ITEM_CATEGORY'].':</td><td><input type="hidden" name="id" value="'.$id.'">';
               $recordSet = &$conn->Execute('select name,seasonname1,seasonname2,seasonname3,seasonname4,seasonbegin1,seasonbegin2,seasonbegin3,seasonbegin4,seasonend1,seasonend2,seasonend3,seasonend4 from itemcategory where id='.$id);
               if (!$recordSet->EOF) {
                    echo '<input type="text" name="name" size="30" value="'.$recordSet->fields[0].'"'.INC_TEXTBOX.'>';
                    $seasonname1=$recordSet->fields[1];
                    $seasonname2=$recordSet->fields[2];
                    $seasonname3=$recordSet->fields[3];
                    $seasonname4=$recordSet->fields[4];
                    $seasonbegin1=$recordSet->fields[5];
                    $seasonbegin2=$recordSet->fields[6];
                    $seasonbegin3=$recordSet->fields[7];
                    $seasonbegin4=$recordSet->fields[8];
                    $seasonend1=$recordSet->fields[9];
                    $seasonend2=$recordSet->fields[10];
                    $seasonend3=$recordSet->fields[11];
                    $seasonend4=$recordSet->fields[12];

               };
               if (!$seasonbegin1) {
                    $seasonbegin1=1;
                    $seasonend1=12;
               };
               echo '<tr><th>'.$lang['STR_SEASON_NAME'].'</th><th>'.$lang['STR_BEGINNING_MONTH'].'</th><th>'.$lang['STR_ENDING_MONTH'].'</th></tr>';
               echo '<tr><td><input type="text" name="seasonname1" maxlength="20" value="'.$seasonname1.'"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonbegin1" value="'.$seasonbegin1.'"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonend1" value="'.$seasonend1.'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td><input type="text" name="seasonname2" maxlength="20" value="'.$seasonname2.'"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonbegin2" value="'.$seasonbegin2.'"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonend2" value="'.$seasonend2.'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td><input type="text" name="seasonname3" maxlength="20" value="'.$seasonname3.'"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonbegin3" value="'.$seasonbegin3.'"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonend3" value="'.$seasonend3.'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td><input type="text" name="seasonname4" maxlength="20" value="'.$seasonname4.'"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonbegin4" value="'.$seasonbegin4.'"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonend4" value="'.$seasonend4.'"'.INC_TEXTBOX.'></td></tr>';

               echo '</select></td></tr></table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'invitemcatupd.php?delete=1&id='.$id.'\')">Delete this Item Category</a>';
          };
     } else { //display item categories, let the user pick one to edit
          $recordSet = &$conn->Execute('select id,name from itemcategory order by name');
          if (!$recordSet->EOF) {
              echo '<form action="invitemcatupd.php" method="post"><table><tr><td>'.$lang['STR_ITEM_CATEGORY'].':</td><td><select name="id"'.INC_TEXTBOX.'>';
              while (!$recordSet->EOF) {
                 echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                 $recordSet->MoveNext();
              };
              echo '</select></td></tr></table><br><input type="submit" value="'.$lang['STR_EDIT'].'"></form>';
          };
          echo '<br><a href="invitemcatadd.php">'.$lang['STR_ADD_NEW_ITEM_CATEGORY'].'</a>';
     };
      
      echo '<center>';
?>

<?php include('includes/footer.php'); ?>
