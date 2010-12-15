<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestquotecblstdsizeupd.php
     echo texttitle('Standard Size Update');
     if ($delete) { //delete size
             if ($conn->Execute('delete from estquotecblstdsize where gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id)) === false) {
                echo texterror("Error deleting standard size.");
             } else {
                echo textsuccess("Standard Size deleted successfully.");
                unset($id);  //return to first screen
             };
     };
     if ($width&&$length) { //update size
             if ($conn->Execute('update estquotecblstdsize set width='.sqlprep($width).', length='.sqlprep($length).',orderflag='.sqlprep($orderflag).' where id='.sqlprep($id)) === false) {
                echo texterror("Error updating standard size.");
             } else {
                echo textsuccess("Standard Size updated successfully.");
             };
     };
     if ($id) { // if the user has selected a size
          echo '<form action="adminestquotecblstdsizeupd.php" method="post" name="mainform"><input type="hidden" name="id" value="'.$id.'"><table>';
          $recordSet2 = &$conn->SelectLimit('select width,length,orderflag from estquotecblstdsize where gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id),1);
          if ($recordSet2->EOF) die(texterror('Size not found.'));
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Width/Length:</td><td><input type="text" name="width" size="5" maxlength="6" value="'.$recordSet2->fields[0].'"'.INC_TEXTBOX.'">x<input type="text" name="length" size="5" maxlength="6" value="'.$recordSet2->fields[1].'"'.INC_TEXTBOX.'"></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Priority:</td><td><input type="text" name="orderflag" size="5" maxlength="6" value="'.$recordSet2->fields[2].'"'.INC_TEXTBOX.'">';
          echo '</table><input type="submit" value="Save Changes"></form> <a href="javascript:confirmdelete(\'adminestquotecblstdsizeupd.php?delete=1&id='.$id.'\')">Delete this Standard Size</a>';
     } else { //show search
          $recordSet = &$conn->Execute('select id,width,length from estquotecblstdsize where gencompanyid='.sqlprep($active_company).' order by orderflag,width,length');
          if (!$recordSet->EOF) {
              while (!$recordSet->EOF) {
                 echo '<a href="adminestquotecblstdsizeupd.php?id='.$recordSet->fields[0].'">'.checkdec($recordSet->fields[1],2).'" x '.checkdec($recordSet->fields[2],2).'"</a><br>';
                 $recordSet->MoveNext();
              };
          };
          echo '<br><a href="adminestquotecblstdsizeadd.php">Add new Standard Size</a>';
     };
?>
<?php include('includes/footer.php'); ?>
