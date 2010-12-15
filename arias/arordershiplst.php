<?php include("includes/main.php"); ?>
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
   if ($begindate&&$enddate) {
     echo texttitle($lang['STR_SHIPPED_ORDER_LIST']);
     if ($sortorder==1) { //BY CUSTOMER NAME
          $queryord="company.companyname, arorder.ordernumber desc,arordershipdetail.entrydate desc";
     } elseif ($sortorder==2) { //BY PO NUMBER
          $queryord="arorder.ponumber desc, arorder.ordernumber desc, arordershipdetail.entrydate desc";
     } else { //BY ORDER NUMBER
          $queryord="arorder.ordernumber desc, arordershipdetail.entrydate desc, item.itemcode";
     };
         if ($location) $locationstr=' and arorder.inventorylocationid='.sqlprep($location);
     $recordSet = &$conn->Execute('select item.itemcode, item.description, arorder.id, company.companyname, arorder.ordernumber, arordershipdetail.entrydate, arorder.entrydate, arorderdetail.qtyorder, arordershipdetail.shipqty, arorderdetail.priceach, arorder.ponumber from arorder,item,arorderdetail,company,arordershipdetail where arorder.id=arorderdetail.orderid and arorder.companyid='.sqlprep($active_company).' and item.companyid='.sqlprep($active_company).' and arorder.cancel=0 and company.id=arorder.orderbycompanyid and item.id=arorderdetail.itemid and arordershipdetail.orderdetailid=arorderdetail.id and substring(arordershipdetail.entrydate,1,10)>='.sqlprep($begindate).' and substring(arordershipdetail.entrydate,1,10)<='.sqlprep($enddate).$locationstr.$companystr.' order by '.$queryord);
     if ($recordSet->EOF) die(texterror('No matching shipments found.'));
     echo '<table border="1"><tr><th rowspan="2">'.$lang['STR_ITEM_CODE'].'</th><th width="10%"><a href="arordershiplst.php?sortorder=0&&printable='.$printable.'&&begindate='.$begindate.'&&enddate='.$enddate.'" class="blacklink" >'.$lang['STR_ORDER_NUMBER'].'</a></th><th colspan="4"><a href="arordershiplst.php?sortorder=1&&printable='.$printable.'&&begindate='.$begindate.'&&enddate='.$enddate.'" class="blacklink" >'.$lang['STR_CUSTOMER'].'</a></th><th><a href="arordershiplst.php?sortorder=2&&printable='.$printable.'&&begindate='.$begindate.'&&enddate='.$enddate.'" class="blacklink" >'.$lang['STR_PO_NUMBER'].'</th></tr>';
     echo '<tr><th colspan="2">'.$lang['STR_DESCRIPTION'].'</th><th>'.$lang['STR_SHIP_DATE'].'</th><th>'.$lang['STR_SHIP_QTY'].'</th><th>'.$lang['STR_PRICE_UNIT'].'</th><th>'.$lang['STR_EXTENDED_PRICE'].'</th></tr>';
     while (!$recordSet->EOF) {
           if ($lastorder<>$recordSet->fields[4]) {
               // total of previous order here
               if ($itotal>0) {
                  echo '<tr><td></td><td colspan="3">'.$lang['STR_ORDERS'].'<b>'.$lastorder.'</b> '.$lang['STR_TOTALS'].'</td><td>'.checkdec($icount,0).'</td>';
                  echo '<td></td><td align="right">'.CURRENCY_SYMBOL.checkdec($itotal,PREFERRED_DECIMAL_PLACES).'</td><tr>';
               };
               $lastorder="";
               $itotal=0;
               $icount=0;
           };
           if (!$recordSet->fields[8]==0) {
                if (!$lastorder) {
                   $lastorder=$recordSet->fields[4];
                   //print header here
                   echo '<tr><td></td><th><a href="arordshipview.php?printable='.$printable.'&&ordernumber='.$lastorder.'"  ><b>'.$recordSet->fields[4].'</b></th><th colspan="4"><b>'.$recordSet->fields[3].'</b></th><td><b>'.$recordSet->fields[10].'</b></td></tr>';
                };
                echo '<tr><td nowrap>'.$recordSet->fields[0].'</td><td colspan="2">'.$recordSet->fields[1].'</td>';
                echo '<td>'.substr($recordSet->fields[5],0,10).'</td>';
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
                  echo '<tr><td></td><td colspan="3">'.$lang['STR_ORDERS'].'<b>'.$lastorder.'</b> '.$lang['STR_TOTALS'].'</td><td>'.checkdec($icount,0).'</td>';
                  echo '<td></td><td align="right">'.CURRENCY_SYMBOL.checkdec($itotal,PREFERRED_DECIMAL_PLACES).'</td><tr>';
      };
    } else {
           echo texttitle($lang['STR_SHIPPED_ORDER_LIST']);
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
           echo '<form action="arordershiplst.php" method="post" name="mainform"><table>';
           echo '<input type="hidden" name="printable" value="1">';
           echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_PERIOD'].':</td><td><input type="text" name="begindate" onchange="formatDate(this)" value="'.$bgdate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.begindate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
           echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_PERIOD'].':</td><td><input type="text" name="enddate" onchange="formatDate(this)" value="'.$enddate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.enddate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
           $recordSet = &$conn->Execute('select count(*) from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company));
           if (!$recordSet->EOF) if ($recordSet->fields[0]>1) {
                   echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Location:</td><td><select name="location"'.INC_TEXTBOX.'><option value="0">All';
                   $recordSet = &$conn->Execute('select inventorylocation.id,company.companyname from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
                   while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                        $recordSet->MoveNext();
                   };
                   echo '</select></td></tr>';
           };
           echo '</table>';
           echo '<center><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></center>';
           echo '</form>';
           
    };
echo '</center>';


?>

<?php include_once("includes/footer.php"); ?>
