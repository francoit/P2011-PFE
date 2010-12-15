<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestquotecblinkadd.php
     echo texttitle('Ink Add');
     if ($name) { //if the user has submitted info
//             checkpermissions('est');
             if ($type==SD_INK_PMS) {
               $recordSet=&$conn->Execute('select name from estquotecblink where gencompanyid='.sqlprep($active_company).' and type='.sqlprep(SD_INK_PMS));
               if (!$recordSet->EOF) {
                   echo texterror("Cannot enter TWO inks as type = PMS INK");
                   die();
               };
             };
             if ($conn->Execute('insert into estquotecblink (name,jobprice,mprice,type,gencompanyid) VALUES ('.sqlprep($name).', '.sqlprep($jobprice).', '.sqlprep($mprice).', '.sqlprep($type).', '.sqlprep($active_company).')') === false) {
                echo texterror("Error adding ink.");
             } else {
                echo textsuccess("Ink added successfully.");
             };
             die();
     };
     echo '<form action="adminestquotecblinkadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name:</td><td><input type="text" name="name" size="30" maxlength="30"'.INC_TEXTBOX.'"></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Price / Job:</td><td><input type="text" name="jobprice" size="30" maxlength="9"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Price / M:</td><td><input type="text" name="mprice" size="30" maxlength="11"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Type:</td><td><select name="type"'.INC_TEXTBOX.'>';
     echo '<option value="'.SD_INK_PMS.'">Custom PMS';
     echo '<option value="'.SD_INK_COLOR.'">Std.Color';
     echo '<option value="'.SD_INK_BLACK.'">Black';
     echo '</select></td></tr>';
     echo '</table><input type="submit" value="Add"></form>';
?>
<?php include('includes/footer.php'); ?>
