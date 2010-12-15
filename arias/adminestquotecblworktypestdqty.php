<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //adminestquotecblworktypestdqty.php
     echo texttitle('Work Type Standard Size List');

     $recordSet=&$conn->Execute('select name from estquotecblworktype where id='.sqlprep($worktypeid));
     if (!$recordSet->EOF) {

           echo texttitle ('Work Type: '.$recordSet->fields[0]);
     } else {
           die (texterror("Cannot Find Work Type"));
     };
     if ($save) { //update files
          for ($i=1;$i<=$sel;$i++) {
             if (${"id".$i}>0) { //had an entry before
               if (${"qty".$i}>0) {//update file
                  if ($conn->Execute('update estquotecblworktypestdqty set quantity='.sqlprep(${"qty".$i}).' where id='.sqlprep(${"id".$i})) === false) {
                     echo texterror("Error updating standard quantity.");
                  };
               } else { //delete entry
                  if ($conn->Execute('delete from estquotecblworktypestdqty where id='.sqlprep(${"id".$i})) === false) {
                     echo texterror("Error deleting standard quantity.");
                  };
               };
             } elseif (${"qty".$i}>0) {//added a new entry
                $conn->Execute('insert into estquotecblworktypestdqty (worktypeid,quantity) values ('.sqlprep($worktypeid).', '.sqlprep(${"qty".$i}).')');
             };

          };
          unset($save);
     };
     echo '<form action="adminestquotecblworktypestdqty.php" method="post" name="mainform">';
     echo '<input type="hidden" name="worktypeid" value="'.$worktypeid.'"><table>';
     $recordSet=&$conn->Execute('select id,quantity from estquotecblworktypestdqty where worktypeid='.sqlprep($worktypeid).' order by quantity');
     echo '<tr><th>Quantity</th></tr>';
     $sel=0;
     while (!$recordSet->EOF) {
          $sel++;
          ${"qty".$sel}=$recordSet->fields[1];
          ${"id".$sel}=$recordSet->fields[0];
          echo '<input type="hidden" name="id'.$sel.'" value="'.$recordSet->fields[0].'">';
          echo '<tr><td><input type="text" name="qty'.$sel.'" size="10" maxlength="10" value="'.$recordSet->fields[1].'"'.INC_TEXTBOX.'"></td></tr>';
          $recordSet->MoveNext();
     };
     for ($i=0;$i<3;$i++) {
          $sel++;
          echo '<input type="hidden" name="id'.$sel.'" value="0">';
          echo '<tr><td><input type="text" name="qty'.$sel.'" size="10" maxlength="10" '.INC_TEXTBOX.'"></td></tr>';
     };
     echo '<input type="hidden" name="sel" value="'.$sel.'">';
     echo '</table>';
     echo '<input type="submit" name="save" value="Save Changes"></form>';
?>
<?php include('includes/footer.php'); ?>
