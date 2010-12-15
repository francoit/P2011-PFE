<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>


<?php //adminestcostcenteradd.php
     echo texttitle($lang['STR_COST_CENTER_ADD']);
     echo '<center>';
     if ($name) { //if the user has submitted info
//             checkpermissions('l3');
             if ($conn->Execute('insert into estcostcenter (name,cctype,orderflag) VALUES ('.sqlprep($name).', '.sqlprep($cctype).', '.sqlprep($orderflag).')') === false) {
                echo texterror($lang['STR_ERROR_ADDING_COST_CENTER']);
             } else {
                echo textsuccess($lang['STR_COST_CENTER_ADDED_SUCCESSFULLY']);
             };
     };
     echo '<form action="adminestcostcenteradd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="30"'.INC_TEXTBOX.'"></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COST_CENTER_TYPE'].':</td><td><select name="cctype"'.INC_TEXTBOX.'"><option value="'.SD_CCTYPE_PREFLIGHT.'">Pre-Flight<option value="'.SD_CCTYPE_PROOF.'">Proof<option value="'.SD_CCTYPE_PREPRESS.'">Prepress<option value="'.SD_CCTYPE_INK.'">Ink<option value="'.SD_CCTYPE_PAPER.'">Paper<option value="'.SD_CCTYPE_PRINT.'">Print<option value="'.SD_CCTYPE_FINISH.'">Finish<option value="'.SD_CCTYPE_QC.'">QC<option value="'.SD_CCTYPE_SHIP.'">Ship</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SORT_ORDER'].':</td><td><input type="text" name="orderflag" size="30" maxlength="5" onchange="validateint(this)"'.INC_TEXTBOX.'"></td></tr>';
     echo '</table><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
     echo '</center>';
?>

<?php include('includes/footer.php'); ?>
