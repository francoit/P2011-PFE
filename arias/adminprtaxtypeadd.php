<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php');?>
<?php include('includes/prfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center>';
     echo texttitle($lang['STR_PAYROLL_TAX_ADD_GENERAL_INFO']);
     if ($locale) {
          if ($locale=='Federal') { //federal is very different from others, so we just handle it separately
               if ($final) {
                    $conn->Execute('insert into prfederal (maxwagesfica,employeeficapercent,companyficapercent,maxwagesmedicare,employeemedicarepercent,companymedicarepercent,maxwagesfui,companyfuipercent,eicsinglepercent1,eicsingleover1,eicsingletax2,eicsingleover2,eicsingletax3,eicsinglepercent3,eicsingleover3,eicmarriedpercent1,eicmarriedover1,eicmarriedtax2,eicmarriedover2,eicmarriedtax3,eicmarriedpercent3,eicmarriedover3,exemptionallow,gencompanyid,entrydate,entryuserid,lastchangeuserid) values ('.sqlprep($maxwagesfica).','.sqlprep($employeeficapercent).','.sqlprep($companyficapercent).','.sqlprep($maxwagesmedicare).','.sqlprep($employeemedicarepercent).','.sqlprep($companymedicarepercent).','.sqlprep($maxwagesfui).','.sqlprep($companyfuipercent).','.sqlprep($eicsinglepercent1).','.sqlprep($eicsingleover1).','.sqlprep($eicsingletax2).','.sqlprep($eicsingleover2).','.sqlprep($eicsingletax3).','.sqlprep($eicsinglepercent3).','.sqlprep($eicsingleover3).','.sqlprep($eicmarriedpercent1).','.sqlprep($eicmarriedover1).','.sqlprep($eicmarriedtax2).','.sqlprep($eicmarriedover2).','.sqlprep($eicmarriedtax3).','.sqlprep($eicmarriedpercent3).','.sqlprep($eicmarriedover3).','.sqlprep($exemptionallow).','.sqlprep($active_company).',NOW(),'.sqlprep($userid).','.sqlprep($userid).')');
                    echo textsuccess($lang['STR_TAX_ADDED_SUCCESSFULLY']);
                    $recordSet=&$conn->SelectLimit('select id from pr'.strtolower($locale).' where entryuserid='.sqlprep($userid).' and cancel=0'.$str3.' order by entrydate desc',1);
                    if (!$recordSet->EOF) echo '<a href="adminprtaxtypedtl.php?locale='.$recordSet->fields[0].'&localeid='.$id.'">'.$lang['STR_UPDATE_TAX_TABLES'].'</a><br>';
               } else {
                    echo '<form action="adminprtaxtypeadd.php" method="post" name="mainform"><input type="hidden" name="locale" value="'.$locale.'"><input type="hidden" name="final" value="1"><table>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_TYPE'].': </td><td>'.$locale.'</td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ANNUAL_EXEMPTION_ALLOWANCE'].':</td><td><input type="text" name="exemptionallow" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><th colspan="2">'.$lang['STR_FICA'].' <font size="-2">('.$lang['STR_SOCIAL_SECURITY'].')</font></th></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAX_WAGES'].':</td><td><input type="text" name="maxwagesfica" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMPLOYEE'].' %:</td><td><input type="text" name="employeeficapercent" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY'].' %:</td><td><input type="text" name="companyficapercent" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><th colspan="2">'.$lang['STR_MEDICARE'].'</th></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAX_WAGES'].':</td><td><input type="text" name="maxwagesmedicare" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMPLOYEE'].' %:</td><td><input type="text" name="employeemedicarepercent" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY'].' %:</td><td><input type="text" name="companymedicarepercent" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><th colspan="2">'.$lang['STR_FUI'].' <font size="-2">('.$lang['STR_FEDERAL_UNEMPLOYMENT_INSURANCE'].')</font></th></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAX_WAGES'].':</td><td><input type="text" name="maxwagesfui" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY'].' %:</td><td><input type="text" name="companyfuipercent" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><th colspan="2">'.$lang['STR_EIC_SINGLE'].' <font size="-2">('.$lang['STR_EARNED_INCOME_CREDIT'].')</font></th></tr>';
                    echo '<tr><td colspan="2"><input type="text" onchange="validatenum(this)" name="eicsinglepercent1" size="15" maxlength="15"'.INC_TEXTBOX.'>% '.$lang['STR_OF_WAGES_ON_ANNUAL_EARNINGS_OVER'].' '.CURRENCY_SYMBOL.'<input type="text" onchange="validatenum(this)" name="eicsingleover1" size="15" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td colspan="2">'.CURRENCY_SYMBOL.'<input type="text" onchange="validatenum(this)" name="eicsingletax2" size="15" maxlength="15"'.INC_TEXTBOX.'> '.$lang['STR_TAX_FOR_ANNUAL_EARNINGS_OVER'].' '.CURRENCY_SYMBOL.'<input type="text" onchange="validatenum(this)" name="eicsingleover2" size="15" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td colspan="2">'.CURRENCY_SYMBOL.'<input type="text" onchange="validatenum(this)" name="eicsingletax3" size="15" maxlength="15"'.INC_TEXTBOX.'> '.$lang['STR_TAX_LESS'].' <input type="text" name="eicsinglepercent3" onchange="validatenum(this)" size="15" maxlength="15"'.INC_TEXTBOX.'>% '.$lang['STR_OF_WAGES_ON_ANNUAL_EARNINGS_OVER'].' '.CURRENCY_SYMBOL.'<input type="text" onchange="validatenum(this)" name="eicsingleover3" size="15" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><th colspan="2">'.$lang['STR_EIC_MARRIED'].' <font size="-2">('.$lang['STR_EARNED_INCOME_CREDIT'].')</font></th></tr>';
                    echo '<tr><td colspan="2"><input type="text" onchange="validatenum(this)" name="eicmarriedpercent1" size="15" maxlength="15"'.INC_TEXTBOX.'>% '.$lang['STR_OF_WAGES_ON_ANNUAL_EARNINGS_OVER'].' '.CURRENCY_SYMBOL.'<input type="text" onchange="validatenum(this)" name="eicmarriedover1" size="15" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td colspan="2">'.CURRENCY_SYMBOL.'<input type="text" onchange="validatenum(this)" name="eicmarriedtax2" size="15" maxlength="15"'.INC_TEXTBOX.'> '.$lang['STR_TAX_FOR_ANNUAL_EARNINGS_OVER'].' '.CURRENCY_SYMBOL.'<input type="text" onchange="validatenum(this)" name="eicmarriedover2" size="15" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td colspan="2">'.CURRENCY_SYMBOL.'<input type="text" onchange="validatenum(this)" name="eicmarriedtax3" size="15" maxlength="15"'.INC_TEXTBOX.'> '.$lang['STR_TAX_LESS'].' <input type="text" onchange="validatenum(this)" name="eicmarriedpercent3" size="15" maxlength="15"'.INC_TEXTBOX.'>% '.$lang['STR_OF_WAGES_ON_ANNUAL_EARNINGS_OVER'].' '.CURRENCY_SYMBOL.'<input type="text" onchange="validatenum(this)" name="eicmarriedover3" size="15" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
               };
          } elseif ($locale=='State'||$locale=='City'||$locale=='Local') {
               if ($final&&($name||$genstateid)) {
                    checkpermissions('pay');
                    if ($locale=='State') {
                         $str1='genstateid,suipercent,suimax,';
                         $str2=sqlprep($genstateid).','.sqlprep($suipercent).','.sqlprep($suimax).',';
                         $str3=' and genstateid='.sqlprep($genstateid);
                    } else {
                         $str1='abrev,name,';
                         $str2=sqlprep($abrev).','.sqlprep($name).',';
                         $str3=' and name='.sqlprep($name).' and abrev='.sqlprep($abrev);
                    };
                    $conn->Execute('insert into pr'.strtolower($locale).' ('.$str1.'taxnum,deductfed,feddeductmax,exemptyr1,exemptyr2,exemptyr3,exemptyr4,glacctid,maxexemptpercent,maxexemptyear,taxcreditexempt1,taxcreditexempt2,taxcreditexempt3,taxcreditexempt4,vendorid,gencompanyid,entrydate,entryuserid,lastchangeuserid) values ('.$str2.sqlprep($taxnum).','.sqlprep($deductfed).','.sqlprep($feddeductmax).','.sqlprep($exemptyr1).','.sqlprep($exemptyr2).','.sqlprep($exemptyr3).','.sqlprep($exemptyr4).','.sqlprep($glacctid).','.sqlprep($maxexemptpercent).','.sqlprep($maxexemptyear).','.sqlprep($taxcreditexempt1).','.sqlprep($taxcreditexempt2).','.sqlprep($taxcreditexempt3).','.sqlprep($taxcreditexempt4).', '.sqlprep($vendorid).', '.sqlprep($active_company).',NOW(),'.sqlprep($userid).','.sqlprep($userid).')');
                    echo textsuccess($lang['STR_TAX_ADDED_SUCCESSFULLY']);
                    $recordSet=&$conn->SelectLimit('select id from pr'.strtolower($locale).' where entryuserid='.sqlprep($userid).' and cancel=0'.$str3.' order by entrydate desc',1);
                    if (!$recordSet->EOF) echo '<a href="adminprtaxtypedtl.php?locale='.$locale.'&localeid='.$recordSet->fields[0].'">'.$lang['STR_UPDATE_TAX_TABLES'].'</a><br>';
               } else {
                    echo '<form action="adminprtaxtypeadd.php" method="post" name="mainform"><input type="hidden" name="locale" value="'.$locale.'"><input type="hidden" name="final" value="1"><table>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_TYPE'].':</td><td>'.$locale.'</td></tr>';
                    if ($locale=='State') {
                         stateselect('genstateid', 0);
                         echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SUI'].' %:</td><td><input type="text" name="suipercent" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                         echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAX_SUI_PER_YEAR'].':</td><td><input type="text" name="suimax" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    } else {
                         echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$locale.' '.$lang['STR_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
                         echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$locale.' '.$lang['STR_CHECK_ABBR'].':</td><td><input type="text" name="abrev" size="30" maxlength="3"'.INC_TEXTBOX.'></td></tr>';
                    };
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEDUCT_FEDERAL_TAX_FIRST'].':</td><td><input type="checkbox" name="deductfed" value="1"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAXIMUM_FEDERAL_TAX_TO_DEDUCT'].':</td><td><input type="text" name="feddeductmax" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_ACCOUNT'].':</td><td><select name="glacctid"'.INC_TEXTBOX.'>';
                    $recordSet2=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (glaccount.accounttypeid>=21 or glaccount.accounttypeid<=23) and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
                    while (!$recordSet2->EOF) {
                         echo '<option value="'.$recordSet2->fields[0].'">'.$recordSet2->fields[1].' - '.$recordSet2->fields[2]."\n";
                         $recordSet2->MoveNext();
                    };
                    echo '</td></tr></select>';
                    formapvendorselect('vendorid');
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX'].' #:</td><td><input type="text" name="taxnum" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><th colspan="2" align="center">'.$lang['STR_EXEMPTION_BEFORE_TAX_CALCULATED'].'</th></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AMOUNT_FOR_1_EXEMPTION'].':</td><td><input type="text" name="exemptyr1" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AMOUNT_FOR_2_EXEMPTIONS'].':</td><td><input type="text" name="exemptyr2" onchange="validatenum(this)" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AMOUNT_FOR_3_EXEMPTIONS'].':</td><td><input type="text" name="exemptyr3" onchange="validatenum(this)" size="30" maxlength="15" '.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AMOUNT_PER_EXEMPTION_FOR_4_AND_UP'].':</td><td><input type="text" name="exemptyr4" onchange="validatenum(this)" size="30" maxlength="15" '.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAX_EXEMPTION'].' %:</td><td><input type="text" name="maxexemptpercent" onchange="validatenum(this)" size="30" maxlength="8" '.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAX_EXEMPTION_AMOUNT_PER_YEAR'].':</td><td><input type="text" name="maxexemptyear" onchange="validatenum(this)" size="30" maxlength="15" '.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><th colspan="2" align="center">'.$lang['STR_TAX_CREDITS_AFTER_TAX_CALCULATED'].'</th></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CREDIT_FOR_1_EXEMPTION'].':</td><td><input type="text" name="taxcreditexempt1" onchange="validatenum(this)" size="30" maxlength="15" '.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CREDIT_PER_EXEMPTION_FOR_2'].':</td><td><input type="text" name="taxcreditexempt2" onchange="validatenum(this)" size="30" maxlength="15" '.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CREDIT_PER_EXEMPTION_FOR_3'].':</td><td><input type="text" name="taxcreditexempt3" onchange="validatenum(this)" size="30" maxlength="15" '.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CREDIT_PER_EXEMPTION_FOR_4_AND_UP'].':</td><td><input type="text" name="taxcreditexempt4" onchange="validatenum(this)" size="30" maxlength="15" '.INC_TEXTBOX.'></td></tr>';

                    echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
               };
          } else {
               die(texterror('Bad locale.'));
          };
     } else {
          echo '<form action="adminprtaxtypeadd.php" method="post"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_TYPE'].'</td><td><select name="locale"'.INC_TEXTBOX.'><option value="Local">'.$lang['STR_LOCAL'].'<option value="City">'.$lang['STR_CITY'].'<option value="State">'.$lang['STR_STATE'].'<option value="Federal">'.$lang['STR_FEDERAL'].'</select></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_SELECT'].'"></form>';
          
          echo '</center>';
     };
?>
<?php include('includes/footer.php'); ?>
