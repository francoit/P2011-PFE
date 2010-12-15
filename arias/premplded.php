<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
      var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?
     echo texttitle($lang['STR_PAYROLL_EMPLOYEE_DEDUCTIONS']);
     echo '<center>';
     if ($employeeid) {
         if ($save) {
           for ($i=1;$i<=($dedcount+1);$i++) {
                   if (${"id".$i}) { //this one already exists, update or delete
                          if (${"cancel".$i}) { //delete
                                 $conn->Execute('update prempldeduction set cancel=1, canceluserid='.sqlprep($userid).', canceldate=NOW() where id='.sqlprep(${"id".$i}));
                          } else { //update data
                                 $conn->Execute('update prempldeduction set description='.sqlprep(${"desc".$i}).',amountperperiod='.sqlprep(${"amount".$i}).',periodsremain='.sqlprep(${"periods".$i}).',glaccountid='.sqlprep(${"glacctid".$i}).', entryuserid='.sqlprep($userid).', lastchangeuserid='.sqlprep($userid).', entrydate=NOW() where id='.sqlprep(${"id".$i}));
                          };
                   } else { //if a valid entry, insert into file
                           if (((${"amount".$i}>0)&&(${"periods".$i}>0))||${"periods".$i}==-1) $conn->Execute('insert into prempldeduction (description,amountperperiod,periodsremain,glaccountid,employeeid,entrydate,entryuserid,lastchangeuserid) values ('.sqlprep(${"desc".$i}).','.sqlprep(${"amount".$i}).','.sqlprep(${"periods".$i}).','.sqlprep(${"glacctid".$i}).','.sqlprep($employeeid).', NOW(),'.sqlprep($userid).','.sqlprep($userid).')');
                   };
                   ${"id".$i}=0;
                   unset(${"desc".$i});
                   ${"amount".$i}='0';
                   ${"periods".$i}=0;
                   unset(${"glacctid".$i});
                   unset(${"cancel".$i});
              };
              echo textsuccess($lang['STR_DEDUCTION_UPDATED_SUCCESSFULLY']);
         };
           $recordSet=&$conn->Execute('select prempldeduction.id, prempldeduction.description,prempldeduction.amountperperiod,prempldeduction.periodsremain,prempldeduction.lastchangedate, prempldeduction.glaccountid, premployee.firstname, premployee.lastname from prempldeduction, premployee where premployee.gencompanyid='.sqlprep($active_company).' and prempldeduction.employeeid=premployee.id and premployee.id='.sqlprep($employeeid).' and prempldeduction.cancel=0 and prempldeduction.periodsremain<>0 and premployee.cancel=0 order by prempldeduction.description');
           $dedcount=0;
           while (!$recordSet->EOF) {
              $dedcount++;
              ${"id".$dedcount}=$recordSet->fields[0];
              ${"desc".$dedcount}=$recordSet->fields[1];
              ${"amount".$dedcount}=num_format($recordSet->fields[2],PREFERRED_DECIMAL_PLACES);
              ${"periods".$dedcount}=$recordSet->fields[3];
              ${"lastchangedate".$dedcount}=$recordSet->fields[4];
              ${"glacctid".$dedcount}=$recordSet->fields[5];
              ${"name".$dedcount}=$recordSet->fields[7].', '.$recordSet->fields[6];
              ${"cancel".$dedcount}=0;
              $recordSet->MoveNext();
           };
           echo texttitle('<font size="-1">'.${"name".$dedcount}.'</font>');
           echo '<form action="premplded.php" method="post" name="mainform"><table>';
           echo '<tr><th>'.$lang['STR_DESCRIPTION'].'</th><th>'.$lang['STR_AMOUNT'].'</th><th>'.$lang['STR_PERIODS'].'<br>'.$lang['STR_REMAINING'].'</th><th>'.$lang['STR_GL_ACCOUNT'].'</th><th>'.$lang['STR_DELETE_DED'].'<input type="checkbox" checked></th></tr>';
           echo '<input type="hidden" name="dedcount" value="'.$dedcount.'">';
           echo '<input type="hidden" name="employeeid" value="'.$employeeid.'">';
           for ($i=1;$i<=($dedcount+1);$i++) {
                 echo '<input type="hidden" name="id'.$i.'" value="'.${"id".$i}.'">';
                 echo '<input type="hidden" name="lastchangedate'.$i.'" value="'.${"lastchangedate".$i}.'">';
                 echo '<tr><td><input type="text" name="desc'.$i.'" value="'.${"desc".$i}.'" size="20" maxlength="50"'.INC_TEXTBOX.'>';
                 echo '<td><input type="text" name="amount'.$i.'" onchange="validatenum(this)" value="'.${"amount".$i}.'" size="10" maxlength="15"'.INC_TEXTBOX.'>';
                 echo '<td><input type="text" name="periods'.$i.'" onchange="validateintsigned(this)" value="'.${"periods".$i}.'" size="5" maxlength="5"'.INC_TEXTBOX.'></td>';
                 echo '<td><select name="glacctid'.$i.'"'.INC_TEXTBOX.'>';
                 $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid>=21 and glaccount.accounttypeid<=23 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
                 while (!$recordSet->EOF) {
                          echo '<option value="'.$recordSet->fields[0].'"'.checkequal(${"glacctid".$i},$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
                          $recordSet->MoveNext();
                 };
                 echo '</select></td>';
                 echo '<td><input name="cancel'.$i.'" type="checkbox"'.INC_TEXTBOX.'></td></tr>';
                 echo '<tr><td colspan="6" align="center"><hr></td></tr>';
           };
           echo '</table><input type="submit" name="save" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
     } else { //get employeeid
        echo '<form action="premplded.php" method="post"><table>';
        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME'].':</td><td><select name="employeeid"'.INC_TEXTBOX.'>';
        $recordSet=&$conn->Execute('select id,firstname,lastname from premployee where gencompanyid='.sqlprep($active_company).' and cancel=0 and (terminatedate > NOW() or year(terminatedate)=0) order by lastname,firstname');
        while (!$recordSet->EOF) {
                echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[2].', '.$recordSet->fields[1]."\n";
                $recordSet->MoveNext();
        };
        echo '</select></td></tr></table>';
        echo '</table><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></form>';
        
        echo '</center>';

     };
?>
<?php include('includes/footer.php'); ?>
