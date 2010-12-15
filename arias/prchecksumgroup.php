<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
        echo texttitle($companyname. $lang['STR_CHECK_SUMMARY']);
        echo '<center>';
        if ($period) {
              if (!is_array($period)) $period=array($period);
              foreach($period as $data) {
                   if ($i) $sqlstr.=' or ';
                   $prperiodid=${'prperiodid'.$data};
                   $sqlstr.= ' (premplweek.prperiodid='.sqlprep($prperiodid).' and premplweek.periodbegindate='.sqlprep(${'periodbegindate'.$data}).' and premplweek.periodenddate='.sqlprep(${'periodenddate'.$data}).')';
                   $i=1;
              };
              $sqlstr=' and ('.$sqlstr.') ';
              $recordSet=&$conn->Execute('select premplweek.id,premplweek.employeeid,premployee.firstname,premployee.lastname,prperiod.name,prperiod.numperyear,sum(premplweek.statetax),genstate.stateinit,sum(premplweek.localtax),prlocal.abrev,sum(premplweek.citytax),prcity.abrev,sum(premplweek.federaltax),sum(premplweek.ficatax),sum(premplweek.medicarededuction),sum(premplweek.misctaxablepay),sum(premplweek.miscnontaxablepay),sum(premplweek.tipspay),sum(premplweek.tipsaswages),sum(premplweek.netpay),sum(premplweek.miscdeduction), sum(premplweek.fuitax), sum(premplweek.suitax), sum(premplweek.cficatax), sum(premplweek.cmedicarededuction) from premplweek,premployee,prperiod left join prstate on premployee.prstateid=prstate.id left join genstate on prstate.genstateid=genstate.id left join prlocal on premployee.prlocalid=prlocal.id left join prcity on premployee.prcityid=prcity.id where premplweek.prperiodid=prperiod.id and premplweek.employeeid=premployee.id'.$sqlstr.' and premplweek.cancel=0 and premployee.gencompanyid='.sqlprep($active_company).' group by premployee.id order by premployee.lastname,premployee.firstname');
              $factor=$recordSet->fields[5]; //numperyear of prperiods
              if ($factor==0) die(texterror($lang['STR_NUMBER_OF_PERIODS_PER_YEAR_MUST_BE_GREATER_THAN_ZERO']));
              echo '<table border=0 cellpadding="3"><tr><th colspan="2">'.$lang['STR_EMPLOYEE'].'</th><th rowspan="2">'.$lang['STR_DEDUCTIONS'].'</th><th rowspan="2">'.$lang['STR_NET_PAY'].'</th><th rowspan="2">'.$lang['STR_COMPANY_CONTRIBUTIONS'].'</th></tr><tr><th>'.$lang['STR_HOURS'].'</th><th>'.$lang['STR_PAY'].'</th></tr>';
              $totccname=array(); //company contrib
              $totstname=array(); //state tax
              $totltname=array();//local tax
              $totctname=array(); //city tax
              $totedname=array(); //employee ded
              while (!$recordSet->EOF) {
                  $grosspay=0;
                  /*** hours ***/
                  $recordSet2=&$conn->Execute('select sum(premplweekpaydetail.qty),sum(premplweekpaydetail.rate),sum(premplweekpaydetail.amount),prpaytype.name,prbended.name from premplweekpaydetail,prpaytype,premplweek left join prbended on prbended.id=premplweekpaydetail.prbendedid where premplweekpaydetail.premplweekid=premplweek.id and premplweekpaydetail.prpaytypeid=prpaytype.id and premplweek.employeeid='.sqlprep($recordSet->fields[1]).$sqlstr.' and premplweek.cancel=0 group by prpaytypeid,prbendedid');
                  unset($hrstr);
                  unset($paystr);
                  while (!$recordSet2->EOF) {
                      if ($recordSet2->fields[4]) {
                        $hrstr.=substr($recordSet2->fields[4],0,8).'<br>';
                      } else {
                        $hrstr.=$recordSet2->fields[3].':'.$recordSet2->fields[0].'<br>';
                      };
                      $paystr.=CURRENCY_SYMBOL.num_format($recordSet2->fields[2],2).'<br>';
                      $grosspay+=$recordSet2->fields[2];
                      $recordSet2->MoveNext();
                  };
                  $grosspay+=$recordSet->fields[18]+$recordSet->fields[15]+$recordSet->fields[17]+$recordSet->fields[16]; //add stuff to gross and net
                  if ($recordSet->fields[15]>0) { $hrstr.='Misc. Taxable: <br>'; $paystr.=CURRENCY_SYMBOL.num_format($recordSet->fields[15],2).'<br>'; }; //misc tax pay
                  if ($recordSet->fields[16]>0) { $hrstr.='Misc. Non Taxable: <br>'; $paystr.=CURRENCY_SYMBOL.num_format($recordSet->fields[16],2).'<br>'; }; //misc nontax pay
                  if ($recordSet->fields[17]>0) { $hrstr.='Tips: <br>'; $paystr.=CURRENCY_SYMBOL.num_format($recordSet->fields[17],2).'<br>'; }; //tips
                  if ($recordSet->fields[18]>0) { $hrstr.='Tips as wages: <br>'; $paystr.=CURRENCY_SYMBOL.num_format($recordSet->fields[18],2).'<br>'; }; //tips as wages

                  /*** deductions ***/
                  unset($dedstr);
                  $recordSet2=&$conn->Execute('select sum(premplweekdeddetail.amount),prempldeduction.description,prbended.name,prpension.name,prbended.bendedtype, premplweekdeddetail.prbendedid from premplweekdeddetail,premplweek left join prempldeduction on premplweekdeddetail.prempldeductionid=prempldeduction.id left join prbended on premplweekdeddetail.prbendedid=prbended.id left join prpension on premplweekdeddetail.prpensionid=prpension.id where premplweekdeddetail.premplweekid=premplweek.id and premplweek.employeeid='.$recordSet->fields[1].$sqlstr.' and premplweek.cancel=0 group by prbended.name,prpension.name');
                  while (!$recordSet2->EOF) {
                      if ($recordSet2->fields[4]<>2&&$recordSet2->fields[0]<>0) {
                           $dedstr.=$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3].': '.CURRENCY_SYMBOL.num_format($recordSet2->fields[0],2).'<br>';
                           ${"toted".$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3]}+=$recordSet2->fields[0];
                           $totedname[]=$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3];
                      };
                      $recordSet2->MoveNext();
                  };
                  if ($recordSet->fields[20]>0) $dedstr.='Misc. Deductions: '.CURRENCY_SYMBOL.num_format($recordSet->fields[20],2).'<br>'; //misc deductions
                  $totmiscded+=$recordSet->fields[20];

                  /*** taxes ***/
                  unset($taxstr);
                  $recordSet2=&$conn->Execute('select sum(premplweek.statetax),genstate.stateinit from premplweek,prperiod left join prstate on premplweek.prstateid=prstate.id left join genstate on prstate.genstateid=genstate.id where premplweek.prperiodid=prperiod.id and premplweek.employeeid='.sqlprep($recordSet->fields[1]).$sqlstr.' and premplweek.cancel=0 group by premplweek.prstateid order by genstate.stateinit');
                  while (!$recordSet2->EOF) {
                      if ($recordSet2->fields[0]>0) $taxstr.=$recordSet2->fields[1].'.&nbsp;State&nbsp;Tax:&nbsp;'.CURRENCY_SYMBOL.num_format($recordSet2->fields[0],2).'<br>';
                      ${"totst".$recordSet2->fields[1]}+=$recordSet2->fields[0];
                      $totstname[]=$recordSet2->fields[1];
                      $recordSet2->MoveNext();
                  };
                  $recordSet2=&$conn->Execute('select sum(premplweek.localtax),prlocal.abrev from premplweek,prperiod left join prlocal on premplweek.prlocalid=prlocal.id where premplweek.prperiodid=prperiod.id and premplweek.employeeid='.$recordSet->fields[1].$sqlstr.' and premplweek.cancel=0 group by premplweek.prlocalid order by prlocal.abrev');
                  while (!$recordSet2->EOF) {
                      if ($recordSet2->fields[0]>0) $taxstr.=$recordSet2->fields[1].'.&nbsp;Local&nbsp;Tax:&nbsp;'.CURRENCY_SYMBOL.num_format($recordSet2->fields[0],2).'<br>';
                      ${"totlt".$recordSet2->fields[1]}+=$recordSet2->fields[0];
                      $totltname[]=$recordSet2->fields[1];
                      $recordSet2->MoveNext();
                  };
                  $recordSet2=&$conn->Execute('select sum(premplweek.citytax),prcity.abrev from premplweek,prperiod left join prcity on premplweek.prcityid=prcity.id where premplweek.prperiodid=prperiod.id and premplweek.employeeid='.$recordSet->fields[1].$sqlstr.' and premplweek.cancel=0 group by premplweek.prcityid order by prcity.abrev');
                  while (!$recordSet2->EOF) {
                      if ($recordSet2->fields[0]>0) $taxstr.=$recordSet2->fields[1].'.&nbsp;City&nbsp;Tax:&nbsp;'.CURRENCY_SYMBOL.num_format($recordSet2->fields[0],2).'<br>';
                      ${"totct".$recordSet2->fields[1]}+=$recordSet2->fields[0];
                      $totctname[]=$recordSet2->fields[1];
                      $recordSet2->MoveNext();
                  };
                  if ($recordSet->fields[12]>0) $taxstr.='Fed. Tax: '.CURRENCY_SYMBOL.num_format($recordSet->fields[12],2).'<br>';
                  $fedtax+=$recordSet->fields[12];
                  if ($recordSet->fields[13]>0) $taxstr.='FICA: '.CURRENCY_SYMBOL.num_format($recordSet->fields[13],2).'<br>';
                  $eficatax+=$recordSet->fields[13];
                  if ($recordSet->fields[14]>0) $taxstr.='Medicare: '.CURRENCY_SYMBOL.num_format($recordSet->fields[14],2).'<br>';
                  $emedtax+=$recordSet->fields[14];

                  /*** net pay***/
                  $netstr='Net Pay: '.CURRENCY_SYMBOL.num_format($recordSet->fields[19],2).'<br>';
                  $totnetpay+=$recordSet->fields[19];
                  $netstr.='Gross: '.CURRENCY_SYMBOL.num_format($grosspay,2).'<br>';
                  $totgrosspay+=$grosspay;
                  $recordSet2=&$conn->Execute('select sum(premplweekpaydetail.amount) from premplweekpaydetail,premplweek where premplweek.id=premplweekpaydetail.premplweekid and year(premplweek.periodenddate)=year(now()) and premplweek.employeeid='.sqlprep($recordSet->fields[1]));
                  $netstr.='Gross/Yr: '.CURRENCY_SYMBOL.num_format($recordSet2->fields[0],2).'<br>';
                  $totgrosspayyear+=$recordSet2->fields[0];

                  /*** company contributions***/
                  unset($ccstr);
                  if ($recordSet->fields[21]>0) $ccstr.='FUI Tax: '.CURRENCY_SYMBOL.num_format($recordSet->fields[21],2).'<br>';
                  $totcofui+=$recordSet->fields[21];
                  if ($recordSet->fields[22]>0) $ccstr.='SUI Tax: '.CURRENCY_SYMBOL.num_format($recordSet->fields[22],2).'<br>';
                  $totcosui+=$recordSet->fields[22];
                  if ($recordSet->fields[23]>0) $ccstr.='FICA Tax: '.CURRENCY_SYMBOL.num_format($recordSet->fields[23],2).'<br>';
                  $cficatax+=$recordSet->fields[23];
                  if ($recordSet->fields[24]>0) $ccstr.='Medicare Deduction: '.CURRENCY_SYMBOL.num_format($recordSet->fields[24],2).'<br>';
                  $cmedtax+=$recordSet->fields[24];
                  $recordSet2=&$conn->Execute('select sum(premplweekdeddetail.amount),prempldeduction.description,prbended.name,prpension.name,prbended.bendedtype, premplweekdeddetail.prbendedid from premplweekdeddetail,premplweek left join prempldeduction on premplweekdeddetail.prempldeductionid=prempldeduction.id left join prbended on premplweekdeddetail.prbendedid=prbended.id left join prpension on premplweekdeddetail.prpensionid=prpension.id where premplweekdeddetail.premplweekid=premplweek.id and premplweek.employeeid='.$recordSet->fields[1].$sqlstr.' and premplweek.cancel=0 and prbended.bendedtype=2 group by prbended.name,prpension.name');
                  while (!$recordSet2->EOF) {
                      $ccstr.=$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3].': '.CURRENCY_SYMBOL.$recordSet2->fields[0].'<br>';
                      ${"totcc".$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3]}+=$recordSet2->fields[0];
                      $totccname[]=$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3];
                      $recordSet2->MoveNext();
                  };

                  /*** write table***/
                  echo '<tr><td colspan="2" valign="center">'.$recordSet->fields[3].', '.$recordSet->fields[2].'</td><td rowspan="2" valign="top">'.$taxstr.$dedstr.'</td><td rowspan="2" valign="top">'.$netstr.'</td><td rowspan="2" valign="top">'.$ccstr.'</td></tr><tr><td valign="top">'.$hrstr.'</td><td valign="top">'.$paystr.'</td></tr>';
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
              echo '<tr><th colspan="2" valign="center">'.$lang['STR_SUBTOTAL'].':</th><th valign="top" align="left">'.$totststr.$totltstr.$totctstr.' '.$lang['STR_FEDERAL_TAX'].': '.CURRENCY_SYMBOL.num_format($fedtax,2).'<br> '.$lang['STR_FICA_TAX'].': '.CURRENCY_SYMBOL.num_format($eficatax,2).'<br>'.$lang['STR_MEDICARE'].': '.CURRENCY_SYMBOL.num_format($emedtax,2).'<br>'.$totedstr.' '.$lang['STR_TOTAL_MISC_DEDUCTIONS'].': '.CURRENCY_SYMBOL.num_format($totmiscded,2).'</th><th valign="top" align="left"> '.$lang['STR_NET_PAY'].': '.CURRENCY_SYMBOL.num_format($totnetpay,2).'<br>'.$lang['STR_GROSS_PAY'].': '.CURRENCY_SYMBOL.num_format($totgrosspay,2).'<br>'.$lang['STR_GROSS_PAY_PER_YEAR'].': '.CURRENCY_SYMBOL.num_format($totgrosspayyear,2).'<br></th><th valign="top" align="left">'.$lang['STR_FUI'].': '.CURRENCY_SYMBOL.num_format($totcofui,2).'<br>'.$lang['STR_SUI'].': '.CURRENCY_SYMBOL.num_format($totcosui,2).'<br> '.$lang['STR_FICA_TAX'].': '.CURRENCY_SYMBOL.num_format($cficatax,2).'<br>'.$lang['STR_MEDICARE'].': '.CURRENCY_SYMBOL.num_format($cmedtax,2).'<br>'.$totccstr.'</th></tr>';
              echo '</table>';
        } else {
          $recordSet=&$conn->CacheExecute(10,'select distinct prperiod.id,premplweek.periodbegindate,premplweek.periodenddate,prperiod.name,premplweek.calculatestatus,chk.checkdate from premplweek,prperiod,premployee,chk where checkid=chk.id and premplweek.employeeid=premployee.id and prperiod.id=premplweek.prperiodid and premplweek.cancel=0 and premployee.gencompanyid='.sqlprep($active_company).' order by premplweek.periodbegindate desc,prperiod.name');
          if ($recordSet->EOF) die(texterror($lang['STR_NO_UNPAID_HOURS_FOUND']));
          echo texttitle('<font size="-1"> '.$lang['STR_SELECT_PAY_PERIOD'].' </font>');
          echo '<form method="post" name="mainform" action="prchecksumgroup.php"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PERIOD'].':</td><td><input type="hidden" name="nonprintable" value="1"><select name="period[]" multiple size="10"'.INC_TEXTBOX.'>';
          $i=1;
          while (!$recordSet->EOF) {
              echo '<option value="'.$i.'">'.$recordSet->fields[1].' - '.$recordSet->fields[2].' - '.$lang['STR_PAID'].': '.$recordSet->fields[5].' - '.$recordSet->fields[3].checkequal($recordSet->fields[4],1,' - '.$lang['STR_CALCULATED'].' ')."\n";
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
          echo '<center>';
        };
?>
<?php include('includes/footer.php'); ?>
