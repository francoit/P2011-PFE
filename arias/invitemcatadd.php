<?php include('includes/main.php');?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //invitemcatadd.php
     echo texttitle($lang['STR_ITEM_CATEGORY_ADD']);
     echo '<center>';
     if ($name) { //if the user has submitted info
                  if (!$seasonbegin1) {
                         $seasonname1="all";
                         $seasonbegin1=1;
                         $seasonend1=12;
                         $seasonname2="";
                         $seasonbegin2=0;
                         $seasonend2=0;
                         $seasonname3="";
                         $seasonbegin3=0;
                         $seasonend3=0;
                         $seasonname4="";
                         $seasonbegin4=0;
                         $seasonend4=0;
                  };
                  $recordSet=&$conn->Execute('select * from itemcategory where name='.sqlprep($name));
                  if (!$recordSet) die (texterror($lang['STR_CATEGORY_ALREADY_EXISTS_BY_THAT_NAME']));
                  checkpermissions('inv');
                  if ($conn->Execute("insert into itemcategory (name,seasonname1,seasonname2,seasonname3,seasonname4,seasonbegin1,seasonbegin2,seasonbegin3,seasonbegin4,seasonend1,seasonend2,seasonend3,seasonend4) VALUES (".sqlprep($name).",".sqlprep($seasonname1).",".sqlprep($seasonname2).",".sqlprep($seasonname3).",".sqlprep($seasonname4).",".sqlprep($seasonbegin1).",".sqlprep($seasonbegin2).",".sqlprep($seasonbegin3).",".sqlprep($seasonbegin4).",".sqlprep($seasonend1).",".sqlprep($seasonend2).",".sqlprep($seasonend3).",".sqlprep($seasonend4).")") === false) {
                     echo texterror($lang['STR_ERROR_ADDING_CATEGORY']);
                  } else {
                     echo '<center>'.$lang['STR_ITEM_CATEGORY_ADDED_SUCCESSFULLY'].'</center><br>';
                  };
     };
     echo '<form action="invitemcatadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table><tr><td>Item Category:</td><td><input type="text" name="name" size="30"></td></tr>';
     echo '<tr><th>'.$lang['STR_SEASON_NAME'].'</th><th>'.$lang['STR_BEGIN_MONTH'].'</th><th>'.$lang['STR_END_MONTH'].'</th></tr>';
     echo '<tr><td><input type="text" name="seasonname1" maxlength="20"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonbegin1" default="1"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonend1" default="12"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td><input type="text" name="seasonname2" maxlength="20"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonbegin2"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonend2"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td><input type="text" name="seasonname3" maxlength="20"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonbegin3"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonend3"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td><input type="text" name="seasonname4" maxlength="20"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonbegin4"'.INC_TEXTBOX.'></td><td><input type="text" name="seasonend4"'.INC_TEXTBOX.'></td></tr>';
     echo '</table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
     
     echo '</center>';
?>

<?php include('includes/footer.php'); ?>
