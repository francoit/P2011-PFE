<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //invleveladd.php
     echo texttitle($lang['STR_PRICE_LEVEL_ADD']);
     echo '<center>';
     if ($description) { //if the user has submitted info
                  $recordSet=&$conn->Execute('select * from pricelevel where description='.sqlprep($description).' and (companyid=0 or companyid='.sqlprep($active_company).')');
                  if (!$recordSet->EOF) die (texterror($lang['STR_PRICE_LEVEL_ALREADY_EXISTS_WITH_THAT_DESCRIPTION']));
                  checkpermissions('inv');
                  if ($conn->Execute("insert into pricelevel (description,companyid) VALUES (".sqlprep($description).",".sqlprep($active_company).")") === false) {
                     echo texterror($lang['STR_ERROR_ADDING_PRICE_LEVEL']);
                  } else {
                     echo textsuccess($lang['STR_PRICE_LEVEL_ADDED_SUCCESSFULLY']);
                  };
     };
     echo '<form action="invleveladd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table><tr><td>Price Level Description:</td><td><input type="text" name="description" size="30"></td></tr>';
     echo '</table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
     
     echo '</center>';
?>

<?php include('includes/footer.php'); ?>
