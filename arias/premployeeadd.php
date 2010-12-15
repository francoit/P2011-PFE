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
        echo texttitle($lang['STR_PAYROLL_EMPLOYEE_ADD']);
        echo '<center>';
        if ($employeeid&&$deduction) {
                $conn->Execute('update premployee set workmanscomprate='.sqlprep($workmanscomprate).', pensplanid1='.sqlprep($pensplanid1).', pensplandedamount1='.sqlprep($pensplandedamount1).', pensplanbase1='.sqlprep($pensplanbase1).', pensplanid2='.sqlprep($pensplanid2).', pensplandedamount2='.sqlprep($pensplandedamount2).', pensplanbase2='.sqlprep($pensplanbase2).', prdedgroupid='.sqlprep($prdedgroupid).' where id='.sqlprep($employeeid));
                echo textsuccess($lang['STR_EMPLOYEE_ADDED_SUCCESSFULLY']);
        } elseif ($employeeid&&$maritalstatus) {
                $conn->Execute('update premployee set maritalstatus='.sqlprep($maritalstatus).', federalexemptions='.sqlprep($federalexemptions).', extrafitperpayperiod='.sqlprep($extrafitperpayperiod).', extrafitbasedon='.sqlprep($extrafitbasedon).', eic='.sqlprep($eic).', prstateid='.sqlprep($prstateid).', stateexemptions='.sqlprep($stateexemptions).', extrasitperpayperiod='.sqlprep($extrasitperpayperiod).', extrasitbasedon='.sqlprep($extrasitbasedon).', prlocalid='.sqlprep($prlocal).', localexemptions='.sqlprep($localexemptions).', extralitperpayperiod='.sqlprep($extralitperpayperiod).', extralitbasedon='.sqlprep($extralitbasedon).', prcityid='.sqlprep($prcityid).', cityexemptions='.sqlprep($cityexemptions).', extracitperpayperiod='.sqlprep($extracitperpayperiod).', extracitbasedon='.sqlprep($extracitbased).' where id='.sqlprep($employeeid));
                echo texttitle($lang['STR_DEDUCTION_INFO']);
                $recordSet=&$conn->Execute('select firstname,lastname from premployee where id='.sqlprep($employeeid));
                echo '<form action="premployeeadd.php" method="post"><table><input type="hidden" name="deduction" value="1"><input type="hidden" name="employeeid" value="'.$employeeid.'">';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME'].':</td><td>'.$recordSet->fields[0].' '.$recordSet->fields[1].'</td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WORKMANS_COMP_RATE'].':</td><td><input type="text" name="workmanscomprate" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                $recordSet = &$conn->Execute('select id, name from prdedgroup where gencompanyid='.sqlprep($active_company).' order by name');
                while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                        $recordSet->MoveNext();
                };
                echo '</select></td></tr>';
                $recordSet=&$conn->CacheExecute(15,'select id,name from prpension where cancel=0 and gencompanyid='.sqlprep($active_company));
                if ($recordSet->EOF) {
                        echo '<input type="hidden" name="pensplanid1" value="0">';
                        echo '<input type="hidden" name="pensplandedamount1" value="0">';
                        echo '<input type="hidden" name="pensplanbase1" value="0">';
                        echo '<input type="hidden" name="pensplanid2" value="0">';
                        echo '<input type="hidden" name="pensplandedamount2" value="0">';
                        echo '<input type="hidden" name="pensplanbase2" value="0">';
                } else {
                        echo '<tr><th colspan="2" align="center">'.$lang['STR_PENSION_SAVINGS_PLAN'].'</th></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PLAN_ONE'].':</td><td>';
                        echo '<select name="pensplanid1"'.INC_TEXTBOX.'><option value="0">';
                        while (!$recordSet->EOF) {
                                echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1];
                                $recordSet->MoveNext();
                        };
                        echo '</td></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEDUCTION_AMOUNT_ONE'].':</td><td><input type="text" name="pensplandedamount1" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BASE_ONE'].':</td><td><select name="pensplanbase1"'.INC_TEXTBOX.'><option value="0">'.$lang['STR_FIXED_DOLLAR_AMOUNT'].'<option value="1">'.$lang['STR_PERCENTAGE_OF_PAY'].'</select></td></tr><tr><td>.</td></tr>';
                        $recordSet=&$conn->CacheExecute(15,'select id,name from prpension where cancel=0 and gencompanyid='.sqlprep($active_company));
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PLAN_TWO'].':</td><td>';
                        echo '<select name="pensplanid2"'.INC_TEXTBOX.'><option value="0">';
                        while (!$recordSet->EOF) {
                                echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1];
                                $recordSet->MoveNext();
                        };
                        echo '</td></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEDUCTION_AMOUNT_TWO'].':</td><td><input type="text" name="pensplandedamount2" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BASE_TWO'].':</td><td><select name="pensplanbase2"'.INC_TEXTBOX.'><option value="0">'.$lang['STR_FIXED_DOLLAR_AMOUNT'].'<option value="1">'.$lang['STR_PERCENTAGE_OF_PAY'].'</select></td></tr>';
                };
                echo '</table><input type="submit" value="'.$lang['STR_FINISH_ADD'].'"></form>';
        } elseif ($firstname&&$lastname&&$federalid) {
                checkpermissions('pay');
                if (!prcompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $firstname.' '.$lastname,$mailstop)) die(texterror($lang['STR_ERROR_ADDING_EMPLOYEE']));
                $recordSet=&$conn->SelectLimit('select id from company where companyname='.sqlprep($firstname.' '.$lastname).' order by entrydate desc',1);
                if (!$recordSet->EOF) $companyid=$recordSet->fields[0];
                $conn->Execute('insert into premployee (companyid,firstname,lastname,ssnumber,dateofbirth,hiredate,paytype,payperiod,payperperiod,glaccountid,vacationhoursaccrued,sickleavehoursaccrued,status,gencompanyid,entrydate,entryuserid,lastchangeuserid) values ('.sqlprep($companyid).','.sqlprep($firstname).','.sqlprep($lastname).','.sqlprep($federalid).','.sqlprep($dateofbirth).','.sqlprep($hiredate).','.sqlprep($paytype).','.sqlprep($payperiod).','.sqlprep($payperperiod).','.sqlprep($glaccountid).','.sqlprep($vacationhoursaccrued).','.sqlprep($sickleavehoursaccrued).',1,'.sqlprep($active_company).',NOW(),'.sqlprep($userid).','.sqlprep($userid).')');
                $recordSet=&$conn->SelectLimit('select id from premployee where firstname='.sqlprep($firstname).' and lastname='.sqlprep($lastname).' order by entrydate desc',1);
                if (!$recordSet->EOF) $employeeid=$recordSet->fields[0];
                $conn->Execute('insert into prpaychange (employeeid,oldpay,newpay,paystartdate,lastchangeuserid) values ('.sqlprep($employeeid).',0,'.sqlprep($payperperiod).','.sqlprep($hiredate).','.sqlprep($userid).')');
                echo texttitle($lang['STR_TAX']);
                echo '<form action="premployeeadd.php" method="post"><table><input type="hidden" name="employeeid" value="'.$employeeid.'">';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME'].':</td><td>'.$firstname.' '.$lastname.'</td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MARITAL_STATUS'].':</td><td><select name="maritalstatus"'.INC_TEXTBOX.'>';
                for ($i=1;$i<=4;$i++) echo '<option value="'.$i.'">'.prmaritalstatusfromid($i); //display marital status options options
                echo '</select>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FEDERAL_EXEMPTIONS'].':</td><td><input type="text" name="federalexemptions" onchange="validateint(this)" size="30" maxlength="2"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Extra FIT per pay period:</td><td><input type="text" name="extrafitperpayperiod" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_FIT_PER_PAY_PERIOD'].':</td><td><select name="extrafitbasedon"'.INC_TEXTBOX.'><option value="1">'.$lang['STR_PERCENTAGE_OF_WAGES'].'<option value="2">'.$lang['STR_FEDERAL_TAX_PERCENT'].'<option value="3">'.$lang['STR_DOLLAR_AMOUNT'].'</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EIC'].':</td><td><input type="checkbox" name="eic" value="1"'.INC_TEXTBOX.'></td></tr>';
                $recordSet=&$conn->Execute('select id,genstateid from prstate where cancel=0 and gencompanyid='.sqlprep($active_company));
                if ($recordSet->EOF) die(texterror($lang['STR_NO_STATE_TAXES_DEFINED']));
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><select name="prstateid"'.INC_TEXTBOX.'><option value="0">';
                while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'">'.statenamefromid($recordSet->fields[1]);
                        $recordSet->MoveNext();
                };
                echo '</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE_EXEMPTIONS'].':</td><td><input type="text" name="stateexemptions" onchange="validateint(this)" size="30" maxlength="2"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_SIT_PER_PAY_PERIOD'].':</td><td><input type="text" name="extrasitperpayperiod" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_SIT_BASED_ON'].':</td><td><select name="extrasitbasedon"'.INC_TEXTBOX.'><option value="1">'.$lang['STR_PERCENTAGE_OF_WAGES'].'<option value="2">'.$lang['STR_FEDERAL_TAX_PERCENT'].'<option value="3">'.$lang['STR_DOLLAR_AMOUNT'].'</select></td></tr>';
                $recordSet=&$conn->Execute('select id,name from prlocal where cancel=0 and gencompanyid='.sqlprep($active_company));
                if ($recordSet->EOF) die(texterror($lang['STR_NO_LOCAL_TAXES_DEFINED']));
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LOCAL'].':</td><td><select name="prlocalid"'.INC_TEXTBOX.'><option value="0">';
                while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1];
                        $recordSet->MoveNext();
                };
                echo '</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LOCAL_EXEMPTIONS'].':</td><td><input type="text" name="localexemptions" onchange="validateint(this)" size="30" maxlength="2"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_LIT_PER_PAY_PERIOD'].':</td><td><input type="text" name="extralitperpayperiod" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_LIT_BASED_ON'].':</td><td><select name="extralitbasedon"'.INC_TEXTBOX.'><option value="1">'.$lang['STR_PERCENTAGE_OF_WAGES'].'<option value="2">'.$lang['STR_FEDERAL_TAX_PERCENT'].'<option value="3">'.$lang['STR_DOLLAR_AMOUNT'].'</select></td></tr>';
                $recordSet=&$conn->Execute('select id,name from prcity where cancel=0 and gencompanyid='.sqlprep($active_company));
                if ($recordSet->EOF) die(texterror($lang['STR_NO_CITY_TAXES_DEFINED']));
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><select name="prcityid"'.INC_TEXTBOX.'><option value="0">';
                while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1];
                        $recordSet->MoveNext();
                };
                echo '</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY_EXEMPTIONS'].':</td><td><input type="text" name="cityexemptions" onchange="validateint(this)" size="30" maxlength="2"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_CIT_PER_PAY_PERIOD'].':</td><td><input type="text" name="extracitperpayperiod" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_CIT_BASED_ON'].':</td><td><select name="extracitbasedon"'.INC_TEXTBOX.'><option value="1">'.$lang['STR_PERCENTAGE_OF_WAGES'].'<option value="2">'.$lang['STR_FEDERAL_TAX_PERCENT'].'<option value="3">'.$lang['STR_DOLLAR_AMOUNT'].'</select></td></tr>';
                echo '</table><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
        } else {
                echo texttitle('General Info');
                echo '<form action="premployeeadd.php" method="post" name="mainform"><table>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME_FIRST_LAST'].':</td><td><input type="text" name="firstname" size="14" maxlength="30" value="'.$firstname.'"'.INC_TEXTBOX.'><input type="text" name="lastname" size="14" maxlength="30" value="'.$lastname.'"'.INC_TEXTBOX.'></td></tr>';
                formprcompanyadd();
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DATE_OF_BIRTH'].':</td><td><input type="text" name="dateofbirth" onchange="formatDate(this)" size="30" onchange="formatDate(this)" value="'.$dateofbirth.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.dateofbirth\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_HIRE_DATE'].':</td><td><input type="text" name="hiredate" onchange="formatDate(this)" size="30" onchange="formatDate(this)" value="'.$hiredate.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.hiredate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAYMENT_TYPE'].':</td><td><select name="paytype"'.INC_TEXTBOX.'><option value="0">'.$lang['STR_HOURLY'].'<option value="1">'.$lang['STR_SALARY'].'</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAY_PERIOD'].':</td><td><select name="payperiod"'.INC_TEXTBOX.'>';
                $recordSet=&$conn->Execute('select id,name from prperiod order by name');
                while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                        $recordSet->MoveNext();
                };
                echo '</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAY_PER_HOUR_PERIOD'].':</td><td><input type="text" name="payperperiod" onchange="validatenum(this)" size="30" maxlength="15" value="'.$payperperiod.'"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_ACCOUNT'].':</td><td><select name="glaccountid"'.INC_TEXTBOX.'>';
                $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
                while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
                        $recordSet->MoveNext();
                };
                echo '</td></tr></select>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VACATION_HOURS_ACCRUED'].':</td><td><input type="text" name="vacationhoursaccrued" onchange="validatenum(this)" size="30" maxlength="5"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SICK_LEAVE_HOURS_ACCRUED'].':</td><td><input type="text" name="sickleavehoursaccrued" onchange="validatenum(this)" size="30" maxlength="5"'.INC_TEXTBOX.'></td></tr>';
                echo '</table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';

                echo '</center>';
        };
?>
<?php include('includes/footer.php'); ?>
