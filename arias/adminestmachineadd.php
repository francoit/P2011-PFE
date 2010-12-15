<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestmachineadd.php
     if ($costcenterid&&$classid) {
         $recordSet = &$conn->Execute('select estcostcenter.name,estcostcentersubtype.name,estcostcenter.cctype from estcostcenter,estcostcentersubtype where estcostcenter.cancel=0 and estcostcenter.id='.sqlprep($costcenterid).' and estcostcentersubtype.id='.sqlprep($classid));
     } else {
         echo 'No Cost Center/Family Selected';
         die();
     };
     echo texttitle($recordSet->fields[0].' - '.$recordSet->fields[1].' Machine Add');
     $cctype=$recordSet->fields[2];
     if ($name) { //if the user has submitted info
//             checkpermissions('l3');
             if ($conn->Execute('insert into estmachine (name,costcentersubtypeid,costmachperhr,costoperperhr,costasstperhr,factoverhead,genoverhead,markup,orderflag,gencompanyid) VALUES ('.sqlprep($name).', '.sqlprep($classid).', '.sqlprep($costmachperhr).', '.sqlprep($costoperperhr).', '.sqlprep($costasstperhr).', '.sqlprep($factoverhead).', '.sqlprep($genoverhead).', '.sqlprep($markup).', '.sqlprep($orderflag).', '.sqlprep($active_company).')') === false) {
                echo texterror("Error adding machine.");
             } else {
                echo textsuccess("Machine added successfully.");
             };
     };
     echo '<form action="adminestmachineadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
     echo '<input type="hidden" name="costcenterid" value="'.$costcenterid.'">';
     echo '<input type="hidden" name="classid" value="'.$classid.'">';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name:</td><td><input type="text" name="name" size="30" maxlength="30"'.INC_TEXTBOX.'"></td></tr>';
     if ($cctype==SD_CCTYPE_PAPER||$cctype==SD_CCTYPE_INK){
         //skip machine, and operator costs per hour
     } else {
        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Cost Machine / Hr:</td><td><input type="text" name="costmachperhr" size="30" maxlength="13" onchange="validatenum(this)"'.INC_TEXTBOX.'"></td></tr>';
        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Cost Operator / Hr:</td><td><input type="text" name="costoperperhr" size="30" maxlength="13" onchange="validatenum(this)"'.INC_TEXTBOX.'"></td></tr>';
        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Cost Assistant / Hr:</td><td><input type="text" name="costasstperhr" size="30" maxlength="13" onchange="validatenum(this)"'.INC_TEXTBOX.'"></td></tr>';
     };
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Factory Overhead %:</td><td><input type="text" name="factoverhead" size="30" maxlength="13" onchange="validatenum(this)"'.INC_TEXTBOX.'"></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">General Overhead %:</td><td><input type="text" name="genoverhead" size="30" maxlength="13" onchange="validatenum(this)"'.INC_TEXTBOX.'"></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Markup %:</td><td><input type="text" name="markup" size="30" maxlength="13" onchange="validatenum(this)"'.INC_TEXTBOX.'"></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Order:</td><td><input type="text" name="orderflag" size="30" maxlength="5" onchange="validateint(this)"'.INC_TEXTBOX.'"></td></tr>';
     echo '</table><input type="submit" value="Add">';
     echo '<br><br><a href="adminestcostcenterupd.php">Select a new Cost Center</a>';
     echo '<br><br><a href="adminestcostcentersubtypeupd.php?costcenterid='.$costcenterid.'">Machine Family Selection</a>';
     echo '<br><br><a href="adminestmachineupd.php?costcenterid='.$costcenterid.'&classid='.$classid.'">Machine List Add/Update</a>';

     echo '</form>';
?>
<?php include('includes/footer.php'); ?>
