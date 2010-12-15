<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestcostcentersubtypeupd.php
     if ($costcenterid) {
         $recordSet = &$conn->Execute('select name from estcostcenter where cancel=0 and id='.sqlprep($costcenterid));
     } else {
         echo 'No Cost Center Selected';
         die();
     };
     echo texttitle($recordSet->fields[0].' Cost Center Machine Family Update');

     if ($delete) { //delete cost center
             if ($conn->Execute('update estcostcentersubtype set cancel=1 where id='.sqlprep($id)) === false) {
                echo texterror("Error deleting cost center machine family.");
             } else {
                echo textsuccess("Cost center machine family deleted successfully.");
                unset($id);  //return to first screen
             };
     };
     if ($name) { //update cost center
             if ($conn->Execute('update estcostcentersubtype set name='.sqlprep($name).', costcenterid='.sqlprep($costcenterid).', orderflag='.sqlprep($orderflag).' where id='.sqlprep($id)) === false) {
                echo texterror("Error updating cost center family.");
             } else {
                echo textsuccess("Cost Center Family updated successfully.");
             };
             $id="";
     };
     if ($id) { // if the user has selected a cost center machine family
          echo '<form action="adminestcostcentersubtypeupd.php" method="post" name="mainform">';
          echo '<input type="hidden" name="costcenterid" value="'.$costcenterid.'">';
          echo '<input type="hidden" name="id" value="'.$id.'"><table>';
          $recordSet2 = &$conn->SelectLimit('select name,costcenterid,orderflag from estcostcentersubtype where id='.sqlprep($id),1);
          if (!$recordSet2||$recordSet2->EOF) die(texterror('Cost center machine family not found.'));
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name of family of machines:</td><td><input type="text" name="name" size="30" maxlength="30" value="'.$recordSet2->fields[0].'"'.INC_TEXTBOX.'"></td></tr>';

          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Order:</td><td><input type="text" name="orderflag" size="30" maxlength="5" value="'.$recordSet2->fields[2].'" onchange="validateint(this)"'.INC_TEXTBOX.'"></td></tr>';
          echo '</table><input type="submit" value="Save Changes"></form>';
          echo '<a href="javascript:confirmdelete(\'adminestcostcenterupd.php?delete=1&costcenterid='.$costcenterid.'\')">Delete this Cost Center Machine Family</a>';
          echo '<br><br><a href="adminestmachineupd.php?costcenterid='.$costcenterid.'&classid='.$id.'">Add/Update Machine List</a>';
     } else { //show search
          $recordSet = &$conn->Execute('select estcostcentersubtype.id,estcostcentersubtype.name,estcostcenter.name from estcostcentersubtype,estcostcenter where estcostcentersubtype.cancel=0 and estcostcentersubtype.costcenterid=estcostcenter.id and estcostcenter.id='.sqlprep($costcenterid).' order by estcostcenter.name,estcostcentersubtype.orderflag,estcostcentersubtype.name');
          if ($recordSet&&!$recordSet->EOF) {
              echo '<form action="adminestcostcentersubtypeupd.php" method="post" name="mainform"><table>';
              echo '<input type="hidden" name="costcenterid" value="'.$costcenterid.'">';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Cost Center Machine Family:</td><td><select name="id"'.INC_TEXTBOX.'>';
              while ($recordSet&&!$recordSet->EOF) {
                 echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[2].' - '.$recordSet->fields[1]."\n";
                 $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
              echo '</table><input type="submit" name="submit" value="Select"></form>';
          };
          echo '<a href="adminestcostcenterupd.php">Select a new Cost Center</a>';
          echo '<br><br><a href="adminestcostcentersubtypeadd.php?costcenterid='.$costcenterid.'">Add New Machine Family</a>';
     };
?>
<?php include('includes/footer.php'); ?>
