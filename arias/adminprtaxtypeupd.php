<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php');?>
<?php include('includes/prfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center>';
     echo texttitle($lang['STR_PAYROLL_TAX_UPDATE_GENERAL_INFO']);
     if ($delete&&($locale=='State'||$locale=='City'||$locale=='Local'||$locale=='Federal')&&$id) { //delete tax type
          $conn->Execute('update pr'.strtolower($locale).' set cancel=1, canceldate=NOW(), canceluserid='.sqlprep($userid).' where id='.sqlprep($id));
          unset($locale);
          echo textsuccess($lang['STR_TAX_DELETED_SUCCESSFULLY']);
     };
     if ($locale=='Federal') {
          if ($final) {
               $recordSet=&$conn->Execute('select lastchangedate from pr'.strtolower($locale).' where gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id));
               if (!$recordSet->EOF) if ($recordSet->fields[0]<>$lastchangedate) {
                    showwhochanged($id,strtolower("pr".$locale),"id");
               } else {
                    $conn->Execute('update prfederal set maxwagesfica='.sqlprep($maxwagesfica).',employeeficapercent='.sqlprep($employeeficapercent).',companyficapercent='.sqlprep($companyficapercent).',maxwagesmedicare='.sqlprep($maxwagesmedicare).',employeemedicarepercent='.sqlprep($employeemedicarepercent).',companymedicarepercent='.sqlprep($companymedicarepercent).',maxwagesfui='.sqlprep($maxwagesfui).',companyfuipercent='.sqlprep($companyfuipercent).',eicsinglepercent1='.sqlprep($eicsinglepercent1).',eicsingleover1='.sqlprep($eicsingleover1).',eicsingletax2='.sqlprep($eicsingletax2).',eicsingleover2='.sqlprep($eicsingleover2).',eicsingletax3='.sqlprep($eicsingletax3).',eicsinglepercent3='.sqlprep($eicsinglepercent3).',eicsingleover3='.sqlprep($eicsingleover3).',eicmarriedpercent1='.sqlprep($eicmarriedpercent1).',eicmarriedover1='.sqlprep($eicmarriedover1).',eicmarriedtax2='.sqlprep($eicmarriedtax2).',eicmarriedover2='.sqlprep($eicmarriedover2).',eicmarriedtax3='.sqlprep($eicmarriedtax3).',eicmarriedpercent3='.sqlprep($eicmarriedpercent3).',eicmarriedover3='.sqlprep($eicmarriedover3).',exemptionallow='.sqlprep($exemptionallow).',lastchangeuserid='.sqlprep($userid).' where gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id));
                    echo textsuccess($lang['STR_TAX_UPDATED_SUCCESSFULLY']);
                    echo '<a href="adminprtaxtypedtl.php?locale='.$locale.'&localeid='.$id.'">'.$lang['STR_UPDATE_TAX_TABLES'].'</a><br>';
               };
          };
          $recordSet=&$conn->SelectLimit('select maxwagesfica,employeeficapercent,companyficapercent,maxwagesmedicare,employeemedicarepercent,companymedicarepercent,maxwagesfui,companyfuipercent,eicsinglepercent1,eicsingleover1,eicsingletax2,eicsingleover2,eicsingletax3,eicsinglepercent3,eicsingleover3,eicmarriedpercent1,eicmarriedover1,eicmarriedtax2,eicmarriedover2,eicmarriedtax3,eicmarriedpercent3,eicmarriedover3,exemptionallow,id,lastchangedate from prfederal where gencompanyid='.sqlprep($active_company),1);
          if (!$recordSet->EOF) {
               echo '<form action="adminprtaxtypeupd.php" method="post" name="mainform"><input type="hidden" name="locale" value="'.$locale.'"><input type="hidden" name="final" value="1"><input type="hidden" name="id" value="'.$recordSet->fields[23].'"><input type="hidden" name="lastchangedate" value="'.$recordSet->fields[24].'"><table>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_TYPE'],':</td><td>'.$locale.'</td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ANNUAL_EXEMPTION_ALLOWANCE'].':</td><td><input type="text" name="exemptionallow" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[22].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><th colspan="2">'.$lang['STR_FICA'].' <font size="-2">('.$lang['STR_SOCIAL_SECURITY'].')</font></th></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAX_WAGES'].':</td><td><input type="text" name="maxwagesfica" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[0].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMPLOYEE'].' %:</td><td><input type="text" name="employeeficapercent" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[1].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY'].' %:</td><td><input type="text" name="companyficapercent" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[2].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><th colspan="2">'.$lang['STR_MEDICARE'].'</th></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAX_WAGES'].':</td><td><input type="text" name="maxwagesmedicare" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[3].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMPLOYEE'].' %:</td><td><input type="text" name="employeemedicarepercent" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[4].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY'].' %:</td><td><input type="text" name="companymedicarepercent" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[5].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><th colspan="2">'.$lang['STR_FUI'].' <font size="-2">('.$lang['STR_FEDERAL_UNEMPLOYMENT_INSURANCE'].')</font></th></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAX_WAGES'].':</td><td><input type="text" name="maxwagesfui" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[6].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY'].' %:</td><td><input type="text" name="companyfuipercent" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[7].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><th colspan="2">'.$lang['STR_EIC_SINGLE'].' <font size="-2">('.$lang['STR_EARNED_INCOME_CREDIT'].')</font></th></tr>';
               echo '<tr><td colspan="2"><input type="text" name="eicsinglepercent1" onchange="validatenum(this)" size="15" maxlength="15" value="'.$recordSet->fields[8].'"'.INC_TEXTBOX.'>% '.$lang['STR_OF_WAGES_ON_ANNUAL_EARNINGS_OVER'].' '.CURRENCY_SYMBOL.'<input type="text" name="eicsingleover1" onchange="validatenum(this)" size="15" maxlength="15" value="'.$recordSet->fields[9].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td colspan="2">'.CURRENCY_SYMBOL.'<input type="text" name="eicsingletax2" onchange="validatenum(this)" size="15" maxlength="15" value="'.$recordSet->fields[10].'"'.INC_TEXTBOX.'> '.$lang['STR_TAX_FOR_ANNUAL_EARNINGS_OVER'].' '.CURRENCY_SYMBOL.'<input type="text" name="eicsingleover2" onchange="validatenum(this)" size="15" maxlength="15" value="'.$recordSet->fields[11].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td colspan="2">'.CURRENCY_SYMBOL.'<input type="text" name="eicsingletax3" onchange="validatenum(this)" size="15" maxlength="15" value="'.$recordSet->fields[12].'"'.INC_TEXTBOX.'> '.$lang['STR_TAX_LESS'].' <input type="text" name="eicsinglepercent3" size="15" onchange="validatenum(this)" maxlength="15" value="'.$recordSet->fields[13].'"'.INC_TEXTBOX.'>% '.$lang['STR_OF_WAGES_ON_ANNUAL_EARNINGS_OVER'].' '.CURRENCY_SYMBOL.'<input type="text" name="eicsingleover3" onchange="validatenum(this)" size="15" maxlength="15" value="'.$recordSet->fields[14].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><th colspan="2">'.$lang['STR_EIC_MARRIED'].' <font size="-2">('.$lang['STR_EARNED_INCOME_CREDIT'].')</font></th></tr>';
               echo '<tr><td colspan="2"><input type="text" name="eicmarriedpercent1" onchange="validatenum(this)" size="15" maxlength="15" value="'.$recordSet->fields[15].'"'.INC_TEXTBOX.'>% '.$lang['STR_OF_WAGES_ON_ANNUAL_EARNINGS_OVER'].' '.CURRENCY_SYMBOL.'<input type="text" name="eicmarriedover1" onchange="validatenum(this)" size="15" maxlength="15" value="'.$recordSet->fields[16].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td colspan="2">'.CURRENCY_SYMBOL.'<input type="text" name="eicmarriedtax2" onchange="validatenum(this)" size="15" maxlength="15" value="'.$recordSet->fields[17].'"'.INC_TEXTBOX.'> '.$lang['STR_TAX_FOR_ANNUAL_EARNINGS_OVER'].' '.CURRENCY_SYMBOL.'<input type="text" name="eicmarriedover2" onchange="validatenum(this)" size="15" maxlength="15" value="'.$recordSet->fields[18].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td colspan="2">'.CURRENCY_SYMBOL.'<input type="text" name="eicmarriedtax3" onchange="validatenum(this)" size="15" maxlength="15" value="'.$recordSet->fields[19].'"'.INC_TEXTBOX.'> '.$lang['STR_TAX_LESS'].' <input type="text" name="eicmarriedpercent3" size="15" onchange="validatenum(this)" maxlength="15" value="'.$recordSet->fields[20].'"'.INC_TEXTBOX.'>% '.$lang['STR_OF_WAGES_ON_ANNUAL_EARNINGS_OVER'].' '.CURRENCY_SYMBOL.'<input type="text" name="eicmarriedover3" onchange="validatenum(this)" size="15" maxlength="15" value="'.$recordSet->fields[21].'"'.INC_TEXTBOX.'></td></tr>';
               echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
          } else {
               die(texterror($lang['STR_TAX_NOT_FOUND']));
          };
     } elseif ($locale=='State'||$locale=='City'||$locale=='Local') {
          if ($id) {
               if ($final&&($name||$genstateid)) { //write update to table
                    $recordSet=&$conn->Execute('select lastchangedate from pr'.strtolower($locale).' where gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id));
                    if (!$recordSet->EOF) if ($recordSet->fields[0]<>$lastchangedate) {
                         showwhochanged($id,strtolower("pr".$locale),"id");
                    } else {
                         if ($locale=='State') {
                              $str='genstateid='.sqlprep($genstateid).', suipercent='.sqlprep($suipercent).', suimax='.sqlprep($suimax).', ';
                         } else {
                              $str='abrev='.sqlprep($abrev).', name='.sqlprep($name).', ';
                         };
                         $conn->Execute('update pr'.strtolower($locale).' set '.$str.'vendorid='.sqlprep($vendorid).', taxnum='.sqlprep($taxnum).', deductfed='.sqlprep($deductfed).', feddeductmax='.sqlprep($feddeductmax).', exemptyr1='.sqlprep($exemptyr1).', exemptyr2='.sqlprep($exemptyr2).', exemptyr3='.sqlprep($exemptyr3).', exemptyr4='.sqlprep($exemptyr4).', glacctid='.sqlprep($glacctid).', maxexemptpercent='.sqlprep($maxexemptpercent).', maxexemptyear='.sqlprep($maxexemptyear).', taxcreditexempt1='.sqlprep($taxcreditexempt1).', taxcreditexempt2='.sqlprep($taxcreditexempt2).', taxcreditexempt3='.sqlprep($taxcreditexempt3).', taxcreditexempt4='.sqlprep($taxcreditexempt4).', lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($id));
                         echo textsuccess($lang['STR_TAX_UPDATED_SUCCESSFULLY']);
                         echo '<a href="adminprtaxtypedtl.php?locale='.$locale.'&localeid='.$id.'">'.$lang['STR_UPDATE_TAX_TABLES'].'</a><br>';
                    };
               };
               if ($locale=='State') {
                    $str=',genstateid,suipercent,suimax';
               } else {
                    $str=',abrev,name';
               };
               $recordSet=&$conn->Execute('select taxnum,deductfed,feddeductmax,exemptyr1,exemptyr2,exemptyr3,exemptyr4,glacctid,maxexemptpercent,maxexemptyear,taxcreditexempt1,taxcreditexempt2,taxcreditexempt3,taxcreditexempt4,vendorid,lastchangedate'.$str.' from pr'.strtolower($locale).' where cancel=0 and gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id));
               if (!$recordSet->EOF) {
                    echo '<form action="adminprtaxtypeupd.php" method="post" name="mainform"><input type="hidden" name="locale" value="'.$locale.'"><input type="hidden" name="lastchangedate" value="'.$recordSet->fields[15].'"><input type="hidden" name="id" value="'.$id.'"><input type="hidden" name="final" value="1"><table>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_TYPE'].': </td><td>'.$locale.'</td></tr>';
                    if ($locale=='State') {
                         stateselect('genstateid',  $recordSet->fields[16]);
                         echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SUI'].' %:</td><td><input type="text" name="suipercent" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[17].'"'.INC_TEXTBOX.'></td></tr>';
                         echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAX_SUI_PER_YEAR'].':</td><td><input type="text" name="suimax" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[18].'"'.INC_TEXTBOX.'></td></tr>';
                    } else {
                         echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$locale.' '.$lang['STR_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="30" value="'.$recordSet->fields[17].'"'.INC_TEXTBOX.'></td></tr>';
                         echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$locale.' '.$lang['STR_CHECK_ABBR'].':</td><td><input type="text" name="abrev" size="30" maxlength="3" value="'.$recordSet->fields[16].'"'.INC_TEXTBOX.'></td></tr>';
                    };
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEDUCT_FEDERAL_TAX_FIRST'].':</td><td><input type="checkbox" name="deductfed" value="1"'.checkequal($recordSet->fields[1],1,' checked').INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAXIMUM_FEDERAL_TAX_TO_DEDUCT'].':</td><td><input type="text" name="feddeductmax" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[2].'"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_ACCOUNT'].':</td><td><select name="glacctid"'.INC_TEXTBOX.'>';
                    $recordSet2=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (glaccount.accounttypeid>=21 or glaccount.accounttypeid<=23) and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
                    while (!$recordSet2->EOF) {
                         echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[7],$recordSet2->fields[0]," selected").'>'.$recordSet2->fields[1].' - '.$recordSet2->fields[2]."\n";
                         $recordSet2->MoveNext();
                    };
                    echo '</td></tr></select>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VENDOR'].' #:</td><td><input type="text" length="20" maxsize="30" onchange="validateint(this)" value="'.$recordSet->fields[14].'" name="vendorid"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupvendor.php?name=vendorid\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="'.IMAGE_VENDOR_LOOKUP.'" border="0" alt="Vendor Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'apvendadd.php\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="'.IMAGE_VENDOR_ADD.'" border="0" alt="Vendor Add"></a></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_NUMBER_FOR_W2'].':</td><td><input type="text" name="taxnum" size="30" maxlength="20" value="'.$recordSet->fields[0].'"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><th colspan="2" align="center">'.$lang['STR_EXEMPTION_BEFORE_TAX_CALCULATED'].'</th></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AMOUNT_FOR_1_EXEMPTION'].':</td><td><input type="text" name="exemptyr1" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[3].'"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AMOUNT_PER_EXEMPTION_FOR_2'].':</td><td><input type="text" name="exemptyr2" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[4].'"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AMOUNT_PER_EXEMPTION_FOR_3'].':</td><td><input type="text" name="exemptyr3" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[5].'"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AMOUNT_PER_EXEMPTION_FOR_4_AND_UP'].':</td><td><input type="text" name="exemptyr4" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[6].'"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAX_EXEMPTION'].' %:</td><td><input type="text" name="maxexemptpercent" onchange="validatenum(this)" size="30" maxlength="8" value="'.$recordSet->fields[8].'"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAX_EXEMPTION_AMOUNT_PER_YEAR'].':</td><td><input type="text" name="maxexemptyear" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[9].'"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><th colspan="2" align="center">'.$lang['STR_TAX_CREDITS_AFTER_TAX_CALCULATED'].'</th></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CREDIT_FOR_1_EXEMPTION'].':</td><td><input type="text" name="taxcreditexempt1" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[10].'"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CREDIT_PER_EXEMPTION_FOR_2'].':</td><td><input type="text" name="taxcreditexempt2" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[11].'"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CREDIT_PER_EXEMPTION_FOR_3'].':</td><td><input type="text" name="taxcreditexempt3" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[12].'"'.INC_TEXTBOX.'></td></tr>';
                    echo '<tr><td valign="top" align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CREDIT_PER_EXEMPTION_FOR_4_AND_UP'].':</td><td><input type="text" name="taxcreditexempt4" onchange="validatenum(this)" size="30" maxlength="15" value="'.$recordSet->fields[13].'"'.INC_TEXTBOX.'></td></tr>';
                    echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'adminprtaxtypeupd.php?delete=1&locale='.$locale.'&id='.$id.'\')">'.$lang['STR_DELETE_THIS_TAX_TYPE'].'</a>';
               } else {
                    die(texterror($lang['STR_TAX_TYPE_NOT_FOUND']));
               };
          } else {
               if ($locale=='State') {
                    $recordSet = &$conn->Execute('select id,genstateid,\'\' from prstate where cancel=0 and gencompanyid='.sqlprep($active_company));
               } elseif ($locale=='Local'||$locale=='City') {
                    $recordSet = &$conn->Execute('select id,name,abrev from pr'.strtolower($locale).' where cancel=0 and gencompanyid='.sqlprep($active_company));
               };
               if ($recordSet->EOF) die(texterror($lang['STR_NO_TAXES_FOUND']));
               echo '<form action="adminprtaxtypeupd.php" method="post" name="mainform"><input type="hidden" name="locale" value="'.$locale.'"><table>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_TYPE'].': </td><td><select name="id"'.INC_TEXTBOX.'>';
               while (!$recordSet->EOF) {
                    if ($locale=='State') {
                         $str=statenamefromid($recordSet->fields[1]);
                    } else {
                         $str=$recordSet->fields[1];
                    };
                    echo '<option value="'.$recordSet->fields[0].'">'.$str;
                    $recordSet->MoveNext();
               };
               echo '</table><input type="submit" value="'.$lang['STR_SELECT'].'"></form>';
          };
     } else {
          echo '<form action="adminprtaxtypeupd.php" method="post"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_TYPE'].': </td><td><select name="locale"'.INC_TEXTBOX.'><option value="Local">'.$lang['STR_LOCAL'].'<option value="City">'.$lang['STR_CITY'].'<option value="State">'.$lang['STR_STATE'].'<option value="Federal">'.$lang['STR_FEDERAL'].'</select></td></tr>';
          echo '</table><br><input type="submit" value="Select"></form>';
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
