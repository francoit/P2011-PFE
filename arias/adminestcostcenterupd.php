<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestcostcenterupd.php
     echo texttitle('Cost Center Update');
     if ($delete) { //delete cost center
             if ($conn->Execute('update estcostcenter set cancel=1 where id='.sqlprep($id)) === false) {
                echo texterror("Error deleting cost center.");
             } else {
                echo textsuccess("Cost center deleted successfully.");
                unset($id);  //return to first screen
             };
     };
     if ($name) { //update cost center
             if ($conn->Execute('update estcostcenter set name='.sqlprep($name).', cctype='.sqlprep($cctype).', orderflag='.sqlprep($orderflag).' where id='.sqlprep($id)) === false) {
                echo texterror("Error updating cost center.");
             } else {
                echo textsuccess("Cost center updated successfully.");
             };
             unset($id);
     };
     if ($id) { // if the user has selected a cost center
          echo '<form action="adminestcostcenterupd.php" method="post" name="mainform"><input type="hidden" name="id" value="'.$id.'"><table>';
          $recordSet2 = &$conn->SelectLimit('select name,cctype,orderflag from estcostcenter where id='.sqlprep($id),1);
          if (!$recordSet2||$recordSet2->EOF) die(texterror('Cost center not found.'));
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name:</td><td><input type="text" name="name" size="30" maxlength="30" value="'.$recordSet2->fields[0].'"'.INC_TEXTBOX.'"></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Cost Center Type:</td><td><select name="cctype"'.INC_TEXTBOX.'"><option value="'.SD_CCTYPE_PREFLIGHT.'"'.checkequal(SD_CCTYPE_PREFLIGHT,$recordSet2->fields[1],' selected').'>Pre-Flight<option value="'.SD_CCTYPE_PROOF.'"'.checkequal(SD_CCTYPE_PROOF,$recordSet2->fields[1],' selected').'>Proof<option value="'.SD_CCTYPE_PREPRESS.'"'.checkequal(SD_CCTYPE_PREPRESS,$recordSet2->fields[1],' selected').'>Prepress<option value="'.SD_CCTYPE_INK.'"'.checkequal(SD_CCTYPE_INK,$recordSet2->fields[1],' selected').'>Ink<option value="'.SD_CCTYPE_PAPER.'"'.checkequal(SD_CCTYPE_PAPER,$recordSet2->fields[1],' selected').'>Paper<option value="'.SD_CCTYPE_PRINT.'"'.checkequal(SD_CCTYPE_PRINT,$recordSet2->fields[1],' selected').'>Print<option value="'.SD_CCTYPE_FINISH.'"'.checkequal(SD_CCTYPE_FINISH,$recordSet2->fields[1],' selected').'>Finish<option value="'.SD_CCTYPE_QC.'"'.checkequal(SD_CCTYPE_QC,$recordSet2->fields[1],' selected').'>QC<option value="'.SD_CCTYPE_SHIP.'"'.checkequal(SD_CCTYPE_SHIP,$recordSet2->fields[1],' selected').'>Ship</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Order:</td><td><input type="text" name="orderflag" size="30" maxlength="5" value="'.$recordSet2->fields[2].'" onchange="validateint(this)"'.INC_TEXTBOX.'"></td></tr>';
          echo '</table><input type="submit" value="Save Changes"></form>';
          echo ' <a href="adminestcostcentersubtypeupd.php?costcenterid='.$id.'">Update/Add Machine Families</a>';
          echo ' <br><br><a href="javascript:confirmdelete(\'adminestcostcenterupd.php?delete=1&id='.$id.'\')">Delete this Cost Center</a>';
     } else { //show search
          $recordSet = &$conn->Execute('select id,name from estcostcenter where cancel=0 order by orderflag,name');
          if ($recordSet&&!$recordSet->EOF) {
              echo '<form action="adminestcostcenterupd.php" method="post" name="mainform"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Cost Center:</td><td><select name="id"'.INC_TEXTBOX.'>';
              while ($recordSet&&!$recordSet->EOF) {
                 echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                 $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
              echo '</table><input type="submit" name="submit" value="Edit Selection"></form>';
          };
          echo '<a href="adminestcostcenteradd.php">Add new Cost Center</a>';
     };
?>
<?php include('includes/footer.php'); ?>
