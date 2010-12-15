<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestquotecblinkadd.php
     echo texttitle('Standard Size Add');
     if ($width&&$length) { //if the user has submitted info
//             checkpermissions('est');
             if ($conn->Execute('insert into estquotecblstdsize (width,length,orderflag,gencompanyid) VALUES ('.sqlprep($width).', '.sqlprep($length).','.sqlprep($sortorder).', '.sqlprep($active_company).')') === false) {
                echo texterror("Error adding standard size.");
             } else {
                echo textsuccess("Standard Size added successfully.");
             };
     };
     echo '<form action="adminestquotecblstdsizeadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Width/Length:</td><td><input type="text" name="width" size="5" maxlength="6"'.INC_TEXTBOX.'">x<input type="text" name="length" size="5" maxlength="6"'.INC_TEXTBOX.'"></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Priority:</td><td><input type="text" name="orderflag" size="5" maxlength="6"'.INC_TEXTBOX.'"></td></tr>';
     echo '</table><input type="submit" value="Add"></form>';
?>
<?php include('includes/footer.php'); ?>
