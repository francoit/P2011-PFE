<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php if ($printable) $printablestr='printable='.$printable.'&'; ?>
<script language="JavaScript">
function loca()
{
        location.href = 'arorderlist.php?<?=$printablestr;?>location='+document.mainform.location.value;
}
</script>
<?php //arorderlist.php - April 2001 copyright Noguska - Fostoria, OH  44830
   //prints lists of open orders sorted by order number, order by, due date, and PO Number
     unset($companystr);
     if ($custcompanyid) { //if external customer
          $customerid=$custcompanyid; //only allow them to edit their info
          $recordSet = &$conn->Execute('select companyid from customer where id='.sqlprep($customerid));
          $companyid=$recordSet->fields[0];
          if ($recordSet->EOF) die(texterror($lang['STR_NO_OPEN_ORDERS_FOUND']));
          $companystr=' and arorder.orderbycompanyid='.sqlprep($companyid);
     };
     echo texttitle($lang['STR_ORDER_LIST_FOR'] .$companyname);
     echo texttitle($lang['STR_OPEN_ORDERS']);
     if ($sortorder==1) { //define sort based on which column clicked last
          $queryord="arorder.ordernumber desc";
     } elseif ($sortorder==2) {
          $queryord="company.companyname, arorder.duedate desc";
     } elseif ($sortorder==3) {
          $queryord="arorder.ponumber desc, arorder.ordernumber desc";
     } else {
          $queryord="arorder.duedate desc, arorder.ordernumber desc";
     };
     if ($location) $locationstr=' and arorder.inventorylocationid='.sqlprep($location);
     $recordSet = &$conn->Execute('select arorder.ordernumber, company.companyname, arorder.duedate, arorder.entrydate,arorder.id,arorder.ponumber from arorder,company where arorder.companyid='.sqlprep($active_company).$locationstr.$companystr.' and arorder.orderbycompanyid=company.id and arorder.status=0 and arorder.cancel=0  order by '.$queryord);
     if ($recordSet->EOF) die(texterror($lang['STR_NO_OPEN_ORDERS_FOUND']));
     $recordSet2 = &$conn->Execute('select count(*) from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company));
     if (!$recordSet2->EOF) if ($recordSet2->fields[0]>1) { //if more than 1 location, allow to restrict by location
         echo '<form name="mainform">'.$lang['STR_LOCATION'].':&nbsp;&nbsp;<select name="location"'.INC_TEXTBOX.' onChange="loca()"><option value="0">All';
         $recordSet2 = &$conn->Execute('select inventorylocation.id,company.companyname from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
          while (!$recordSet2->EOF) {
              echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($location,$recordSet2->fields[0]," selected").'>'.$recordSet2->fields[1]."\n";
              $recordSet2->MoveNext();
          };
          echo '</select></form>';
     };
     echo '<table border=0><tr><th rowspan="2" width="10%"><a href="arorderlist.php?sortorder=1&&printable='.$printable.'" class="blacklink" >'.$lang['STR_ORDER_NUMBER'].'</a></th><th colspan="3"><a href="arorderlist.php?sortorder=2&&printable='.$printable.'" class="blacklink">'.$lang['STR_ORDER_BY'].'</a></th><th><a href="arorderlist.php?sortorder=3&&printable='.$printable.'" class="blacklink">'.$lang['STR_PO_NUMBER'].'</a></th><th><a href="arorderlist.php?printable='.$printable.'" class="blacklink">'.$lang['STR_DUE_DATE'].'</a></th></tr>';
     echo '<tr><th>'.$lang['STR_QUANTITY'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_DESCRIPTION'].'</th><th>'.$lang['STR_PRICE_UNIT'].'</th><th>'.$lang['STR_EXTENDED_PRICE'].'</th></tr>';
     while (!$recordSet->EOF) {
           echo '<tr><th><a href="arordpicktick.php?ordernumber='.$recordSet->fields[0].'">'.$recordSet->fields[0].'</a></th><th colspan="3">'.$recordSet->fields[1].'</th><td>'.$recordSet->fields[5];
           //check to see if already past due date and make this display in red
           $duedate=$recordSet->fields[2];
           $year=substr($duedate,0,4);
           $month=substr($duedate,5,2);
           $day=substr($duedate,8,2);
           $duedate=$year.$month.$day;
           $timestamp =  time();
           $date_time_array =  getdate($timestamp);
           $month =  $date_time_array["mon"];
           $day =  $date_time_array["mday"];
           $year =  $date_time_array["year"];

           $today=$year.$month.$day;
           if ($duedate<$today) {
                //red because late
                $colorchoice='<td bgcolor="#FFFFFF" ><font color="#FF0000">';
           } elseif ($duedate==$today) {
               //green because due today
               $colorchoice='<td bgcolor="#FFFFFF"><font color="#00FF00">';
           } else {
                //normal color because on time so far
                $colorchoice="<td><font>";

           };
           if ($printable==1) $colorchoice='<td><font>';
           echo $colorchoice.'<nobr>'.$recordSet->fields[2].'</nobr></font></td></tr>';
           $recordSet1=&$conn->Execute('select note from arordernotes where orderid='.sqlprep($recordSet->fields[4]));
           if (!$recordSet1->EOF) {
                 if ($recordSet1->fields[0]>"") echo '<td></td><td colspan="4">'.$lang['STR_NOTES'].': '.$recordSet1->fields[0].'</td></tr>';
           };
           $recordSet2=&$conn->Execute('select arorderdetail.qtyorder, arorderdetail.qtyship, arorderdetail.priceach, item.itemcode, item.description from arorderdetail,item where arorderdetail.itemid=item.id and item.companyid='.sqlprep($active_company).' and arorderdetail.orderid='.sqlprep($recordSet->fields[4]));
           $ototal=0;
           while (!$recordSet2->EOF) {
                    $ltotal=$recordSet2->fields[2]*($recordSet2->fields[0]-$recordSet2->fields[1]);
                    $ototal+=$ltotal;
                    echo '<tr><td></td><td>'.($recordSet2->fields[0]-$recordSet2->fields[1]).'</td>';
                    echo '<td nowrap>'.$recordSet2->fields[3].'</td>';
                    echo '<td>'.$recordSet2->fields[4].'</td>';
                    echo '<td align="right">'.CURRENCY_SYMBOL.checkdec($recordSet2->fields[2],PREFERRED_DECIMAL_PLACES).'</td>';
                    echo '<td align="right">'.CURRENCY_SYMBOL.checkdec($ltotal,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                    $recordSet2->MoveNext();
           };
           echo '<td></td><td colspan="4">'.$lang['STR_ORDER_TOTAL'].'</td><td align="right">'.CURRENCY_SYMBOL.checkdec($ototal,PREFERRED_DECIMAL_PLACES).'</td></tr>';
           $recordSet->MoveNext();
     };
           echo '</table>';
?>
<?php include('includes/footer.php'); ?>
