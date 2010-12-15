<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //adminestquoteworktypeaddl.php - copyright 2001 by Noguska - Fostoria, OH

     $recordSet=&$conn->Execute('select estquoteworktype.name,estquoteworktypegen.name from estquoteworktype,estquoteworktypegen where estquoteworktype.genworktypeid=estquoteworktypegen.id and estquoteworktype.id='.sqlprep($worktypeid));
     if (!$recordSet->EOF) {
           $displayname=' for Work Type: '.$recordSet->fields[1].' - '.$recordSet->fields[0];
     };
     echo texttitle('Work Type Question'.$displayname);

     if ($delete&&$id) { //if update
       if ($conn->Execute('update estquoteworktypeaddl set cancel=1, canceluserid='.sqlprep($userid).', canceldate=NOW() where id='.sqlprep($id)) === false) {
           echo texterror("Error deleting work type question.");
       } else {
           echo textsuccess("Work Type Question deleted successfully.");
       };
     };
     if ($save) {
         if ($id) { //if update
          if ($conn->Execute('update estquoteworktypeaddl set question='.sqlprep($question).',validreply='.sqlprep($validreply).',minreply='.sqlprep($minreply).',maxreply='.sqlprep($maxreply).',askonlyifmtblackink='.sqlprep($askonlyifmtblackink).',askonlyifmtoneink='.sqlprep($askonlyifmtoneink).',askonlyifmtoneside='.sqlprep($askonlyifmtoneside).',askonlyminsizelength='.sqlprep($askonlyminsizelength).',askonlyminsizewidth='.sqlprep($askonlyminsizewidth).',askonlymaxsizelength='.sqlprep($askonlymaxsizelength).',askonlymaxsizewidth='.sqlprep($askonlymaxsizewidth).', askonlyifoneink='.sqlprep($askonlyifoneink).',calculatehow='.sqlprep($calculatehow).',aboveqty='.sqlprep($aboveqty).',lastchangeuserid='.sqlprep($userid).',amount='.sqlprep($amount).' where id='.sqlprep($id)) === false) {
             echo texterror("Error updating work type addl info.");
          } else {
             echo textsuccess("Work Type Question updated successfully.");
          };
         } else { //if insert
          if ($conn->Execute('insert into estquoteworktypeaddl (worktypeid,question,validreply,minreply,maxreply,askonlyifmtblackink,askonlyifmtoneink,askonlyifmtoneside,askonlyminsizelength,askonlyminsizewidth,askonlymaxsizelength,askonlymaxsizewidth,askonlyifoneink,calculatehow,amount,aboveqty,entrydate,entryuserid,lastchangeuserid) values ('.sqlprep($worktypeid).', '.sqlprep($question).', '.sqlprep($validreply).', '.sqlprep($minreply).', '.sqlprep($maxreply).', '.sqlprep($askonlyifmtblackink).', '.sqlprep($askonlyifmtoneink).', '.sqlprep($askonlyifmtoneside).', '.sqlprep($askonlyminsizelength).', '.sqlprep($askonlyminsizewidth).', '.sqlprep($askonlymaxsizelength).', '.sqlprep($askonlymaxsizewidth).', '.sqlprep($askonlyifoneink).', '.sqlprep($calculatehow).', '.sqlprep($amount).','.sqlprep($aboveqty).',NOW(),'.sqlprep($userid).', '.sqlprep($userid).')') === false) {
             echo texterror("Error adding Work type addl info.");
          } else {
             echo textsuccess("Work Type Question added successfully.");
          };
         };
     };
       $recordSet=&$conn->SelectLimit('select id,question,validreply,minreply,maxreply,askonlyifmtblackink,askonlyifmtoneink,askonlyifmtoneside,askonlyminsizelength,askonlyminsizewidth,askonlymaxsizelength,askonlymaxsizewidth,calculatehow,amount,askonlyifoneink,aboveqty from estquoteworktypeaddl where id='.sqlprep($id).' and worktypeid='.sqlprep($worktypeid),1);
       if (!$recordSet->EOF) {
          $id=$recordSet->fields[0];
          $question=$recordSet->fields[1];
          $validreply=$recordSet->fields[2];
          $minreply=$recordSet->fields[3];
          $maxreply=$recordSet->fields[4];
          $askonlyifmtblackink=$recordSet->fields[5];
          $askonlyifmtoneink=$recordSet->fields[6];
          $askonlyifmtoneside=$recordSet->fields[7];
          $askonlyminsizelength=$recordSet->fields[8];
          $askonlyminsizewidth=$recordSet->fields[9];
          $askonlymaxsizelength=$recordSet->fields[10];
          $askonlymaxsizewidth=$recordSet->fields[11];
          $calculatehow=$recordSet->fields[12];
          $amount=$recordSet->fields[13];
          $askonlyifoneink=$recordSet->fields[14];
          $aboveqty=$recordSet->fields[15];
          $recordSet->MoveNext();
       };
       echo '<form action="adminestquoteworktypeaddl.php" method="post" name="mainform"><table border="1">';
       echo '<input type="hidden" name="id" value="'.$id.'">';
       echo '<input type="hidden" name="genworktypeid" value="'.$genworktypeid.'">';
       echo '<input type="hidden" name="worktypeid" value="'.$worktypeid.'">';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Question?:</td><td><input type="text" name="question" size="30" maxlength="50" value="'.$question.'"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Calculate How:</td><td><select name="calculatehow"'.INC_TEXTBOX.'><option value="'.SD_PER_JOB.'"'.checkequal($calculatehow,SD_PER_JOB,' selected').'>'.CURRENCY_SYMBOL.' Amount Extra / Job<option value="'.SD_PER_M.'"'.checkequal($calculatehow,SD_PER_M,' selected').'>'.CURRENCY_SYMBOL.' Amount / M<option value="'.SD_PER_M_PER_SIDE.'"'.checkequal($calculatehow,SD_PER_M_PER_SIDE,' selected').'>'.CURRENCY_SYMBOL.' Amount / M /side <option value="'.SD_EXTRA_TIMES.'"'.checkequal($calculatehow,SD_EXTRA_TIMES,' selected').'>'.CURRENCY_SYMBOL.' Amount X Answer<option value="'.SD_NO_CHARGE.'"'.checkequal($calculatehow,SD_NO_CHARGE,' selected').'>No Addl. Charge</select></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Amount:</td><td><input type="text" name="amount" size="30" maxlength="8" value="'.$amount.'"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Only on Quantities above:</td><td><input type="text" name="aboveqty" size="30" maxlength="8" value="'.$aboveqty.'"'.INC_TEXTBOX.'></td></tr>';

       echo '<tr><td rowspan="3" align="'.TABLE_LEFT_SIDE_ALIGN.'">Valid Reply:</td><td><input type="radio" name="validreply" value="'.SD_YES_NO.'"'.checkequal($validreply,SD_YES_NO,' checked').INC_TEXTBOX.'>Yes/No</td></tr><tr><td><input type="radio" name="validreply" value="'.SD_NUM_BETWEEN.'"'.checkequal($validreply,SD_NUM_BETWEEN,' checked').INC_TEXTBOX.'>Number between <input type="text" name="minreply" size="5" maxlength="13" value="'.checkdec($minreply,0).'"'.INC_TEXTBOX.'> and <input type="text" name="maxreply" size="5" maxlength="13" value="'.checkdec($maxreply,0).'"'.INC_TEXTBOX.'></td></tr><tr><td><input type="radio" name="validreply" value="'.SD_TEXT.'"'.checkequal($validreply,SD_TEXT,' checked').INC_TEXTBOX.'>Text</td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Ask only if using non-black ink:</td><td><input type="checkbox" name="askonlyifmtblackink" value="1"'.checkequal($askonlyifmtblackink,1,' checked').INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Ask only if using one ink:</td><td><input type="checkbox" name="askonlyifoneink" value="1"'.checkequal($askonlyifoneink,1,' checked').INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Ask only if using more than one ink:</td><td><input type="checkbox" name="askonlyifmtoneink" value="1"'.checkequal($askonlyifmtoneink,1,' checked').INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Ask only if using more than one side:</td><td><input type="checkbox" name="askonlyifmtoneside" value="1"'.checkequal($askonlyifmtoneside,1,' checked').INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Ask only if larger than:</td><td><input type="text" name="askonlyminsizewidth" size="5" maxlength="13" value="'.$askonlyminsizewidth.'"'.INC_TEXTBOX.'> x <input type="text" name="askonlyminsizelength" size="5" maxlength="13" value="'.$askonlyminsizelength.'"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Ask only if smaller than:</td><td><input type="text" name="askonlymaxsizewidth" size="5" maxlength="13" value="'.$askonlymaxsizewidth.'"'.INC_TEXTBOX.'> x <input type="text" name="askonlymaxsizelength" size="5" maxlength="13" value="'.$askonlymaxsizelength.'"'.INC_TEXTBOX.'></td></tr>';
       echo '</table><input type="submit" name="save" value="Save Changes"></form>';
       echo '<br><br><a href="adminestquoteworktypeupd.php?genworktypeid='.$genworktypeid.'&id='.$worktypeid.'">Return to Detail Work Type</a><br><br> ';

       echo '<a href="javascript:confirmdelete(\'adminestquoteworktypeaddl.php?delete=1&id='.$id.'&genworktypeid='.$genworktypeid.'\');">Delete this Question</a>';
?>
<?php include('includes/footer.php'); ?>
