<?php include("includes/main.php"); ?>
<?php include("includes/arfunctions.php"); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
     var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?php      echo '<center>';

        if ($custcompanyid) { //if external customer
           $customerid=$custcompanyid; //only allow them to edit their info
        };
        echo texttitle($lang['STR_PERFORMANCE_STATISTICS']);
        if ($bgdate&&$eddate) {
                echo texttitle($bgdate.' / '.$eddate);
                if ($customerid) {
                    $recordSet = &$conn->Execute('select company.id from company,customer where company.id=customer.companyid and customer.id='.sqlprep($customerid));
                    if (!$recordSet->EOF) $customerid=$recordSet->fields[0];
                } else {
                    $customerid=0;
                };
                if (orderstatontimegraph($bgdate,$eddate,$customerid)) orderstatdailygraph($bgdate,$eddate,$customerid);
        } else {
                $timestamp =  time();
                $date_time_array =  getdate($timestamp);
                $hours =  $date_time_array["hours"];
                $minutes =  $date_time_array["minutes"];
                $seconds =  $date_time_array["seconds"];
                $month =  $date_time_array["mon"];
                $day =  $date_time_array["mday"];
                $year =  $date_time_array["year"];
                $timestamp =  mktime($hour, $minute, $second, $month, $day, $year);
                $today=date("Y-m-d", $timestamp);
                $timestamp =  mktime($hour, $minute, $second, $month-1, $day, $year);
                $lastmonth=date("Y-m-d", $timestamp);
                echo '<form action="arorderstatlst.php" method="post" name="mainform"><table>';
                if (!$custcompanyid) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER'].': </td><td><input type="text" name="customerid" onchange="validateint(this)" size="10"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupcustomer.php?name=customerid\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Customer Lookup"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_DATE'].': </td><td><input type="text" name="bgdate" onchange="formatDate(this)" size="30" value="'.$lastmonth.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.bgdate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_DATE'].': </td><td><input type="text" name="eddate" onchange="formatDate(this)" size="30" value="'.$today.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.eddate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '</table><center><br><input type="submit" value="'.$lang['STR_SEARCH'].'"></center></form>';
        };
        
echo '</center>';
?>

<?php include_once("includes/footer.php"); ?>
