<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>


<?php echo '<center>';
     echo texttitle($lang['STR_PAYROLL_STANDARD_GL_ACCOUNTS']);
     if ($fedtaxnum) {
          $recordSet=&$conn->Execute('select count(*) from prcompany where id='.sqlprep($active_company));
          if (!$recordSet->EOF) if ($recordSet->fields[0]) { // found an entry and it is > 0 for count. need to update entry
                prcompanyinfoupdate($fedtaxnum,$w2companyname,$w2companyaddress1,$w2companyaddress2,$w2citystatezip, $stateunemplnum,$glcheckaccountid,$glfitpayableid,$glficapayableid,$glficaexpenseid,$glfuipayableid,$glfuiexpenseid,$glmedicarepayableid,$glmedicareexpenseid,$glsuipayableid,$glsuiexpenseid,$glmiscdedpayableid,$gltaxexemptexpenseid,$glworkmanscomppayableid,$glworkmanscompexpenseid,$post2payables,$checkacctid,$autoprintdeposit,$depositvendorid,$lastchangedate);
          } else { //not found, so add new entry now.
                $conn->Execute('insert into prcompany (id,fedtaxnum,w2companyname,w2companyaddress1,w2companyaddress2,w2citystatezip, stateunemplnum,glcheckaccountid,glfitpayableid,glficapayableid,glficaexpenseid,glfuipayableid,glfuiexpenseid,glmedicarepayableid,glmedicareexpenseid,glsuipayableid,glsuiexpenseid,glmiscdedpayableid,gltaxexemptexpenseid,glworkmanscomppayableid,glworkmanscompexpenseid,post2payables,checkacctid,autoprintdeposit,depositvendorid,entrydate,entryuserid,lastchangeuserid) values ('.sqlprep($active_company).', '.sqlprep($fedtaxnum).','.sqlprep($w2companyname).','.sqlprep($w2companyaddress1).','.sqlprep($w2companyaddress2).','.sqlprep($w2citystatezip).', '.sqlprep($stateunemplnum).','.sqlprep($glcheckaccountid).','.sqlprep($glfitpayableid).','.sqlprep($glficapayableid).','.sqlprep($glficaexpenseid).','.sqlprep($glfuipayableid).','.sqlprep($glfuiexpenseid).','.sqlprep($glmedicarepayableid).','.sqlprep($glmedicareexpenseid).','.sqlprep($glsuipayableid).','.sqlprep($glsuiexpenseid).','.sqlprep($glmiscdedpayableid).','.sqlprep($gltaxexemptexpenseid).','.sqlprep($glworkmanscomppayableid).','.sqlprep($glworkmanscompexpenseid).','.sqlprep($post2payables).','.sqlprep($checkacctid).','.sqlprep($autoprintdeposit).','.sqlprep($depositvendorid).',NOW(),'.sqlprep($userid).','.sqlprep($userid).')');
          };
     } else {
       $recordSet=&$conn->Execute('select fedtaxnum,w2companyname,w2companyaddress1,w2companyaddress2,w2citystatezip, stateunemplnum,glcheckaccountid,glfitpayableid,glficapayableid,glficaexpenseid,glfuipayableid,glfuiexpenseid,glmedicarepayableid,glmedicareexpenseid,glsuipayableid,glsuiexpenseid,glmiscdedpayableid,gltaxexemptexpenseid,glworkmanscomppayableid,glworkmanscompexpenseid,post2payables,checkacctid,autoprintdeposit,depositvendorid,lastchangedate from prcompany where id='.sqlprep($active_company));
       if (!$recordSet->EOF) {
          $fedtaxnum=$recordSet->fields[0];
          $w2companyname=$recordSet->fields[1];
          $w2companyaddress1=$recordSet->fields[2];
          $w2companyaddress2=$recordSet->fields[3];
          $w2citystatezip=$recordSet->fields[4];
          $stateunemplnum=$recordSet->fields[5];
          $glcheckaccountid=$recordSet->fields[6];
          $glfitpayableid=$recordSet->fields[7];
          $glficapayableid=$recordSet->fields[8];
          $glficaexpenseid=$recordSet->fields[9];
          $glfuipayableid=$recordSet->fields[10];
          $glfuiexpenseid=$recordSet->fields[11];
          $glmedicarepayableid=$recordSet->fields[12];
          $glmedicareexpenseid=$recordSet->fields[13];
          $glsuipayableid=$recordSet->fields[14];
          $glsuiexpenseid=$recordSet->fields[15];
          $glmiscdedpayableid=$recordSet->fields[16];
          $gltaxexemptexpenseid=$recordSet->fields[17];
          $glworkmanscomppayableid=$recordSet->fields[18];
          $glworkmanscompexpenseid=$recordSet->fields[19];
          $post2payables=$recordSet->fields[20];
          $checkacctid=$recordSet->fields[21];
          $autoprintdeposit=$recordSet->fields[22];
          $depositvendorid=$recordSet->fields[23];
          $lastchangedate=$recordSet->fields[24];

       };
       echo '<form action="adminprglacct.php" method="post" name="mainform"><table>';
       echo '<input type="hidden" name="lastchangedate" value="'.$lastchangedate.'">';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FEDERAL_TAX_ID'].': </td><td><input type="text" name="fedtaxnum" value="'.$fedtaxnum.'"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE_UNEMPLOYMENT_ID'].': </td><td><input type="text" name="stateunemplnum" value="'.$stateunemplnum.'"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_W2_COMPANY_NAME'].': </td><td><input type="text" name="w2companyname" value="'.$w2companyname.'"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_W2_ADDRESS_1'].':</td><td><input type="text" name="w2companyaddress1" value="'.$w2companyaddress1.'"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_W2_ADDRESS_2'].': </td><td><input type="text" name="w2companyaddress2" value="'.$w2companyaddress2.'"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_W2_CITY_STATE_AND_ZIP'].': </td><td><input type="text" name="w2citystatezip" value="'.$w2citystatezip.'"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POST_TO_PAYABLES'].': </td><td><input type="checkbox" name="post2payables" value="1"'.checkequal($post2payables,1," checked").INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEFAULT_CHECKING_ACCOUNT'].': </td><td><select name="checkacctid"'.INC_TEXTBOX.'>';
       $recordSet=&$conn->Execute('select id,name from checkacct where pay=1 and gencompanyid='.sqlprep($active_company));
       while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($checkaccttid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1]."\n";
          $recordSet->MoveNext();
       };
       echo '</select></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"><nbsp>'.$lang['STR_AUTO_PRINT_DEPOSIT_CHECK'].': </nbsp></td><td><input type="checkbox" name="autoprintdeposit" value="1"'.checkequal($autoprintdeposit,1," checked").INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VENDOR_FOR_PAYROLL_DEPOSIT_CHECK'].':</td>';
       $vname='';
       if ($depositvendorid>0) {
            $recordSet=&$conn->Execute('select vendor.id,company.companyname from vendor,company where vendor.paytocompanyid=company.id and vendor.id='.sqlprep($depositvendorid));
            if (!$recordSet->EOF) $vname=$recordSet->fields[1];
       };
       echo '<td><input type="text" size="12" maxlength="30" name="depositvendorid" value="'.$depositvendorid.'"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupvendor.php?name='."depositvendorid".'\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Vendor Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'apvendadd.php\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="images/addbutton.png" border="0" alt="Payroll GL Account"></a><font size="-1"> </font></td></tr>';
       echo '<tr><th colspan="2" align="center">'.$lang['STR_GENERAL_LEDGER_ACCOUNTS_FOR_POSTING'].'</th></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHECKING'].': </td><td><select name="glcheckaccountid"'.INC_TEXTBOX.'>';
       $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=10 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
       while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($glcheckaccountid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FEDERAL_INCOME_TAX_PAYABLE'].': </td><td><select name="glfitpayableid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=21 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($glfitpayableid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FICA_PAYABLE'].': </td><td><select name="glficapayableid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=21 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($glficapayableid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FICA_COMPANY_EXPENSE'].': </td><td><select name="glficaexpenseid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (glaccount.accounttypeid=70 or glaccount.accounttypeid=80) and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($glficaexpenseid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MEDICARE_PAYABLE'].': </td><td><select name="glmedicarepayableid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=21 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($glmedicarepayableid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MEDICARE_EXPENSE'].': </td><td><select name="glmedicareexpenseid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (glaccount.accounttypeid=70 or glaccount.accounttypeid=80) and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($glmedicareexpenseid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FEDERAL_INCOME_TAX_PAYABLE'].': </td><td><select name="glfuipayableid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=21 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($glfuipayableid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FEDERAL_UNEMPLOYMENT_TAX_EXPENSE'].': </td><td><select name="glfuiexpenseid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (glaccount.accounttypeid=70 or glaccount.accounttypeid=80) and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($glfuiexpenseid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FEDERAL_UNEMPLOYMENT_TAX_PAYABLE'].': </td><td><select name="glsuipayableid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=21 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($glsuipayableid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE_UNEMPLOYMENT_TAX'].': </td><td><select name="glsuiexpenseid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (glaccount.accounttypeid=70 or glaccount.accounttypeid=80) and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($glsuiexpenseid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WORKMANS_COMP_PAYABLE'].': </td><td><select name="glworkmanscomppayableid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=21 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($glworkmanscomppayableid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WORKMANS_COMP_EXPENSE'].': </td><td><select name="glworkmanscompexpenseid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (glaccount.accounttypeid=70 or glaccount.accounttypeid=80) and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($glworkmanscompexpenseid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MISC_DEDUCTION_PAYABLE'].': </td><td><select name="glmiscdedpayableid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($glmiscdedpayableid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MISC_TAX_EXEMPT_PAY_EXPENSE'].': </td><td><select name="gltaxexemptexpenseid"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (glaccount.accounttypeid=70 or glaccount.accounttypeid=80) and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($gltaxexemptexpenseid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '</table><br><input type="submit" value="'.$lang['STR_SAVE'].'"></form>';
     
     echo '</center>';
   };
?>

<?php include('includes/footer.php'); ?>
