<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestquotecblinkupd.php
     echo texttitle('Ink Update');
     if ($delete) { //delete ink
             if ($conn->Execute('delete from estquotecblink where gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id)) === false) {
                echo texterror("Error deleting ink.");
             } else {
                echo textsuccess("Ink deleted successfully.");
                unset($id);  //return to first screen
             };
     };
     if ($name) { //update ink
             if ($type==SD_INK_PMS) {
               $recordSet=&$conn->Execute('select id from estquotecblink where gencompanyid='.sqlprep($active_company).' and type='.sqlprep(SD_INK_PMS));
               if (!$recordSet->EOF) {
                   if ($id<>$recordSet->fields[0]) {
                     echo texterror("Cannot enter TWO inks as type = PMS INK");
                     die();
                   };
               };
             };

             if ($conn->Execute('update estquotecblink set name='.sqlprep($name).', jobprice='.sqlprep($jobprice).', mprice='.sqlprep($mprice).', type='.sqlprep($type).' where id='.sqlprep($id)) === false) {
                echo texterror("Error updating ink.");
             } else {
                echo textsuccess("Ink updated successfully.");
             };
     };
     if ($id) { // if the user has selected an ink
          echo '<form action="adminestquotecblinkupd.php" method="post" name="mainform"><input type="hidden" name="id" value="'.$id.'"><table>';
          $recordSet2 = &$conn->SelectLimit('select name,jobprice,mprice,type from estquotecblink where gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id),1);
          if ($recordSet2->EOF) die(texterror('Ink not found.'));
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name:</td><td><input type="text" name="name" size="30" maxlength="30" value="'.$recordSet2->fields[0].'"'.INC_TEXTBOX.'"></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Price / Job:</td><td><input type="text" name="jobprice" size="30" maxlength="9" value="'.$recordSet2->fields[1].'"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Price / M:</td><td><input type="text" name="mprice" size="30" maxlength="11" value="'.checkdec($recordSet2->fields[2],2).'"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Type:</td><td><select name="type"'.INC_TEXTBOX.'>';
          echo '<option value="'.SD_INK_PMS.'"'.checkequal($recordSet2->fields[3],SD_INK_PMS,' selected').'>CustomPMS';
          echo '<option value="'.SD_INK_COLOR.'"'.checkequal($recordSet2->fields[3],SD_INK_COLOR,' selected').'>Std.Color';
          echo '<option value="'.SD_INK_BLACK.'"'.checkequal($recordSet2->fields[3],SD_INK_BLACK,' selected').'>Black';
          echo '</select></td></tr></table><input type="submit" value="Save Changes"></form> <a href="javascript:confirmdelete(\'adminestquotecblinkupd.php?delete=1&id='.$id.'\')">Delete this Ink</a>';
     } else { //show search
          $recordSet = &$conn->Execute('select id,name from estquotecblink where gencompanyid='.sqlprep($active_company).' order by type DESC,name');
          if (!$recordSet->EOF) {
              echo '<form action="adminestquotecblinkupd.php" method="post" name="mainform"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Ink:</td><td><select name="id"'.INC_TEXTBOX.'>';
              while (!$recordSet->EOF) {
                 echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                 $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
              echo '</table><input type="submit" name="submit" value="Select"></form>';
          };
          echo '<a href="adminestquotecblinkadd.php">Add new Ink</a>';
     };
?>
<?php include('includes/footer.php'); ?>
