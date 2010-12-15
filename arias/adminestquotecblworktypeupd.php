<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestquotecblworktypeupd.php
     echo texttitle('Work Type Update');
     if ($delete) { //delete work type
             if ($conn->Execute('update estquotecblworktype set cancel=1, canceldate=NOW(), canceluserid='.sqlprep($userid).' where id='.sqlprep($id)) === false) {
                echo texterror("Error deleting work type.");
             } else {
                echo textsuccess("Work Type deleted successfully.");
                unset($id);  //return to first screen
             };
     };
     if ($name) { //update work type
             if ($conn->Execute('update estquotecblworktype set name='.sqlprep($name).', turnaroundqty='.sqlprep($turnaroundqty).', turnarounddaysuptoqty='.sqlprep($turnarounddaysuptoqty).', turnarounddaysoverqty='.sqlprep($turnarounddaysoverqty).',notes='.sqlprep($notes).', lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($id)) === false) {
                echo texterror("Error updating Work Type.");
             } else {
                echo textsuccess("Work Type updated successfully.");
             };
     };
     if ($id) { // if the user has selected a work type
          echo '<form action="adminestquotecblworktypeupd.php" method="post" name="mainform"><input type="hidden" name="id" value="'.$id.'"><table>';
          $recordSet2 = &$conn->SelectLimit('select name,turnaroundqty,turnarounddaysuptoqty,turnarounddaysoverqty,notes from estquotecblworktype where gencompanyid='.sqlprep($active_company).' and cancel=0 and id='.sqlprep($id),1);
          if ($recordSet2->EOF) die(texterror('Work Type not found.'));
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name:</td><td><input type="text" name="name" size="30" maxlength="30" value="'.$recordSet2->fields[0].'"'.INC_TEXTBOX.'"></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Turnaround days up to quantity <input type="text" name="turnaroundqty" size="6" maxlength="10" value="'.$recordSet2->fields[1].'"'.INC_TEXTBOX.'>:</td><td><input type="text" name="turnarounddaysuptoqty" size="30" maxlength="4" value="'.$recordSet2->fields[2].'"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Turnaround days over quantity:</td><td><input type="text" name="turnarounddaysoverqty" size="30" maxlength="4" value="'.$recordSet2->fields[3].'"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Notes for Quote:</td><td><textarea name="notes" rows="5" cols="40">'.$recordSet2->fields[4].'</textarea></td></tr>';

          echo '</table><br>';
          $recordSet = &$conn->Execute('select id,question,validreply,minreply,maxreply,askonlyifmtblackink,askonlyifmtoneink,askonlyifmtoneside,askonlyminsizelength,askonlyminsizewidth,askonlymaxsizelength,askonlymaxsizewidth,calculatehow,amount from estquotecblworktypeaddl where worktypeid='.sqlprep($id).' and cancel=0');
          while (!$recordSet->EOF) {
              if ($recordSet->fields[5]) $wherestr.=' and if using more than black ink';
              if ($recordSet->fields[6]) $wherestr.=' and if using more than one ink';
              if ($recordSet->fields[7]) $wherestr.=' and if using more than one side';
              if ($recordSet->fields[8]>0&&$recordSet->fields[9]>0) $wherestr.=' and if larger than '.checkdec($recordSet->fields[8],2).'" x '.checkdec($recordSet->fields[9],2).'"';
              if ($recordSet->fields[10]>0&&$recordSet->fields[11]>0) $wherestr.=' and if smaller than '.checkdec($recordSet->fields[10],2).'" x '.checkdec($recordSet->fields[11],2).'"';
              if (strlen($wherestr)>5) $wherestr=substr($wherestr,5); //remove and from str
              switch ($recordSet->fields[2]) { //validreply
                  case SD_YES_NO:
                      $ansstr=' where answer is Yes or No';
                      break;
                  case SD_NUM_BETWEEN:
                      $ansstr=' where answer is a number between '.checkdec($recordSet->fields[3],0).' and '.checkdec($recordSet->fields[4],0);
                      break;
                  case SD_TEXT:
                      $ansstr=' where answer is text';
                      break;
              };
              switch ($recordSet->fields[12]) { //calculate how
                  case SD_PER_JOB:
                      $calcstr='.  Add $'.num_format($recordSet->fields[13],2).' to job';
                      break;
                  case SD_PER_M:
                      $calcstr='.  Add $'.num_format($recordSet->fields[13],2).' per M to job';
                      break;
                  case SD_EXTRA_TIMES:
                      $calcstr='.  Add $'.num_format($recordSet->fields[13],2).' X Answer to job';
                      break;
              };
              echo 'Ask Question: <a href="adminestquotecblworktypeaddl.php?id='.$recordSet->fields[0].'&worktypeid='.$id.'">'.$recordSet->fields[1].'</a> '.$wherestr.$ansstr.$calcstr.'<br>';
              $recordSet->MoveNext();
              unset($wherestr);
              unset($ansstr);
              unset($calcstr);
          };
          echo '<a href="adminestquotecblworktypeaddl.php?worktypeid='.$id.'">Add new Question</a><br><br>';
          echo '<input type="submit" value="Save Changes"></form>';
          echo '<a href="javascript:confirmdelete(\'adminestquotecblworktypeupd.php?delete=1&id='.$id.'\')">Delete this Work Type</a><br>';
          echo '<br><br><a href="adminestquotecblworktypestdqty.php?worktypeid='.$id.'">Create Standard Quantity List for Price Lists</a><br><br>';
     } else { //show search
          $recordSet = &$conn->Execute('select id,name from estquotecblworktype where gencompanyid='.sqlprep($active_company).' and cancel=0 order by name');
          if (!$recordSet->EOF) {
              echo '<form action="adminestquotecblworktypeupd.php" method="post" name="mainform"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Work Type:</td><td><select name="id"'.INC_TEXTBOX.'>';
              while (!$recordSet->EOF) {
                 echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                 $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
              echo '</table><input type="submit" name="submit" value="Select"></form>';
          };
          echo '<a href="adminestquotecblworktypeadd.php">Add new Work Type</a>';
     };
?>
<?php include('includes/footer.php'); ?>
