<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
     var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?
     echo texttitle($lang['STR_JOURNAL_LISTING_FOR'] .$companyname);
     echo '<center>';
     if ($wherefrom) {
               if (!$sortord) $sortord='gltransvoucher.voucher';
               if (!$show) $showstr=' and gltransvoucher.status=0';
               $recordSet = &$conn->Execute("select gltransaction.amount, gltransaction.glaccountid, glaccount.name, glaccount.description, accounttype.description, gltransaction.voucherid, gltransvoucher.entrydate, gltransvoucher.description, gltransvoucher.voucher,gltransvoucher.status from gltransaction, gltransvoucher, glaccount, accounttype where gltransvoucher.cancel='0' and gltransvoucher.wherefrom=".sqlprep($wherefrom)."and substring(gltransvoucher.entrydate from 1 for 10) >= ".sqlprep($begindate)." and substring(gltransvoucher.entrydate from 1 for 10) <= ".sqlprep($enddate)." and gltransaction.voucherid=gltransvoucher.id and glaccount.id=gltransaction.glaccountid and accounttype.id=glaccount.accounttypeid".$showstr." and gltransvoucher.companyid=".sqlprep($active_company)." order by ".$sortord.", gltransvoucher.voucher, gltransvoucher.id, glaccount.name");
               if ($recordSet->EOF) die(texterror($lang['STR_NO_ENTIES_FOUND_MATCHING_CRITERIA']));
               $amount=0;
               $oldvoucherid=$recordSet->fields[5];
               $oldvouchername=$recordSet->fields[8];
               echo '<table border="1" width="90%"><tr><th width="10%"><a href="glrepjournal.php?begindate='.$begindate.'&&enddate='.$enddate.'&&show='.$show.'&&wherefrom='.$wherefrom.'&&sortord=gltransvoucher.id">'.$lang['STR_VOUCHER'].'</a> (*=posted)</th><th width="25%">Description</th><th colspan="2"  width="30%"><a href="glrepjournal.php?begindate='.$begindate.'&&enddate='.$enddate.'&&show='.$show.'&&wherefrom='.$wherefrom.'&&sortord=glaccount.name"><nobr>---------'.$lang['STR_ACCOUNT'].'---------</a></th><th width="15%"><a href="glrepjournal.php?begindate='.$begindate.'&&enddate='.$enddate.'&&show='.$show.'&&wherefrom='.$wherefrom.'&&sortord=gltransvoucher.entrydate">--'.$lang['STR_DATE'].'--</th><th width="10%">'.$lang['STR_DEBIT'].'</th><th width="10%">'.$lang['STR_CREDIT'].'</th></tr>';
               while (!$recordSet->EOF) {
                    $debit="";
                    $credit="";
                    if ($recordSet->fields[5]!=$oldvoucherid) {
                       $dispcredit="";
                       if ($sumcredit<>0) $dispcredit=checkdec(0-$sumcredit,2);
                       echo '<tr><td colspan="5" width="80%" align="right"><i>'.$lang['STR_TOTALS_FOR_VOUCHER'].' '.$oldvouchername.':</i></td><td width="10%"><nobr>'.checkdec($sumdebit,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%"><nobr>'.$dispcredit.'<nobr></td></tr>';
                       $gsumcredit+=$sumcredit;
                       $gsumdebit+=$sumdebit;
                       $sumcredit="";
                       $sumdebit="";
                       echo '<tr><td colspan="7">&nbsp;</td></tr>';
                    };
                    $oldvoucherid=$recordSet->fields[5];
                    $oldvouchername=$recordSet->fields[8];
                    if ($recordSet->fields[0]<0) {
                         $credit=checkdec($recordSet->fields[0],PREFERRED_DECIMAL_PLACES);
                         $sumcredit+=$credit;
                    } else {
                         $debit=checkdec($recordSet->fields[0],PREFERRED_DECIMAL_PLACES);
                         $sumdebit+=$debit;
                    };
                    $dispcredit="";
                    if ($credit<>0) $dispcredit=checkdec(0-$credit,PREFERRED_DECIMAL_PLACES);
                    $statusflag="";
                    if ($recordSet->fields[9]==1)    $statusflag="*" ;
                    echo '<tr><td>'.$recordSet->fields[8].$statusflag.'</td><td>'.$recordSet->fields[7].'</td><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.substr($recordSet->fields[6],0,10).'</td><td>'.$debit.'</td><td>'.$dispcredit.'</td></tr>';
                    $amount+=$recordSet->fields[0];
                    $recordSet->MoveNext();
               };
               $dispcredit="";
               if ($sumcredit<>0) $dispcredit=checkdec(0-$sumcredit,PREFERRED_DECIMAL_PLACES);

               echo '<tr><td colspan="5" width="80%" align="right"><i>'.$lang['STR_TOTALS_FOR_VOUCHER'].' '.$oldvouchername.':</i></td><td width="10%"><nobr>'.checkdec($sumdebit,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%"><nobr>'.$dispcredit.'</nobr></td></tr>';
               $gsumcredit+=$sumcredit;
               $gsumdebit+=$sumdebit;
               $sumcredit="";
               $sumdebit="";
               $dispcredit="";
               if ($gsumcredit<>0) $dispcredit=checkdec(0-$gsumcredit,PREFERRED_DECIMAL_PLACES);
               echo '<tr><td colspan="5" width="80%">'.$lang['STR_GRAND_TOTALS_FOR_ALL_LISTED'].':</td><td width="10%"><nobr>'.checkdec($gsumdebit,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%"><nobr>'.$dispcredit.'</nobr></td></tr></table>';
               $sumdedit="";
               $sumcredit="";
     } else {
          //do this query at the top, because it checks if there are any gl entries
          $recordSet = &$conn->Execute('select distinct wherefrom from gltransvoucher');
          if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_GL_JOURNAL_ENTRIES_FOUND']));
          $timestamp =  time();
          $date_time_array =  getdate($timestamp);
          $hours =  $date_time_array["hours"];
          $minutes =  $date_time_array["minutes"];
          $seconds =  $date_time_array["seconds"];
          $month =  $date_time_array["mon"];
          $day =  $date_time_array["mday"];
          $year =  $date_time_array["year"];
          $timestamp =  mktime($hour, $minute, $second, $month-1, 1, $year);
          $bgdate=date("Y-m-d", $timestamp);
          $timestamp =  mktime($hour, $minute, $second, $month, 0, $year);
          $eddate=date("Y-m-d", $timestamp);
          echo '<form action="glrepjournal.php" method="post" name="mainform"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_PERIOD'].':</td><td><input type="text" name="begindate" onchange="formatDate(this)" value="'.$bgdate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.begindate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_PERIOD'].':</td><td><input type="text" name="enddate" onchange="formatDate(this)" value="'.$eddate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.enddate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHOW_UNPOSTED_ENTRIES'].'</td><td><input type="radio" name="show" value="0" checked'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHOW_ALL_ENTRIES'].'</td><td><input type="radio" name="show" value="1"'.INC_TEXTBOX.'></td></tr>';
          //this is a continuation of the query at the top.. don't set recordset between here and there, or this will break :)
          if ($recordSet&&!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_REPORT_ON_MODULE'].':</td><td><select name="wherefrom"'.INC_TEXTBOX.'>';
               while ($recordSet&&!$recordSet->EOF) {
                    echo '<option value="'.$recordSet->fields[0].'">'.modulename(modulenameshort($recordSet->fields[0]));
                    $recordSet->MoveNext();
               };
               echo '</select></td></tr>';
          };
          echo '</table><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
     };
     	
        echo '</center>';
?>

<?php include('includes/footer.php'); ?>
