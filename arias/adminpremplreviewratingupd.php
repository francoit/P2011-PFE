<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center>';
     echo texttitle($lang['STR_EMPLOYEE_RATING_UPDATE']);
     if ($delete) { //if we should be deleting the entry
          checkpermissions('pay');
          if ($conn->Execute("delete from premplreviewrating where id=".sqlprep($id)) === false) {
                     echo texterror($lang['STR_ERROR_DELETING_REVIEW_RATING']);
          } else {
                     echo textsuccess($lang['STR_REVIEW_RATING_DELETED_SUCCESSFULLY']);
                     unset($id);
          };
     } elseif ($description) { //if we should update the entry
          checkpermissions('pay');
          if ($conn->Execute("update premplreviewrating set description=".sqlprep($description)." where id=".sqlprep($id)) === false) {
                     echo texterror($lang['STR_ERROR_UPDATING_REVIEW_RATING']);
          } else {
                     echo textsuccess($lang['STR_REVIEW_RATING_UPDATED_SUCCESSFULLY']);
          };
     };
     if ($id) { // if the user has submitted info
          echo '<form action="adminpremplreviewratingupd.php" method="post"><table><tr><td>'.$lang['STR_DESCRIPTION'].':</td><td><input type="hidden" name="id" value="'.$id.'">';
          $recordSet = &$conn->Execute('select description from premplreviewrating where id='.$id);
          if (!$recordSet->EOF) echo '<input type="text" name="description" size="30" value="'.$recordSet->fields[0].'"'.INC_TEXTBOX.'>';
          echo '</td></tr></table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'adminpremplreviewratingupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_REVIEW_RATING'].'</a>';
     } else { //display review ratings, let the user pick one to edit
          $recordSet = &$conn->Execute('select id,description from premplreviewrating order by description');
          if (!$recordSet->EOF) {
             echo '<form action="adminpremplreviewratingupd.php" method="post"><table><tr><td>'.$lang['STR_DESCRIPTION'].':</td><td><select name="id"'.INC_TEXTBOX.'>';
             while (!$recordSet->EOF) {
                 echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                 $recordSet->MoveNext();
             };
             echo '</select></td></tr></table><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></form>';
          };
          echo '<a href="adminpremplreviewratingadd.php">'.$lang['STR_ADD_NEW_REVIEW_RATING'].'</a>';
     };
     
     echo '</center>';
?>
<?php include('includes/footer.php'); ?>
