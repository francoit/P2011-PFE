<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestmachineupd.php
     if ($costcenterid&&$classid) {
         $recordSet = &$conn->Execute('select estcostcenter.name,estcostcentersubtype.name,estcostcenter.cctype from estcostcenter,estcostcentersubtype where estcostcenter.cancel=0 and estcostcenter.id='.sqlprep($costcenterid).' and estcostcentersubtype.id='.sqlprep($classid));
     } else {
         echo 'No Cost Center/Family Selected';
         die();
     };
     echo texttitle($recordSet->fields[0].' - '.$recordSet->fields[1].' Machine List Update');
     $cctype=$recordSet->fields[2];
     if ($delete) { //delete   machine
             if ($conn->Execute('update estmachine set cancel=1 where gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id)) === false) {
                echo texterror("Error deleting machine.");
             } else {
                echo textsuccess("Machine deleted successfully.");
                unset($name);
                unset($id);
             };
     };
     if ($name) { //update machine
             if ($conn->Execute('update estmachine set name='.sqlprep($name).', costcentersubtypeid='.sqlprep($classid).', costmachperhr='.sqlprep($costmachperhr).', costoperperhr='.sqlprep($costoperperhr).', costasstperhr='.sqlprep($costasstperhr).', factoverhead='.sqlprep($factoverhead).', genoverhead='.sqlprep($genoverhead).', markup='.sqlprep($markup).', orderflag='.sqlprep($orderflag).' where id='.sqlprep($id)) === false) {
                echo texterror("Error updating machine.");
             } else {
                echo textsuccess("Machine updated successfully.");
             };
             unset($name);
             unset($id);
     };
     if ($id) { // if the user has selected a machine
          echo '<form action="adminestmachineupd.php" method="post" name="mainform"><input type="hidden" name="id" value="'.$id.'"><table>';
          echo '<input type="hidden" name="classid" value="'.$classid.'">';
          echo '<input type="hidden" name="costcenterid" value="'.$costcenterid.'">';
          $recordSet2 = &$conn->SelectLimit('select name,costcentersubtypeid,costmachperhr,factoverhead,genoverhead,markup,orderflag,costoperperhr,costasstperhr from estmachine where gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id),1);
          if (!$recordSet2||$recordSet2->EOF) die(texterror('Machine not found.'));
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name:</td><td><input type="text" name="name" size="30" maxlength="30" value="'.$recordSet2->fields[0].'"'.INC_TEXTBOX.'"></td></tr>';
           echo '<input type="hidden" name="classid" value="'.$classid.'">';
           if ($cctype==SD_CCTYPE_PAPER||$cctype==SD_CCTYPE_INK) {
               //skip machine and operator costs/hour

           } else {
             echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Cost Machine / Hr:</td><td><input type="text" name="costmachperhr" size="30" maxlength="13" value="'.$recordSet2->fields[2].'" onchange="validatenum(this)"'.INC_TEXTBOX.'"></td></tr>';
             echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Cost Operator / Hr:</td><td><input type="text" name="costoperperhr" size="30" maxlength="13" value="'.$recordSet2->fields[7].'" onchange="validatenum(this)"'.INC_TEXTBOX.'"></td></tr>';
             echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Cost Assistant / Hr:</td><td><input type="text" name="costasstperhr" size="30" maxlength="13" value="'.$recordSet2->fields[8].'" onchange="validatenum(this)"'.INC_TEXTBOX.'"></td></tr>';
           };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Factory Overhead %:</td><td><input type="text" name="factoverhead" size="30" maxlength="13" value="'.$recordSet2->fields[3].'" onchange="validatenum(this)"'.INC_TEXTBOX.'"></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">General Overhead %:</td><td><input type="text" name="genoverhead" size="30" maxlength="13" value="'.$recordSet2->fields[4].'" onchange="validatenum(this)"'.INC_TEXTBOX.'"></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Markup %:</td><td><input type="text" name="markup" size="30" maxlength="13" value="'.$recordSet2->fields[5].'" onchange="validatenum(this)"'.INC_TEXTBOX.'"></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Order:</td><td><input type="text" name="orderflag" size="30" maxlength="5" value="'.$recordSet2->fields[6].'" onchange="validateint(this)"'.INC_TEXTBOX.'"></td></tr>';
          echo '</table><input type="submit" value="Save Changes"></form> <a href="javascript:confirmdelete(\'adminestmachineupd.php?delete=1&id='.$id.'\')">Delete this Machine</a>';
     } else { //show search
          $recordSet = &$conn->Execute('select id,name from estmachine where gencompanyid='.sqlprep($active_company).' and cancel=0 and costcentersubtypeid='.sqlprep($classid).' order by orderflag,name');
          if ($recordSet&&!$recordSet->EOF) {
              echo '<form action="adminestmachineupd.php" method="post" name="mainform"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Machine:</td><td><select name="id"'.INC_TEXTBOX.'>';
              while ($recordSet&&!$recordSet->EOF) {
                 echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                 $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
              echo '<input type="hidden" name="classid" value="'.$classid.'">';
              echo '<input type="hidden" name="costcenterid" value="'.$costcenterid.'">';
              echo '</table><input type="submit" name="submit" value="Select"></form>';
          };
          echo '<a href="adminestmachineadd.php?costcenterid='.$costcenterid.'&classid='.$classid.'">Add new Machine</a>';
          echo '<br><br><a href="adminestcostcenterupd.php">Select a new Cost Center</a>';
          echo '<br><br><a href="adminestcostcentersubtypeupd.php?costcenterid='.$costcenterid.'">Machine Family Selection</a>';

     };
?>
<?php include('includes/footer.php'); ?>
