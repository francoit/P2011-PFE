<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?
     echo '<center>';
     echo texttitle($lang['STR_PAYROLL_BENEFITS_AND_DEDUCTIONS_GENERAL_DATA']);
     if ($save) {
          $recordSet=&$conn->Execute('select count(*) from prcompany where id='.sqlprep($active_company));
          if (!$recordSet->EOF) if ($recordSet->fields[0]) { // found an entry and it is > 0 for count. need to update entry
                prcompanyupdatebended($shift2multiplier, $shift3multiplier, $sickleavehrsperyear, $maxsickleave, $minwageperhr, $lastchangedate);
          } else { //not found, so add new entry now.
               $conn->Execute('insert into prcompany (id,shift2multiplier,shift3multiplier,sickleavehrsperyear,maxsickleave,minwagehr) values ('.sqlprep($active_company).','.sqlprep($shift2multiplier).','.sqlprep($shift3multiplier).','.sqlprep($sickleavehrsperyear).','.sqlprep($maxsickleave).','.sqlprep($minwagehr).')');
          };
          $conn->Execute('delete from prcompanyperiod where prcompanyid='.sqlprep($active_company));
          $recordSet=&$conn->Execute('select id from prperiod');
          while (!$recordSet->EOF) {
               $conn->Execute('insert into prcompanyperiod (prcompanyid,prperiodid,maxpayhr,maxgross) values ('.sqlprep($active_company).', '.sqlprep($recordSet->fields[0]).', '.sqlprep(${'maxpayhr'.$recordSet->fields[0]}).','.sqlprep(${'maxgross'.$recordSet->fields[0]}).')');
               $recordSet->MoveNext();
          };
          for ($i=1;$i<=$vaccount+2;$i++) {
              if (${"vacid".$i}) { //had a record previously, update it now
                 if (${"cancel".$i}) { //delete
                    $conn->Execute('delete from prvacation where id='.sqlprep(${"vacid".$i}));
                 } else { //update
                    prvacationupdate(${"vacid".$i}, ${"yrsbeforeaccrue".$i}, ${"vacdaysperyear".$i}, ${"maxaccrue".$i},${"lastchangedate".$i});                   $conn->Execute('update prvacation set yrsbeforeaccrue='.sqlprep(${"yrsbeforeaccrue".$i}).', vacdaysperyear='.sqlprep(${"vacdaysperyear".$i}).', maxaccrue='.sqlprep(${"maxaccrue".$i}).' where id='.sqlprep(${"vacid".$i}));
                 };
              } else { //add a new record after check for valid
                 if ((${"vacdaysperyear".$i}>0)&&!${"cancel".$i}) {
                      $conn->Execute('insert into prvacation (yrsbeforeaccrue,vacdaysperyear,maxaccrue,gencompanyid) values ('.sqlprep(${"yrsbeforeaccrue".$i}).','.sqlprep(${"vacdaysperyear".$i}).','.sqlprep(${"maxaccrue".$i}).','.sqlprep($active_company).')');
                 };
              };
              ${"vacid".$i}=0;
              ${"yrsbeforeaccrue".$i}=0;
              ${"vacdaysperyear".$i}=0;
              ${"maxaccrue".$i}=0;
              ${"cancel".$i}=0;

          };
          echo textsuccess($lang['STR_UPDATE_SUCCESSFUL']);
     };
      $recordSet=&$conn->Execute('select shift2multiplier,shift3multiplier,sickleavehrsperyear,maxsickleave,minwagehr,lastchangedate from prcompany where id='.sqlprep($active_company));
       if (!$recordSet->EOF) {
          $shift2multiplier=$recordSet->fields[0];
          $shift3multiplier=$recordSet->fields[1];
          $sickleavehrsperyear=$recordSet->fields[2];
          $maxsickleave=$recordSet->fields[3];
          $minwagehr=$recordSet->fields[4];
          $lastchangedate=$recordSet->fields[5];
       };
       $recordSet=&$conn->Execute('select prperiodid,maxpayhr,maxgross from prcompanyperiod where prcompanyid='.sqlprep($active_company).' order by prperiodid');
       while (!$recordSet->EOF) {
                ${'maxpayhr'.$recordSet->fields[0]}=$recordSet->fields[1];
                ${'maxgross'.$recordSet->fields[0]}=$recordSet->fields[2];
                $recordSet->MoveNext();
       };
       $recordSet=&$conn->Execute('select id,yrsbeforeaccrue,vacdaysperyear,maxaccrue,lastchangedate from prvacation where gencompanyid='.sqlprep($active_company).' order by yrsbeforeaccrue');
       $vaccount=0;
       while (!$recordSet->EOF) {
                $vaccount++;
                ${"vacid".$vaccount}=$recordSet->fields[0];
                ${"yrsbeforeaccrue".$vaccount}=$recordSet->fields[1];
                ${"vacdaysperyear".$vaccount}=$recordSet->fields[2];
                ${"maxaccrue".$vaccount}=$recordSet->fields[3];
                ${"cancel".$vaccount}=0;
                ${"lastchangedate".$vaccount}=$recordSet->fields[4];
                $recordSet->MoveNext();
       };
       echo '<form action="adminprgendat.php" method="post" name="mainform"><table>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MINIMUM_WAGE'].': </td><td><input type="text" name="minwagehr"  onchange="validatenum(this)" value="'.$minwagehr.'"'.INC_TEXTBOX.'>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MULTIPLIER_FOR_SECOND_SHIFT'].': </tr><td><input type="text" name="shift2multiplier"  onchange="validatenum(this)" value="'.$shift2multiplier.'"'.INC_TEXTBOX.'>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MULTIPLIER_FOR_THIRD_SHIFT'].': </tr><td><input type="text" name="shift3multiplier"  onchange="validatenum(this)" value="'.$shift3multiplier.'"'.INC_TEXTBOX.'>';
       $recordSet=&$conn->Execute('select id,name from prperiod order by id');
       while (!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAXIMUM_PAY_HOUR_FOR'].': '.$recordSet->fields[1].':</tr><td><input type="text"  onchange="validatenum(this)" name="maxpayhr'.$recordSet->fields[0].'" value="'.${'maxpayhr'.$recordSet->fields[0]}.'"'.INC_TEXTBOX.'>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAXIMUM_GROSS_PAY_FOR'].': '.$recordSet->fields[1].':</tr><td><input type="text" onchange="validatenum(this)" name="maxgross'.$recordSet->fields[0].'" value="'.${'maxgross'.$recordSet->fields[0]}.'"'.INC_TEXTBOX.'>';
               $recordSet->MoveNext();
       };
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SICK_LEAVE_HOURS_ALLOWED_PER_YEAR'].': </tr><td><input type="text" onchange="validatenum(this)" name="sickleavehrsperyear" value="'.$sickleavehrsperyear.'"'.INC_TEXTBOX.'>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAXIMUM_SICK_LEAVE_ALLOWED_TO_ACCRUE'].': </tr><td><input type="text" onchange="validatenum(this)" name="maxsickleave" value="'.$maxsickleave.'"'.INC_TEXTBOX.'>';
       echo '</table><table>';
       echo '<tr><th>'.$lang['STR_YEARS_BEFORE'].'<br>'.$lang['STR_THIS_PERIOD'].'</th><th>'.$lang['STR_VACATION'].'<br>'.$lang['STR_DAYS_YEAR'].'</th><th>'.$lang['STR_MAX_VACATION_DAYS'].'<br>'.$lang['STR_ALLOWED_TO_ACCRUE'].'</th><th>'.$lang['STR_DELETE'].'<br><input type="checkbox" checked></th></tr>';
       for ($i=1;$i<=$vaccount+2;$i++) {
             echo '<tr><td><input type="text" name="yrsbeforeaccrue'.$i.'" value="'.${"yrsbeforeaccrue".$i}.'" '.INC_TEXTBOX.'>';
             echo '<td><input type="text" name="vacdaysperyear'.$i.'"  onchange="validatenum(this)" value="'.${"vacdaysperyear".$i}.'" '.INC_TEXTBOX.'>';
             echo '<td><input type="text" name="maxaccrue'.$i.'" onchange="validatenum(this)" value="'.${"maxaccrue".$i}.'" '.INC_TEXTBOX.'>';
             echo '<input type="hidden" name="vacid'.$i.'" value="'.${"vacid".$i}.'">';
             echo '<input type="hidden" name="lastchangedate'.$i.'" value="'.${"lastchangedate".$i}.'">';
             echo '<td><input name="cancel'.$i.'" type="checkbox"></td></tr>';
       };
       echo '<input type="hidden" name="vaccount" value="'.$vaccount.'">';
       echo '<input type="hidden" name="lastchangedate" value="'.$lastchangedate.'">';
       echo '</table><br><input type="submit" name="save" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
       

       echo '</center>';
?>

<?php include('includes/footer.php'); ?>
