<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>


<?php //adminestcostcentersubtypeadd.php
     if ($costcenterid) {
         $recordSet = &$conn->Execute('select name from estcostcenter where cancel=0 and id='.sqlprep($costcenterid));
     } else {
         echo texterror($lang['STR_NO_COST_CENTER_SELECTED']);
         die();
     };
     echo texttitle($recordSet->fields[0]. $lang['STR_COST_CENTER_MACHINE_FAMILY_ADD']);
     if ($name) { //if the user has submitted info
//             checkpermissions('l3');
             if ($conn->Execute('insert into estcostcentersubtype (name,costcenterid,orderflag) VALUES ('.sqlprep($name).', '.sqlprep($costcenterid).', '.sqlprep($orderflag).')') === false) {
                echo texterror($lang['STR_ERROR_ADDING_COST_CENTER_FAMILY']);
             } else {
                echo textsuccess($lang['STR_COST_CENTER_FAMILY_ADDED_SUCCESSFULLY']);
             };
     };
     echo '<form action="adminestcostcentersubtypeadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="30"'.INC_TEXTBOX.'"></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SORT_ORDER'].':</td><td><input type="text" name="orderflag" size="30" maxlength="5" onchange="validateint(this)"'.INC_TEXTBOX.'"></td></tr>';
     echo '</table><input type="submit" value="'.$lang['STR_ADD'].'">';
     echo '<input type="hidden" name="costcenterid" value="'.$costcenterid.'">';
     echo '<br><br><a href="adminestcostcenterupd.php">'.$lang['STR_SELECT_A_NEW_COST_CENTER'].'</a>';
     echo '<br><br><a href="adminestcostcentersubtypeupd.php?costcenterid='.$costcenterid.'">'.$lang['STR_MACHINE_FAMILY_SELECTION'].'</a>';

     echo '</form>';

?>

<?php include('includes/footer.php'); ?>
