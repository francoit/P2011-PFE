<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
        echo '<center>';
        echo texttitle($companyname. $lang['STR_CHECK_SUMMARY']);
        if ($period) {
             $prperiodid=${'prperiodid'.$period};
              $recordSet=&$conn->Execute('select premplweek.id,premplweek.employeeid,premployee.firstname,premployee.lastname,prperiod.name,prperiod.numperyear,sum(premplweek.statetax),genstate.stateinit,sum(premplweek.localtax),prlocal.abrev,sum(premplweek.citytax),prcity.abrev,sum(premplweek.federaltax),sum(premplweek.ficatax),sum(premplweek.medicarededuction),sum(premplweek.misctaxablepay),sum(premplweek.miscnontaxablepay),sum(premplweek.tipspay),sum(premplweek.tipsaswages),sum(premplweek.netpay),sum(premplweek.miscdeduction),sum(premplweek.fuitax), sum(premplweek.suitax), sum(premplweek.cficatax), sum(premplweek.cmedicarededuction), chk.checknumber, chk.checkdate, dc.checknumber, dc.checkdate, dc.amount, chk.id, dc.id from premplweek,premployee,prperiod left join chk on premplweek.checkid=chk.id left join prstate on premplweek.prstateid=prstate.id left join genstate on prstate.genstateid=genstate.id left join prlocal on premplweek.prlocalid=prlocal.id left join prcity on premplweek.prcityid=prcity.id left join prdepositchecks on (prdepositchecks.prperiodid='.sqlprep($prperiodid).' and prdepositchecks.periodbegindate='.sqlprep(${'periodbegindate'.$period}).' and prdepositchecks.periodenddate='.sqlprep(${'periodenddate'.$period}).' and prdepositchecks.gencompanyid='.sqlprep($active_company).') left join chk as dc on prdepositchecks.checkid=dc.id where premplweek.prperiodid=prperiod.id and premplweek.employeeid=premployee.id and premplweek.prperiodid='.sqlprep($prperiodid).' and premplweek.periodbegindate='.sqlprep(${'periodbegindate'.$period}).' and premplweek.periodenddate='.sqlprep(${'periodenddate'.$period}).' and premplweek.cancel=0 and premployee.gencompanyid='.sqlprep($active_company).' group by premplweek.employeeid order by premployee.lastname,premployee.firstname');
              echo texttitle('<font size="-1">'.$recordSet->fields[4].': '.${'periodbegindate'.$period}.' - '.${'periodenddate'.$period}.'</font>');
              $factor=$recordSet->fields[5]; //numperyear of prperiods
              if ($factor==0) die(texterror($lang['STR_NUMBER_OF_PERIODS_PER_YEAR_MUST_BE_GREATER_THAN_ZERO']));
              // get deposit check info
              if ($recordSet->fields[27]>0) $dcinfostr='<a target="_new" href="prchecks.php?depcheck=1&onlydep=1&checknbr='.$recordSet->fields[31].'">'.$lang['STR_CHECK'].': '.$recordSet->fields[27].'</a><br>';
              if ($recordSet->fields[28]>0) $dcinfostr.=$lang['STR_DATE'].$recordSet->fields[28].'<br>';
              if ($recordSet->fields[29]>0) $dcamount=num_format($recordSet->fields[29],2);
              echo '<table border="1" cellpadding="3"><tr><th colspan="2">'.$lang['STR_EMPLOYEE'].'</th><th rowspan="2">'.$lang['STR_DEDUCTIONS'].'</th><th rowspan="2">'.$lang['STR_NET_PAY'].'</th><th rowspan="2">'.$lang['STR_COMPANY_CONTRIBUTIONS'].'</th><th rowspan="2">'.$lang['STR_CHECK_INFO'].'</th></tr><tr><th>'.$lang['STR_HOURS'].'</th><th>'.$lang['STR_PAY'].'</th></tr>';
              $totccname=array();
              $totstname=array();
              $totltname=array();
              $totctname=array();
              $totedname=array();
              while (!$recordSet->EOF) {
                  $grosspay=0;
                  /*** hours ***/
                  $recordSet2=&$conn->Execute('select premplweekpaydetail.qty,premplweekpaydetail.rate,premplweekpaydetail.amount,prpaytype.name,prbended.name from premplweekpaydetail,prpaytype,premplweek left join prbended on prbended.id=premplweekpaydetail.prbendedid where premplweekpaydetail.premplweekid=premplweek.id and premplweekpaydetail.prpaytypeid=prpaytype.id and premplweek.employeeid='.sqlprep($recordSet->fields[1]).' and premplweek.prperiodid='.sqlprep($prperiodid).' and premplweek.periodbegindate='.sqlprep(${'periodbegindate'.$period}).' and premplweek.periodenddate='.sqlprep(${'periodenddate'.$period}).' and premplweek.cancel=0');
                  unset($hrstr);
                  unset($paystr);
                  while (!$recordSet2->EOF) {
                      if ($recordSet2->fields[4]) {
                        $hrstr.=substr($recordSet2->fields[4],0,8).'<br>';
                      } else {
                        $hrstr.=$recordSet2->fields[3].':&nbsp;'.$recordSet2->fields[0].'<br>';
                      };
                      $paystr.=CURRENCY_SYMBOL.num_format($recordSet2->fields[2],2).'<br>';
                      $grosspay+=$recordSet2->fields[2];
                      $recordSet2->MoveNext();
                  };
                  $grosspay+=$recordSet->fields[18]+$recordSet->fields[15]+$recordSet->fields[17]+$recordSet->fields[16]; //add stuff to gross and net
                  if ($recordSet->fields[15]>0) { $hrstr.='Misc.&nbsp;Taxable:<br>'; $paystr.=CURRENCY_SYMBOL.num_format($recordSet->fields[15],2).'<br>'; }; //misc tax pay
                  if ($recordSet->fields[16]>0) { $hrstr.='Misc.&nbsp;Non&nbsp;Taxable:<br>'; $paystr.=CURRENCY_SYMBOL.num_format($recordSet->fields[16],2).'<br>'; }; //misc nontax pay
                  if ($recordSet->fields[17]>0) { $hrstr.='Tips:<br>'; $paystr.=CURRENCY_SYMBOL.num_format($recordSet->fields[17],2).'<br>'; }; //tips
                  if ($recordSet->fields[18]>0) { $hrstr.='Tips&nbsp;as&nbsp;wages:<br>'; $paystr.=CURRENCY_SYMBOL.num_format($recordSet->fields[18],2).'<br>'; }; //tips as wages

                  /*** deductions ***/
                  unset($dedstr);
                  $recordSet2=&$conn->Execute('select premplweekdeddetail.amount,prempldeduction.description,prbended.name,prpension.name,prbended.bendedtype, premplweekdeddetail.prbendedid from premplweekdeddetail,premplweek left join prempldeduction on premplweekdeddetail.prempldeductionid=prempldeduction.id left join prbended on premplweekdeddetail.prbendedid=prbended.id left join prpension on premplweekdeddetail.prpensionid=prpension.id where premplweekdeddetail.premplweekid=premplweek.id and premplweek.prperiodid='.sqlprep($prperiodid).' and premplweek.periodbegindate='.sqlprep(${'periodbegindate'.$period}).' and premplweek.periodenddate='.sqlprep(${'periodenddate'.$period}).' and premplweek.employeeid='.$recordSet->fields[1].' and premplweek.cancel=0');
                  while (!$recordSet2->EOF) {
                      if ($recordSet2->fields[4]<>2&&$recordSet2->fields[0]<>0) {
                           $dedstr.=$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3].':&nbsp;'.CURRENCY_SYMBOL.num_format($recordSet2->fields[0],2).'<br>';
                           ${"toted".$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3]}+=$recordSet2->fields[0];
                           $totedname[]=$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3];
                      };
                      $recordSet2->MoveNext();
                  };
                  if ($recordSet->fields[20]>0) $dedstr.=$lang['STR_MISC_DEDUCTIONS'].CURRENCY_SYMBOL.num_format($recordSet->fields[20],2).'<br>'; //misc deductions
                  $totmiscded+=$recordSet->fields[20];

                  /*** taxes ***/
                  unset($taxstr);
                  if ($recordSet->fields[6]>0) $taxstr.=$recordSet->fields[7].$lang['STR_STATE_TAX'].CURRENCY_SYMBOL.num_format($recordSet->fields[6],2).'<br>';
                  ${"totst".$recordSet->fields[7]}+=$recordSet->fields[6];
                  $totstname[]=$recordSet->fields[7];
                  if ($recordSet->fields[8]>0) $taxstr.=$recordSet->fields[9].$lang['STR_LOCAL_TAX'].CURRENCY_SYMBOL.num_format($recordSet->fields[8],2).'<br>';
                  ${"totlt".$recordSet->fields[9]}+=$recordSet->fields[8];
                  $totltname[]=$recordSet->fields[9];
                  if ($recordSet->fields[10]>0) $taxstr.=$recordSet->fields[11].$lang['STR_CITY_TAX'].CURRENCY_SYMBOL.num_format($recordSet->fields[10],2).'<br>';
                  ${"totct".$recordSet->fields[11]}+=$recordSet->fields[10];
                  $totctname[]=$recordSet->fields[11];
                  if ($recordSet->fields[12]>0) $taxstr.=$lang['STR_FEDERAL_TAX'].CURRENCY_SYMBOL.num_format($recordSet->fields[12],2).'<br>';
                  $fedtax+=$recordSet->fields[12];
                  if ($recordSet->fields[13]>0) $taxstr.=$lang['STR_FICA'].CURRENCY_SYMBOL.num_format($recordSet->fields[13],2).'<br>';
                  $eficatax+=$recordSet->fields[13];
                  if ($recordSet->fields[14]>0) $taxstr.=$lang['STR_MEDICARE'].CURRENCY_SYMBOL.num_format($recordSet->fields[14],2).'<br>';
                  $emedtax+=$recordSet->fields[14];

                  /*** net pay***/
                  $netstr=$lang['STR_NET_PAY'].CURRENCY_SYMBOL.num_format($recordSet->fields[19],2).'<br>';
                  $totnetpay+=$recordSet->fields[19];
                  $netstr.=$lang['STR_GROSS_PAY'].CURRENCY_SYMBOL.num_format($grosspay,2).'<br>';
                  $totgrosspay+=$grosspay;
                  $recordSet2=&$conn->Execute('select sum(premplweekpaydetail.amount) from premplweekpaydetail,premplweek where premplweek.id=premplweekpaydetail.premplweekid and premplweek.periodenddate<='.sqlprep(${'periodenddate'.$period}).' and year(premplweek.periodenddate)=year(now()) and premplweek.employeeid='.sqlprep($recordSet->fields[1]));
                  $netstr.=$lang['STR_GROSS_PAY_PER_YEAR'].CURRENCY_SYMBOL.num_format($recordSet2->fields[0],2).'<br>';
                  $totgrosspayyear+=$recordSet2->fields[0];

                  /*** company contributions***/
                  unset($ccstr);
                  if ($recordSet->fields[21]>0) $ccstr.=$lang['STR_CO_FUI_TAX'].CURRENCY_SYMBOL.num_format($recordSet->fields[21],2).'<br>';
                  $totcofui+=$recordSet->fields[21];
                  if ($recordSet->fields[22]>0) $ccstr.=$lang['STR_CO_SUI_TAX'].CURRENCY_SYMBOL.num_format($recordSet->fields[22],2).'<br>';
                  $totcosui+=$recordSet->fields[22];
                  if ($recordSet->fields[23]>0) $ccstr.=$lang['STR_CO_FICA_TAX'].CURRENCY_SYMBOL.num_format($recordSet->fields[23],2).'<br>';
                  $cficatax+=$recordSet->fields[23];
                  if ($recordSet->fields[24]>0) $ccstr.=$lang['STR_CO_MEDICARE'].CURRENCY_SYMBOL.num_format($recordSet->fields[24],2).'<br>';
                  $cmedtax+=$recordSet->fields[24];
                  $recordSet2=&$conn->Execute('select premplweekdeddetail.amount,prempldeduction.description,prbended.name,prpension.name,prbended.bendedtype, premplweekdeddetail.prbendedid from premplweekdeddetail,premplweek left join prempldeduction on premplweekdeddetail.prempldeductionid=prempldeduction.id left join prbended on premplweekdeddetail.prbendedid=prbended.id left join prpension on premplweekdeddetail.prpensionid=prpension.id where premplweekdeddetail.premplweekid=premplweek.id and premplweek.prperiodid='.sqlprep($prperiodid).' and premplweek.periodbegindate='.sqlprep(${'periodbegindate'.$period}).' and premplweek.periodenddate='.sqlprep(${'periodenddate'.$period}).' and premplweek.employeeid='.$recordSet->fields[1].' and prbended.bendedtype=2 and premplweek.cancel=0');
                  while (!$recordSet2->EOF) {
                      $ccstr.=$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3].':&nbsp;'.CURRENCY_SYMBOL.$recordSet2->fields[0].'<br>';
                      ${"totcc".$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3]}+=$recordSet2->fields[0];
                      $totccname[]=$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3];
                      $recordSet2->MoveNext();
                  };

                  /*** check info***/
                  unset($cinfostr);
                  if ($recordSet->fields[25]>0) $cinfostr.='<a target="_new" href="prchecks.php?checknbr='.$recordSet->fields[30].'">'.$lang['STR_CHECK_NUMBER'].' '.$recordSet->fields[25].'</a><br>';
                  if ($recordSet->fields[26]>0) $cinfostr.=$lang['STR_DATE'].$recordSet->fields[26].'<br>';
                  if ($recordSet->fields[25]>0) $cinfostr.='<br><a href="prcheckvoid.php?checknbr='.$recordSet->fields[30].'&name='.$recordSet->fields[3].', '.$recordSet->fields[2].'&check='.$recordSet->fields[25].'&emplweekid='.$recordSet->fields[0].'" target="_new">'.$lang['STR_VOID_CHECK'].'</a><br>';
                  /*** write table***/
                  echo '<tr><td colspan="2" valign="center" nowrap>'.$recordSet->fields[3].', '.$recordSet->fields[2].'</td><td rowspan="2" valign="top" nowrap>'.$taxstr.$dedstr.'</td><td rowspan="2" valign="top" nowrap>'.$netstr.'</td><td rowspan="2" valign="top">'.$ccstr.'</td><td rowspan="2" valign="top" nowrap>'.$cinfostr.'</td></tr><tr><td valign="top" nowrap>'.$hrstr.'</td><td valign="top" nowrap>'.$paystr.'</td></tr>';
                  $recordSet->MoveNext();
              };
              //subtotal
              $totccname=array_unique($totccname);
              foreach($totccname as $data) if (${"totcc".$data}>0) $totccstr.=$data.": ".CURRENCY_SYMBOL.num_format(${"totcc".$data},2)."<br>";
              $totstname=array_unique($totstname);
              foreach($totstname as $data) if (${"totst".$data}>0) $totststr.=$data.". ".$lang['STR_STATE_TAX'].": ".CURRENCY_SYMBOL.num_format(${"totst".$data},2)."<br>";
              $totltname=array_unique($totltname);
              foreach($totltname as $data) if (${"totlt".$data}>0) $totltstr.=$data.". ".$lang['STR_LOCAL_TAX'].": ".CURRENCY_SYMBOL.num_format(${"totlt".$data},2)."<br>";
              $totctname=array_unique($totctname);
              foreach($totctname as $data) if (${"totct".$data}>0) $totctstr.=$data.". ".$lang['STR_CITY_TAX'].": ".CURRENCY_SYMBOL.num_format(${"totct".$data},2)."<br>";
              $totedname=array_unique($totedname);
              foreach($totedname as $data) if (${"toted".$data}>0) $totedstr.=$data.". ".$lang['STR_DEDUCTION'].": ".CURRENCY_SYMBOL.num_format(${"toted".$data},2)."<br>";
              echo '<tr><th colspan="2" valign="center">'.$lang['STR_SUBTOTAL'].':</th><th valign="top" align="left">'.$totststr.$totltstr.$totctstr.' '.$lang['STR_FEDERAL_TAX'].': '.CURRENCY_SYMBOL.num_format($fedtax,2).'<br>'.$lang['STR_FICA_TAX'].': '.CURRENCY_SYMBOL.num_format($eficatax,2).'<br>'.$lang['STR_MEDICARE'].': '.CURRENCY_SYMBOL.num_format($emedtax,2).'<br>'.$totedstr.' '.$lang['STR_TOTAL_MISC_DEDUCTIONS'].': '.CURRENCY_SYMBOL.num_format($totmiscded,2).'</th><th valign="top" align="left">'.$lang['STR_NET_PAY'].': '.CURRENCY_SYMBOL.num_format($totnetpay,2).'<br>'.$lang['STR_GROSS_PAY'].': '.CURRENCY_SYMBOL.num_format($totgrosspay,2).'<br>'.$lang['STR_GROSS_PAY_PER_YEAR'].': '.CURRENCY_SYMBOL.num_format($totgrosspayyear,2).'<br></th><th valign="top" align="left">'.$lang['STR_FUI'].': '.CURRENCY_SYMBOL.num_format($totcofui,2).'<br>'.$lang['STR_SUI'].': '.CURRENCY_SYMBOL.num_format($totcosui,2).'<br>'.$lang['STR_FICA_TAX'].': '.CURRENCY_SYMBOL.num_format($cficatax,2).'<br>'.$lang['STR_MEDICARE'].': '.CURRENCY_SYMBOL.num_format($cmedtax,2).'<br>'.$totccstr.'</th><th valign="top" align="left"></th></tr>';

              /*** deposit check info***/
              if ($dcamount>0) {
                  $dcinfostr.='Amount: '.CURRENCY_SYMBOL.num_format($dcamount,2).'<br>';
              } else {
                  $dcinfostr.='Amount: '.CURRENCY_SYMBOL.num_format(($fedtax+$eficatax+$emedtax+$cficatax+$cmedtax),2).'<br>';
              };
              $netstr='Federal Tax: '.CURRENCY_SYMBOL.num_format($fedtax,2).'<br>';
              $netstr.='FICA Tax: '.CURRENCY_SYMBOL.num_format($eficatax,2).'<br>';
              $netstr.='Medicare Deduction: '.CURRENCY_SYMBOL.num_format($emedtax,2).'<br>';
              $ccstr='Co. FICA Tax: '.CURRENCY_SYMBOL.num_format($cficatax,2).'<br>';
              $ccstr.='Co. Medicare: '.CURRENCY_SYMBOL.num_format($cmedtax,2).'<br>';
              echo '<tr><td colspan="2" rowspan="2" valign="center">'.$lang['STR_DEPOSIT_CHECK'].'</td><td rowspan="2" valign="top"></td><td rowspan="2" valign="top">'.$netstr.'</td><td rowspan="2" valign="top">'.$ccstr.'</td><td rowspan="2" valign="top">'.$dcinfostr.'</td></tr>';
              echo '</table>';
        } else {
          $recordSet=&$conn->CacheExecute(10,'select distinct prperiod.id,premplweek.periodbegindate,premplweek.periodenddate,prperiod.name,premplweek.calculatestatus from premplweek,prperiod,premployee where premplweek.employeeid=premployee.id and prperiod.id=premplweek.prperiodid and premplweek.cancel=0 and premployee.gencompanyid='.sqlprep($active_company).' order by premplweek.periodbegindate desc,prperiod.name');
          if ($recordSet->EOF) die(texterror($lang['STR_NO_UNPAID_HOURS_FOUND']));
          echo texttitle('<font size="-1">'.$lang['STR_SELECT_PAY_PERIOD'].'</font>');
          echo '<form method="post" name="mainform" action="prchecksum.php"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PERIOD'].'</td><td><select name="period"'.INC_TEXTBOX.'>';
          $i=1;
          while (!$recordSet->EOF) {
              echo '<option value="'.$i.'">'.$recordSet->fields[1].' - '.$recordSet->fields[2].' - '.$recordSet->fields[3].checkequal($recordSet->fields[4],1,' - Calculated')."\n";
              $recordSet->MoveNext();
              $i++;
          };
          echo '</select></td></tr></table><input type="submit" value="'.$lang['STR_CONTINUE'].'">';
          $recordSet=&$conn->CacheExecute(10,'select distinct prperiod.id,premplweek.periodbegindate,premplweek.periodenddate,prperiod.name,premplweek.calculatestatus from premplweek,prperiod,premployee where premplweek.employeeid=premployee.id and prperiod.id=premplweek.prperiodid and premplweek.cancel=0 and premployee.gencompanyid='.sqlprep($active_company).' order by premplweek.periodbegindate desc,prperiod.name');
          $i=1;
          while (!$recordSet->EOF) {
              echo '<input type="hidden" name="prperiodid'.$i.'" value="'.$recordSet->fields[0].'">'; //passes prperiodid, so we can uniquely identify period to pay
              echo '<input type="hidden" name="periodbegindate'.$i.'" value="'.$recordSet->fields[1].'">'; //passes periodbegindate, so we can uniquely identify period to pay
              echo '<input type="hidden" name="periodenddate'.$i.'" value="'.$recordSet->fields[2].'">'; //passes periodenddate, so we can uniquely identify period to pay
              $recordSet->MoveNext();
              $i++;
          };
          echo '</form>';
        };
         echo '</center>';
?>
<?php include('includes/footer.php'); ?>
