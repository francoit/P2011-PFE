<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestquotecblworktypeadd.php
     echo texttitle('Work Type Add');
     if ($name) { //if the user has submitted info
//             checkpermissions('est');
             if ($conn->Execute('insert into estquotecblworktype (name,turnaroundqty,turnarounddaysuptoqty,turnarounddaysoverqty,notes,gencompanyid,entrydate,entryuserid,lastchangeuserid) VALUES ('.sqlprep($name).', '.sqlprep($turnaroundqty).', '.sqlprep($turnarounddaysuptoqty).', '.sqlprep($turnarounddaysoverqty).','.sqlprep($notes).', '.sqlprep($active_company).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).')') === false) {
                echo texterror("Error adding work type.");
             } else {
                echo textsuccess("Work Type added successfully.");
             };
     };
     echo '<form action="adminestquotecblworktypeadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name:</td><td><input type="text" name="name" size="30" maxlength="30"'.INC_TEXTBOX.'"></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Turnaround days up to quantity <input type="text" name="turnaroundqty" size="6" maxlength="10"'.INC_TEXTBOX.'>:</td><td><input type="text" name="turnarounddaysuptoqty" size="30" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Turnaround days over quantity:</td><td><input type="text" name="turnarounddaysoverqty" size="30" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Notes for Quote:</td><td><textarea name="notes" rows="5" cols="40"></textarea></td></tr>';
     echo '</table><input type="submit" value="Add"></form>';
?>
<?php include('includes/footer.php'); ?>
