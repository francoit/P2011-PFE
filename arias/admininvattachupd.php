<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<script language="JavaScript">
      var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?php //admininvattachupd.php
     echo texttitle($lang['STR_INVENTORY_FILE_ATTACHMENT_MAINTAINENCE']);
     echo '<center>';
     if ($itemcode||$begindate||$enddate) {
	  $count=0;
	  $begindate=strtotime($begindate);
	  $enddate=strtotime($enddate);
          if ($itemcode) {
                 $recordSet = &$conn->Execute("select id from item where item.companyid=".$active_company." and item.itemcode=".sqlprep($itemcode));
                 if (!$recordSet->EOF) $itemid=$recordSet->fields[0];
          };
          checkpermissions('inv');
          if ($dir = @opendir(IMAGE_UPLOAD_DIR)) {
                 while($file = readdir($dir)) {
			if ($begindate||$enddate) {
				$ctime=filectime(IMAGE_UPLOAD_DIR.$file); //get file modified time
	            if (substr_count($file, "graphic")==1) {
					if ($ctime>$begindate&&$ctime<$enddate) {
						unlink(IMAGE_UPLOAD_DIR.$file);
						$count++;
					};
				};
	            if (substr_count($file, "catalog")==1) {
					if ($ctime>$begindate&&$ctime<$enddate) {
						unlink(IMAGE_UPLOAD_DIR.$file);
						$count++;
					};
				};
			};
			if ($itemcode) {
		            if (substr_count($file, "graphic".$itemid.".")==1) {
						unlink(IMAGE_UPLOAD_DIR.$file);
						$count++;
					};
		            if (substr_count($file, "catalog".$itemid.".")==1) {
						unlink(IMAGE_UPLOAD_DIR.$file);
						$count++;
					};
			};
		 };
         closedir($dir);
	  	 echo textsuccess($count.' '.$lang['STR_FILES_REMOVED']);
      };
     };
     $timestamp =  time();
     $date_time_array =  getdate($timestamp);
     $hours = $date_time_array["hours"];
     $minutes = $date_time_array["minutes"];
     $seconds = $date_time_array["seconds"];
     $month = $date_time_array["mon"];
     $day = $date_time_array["mday"];
     $year = $date_time_array["year"];
     $timestamp = mktime($hour, $minute, $second, $month-6, $day, $year);
     $sixmo=date("Y-m-d", $timestamp);
     echo '<form action="admininvattachupd.php" method="post" name="mainform"><table>';
     echo '<tr><th colspan="2" align="center"> '.$lang['STR_SELECT_ITEM_OR_DATE_RANGE_TO_PURGE'].':</td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ITEM_CODE'].':</td><td><input type="text" name="itemcode" size="30" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode\',\'cal\',\'dependent=yes,width=410,height=230,screenX=400,screenY=300,titlebar=yes\')"><img src="'.IMAGE_ITEM_LOOKUP.'" border="0" alt="Item Lookup"></a></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_DATE'].': </td><td><input type="text" name="begindate" size="10" value="0000-00-00"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.begindate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_DATE'].': </td><td><input type="text" name="enddate" size="10" value="'.$sixmo.'"'.INC_TEXTBOX.' checked><a href="javascript:show_calendar(\'mainform.enddate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
     echo '</table><br><input type="submit" value="'.$lang['STR_REMOVE_FILES'].'"></form>';
     
     echo '</center>';
?>

<?php include('includes/footer.php'); ?>
