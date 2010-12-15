<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestquoteinkadd.php
     echo texttitle('Ink Add');
     if ($name) { //if the user has submitted info
//             checkpermissions('est');
             if ($type==SD_INK_PMS) {
               $recordSet=&$conn->Execute('select name from estquoteink where gencompanyid='.sqlprep($active_company).' and type='.sqlprep(SD_INK_PMS));
               if ($recordSet&&!$recordSet->EOF) die(texterror("Cannot enter TWO inks as type = PMS INK"));
             };
             if ($conn->Execute('insert into estquoteink (name,jobprice,mprice,type,costper,coverage,costbased,gencompanyid) VALUES ('.sqlprep($name).', '.sqlprep($jobprice).', '.sqlprep($mprice).', '.sqlprep($type).','.sqlprep($costper).','.sqlprep($coverage).','.sqlprep($costbased).', '.sqlprep($active_company).')') === false) {
                echo texterror("Error adding ink.");
             } else {
                echo textsuccess("Ink added successfully.");
             };
             die();
     } else {
     echo '<form action="adminestquoteinkadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name:</td><td><input type="text" name="name" size="30" maxlength="30"'.INC_TEXTBOX.'"></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Cost based on:</td><td><select name="costbased"'.INC_TEXTBOX.'><option value="'.SD_COST_GALLONS.'">Gallon<option value="'.SD_COST_LBS.'">Lb</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Cost per unit:</td><td><input type="text" name="costper" size="30" maxlength="9" onchange="validatenum(this)"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sq. In. Coverage per unit:</td><td><input type="text" name="coverage" size="30" maxlength="9" onchange="validateint(this)"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Price / Job:</td><td><input type="text" name="jobprice" size="30" maxlength="9"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Price / M:</td><td><input type="text" name="mprice" size="30" maxlength="11"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Type:</td><td><select name="type"'.INC_TEXTBOX.'>';
     echo '<option value="'.SD_INK_PMS.'">Custom PMS';
     echo '<option value="'.SD_INK_COLOR.'">Std.Color';
     echo '<option value="'.SD_INK_BLACK.'">Black';
     echo '</select></td></tr>';
     echo '</table><input type="submit" value="Add"></form>';
     };
?>
<?php include('includes/footer.php'); ?>
