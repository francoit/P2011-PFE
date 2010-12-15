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
   echo '<center>';
   unset($companystr);
   if ($custcompanyid) { //if external customer
          $customerid=$custcompanyid; //only allow them to edit their info
          $recordSet = &$conn->Execute('select companyid from customer where id='.sqlprep($customerid));
          $companyid=$recordSet->fields[0];
          if ($recordSet->EOF) die(texterror($lang['STR_NO_OPEN_ORDERS_FOUND']));
          $companystr=' and arorder.orderbycompanyid='.sqlprep($companyid);
   };
   echo texttitle($lang['STR_SHIPPED_ITEM_LIST']);
   if ($begindate&&$enddate) {
     if (strlen($begindate)==7) $begindate=$begindate."-01";
     if (strlen($enddate)==7) $enddate=$enddate."-31";
     if (strlen($begindate)==4) $begindate=$begindate."-01-01";
     if (strlen($enddate)==4) $enddate=$enddate."-12-31";

     if ($sortorder==1) {
          $queryord="item.description, arordershipdetail.entrydate desc";
     } else {
          $queryord="item.itemcode,arordershipdetail.entrydate desc";
     };
     if ($itemcode) $itemstr=' and item.itemcode='.sqlprep($itemcode);
     if ($location) $locationstr=' and arorder.inventorylocationid='.sqlprep($location);
     $recordSet = &$conn->Execute('select item.itemcode, item.description, arorder.id, company.companyname, arorder.ordernumber, arordershipdetail.entrydate, arorder.entrydate, arorderdetail.qtyorder, arordershipdetail.shipqty, arorderdetail.priceach from arorder,item,arorderdetail,company,arordershipdetail where  arorder.id=arorderdetail.orderid '.$itemstr.' and item.companyid='.sqlprep($active_company).' and arorder.companyid='.sqlprep($active_company).' and arorder.cancel=0 and company.id=arorder.orderbycompanyid and item.id=arorderdetail.itemid and arordershipdetail.orderdetailid=arorderdetail.id and substring(arordershipdetail.entrydate,1,10)>='.sqlprep($begindate).' and substring(arordershipdetail.entrydate,1,10)<='.sqlprep($enddate).$locationstr.$companystr.' order by '.$queryord);
     if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_SHIPMENTS_FOUND']));
     echo '<table border="1"><tr><th rowspan="2">'.$lang['STR_ORDER_NUMBER'].'</th><th width="10%"><a href="arordershipitemlst.php?sortorder=0&&printable='.$printable.'&&begindate='.$begindate.'&&enddate='.$enddate.'&itemcode='.$itemcode.'" class="blacklink" >'.$lang['STR_ITEM_CODE'].'</a></th><th colspan="5"><a href="arordershipitemlst.php?sortorder=1&&printable='.$printable.'&&begindate='.$begindate.'&&enddate='.$enddate.'&itemcode='.$itemcode.'" class="blacklink" >'.$lang['STR_DESCRIPTION'].'</a></th></tr>';
     echo '<tr><th colspan="2">'.$lang['STR_ORDER_BY'].'</th><th>'.$lang['STR_SHIP_DATE'].'</th><th>'.$lang['STR_SHIP_QTY'].'</th><th>'.$lang['STR_PRICE_UNIT'].'</th><th>'.$lang['STR_EXTENDED_PRICE'].'</th></tr>';
     while (!$recordSet->EOF) {
           if ($lastitem<>$recordSet->fields[0]) { // total of previous item here
               if ($itotal>0) {
                  echo '<tr><td></td><td colspan="3">'.$lang['STR_ITEM'].'<b>'.$lastitem.'</b>'.$lang['STR_TOTALS'].'</td><td>'.checkdec($icount,0).'</td>';
                  echo '<td></td><td align="right">'.CURRENCY_SYMBOL.checkdec($itotal,PREFERRED_DECIMAL_PLACES).'</td><tr>';
               };
               unset($lastitem);
               $itotal=0;
               $icount=0;
           };
           if (!$recordSet->fields[8]==0) {
                if (!$lastitem) {
                   $lastitem=$recordSet->fields[0];
                   echo '<tr><td></td><th nowrap><b>'.$lastitem.'</b></th><th colspan="4"><b>'.$recordSet->fields[1].'</b></th><tr>';
                };
                echo '<tr><td>'.$recordSet->fields[4].'</td><td colspan="2">'.$recordSet->fields[3].'</td>';
                echo '<td>'.$recordSet->fields[5].'</td>';
                $ltotal=$recordSet->fields[9] * $recordSet->fields[8];
                $itotal+=$ltotal;
                $icount+=$recordSet->fields[8];
                echo '<td>'.$recordSet->fields[8].'</td>';
                echo '<td align="right">'.CURRENCY_SYMBOL.checkdec($recordSet->fields[9],PREFERRED_DECIMAL_PLACES).'</td>';
                echo '<td align="right">'.CURRENCY_SYMBOL.checkdec($ltotal,PREFERRED_DECIMAL_PLACES).'</td></tr>';
            };
            $recordSet->MoveNext();
        };
        if ($itotal>0) {
                  echo '<tr><td></td><td colspan="3">'.$lang['STR_ITEM'].'<b>'.$lastitem.'</b>'.$lang['STR_TOTALS'].'</td><td>'.checkdec($icount,0).'</td>';
                  echo '<td></td><td align="right">$'.checkdec($itotal,PREFERRED_DECIMAL_PLACES).'</td><tr>';
        };
    } else {
           $timestamp =  time();
           $enddate=date("Y-m-d",$timestamp);
           $date_time_array =  getdate($timestamp);
           $hours =  $date_time_array["hours"];
           $minutes =  $date_time_array["minutes"];
           $seconds =  $date_time_array["seconds"];
           $month =  $date_time_array["mon"];
           $day =  $date_time_array["mday"];
           $year =  $date_time_array["year"];
           $timestamp =  mktime($hour, $minute, $second, $month-1, $day, $year);
           $bgdate=date("Y-m-d", $timestamp);
           echo '<form action="arordershipitemlst.php" method="post" name="mainform"><table>';
           echo '<input type="hidden" name="printable" value="1">';
           echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_PERIOD'].':</td><td><input type="text" name="begindate" onchange="formatDate(this)" value="'.$bgdate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.begindate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
           echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_PERIOD'].':</td><td><input type="text" name="enddate" onchange="formatDate(this)" value="'.$enddate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.enddate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
           $recordSet = &$conn->Execute('select count(*) from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company));
           if (!$recordSet->EOF) if ($recordSet->fields[0]>1) {
                   echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LOCATION'].':</td><td><select name="location"'.INC_TEXTBOX.'><option value="0">All';
                   $recordSet = &$conn->Execute('select inventorylocation.id,company.companyname from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
                   while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                        $recordSet->MoveNext();
                   };
                   echo '</select></td></tr>';
           };
           echo '</table><center><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></center></form>';
           
    };
echo '</center>';
?>
<?php include('includes/footer.php'); ?>
