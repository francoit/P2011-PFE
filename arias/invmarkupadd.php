<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //invmarkupsetadd.php
     echo texttitle($lang['STR_STANDARD_MARKUP_SET_ADD']);
     echo '<center>';
     if ($id) { // have already saved main info, now need to save price level info
           for ($levelcounter=1;$levelcounter<=$counter;$levelcounter++) {
                 //first test to make sure not already in file
                 $recordSet=&$conn->Execute('select * from markupsetlevel where markupsetid='.sqlprep($id).' and pricelevelid='.sqlprep(${"levelid".$levelcounter}));
                if ($recordSet->EOF) {  // save now, no match found
                    if (${"price".$levelcounter}>0) {
                      checkpermissions('inv');
                      if ($conn->Execute("insert into markupsetlevel (pricelevelid,markupsetid,markuppercent) VALUES (".sqlprep(${"levelid".$levelcounter}).",".sqlprep($id).",".sqlprep(${"price".$levelcounter}).")") === false) {
                           echo texterror($lang['STR_ERROR_ADDING_MARKUP_SET_FOR_LEVEL']);
                      } else {
                           echo textsuccess($lang['STR_MARKUP_SET_FOR_LEVEL_ADDED_SUCCESSFULLY']);
                      };
                    };
                 };
           };
     } elseif ($description) { //if the user has submitted info
           $recordSet=&$conn->Execute('select * from markupset where description='.sqlprep($description));
           if (!$recordSet->EOF) die (texterror($lang['STR_STANDARD_MARKUP_SET_ALREADY_EXISTS_WITH_THAT_DESCRIPTION']));
           checkpermissions('inv');
           if ($conn->Execute("insert into markupset (description, costbased) VALUES (".sqlprep($description).",".sqlprep($costbased).")") === false) die (texterror($lang['STR_ERROR_ADDING_STANDARD_MARKUP_SET']));
           $recordSet=&$conn->Execute('select id from markupset where description='.sqlprep($description));
           if (!$recordSet->EOF) $id=$recordSet->fields[0];
           // now ask for price level info for this set
           $recordSet=&$conn->Execute("select description, id from pricelevel");
           $counter=0;
           while (!$recordSet->EOF) {
                   $counter++;
                   ${"pricelevel".$counter} = $recordSet->fields[0];
                   ${"levelid".$counter}=$recordSet->fields[1];
                   $recordSet->MoveNext();
           };
           echo '<form action="invmarkupadd.php" method="post"><input type="hidden" name="nonprintable" value="1">';
           echo '<table><tr><th colspan="2">'.$lang['STR_MARKUP_SET_DESCRIPTION'].': '.$description.'</th></tr>';
           echo '<tr><th>'.$lang['STR_PRICE_LEVEL'].'</th><th>'.$lang['STR_MARKUP'].'</th></tr>';
           echo '<input type="hidden" name="id" value="'.$id.'"><input type="hidden" name="description" value="'.$description.'"><input type="hidden" name="counter" value="'.$counter.'">';
           for ($levelcounter=1;$levelcounter<=$counter;$levelcounter++) {
                 echo '<td>'.${"pricelevel".$levelcounter}.'</td><td><input type="text" name="price'.$levelcounter.'" onchange="validatenum(this)" size="10" value="'.${"price".$levelcounter}.'" onKeyPress="return handleEnter(this, event)" onFocus="highlightField(this)" onBlur="normalField(this)"></td></tr>';
                 echo '<input type="hidden" name="levelid'.$levelcounter.'" value="'.${"levelid".$levelcounter}.'">';
           };
           echo '</table><input type="submit" value="'.$lang['STR_ADD'].'"></form>';

     } else {
       echo '<form action="invmarkupadd.php" method="post"><input type="hidden" name="nonprintable" value="1">';
       echo '<table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MARKUP_SET_DESCRIPTION'].':</td><td><input type="text" name="description" size="30"'.INC_TEXTBOX.'></td></td>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MARKUP_BASED_ON'].':</td><td><select name="costbased"'.INC_TEXTBOX.'><option value="1">'.$lang['STR_FIRST_COST'].' ';
              echo '<option value="2">'.$lang['STR_MID_COST'].' ';
              echo '<option value="3">'.$lang['STR_LAST_COST'].' ';
       echo '</select></td></tr>';
       echo '</table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
     };
       
       echo '</center>';
?>

<?php include('includes/footer.php'); ?>
