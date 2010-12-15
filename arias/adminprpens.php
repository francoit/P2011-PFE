<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?
   echo '<center>';
   echo texttitle($lang['STR_PAYROLL_PENSION_CAFETERIA_SAVINGS_PLAN']);
   if ($delete) if ($id) {
      $conn->Execute('update prpension set cancel=1, canceluserid='.sqlprep($userid).', canceldate=NOW() where id='.sqlprep($id));
      $id='';
      $save='';
   };
   if ($save) {
          if ($id) { //this one already exists, update
                    prpensionupdate($id, $name,$w2plantype,$w2plansubtype,$employercontribhow,$employercontribute,$employermaxmatchpercent,$mustbeinplan,$calcbasis,$prdedgroupid,$paytype,$payableglacctid,$expenseglacctid,$federalincometax,$stateincometax,$localincometax,$cityincometax,$employeefica,$companyfica,$fui,$sui,$workmanscomp,$vendorid,$lastchangedate);
                    echo textsuccess($lang['STR_PENSION_UPDATED_SUCCESSFULLY']);
          } else { //if a valid entry, insert into file
                    if ($name) $conn->Execute('insert into prpension (gencompanyid,name,w2plantype,w2plansubtype,employercontribhow,employercontribute,employermaxmatchpercent,mustbeinplan,calcbasis,prdedgroupid,paytype,payableglacctid,expenseglacctid,federalincometax,stateincometax,localincometax,cityincometax,employeefica,companyfica,fui,sui,workmanscomp,vendorid,entrydate,entryuserid,lastchangeuserid) values ('.sqlprep($active_company).','.sqlprep($name).','.sqlprep($w2plantype).','.sqlprep($w2plansubtype).','.sqlprep($employercontribhow).','.sqlprep($employercontribute).','.sqlprep($employermaxmatchpercent).','.sqlprep($mustbeinplan).','.sqlprep($calcbasis).','.sqlprep($prdedgroupid).','.sqlprep($paytype).','.sqlprep($payableglacctid).','.sqlprep($expenseglacctid).','.sqlprep($federalincometax).','.sqlprep($stateincometax).','.sqlprep($localincometax).','.sqlprep($cityincometax).','.sqlprep($employeefica).','.sqlprep($companyfica).','.sqlprep($fui).','.sqlprep($sui).','.sqlprep($workmanscomp).','.sqlprep($vendorid).',NOW(),'.sqlprep($userid).','.sqlprep($userid).')');
                    echo textsuccess($lang['STR_PENSION_ADDED_SUCCESSFULLY']);
          };
   };
   if ($which) {
       if ($which===$lang['STR_EDIT_SELECTION']) {
              $recordSet=&$conn->Execute('select id,name,w2plantype,w2plansubtype,employercontribhow,employercontribute,employermaxmatchpercent,mustbeinplan,calcbasis,prdedgroupid,paytype,payableglacctid,expenseglacctid,federalincometax,stateincometax,localincometax,cityincometax,employeefica,companyfica,fui,sui,workmanscomp,vendorid,lastchangedate from prpension where id='.sqlprep($id));
              if (!$recordSet->EOF) {
                 $id=$recordSet->fields[0];
                 $name=$recordSet->fields[1];
                 $w2plantype=$recordSet->fields[2];
                 $w2plansubtype=$recordSet->fields[3];
                 $employercontribhow=$recordSet->fields[4];
                 $employercontribute=$recordSet->fields[5];
                 $employermaxmatchpercent=$recordSet->fields[6];
                 $mustbeinplan=$recordSet->fields[7];
                 $calcbasis=$recordSet->fields[8];
                 $prdedgroupid=$recordSet->fields[9];
                 $paytype=$recordSet->fields[10];
                 $payableglacctid=$recordSet->fields[11];
                 $expenseglacctid=$recordSet->fields[12];
                 $federalincometax=$recordSet->fields[13];
                 $stateincometax=$recordSet->fields[14];
                 $localincometax=$recordSet->fields[15];
                 $cityincometax=$recordSet->fields[16];
                 $employeefica=$recordSet->fields[17];
                 $companyfica=$recordSet->fields[18];
                 $fui=$recordSet->fields[19];
                 $sui=$recordSet->fields[20];
                 $workmanscomp=$recordSet->fields[21];
                 $vendorid=$recordSet->fields[22];
                 $lastchangedate=$recordSet->fields[23];
              } else {
                die (texterror($lang['STR_ID_HAS_NO_MATCH_IN_FILE']));
              };
     };
     echo '<form action="adminprpens.php" method="post" name="mainform"><table>';
     echo '<input type="hidden" name="lastchangedate" value="'.$lastchangedate.'">';
     echo '<input type="hidden" name="id" value="'.$id.'">';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PLAN_NAME'].':</td><td><input type="text" name="name" value="'.$name.'"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_PAYABLE_ACCOUNT'].':</td>';
     echo '<td><select name="payableglacctid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=21 and (glaccount.companyid=0 or glaccount.companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
           echo '<option value="'.$recordSet->fields[0].'"'.checkequal($payableglacctid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
           $recordSet->MoveNext();
     };
     echo '</select></td>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_EXPENSE_ACCOUNT_FOR_COMPANY_PORTION'].'</td>';
     echo '<td><select name="expenseglacctid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid>69 and (glaccount.companyid=0 or glaccount.companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
           echo '<option value="'.$recordSet->fields[0].'"'.checkequal($expenseglacctid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
           $recordSet->MoveNext();
     };
     echo '</select></td>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VENDOR'].':</td>';
     $vname='';
     if ($vendorid>0) {
        $recordSet=&$conn->Execute('select vendor.id,company.companyname from vendor,company where vendor.paytocompanyid=company.id and vendor.id='.sqlprep($vendorid));
        if (!$recordSet->EOF) $vname=$recordSet->fields[1];
     };
     echo '<td colspan="3"><input type="text" size="12" maxlength"30" name="vendorid" onchange="validateint(this)" value="'.$vendorid.'"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupvendor.php?name='."vendorid".'\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="'.IMAGE_VENDOR_LOOKUP.'" border="0" alt="Vendor Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'apvendadd.php\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="'.IMAGE_VENDOR_ADD.'" border="0" alt="Vendor Add"></a><font size="-1"> ('.$vname.')</font></td>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_W2_USE_WHAT_BOX_FOR_PLAN'].':</td><td><select name="w2plantype"'.INC_TEXTBOX.'>';
     echo '<option value="0"'.checkequal($w2plantype,0," selected").'>'.$lang['STR_BOX_13_QUALIFIED_PLAN'];
     echo '<option value="1"'.checkequal($w2plantype,1," selected").'>'.$lang['STR_BOX_14_QUALIFIED_PLAN'];
     echo '<option value="2"'.checkequal($w2plantype,2," selected").'>'.$lang['STR_BOX_11_NONQUALIFIED_PLAN'];
     echo '<option value="2"'.checkequal($w2plantype,3," selected").'>'.$lang['STR_BOX_12_NONQUALIFIED_PLAN'];
     echo '</select></td>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_IF_BOX_13_QUALIFIED_PLAN_LETTER_FOR_W2_PRINT'].':</td><td><input type="text" name="w2plansubtype" value="'.$w2plansubtype.'" size="1" '.INC_TEXTBOX.'></td></tr>';
     echo '<tr><th colspan="2">'.$lang['STR_EMPLOYER_CONTRIBUTION'].'</th></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMPLOYER_CONTRIBUTES_HOW'].':</td><td><select name="employercontribhow"'.INC_TEXTBOX.'>';
     echo '<option value="0"'.checkequal($employercontribhow,0," selected").'>'.$lang['STR_NO_CONTRIBUTION'];
     echo '<option value="1"'.checkequal($employercontribhow,1," selected").'>'.$lang['STR_FLAT_PERCENTAGE'];
     echo '<option value="2"'.checkequal($employercontribhow,2," selected").'>'.$lang['STR_MATCHING_PERCENTAGE'];
     echo '</select></td>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAXIMUM_PERCENT_MATCHED_BY_EMPLOYER'].':</td><td><input type="text" onchange="validatenum(this)" name="employermaxmatchpercent" value="'.$employermaxmatchpercent.'"'.INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMPLOYEE_MUST_PARTICIPATE'].':</td>';
     echo '<td><input name="mustbeinplan" type="checkbox" value="1"'.checkequal($mustbeinplan,1," checked").INC_TEXTBOX.'></td></tr>';
     echo '<tr><th colspan="2">'.$lang['STR_CALCULATION_DATA'].'</th></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BASIS_FOR_CALCULATION'].':</td><td><select name="calcbasis"'.INC_TEXTBOX.'>';
     echo '<option value="0"'.checkequal($calcbasis,0," selected").'>'.$lang['STR_ALL_PAY'];
     echo '<option value="1"'.checkequal($calcbasis,1," selected").'>'.$lang['STR_ST_PAY'];
     echo '<option value="2"'.checkequal($calcbasis,2," selected").'>'.$lang['STR_HOURLY_WAGES'];
     echo '</select></td>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEDUCTION_GROUP'].':</td><td><select name="prdedgroupid"'.INC_TEXTBOX.'><option value="0">';
           $recordSet2 = &$conn->Execute('select id, name from prdedgroup where gencompanyid='.sqlprep($active_company).' order by name');
           while (!$recordSet2->EOF) {
                   echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($prdedgroupid,$recordSet2->fields[0],' selected').'>'.$recordSet2->fields[1]."\n";
                   $recordSet2->MoveNext();
           };
           echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COVERS_WHICH_WORKERS'].':</td><td><select name="paytype"'.INC_TEXTBOX.'>';
     echo '<option value="0"'.checkequal($paytype,0," selected").'>'.$lang['STR_ALL_EMPLOYEES'];
     echo '<option value="1"'.checkequal($paytype,1," selected").'>'.$lang['STR_ONLY_HOURLY'];
     echo '<option value="2"'.checkequal($paytype,2," selected").'>'.$lang['STR_ONLY_SALARY'];
     echo '</select></td>';
     echo '<tr><th colspan="2">'.$lang['STR_WHICH_TAXES_ARE_CALCULATED_ON_WAGES_REDUCED_BY_PENSION_AMOUNTS'].'</th></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FEDERAL_INCOME_TAX'].':</td>';
     echo '<td><input name="federalincometax" type="checkbox" value="1"'.checkequal($federalincometax,1," checked").INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE_INCOME_TAX'].':</td>';
     echo '<td><input name="stateincometax" type="checkbox" value="1"'.checkequal($stateincometax,1," checked").INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LOCAL_INCOME_TAX'].':</td>';
     echo '<td><input name="localincometax" type="checkbox" value="1"'.checkequal($localincometax,1," checked").INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY_INCOME_TAX'].':</td>';
     echo '<td><input name="cityincometax" type="checkbox" value="1"'.checkequal($cityincometax,1," checked").INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMPLOYEE_FICA_MEDICARE'].':</td>';
     echo '<td><input name="employeefica" type="checkbox" value="1"'.checkequal($employeefica,1," checked").INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY_FICA_MEDICARE'].':</td>';
     echo '<td><input name="companyfica" type="checkbox" value="1"'.checkequal($companyfica,1," checked").INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FEDERAL_UNEMPLOYMENT_TAX'].':</td>';
     echo '<td><input name="fui" type="checkbox" value="1"'.checkequal($fui,1," checked").INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE_UNEMPLOYMENT_TAX'].':</td>';
     echo '<td><input name="sui" type="checkbox" value="1"'.checkequal($sui,1," checked").INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WORKMANS_COMP'].'</td>';
     echo '<td><input name="workmanscomp" type="checkbox" value="1"'.checkequal($workmanscomp,1," checked").INC_TEXTBOX.'></td></tr>';
     echo '</table><input type="submit" name="'.$lang['STR_SAVE'].'" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'adminprpens.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_PLAN'].'</a>';
   } else {
            // get name of pension plan, or add a new one
            echo '<form action="adminprpens.php" method="post" ><table>';
            echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SELECT_PLAN'].': </td><td><select name="id"'.INC_TEXTBOX.'>';
            $recordSet=&$conn->Execute('select id,name from prpension where cancel=0 and gencompanyid='.sqlprep($active_company).' order by name');
            while (!$recordSet->EOF) {
                     echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                     $recordSet->MoveNext();
            };
            echo '</select></td></tr></table><br><input type="submit" name="which" value="'.$lang['STR_EDIT_SELECTION'].'"><br>';
            echo '<br><input type="submit" name="which" value="'.$lang['STR_ADD_NEW_PLAN'].'"></form>';
            
            echo '</center>';
    };

?>

<?php include('includes/footer.php'); ?>
