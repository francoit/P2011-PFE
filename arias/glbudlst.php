<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
    $timestamp =  time();
    $date_time_array =  getdate($timestamp);
    $hours =  $date_time_array["hours"];
    $minutes =  $date_time_array["minutes"];
    $seconds =  $date_time_array["seconds"];
    $month =  $date_time_array["mon"];
    $day =  $date_time_array["mday"];
    $year =  $date_time_array["year"];
    $timestamp =  mktime($hour, $minute, $second, $month, 1, $year);
    $beginyear=date("Y", $timestamp);
    $bgdatemonth=date("m", $timestamp);
    $timestamp =  mktime($hour, $minute, $second, $month, 1, $year+1);
    $datenextyear=date("Y", $timestamp);

    echo texttitle($lang['STR_BUDGET_LIST_BY_ACCOUNT_FOR'] .$companyname);
    $recordSet=&$conn->Execute('select id, name,description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') and accounttypeid>49 order by name');
    if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_BUDGETS_FOUND']));
    $firsttime=1;
    echo '<table border="1">';
    while (!$recordSet->EOF) { //read account name and description for display
          $glaccountname=$recordSet->fields[1];
          $glaccountdesc=$recordSet->fields[2];
          $glaccountid=$recordSet->fields[0];
          for ($curmonth=1;$curmonth<25;$curmonth++) {
                ${"month".$curmonth}=0;
          };
          $recordSet1 = &$conn->Execute('select id,glaccountid,companyid,budgetyear,jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,decm from glbudgets where budgetyear='.sqlprep($beginyear).' and glaccountid='.sqlprep($glaccountid).' and companyid='.sqlprep($active_company));
          if (!$recordSet1->EOF) { //read data now for existing budget info
               for ($curmonth=1;$curmonth<=12;$curmonth++) { //read monthly data
                    ${"month".$curmonth}=$recordSet1->fields[$curmonth+3];
                    ${"year".$curmonth}=$beginyear;
               };
          };
          $recordSet1 = &$conn->Execute('select id,glaccountid,companyid,budgetyear,jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,decm from glbudgets where budgetyear='.sqlprep($datenextyear).' and glaccountid='.sqlprep($glaccountid).' and companyid='.sqlprep($active_company));
          if (!$recordSet1->EOF) { //read data now for existing budget info from next year
               for ($curmonth=13;$curmonth<=24;$curmonth++) { //read monthly data
                    ${"month".$curmonth}=$recordSet1->fields[$curmonth+3-12];
                    ${"year".$curmonth}=$datenextyear;
               };
          };
          if ($firsttime) {
                    echo '<tr><th>'.$lang['STR_ACCOUNT'].'</th></font>';
                    echo '<th>'.$lang['STR_DESCRIPTION'].'</th></font>';
                    for ($mymonth=1; $mymonth<=24;$mymonth++) {
                       $moname=num2month($mymonth);
                       if ($mymonth>=$bgdatemonth&&$mymonth<=$bgdatemonth+12) echo '<th><nobr>'.$moname.'  '.${"year".$mymonth}.'</nobr></th>';
                    };
                    echo '</tr>';
                    $firsttime=0;
          };
          echo '<tr><td>'.$glaccountname.'</td><td>'.$glaccountdesc.'</td>';
          for ($curmonth=1;$curmonth<=24;$curmonth++) {
                 if ($curmonth>=$bgdatemonth&&$curmonth<=$bgdatemonth+12) echo'<td><nobr>'.${"month".$curmonth}.'</nobr></td>';
          };
          echo '</tr>';
          $recordSet->MoveNext();
     };
     echo '</table>';
     
     echo '</center>';
 ?>

<?php include('includes/footer.php'); ?>
