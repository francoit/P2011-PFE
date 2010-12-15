<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
      var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?php   echo '<center>';
     echo texttitle($companyname. $lang['STR_EMPLOYEE_REVIEW_UPDATE']);
     if ($submit&&!$id) { //to check to see if search only pulled back 1 result
          $emplstr='';
          $bgdatestr='';
          $eddatestr='';
          if ($employeeid) $emplstr=' and premployee.id='.sqlprep($employeeid);
          if ($bgevaldate) $bgdatestr=' and premplreview.evaldate>='.sqlprep($bgevaldate);
          if ($edevaldate) $eddatestr=' and premplreview.evaldate<='.sqlprep($edevaldate);
          $recordSet = &$conn->Execute('select count(*) from premployee,premplreview where premployee.id=premplreview.employeeid and premployee.gencompanyid='.sqlprep($active_company).' and premployee.cancel=0'.$emplstr.$bgdatestr.$eddatestr);
          if (!$recordSet->EOF) if ($recordSet->fields[0]==1) {
                    $recordSet = &$conn->Execute('select premplreview.id from premployee,premplreview where premployee.id=premplreview.employeeid and premployee.gencompanyid='.sqlprep($active_company).' and premployee.cancel=0'.$emplstr.$bgdatestr.$eddatestr.' order by premployee.lastname,premployee.firstname,premplreview.evaldate desc');
                    $id=$recordSet->fields[0];
                    unset($employeeid);
          };
     };
     if ($id&&$delete) { //if we should be deleting the entry
          checkpermissions('pay');
          if ($conn->Execute("update premplreview set cancel=1, canceldate=NOW(), canceluserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
                     echo texterror($lang['STR_ERROR_DELETING_EMPLOYEE_REVIEW']);
          } else {
                     echo textsuccess($lang['STR_ERROR_DELETING_EMPLOYEE_REVIEW']);
                     unset($id);
          };
     } elseif ($id&&$evaluatorname) { //if we should update the entry
          checkpermissions('pay');
          if ($conn->Execute('update premplreview set evaluatorname='.sqlprep($evaluatorname).',evaldate='.sqlprep($evaldate).',premplreviewratingid='.sqlprep($premplreviewratingid).',comments='.sqlprep($comments).' where id='.sqlprep($id)) === false) {
                     echo texterror($lang['STR_ERROR_UPDATING_EMPLOYEE_REVIEW']);
          } else {
                     echo textsuccess($lang['STR_EMPLOYEE_REVIEW_UPDATE_SUCCESSFULLY']);
          };
     };
     if ($id) { // if the user has submitted info
          echo '<form action="premplreviewupd.php" method="post" name="mainform"><input type="hidden" name="id" value="'.$id.'"><table>';
          $recordSet2 = &$conn->Execute('select premplreview.id,premployee.lastname,premployee.firstname,premplreview.evaluatorname,premplreview.evaldate,premplreview.premplreviewratingid,premplreview.comments from premployee,premplreview where premployee.id=premplreview.employeeid and premployee.gencompanyid='.sqlprep($active_company).' and premployee.cancel=0 and premplreview.id='.sqlprep($id));
          if ($recordSet2->EOF) die(texterror($lang['STR_EMPLOYEE_REVIEW_NOT_FOUND']));
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMPLOYEE'].':</td><td>'.$recordSet2->fields[1].', '.$recordSet2->fields[2].'</td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EVALUATOR_NAME'].':</td><td><input type="text" name="evaluatorname" size="30" maxlength="50" value="'.$recordSet2->fields[3].'"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EVALUATION_DATE'].':</td><td><input type="text" name="evaldate" onchange="formatDate(this)" size="30" value="'.$recordSet2->fields[4].'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.evaldate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_RATING'].':</td><td><select name="premplreviewratingid"'.INC_TEXTBOX.'>';
          $recordSet=&$conn->Execute('select id,description from premplreviewrating order by description');
          while (!$recordSet->EOF) {
             echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],$recordSet2->fields[5],' selected').'>'.$recordSet->fields[1];
             $recordSet->MoveNext();
          };
          echo '</td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMMENTS'].':</td><td><textarea name="comments" cols="40" rows="8">'.$recordSet2->fields[6].'</textarea></td></tr>';
          echo '</td></tr></table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'premplreviewupd.php?delete=1&id='.$id.'\')">Delete this review</a>';
     } elseif ($submit) { //display reviews, let the user pick one to edit
          $emplstr='';
          $bgdatestr='';
          $eddatestr='';
          if ($employeeid) $emplstr=' and premployee.id='.sqlprep($employeeid);
          if ($bgevaldate) $bgdatestr=' and premplreview.evaldate>='.sqlprep($bgevaldate);
          if ($edevaldate) $eddatestr=' and premplreview.evaldate<='.sqlprep($edevaldate);
          $recordSet = &$conn->Execute('select premplreview.id,premployee.lastname,premployee.firstname,premplreview.evaluatorname,premplreview.evaldate,premplreviewrating.description from premployee,premplreview,premplreviewrating where premplreviewrating.id=premplreview.premplreviewratingid and premployee.id=premplreview.employeeid and premployee.gencompanyid='.sqlprep($active_company).' and premployee.cancel=0'.$emplstr.$bgdatestr.$eddatestr.' order by premployee.lastname,premployee.firstname,premplreview.evaldate desc');
          if ($recordSet->EOF) die(texterror($lang['STR_NO_REVIEWS_MATCH_SEARCH']));
          echo '<table border="1"><tr><th>'.$lang['STR_EMPLOYEE'].'</th><th>'.$lang['STR_EVALUATION_DATE'].'</th><th>'.$lang['STR_EVALUATOR'].'</th><th>'.$lang['STR_RATING'].'</th></tr>';
          while (!$recordSet->EOF) {
                  echo '<tr><td><a href="premplreviewupd.php?id='.$recordSet->fields[0].'">'.$recordSet->fields[1].', '.$recordSet->fields[2].'</a></td><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[5].'</td></tr>';
                  $recordSet->MoveNext();
          };
          echo '</table>';
     } else { //show search
          $recordSet = &$conn->Execute('select id,lastname,firstname from premployee where gencompanyid='.sqlprep($active_company).' and cancel=0 order by lastname,firstname');
          if (!$recordSet->EOF) {
              echo '<form action="premplreviewupd.php" method="post" name="mainform"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Employee:</td><td><select name="employeeid"'.INC_TEXTBOX.'><option>';
              while (!$recordSet->EOF) {
                 echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1].', '.$recordSet->fields[2]."\n";
                 $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGINNING_EVALUATION_DATE'].':</td><td><input type="text" name="bgevaldate" size="30" onchange="formatDate(this)"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.bgevaldate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ENDING_EVALUATION_DATE'].':</td><td><input type="text" name="edevaldate" size="30" onchange="formatDate(this)"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.edevaldate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
              echo '</table><input type="submit" name="submit" value="'.$lang['STR_SEARCH'].'"></form>';
          };
          echo '<a href="premplreviewadd.php">'.$lang['STR_ADD_NEW_EMPLOYEE_REVIEW'].'</a>';
     };
          echo '</center>';
?>
<?php include('includes/footer.php'); ?>
