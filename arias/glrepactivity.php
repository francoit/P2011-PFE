<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo texttitle($lang['STR_ACTIVITY_REPORT_FOR'].' '.$companyname);
     echo '<center>';
     if ($beginyear&&$beginmonth&&$endyear&&$endmonth) {
          $begindate=$beginyear.'-'.$beginmonth;
          $enddate=$endyear.'-'.$endmonth;
          if ($begindate!=$enddate) {
               echo texttitle("(".$lang['STR_PERIOD'].": ".$beginyear."/".$beginmonth." - ".$endyear."/".$endmonth.')');
          } else {
               echo texttitle("(".$lang['STR_PERIOD'].": ".$beginyear."/".$beginmonth.')');
          };
          $fiscalrecordSet = &$conn->Execute('select fiscalbeginmonth from glcompany where id='.sqlprep($active_company));
          if ($fiscalrecordSet&&!$fiscalrecordSet->EOF) $fiscalmonth=$fiscalrecordSet->fields[0];
          if (!$fiscalmonth) $fiscalmonth=1;
          $timestamp=mktime(0, 0, 0, $endmonth+1, 1, $endyear);
          $enddate=date("Y", $timestamp).'-'.date("m",$timestamp);
          unset($showstr);
          unset($showstr1);
          unset($showstr2);
          unset($showstr3);
          unset($showstr4);          
          if (!$show) {
          	$showstr=" and gltransvoucher.status='0'";
          	$showstr3=" and gltransvoucher.post2date < ".sqlprep($enddate."-01");
          } else {
          	$showstr1=" and gltransvoucher.post2date >= ".sqlprep($begindate."-01") ;
          	$showstr2=" and gltransvoucher.status='1' and gltransvoucher.post2date < ".sqlprep($begindate."-01");
          }
          if ($id>0) $showacct=' and gltransaction.glaccountid='.sqlprep($id);
          $recordSet = &$conn->Execute("select gltransaction.amount, gltransaction.glaccountid, glaccount.name, glaccount.description, accounttype.description, gltransaction.voucherid, gltransvoucher.wherefrom, gltransvoucher.entrydate, gltransvoucher.post2date, gltransvoucher.description from gltransaction, gltransvoucher, glaccount, accounttype where gltransvoucher.cancel='0' ".$showstr1." and gltransvoucher.post2date < ".sqlprep($enddate."-01")." and gltransaction.voucherid=gltransvoucher.id and glaccount.id=gltransaction.glaccountid and accounttype.id=glaccount.accounttypeid".$showstr.$showacct." and gltransvoucher.companyid=".$active_company." order by glaccount.name, gltransvoucher.post2date, gltransvoucher.voucher");
          if (!$recordSet->EOF) {
               $gltype = &$conn->Execute("select glaccount.accounttypeid from glaccount where glaccount.id=".$recordSet->fields[1]);
               $amount=0;
               if ($gltype->fields[0] >= 50) {
               	 $sessiongldate=$endyear.'-'.$fiscalmonth;
               	 $showstr4=" and gltransvoucher.post2date >= ".sqlprep($sessiongldate."-01") ;
               	 }
              $recordSet2 = &$conn->Execute("select sum(gltransaction.amount) from gltransaction, gltransvoucher where gltransaction.glaccountid='".$recordSet->fields[1]."' and gltransvoucher.cancel='0' and gltransvoucher.status='1' and gltransaction.voucherid=gltransvoucher.id ".$showstr. $showstr4.$showstr2.$showstr3);
               if (!$recordSet2->EOF) $amount=checkdec($recordSet2->fields[0],PREFERRED_DECIMAL_PLACES);
               echo '<table border="1" width="90%">';
               echo '<tr><th><b>'.$lang['STR_FROM'].'</b><th><b>'.$lang['STR_DATE'].'</b></th><th align="center"><b>'.$lang['STR_DESCRIPTION'].'</b></th><th><b>'.$lang['STR_POSTED_TO'].'</b></th><th><b>'.$lang['STR_DEBIT'].'</b></th><th><b>'.$lang['STR_CREDIT'].'</b></th></tr>';
               echo '<tr><th colspan="6">'.$recordSet->fields[2].' - '.$recordSet->fields[3].' - '.$recordSet->fields[4]."</th></tr>";
               $oldglaccountid=$recordSet->fields[1];
               $oldglaccountname=$recordSet->fields[2];
               $debit="";
               $credit="";
               if ($amount<0) {
                    $credit=checkdec((0-$amount),PREFERRED_DECIMAL_PLACES);
               } else {
                    $debit=checkdec($amount,PREFERRED_DECIMAL_PLACES);
               };
               echo '<tr><td colspan="4" width="80%">'.$lang['STR_BEGINNING_BALANCE'].':</td><td width="10%"><div align="right"><nobr>'.$debit.'</nobr></div></td><td width="10%"><div align="right">'.$credit.'</nobr></div></td></tr>';
          } else {
               die(texterror($lang['STR_NO_ENTIES_FOUND_MATCHING_CRITERIA']));
          };
          while (!$recordSet->EOF) {
               $gltype = &$conn->Execute("select glaccount.accounttypeid from glaccount where glaccount.id=".$recordSet->fields[1]);
               if ($gltype->fields[0] >= 50) {
              	 $sessiongldate=$endyear.'-'.$fiscalmonth;
             	 $showstr4=" and gltransvoucher.post2date >= ".sqlprep($sessiongldate."-01") ;
               	 }
               $debit="";
               $credit="";
               if ($recordSet->fields[1]!=$oldglaccountid) {
                    if ($amount<0) {
                         $credit=checkdec((0-$amount),PREFERRED_DECIMAL_PLACES);
                    } else {
                         $debit=checkdec($amount,PREFERRED_DECIMAL_PLACES);
                    };
                    echo '<tr><td colspan="4">'.$lang['STR_ENDING_BALANCE_ACCOUNT'].' '.$oldglaccountname.':</td><td><div align="right"><nobr>'.$debit.'</nobr></div></td><td><div align="right"><nobr>'.$credit.'</nobr></div></td></tr>';
                    $oldglaccountid=$recordSet->fields[1];
                    $oldglaccountname=$recordSet->fields[2];
                    $debit="";
                    $credit="";
                   $recordSet2 = &$conn->Execute("select sum(gltransaction.amount) from gltransaction, gltransvoucher where gltransaction.glaccountid='".$recordSet->fields[1]."' and gltransvoucher.cancel='0' and gltransvoucher.status='1' and gltransaction.voucherid=gltransvoucher.id ".$showstr.$showstr4.$showstr2.$showstr3);
                    if (!$recordSet2->EOF) $amount=checkdec($recordSet2->fields[0],PREFERRED_DECIMAL_PLACES);
                    echo '<tr><th><b>'.$lang['STR_FROM'].'</b><th><b>'.$lang['STR_DATE'].'</b></th><th align="center"><b>'.$lang['STR_DESCRIPTION'].'</b></th><th><b>'.$lang['STR_POSTED_TO'].'</b></th><th><b>'.$lang['STR_DEBIT'].'</b></th><th><b>'.$lang['STR_CREDIT'].'</b></th></tr>';
                    //echo '</table><br><table border="1" width="90%">';
                    echo '<tr><th colspan="6">'.$recordSet->fields[2].' - '.$recordSet->fields[3].' - '.$recordSet->fields[4]."</th></tr>";
                    $oldglaccountid=$recordSet->fields[1];
                    $oldglaccountname=$recordSet->fields[2];
                    if ($amount<0) {
                         $credit=checkdec((0-$amount),PREFERRED_DECIMAL_PLACES);
                    } else {
                         $debit=checkdec($amount,PREFERRED_DECIMAL_PLACES);
                    };
                    echo '<tr><td colspan="4" width="80%">'.$lang['STR_BEGINNING_BALANCE'].':</td><td width="10%"><div align="right"><nobr>'.$debit.'</nobr></div></td><td width="10%"><div align="right"><nobr>'.$credit.'</nobr></div></td></tr>';
               };
               $oldglaccountid=$recordSet->fields[1];
               $oldglaccountname=$recordSet->fields[2];
               $debit="";
               $credit="";
               if ($recordSet->fields[0]<0) {
                    $credit=checkdec((0-$recordSet->fields[0]),PREFERRED_DECIMAL_PLACES);
               } else {
                    $debit=checkdec($recordSet->fields[0],PREFERRED_DECIMAL_PLACES);
               };
               echo '<tr><td>'.strtoupper(modulenameshort($recordSet->fields[6])).'</td><td>'.substr($recordSet->fields[7],0,10).'</td><td>'.$recordSet->fields[9].'</td><td>'.substr($recordSet->fields[8],0,7).'</td><td><div align="right">'.$debit.'</div></td><td><div align="right">'.$credit.'</div></td></tr>';
               $amount=checkdec($amount+($recordSet->fields[0]),PREFERRED_DECIMAL_PLACES);
               $recordSet->MoveNext();
          };
          $debit="";
          $credit="";
          if ($amount<0) {
               $credit=checkdec((0-$amount),PREFERRED_DECIMAL_PLACES);
          } else {
               $debit=checkdec($amount,PREFERRED_DECIMAL_PLACES);
          };
          echo '<tr><td colspan="4">'.$lang['STR_ENDING_BALANCE_ACCOUNT'].''.$oldglaccountname.':</td><td><div align="right">'.$debit.'</div></td><td><div align="right">'.$credit.'</div></td></tr>';
          echo '</table>';
     } else {
          $timestamp =  time();
          $date_time_array =  getdate($timestamp);
          $hours =  $date_time_array["hours"];
          $minutes =  $date_time_array["minutes"];
          $seconds =  $date_time_array["seconds"];
          $month =  $date_time_array["mon"];
          $day =  $date_time_array["mday"];
          $year =  $date_time_array["year"];
          $timestamp =  mktime($hour, $minute, $second, $month-1, 1, $year);
          $bgdateyear=date("Y", $timestamp);
          $bgdatemonth=date("m", $timestamp);
          $eddateyear=date("Y", $timestamp);
          $eddatemonth=date("m", $timestamp);
          echo '<form action="glrepactivity.php" method="post"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_PERIOD'].':</td><td><input type="text" name="beginyear" onchange="validateint(this)" value="'.$bgdateyear.'" size="14" maxlength="4"'.INC_TEXTBOX.'><input type="text" name="beginmonth" onchange="validateint(this)" value="'.$bgdatemonth.'" size="14" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_PERIOD'].':</td><td><input type="text" name="endyear" onchange="validateint(this)" value="'.$eddateyear.'" size="14" maxlength="4"'.INC_TEXTBOX.'><input type="text" onchange="validateint(this)" name="endmonth" value="'.$eddatemonth.'" size="14" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHOW_UNPOSTED_ENTRIES'].':</td><td><input type="radio" name="show" value="0" checked'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHOW_ALL_ENTRIES'].':</td><td><input type="radio" name="show" value="1"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SPECIFIC_GENERAL_LEDGER_ACCOUNT'].':</td><td><select name="id"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id,name,description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by id');
          echo '<option value="0">0=All Accounts';
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]." - ".$recordSet->fields[2]."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr></table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
     };
          
          echo '</center>';
?>

<?php include('includes/footer.php'); ?>
