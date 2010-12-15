<?php include('includes/main.php'); ?>
<?php include('includes/glfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
     function fillform() { //prefills budget form
<?php          for ($i=$beginmonth; $i<=$beginmonth+12; $i++) echo '          if (eval(document.mainform.month'.intval($i).'.value)==0) {  document.mainform.month'.intval($i).'.value = document.mainform.month'.intval($beginmonth).'.value }'."\n"; ?>
     }
</script>
<?
     echo texttitle($lang['STR_BUDGET_ADD_UPDATE']);
     echo '<center>';
     if (!$beginyear) {
          $timestamp =  time();
          $date_time_array =  getdate($timestamp);
          $hours =  $date_time_array["hours"];
          $minutes =  $date_time_array["minutes"];
          $seconds =  $date_time_array["seconds"];
          $month =  $date_time_array["mon"];
          $day =  $date_time_array["mday"];
          $year =  $date_time_array["year"];
          $timestamp =  mktime($hour, $minute, $second, $month, 1, $year);
          $bgdateyear=date("Y", $timestamp);
          $bgdatemonth=date("m", $timestamp);
          $timestamp =  mktime($hour, $minute, $second, $month, 1, $year+1);
          $datenextyear=date("Y", $timestamp);
          echo '<form action="glbudadd.php" method="post" name="mainform"><table>';
          echo '<tr><td align="right">'.$lang['STR_UPDATE_BEGIN_PERIOD_YEAR'].':</td><td><input type="text" name="beginyear" onchange="validateint(this)" value='.$bgdateyear.' size="14" maxlength="4"></td></tr><tr><td align="right">Month</td><td><input type="text" name="beginmonth" value='.$bgdatemonth.' size="14" maxlength="4"></td><input type="hidden" name="datenextyear" value='.$datenextyear.'></tr>';
          echo '<tr><td>'.$lang['STR_GENERAL_LEDGER_ACCOUNT_TO_BUDGET'].':</td><td><select name="glaccountid">';
          $recordSet = &$conn->Execute('select id, name,description from glaccount where accounttypeid>49 and (companyid=0 or companyid='.$active_company.') order by name');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr></table><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
     } elseif (!$binsert) {
          //now display account name & number at top
          $recordSet=&$conn->Execute('select id, name,description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') and  id='.$glaccountid);
          if (!$recordSet->EOF) {
               //read account name and description for display
               $glaccountname=$recordSet->fields[1];
               $glaccountdesc=$recordSet->fields[2];
          };
          echo '<form action="glbudadd.php" method="post" name="mainform"><table border=1><input type="hidden" name="binsert" value="1"><input type="hidden" name="datenextyear" value="'.$datenextyear.'">';
          echo '<input type="hidden" name="beginyear" value="'.$beginyear.'">';
          echo '<tr><th colspan="2">'.$lang['STR_GENERAL_LEDGER_ACCOUNT'].': '.$glaccountname." - ".$glaccountdesc.'</td><tr>';
          echo '<input type="hidden" name="glaccountid" value="'.$glaccountid.'">';

          //read budget year one  (this year)
          $recordSet = &$conn->Execute('select id,glaccountid,companyid,budgetyear,jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,decm,lastchangedate from glbudgets where budgetyear='.$beginyear.' and glaccountid='.$glaccountid.' and companyid='.$active_company);
          if (!$recordSet->EOF) {
               //read data now for existing budget info
               for ($curmonth=1;$curmonth<=12;$curmonth++) {
                    //read monthly data
                    ${"month".$curmonth}=$recordSet->fields[$curmonth+3];
                    ${"year".$curmonth}=$beginyear;
               };
          } else {
               //did not find record, so create a new one
               checkpermissions('gl');
               if ($conn->Execute('insert into glbudgets (glaccountid,companyid,budgetyear) VALUES ('.$glaccountid.",".$active_company.",".$beginyear.')') === false) die(texterror("Error inserting budget info for '.$beginyear"));
               $recordSet = &$conn->Execute('select id,glaccountid,companyid,budgetyear,jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,decm,lastchangedate from glbudgets where budgetyear='.$beginyear.' and glaccountid='.$glaccountid.' and companyid='.$active_company);
               if (!$recordSet->EOF) {
                     //read data now for existing budget info
                     for ($curmonth=1;$curmonth<=12;$curmonth++) {
                           //read monthly data
                           ${"month".$curmonth}=$recordSet->fields[$curmonth+3];
                           ${"year".$curmonth}=$beginyear;
                     };
               };
          };

          //read budget from year two (next year)
          $recordSet = &$conn->Execute('select id,glaccountid,companyid,budgetyear,jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,decm,lastchangedate  from glbudgets where budgetyear='.$datenextyear.' and glaccountid='.$glaccountid.' and companyid='.$active_company);
          if (!$recordSet->EOF) {
               //read data now for existing budget info from next year
               for ($curmonth=13;$curmonth<=24;$curmonth++) {
                    //read monthly data
                    ${"month".$curmonth}=$recordSet->fields[$curmonth+3-12];
                    ${"year".$curmonth}=$datenextyear;
               };
               $lastchangedate=$recordSet->fields[16];
          } else {
               //did not find record, so create a new one
               checkpermissions('gl');
               if ($conn->Execute('insert into glbudgets (glaccountid,companyid,budgetyear) VALUES ('.$glaccountid.",".$active_company.",".$datenextyear.')') === false) die(texterror("Error inserting budget info for ".$datenextyear));
               $recordSet = &$conn->Execute('select id,glaccountid,companyid,budgetyear,jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,decm,lastchangedate from glbudgets where budgetyear='.$beginyear.' and glaccountid='.$glaccountid.' and companyid='.$active_company);
               if (!$recordSet->EOF) {
                     //read data now for existing budget info
                     for ($curmonth=13;$curmonth<25;$curmonth++) { //read monthly data
                           ${"month".$curmonth}=$recordSet->fields[$curmonth+3-12];
                           ${"year".$curmonth}=$beginyear;
                     };
               };
               $lastchangedate=$recordSet->fields[16];
          };
          echo '<tr><th>'.$lang['STR_MONTH_YEAR'].'</th><th>'.$lang['STR_BUDGET_AMOUNT'].'</th></tr>';
          for ($curmonth=1;$curmonth<=24;$curmonth++) {
               if ($curmonth>=$beginmonth&&$curmonth<$beginmonth+12) {
               if ($curmonth==$beginmonth) {
                          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.month2monthlong(num2month($curmonth)).' - '.${"year".$curmonth}.'</td><td><input onblur="fillform()" type="text" name="month'.$curmonth.'" onchange="validatenum(this)" value="'.${"month".$curmonth}.'"></td></tr>';
               } else {
                          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.month2monthlong(num2month($curmonth)).' - '.${"year".$curmonth}.'</td><td><input type="text" name="month'.$curmonth.'" onchange="validatenum(this)" value='.${"month".$curmonth}.'></td></tr>';
               };
                } else {
                     echo '<input type="hidden" name="month'.$curmonth.'" value="'.${"moth".$curmonth}.'">';
                     echo '<input type="hidden" name="lastchangedate" value="'.$lastchangedate.'">';
                };
           };
           echo '</table><input type="submit" value="'.$lang['STR_UPDATE'].'"></form>';
      } else {       //update files now for both years
          $recordSet=&$conn->Execute('select count(*) from glbudgets where budgetyear='.sqlprep($datenextyear).' and glaccountid='.sqlprep($glaccountid).' and companyid='.sqlprep($active_company).' and lastchangedate='.sqlprep($lastchangedate));
          if (!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    $recordSet=&$conn->Execute('select lastchangedate, lastchangeuserid from glbudgets  where budgetyear='.sqlprep($datenextyear).' and glaccountid='.sqlprep($glaccountid).' and companyid='.sqlprep($active_company));
                    if (!$recordSet->EOF) {
                         $lastchangeuserid=$recordSet->fields[1];
                         $lastchangedate=$recordSet->fields[0];
                         echo texterror($lang['STR_COULD_NOT_UPDATE_BECAUSE'] .getname($lastchangeuserid)." made changes ".substr($lastchangedate,1,2)."/".substr($lastchangedate,3,2)."/".substr($lastchangedate,5,4). $lang['STR_TO_THIS_SAME_GL_BUDGET_ACCOUNT_WHILE_YOU_WERE_WORKING']);
                    };
               } else {
                 checkpermissions('gl');
                 $query='update glbudgets set ';
                 for ($curmonth=1;$curmonth<=12;$curmonth++) {
                       if (${"month".$curmonth}==0) ${"month".$curmonth}=0;
                       $query.=" ".num2month($curmonth, 1)."=".sqlprep(${"month".$curmonth});
                       if ($curmonth<12) $query.=",";
                 };
                 $query.=", lastchangeuserid=".sqlprep($userid).' where budgetyear='.sqlprep($beginyear).' and glaccountid='.sqlprep($glaccountid).' and companyid='.sqlprep($active_company);
                 if ($conn->Execute($query) === false) {
                            echo texterror($lang['STR_ERROR_UPDATING_BUDGET_FOR_YEAR'] .$beginyear);
                 } else {
                            echo textsuccess($lang['STR_BUDGET_FOR_YEAR'] .$beginyear. $lang['STR_UPDATED_SUCCESSFULLY']);
                            $query='update glbudgets set';
                            for ($curmonth=13;$curmonth<=24;$curmonth++) {
                                  if (${"month".$curmonth}==0) ${"month".$curmonth}=0;
                                  $query.=" ".num2month($curmonth, 1).'='.sqlprep(${"month".$curmonth});
                                  if ($curmonth<24) $query.=",";
                            };
                            $query.=", lastchangeuserid=".sqlprep($userid).' where budgetyear='.sqlprep($datenextyear).' and glaccountid='.sqlprep($glaccountid).' and companyid='.sqlprep($active_company);
                            if ($conn->Execute($query) === false) {
                                 echo texterror($lang['STR_ERROR_UPDATING_BUDGET_FOR_YEAR'] .$datenextyear);
                            } else {
                                 echo textsuccess($lang['STR_BUDGET_FOR_YEAR'] .$datenextyear. $lang['STR_UPDATED_SUCCESSFULLY']);
                            };
                 };
             };
          };
     };
     
     echo '<center>';
?>

<?php include('includes/footer.php'); ?>
