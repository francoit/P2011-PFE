<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center>';
     echo texttitle('Employee Rating Add');
     if ($description) { //if the user has submitted info
                  $recordSet=&$conn->Execute('select * from premplreviewrating where description='.sqlprep($description));
                  if (!$recordSet) die (texterror($lang['STR_RATING_ALREADY_EXISTS_BY_THAT_NAME']));
                  checkpermissions('pay');
                  if ($conn->Execute("insert into premplreviewrating (description) VALUES (".sqlprep($description).")") === false) {
                     echo texterror($lang['STR_ERROR_ADDING_REVIEW_RATING']);
                  } else {
                     echo textsuccess($lang['STR_REVIEW_RATING_ADDED_SUCCESSFULLY']);
                  };
     };
     echo '<form action="adminpremplreviewratingadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table><tr><td>Description: </td><td><input type="text" name="description" size="30"></td></tr>';
      echo '</table><br><input type="submit" value="Add"></form>';
      echo '<br><br><a href="javascript:doNothing()" onclick="top.newWin = window.open (\'helpadminpremplreviewratingadd.php?name='.$name.'\',\'cal\',\'dependent=yes,width=420,height=460,screenX=200,screenY=300,titlebar=yes,scrollbars=1,resizable=1\')"><img src="images/opera.jpg" width="40" border="0" alt="Employee Rating Add Help"></a>';
     echo '</center>';
?>
<?php include('includes/footer.php'); ?>
