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
        echo texttitle($lang['STR_PAYROLL_CALCULATE_HOURS']);
        if ($period) { //start calculating
          checkpermissions('pay');
          $prperiodid=${'prperiodid'.$period};
          $recordSet=&$conn->Execute('select premplweek.id,premplweek.employeeid,premployee.firstname,premployee.lastname,prperiod.name,prperiod.numperyear,premployee.vacationhoursaccrued,premployee.sickleavehoursaccrued,premployee.hiredate,premployee.pensplanid1,premployee.pensplandedamount1,premployee.pensplanbase1,premployee.pensplanid2,premployee.pensplandedamount2,premployee.pensplanbase2,premployee.paytype,premployee.prdedgroupid,premployee.federalexemptions,premployee.extrafitperpayperiod,premployee.extrafitbasedon,premployee.maritalstatus,premplweek.prstateid,premplweek.prlocalid,premplweek.prcityid,premployee.stateexemptions,premployee.localexemptions,premployee.cityexemptions,premployee.extrasitperpayperiod,premployee.extrasitbasedon,premployee.extralitperpayperiod,premployee.extralitbasedon,premployee.extracitperpayperiod,premployee.extracitbasedon, premplweek.periodbegindate, premplweek.periodenddate, premplweek.tipspay,premplweek.tipsaswages,premplweek.misctaxablepay,premplweek.miscnontaxablepay,premplweek.miscdeduction from premplweek,premployee,prperiod where premplweek.prperiodid=prperiod.id and premplweek.employeeid=premployee.id and premplweek.prperiodid='.sqlprep($prperiodid).' and premplweek.periodbegindate='.sqlprep(${'periodbegindate'.$period}).' and premplweek.periodenddate='.sqlprep(${'periodenddate'.$period}).' and premplweek.cancel=0 and premplweek.calculatestatus=0 and premployee.gencompanyid='.sqlprep($active_company).' order by premployee.lastname,premployee.firstname');
          echo texttitle('<font size="-1">'.$recordSet->fields[4].': '.${'periodbegindate'.$period}.' - '.${'periodenddate'.$period}.'</font>');
          $factor=$recordSet->fields[5]; //numperyear of prperiods
          if ($factor==0) die(texterror($lang['STR_NUMBER_OF_PERIODS_PER_YEAR_MUST_BE_GREATER_THAN_ZERO']));
          while (!$recordSet->EOF) {
                  /*** employee info ***/
                  $week1=0;
                  $periodbegindate=substr($recordSet->fields[33],0,4).substr($recordSet->fields[33],5,2).substr($recordSet->fields[33],8,2); //format date as number
                  $periodenddate=substr($recordSet->fields[34],0,4).substr($recordSet->fields[34],5,2).substr($recordSet->fields[34],8,2);
                  for ($i=substr($periodbegindate,4,2); $i<=intval(substr($periodenddate,4,2)); $i++) { //test for week 1
                      $testdate=intval(substr($periodbegindate,0,4).substr($periodbegindate,4,2).str_pad($i,2,'0',$lang['STR_PAD_LEFT']).'01');
                      if (intval(substr($periodbegindate,0,4).substr($periodbegindate,4,2).substr($periodbegindate,6,2))<=$testdate&&intval(substr($periodenddate,0,4).substr($periodenddate,4,2).substr($periodenddate,6,2))>=$testdate) $week1=1;
                  };
                  $vachours=$recordSet->fields[6]; //vacation hours accrued
                  $sickhours=$recordSet->fields[7]; //sick leave hours accrued
                  $hiredate=$recordSet->fields[8];
                  $pensplanid1=$recordSet->fields[9];
                  $pensplandedamount1=$recordSet->fields[10];
                  $pensplanbase1=$recordSet->fields[11];
                  $pensplanid2=$recordSet->fields[12];
                  $pensplandedamount2=$recordSet->fields[13];
                  $pensplanbase2=$recordSet->fields[14];
                  $prstateid=$recordSet->fields[21];
                  $prlocalid=$recordSet->fields[22];
                  $prcityid=$recordSet->fields[23];
                  $recordSet2=&$conn->Execute('select max(vacdaysperyear), max(maxaccrue) from prvacation where yrsbeforeaccrue<=period_diff(date_format(NOW(),\'%Y%m\'),date_format('.sqlprep($hiredate).',\'%Y%m\'))/12 and gencompanyid='.sqlprep($active_company).' and cancel=0');
                  if (!$recordSet2->EOF) {
                       $vachoursperyear=8*$recordSet2->fields[0]; //vacation hours accrued per year
                       $maxvachours=$recordSet2->fields[1]; //maximum vacation hours accrued
                  };
                  $recordSet2=&$conn->Execute('select sum(premplweekpaydetail.qty), sum(premplweekpaydetail.qty*prpaytype.multiplier), sum(premplweekpaydetail.amount) from premplweekpaydetail,prpaytype where premplweekpaydetail.prpaytypeid=prpaytype.id and premplweekpaydetail.premplweekid='.sqlprep($recordSet->fields[0]).' group by premplweekpaydetail.premplweekid');
                  if (!$recordSet2->EOF) {
                       $hoursworked=$recordSet2->fields[0];
                       $hourspaid=$recordSet2->fields[1];
                       $pay=$recordSet2->fields[2];
                  };
                  $pay+=$recordSet->fields[36]+$recordSet->fields[37]; //add tips as wages and misc taxable pay
                  $recordSet2=&$conn->Execute('select sum(premplweekpaydetail.qty) from premplweekpaydetail,prpaytype where premplweekpaydetail.prpaytypeid=prpaytype.id and premplweekpaydetail.premplweekid='.sqlprep($recordSet->fields[0]).' and UCASE(TRIM(prpaytype.name)=\'ST\') group by premplweekpaydetail.premplweekid');
                  if (!$recordSet2->EOF) $sthours=$recordSet2->fields[0];
                  $recordSet2=&$conn->Execute('select sum(premplweekpaydetail.amount) from premplweekpaydetail,premplweek where premplweek.cancel=0 and premplweek.calculatestatus>0 and premplweekpaydetail.premplweekid=premplweek.id and premplweek.employeeid='.sqlprep($recordSet->fields[1]).' and year(premplweek.periodenddate)=year(NOW())');
                  $ytdpay=0;
                  if (!$recordSet2->EOF) $ytdpay=$recordSet2->fields[0];
                  unset($ytdtipspay);
                  unset($ytdsmisctaxablepay);
                  $recordSet2=&$conn->Execute('select sum(premplweek.tipswaswages), sum(premplweek.misctaxablepay) from premplweek where premplweek.id='.sqlprep($recordSet->fields[0]).' and year(premplweek.periodenddate)=year(NOW())');
                  if (!$recordSet2->EOF) {
                       $ytdtipspay=$recordSet2->fields[0];
                       $ytdmisctaxablepay=$recordSet2->fields[1];
                  };
                  $ytdpay+=$ytdtipspay+$ytdsmisctaxablepay;
                  $recordSet2=&$conn->Execute('select prcompanyperiod.maxpayhr,prcompanyperiod.maxgross, prcompany.maxsickleave,prcompany.sickleavehrsperyear from prcompany,prcompanyperiod where prcompany.id='.sqlprep($active_company).' and prcompanyperiod.prcompanyid=prcompany.id and prcompanyperiod.prperiodid='.sqlprep($prperiodid));
                  if (!$recordSet2->EOF) {
                       $maxpayhr=$recordSet2->fields[0];
                       $maxgross=$recordSet2->fields[1];
                       $maxsickhours=$recordSet2->fields[2];
                       $sickhoursperyear=$recordSet2->fields[3];
                  };
                  $newsickhours=($sickhoursperyear/$factor)+$sickhours;
                  if ($newsickhours>$maxsickhours) $newsickhours=$maxsickhours;
                  $newvachours=($vachoursperyear/$factor)+$vachours;
                  if ($newvachours>$maxvachours) $newvachours=$maxvachours;
                  if ($conn->Execute('update premployee set vacationhoursaccrued='.sqlprep(num_format($newvachours,2)).',sickleavehoursaccrued='.sqlprep(num_format($newsickhours,2)).' where id='.sqlprep($recordSet->fields[1])) === false) echo texterror('premployee update failed.');

                  /*** pension plans ***/
                  $fedded=0;
                  $ficaded=0;
                  $cficaded=0;
                  $fuided=0;
                  $suided=0;
                  $wcded=0;
                  $stateded=0;
                  $localded=0;
                  $cityded=0;
                  $totalbenamount=0;
                  $totalededamount=0;
                  $totaldedamount=0;
                  $recordSet2=&$conn->Execute('select id,name,employercontribhow,employercontribute,employermaxmatchpercent,mustbeinplan,calcbasis,prdedgroupid,paytype,payableglacctid,expenseglacctid,federalincometax,stateincometax,localincometax,cityincometax,employeefica,companyfica,fui,sui,workmanscomp,vendorid from prpension where gencompanyid='.sqlprep($active_company).' and cancel=0');
                  while (!$recordSet2->EOF) {
                          if ($recordSet->fields[16]==$recordSet2->fields[7]||$recordSet2->fields[7]==0) { //if prdedgroup matches
                              if ($recordSet->fields[9]==$recordSet2->fields[0]) { //employee's first plan
                                  if ($recordSet2->fields[8]==0||($recordSet2->fields[8]==$recordSet->fields[15]+1)) { //if pay type matches
                                      if (($recordSet2->fields[5]==0)||($recordSet->fields[10])) { //if employee must contribute, and they have a rate entered
                                           if ($recordSet->fields[11]) { //percentage deduction
                                              $ededamount=$pay*($recordSet->fields[10]/100);
                                           } else { //fixed rate deduction
                                              $ededamount=$recordSet->fields[10];
                                           };
                                           $ededamount=num_format($ededamount,2); //employee amount
                                           $totalededamount+=$ededamount;
                                           $conn->Execute('insert into premplweekdeddetail (premplweekid,prpensionid,amount,dedtype) values ('.sqlprep($recordSet->fields[0]).', '.sqlprep($recordSet2->fields[0]).', '.sqlprep($ededamount).',0)');
                                           if ($recordSet2->fields[11]) $fedded+=$ededamount;
                                           if ($recordSet2->fields[15]) $ficaded+=$ededamount;
                                           if ($recordSet2->fields[16]) $cficaded+=$ededamount;
                                           if ($recordSet2->fields[17]) $fuided+=$ededamount;
                                           if ($recordSet2->fields[18]) $suided+=$ededamount;
                                           if ($recordSet2->fields[19]) $wcded+=$ededamount;
                                           if ($recordSet2->fields[12]) $stateded+=$ededamount;
                                           if ($recordSet2->fields[13]) $localded+=$ededamount;
                                           if ($recordSet2->fields[14]) $cityded+=$ededamount;
                                           $cdedamount=0;
                                           if ($recordSet2->fields[2]) { //if company contributes flat rate
                                               $cdedamount=($recordSet2->fields[3]);
                                           } else { //if company is % ded match
                                               $cdedamount=($recordSet2->fields[3]*$ededamount);
                                           };
                                           if ($recordSet2->fields[4]) { //check max comp contrib
                                               if ($cdedamount>($recordSet2->fields[4]/100)*$pay) $cdedamount=($recordSet2->fields[4]/100)*$pay;
                                           };
                                           $cdedamount=num_format($cdedamount,2);
                                           $conn->Execute('insert into premplweekdeddetail (premplweekid,prpensionid,amount,dedtype) values ('.sqlprep($recordSet->fields[0]).', '.sqlprep($recordSet2->fields[0]).', '.sqlprep($cdedamount).',1)');
                                      };
                                  };
                              };
                              if ($recordSet->fields[12]==$recordSet2->fields[0]) { //employee's second plan
                                  if ($recordSet2->fields[8]==0||($recordSet2->fields[8]==$recordSet->fields[15]+1)) { //if pay type matches
                                      if (($recordSet2->fields[5]==0)||($recordSet->fields[13])) { //if employee must contribute, and they have a rate entered
                                           if ($recordSet->fields[14]) { //percentage deduction
                                              $ededamount=$pay*($recordSet->fields[13]/100);
                                           } else { //fixed rate deduction
                                              $ededamount=$recordSet->fields[13];
                                           };
                                           $ededamount=num_format($ededamount,2); //employee amount
                                           $totalededamount+=$ededamount;
                                           $conn->Execute('insert into premplweekdeddetail (premplweekid,prpensionid,amount,dedtype) values ('.sqlprep($recordSet->fields[0]).', '.sqlprep($recordSet2->fields[0]).', '.sqlprep($ededamount).',0)');
                                           if ($recordSet2->fields[11]) $fedded+=$ededamount;
                                           if ($recordSet2->fields[15]) $ficaded+=$ededamount;
                                           if ($recordSet2->fields[16]) $cficaded+=$ededamount;
                                           if ($recordSet2->fields[17]) $fuided+=$ededamount;
                                           if ($recordSet2->fields[18]) $suided+=$ededamount;
                                           if ($recordSet2->fields[19]) $wcded+=$ededamount;
                                           if ($recordSet2->fields[12]) $stateded+=$ededamount;
                                           if ($recordSet2->fields[13]) $localded+=$ededamount;
                                           if ($recordSet2->fields[14]) $cityded+=$ededamount;
                                           $cdedamount=0;
                                           if ($recordSet2->fields[2]) { //if company contributes flat rate
                                               $cdedamount=($recordSet2->fields[3]);
                                           } else { //if company is % ded match
                                               $cdedamount=($recordSet2->fields[3]*$ededamount);
                                           };
                                           if ($recordSet2->fields[4]) { //check max comp contrib
                                               if ($cdedamount>($recordSet2->fields[4]/100)*$pay) $cdedamount=($recordSet2->fields[4]/100)*$pay;
                                           };
                                           $cdedamount=num_format($cdedamount,2);
                                           $conn->Execute('insert into premplweekdeddetail (premplweekid,prpensionid,amount,dedtype) values ('.sqlprep($recordSet->fields[0]).', '.sqlprep($recordSet2->fields[0]).', '.sqlprep($cdedamount).',1)');
                                      };
                                  };
                              };
                          };
                          $recordSet2->MoveNext();
                  };
                  /*** benefits ***/
                  $recordSet2=&$conn->Execute('select id,paytype,bendedtype,name,howfig,prdedgroupid,rate,ceilingperyear,expenseglacctid from prbended where gencompanyid='.sqlprep($active_company).' and bendedtype=0 and cancel=0');
                  while (!$recordSet2->EOF) {

                          if ($recordSet->fields[16]==$recordSet2->fields[5]||$recordSet2->fields[5]==0) { //if prdedgroup matches
                                  if ($recordSet2->fields[1]==$recordSet->fields[15]) { //if pay type matches
                                         if (($ytdpay+$pay)>$recordSet2->fields[7]&&$recordSet2->fields[7]>0) { //check to not exceed benefit year ceiling
                                            $benpay=$recordSet2->fields[7]-$ytdpay;
                                         } else {
                                            $benpay=$pay;
                                         };
                                         if ($benpay<0) $benpay=0;
                                         switch ($recordSet2->fields[4]) { //calc benefit
                                            case (1): //%tax pay - all pay except tax exempt logged during hours
                                               $benamount=($benpay*($recordSet2->fields[6]/100));
                                               break;
                                            case (2): //%tax pay - tax - #1 minus FIT
                                               $benamount=(($benpay-$fedtax)*($recordSet2->fields[6]/100));
                                               break;
                                            case (3): //%st pay  - ST HOURS Pay
                                               $benamount=($benpay*($recordSet2->fields[6]/100));
                                               break;
                                            case (4): //hours worked - amount per hour for all hours
                                               $benamount=($hoursworked*$recordSet2->fields[6]);
                                               break;
                                            case (5): //hours paid - amount per hour paid
                                               $benamount=($hourspaid*$recordSet2->fields[6]);
                                               break;
                                            case (6): //st hours - amount per ST hours only
                                               $benamount=($sthours*$recordSet2->fields[6]);
                                               break;
                                            case (7): //weekly amount - $$ amount per week
                                               $benamount=($recordSet2->fields[6]*$factor)/52;
                                               break;
                                            case (8): //amt week 1 only  - amount in week that include 1st day of month in period
                                               if ($week1) $benamount=$recordSet2->fields[6];
                                               break;
                                         };
                                         $benamount=num_format($benamount,2);
                                         $totalbenamount+=$benamount;
                                         if ($benamount>0) {  // only save if non-zero calculation
                                            $conn->Execute('insert into premplweekpaydetail (premplweekid,prbendedid,amount,glaccountid) values ('.sqlprep($recordSet->fields[0]).', '.sqlprep($recordSet2->fields[0]).', '.sqlprep($benamount).', '.sqlprep($recordSet2->fields[8]).')');
                                         };
                                     };
                                  };
                          $recordSet2->MoveNext();
                  };
                  $pay+=$totalbenamount; //needs to be included in tax calculations

                  /*** federal tax ***/
                  $nofedtax=0;
                  $exemptions=$recordSet->fields[17];
                  switch (true) { //adj exemptions
                     case ($exemptions>95):
                        $nofedtax=1;
                        break;
                     case ($exemptions>69):
                        $exemptions-=70;
                        break;
                     case ($exemptions>49):
                        $exemptions-=50;
                        break;
                     case ($exemptions>29):
                        $exemptions-=30;
                        break;
                  };
                  if (!$nofedtax) { //if not federally exempt
                       $annpay=($pay-$fedded)*$factor; //subtract federally exemption pension plans, and calc annual pay
                       $recordSet2=&$conn->Execute('select prfederal.exemptionallow,prfederal.maxwagesfui,prfederal.companyfuipercent from prfederal where gencompanyid='.sqlprep($active_company));
                       $annpay-=($exemptions*$recordSet2->fields[0]); //subtract exemptions
                       $maxwagesfui=$recordSet2->fields[1]; //max fui
                       $companyfuipercent=$recordSet2->fields[2]/100; //comp percent
                       $recordSet2=&$conn->SelectLimit('select prfederaldetail.tax,prfederaldetail.percent,prfederaldetail.over from prfederaldetail,prfederal where prfederal.id=prfederaldetail.prfederalid and prfederaldetail.maritalstatus='.sqlprep($recordSet->fields[20]).' and prfederal.gencompanyid='.sqlprep($active_company).' and prfederaldetail.over<='.sqlprep($annpay).' order by over desc',1); //get fed tax from table
                       $fedtax=($recordSet2->fields[0]+(($recordSet2->fields[1]/100)*($annpay-$recordSet2->fields[2])))/$factor; //calc fed tax
                       $extrafit=0;
                       if ($recordSet->fields[19]==3) { //if extra fit based on $ amount
                           $extrafit=$recordSet->fields[18];
                       } elseif ($recordSet->fields[19]==2) { //based on federal tax
                           $extrafit=$recordSet->fields[18]*$fedtax;
                       } elseif ($recordSet->fields[19]==1) { //based on wages
                           $extrafit=$recordSet->fields[18]*($annpay/$factor);
                       };
                       $fedtax+=$extrafit;//add extra fit
                       $fedtax=num_format($fedtax,2);
                       if ($ytdpay+$pay>$maxwagesfui&&$maxwagesfui>0) {
                           $fuipay=$maxwagesfui-$ytdpay; //if we're going over ceiling, adj pay affected'
                       } else {
                           $fuipay=$pay;
                       };
                       if ($fuipay<0) $fuipay=0;
                       $fuitax=$fuipay*$companyfuipercent;
                       $fuitax=num_format($fuitax,2);
                  };


                  /*** fica & medicare tax ***/
                  $noficatax=0;
                  $nomedicaretax=0;
                  if ($exemptions>96||($exemptions>29&&$exemptions<70)) { //check if exempt
                      $noficatax=1;
                      $nomedicaretax=1;
                  };
                  $recordSet2=&$conn->Execute('select sum(premplweekdeddetail.amount) from premplweekdeddetail,premplweek,prpension where premplweek.id=premplweekdeddetail.premplweekid and year(premplweek.periodenddate)=year(NOW()) and premplweek.employeeid='.sqlprep($recordSet->fields[1]).' and premplweekdeddetail.prpensionid=prpension.id and premplweekdeddetail.dedtype=0 and prpension.employeefica=1');
                  $ytdamount=$recordSet2->fields[0]; //ytd amount of fica exempt pension
                  $recordSet2=&$conn->Execute('select prfederal.maxwagesfica, prfederal.employeeficapercent, prfederal.maxwagesmedicare, prfederal.employeemedicarepercent,prfederal.companyficapercent,prfederal.companymedicarepercent from prfederal where gencompanyid='.sqlprep($active_company));
                  $ficaceiling=$recordSet2->fields[0];
                  $ficapercent=$recordSet2->fields[1]/100;
                  $cficapercent=$recordSet2->fields[4]/100;
                  $medicareceiling=$recordSet2->fields[2];
                  $medicarepercent=$recordSet2->fields[3]/100;
                  $cmedicarepercent=$recordSet2->fields[5]/100;

                  if (!$noficatax) { //if not fica exempt
                       if ($ficaceiling>0&&($ytdpay+$pay)>$ficaceiling) { //check to not exceed fica year ceiling
                           $ficaamount=$ficaceiling-$ytdpay;
                       } else {
                           $ficaamount=$pay;
                       };
                       if ($ficaamount<0) $ficaamount=0;
                       $ficatax=($ficaamount-$ficaded)*$ficapercent; //calc fica tax
                       $cficatax=($ficaamount-$cficaded)*$cficapercent; //calc company fica tax
                  };
                  if (!$nomedicaretax) { //if not medicare exempt
                       if (($ytdpay+$pay)>$medicareceiling&&$medicareceiling>0) { //check to not exceed medicare year ceiling
                           $medicareamount=$medicareceiling-$ytdpay;
                       } else {
                           $medicareamount=$pay;
                       };
                       if ($medicareamount<0) $medicareamount=0;
                       $medicaretax=($medicareamount-$ficaded)*$medicarepercent; //calc medicare tax
                       $cmedicaretax=($medicareamount-$cficaded)*$cmedicarepercent; //calc company medicare tax
                  };
                  $ficatax=num_format($ficatax,2);
                  $cficatax=num_format($cficatax,2);
                  $medicaretax=num_format($medicaretax,2);
                  $cmedicaretax=num_format($cmedicaretax,2);

                  /*** state/local/city tax ***/
                  $taxes=array('state','local','city');
                  foreach ($taxes as $type) {
                     ${'no'.$type.'tax'}=0;
                     ${$type.'pay'}=0;
                     $annpay=$pay-${$type.'ded'}; //subtract state/local/city exempt pension plans
                     switch ($type) {
                               case ('state'):
                                   $localeid=$recordSet->fields[21];
                                   $exemptions=$recordSet->fields[24];
                                   $extra=$recordSet->fields[27];
                                   $basedon=$recordSet->fields[28];
                                   break;
                               case ('local'):
                                   $localeid=$recordSet->fields[22];
                                   $exemptions=$recordSet->fields[25];
                                   $extra=$recordSet->fields[29];
                                   $basedon=$recordSet->fields[30];
                                   break;
                               case ('city'):
                                   $localeid=$recordSet->fields[23];
                                   $exemptions=$recordSet->fields[26];
                                   $extra=$recordSet->fields[31];
                                   $basedon=$recordSet->fields[32];
                                   break;
                     };
                     switch (true) { //adj exemptions
                        case ($exemptions>97):
                           $nostatetax=1;
                           break;
                        case ($exemptions>69&&$exemptions<90):
                           $exemptions-=70;
                           break;
                     };
                     if (!${'no'.$type.'tax'}) { //if not state/local/city exempt
                       $ststr=''; //prevent outside setting of this variable
                       if ($type=='state') $ststr=',suipercent,suimax';
                       $recordSet2=&$conn->Execute('select deductfed,exemptyr1,exemptyr2,exemptyr3,exemptyr4,maxexemptpercent,maxexemptyear,taxcreditexempt1,taxcreditexempt2,taxcreditexempt3,taxcreditexempt4'.$ststr.' from pr'.$type.' where gencompanyid='.sqlprep($active_company).' and cancel=0 and id='.sqlprep($localeid));
                       if ($recordSet2->fields[0]) $annpay-=$fedtax; //subtract fed tax first, if we should
                       $annpay*=$factor; //annualize pay
                       switch (true) { //calc exemption pay reductions.  lack of break statements makes this cummulative
                                  case ($exemptions>3):
                                        ${$type.'pay'}+=($recordSet2->fields[4]*($exemptions-3));
                                  case ($exemptions>2):
                                        ${$type.'pay'}+=$recordSet2->fields[3];
                                  case ($exemptions>1):
                                        ${$type.'pay'}+=$recordSet2->fields[2];
                                  case ($exemptions>0):
                                        ${$type.'pay'}+=$recordSet2->fields[1];
                       };
                       if ($recordSet2->fields[5]>0&&${$type.'pay'}>($annpay*($recordSet2->fields[5])/100)) ${$type.'pay'}=($annpay*($recordSet2->fields[5]/100)); //check max percent
                       if ($recordSet2->fields[6]>0&&${$type.'pay'}>$recordSet2->fields[6]) ${$type.'pay'}=$recordSet2->fields[6]; //check max yearly exemption
                       ${'taxable'.$type.'pay'}=$annpay-${$type.'pay'};
                       $recordSet3=&$conn->SelectLimit('select pr'.$type.'detail.tax,pr'.$type.'detail.percent,pr'.$type.'detail.over from pr'.$type.'detail,pr'.$type.' where pr'.$type.'.id=pr'.$type.'detail.pr'.$type.'id and pr'.$type.'detail.maritalstatus='.sqlprep($recordSet->fields[20]).' and pr'.$type.'.gencompanyid='.sqlprep($active_company).' and pr'.$type.'detail.over<='.sqlprep($annpay).' order by over desc',1); //get state/local/city tax from table
                       ${$type.'tax'}=($recordSet3->fields[0]+($recordSet3->fields[1]/100)*(${'taxable'.$type.'pay'}-$recordSet3->fields[2])); //calc state/local/city tax
                       if ((${$type.'tax'})>0) { //check that deductions don't exceed tax
                              switch (true) { //calc tax credits.  lack of break statements makes this cummulative
                                  case ($exemptions>3):
                                       ${$type.'tax'}-=($recordSet2->fields[10]*($exemptions-3));
                                  case ($exemptions>2):
                                       ${$type.'tax'}-=$recordSet2->fields[9];
                                  case ($exemptions>1):
                                       ${$type.'tax'}-=$recordSet2->fields[8];
                                  case ($exemptions>0):
                                       ${$type.'tax'}-=$recordSet2->fields[7];
                              };
                              if ((${$type.'tax'})<=0) ${$type.'tax'}=0; //another 0 check
                              ${$type.'tax'}=${$type.'tax'}/$factor; //calc tax for period
                              $extrait=0;
                              if ($basedon==3) { //if extra it based on $ amount
                                  $extrait=$extra;
                               } elseif ($basedon==2) { //based on state/local/city tax
                                  $extrait=$extra*${$type.'tax'};
                               } elseif ($basedon==1) { //based on wages
                                  $extrait=$extra*($annpay/$factor);
                               };
                               ${$type.'tax'}+=$extrait;//add extra it
                       } else {
                              ${$type.'tax'}=0;
                       };
                       ${$type.'tax'}=num_format(${$type.'tax'},2);

                     };
                     if ($type='state') { //company sui tax
                       $companysuipercent=$recordSet2->fields[11]/100;
                       $maxwagessui=$recordSet2->fields[12];
                       if ($ytdpay+$pay>$maxwagessui&&$maxwagessui>0) {
                           $suipay=$maxwagessui-$ytdpay; //if we're going over ceiling, adj pay affected'
                       } else {
                           $suipay=$pay;
                       };
                       if ($suipay<0) $suipay=0;
                       $suitax=$suipay*$companysuipercent;
                       $suitax=num_format($suitax,2);
                     };
                  };

                  /*** deductions ***/
                  $recordSet2=&$conn->Execute('select id,paytype,bendedtype,name,howfig,prdedgroupid,rate,ceilingperyear from prbended where gencompanyid='.sqlprep($active_company).' and bendedtype=1 and cancel=0');
                  while (!$recordSet2->EOF) {
                          if ($recordSet->fields[16]==$recordSet2->fields[5]||$recordSet2->fields[5]==0) { //if prdedgroup matches
                                  if ($recordSet2->fields[1]==$recordSet->fields[15]) { //if pay type matches
                                         if ($ytdpay+$pay>$recordSet2->fields[7]&&$recordSet2->fields[7]>0) { //check to not exceed deduction year ceiling
                                            $dedpay=($recordSet2->fields[7]-$ytdpay);
                                         } else {
                                            $dedpay=$pay;
                                         };
                                         if ($dedpay<0) $dedpay=0;
                                         switch ($recordSet2->fields[4]) { //calc deduction
                                            case (1): //%tax pay - all pay except tax exempt logged during hours
                                               $dedamount=($dedpay*($recordSet2->fields[6]/100));
                                               break;
                                            case (2): //%tax pay - tax - #1 minus FIT
                                               $dedamount=(($dedpay-$fedtax)*($recordSet2->fields[6])/100);
                                               break;
                                            case (3): //%st pay  - ST HOURS Pay
                                               $dedamount=($dedpay*($recordSet2->fields[6])/100);
                                               break;
                                            case (4): //hours worked - amount per hour for all hours
                                               $dedamount=($hoursworked*$recordSet2->fields[6]);
                                               break;
                                            case (5): //hours paid - amount per hour paid
                                               $dedamount=($hourspaid*$recordSet2->fields[6]);
                                               break;
                                            case (6): //st hours - amount per ST hours only
                                               $dedamount=($sthours*$recordSet2->fields[6]);
                                               break;
                                            case (7): //weekly amount - $$ amount per week
                                               $dedamount=($recordSet2->fields[6]*$factor)/52;
                                               break;
                                            case (8): //amt week 1 only  - amount in week that include 1st day of month in period
                                               if ($week1) $dedamount=$recordSet2->fields[6];
                                               break;
                                         };
                                         $dedamount=num_format($dedamount,2);
                                         $totaldedamount+=$dedamount;
                                         if ($conn->Execute('insert into premplweekdeddetail (premplweekid,prbendedid,amount,dedtype) values ('.sqlprep($recordSet->fields[0]).', '.sqlprep($recordSet2->fields[0]).', '.sqlprep($dedamount).',0)') === false) echo texterror('premplweekdeddetail update failed.');
                                  };
                          };
                          $recordSet2->MoveNext();
                  };

                  /*** employee specific deductions ***/
                  $esdedamount=0;
                  $recordSet2=&$conn->Execute('select id,amountperperiod,periodsremain from prempldeduction where cancel=0 and periodsremain<>0 and employeeid='.sqlprep($recordSet->fields[1]));
                  while (!$recordSet2->EOF) {
                      $dedamount=$recordSet2->fields[1];
                      $dedamount=num_format($dedamount,2);
                      if ($recordSet2->fields[2]>0){  // only reduce if NOT ongoing deduction (periods=-1)
                           if ($conn->Execute('update prempldeduction set periodsremain=periodsremain-1 where id='.sqlprep($recordSet2->fields[0])) === false) echo texterror('prempldeduction update failed.');
                      };
                      if ($conn->Execute('insert into premplweekdeddetail (premplweekid,prempldeductionid,amount,dedtype) values ('.sqlprep($recordSet->fields[0]).', '.sqlprep($recordSet2->fields[0]).', '.sqlprep($dedamount).',0)') === false) echo texterror('premplweekdeddetail update failed.');
                      $esdedamount+=$dedamount;
                      $recordSet2->MoveNext();
                  };

                  /*** company contributions ***/
                  $recordSet2=&$conn->Execute('select id,paytype,bendedtype,name,howfig,prdedgroupid,rate,ceilingperyear from prbended where gencompanyid='.sqlprep($active_company).' and bendedtype=2 and cancel=0');
                  while (!$recordSet2->EOF) {
                          if ($recordSet->fields[16]==$recordSet2->fields[5]||$recordSet2->fields[5]==0) { //if prdedgroup matches
                                  if ($recordSet2->fields[1]==$recordSet->fields[15]) { //if pay type matches
                                         if ($ytdpay+$pay>$recordSet2->fields[7]&&$recordSet2->fields[7]>0) { //check to not exceed deduction year ceiling
                                            $dedpay=$recordSet2->fields[7]-$ytdpay;
                                         } else {
                                            $dedpay=$pay;
                                         };
                                         if ($dedpay<0) $dedpay=0;
                                         switch ($recordSet2->fields[4]) { //calc deduction
                                            case (1): //%tax pay - all pay except tax exempt logged during hours
                                               $dedamount=($dedpay*($recordSet2->fields[6])/100);
                                               break;
                                            case (2): //%tax pay - tax - #1 minus FIT
                                               $dedamount=(($dedpay-$fedtax)*($recordSet2->fields[6])/100);
                                               break;
                                            case (3): //%st pay  - ST HOURS Pay
                                               $dedamount=($dedpay*($recordSet2->fields[6])/100);
                                               break;
                                            case (4): //hours worked - amount per hour for all hours
                                               $dedamount=($hoursworked*($recordSet2->fields[6])/100);
                                               break;
                                            case (5): //hours paid - amount per hour paid
                                               $dedamount=($hourspaid*$recordSet2->fields[6]);
                                               break;
                                            case (6): //st hours - amount per ST hours only
                                               $dedamount=($sthours*$recordSet2->fields[6]);
                                               break;
                                            case (7): //weekly amount - $$ amount per week
                                               $dedamount=($recordSet2->fields[6]*$factor)/52;
                                               break;
                                            case (8): //amt week 1 only  - amount in week that include 1st day of month in period
                                               if ($week1) $dedamount=$recordSet2->fields[6];
                                               break;
                                         };
                                         $dedamount=num_format($dedamount,2);
                                         if ($conn->Execute('insert into premplweekdeddetail (premplweekid,prbendedid,amount,dedtype) values ('.sqlprep($recordSet->fields[0]).', '.sqlprep($recordSet2->fields[0]).', '.sqlprep($dedamount).',1)') === false) echo texterror('premplweekdeddetail update failed.');
                                     };
                              };
                          $recordSet2->MoveNext();
                  };

                  //net pay
                  $netpay=$pay+$recordSet->fields[38]-$recordSet->fields[39]-$fedtax-$ficatax-$statetax-$localtax-$citytax-$medicaretax-$totaldedamount-$totalededamount-$esdedamount;

                  //write calcs to table
                  if ($conn->Execute('update premplweek set calculatestatus=1,netpay='.sqlprep($netpay).',vacaccrue='.sqlprep(num_format($newvachours-$vachours,2)).',sickaccrue='.sqlprep(num_format($newsickhours-$sickhours,2)).',federaltax='.sqlprep($fedtax).',ficatax='.sqlprep($ficatax).',statetax='.sqlprep($statetax).',localtax='.sqlprep($localtax).',citytax='.sqlprep($citytax).',prstateid='.sqlprep($prstateid).', prlocalid='.sqlprep($prlocalid).', prcityid='.sqlprep($prcityid).', medicarededuction='.sqlprep($medicaretax).',fuitax='.sqlprep($fuitax).', cficatax='.sqlprep($cficatax).', cmedicarededuction='.sqlprep($cmedicaretax).', suitax='.sqlprep($suitax).' where id='.sqlprep($recordSet->fields[0])) === false) echo texterror('premplweek insert failed.');
                  $recordSet->MoveNext();
           };
           echo textsuccess($lang['STR_HOURS_CALCULATED_SUCCESSFULLY']);
        } else {
          $recordSet=&$conn->CacheExecute(10,'select distinct prperiod.id,premplweek.periodbegindate,premplweek.periodenddate,prperiod.name from premplweek,prperiod,premployee where premplweek.employeeid=premployee.id and prperiod.id=premplweek.prperiodid and premplweek.cancel=0 and premplweek.calculatestatus=0 and premployee.gencompanyid='.sqlprep($active_company).' order by premplweek.periodbegindate,prperiod.name');
          if ($recordSet->EOF) die(texterror($lang['STR_NO_UNPAID_HOURS_FOUND']));
          echo texttitle('<font size="-1">'.$lang['STR_SELECT_PAY_PERIOD'].'</font>');
          echo '<form method="post" name="mainform" action="prcalchours.php"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Period:</td><td><select name="period"'.INC_TEXTBOX.'>';
          $i=1;
          while (!$recordSet->EOF) {
              echo '<option value="'.$i.'">'.$recordSet->fields[1].' - '.$recordSet->fields[2].' - '.$recordSet->fields[3]."\n";
              $recordSet->MoveNext();
              $i++;
          };
          echo '</select></td></tr></table><input type="submit" value="'.$lang['STR_CALCULATE'].'">';
          $recordSet=&$conn->CacheExecute(10,'select distinct prperiod.id,premplweek.periodbegindate,premplweek.periodenddate,prperiod.name from premplweek,prperiod,premployee where premplweek.employeeid=premployee.id and prperiod.id=premplweek.prperiodid and premplweek.cancel=0 and premplweek.calculatestatus=0 and premployee.gencompanyid='.sqlprep($active_company).' order by premplweek.periodbegindate,prperiod.name');
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
