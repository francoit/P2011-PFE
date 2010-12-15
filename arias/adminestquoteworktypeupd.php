<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //adminestquoteworktypeupd.php
     echo texttitle('Work Type Update');
     if ($id) {
       if ($delete) { //delete work type
             if ($conn->Execute('update estquoteworktype set cancel=1, canceldate=NOW(), canceluserid='.sqlprep($userid).' where id='.sqlprep($id)) === false) {
                echo texterror("Error deleting detail work type.");
             } else {
                echo textsuccess("Detail Work Type deleted successfully.");
                unset($id);  //return to first screen
                unset($delete);
             };
       } elseif ($name) { //update work type
             if ($conn->Execute('update estquoteworktype set name='.sqlprep($name).', turnaroundqty='.sqlprep($turnaroundqty).', turnarounddaysuptoqty='.sqlprep($turnarounddaysuptoqty).', turnarounddaysoverqty='.sqlprep($turnarounddaysoverqty).',notes='.sqlprep($notes).', lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($id)) === false) {
                echo texterror("Error updating Detail Work Type.");
             } else {
                echo textsuccess("Detail Work Type updated successfully.");
             };
             unset ($id);
             unset($name);
       } else {
          echo '<form action="adminestquoteworktypeupd.php" method="post" name="mainform"><input type="hidden" name="id" value="'.$id.'"><table>';
          $recordSet2 = &$conn->SelectLimit('select estquoteworktype.name,estquoteworktype.turnaroundqty,estquoteworktype.turnarounddaysuptoqty,estquoteworktype.turnarounddaysoverqty,estquoteworktype.notes,estquoteworktype.orderflag,estquoteworktypegen.name from estquoteworktype,estquoteworktypegen where estquoteworktypegen.id=estquoteworktype.genworktypeid and estquoteworktype.id='.sqlprep($id),1);
          if ($recordSet2->EOF) die(texterror('Work Type not found.'));
          echo '<input type="hidden" name="genworktypeid" value="'.$genworktypeid.'">';
          echo texttitle('Update Detail Work Type Info<br>General Work Type = '.$recordSet2->fields[6]);
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name:</td><td><input type="text" name="name" size="30" maxlength="30" value="'.$recordSet2->fields[0].'"'.INC_TEXTBOX.'"></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Turnaround days up to quantity <input type="text" name="turnaroundqty" size="10" maxlength="8" value="'.$recordSet2->fields[1].'"'.INC_TEXTBOX.'>:</td><td><input type="text" name="turnarounddaysuptoqty" size="6" maxlength="4" value="'.$recordSet2->fields[2].'"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Turnaround days over quantity:</td><td><input type="text" name="turnarounddaysoverqty" size="6" maxlength="4" value="'.$recordSet2->fields[3].'"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Order for Display:</td><td><input type="text" name="orderflag" size="6" maxlength="4" value="'.$recordSet2->fields[5].'"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Notes for Quote:</td><td><textarea name="notes" rows="5" cols="40">'.$recordSet2->fields[4].'</textarea></td></tr>';

          echo '</table><br>';
          $recordSet = &$conn->Execute('select id,question,validreply,minreply,maxreply,askonlyifmtblackink,askonlyifmtoneink,askonlyifmtoneside,askonlyminsizelength,askonlyminsizewidth,askonlymaxsizelength,askonlymaxsizewidth,calculatehow,amount from estquoteworktypeaddl where worktypeid='.sqlprep($id).' and cancel=0');
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
              echo 'Ask Question: <a href="adminestquoteworktypeaddl.php?genworktypeid='.$genworktypeid.'&id='.$recordSet->fields[0].'&worktypeid='.$id.'">'.$recordSet->fields[1].'</a> '.$wherestr.$ansstr.$calcstr.'<br>';
              $recordSet->MoveNext();
              unset($wherestr);
              unset($ansstr);
              unset($calcstr);
          };
          echo '<a href="adminestquoteworktypeaddl.php?worktypeid='.$id.'&genworktypeid='.$genworktypeid.'">Add new Question</a><br><br>';
          echo '<input type="submit" value="Save Changes"></form>';
          echo '<a href="javascript:confirmdelete(\'adminestquoteworktypeupd.php?delete=1&id='.$id.'&genworktypeid='.$genworktypeid.'\')">Delete this Work Type</a><br>';
       };
     };
     if ($genadd==1) { //add a new general work type name & sort order
        if ($gensave) {
           //save general work type info
           if  ($conn->Execute('insert into estquoteworktypegen (name,orderflag,entrydate,entryuserid,lastchangeuserid,gencompanyid) values ('.sqlprep($genname).','.sqlprep($genorderflag).',NOW(),'.sqlprep($userid).','.sqlprep($userid).','.sqlprep($active_company).')') === false) {
                  echo texterror("Error adding general work type");
           } else {
                echo textsuccess("General Work Type added successfully.");
           };
           unset($gensave);
           unset($genadd);
        } else {
          echo '<form action="adminestquoteworktypeupd.php" method="post" name="mainform"><table>';
          echo '<input type="hidden" name="genadd" value="1">';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name:</td><td><input type="text" name="genname" size="30" maxlength="30" '.INC_TEXTBOX.'"></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Order:</td><td><input type="text" name="genorderflag" size="6" maxlength="4" '.INC_TEXTBOX.'></td></tr>';
          echo '</table>';
          echo '<input type="submit" name="gensave" value="Save Changes"></form>';
          echo '<br><br><a href="adminestquoteworktypeupd.php">Cancel Add and Return to Selection</a><br><br>';
        };
     } elseif ($genupd) {
        if ($gensave) {
           //save general work type info
           if  ($conn->Execute('update estquoteworktypegen set name='.sqlprep($genname).',orderflag='.sqlprep($genorderflag).',lastchangedate=NOW(),lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($genworktypeid)) === false) {
                  echo texterror("Error updating general work type");
           } else {
                echo textsuccess("General Work Type updated successfully.");
           };
           unset($gensave);
           unset($genupd);
        } elseif ($delete) {
             if ($conn->Execute('update estquoteworktypegen set cancel=1, canceldate=NOW(), canceluserid='.sqlprep($userid).' where id='.sqlprep($genworktypeid)) === false) {
                echo texterror("Error deleting general work type.");
             } else {
                echo textsuccess("General Work Type deleted successfully.");
                unset($genworktypeid);  //return to first screen
             };
             unset($genupd);
             unset($genadd);
             unset($genworktypeid);
        } else {
          //edit existing general work type, then after ask about details.
          echo '<form action="adminestquoteworktypeupd.php" method="post" name="mainform"><table>';
          echo '<input type="hidden" name="genupd" value="1">';
          echo '<input type="hidden" name="genworktypeid" value="'.$genworktypeid.'">';
          $recordSet=&$conn->Execute('select name,orderflag from estquoteworktypegen where id='.sqlprep($genworktypeid));
          if (!$recordSet->EOF) {
             echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name:</td><td><input type="text" name="genname" value="'.$recordSet->fields[0].'" size="30" maxlength="30" '.INC_TEXTBOX.'"></td></tr>';
             echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Order:</td><td><input type="text" name="genorderflag" value="'.$recordSet->fields[1].'"  size="6" maxlength="4" '.INC_TEXTBOX.'></td></tr>';
             echo '</table>';
             echo '<input type="submit" name="gensave" value="Save Changes"></form>';
             echo '<br><br><a href="adminestquoteworktypestdqty.php?worktypeid='.$genworktypeid.'">Create Standard Quantity List for Price Lists</a><br><br>';
             echo '<br><br><a href="javascript:confirmdelete(\'adminestquoteworktypeupd.php?delete=1&&genupd=1&&genworktypeid='.$genworktypeid.'\')">Delete this Work Type</a><br>';
          } else {
             echo 'Cannot Find General Work Type';
          };
        };
     };
     if ($genworktypeid&!$id&&!$genupd&&$genadd<>1) { //show search of detail work typem, and edit of general
          $recordSet = &$conn->Execute('select estquoteworktype.id,estquoteworktype.name,estquoteworktypegen.name from estquoteworktype,estquoteworktypegen where estquoteworktype.genworktypeid=estquoteworktypegen.id  and estquoteworktype.genworktypeid='.sqlprep($genworktypeid).' and estquoteworktype.cancel=0 order by estquoteworktype.orderflag,estquoteworktype.name');

          if (!$recordSet->EOF) {
              echo '<form action="adminestquoteworktypeupd.php" method="post" name="mainform"><table>';
              echo '<tr><th colspan="2"><center>General Work Type = '.$recordSet->fields[2].'</center></th></tr>';
              echo '<input type="hidden" name="genworktypeid" value="'.$genworktypeid.'">';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Detail Work Type:</td><td><select name="id"'.INC_TEXTBOX.'>';
              while (!$recordSet->EOF) {
                 echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                 $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
              echo '</table><input type="submit" name="submit" value="Select"></form>';
          };
          echo '<a href="adminestquoteworktypeadd.php?genworktypeid='.$genworktypeid.'">Add new Detail Work Type</a>';
     } elseif (!$genworktypeid&&$genadd<>1) { // pick a general work type
          $recordSet = &$conn->Execute('select id,name from estquoteworktypegen where gencompanyid='.sqlprep($active_company).' and cancel=0 order by orderflag,name');
          if (!$recordSet->EOF) {
              echo '<form action="adminestquoteworktypeupd.php" method="post" name="mainform"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">General Work Type:</td><td><select name="genworktypeid"'.INC_TEXTBOX.'>';
              while (!$recordSet->EOF) {
                 echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                 $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
              echo '</table><input type="submit" name="genupd" value="Edit selection"></form>';
          };
          echo '<a href="adminestquoteworktypeupd.php?genadd=1">Add new General Work Type</a>';
     };
?>
<?php include('includes/footer.php'); ?>
