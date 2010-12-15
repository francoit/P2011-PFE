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
     echo '<center>';
     echo texttitle($lang['STR_PAYROLL_GENERAL_DEDUCTIONS']);
     if ($save) {
          for ($i=1;$i<=$bencount+4;$i++) {
               if (${"id".$i}) { //this one already exists, update or delete
                      if (${"cancel".$i}) { //delete
                             $conn->Execute('update prbended set cancel=1, canceluserid='.sqlprep($userid).', canceldate=NOW() where id='.sqlprep(${"id".$i}));
                      } else { //update data
                             prbendedupdate(${"id".$i}, ${"paytype".$i}, ${"name".$i}, ${"howfig".$i},${"prdedgroupid".$i},${"rate".$i},${"ceilingperyear".$i},${"payableglacctid".$i},${"expenseglacctid".$i},${"vendorid".$i},${"lastchangedate".$i});
                      };
               } else { //if a valid entry, insert into file
                       if ((${"rate".$i}>0)&&(${"howfig".$i}>0)) if ($conn->Execute('insert into prbended (gencompanyid,paytype,bendedtype,name,howfig,prdedgroupid,rate,payableglacctid,entrydate,entryuserid,lastchangeuserid,ceilingperyear,vendorid) values ('.sqlprep($active_company).','.sqlprep(${"paytype".$i}).',1,'.sqlprep(${"name".$i}).','.sqlprep(${"howfig".$i}).','.sqlprep(${"prdedgroupid".$i}).','.sqlprep(${"rate".$i}).','.sqlprep(${"payableglacctid".$i}).', NOW(),'.sqlprep($userid).','.sqlprep($userid).','.sqlprep(${"ceilingpeyear".$i}).','.sqlprep(${"vendorid".$i}).')') === false) echo texterror($lang['STR_PRBENDED_INSERT_FAILED']);
               };
               ${"id".$i}=0;
               ${"paytype".$i}=0;
               ${"name".$i}='';
               ${"howfig".$i}=0;
               ${"prdedgroupid".$i}='';
               ${"rate".$i}=0;
               ${"payableglacctid".$i}=0;
               ${"ceilingperyear".$i}=0;
               ${"vendorid".$i}=0;
               ${"cancel".$i}=0;

          };
          echo textsuccess($lang['STR_DEDUCTION_UPDATED_SUCCESSFULLY']);
     };
       $recordSet=&$conn->Execute('select id,paytype,name,howfig,prdedgroupid,rate,payableglacctid,ceilingperyear,vendorid,lastchangedate from prbended where gencompanyid='.sqlprep($active_company).' and bendedtype=1 and cancel=0 order by name,paytype');
       $bencount=0;
       while (!$recordSet->EOF) {
          $bencount++;
          ${"id".$bencount}=$recordSet->fields[0];
          ${"paytype".$bencount}=$recordSet->fields[1];
          ${"name".$bencount}=$recordSet->fields[2];
          ${"howfig".$bencount}=$recordSet->fields[3];
          ${"prdedgroupid".$bencount}=$recordSet->fields[4];
          ${"rate".$bencount}=$recordSet->fields[5];
          ${"payableglacctid".$bencount}=$recordSet->fields[6];
          ${"ceilingperyear".$bencount}=$recordSet->fields[7];
          ${"vendorid".$bencount}=$recordSet->fields[8];
          ${"lastchangedate".$bencount}=$recordSet->fields[9];

          ${"cancel".$bencount}=0;
          $recordSet->MoveNext();
       };
       echo '<form action="adminprded.php" method="post" name="mainform"><table>';
       echo '<tr><th>'.$lang['STR_HOURLY_SALARY'].'</th><th>'.$lang['STR_DESCRIPTION'].'</th><th>'.$lang['STR_HOW_FIGURED'].'</th><th>'.$lang['STR_GROUP'].'</th><th>'.$lang['STR_RATE'].'</th><th>'.$lang['STR_YEARLY_CEILING'].'</th></tr><tr><th></th><th>'.$lang['STR_GL_PAYABLE_ACCOUNT'].'</th><th colspan="3">'.$lang['STR_VENDOR'].'</th><th>'.$lang['STR_DELETE'].'<input type="checkbox" checked></th></tr>';
       echo '<input type="hidden" name="bencount" value="'.$bencount.'">';
       for ($i=1;$i<=$bencount+4;$i++) {
             echo '<input type="hidden" name="id'.$i.'" value="'.${"id".$i}.'">';
             echo '<input type="hidden" name="lastchangedate'.$i.'" value="'.${"lastchangedate".$i}.'">';
             echo '<tr><td><select name="paytype'.$i.'"'.INC_TEXTBOX.'>';
             echo '<option value="0"'.checkequal(${"paytype".$i},0," selected").'>'.Hourly;
             echo '<option value="1"'.checkequal(${"paytype".$i},1," selected").'>'.Salary;
             echo '</select></td>';
             echo '<td><input type="text" name="name'.$i.'" value="'.${"name".$i}.'" size="15" '.INC_TEXTBOX.'>';

             echo '<td><select name="howfig'.$i.'"'.INC_TEXTBOX.'>';
             echo '<option value="1"'.checkequal(${"howfig".$i},1," selected").'>% '.$lang['STR_TAXABLE_PAY'];
             echo '<option value="2"'.checkequal(${"howfig".$i},2," selected").'>% '.$lang['STR_TAXABLE_PAY_TAX'];
             echo '<option value="3"'.checkequal(${"howfig".$i},3," selected").'>% '.$lang['STR_ST_PAY'];
             echo '<option value="4"'.checkequal(${"howfig".$i},4," selected").'>'.$lang['STR_HOURS_WORKED'];
             echo '<option value="5"'.checkequal(${"howfig".$i},5," selected").'>'.$lang['STR_HOURS_PAID'];
             echo '<option value="6"'.checkequal(${"howfig".$i},6," selected").'>'.$lang['STR_ST_HOURS'];
             echo '<option value="7"'.checkequal(${"howfig".$i},7," selected").'>'.$lang['STR_WEEKLY_AMT'];
             echo '<option value="8"'.checkequal(${"howfig".$i},8," selected").'>'.$lang['STR_AMT_1_WEEK_MONTH'];
             echo '</select></td>';
             echo '<td><select name="prdedgroupid'.$i.'"'.INC_TEXTBOX.'><option value="0">';
             $recordSet = &$conn->Execute('select id, name from prdedgroup where gencompanyid='.sqlprep($active_company).' order by name');
             while (!$recordSet->EOF) {
                     echo '<option value="'.$recordSet->fields[0].'"'.checkequal(${"prdedgroupid".$i},$recordSet->fields[0],' selected').'>'.$recordSet->fields[1]."\n";
                     $recordSet->MoveNext();
             };
             echo '</select></td>';
             echo '<td><input type="text" name="rate'.$i.'" value="'.${"rate".$i}.'" size="8" maxlength="10" onchange="validatenum(this)"'.INC_TEXTBOX.'>';
             echo '<td><input type="text" name="ceilingperyear'.$i.'" value="'.${"ceilingperyear".$i}.'" size="10"  onchange="validatenum(this)"'.INC_TEXTBOX.'></td></tr>';
             echo '<tr><td></td><td><select name="payableglacctid'.$i.'"'.INC_TEXTBOX.'>';
             $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid>=21 and glaccount.accounttypeid<=23 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
             while (!$recordSet->EOF) {
                      echo '<option value="'.$recordSet->fields[0].'"'.checkequal(${"payableglacctid".$i},$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
                      $recordSet->MoveNext();
             };
             echo '</select></td>';
             ${"vname".$i}='';
             if (${"vendorid".$i}>0) {
                    $recordSet=&$conn->Execute('select vendor.id,company.companyname from vendor,company where vendor.paytocompanyid=company.id and vendor.id='.sqlprep(${"vendorid".$i}));
                    if (!$recordSet->EOF) ${"vname".$i}=$recordSet->fields[1];
             };
             echo '<td colspan="3"><input type="text" size="15" maxsize="30" name="vendorid'.$i.'" value="'.${"vendorid".$i}.'"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupvendor.php?name='."vendorid".$i.'\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="'.IMAGE_VENDOR_LOOKUP.'" border="0" alt="Vendor Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'apvendadd.php\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="'.IMAGE_VENDOR_ADD.'" border="0" alt="Vendor Add"></a><font size="-1"> ('.${"vname".$i}.')</font></td>';
             echo '<td><input name="cancel'.$i.'" type="checkbox"></td></tr>';
             echo '<tr><td colspan="6" align="center"><hr></td></tr>';
       };
       echo '</table><input type="submit" name="save" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
       
       echo '</center>';
?>

<?php include('includes/footer.php'); ?>
