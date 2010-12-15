<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestquotestockcolor.php
     echo texttitle('Stock Color List');
     $recordSet=&$conn->Execute('select estquotegenstock.name,estquotesubstock.name from estquotegenstock,estquotesubstock where estquotegenstock.id='.sqlprep($genstockid).' and estquotesubstock.estquotegenstockid='.sqlprep($genstockid).' and estquotesubstock.id='.sqlprep($substockid));
     if (!$recordSet->EOF) {
           echo texttitle ('Stock: '.$recordSet->fields[0].' - '.$recordSet->fields[1]);
           $name=$recordSet->fields[0];
     } else {
           die(texterror("Cannot Find Work Type"));
     };
     if ($save) { //update files
          for ($i=1;$i<=$sel;$i++) {
             if (${"id".$i}) { //had an entry before
               if (${"color".$i}) {//update file
                  if ($conn->Execute('update estquotesubstockcolors set color='.sqlprep(${"color".$i}).' where id='.sqlprep(${"id".$i})) === false) echo texterror("Error updating color.");
               } else { //delete entry
                  if ($conn->Execute('delete from estquotesubstockcolors where id='.sqlprep(${"id".$i})) === false) echo texterror("Error deleting color.");
               };
             } elseif (${"color".$i}) {//added a new entry
                if ($conn->Execute('insert into estquotesubstockcolors (substockid,color) values ('.sqlprep($substockid).', '.sqlprep(${"color".$i}).')') === false) echo texterror("Error adding color.");;
             };

          };
          unset($save);
     };
     echo '<form action="adminestquotestockcolor.php" method="post" name="mainform">';
     echo '<input type="hidden" name="substockid" value="'.$substockid.'">';
     echo '<input type="hidden" name="genstockid" value="'.$genstockid.'">';
     echo '<table>';
     echo '<tr><th>Color</th></tr>';
     $sel=1;
     $recordSet=&$conn->Execute('select id,color from estquotesubstockcolors where substockid='.sqlprep($substockid).' order by color');
     while (!$recordSet->EOF) {
          ${"color".$sel}=$recordSet->fields[1];
          ${"id".$sel}=$recordSet->fields[0];
          echo '<input type="hidden" name="id'.$sel.'" value="'.${"id".$sel}.'">';
          echo '<tr><td><input type="text" name="color'.$sel.'" size="20" maxlength="50" value="'.${"color".$sel}.'" '.INC_TEXTBOX.'></td></tr>';
          $sel++;
          $recordSet->MoveNext();
     };
     for ($i=0;$i<2;$i++) {
          echo '<tr><td><input type="text" name="color'.$sel.'" size="20" maxlength="50" '.INC_TEXTBOX.'></td></tr>';
          $sel++;
     };
     echo '<input type="hidden" name="sel" value="'.(--$sel).'">';
     echo '</table>';
     echo '<input type="submit" name="save" value="Save Changes"><br><br><br>';
     echo '<a href="estquotestockupd.php?substockid='.$substockid.'&&genstockid='.$genstockid.'&&updated=2&&name='.$name.'">Return to Sub Stock Edit</a><br>';

     echo '</form>';
?>
<?php include('includes/footer.php'); ?>
