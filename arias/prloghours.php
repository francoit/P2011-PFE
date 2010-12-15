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
        echo texttitle($lang['STR_PAYROLL_LOG_HOURS']);
        if ($defaulthrsset&&$prperiodid&&$periodbegindate&&$periodenddate) {
          echo texttitle('<font size="-1">'.$lang['STR_LOG_HOURS'].' '.$periodbegindate.' - '.$periodenddate.'</font>');
          if ($premployeeid) { //insert logged hours
              checkpermissions('pay');
              $recordSet=&$conn->Execute('select minwagehr,shift2multiplier,shift3multiplier from prcompany where id='.sqlprep($active_company));
              $minwage=$recordSet->fields[0];
              $shift1mul=1;
              $shift2mul=$recordSet->fields[1];
              $shift3mul=$recordSet->fields[2];
              unset($emphours);
              unset($empamount);
              $recordSet=&$conn->CacheExecute(10,'select id,multiplier from prpaytype where gencompanyid='.sqlprep($active_company).' order by name');
              while (!$recordSet->EOF) { //calulate total hours worked and pay
                 $emphours+=${'hours'.$recordSet->fields[0]};
                 ${'rate'.$recordSet->fields[0]}*=${"shift".$shift."mul"}; //multiply rate by shift multiplier
                 $empamount+=${'hours'.$recordSet->fields[0]}*(${'rate'.$recordSet->fields[0]}*$recordSet->fields[1]);
                 $recordSet->MoveNext();
              };
              if ($emphours<>0) { //if hours is 0, then just skip it.
                  $empminwage=$empamount/$emphours;
                  if ($minwage>$empminwage) { //if min wage > hourly wage
                      $minwagepay=$emphours*$minwage;
                      if ($tips+$tipsaswages>=($minwagepay-$empamount)) { //if hourly pay + tips>= min wage
                          $tipsaswages2=$minwagepay-$empamount; //named tipsaswages2 so we don't take out old tipsaswages twicefrom tipspay
                          $tipspay=$tips+$tipspay-$tipsaswages2;
                          $tipsaswages=$tipsaswages2+$tipsaswages;
                      } else { //if hourly pay + tips < min wage
                          $tipsaswages=$tips+$tipsaswages;
                          $misctaxablepay+=($minwagepay-($empamount-$tipsaswages));
                      };
                  } else { //if hourly wage >= min wage
                      $tipspay=$tips;
                  };
                  if (!$premplweekid) {
                      $recordSet=&$conn->SelectLimit('select prstateid,prlocalid,prcityid from premployee where id='.sqlprep($premployeeid),1);
                      if ($conn->Execute('insert into premplweek (employeeid,periodbegindate,periodenddate,prperiodid,tipspay,tipsaswages,misctaxablepay,misctaxablecomment,miscnontaxablepay,miscnontaxablecomment,miscdeduction,miscdeductioncomment,prstateid,prlocalid,prcityid,entrydate,entryuserid,lastchangeuserid) values ('.sqlprep($premployeeid).','.sqlprep($periodbegindate).', '.sqlprep($periodenddate).', '.sqlprep($prperiodid).', '.sqlprep($tipspay).', '.sqlprep($tipsaswages).', '.sqlprep($misctaxablepay).', '.sqlprep($misctaxablepaycomment).', '.sqlprep($miscnontaxablepay).', '.sqlprep($miscnontaxablepaycomment).', '.sqlprep($miscdeduction).', '.sqlprep($miscdeductioncomment).', '.sqlprep($recordSet->fields[0]).', '.sqlprep($recordSet->fields[1]).', '.sqlprep($recordSet->fields[2]).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).')') === false) echo texterror('premplweek insert failed.');
                      $recordSet=&$conn->SelectLimit('select id from premplweek where employeeid='.sqlprep($premployeeid).' order by entrydate desc',1);
                      $premplweekid = $recordSet->fields[0];
                  };
                  $recordSet=&$conn->CacheExecute(10,'select id,multiplier from prpaytype where gencompanyid='.sqlprep($active_company).' order by name');
                  while (!$recordSet->EOF) {
                     if (${'hours'.$recordSet->fields[0]}&&${'rate'.$recordSet->fields[0]}) $conn->Execute('insert into premplweekpaydetail (premplweekid,prpaytypeid,prbendedid,qty,rate,amount,glaccountid) values ('.sqlprep($premplweekid).','.sqlprep($recordSet->fields[0]).', 0, '.sqlprep(${'hours'.$recordSet->fields[0]}).', '.sqlprep(${'rate'.$recordSet->fields[0]}).', '.sqlprep(${'hours'.$recordSet->fields[0]}*(${'rate'.$recordSet->fields[0]}*$recordSet->fields[1])).', '.sqlprep(${'glaccountid'.$recordSet->fields[0]}).')');
                     $recordSet->MoveNext();
                  };
              };
              echo textsuccess($lang['STR_EMPLOYEE_HOURS_ADDED_SUCCESSFULLY']);
              if ($submit=="Next Employee") $position++; //increment employee counter
          };
          if (!isset($position)) $position=0;
          $recordSet=&$conn->SelectLimit('select id,lastname,firstname,paytype,payperperiod,glaccountid,vacationhoursaccrued,sickleavehoursaccrued from premployee where payperiod='.$prperiodid.' and status=1 and (terminatedate=\'0000-00-00\' or terminatedate>'.$periodbegindate.') and gencompanyid='.sqlprep($active_company).' order by lastname,firstname',1,$position);
          if ($recordSet->EOF) die(textsuccess($lang['STR_ALL_EMPLOYEES_ENTERED']));
          $deftotal=$recordSet->fields[4];
          $vachours=$recordSet->fields[6];
          $sichours=$recordSet->fields[7];
          echo '<form method="post" name="mainform" action="prloghours.php"><table><input type="hidden" name="prperiodid" value="'.$prperiodid.'"><input type="hidden" name="defaulthrsset" value="1"><input type="hidden" name="periodbegindate" value="'.$periodbegindate.'"><input type="hidden" name="periodenddate" value="'.$periodenddate.'"><input type="hidden" name="nonprintable" value="1">';
          if ($submit=="Enter More Hours") {
              echo '<input type="hidden" name="tipspay" value="'.$tipspay.'">';
              echo '<input type="hidden" name="tipsaswages" value="'.$tipsaswages.'">';
              echo '<input type="hidden" name="premplweekid" value="'.$premplweekid.'">';
          };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMPLOYEE'].': </td><td colspan="4">'.$recordSet->fields[1].', '.$recordSet->fields[2].'<input type="hidden" name="premployeeid" value="'.$recordSet->fields[0].'"><input type="hidden" name="position" value="'.$position.'"></td></tr>';
          echo '<tr><th>'.$lang['STR_PAY_TYPE'].'</th><th>'.$lang['STR_HOURS'].'</th><th>'.$lang['STR_RATE'].'</th><th>'.$lang['STR_SHIFT'].'</th><th>'.$lang['STR_TOTAL'].'</th><th>'.$lang['STR_GL_ACCOUNT'].'</th></tr>';
          $i = array();
          $defglaccount=$recordSet->fields[5];
          if ($recordSet->fields[3]==0) { //if hourly
              $defrate=$recordSet->fields[4];
          } else { //if salary
              $recordSet=&$conn->Execute('select id from prpaytype where gencompanyid='.sqlprep($active_company).' order by name');
              while (!$recordSet->EOF) {
                      $maxhours+=${'defhours'.$recordSet->fields[0]};
                      $recordSet->MoveNext();
              };
              $recordSet=&$conn->Execute('select id from prpaytype where gencompanyid='.sqlprep($active_company).' order by name');
              while (!$recordSet->EOF) {
                      if ($maxhours>0) ${'deftotal'.$recordSet->fields[0]}=$deftotal*(${'defhours'.$recordSet->fields[0]}/$maxhours);
                      $recordSet->MoveNext();
              };
          };
          $recordSet=&$conn->Execute('select id,name,multiplier,vacation,sick from prpaytype where gencompanyid='.sqlprep($active_company).' order by name');
          while (!$recordSet->EOF) {
              echo '<script language="JavaScript">'."\n";
              echo 'function vac'.$recordSet->fields[0].'() {'."\n";
              if ($recordSet->fields[3]) { //if pay type is a vacation
                    echo '   if (document.mainform.hours'.$recordSet->fields[0].'.value > '.intval($vachours).') {'."\n";
                    echo '       document.mainform.hours'.$recordSet->fields[0].'.value='.intval($vachours)."\n";
                    echo '   }'."\n";
              };
              echo '}'."\n";
              echo 'function sic'.$recordSet->fields[0].'() {'."\n";
              if ($recordSet->fields[4]) { //if pay type is a sick leave
                    echo '   if (document.mainform.hours'.$recordSet->fields[0].'.value > '.intval($sichours).') {'."\n";
                    echo '       document.mainform.hours'.$recordSet->fields[0].'.value='.intval($sichours)."\n";
                    echo '   }'."\n";
              };
              echo '}'."\n";
              echo '</script>'."\n";
              $i[]=$recordSet->fields[0];
              ${'mult'.$recordSet->fields[0]}=$recordSet->fields[2];
              $multstr='';
              if (num_format($recordSet->fields[2],0)<>1) $multstr=' <font size="-1">(x'.num_format($recordSet->fields[2],0).')</font>'; //display multiplier if not 1
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$recordSet->fields[1].$multstr.':</td><td><input type="text" name="hours'.$recordSet->fields[0].'" value="'.${'defhours'.$recordSet->fields[0]}.'" size="5" maxlength="4"'.INC_TEXTBOX.' onchange="validatenum(this); calc(); vac'.$recordSet->fields[0].'(); sic'.$recordSet->fields[0].'();" onclick="calc()"><input type="hidden" name="defhours'.$recordSet->fields[0].'" value="'.${'defhours'.$recordSet->fields[0]}.'"></td><td><input type="text" name="rate'.$recordSet->fields[0].'" value="'.$defrate.'" size="6" maxlength="6"'.INC_TEXTBOX.' onchange="validatenum(this); calc(); vac'.$recordSet->fields[0].'(); sic'.$recordSet->fields[0].'();" onclick="calc()"></td><td><input type="text" disabled name="shift'.$recordSet->fields[0].'" value="x1" size="6" maxlength="6"></td><td><input type="text" name="total'.$recordSet->fields[0].'" value="'.${'deftotal'.$recordSet->fields[0]}.'" size="7" maxlength="7"'.INC_TEXTBOX.' onchange="validatenum(this); calc(); vac'.$recordSet->fields[0].'(); sic'.$recordSet->fields[0].'();" onclick="calc()"></td><td><select name="glaccountid'.$recordSet->fields[0].'"'.INC_TEXTBOX.'>';
              $recordSet2=&$conn->Execute('select id,name,description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') and accounttypeid>=70 and accounttypeid<=80');
              while (!$recordSet2->EOF) {
                      echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$defglaccount,' selected').'>'.$recordSet2->fields[1].' - '.$recordSet2->fields[2];
                      $recordSet2->MoveNext();
              };
              echo '</select></td></tr>';
              $recordSet->MoveNext();
          };
          echo '<script language="JavaScript">'."\n";
          echo 'function calc() {'."\n";
          foreach ($i as $data) {
              echo '   if (document.mainform.hours'.$data.'.value != \'\' && document.mainform.rate'.$data.'.value != \'\') {'."\n";
              echo '       document.mainform.total'.$data.'.value=document.mainform.hours'.$data.'.value*document.mainform.rate'.$data.'.value*'.${'mult'.$data}."\n";
              echo '   } else if (document.mainform.rate'.$data.'.value != \'\' && document.mainform.total'.$data.'.value > 0) {'."\n";
              echo '       document.mainform.hours'.$data.'.value=document.mainform.total'.$data.'.value/(document.mainform.rate'.$data.'.value*'.${'mult'.$data}.")\n";
              echo '   } else if (document.mainform.hours'.$data.'.value > 0 && document.mainform.total'.$data.'.value != \'\') {'."\n";
              echo '       document.mainform.rate'.$data.'.value=document.mainform.total'.$data.'.value/(document.mainform.hours'.$data.'.value*'.${'mult'.$data}.")\n";
              echo '   }'."\n";
          };
          echo '}'."\n";
          echo '</script>'."\n";
          $recordSet=&$conn->Execute('select minwagehr,shift2multiplier,shift3multiplier from prcompany where id='.sqlprep($active_company));
          echo '<input type="hidden" name="shiftmul1" value="x1">';
          echo '<input type="hidden" name="shiftmul2" value="x'.checkdec($recordSet->fields[1],0).'">';
          echo '<input type="hidden" name="shiftmul3" value="x'.checkdec($recordSet->fields[2],0).'">';

          echo '<script language="JavaScript">'."\n";
          echo 'function shiftcalc() {'."\n";
          foreach ($i as $data) {
              echo '   document.mainform.shift'.$data.'.disabled = false;'."\n";
              echo '   if (document.mainform.shift.value == 1) {'."\n";
              echo '       document.mainform.shift'.$data.'.value=document.mainform.shiftmul1.value;'."\n";
              echo '   } else if (document.mainform.shift.value == 2) {'."\n";
              echo '       document.mainform.shift'.$data.'.value=document.mainform.shiftmul2.value;'."\n";
              echo '   } else if (document.mainform.shift.value == 3) {'."\n";
              echo '       document.mainform.shift'.$data.'.value=document.mainform.shiftmul3.value;'."\n";
              echo '   }'."\n";
              echo '   document.mainform.shift'.$data.'.disabled = true;';
          };
          echo '}'."\n";
          echo '</script>'."\n";
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHIFT'].': </td><td colspan="4"><select name="shift"'.INC_TEXTBOX.' onchange="shiftcalc()"><option value="1" selected>First<option value="2">Second<option value="3">Third</select></td>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MISC_TAXABLE_PAY'].': </td><td colspan="4"><input type="text" name="misctaxablepay" onchange="validatenum(this)" size="10" maxlength="10" value="0"'.INC_TEXTBOX.'></td>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMMENT'].':</td><td colspan="4"><input type="text" name="misctaxablepaycomment" size="30" maxlength="30" '.INC_TEXTBOX.'></td>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MISC_NON_TAXABLE_PAY'].': </td><td colspan="4"><input type="text" name="miscnontaxablepay" onchange="validatenum(this)" size="10" maxlength="10" value="0"'.INC_TEXTBOX.'></td>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMMENT'].':</td><td colspan="4"><input type="text" name="miscnontaxablepaycomment" size="30" maxlength="30" '.INC_TEXTBOX.'></td>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MISC_DEDUCTION'].': </td><td colspan="4"><input type="text" name="miscdeduction" onchange="validatenum(this)" size="10" maxlength="10" value="0"'.INC_TEXTBOX.'></td>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMMENT'].':</td><td colspan="4"><input type="text" name="miscdeductioncomment" size="30" maxlength="30" '.INC_TEXTBOX.'></td>';
          echo '</table><input type="submit" name="submit" value="'.$lang['STR_NEXT_EMPLOYEE'].'" onmouseover="calc()" onkeypress="calc()"><input type="submit" name="submit" value="Enter More Hours" onmouseover="calc()" onkeypress="calc()">';
          echo '</form>';
        } elseif ($prperiodid&&$periodbegindate&&$periodenddate) {
          echo texttitle('<font size="-1">'.$lang['STR_SET_DEFAULTS'].' '.$periodbegindate.' - '.$periodenddate.'</font>');
          echo '<form method="post" name="mainform" action="prloghours.php"><table><input type="hidden" name="prperiodid" value="'.$prperiodid.'"><input type="hidden" name="defaulthrsset" value="1"><input type="hidden" name="periodbegindate" value="'.$periodbegindate.'"><input type="hidden" name="periodenddate" value="'.$periodenddate.'">';
          $recordSet=&$conn->Execute('select id,name from prpaytype where gencompanyid='.sqlprep($active_company).' order by name');
          while (!$recordSet->EOF) {
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$recordSet->fields[1].':</td><td><input type="text" name="defhours'.$recordSet->fields[0].'" onchange="validatenum(this)" size="5" maxlength="4" value="0"'.INC_TEXTBOX.'></td></tr>';
              $recordSet->MoveNext();
          };
          echo '</table><input type="submit" value="'.$lang['STR_CONTINUE'].'">';
          echo '</form>';
        } elseif ($prperiodid) {
          echo texttitle($lang['STR_SELECT_PAY_PERIOD_DATES']);
          $recordSet=&$conn->Execute('select name from prperiod where id='.sqlprep($prperiodid));
          if ($recordSet->EOF) die(texterror($lang['STR_PERIOD_NOT_FOUND']));
          echo texttitle('<font size="-1">'.$lang['STR_PERIOD'].' '.$recordSet->fields[0].'</font>');
          $timestamp =  time();
          $date_time_array =  getdate($timestamp);
          $hours =  $date_time_array["hours"];
          $minutes =  $date_time_array["minutes"];
          $seconds =  $date_time_array["seconds"];
          $month =  $date_time_array["mon"];
          $day =  $date_time_array["mday"];
          $year =  $date_time_array["year"];
          $recordSet=&$conn->Execute('select numperyear from prperiod where id='.sqlprep($prperiodid));
          if (!$recordSet->EOF) $numdays=num_format(365/$recordSet->fields[0],0);
//echo 'select premplweek.periodbegindate, premplweek.periodenddate from premplweek,premployee where premplweek.employeeid=premployee.id and premployee.gencompanyid='.sqlprep($active_company).' and premplweek.prperiodid='.sqlprep($prperiodid).' order by premplweek.periodenddate desc';
          $recordSet=&$conn->SelectLimit('select premplweek.periodbegindate, premplweek.periodenddate from premplweek,premployee where premplweek.employeeid=premployee.id and premployee.gencompanyid='.sqlprep($active_company).' and premplweek.prperiodid='.sqlprep($prperiodid).' order by premplweek.periodenddate desc',1);
          if (!$recordSet->EOF) {
                    $lastperiodenddate=$recordSet->fields[1];
                   $timestamp =  mktime($hour, $minute, $second, substr($lastperiodenddate,5,2), substr($lastperiodenddate,8,2)+1, substr($lastperiodenddate,0,4));
                    $periodbegindate=date("Y-m-d", $timestamp);
                    $timestamp =  mktime($hour, $minute, $second, substr($lastperiodenddate,5,2), substr($lastperiodenddate,8,2)+1+$numdays, substr($lastperiodenddate,0,4));
                    $periodenddate=date("Y-m-d", $timestamp);
          } else {
                    $timestamp =  mktime($hour, $minute, $second, $month, $day-$numdays, $year);
                    $periodbegindate=date("Y-m-d", $timestamp);
                    $timestamp =  mktime($hour, $minute, $second, $month, $day, $year);
                    $periodenddate=date("Y-m-d", $timestamp);
          };
          echo '<form method="post" name="mainform" action="prloghours.php"><input type="hidden" name="prperiodid" value="'.$prperiodid.'"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PERIOD_BEGIN_DATE'].':</td><td><input type="text" name="periodbegindate" onchange="formatDate(this)" value="'.$periodbegindate.'" size="30" maxlength="10"'.INC_TEXTBOX.'>maguma</td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PERIOD_END_DATE'].':</td><td><input type="text" name="periodenddate" onchange="formatDate(this)" value="'.$periodenddate.'" size="30" maxlength="10"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.periodenddate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr></table><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
        } else {
          echo texttitle('<font size="-1">'.$lang['STR_SELECT_PAY_PERIOD'].'</font>');
          echo '<form method="post" name="mainform" action="prloghours.php"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PERIOD'].':</td><td><select name="prperiodid"'.INC_TEXTBOX.'>';
          $recordSet=&$conn->Execute('select id,name from prperiod order by name');
          while (!$recordSet->EOF) {
              echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
              $recordSet->MoveNext();
          };
          echo '</select></td></tr></table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'">';
          echo '</form>';

          echo '</center>';
        };
?>
<?php include('includes/footer.php'); ?>
