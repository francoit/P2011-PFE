<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestquotegenink.php - copyright 2001 by Noguska - Fostoria, OH
     echo texttitle('General Ink Info');
     if ($save) {
         if ($id) { //if update
          if ($conn->Execute('update estquotegenink set maxcolors='.sqlprep($maxcolors).', regcharge='.sqlprep($regcharge).', namecov1='.sqlprep($namecov1).', namecov2='.sqlprep($namecov2).', namecov3='.sqlprep($namecov3).', namecov4='.sqlprep($namecov4).', namecov5='.sqlprep($namecov5).', namecov6='.sqlprep($namecov6).', namecov7='.sqlprep($namecov7).', namecov8='.sqlprep($namecov8).', namecov9='.sqlprep($namecov9).', covpct1='.sqlprep($covpct1).',covpct2='.sqlprep($covpct2).',covpct3='.sqlprep($covpct3).',covpct4='.sqlprep($covpct4).',covpct5='.sqlprep($covpct5).',covpct6='.sqlprep($covpct6).',covpct7='.sqlprep($covpct7).',covpct8='.sqlprep($covpct8).',covpct9='.sqlprep($covpct9).' where gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id)) === false) {
             echo texterror("Error updating ink info.");
          } else {
             echo textsuccess("Ink Info updated successfully.");
          };
         } else { //if insert
          if ($conn->Execute('insert into estquotegenink (maxcolors,regcharge,namecov1,namecov2,namecov3,namecov4,namecov5,namecov6,namecov7,namecov8,namecov9,covpct1,covpct2,covpct3,covpct4,covpct5,covpct6,covpct7,covpct8,covpct9,gencompanyid) values ('.sqlprep($maxcolors).', '.sqlprep($regcharge).','.sqlprep($namecov1).','.sqlprep($namecov2).','.sqlprep($namecov3).','.sqlprep($namecov4).','.sqlprep($namecov5).','.sqlprep($namecov6).','.sqlprep($namecov7).','.sqlprep($namecov8).','.sqlprep($namecov9).','.sqlprep($covpct1).', '.sqlprep($covpct2).','.sqlprep($covpct3).','.sqlprep($covpct4).','.sqlprep($covpct5).','.sqlprep($covpct6).','.sqlprep($covpct7).','.sqlprep($covpct8).','.sqlprep($covpct9).','.sqlprep($active_company).')') === false) {
             echo texterror("Error adding ink info.");
          } else {
             echo textsuccess("Ink Info added successfully.");
          };
         };
         die();
     };
       $recordSet=&$conn->SelectLimit('select id,maxcolors,regcharge,namecov1,namecov2,namecov3,namecov4,namecov5,namecov6,namecov7,namecov8,namecov9,covpct1,covpct2,covpct3,covpct4,covpct5,covpct6,covpct7,covpct8,covpct9 from estquotegenink where gencompanyid='.sqlprep($active_company),1);
       if (!$recordSet->EOF) {
          $id=$recordSet->fields[0];
          $maxcolors=$recordSet->fields[1];
          $regcharge=$recordSet->fields[2];
          for ($i=1;$i<10;$i++) {
              ${"namecov".$i}=$recordSet->fields[2+$i];
              ${"covpct".$i}=$recordSet->fields[11+$i];
          };
       };
       echo '<form action="adminestquotegenink.php" method="post" name="mainform"><table>';
       echo '<input type="hidden" name="id" value="'.$id.'">';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Max # Colors:</td><td><input type="text" name="maxcolors" size="30" maxlength="5" value="'.$maxcolors.'"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Charge for Tight Reqistration:</td><td><input type="text" name="regcharge" size="30" maxlength="7" value="'.$regcharge.'"'.INC_TEXTBOX.'></td></tr>';
       echo '</table>';
       echo '<table border="2">';
       echo '<tr><th>Ink Coverage Name</th><th>Percentage Coverage</th></tr>';
       for ($i=1;$i<10;$i++):
           echo '<tr><td><input type="text" name="namecov'.$i.'" value="'.${"namecov".$i}.'" size="30" maxlength="30" '.INC_TEXTBOX.'></td>';
           echo '<td><input type="text" name="covpct'.$i.'" value="'.${"covpct".$i}.'" size="3" maxlength="3" '.INC_TEXTBOX.'></td></tr>';
       endfor;
       echo '</table><input type="submit" name="save" value="Save Changes"></form>';

?>
<?php include('includes/footer.php'); ?>
