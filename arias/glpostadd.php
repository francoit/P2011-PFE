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
     echo texttitle($lang['STR_GL_POST']);
     echo '<center>';
     if ($postedyear) {
          if ($postap) $where.="gltransvoucher.wherefrom=1 or ";
          if ($postar) $where.="gltransvoucher.wherefrom=2 or ";
          if ($postgl) $where.="gltransvoucher.wherefrom=3 or ";
          if ($postin) $where.="gltransvoucher.wherefrom=4 or ";
          if ($postfa) $where.="gltransvoucher.wherefrom=5 or ";
          if ($postpr) $where.="gltransvoucher.wherefrom=6 or ";
          $where=substr($where,0,strlen($where)-4);
          $recordSet = &$conn->Execute("select sum(gltransaction.amount), gltransaction.voucherid, gltransvoucher.voucher from gltransaction inner join gltransvoucher on gltransaction.voucherid=gltransvoucher.id where (".$where.") and gltransvoucher.status='0' and gltransvoucher.cancel='0' and gltransvoucher.standardset='0' and substring(gltransvoucher.entrydate,1,10) >= ".sqlprep($begindate)." and substring(gltransvoucher.entrydate,1,10) <= ".sqlprep($enddate)." group by gltransaction.voucherid,gltransvoucher.voucher order by gltransaction.voucherid");
          if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_VOUCHERS_MATCH_THE_SPECIFIED_CRITERIA_TO_POST']));
          while (!$recordSet->EOF) {
               if (checkzero($recordSet->fields[0])) { //transactions balanced to 0
                    if ($conn->Execute("update gltransvoucher set status='1', postuserid=".sqlprep($userid).", posteddate = NOW(), post2date='".$postedyear.'-'.$postedmonth."-01' where id=".sqlprep($recordSet->fields[1])) === false) {
                        echo texterror("Error posting voucher ".$recordSet->fields[2].".");
                    } else {
                        $vouchcount++;
                    };
               } else {
                    echo texterror($lang['STR_VOUCHER'] .$recordSet->fields[2].$lang['STR_FAILED_TO_BALANCE'].'('.CURRENCY_SYMBOL.checkdec($amount,PREFERRED_DECIMAL_PLACES).')');
               };
               $recordSet->MoveNext();
          };
          echo textsuccess($vouchcount. $lang['STR_VOUCHERS_POSTED_OK']);
     } else {
          $timestamp =  time();
          $date_time_array =  getdate($timestamp);
          $hours =  $date_time_array["hours"];
          $minutes =  $date_time_array["minutes"];
          $seconds =  $date_time_array["seconds"];
          $month =  $date_time_array["mon"];
          $day =  $date_time_array["mday"];
          $year =  $date_time_array["year"];
          if ($day<=5) { //set default dates to previous month
              $timestamp =  mktime($hour, $minute, $second, $month, 0, $year);
              $postyear=date("Y", $timestamp);
              $postmonth=date("m", $timestamp);
              $timestamp =  mktime($hour, $minute, $second, $month-1, 1, $year);
              $bgdate=date("Y-m-d", $timestamp);
              $timestamp =  mktime($hour, $minute, $second, $month, 0, $year);
              $eddate=date("Y-m-d", $timestamp);
          } else { //set dates to current month
              $timestamp =  mktime($hour, $minute, $second, $month, $day, $year);
              $postyear=date("Y", $timestamp);
              $postmonth=date("m", $timestamp);
              $timestamp =  mktime($hour, $minute, $second, $month, 1, $year);
              $bgdate=date("Y-m-d", $timestamp);
              $timestamp =  mktime($hour, $minute, $second, $month, $day, $year);
              $eddate=date("Y-m-d", $timestamp);
          };
          echo '<form action="glpostadd.php" method="post" name="mainform"><table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Year/Month Post Into:</td><td><input type="text" name="postedyear" value="'.$postyear.'" size="14" maxlength="4"'.INC_TEXTBOX.'><input type="text" name="postedmonth" value="'.$postmonth.'" size="14" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_PERIOD'].':</td><td><input type="text" name="begindate" onchange="formatDate(this)" value="'.$bgdate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.begindate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_PERIOD'].':</td><td><input type="text" name="enddate" onchange="formatDate(this)" value="'.$eddate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.enddate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
          echo '</td></tr></table><br><table border="1"><tr><th colspan="2">'.$lang['STR_POST_FROM'].':</th></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ACCOUNTS_PAYABLE'].':</td><td><input type="checkbox" name="postap" value="1" checked'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ACCOUNTS_RECEIVABLE'].':</td><td><input type="checkbox" name="postar" value="1" checked'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GENERAL_LEDGER'].':</td><td><input type="checkbox" name="postgl" value="1" checked'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVENTORY'].':</td><td><input type="checkbox" name="postin" value="1" checked'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FIXED_ASSETS'].':</td><td><input type="checkbox" name="postfa" value="1" checked'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAYROLL'].':</td><td><input type="checkbox" name="postpr" value="1" checked'.INC_TEXTBOX.'></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_POST_NOW'].'"></form>';
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
