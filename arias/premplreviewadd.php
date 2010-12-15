<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
      var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?
     echo '<center>';
     echo texttitle($lang['STR_EMPLOYEE_REVIEW_ADD']);
     if ($employeeid) {
             checkpermissions('pay');
             if ($conn->Execute('insert into premplreview (employeeid,evaluatorname,evaldate,premplreviewratingid,comments,entrydate,entryuserid,lastchangeuserid) VALUES ('.sqlprep($employeeid).', '.sqlprep($evaluatorname).', '.sqlprep($evaldate).', '.sqlprep($premplreviewratingid).', '.sqlprep($comments).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).')') === false) {
                echo texterror($lang['STR_ERROR_ADDING_EMPLOYEE_REVIEW']);
             } else {
                echo textsuccess($lang['STR_EMPLOYEE_REVIEW_ADDED_SUCCESSFULLY']);
             };
     };
     echo '<form action="premplreviewadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
     echo '<tr><td>'.$lang['STR_EMPLOYEE'].':</td><td><select name="employeeid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select id,lastname,firstname from premployee where gencompanyid='.sqlprep($active_company).' and cancel=0 and status=1 order by lastname,firstname');
     while (!$recordSet->EOF) {
             echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1].', '.$recordSet->fields[2];
             $recordSet->MoveNext();
     };
     echo '</td></tr>';
     echo '<tr><td>'.$lang['STR_EVALUATOR_NAME'].':</td><td><input type="text" name="evaluatorname" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td>'.$lang['STR_EVALUATION_DATE'].':</td><td><input type="text" name="evaldate" onchange="formatDate(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.evaldate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
     echo '<tr><td>'.$lang['STR_RATING'].':</td><td><select name="premplreviewratingid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select id,description from premplreviewrating order by description');
     while (!$recordSet->EOF) {
             echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1];
             $recordSet->MoveNext();
     };
     echo '</td></tr>';
     echo '<tr><td>'.$lang['STR_COMMENTS'].':</td><td><textarea name="comments" cols="40" rows="8"></textarea></td></tr>';
     echo '</table><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
     
     echo '</center>';
?>
<?php include('includes/footer.php'); ?>
