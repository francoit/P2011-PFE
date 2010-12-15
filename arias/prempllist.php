<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
        echo '<center>';
        echo texttitle($companyname. $lang['STR_EMPLOYEE_LIST']);
        echo '<table border="1">';
        unset($perstr1);
        switch ($type) {
                case ('nap'): //name address phone list
                       $helpanchor=1; //set which part of help to go to
                       $recordSet=&$conn->Execute('select premployee.firstname,premployee.lastname,company.phone1,company.address1,company.address2,company.city,company.state,company.zip from premployee,company where premployee.companyid=company.id and premployee.cancel=0 and premployee.status=1 and premployee.gencompanyid='.sqlprep($active_company).' order by premployee.lastname,premployee.firstname');
                       if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_EMPLOYEES_FOUND']));
                       echo '<tr><th>'.$lang['STR_EMPLOYEE'].'</th><th>'.$lang['STR_PHONE_NUMBER'].'</th><th>'.$lang['STR_ADDRESS'].'</th></tr>';
                       while (!$recordSet->EOF) {
                               echo '<tr><td>'.$recordSet->fields[1].', '.$recordSet->fields[0].'</td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3];
                               if ($recordSet->fields[4]) echo '<br>'.$recordSet->fields[4];
                               echo '<br>'.$recordSet->fields[5].', '.$recordSet->fields[6].' '.$recordSet->fields[7].'</td></tr>';
                               $recordSet->MoveNext();
                       };
                       break;
                case ('phone'): //phone list
                       $helpanchor=2; //set which part of help to go to
                       $recordSet=&$conn->Execute('select premployee.firstname,premployee.lastname,company.phone1,company.phone1comment from premployee,company where premployee.companyid=company.id and premployee.cancel=0 and premployee.status=1 and premployee.gencompanyid='.sqlprep($active_company).' order by premployee.lastname,premployee.firstname');
                       if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_EMPLOYEES_FOUND']));
                       echo '<tr><th>'.$lang['STR_EMPLOYEE'].'</th><th>'.$lang['STR_PHONE'].'</th><th>'.$lang['STR_PHONE_COMMENT'].'</th></tr>';
                       while (!$recordSet->EOF) {
                               echo '<tr><td>'.$recordSet->fields[1].', '.$recordSet->fields[0].'</td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td></tr>';
                               $recordSet->MoveNext();
                       };
                       break;
                case ('bday'): //birthday list
                       $helpanchor=3; //set which part of help to go to
                       $recordSet=&$conn->Execute('select premployee.firstname,premployee.lastname,premployee.dateofbirth from premployee where premployee.cancel=0 and premployee.status=1 and premployee.gencompanyid='.sqlprep($active_company).' order by substring(dateofbirth,6,5),lastname,firstname');
                       if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_EMPLOYEES_FOUND']));
                       echo '<tr><th>'.$lang['STR_EMPLOYEE'].'</th><th>'.$lang['STR_BIRTHDAY'].'</th></tr>';
                       while (!$recordSet->EOF) {
                               echo '<tr><td>'.$recordSet->fields[1].', '.$recordSet->fields[0].'</td><td>'.$recordSet->fields[2].'</td></tr>';
                               $recordSet->MoveNext();
                       };
                       break;
                case ('gen'): //general info list
                       $helpanchor=4; //set which part of help to go to
                       $recordSet=&$conn->Execute('select premployee.firstname,premployee.lastname,premployee.maritalstatus,premployee.federalexemptions,premployee.stateexemptions,premployee.localexemptions,premployee.cityexemptions,premployee.paytype,prperiod.name,premployee.payperperiod from premployee left join prperiod on premployee.payperiod=prperiod.id where premployee.cancel=0 and premployee.status=1 and premployee.gencompanyid='.sqlprep($active_company).' order by substring(dateofbirth,6,5),lastname,firstname');
                       if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_EMPLOYEES_FOUND']));
                       echo '<tr><th rowspan="2">'.$lang['STR_EMPLOYEE'].'</th><th rowspan="2">'.$lang['STR_MARITAL_STATUS'].'</th><th colspan="4">'.$lang['STR_EXEMPTIONS'].'</th><th rowspan="2">'.$lang['STR_PAY_RATE'].'</th></tr><tr><th>'.$lang['STR_FED'].'</th><th>'.$lang['STR_STATE'].'</th><th>'.$lang['STR_LOCAL'].'</th><th>'.$lang['STR_CITY'].'</th></tr>';
                       while (!$recordSet->EOF) {
                               echo '<tr><td>'.$recordSet->fields[1].', '.$recordSet->fields[0].'</td><td>'.prmaritalstatusfromid($recordSet->fields[2]).'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[5].'</td><td>'.$recordSet->fields[6].'</td><td>';
                               if ($recordSet->fields[7]) { //if salary
                                 echo CURRENCY_SYMBOL.$recordSet->fields[9].'/'.$recordSet->fields[8];
                               } else { //if hourly
                                 echo CURRENCY_SYMBOL.$recordSet->fields[9].'/hr';
                               };
                               echo '</td></tr>';
                               $recordSet->MoveNext();
                       };
                       break;
                case ('hpw'): //hours and pay weekly list
                    $helpanchor=5; //set which part of help to go to
//                    $perstr1='extract(week from premplweek.periodenddate)';
                    $perstr1='week(premplweek.periodenddate)';
//                    $perstr2=$conn->Concat('extract(year from premplweek.periodenddate)','extract(quarter from premplweek.periodenddate)');
                    $perstr2=$conn->Concat('extract(year from premplweek.periodenddate)','quarter(premplweek.periodenddate)');
//                    $perstr3=$conn->Concat('extract(year from NOW())','extract(quarter from NOW())');
                    $perstr3=$conn->Concat('extract(year from NOW())','quarter(NOW())');
                    $perstr4='Week';
                    break;
                case ('hpm'): //hours and pay monthly list
                    $helpanchor=6; //set which part of help to go to
                    $perstr1='extract(month from premplweek.periodenddate)';
                    $perstr2='extract(year from premplweek.periodenddate)';
                    $perstr3='extract(year from NOW())';
                    $perstr4='Month';
                    break;
                case ('hpq'): //hours and pay quarterly list
                    $helpanchor=7; //set which part of help to go to
//                    $perstr1='extract(quarter from premplweek.periodenddate)';
                    $perstr1='quarter(premplweek.periodenddate)';
                    $perstr2='extract(year from premplweek.periodenddate)';
                    $perstr3='extract(year from NOW())';
                    $perstr4='Quarter';
                    break;
                };
                if ($perstr1) {
                       $recordSet=&$conn->Execute('select distinct '.$perstr1.', min(premplweek.periodbegindate), max(premplweek.periodenddate) from premplweekpaydetail, premplweek, premployee where ('.$perstr2.'='.$perstr3.') and premplweekpaydetail.premplweekid=premplweek.id and premplweek.employeeid=premployee.id group by '.$perstr1.' order by '.$perstr1);
                       if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_PAYROLL_FOUND_FOR_PERIOD']));
                       $pers=array();
                       $persbgname=array();
                       $persedname=array();
                       while (!$recordSet->EOF) {
                           $pers[]=$recordSet->fields[0];
                           $persbgname[]=$recordSet->fields[1];
                           $persedname[]=$recordSet->fields[2];
                           $recordSet->MoveNext();
                       };
                       $recordSet=&$conn->CacheExecute(10,'select premployee.id, '.$perstr1.', sum(premplweekpaydetail.qty), sum(premplweekpaydetail.amount), premployee.lastname, premployee.firstname from premplweekpaydetail, premplweek, premployee where ('.$perstr2.'='.$perstr3.') and premplweekpaydetail.premplweekid=premplweek.id and premplweek.employeeid=premployee.id group by premplweek.id order by premployee.lastname,premployee.firstname, '.$perstr1);
                       if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_EMPLOYEES_FOUND']));
                       unset($oldpremployeeid);
                       while (!$recordSet->EOF) {
                           ${"hours".$recordSet->fields[0]."per".$recordSet->fields[1]}+=$recordSet->fields[2];
                           ${"tothoursper".$recordSet->fields[1]}+=$recordSet->fields[2];
                           ${"pay".$recordSet->fields[0]."per".$recordSet->fields[1]}+=$recordSet->fields[3];
                           ${"totpayper".$recordSet->fields[1]}+=$recordSet->fields[3];
                           if ($oldpremployeeid==$recordSet->fields[0]) { //if same employee
                               if ($recordSet->fields[1]<>$oldperiod) $i++;
                           } else { //if new employee
                                $oldpremployeeid=$recordSet->fields[0];
                                $i=1;
                           };
                           $recordSet->MoveNext();
                       };
                       $recordSet=&$conn->CacheExecute(10,'select premployee.id, '.$perstr1.', sum(premplweekpaydetail.qty), sum(premplweekpaydetail.amount), premployee.lastname, premployee.firstname from premplweekpaydetail, premplweek, premployee where ('.$perstr2.'='.$perstr3.') and premplweekpaydetail.premplweekid=premplweek.id and premplweek.employeeid=premployee.id group by premplweek.id order by premployee.lastname,premployee.firstname, '.$perstr1);
                       if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_EMPLOYEES_FOUND']));
                       echo '<tr><th rowspan="2">'.$lang['STR_EMPLOYEE'].'</th>';
                       foreach ($pers as $key => $data) {
                           echo '<th colspan="2">'.$perstr4.' '.($key+1).'<br><font size="-2">('.current($persbgname).' thru '.current($persedname).')</font></th>';
                           next($persbgname);
                           next($persedname);
                       };
                       echo '</tr><tr>';
                       foreach ($pers as $data) echo '<th>'.$lang['STR_HOURS'].'</th><th>'.$lang['STR_PAY'].'</th>';
                       $oldpremployeeid=0;
                       while (!$recordSet->EOF) {
                           if ($oldpremployeeid<>$recordSet->fields[0]) {
                               echo '</tr><tr><td>'.$recordSet->fields[4].', '.$recordSet->fields[5].'</td>';
                               foreach ($pers as $data) echo '<td>'.num_format(${"hours".$recordSet->fields[0]."per".$data},0).'</td><td>'.CURRENCY_SYMBOL.num_format(${"pay".$recordSet->fields[0]."per".$data},2).'</td>';
                           };
                           $oldpremployeeid=$recordSet->fields[0];
                           $recordSet->MoveNext();
                       };
                       echo '</tr>';
                       echo '<tr><th>'.$lang['STR_TOTAL'].':</th>';
                       foreach ($pers as $data) {
                               echo '<th>'.num_format(${"tothoursper".$data},0).'</th><th>'.CURRENCY_SYMBOL.num_format(${"totpayper".$data},2).'</th>';
                       };
                       echo '</tr>';

                };
        echo '</table>';
        echo '</center>';
?>
<?php include('includes/footer.php'); ?>
