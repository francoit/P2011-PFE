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
        echo texttitle($lang['STR_PAYROLL_EMPLOYEE_UPDATE']);
        if ($employeeid&&$terminate) {
                checkpermissions('pay');
                $conn->Execute('update premployee set terminatedate=NOW() where id='.sqlprep($employeeid));
                echo textsuccess($lang['STR_EMPLOYEE_TERMINATED_SUCCESSFULLY']);
                unset($employeeid);
        };
        if ($employeeid&&isset($layoff)) {
                checkpermissions('pay');
                $conn->Execute('update premployee set status='.intval($layoff).' where id='.sqlprep($employeeid));
                echo textsuccess($lang['STR_EMPLOYEE_ACTIVE_STATUS_CHANGED_SUCCESSFULLY']);
        };
        if ($employeeid&&$deduction) {
                $conn->Execute('update premployee set workmanscomprate='.sqlprep($workmanscomprate).', pensplanid1='.sqlprep($pensplanid1).', pensplandedamount1='.sqlprep($pensplandedamount1).', pensplanbase1='.sqlprep($pensplanbase1).', pensplanid2='.sqlprep($pensplanid2).', pensplandedamount2='.sqlprep($pensplandedamount2).', pensplanbase2='.sqlprep($pensplanbase2).', prdedgroupid='.sqlprep($prdedgroupid).' where id='.sqlprep($employeeid));
                echo textsuccess($lang['STR_EMPLOYEE_UPDATED_SUCCESSFULLY']);
        } elseif ($employeeid&&$maritalstatus) {
                $conn->Execute('update premployee set maritalstatus='.sqlprep($maritalstatus).', federalexemptions='.sqlprep($federalexemptions).', extrafitperpayperiod='.sqlprep($extrafitperpayperiod).', extrafitbasedon='.sqlprep($extrafitbasedon).', eic='.sqlprep($eic).', prstateid='.sqlprep($prstateid).', stateexemptions='.sqlprep($stateexemptions).', extrasitperpayperiod='.sqlprep($extrasitperpayperiod).', extrasitbasedon='.sqlprep($extrasitbasedon).', prlocalid='.sqlprep($prlocal).', localexemptions='.sqlprep($localexemptions).', extralitperpayperiod='.sqlprep($extralitperpayperiod).', extralitbasedon='.sqlprep($extralitbasedon).', prcityid='.sqlprep($prcityid).', cityexemptions='.sqlprep($cityexemptions).', extracitperpayperiod='.sqlprep($extracitperpayperiod).', extracitbasedon='.sqlprep($extracitbased).' where id='.sqlprep($employeeid));
                echo texttitle($lang['STR_DEDUCTION_INFO']);
                $recordSet=&$conn->Execute('select firstname,lastname,workmanscomprate,pensplanid1,pensplandedamount1,pensplanbase1,pensplanid2,pensplandedamount2,pensplanbase2,prdedgroupid from premployee where id='.sqlprep($employeeid));
                echo '<form action="premployeeupd.php" method="post"><table><input type="hidden" name="deduction" value="1"><input type="hidden" name="employeeid" value="'.$employeeid.'">';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME'].':</td><td>'.$recordSet->fields[0].' '.$recordSet->fields[1].'</td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WORKMANS_COMP_RATE'].':</td><td><input type="text" name="workmanscomprate" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[2].'"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEDUCTION_GROUP'].':</td><td><select name="prdedgroupid"'.INC_TEXTBOX.'><option value="0">';
                $recordSet2 = &$conn->Execute('select id, name from prdedgroup where gencompanyid='.sqlprep($active_company).' order by name');
                while (!$recordSet2->EOF) {
                        echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[9],$recordSet2->fields[0],' selected').'>'.$recordSet2->fields[1]."\n";
                        $recordSet2->MoveNext();
                };
                echo '</select></td></tr>';
                $recordSet2=&$conn->CacheExecute(15,'select id,name from prpension where cancel=0 and gencompanyid='.sqlprep($active_company));
                if ($recordSet2->EOF) {
                        echo '<input type="hidden" name="pensplanid1" value="0">';
                        echo '<input type="hidden" name="pensplandedamount1" value="0">';
                        echo '<input type="hidden" name="pensplanbase1" value="0">';
                        echo '<input type="hidden" name="pensplanid2" value="0">';
                        echo '<input type="hidden" name="pensplandedamount2" value="0">';
                        echo '<input type="hidden" name="pensplanbase2" value="0">';
                } else {
                        echo '<tr><th colspan="2" align="center">'.$lang['STR_PENSION_SAVINGS_PLAN'].'</th></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PLAN_ONE'].':</td><td>';
                        echo '<select name="pensplanid1"'.INC_TEXTBOX.'><option value="0"'.checkequal($recordSet->fields[3],0,' selected').'>';
                        while (!$recordSet2->EOF) {
                                echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[3],$recordSet2->fields[0],' selected').'>'.$recordSet2->fields[1];
                                $recordSet2->MoveNext();
                        };
                        echo '</td></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEDUCTION_AMOUNT_ONE'].':</td><td><input type="text" name="pensplandedamount1" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[4].'"'.INC_TEXTBOX.'></td></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BASE_ONE'].':</td><td><select name="pensplanbase1"'.INC_TEXTBOX.'><option value="0"'.checkequal($recordSet->fields[5],0,' selected').'>Fixed Dollar Amount<option value="1"'.checkequal($recordSet->fields[5],1,' selected').'>Percentage of Pay</select></td></tr><tr><td>.</td></tr>';
                        $recordSet2=&$conn->CacheExecute(15,'select id,name from prpension where cancel=0 and gencompanyid='.sqlprep($active_company));
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PLAN_TWO'].':</td><td>';
                        echo '<select name="pensplanid2"'.INC_TEXTBOX.'><option value="0"'.checkequal($recordSet->fields[6],$recordSet2->fields[0],' selected').'>';
                        while (!$recordSet2->EOF) {
                                echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[6],$recordSet2->fields[0],' selected').'>'.$recordSet2->fields[1];
                                $recordSet2->MoveNext();
                        };
                        echo '</td></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEDUCTION_AMOUNT_TWO'].':</td><td><input type="text" name="pensplandedamount2" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[7].'"'.INC_TEXTBOX.'></td></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BASE_TWO'].':</td><td><select name="pensplanbase2"'.INC_TEXTBOX.'><option value="0"'.checkequal($recordSet->fields[8],0,' selected').'>Fixed Dollar Amount<option value="1"'.checkequal($recordSet->fields[8],1,' selected').'>Percentage of Pay</select></td></tr>';
                };
                echo '</table><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
        } elseif ($employeeid&&$firstname&&$lastname&&$federalid) {
                checkpermissions('pay');
                if (!prcompanyupdate($companyid,$address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $firstname.' '.$lastname,$mailstop, $lastchangedate)) die(texterror('Error updating employee'));
                $conn->Execute('update premployee set companyid='.sqlprep($companyid).',firstname='.sqlprep($firstname).',lastname='.sqlprep($lastname).',ssnumber='.sqlprep($federalid).',dateofbirth='.sqlprep($dateofbirth).',hiredate='.sqlprep($hiredate).',paytype='.sqlprep($paytype).',payperiod='.sqlprep($payperiod).',payperperiod='.sqlprep($payperperiod).',glaccountid='.sqlprep($glaccountid).',vacationhoursaccrued='.sqlprep($vacationhoursaccrued).',sickleavehoursaccrued='.sqlprep($sickleavehoursaccrued).',lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($employeeid));
                $recordSet=&$conn->Execute('select maritalstatus,federalexemptions,extrafitperpayperiod,extrafitbasedon,eic,prstateid,stateexemptions,extrasitperpayperiod,extrasitbasedon,prlocalid,localexemptions,extralitperpayperiod,extralitbasedon,prcityid,cityexemptions,extracitperpayperiod,extracitbasedon from premployee where id='.sqlprep($employeeid));
                if ($recordSet->EOF) die(texterror($lang['STR_EMPLOYEE_NOT_FOUND']));
                if ($oldpayperperiod<>$payperperiod) $conn->Execute('insert into prpaychange (employeeid,oldpay,newpay,paystartdate,lastchangeuserid) values ('.sqlprep($employeeid).','.sqlprep($oldpayperperiod).','.sqlprep($payperperiod).',NOW(),'.sqlprep($userid).')');
                echo texttitle($lang['STR_TAX_INFO']);
                echo '<form action="premployeeupd.php" method="post"><table><input type="hidden" name="employeeid" value="'.$employeeid.'">';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME'].':</td><td>'.$firstname.' '.$lastname.'</td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MARITAL_STATUS'].':</td><td><select name="maritalstatus"'.INC_TEXTBOX.'>';
                for ($i=1;$i<=4;$i++) echo '<option value="'.$i.'"'.checkequal($recordSet->fields[0],$i,' selected').'>'.prmaritalstatusfromid($i); //display marital status options options
                echo '</select>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FEDERAL_EXEMPTIONS'].':</td><td><input type="text" name="federalexemptions" onchange="validatenum(int)" size="30" maxlength="2" value="'.$recordSet->fields[1].'"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.TR_EXTRA_FIT_PER_PAY_PERIOD.':</td><td><input type="text" name="extrafitperpayperiod" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[2].'"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_FIT_BASED_ON'].':</td><td><select name="extrafitbasedon"'.INC_TEXTBOX.'><option value="1"'.checkequal($recordSet->fields[3],1,' selected').'>Percentage of Wages<option value="2"'.checkequal($recordSet->fields[3],2,' selected').'>Federal Tax Percent<option value="3"'.checkequal($recordSet->fields[3],3,' selected').'>Dollar Amount</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">EIC:</td><td><input type="checkbox" name="eic" value="1"'.INC_TEXTBOX.' value="'.$recordSet->fields[4].'"></td></tr>';
                $recordSet2=&$conn->Execute('select id,genstateid from prstate where cancel=0 and gencompanyid='.sqlprep($active_company));
                if ($recordSet2->EOF) die(texterror($lang['STR_NO_STATE_TAXES_DEFINED']));
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><select name="prstateid"'.INC_TEXTBOX.'><option value="0"'.checkequal($recordSet->fields[5],$recordSet2->fields[0],' selected').'>';
                while (!$recordSet2->EOF) {
                        echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[5],$recordSet2->fields[0],' selected').'>'.statenamefromid($recordSet2->fields[1]);
                        $recordSet2->MoveNext();
                };
                echo '</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE_EXEMPTIONS'].':</td><td><input type="text" name="stateexemptions" onchange="validateint(this)" size="30" maxlength="2" value="'.$recordSet->fields[6].'"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_SIT_PER_PAY_PERIOD'].':</td><td><input type="text" name="extrasitperpayperiod" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[7].'"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_SIT_BASED_ON'].':</td><td><select name="extrasitbasedon"'.INC_TEXTBOX.'><option value="1"'.checkequal($recordSet->fields[8],1,' selected').'>Percentage of Wages<option value="2"'.checkequal($recordSet->fields[8],2,' selected').'>Federal Tax Percent<option value="3"'.checkequal($recordSet->fields[8],3,' selected').'>Dollar Amount</select></td></tr>';
                $recordSet2=&$conn->Execute('select id,name from prlocal where cancel=0 and gencompanyid='.sqlprep($active_company));
                if ($recordSet2->EOF) die(texterror($lang['STR_NO_LOCAL_TAXES_DEFINED']));
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LOCAL'].':</td><td><select name="prlocalid"'.INC_TEXTBOX.'><option value="0"'.checkequal($recordSet->fields[9],$recordSet2->fields[0],' selected').'>';
                while (!$recordSet2->EOF) {
                        echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[9],$recordSet2->fields[0],' selected').'>'.$recordSet2->fields[1];
                        $recordSet2->MoveNext();
                };
                echo '</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LOCAL_EXEMPTIONS'].':</td><td><input type="text" name="localexemptions" onchange="validateint(this)" size="30" maxlength="2" value="'.$recordSet->fields[10].'"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_LIT_PER_PAY_PERIOD'].':</td><td><input type="text" name="extralitperpayperiod" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[11].'"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_LIT_BASED_ON'].':</td><td><select name="extralitbasedon"'.INC_TEXTBOX.'><option value="1"'.checkequal($recordSet->fields[12],1,' selected').'>Percentage of Wages<option value="2"'.checkequal($recordSet->fields[12],2,' selected').'>Federal Tax Percent<option value="3"'.checkequal($recordSet->fields[12],3,' selected').'>Dollar Amount</select></td></tr>';
                $recordSet2=&$conn->Execute('select id,name from prcity where cancel=0 and gencompanyid='.sqlprep($active_company));
                if ($recordSet2->EOF) die(texterror($lang['STR_NO_CITY_TAXES_DEFINED']));
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><select name="prcityid"'.INC_TEXTBOX.'><option value="0"'.checkequal($recordSet->fields[13],0,' selected').'>';
                while (!$recordSet2->EOF) {
                        echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[13],$recordSet2->fields[0],' selected').'>'.$recordSet2->fields[1];
                        $recordSet2->MoveNext();
                };
                echo '</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY_EXEMPTIONS'].':</td><td><input type="text" name="cityexemptions" onchange="validateint(this)" size="30" maxlength="2" value="'.$recordSet->fields[14].'"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_CIT_PER_PAY_PERIOD'].':</td><td><input type="text" name="extracitperpayperiod" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[15].'"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EXTRA_CIT_BASED_ON'].':</td><td><select name="extracitbasedon"'.INC_TEXTBOX.'><option value="1"'.checkequal($recordSet->fields[16],1,' selected').'>Percentage of Wages<option value="2"'.checkequal($recordSet->fields[16],2,' selected').'>Federal Tax Percent<option value="3"'.checkequal($recordSet->fields[16],3,' selected').'>Dollar Amount</select></td></tr>';
                echo '</table><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
        } elseif ($employeeid) {
                echo texttitle($lang['STR_GENERAL_INFO']);
                $recordSet=&$conn->Execute('select companyid,firstname,lastname,ssnumber,dateofbirth,hiredate,paytype,payperiod,payperperiod,glaccountid,vacationhoursaccrued,sickleavehoursaccrued,status from premployee where id='.sqlprep($employeeid));
                if ($recordSet->EOF) die(texterror($lang['STR_EMPLOYEE_NOT_FOUND']));
                if ($recordSet->fields[12]==0) echo '<center><font size="+1">'.$lang['STR_EMPLOYEE_CURRENTLY_INACTIVE'].'</font></center><br>';
                echo '<form action="premployeeupd.php" method="post"><input type="hidden" name="employeeid" value="'.$employeeid.'"><input type="hidden" name="companyid" value="'.$recordSet->fields[0].'"><table>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME_FIRST_LAST'].':</td><td><input type="text" name="firstname" size="14" maxlength="30" value="'.$recordSet->fields[1].'"'.INC_TEXTBOX.'><input type="text" name="lastname" size="14" maxlength="30" value="'.$recordSet->fields[2].'"'.INC_TEXTBOX.'></td></tr>';
                formprcompanyupdate($recordSet->fields[0]);
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DATE_OF_BIRTH'].':</td><td><input type="text" name="dateofbirth" onchange="formatDate(this)" size="30" value="'.$recordSet->fields[4].'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.dateofbirth\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_HIRE_DATE'].':</td><td><input type="text" name="hiredate" onchange="formatDate(this)" size="30" value="'.$recordSet->fields[5].'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.hiredate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAYMENT_TYPE'].':</td><td><select name="paytype"'.INC_TEXTBOX.'><option value="0"'.checkequal($recordSet->fields[6],0,' selected').'>Hourly<option value="1"'.checkequal($recordSet->fields[6],1,' selected').'>Salary</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAY_PERIOD'].':</td><td><select name="payperiod"'.INC_TEXTBOX.'>';
                $recordSet2=&$conn->Execute('select id,name from prperiod');
                while (!$recordSet2->EOF) {
                        echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[7],$recordSet2->fields[0],' selected').'>'.$recordSet2->fields[1]."\n";
                        $recordSet2->MoveNext();
                };
                echo '</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAY_PER_HOUR_PERIOD'].':</td><td><input type="text" name="payperperiod" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[8].'"'.INC_TEXTBOX.'><input type="hidden" name="oldpayperperiod"  value="'.$recordSet->fields[8].'"></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_ACCOUNT'].':</td><td><select name="glaccountid"'.INC_TEXTBOX.'>';
                $recordSet2=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
                while (!$recordSet2->EOF) {
                        echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[9],$recordSet2->fields[0],' selected').'>'.$recordSet2->fields[1].' - '.$recordSet2->fields[2]."\n";
                        $recordSet2->MoveNext();
                };
                echo '</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VACATION_HOURS_ACCRUED'].':</td><td><input type="text" name="vacationhoursaccrued" onchange="validatenum(this)" size="30" maxlength="5" value="'.$recordSet->fields[10].'"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SICK_LEAVE_HOURS_ACCRUED'].':</td><td><input type="text" name="sickleavehoursaccrued" onchange="validatenum(this)" size="30" maxlength="5" value="'.$recordSet->fields[11].'"'.INC_TEXTBOX.'></td></tr>';
                echo '</table><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
                if ($recordSet->fields[12]) { $layoffstr='Lay off'; $layoff=0; } else { $layoffstr='Activate'; $layoff=1;};
                echo '<a href="premployeeupd.php?employeeid='.$employeeid.'&layoff='.$layoff.'">'.$layoffstr.'</a>';
                echo '&nbsp;&nbsp;<a href="javascript:confirmdelete(\'premployeeupd.php?employeeid='.$employeeid.'&terminate=1\')">'.$lang['STR_TERMINATE'].'</a>';
        } else {
                echo texttitle($lang['STR_EMPLOYEE_SELECTION']);
                $recordSet=&$conn->Execute('select id,firstname,lastname from premployee where gencompanyid='.sqlprep($active_company).' and terminatedate=\'0000-00-00\' and cancel=0 order by lastname,firstname');
                if ($recordSet&&!$recordSet->EOF) {
                    echo '<form action="premployeeupd.php" method="post"><table>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME'].':</td><td><select name="employeeid"'.INC_TEXTBOX.'>';
                    while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[2].', '.$recordSet->fields[1]."\n";
                        $recordSet->MoveNext();
                    };
                    echo '</select></td></tr></table>';
                    echo '<table><tr style="text-align: center;"><td align="center"><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></td></tr></form>';
                };
                echo '<tr><td style="text-align: center;"><a href="premployeeadd.php">'.$lang['STR_ADD_NEW_EMPLOYEE'].'</a></td></tr>';
        };
                echo '</table></center>';
?>
<?php include('includes/footer.php'); ?>
