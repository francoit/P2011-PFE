<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>
<?php include('includes/glfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
        echo '<center>';
        echo '<b>';
        echo texttitle($companyname);
        echo '</b>';
        //echo '&nbsp';
        echo texttitle($lang['STR_CHECK_WRITING']);
        if ($period&&$checkdate&&$checkacctid&&$checknumber&&$endorser) {
            checkpermissions('pay');
            // read general posting accounts from prcompany
            $recordSet=&$conn->Execute('select fedtaxnum,glfitpayableid,glficapayableid,glficaexpenseid,glfuipayableid,glfuiexpenseid,glmedicarepayableid,glmedicareexpenseid,glsuipayableid,glsuiexpenseid,glmiscdedpayableid,gltaxexemptexpenseid,glworkmanscomppayableid,glworkmanscompexpenseid,post2payables from prcompany where id='.sqlprep($active_company));
            if (!$recordSet->EOF) {
                  $fedtaxnum=$recordSet->fields[0];
                  $glfitpayableid=$recordSet->fields[1];
                  $glficapayableid=$recordSet->fields[2];
                  $glficaexpenseid=$recordSet->fields[3];
                  $glfuipayableid=$recordSet->fields[4];
                  $glfuiexpenseid=$recordSet->fields[5];
                  $glmedicarepayableid=$recordSet->fields[6];
                  $glmedicareexpenseid=$recordSet->fields[7];
                  $glsuipayableid=$recordSet->fields[8];
                  $glsuiexpenseid=$recordSet->fields[9];
                  $glmiscdedpayableid=$recordSet->fields[10];
                  $gltaxexemptexpenseid=$recordSet->fields[11];
                  $glworkmanscomppayableid=$recordSet->fields[12];
                  $glworkmanscompexpenseid=$recordSet->fields[13];
                  $post2payables=$recordSet->fields[14];
            } else {
                    die (TextError($lang['STR_STANDARD_GL_ACCOUNTS_NOT_SETUP_FOR_PAYROLL']));
            };

            // read posting gl account for checking account used
            $recordSet=&$conn->Execute('select glaccountid from checkacct where pay=1 and gencompanyid='.sqlprep($active_company).' and id='.sqlprep($checkacctid));
            if (!$recordSet->EOF) $ckglacctid=$recordSet->fields[0];
            $amount=0;
            unset($checkstr);
            unset($chkstr);
            $recordSet=&$conn->Execute('select premplweek.id,sum(premplweek.netpay), sum(premplweek.cficatax+premplweek.ficatax+premplweek.cmedicarededuction+premplweek.medicarededuction+premplweek.federaltax-premplweek.eiccredit), sum(premplweek.cficatax),sum(premplweek.ficatax),sum(premplweek.cmedicarededuction),sum(premplweek.medicarededuction),sum(premplweek.federaltax-premplweek.eiccredit) from premplweek,premployee,prperiod left join prstate on premployee.prstateid=prstate.id left join genstate on prstate.genstateid=genstate.id left join prlocal on premployee.prlocalid=prlocal.id left join prcity on premployee.prcityid=prcity.id where premplweek.prperiodid=prperiod.id and premplweek.employeeid=premployee.id and premplweek.prperiodid='.sqlprep(${'prperiodid'.$period}).' and premplweek.periodbegindate='.sqlprep(${'periodbegindate'.$period}).' and premplweek.periodenddate='.sqlprep(${'periodenddate'.$period}).' and premplweek.cancel=0 and premplweek.calculatestatus=1 and premplweek.checkid=0 and premployee.gencompanyid='.sqlprep($active_company).' group by premployee.id order by premployee.lastname,premployee.firstname');
            echo texttitle('<font size="-1">'.${'periodbegindate'.$period}.' - '.${'periodenddate'.$period}.'</font>');
            while (!$recordSet->EOF) { //write check
                  if ($recordSet->fields[1]>0) {
                      if ($conn->Execute('insert into chk (wherefrom,amount,paytype,checkdate,checkaccountid,checknumber,entrydate,entryuserid,lastchangeuserid) values (6,'.sqlprep($recordSet->fields[1]).',0,'.sqlprep($checkdate).','.sqlprep($checkacctid).','.sqlprep($checknumber).',NOW(),'.sqlprep($userid).','.sqlprep($userid).')') === false) echo texterror('Check insert failed.');
                      $recordSet2=&$conn->SelectLimit('select id from chk where wherefrom=6 and checkdate='.sqlprep($checkdate).' and checkaccountid='.sqlprep($checkacctid).' and checknumber='.sqlprep($checknumber).' and entryuserid='.sqlprep($userid).' order by entrydate desc, id desc',1);
                      if ($conn->Execute('update premplweek set checkid='.sqlprep($recordSet2->fields[0]).', lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($recordSet->fields[0])) === false) echo texterror('premplweek update failed.');
                      $checkstr.='checknbr[]='.$recordSet2->fields[0].'&';
                      if (isset($chkstr)) $chkstr=$chkstr.' or ';
                      $chkstr.='chk.id='.$recordSet2->fields[0];
                      $depositckamount+=$recordSet->fields[2];
                      $depositcfica+=$recordSet->fields[3];
                      $depositefica+=$recordSet->fields[4];
                      $depositcmed+=$recordSet->fields[5];
                      $depositemed+=$recordSet->fields[6];
                      $depositfit+=$recordSet->fields[7];
                      $checknumber++;
                  };
                  $recordSet->MoveNext();
            };
            if ($chkstr) $chkstr=' and ('.$chkstr.') ';
            if ($writedep) { //write tax deposit check
                if ($conn->Execute('insert into chk (wherefrom,amount,paytype,checkdate,checkaccountid,checknumber,entrydate,entryuserid,lastchangeuserid) values (6,'.sqlprep($depositckamount).',0,'.sqlprep($checkdate).','.sqlprep($checkacctid).','.sqlprep($checknumber).',NOW(),'.sqlprep($userid).','.sqlprep($userid).')') === false) echo texterror('Tax Check insert failed.');
                $recordSet2=&$conn->SelectLimit('select id from chk where wherefrom=6 and checkdate='.sqlprep($checkdate).' and checkaccountid='.sqlprep($checkacctid).' and checknumber='.sqlprep($checknumber).' and entryuserid='.sqlprep($userid).' order by entrydate desc, id desc',1);
                if ($conn->Execute('insert into prdepositchecks (checkid,prperiodid,periodbegindate,periodenddate,gencompanyid) VALUES ('.sqlprep($recordSet2->fields[0]).','.sqlprep($period).','.sqlprep(${'periodbegindate'.$period}).','.sqlprep(${'periodenddate'.$period}).','.sqlprep($active_company).')') === false) echo texterror('prdepositcheck insert failed.');
                //post deposit check to gl - start with main voucher info
                $voucherid=gltransvoucheradd($recordSet->fields[0],$lang['STR_TAX_DEPOSIT_CHECK'],$checkdate,6);
                if (!$voucherid) die(texterror($lang['STR_ERROR_ADDING_GLTRANSVOUCHER_RECORD_-_TAX_DEPOSIT_CHECK']));

                // Post to gl checking the (-)net amount of the check //
                if ($depositckamount<>0) gltransactionadd($voucherid,-($depositckamount),$ckglacctid);
                // Now reduce payables for deposit check
                if ($depositcfica+$depositefica<>0) gltransactionadd($voucherid,$depositcfica+$depositefica,$glficapayableid);
                if ($depositcmed+$depositemed<>0) gltransactionadd($voucherid,$depositcmed+$depositemed,$glmedicarepayableid);
                if ($depositfit<>0) gltransactionadd($voucherid,$depositfit,$glfitpayableid);
                $checknumber++;
            };
            if ($conn->Execute('update checkacct set lastchecknumberused='.($checknumber-1).' where id='.sqlprep($checkacctid)) === false) echo texterror('Check acct update failed.');
            //////////   GL POSTING STARTS HERE   ///////////////////
            if ($chkstr) {
             $recordSet = &$conn->Execute("select company.companyname, company.address1, company.address2, company.city, company.state, company.zip, chk.amount, chk.checkdate, chk.amount, premployee.ssnumber, ".sqlprep($endorser).", chk.checknumber, chk.id, premplweek.prperiodid, premplweek.periodbegindate, premplweek.periodenddate, premplweek.federaltax, premplweek.ficatax, premplweek.statetax, premplweek.localtax, premplweek.citytax, premplweek.miscdeduction, premplweek.medicarededuction, premplweek.misctaxablepay, premplweek.miscnontaxablepay, premplweek.id, premployee.prlocalid, premployee.prcityid, premployee.prstateid, premployee.glaccountid,premplweek.eiccredit,premplweek.fuitax,premplweek.cficatax,premplweek.cmedicarededuction,premplweek.suitax from chk, premployee, premplweek, company, checkacct where checkacct.id=chk.checkaccountid ".$chkstr." and chk.id=premplweek.checkid and premplweek.employeeid=premployee.id and premployee.companyid=company.id");
             while (!$recordSet->EOF) {
                /*** Here's where we write to GL Posting file  ***/
                // First create gltransvoucher entry for this check
                $checknum=$recordSet->fields[11];
                $checkid=$recordSet->fields[12];
                $employee=$recordSet->fields[0];
                $checkdate=$recordSet->fields[7];
                $amount=$recordSet->fields[8];
                $premplweekid=$recordSet->fields[25];
                $voucherid=gltransvoucheradd($checkid,$employee,$checkdate,6);
                if (!$voucherid) die(texterror($lang['STR_ERROR_ADDING_GLTRANSVOUCHER_RECORD_EMPLOYEE'] .$employee));

                // Post to gl checking the (-)net amount of the check //
                if ($amount<>0) gltransactionadd($voucherid,-($amount),$ckglacctid);
                //================================================
                // Post to gl payables the (-) amount of each DEDUCTION
                $amount=$recordSet->fields[16]-$recordSet->fields[30]; // federal tax withheld
                if ($amount<>0) gltransactionadd($voucherid, -($amount),$glfitpayableid);
                $amount=$recordSet->fields[17]; // fica tax withheld
                if ($amount<>0) gltransactionadd($voucherid, -($amount),$glficapayableid);
                $amount=$recordSet->fields[22]; // medicare tax withheld
                if ($amount<>0) gltransactionadd($voucherid, -($amount),$glmedicarepayableid);
                $prlocalid=$recordSet->fields[26];
                $prcityid=$recordSet->fields[27];
                $prstateid=$recordSet->fields[28];
                $recordSet2 = &$conn->Execute('select glacctid from prlocal where id='.sqlprep($prlocalid));
                if (!$recordSet2->EOF) $gllocaltaxacct=$recordSet2->fields[0];
                $recordSet2 = &$conn->Execute('select glacctid from prcity where id='.sqlprep($prcityid));
                if (!$recordSet2->EOF) $glcitytaxacct=$recordSet2->fields[0];
                $recordSet2 = &$conn->Execute('select glacctid from prstate where id='.sqlprep($prstateid));
                if (!$recordSet2->EOF) $glstatetaxacct=$recordSet2->fields[0];
                $amount=$recordSet->fields[18]; // state tax withheld
                if ($amount<>0) gltransactionadd($voucherid, -($amount),$glstatetaxacct);
                $amount=$recordSet->fields[19]; // local tax withheld
                if ($amount<>0) gltransactionadd($voucherid, -($amount),$gllocaltaxacct);
                $amount=$recordSet->fields[20]; // city tax withheld
                if ($amount<>0) gltransactionadd($voucherid, -($amount),$glcitytaxacct);
                $amount=$recordSet->fields[21]; // misc. deduction
                if ($amount<>0) gltransactionadd($voucherid, -($amount),$glmiscdedpayableid);
                $recordSet2=&$conn->Execute('select premplweekdeddetail.amount,prbended.payableglacctid,prpension.payableglacctid,prempldeduction.glaccountid from premplweekdeddetail left join prempldeduction on premplweekdeddetail.prempldeductionid=prempldeduction.id left join prbended on premplweekdeddetail.prbendedid=prbended.id left join prpension on premplweekdeddetail.prpensionid=prpension.id where premplweekdeddetail.premplweekid='.sqlprep($premplweekid).' and premplweekdeddetail.dedtype=0');
                while (!$recordSet2->EOF) { // other deductions
                   $amount=$recordSet2->fields[0] ;
                   $glacctid=$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3];
                   if ($amount<>0) gltransactionadd($voucherid, -($amount),$glacctid);
                   $recordSet2->MoveNext();
                };
                //==================================================

                // Post to gl cost of goods the (+) amount of PAY
                $amount=$recordSet->fields[23]; // misc.  taxable pay
                $glacct=$recordSet->fields[29];
                if ($amount<>0) gltransactionadd($voucherid, $amount,$glacct);
                $amount=$recordSet->fields[24]; // misc. non-taxable pay
                if ($amount<>0) gltransactionadd($voucherid, $amount,$glacct);

                $recordSet2 = &$conn->Execute('select amount, glaccountid from premplweekpaydetail  where premplweekid='.sqlprep($premplweekid));
                while (!$recordSet2->EOF) { //  pay amounts
                   $amount=$recordSet2->fields[0] ;
                   $glacctid=$recordSet2->fields[1];
                   if ($amount<>0) gltransactionadd($voucherid, $amount,$glacctid);
                   $recordSet2->MoveNext();
                };
                //======================================================

                // Post to gl payables & expenses the (+)/(-) amount company contributions
                $amount=$recordSet->fields[31]; //  fui tax
                if ($amount<>0) gltransactionadd($voucherid, $amount,$glfuiexpenseid);
                if ($amount<>0) gltransactionadd($voucherid, -($amount),$glfuipayableid);
                $amount=$recordSet->fields[32]; //  company fica tax
                if ($amount<>0) gltransactionadd($voucherid, $amount,$glficaexpenseid);
                if ($amount<>0) gltransactionadd($voucherid, -($amount),$glficapayableid);
                $amount=$recordSet->fields[33]; //  company medicare
                if ($amount<>0) gltransactionadd($voucherid, $amount,$glmedicareexpenseid);
                if ($amount<>0) gltransactionadd($voucherid, -($amount),$glmedicarepayableid);
                $amount=$recordSet->fields[31]; //  sui tax
                if ($amount<>0) gltransactionadd($voucherid, $amount,$glsuiexpenseid);
                if ($amount<>0) gltransactionadd($voucherid, -($amount),$glsuipayableid);

                $recordSet2=&$conn->Execute('select premplweekdeddetail.amount,prbended.payableglacctid,prpension.payableglacctid,prbended.expenseglacctid,prpension.expenseglacctid from premplweekdeddetail left join prempldeduction on premplweekdeddetail.prempldeductionid=prempldeduction.id left join prbended on premplweekdeddetail.prbendedid=prbended.id left join prpension on premplweekdeddetail.prpensionid=prpension.id where premplweekdeddetail.premplweekid='.sqlprep($premplweekid).' and premplweekdeddetail.dedtype=1');
                while (!$recordSet2->EOF) { // other company contributions
                   $amount=$recordSet2->fields[0] ;
                   $glacctid=$recordSet2->fields[1].$recordSet2->fields[2];
                   $eglacctid=$recordSet2->fields[3].$recordSet2->fields[4];
                   if ($amount<>0) gltransactionadd($voucherid, -($amount),$glacctid);
                   if ($amount<>0) gltransactionadd($voucherid, $amount,$eglacctid);
                   $recordSet2->MoveNext();
                };

                $recordSet->MoveNext();
             };
            };
            ///////////////////////////////////////////////////////////
            echo textsuccess($lang['STR_CHECK_WRITTIN_SUCCESSFULLY']);
            echo '<a href="prchecks.php?endorser='.$endorser.'&'.$checkstr.'&depcheck=1">'.$lang['STR_PRINT_CHECK'].'</a><br>';
        } else {
          $recordSet=&$conn->CacheExecute(10,'select distinct prperiod.id,premplweek.periodbegindate,premplweek.periodenddate,prperiod.name from premplweek,prperiod,premployee where premplweek.employeeid=premployee.id and prperiod.id=premplweek.prperiodid and premplweek.cancel=0 and premplweek.calculatestatus=1 and premplweek.checkid=0 and premployee.gencompanyid='.sqlprep($active_company).' order by premplweek.periodbegindate desc,prperiod.name');
          if ($recordSet->EOF) die(texterror($lang['STR_NO_CALCULATED_UNPAID_HOURS_FOUND']));
          echo texttitle('<font size="-1">'.$lang['STR_SELECT_PAY_PERIOD'].'</font>');
          echo '<form method="post" name="mainform" action="prcheckwrite.php"><input type="hidden" name="nonprintable" value="1"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PERIOD'].':</td><td><select name="period"'.INC_TEXTBOX.'>';
          $i=1;
          while (!$recordSet->EOF) {
              echo '<option value="'.$i.'">'.$recordSet->fields[1].' - '.$recordSet->fields[2].' - '.$recordSet->fields[3]."\n";
              $recordSet->MoveNext();
              $i++;
          };
          echo '</select></td></tr>';
          $recordSet=&$conn->CacheExecute(10,'select distinct prperiod.id,premplweek.periodbegindate,premplweek.periodenddate,prperiod.name from premplweek,prperiod,premployee where premplweek.employeeid=premployee.id and prperiod.id=premplweek.prperiodid and premplweek.cancel=0 and premplweek.calculatestatus=1 and premplweek.checkid=0 and premployee.gencompanyid='.sqlprep($active_company).' order by premplweek.periodbegindate desc,prperiod.name');
          $i=1;
          while (!$recordSet->EOF) {
              echo '<input type="hidden" name="prperiodid'.$i.'" value="'.$recordSet->fields[0].'">'; //passes prperiodid, so we can uniquely identify period to pay
              echo '<input type="hidden" name="periodbegindate'.$i.'" value="'.$recordSet->fields[1].'">'; //passes periodbegindate, so we can uniquely identify period to pay
              echo '<input type="hidden" name="periodenddate'.$i.'" value="'.$recordSet->fields[2].'">'; //passes periodenddate, so we can uniquely identify period to pay
              $recordSet->MoveNext();
              $i++;
          };

          $recordSet=&$conn->Execute('select count(*) from checkacct where pay=1 and gencompanyid='.sqlprep($active_company));
          if ($recordSet->fields[0]==1) {
              $recordSet=&$conn->Execute('select id,lastchecknumberused from checkacct where pay=1 and gencompanyid='.sqlprep($active_company));
              $checkacctid=$recordSet->fields[0];
              $checknumber=($recordSet->fields[1]+1);
              echo '<input type="hidden" name="checkacctid" value="'.$checkacctid.'">';
          } else {
              echo '<script language="JavaScript">'."\n";
              echo '    function changenum() {'."\n";
              echo '       var checknum'."\n";
              echo '       var endorser'."\n";
              echo '       var checkacctid'."\n";
              echo '       checkacctid=document.mainform.checkacctid.value'."\n";
              echo '       checknum=eval("document.mainform.acct" + checkacctid + ".value")'."\n";
              echo '       document.mainform.checknumber.value=checknum'."\n";
              echo '       endorser=eval("document.mainform.end" + checkacctid + ".value")'."\n";
              echo '       document.mainform.endorser.value=endorser'."\n";
              echo '    }'."\n";
              echo '</script>'."\n";
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHECKING_ACCOUNT'].':</td><td><select name="checkacctid"'.INC_TEXTBOX.' onchange="changenum()">';
              $recordSet=&$conn->CacheExecute(10,'select id,lastchecknumberused,name,defaultendorser from checkacct where pay=1 and gencompanyid='.sqlprep($active_company));
              $checknumber=($recordSet->fields[1]+1);
              $endorser=$recordSet->fields[3];
              while (!$recordSet->EOF) {
                  echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[2]."\n";
                  $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
              $recordSet=&$conn->CacheExecute(10,'select id,lastchecknumberused,name,defaultendorser from checkacct where pay=1 and gencompanyid='.sqlprep($active_company));
              while (!$recordSet->EOF) {
                  echo '<input type="hidden" name="acct'.$recordSet->fields[0].'" value="'.($recordSet->fields[1]+1).'">';
                  echo '<input type="hidden" name="end'.$recordSet->fields[0].'" value="'.$recordSet->fields[3].'">';
                  $recordSet->MoveNext();
                  $i++;
              };
          };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGINNING_CHECK_NUMBER'].':</td><td><input type="text" name="checknumber" value="'.$checknumber.'" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHECK_ENDORSER'].':</td><td><input type="text" name="endorser" value="'.$endorser.'" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHECK_DATE'].':</td><td><input type="text" name="checkdate" value="'.createtime('Y-m-d').'" onchange="formatDate(this)" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WRITE_DEPOSIT_CHECK'].':</td><td><input type="checkbox" name="writedep" value="1" checked'.INC_TEXTBOX.'></td></tr>';
          echo '</table><input type="submit" value="'.$lang['STR_CONTINUE'].'">';
          echo '</form>';
          
          echo '</center>';
        };
?>
<?php include('includes/footer.php'); ?>
