<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestquotequotenotesupd.php
     echo texttitle('Work Type Update');
     if ($delete) { //delete work type
             if ($conn->Execute('delete from estquotequotenotes where id='.sqlprep($id)) === false) {
                echo texterror("Error deleting quote notes.");
             } else {
                echo textsuccess("Quote Notes deleted successfully.");
                unset($id);  //return to first screen
             };
     };
     if ($quotebold) { //update work type
             if ($conn->Execute('update estquotequotenotes set quotebold='.sqlprep($quotebold).', quotetext='.sqlprep($quotetext).', showwhen='.sqlprep($showwhen).', orderflag='.sqlprep($orderflag).', lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($id)) === false) {
                echo texterror("Error updating Quote Notes.");
             } else {
                echo textsuccess("Quote Notes updated successfully.");
             };
             unset($id);
     };
     if ($id) { // if the user has selected a   quote note
          echo '<form action="adminestquotequotenotesupd.php" method="post" name="mainform"><input type="hidden" name="id" value="'.$id.'"><table>';
          $recordSet2 = &$conn->Execute('select quotebold,quotetext,showwhen,orderflag from estquotequotenotes where gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id));
          if ($recordSet2->EOF) die(texterror('Quote Note not found.'));
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Bold Intro Portion of Note:</td><td><input type="text" name="quotebold" size="30" maxlength="50" value="'.$recordSet2->fields[0].'" '.INC_TEXTBOX.'"></td></tr>';
          $noteval=$recordSet2->fields[1];
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Text Portion of Note:</td><td><textarea name="quotetext" rows=5 cols=40>'.$noteval.'</textarea></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Show on quote when:</td><td><select name="showwhen" '.INC_TEXTBOX.'>';
          $op=$recordSet2->fields[2];
                 echo '<option value="0"'.checkequal($op,"0",' selected').'>Always Show';
                 echo '<option value="1"'.checkequal($op,"1",' selected').'>One Ink Only';
                 echo '<option value="2">'.checkequal($op,"2",' selected').'More than One Ink';
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Display Order:</td><td><input type="text" name="orderflag" size="6" maxlength="6" value="'.$recordSet2->fields[3].'" '.INC_TEXTBOX.'"></td></tr>';
     } else { //show search
          $recordSet = &$conn->Execute('select id,quotebold from estquotequotenotes where gencompanyid='.sqlprep($active_company).'  order by orderflag,quotebold');
          if (!$recordSet->EOF) {
              echo '<form action="adminestquotequotenotesupd.php" method="post" name="mainform"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Quote Note:</td><td><select name="id"'.INC_TEXTBOX.'>';
              while (!$recordSet->EOF) {
                 echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                 $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
              echo '</table><input type="submit" name="submit" value="Select"></form>';
          };
          echo '<a href="adminestquotequotenotesadd.php">Add new Quote Note</a>';
     };
?>
<?php include('includes/footer.php'); ?>
