<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //adminestquoteworktypeadd.php
     $recordSet = &$conn->Execute('select id,name from estquoteworktypegen where id='.sqlprep($genworktypeid));
     if (!$recordSet->EOF) {
       $displayname=$recordSet->fields[1];
     } else {
       echo texterror("General Work Type not found");
     };
     echo texttitle('Detail Work Type Add for General Work Type='.$displayname);
     if ($name) { //if the user has submitted info
//             checkpermissions('est');
             if ($conn->Execute('insert into estquoteworktype (name,turnaroundqty,turnarounddaysuptoqty,turnarounddaysoverqty,notes,gencompanyid,orderflag,genworktypeid,entrydate,entryuserid,lastchangeuserid) VALUES ('.sqlprep($name).', '.sqlprep($turnaroundqty).', '.sqlprep($turnarounddaysuptoqty).', '.sqlprep($turnarounddaysoverqty).','.sqlprep($notes).', '.sqlprep($active_company).', '.sqlprep($orderflag).','.sqlprep($genworktypeid).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).')') === false) {
                echo texterror("Error adding detail work type.");
             } else {
                echo textsuccess("Detail Work Type added successfully.");
             };
     };
     echo '<form action="adminestquoteworktypeadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
     echo '<input type="hidden" name="genworktypeid" value="'.$genworktypeid.'">';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name:</td><td><input type="text" name="name" size="30" maxlength="30"'.INC_TEXTBOX.'"></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Turnaround days up to quantity <input type="text" name="turnaroundqty" size="10" maxlength="8"'.INC_TEXTBOX.'>:</td><td><input type="text" name="turnarounddaysuptoqty" size="6" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Turnaround days over quantity:</td><td><input type="text" name="turnarounddaysoverqty" size="6" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Order for Display:</td><td><input type="text" name="orderflag" size="30" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Notes for Quote:</td><td><textarea name="notes" rows="5" cols="40"></textarea></td></tr>';
     echo '</table><input type="submit" value="Add"></form>';
     echo '<a href="adminestquoteworktypeupd.php?genworktypeid='.$genworktypeid.'">Return to Detail Selection</a>';

?>
<?php include('includes/footer.php'); ?>
