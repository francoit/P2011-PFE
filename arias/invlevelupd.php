<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //invlevelupd.php
    echo texttitle($lang['STR_PRICE_LEVEL_UPDATE']);
    echo '<center>';
    if ($id) {//user has select a price level to be updated
          if ($delete) { //if we should be deleting the entry
               checkpermissions('inv');
               if ($conn->Execute("delete from pricelevel where id=".sqlprep($id)) === false) {
                          echo texterror($lang['STR_ERROR_DELETING_PRICE_LEVEL']);
               } else {
                          echo textsuccess($lang['STR_PRICE_LEVEL_DELETED_SUCCESSFULLY']);
               };
               $description="";
          } elseif ($description) { //if we should update the entry
                  checkpermissions('inv');
                  if ($conn->Execute('update pricelevel set description='.sqlprep($description).' where id='.sqlprep($id)) === false) {
                     echo texterror($lang['STR_ERROR_UPDATING_PRICE_LEVEL']);
                  } else {
                     echo textsuccess($lang['STR_PRICE_LEVEL_UPDATE_SUCCESSFULLY']);
                  };
           } else {
               echo '<form action="invlevelupd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table><td><input type="hidden" name="id" value="'.$id.'"></td></tr>';
               $recordSet = &$conn->Execute('select id, description from pricelevel where id='.sqlprep($id));
               if (!$recordSet->EOF) echo '<tr><td>'.$lang['STR_PRICE_LEVEL_DESCRIPTION'].':</td><td><input type="text" name="description" size="30" value="'.$recordSet->fields[1].'"></td></tr>';
               echo '</tr></table><input type="submit" value="'.$lang['STR_UPDATE'].'"></form> <a href="javascript:confirmdelete(\'invlevelupd.php?delete=1&id='.$id.'\')">Delete this Price Level</a>';
          };
     } else { //display Price Levels, let the user pick one to edit
          $recordSet = &$conn->Execute('select id,description from pricelevel order by description');
          if (!$recordSet->EOF) {
             echo '<form action="invlevelupd.php" method="post"><table><tr><td>'.$lang['STR_PRICE_LEVEL'].':</td><td><select name="id">';
             while (!$recordSet->EOF) {
                 echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                 $recordSet->MoveNext();
             };
             echo '</select></td></tr></table><br><input type="submit" value="'.$lang['STR_SELECT'].'"></form>';
         };
         echo '<br><a href="invleveladd.php">'.$lang['STR_ADD_NEW_INVENTORY_PRICE_LEVEL'].'</a>';
         
         echo '</center>';
     };
     ?>

<?php include('includes/footer.php'); ?>
