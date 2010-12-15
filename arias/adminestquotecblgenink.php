<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestquotecblgenink.php - copyright 2001 by Noguska - Fostoria, OH
     echo texttitle('General Ink Info');
     if ($save) {
         if ($id) { //if update
          if ($conn->Execute('update estquotecblgenink set maxcolors='.sqlprep($maxcolors).', regcharge='.sqlprep($regcharge).' where gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id)) === false) {
             echo texterror("Error updating ink info.");
          } else {
             echo textsuccess("Ink Info updated successfully.");
          };
         } else { //if insert
          if ($conn->Execute('insert into estquotecblgenink (maxcolors,regcharge,gencompanyid) values ('.sqlprep($maxcolors).', '.sqlprep($regcharge).', '.sqlprep($active_company).')') === false) {
             echo texterror("Error adding ink info.");
          } else {
             echo textsuccess("Ink Info added successfully.");
          };
         };
     };
       $recordSet=&$conn->SelectLimit('select id,maxcolors,regcharge from estquotecblgenink where gencompanyid='.sqlprep($active_company),1);
       if (!$recordSet->EOF) {
          $id=$recordSet->fields[0];
          $maxcolors=$recordSet->fields[1];
          $regcharge=$recordSet->fields[2];
          $recordSet->MoveNext();
       };
       echo '<form action="adminestquotecblgenink.php" method="post" name="mainform"><table>';
       echo '<input type="hidden" name="id" value="'.$id.'">';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Max # Colors:</td><td><input type="text" name="maxcolors" size="30" maxlength="5" value="'.$maxcolors.'"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Charge for Tight Reqistration:</td><td><input type="text" name="regcharge" size="30" maxlength="7" value="'.$regcharge.'"'.INC_TEXTBOX.'></td></tr>';
       echo '</table><input type="submit" name="save" value="Save Changes"></form>';

?>
<?php include('includes/footer.php'); ?>
