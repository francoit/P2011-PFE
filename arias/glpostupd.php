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
     echo texttitle($lang['STR_GL_REVERSE_POST']);
     echo '<center>';
     if ($unpost) {
          if ($begindate) {
               $query = "update gltransvoucher set status='0', postuserid=NULL, posteddate='0000-00-00 00:00:00', post2date='0000-00-00', lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid)." where gltransvoucher.status='1' and gltransvoucher.cancel='0' and gltransvoucher.standardset='0' and substring(gltransvoucher.posteddate,1,10) = ".sqlprep($begindate);
          } else {
               $query = "update gltransvoucher set status='0', postuserid=NULL, posteddate='0000-00-00 00:00:00', post2date='0000-00-00', lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid)." where gltransvoucher.status='1' and gltransvoucher.cancel='0' and gltransvoucher.standardset='0' and gltransvoucher.post2date=".sqlprep($postedyear."-".$postedmonth."-01");
          };
          if ($conn->Execute($query) === false) die(texterror($lang['STR_UNABLE_TO_REVERSE_VOUCHERS']));
          echo textsuccess($conn->Affected_Rows(). $lang['STR_VOUCHERS_REVERSED_OK']);
     } else {
          $postyear=createtime("Y");
          $postmonth=createtime("m");
          $bgdate=createtime("Y-m-d");
          if ($pdate) {
               echo texttitle($lang['STR_REVERSE_ALL_VOUCHERS_POSTED_ON_SPECIFIC_DATE']);
               echo '<form action="glpostupd.php" method="post" name="mainform"><input type="hidden" name="unpost" value="1"><table><tr><td>'.$lang['STR_DATE_TO_UNPOST'].':</td><td><input type="text" name="begindate" onchange="formatDate(this)" value="'.$bgdate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.begindate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="images/calendar.gif" border="0" alt="Display Calendar"></a></td></tr>';
               echo '</table><input type="submit" value="'.$lang['STR_REVERSE_POST'].'"></form>  <a href="glpostupd.php">'.$lang['STR_SWITCH_TO_REVERSE_ALL_ENTRIES_POSTED_ON_A_SPECIFIC_DATE'].'</a>';
          } else {
               echo texttitle($lang['STR_REVERSE_ALL_VOUCHERS_POSTED_TO_A_SPECIFIC_MONTH']);
               echo '<form action="glpostupd.php" method="post" name="mainform"><input type="hidden" name="unpost" value="1"><table><tr><td>'.$lang['STR_YEAR_MONTH_TO_UNPOST'].':</td><td><input type="text" name="postedyear" value="'.$postyear.'" size="14" maxlength="4"'.INC_TEXTBOX.'><input type="text" name="postedmonth" value="'.$postmonth.'" size="14" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
               echo '</table><br><input type="submit" value="'.$lang['STR_REVERSE_POST'].'"></form> <br><a href="glpostupd.php?pdate=1">'.$lang['STR_SWITCH_TO_REVERSE_ALL_ENTRIES_POSTED_ON_A_SPECIFIC_DATE'].'</a>';
               
               echo '</center>';
          };
     };
?>

<?php include('includes/footer.php'); ?>
