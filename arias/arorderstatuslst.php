<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php if ($printable) $printablestr='printable='.$printable.'&'; ?>
<script language="JavaScript">
function loca()
{
        location.href = 'arorderstatuslst.php?<?=$printablestr;?>location='+document.mainform.location.value;
}
</script>
<?php //arorderstatuslst.php - April 2001 copyright Noguska - Fostoria, OH  44830
   //prints lists tracking information for orders
     echo texttitle($lang['STR_ORDER_STATUS_LIST_FOR'] .$companyname);
     echo texttitle($lang['STR_OPEN_ORDERS_ONLY']);
     if ($sortorder==1) {
          $queryord=" order by arorder.ordernumber desc";
     } elseif ($sortorder==2) {
          $queryord=" order by company.companyname, arorder.duedate desc";
     } elseif ($sortorder==3) {
          $queryord=" order by arorder.ponumber desc, arorder.ordernumber desc";
     } else {
          $queryord=" order by arorder.duedate desc, arorder.ordernumber desc";
     };
     if ($location) $locationstr=' and arorder.inventorylocationid='.sqlprep($location);
     $recordSet = &$conn->Execute('select arorder.ordernumber, company.companyname, arorder.duedate, arorder.entrydate,arorder.id,arorder.ponumber from arorder,company where arorder.companyid='.sqlprep($active_company).$locationstr.' and arorder.orderbycompanyid=company.id and arorder.status=0 and arorder.cancel=0'.$queryord);
     if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_ORDERS_FOUND']));
     $recordSet2 = &$conn->Execute('select count(*) from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company));
     if (!$recordSet2->EOF) if ($recordSet2->fields[0]>1) { //if more than 1 location, allow to restrict by location
         echo '<form name="mainform">'.$lang['STR_LOCATION'].':&nbsp;&nbsp;<select name="location"'.INC_TEXTBOX.' onChange="loca()"><option value="0">'.$lang['STR_ALL'].' ';
         $recordSet2 = &$conn->Execute('select inventorylocation.id,company.companyname from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
          while (!$recordSet2->EOF) {
              echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($location,$recordSet2->fields[0]," selected").'>'.$recordSet2->fields[1]."\n";
              $recordSet2->MoveNext();
          };
          echo '</select></form>';
     };
      echo '<table border=0><tr><th width="10%" rowspan="2"><a href="arorderstatuslst.php?'.$printablestr.'sortorder=1" class="blacklink" >'.$lang['STR_ORDER_NUMBER'].'</a></th><th colspan="3"><a href="arorderstatuslst.php?sortorder=2'.$printablestr.'" class="blacklink">'.$lang['STR_ORDER_BY'].'</a></th><th rowspan="2"><a href="arorderstatuslst.php?sortorder=3'.$printablestr.'" class="blacklink">'.$lang['STR_PO_NUMBER'].'</a></th><th rowspan="2"><a href="arorderstatuslst.php?'.$printablestr.'" class="blacklink">'.$lang['STR_DUE_DATE'].'</a></th></tr>';
     echo '<th>'.$lang['STR_ACTION'].'</th><th>'.$lang['STR_DATE_TIME'].'</th><th>'.$lang['STR_PERSON'].'</th></tr>';
     while (!$recordSet->EOF) {
           echo '<tr><td>'.$recordSet->fields[0].'</td><td colspan="3">'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[5].'</td><td>'.$recordSet->fields[2].'</td></tr>';
           $recordSet2=&$conn->Execute('select arordertrack.action, arordertrack.trackdate, arordertrack.trackuserid, genuser.name from arordertrack,genuser where arordertrack.orderid='.sqlprep($recordSet->fields[4]).' and arordertrack.trackuserid=genuser.id order by trackdate');
           $ototal=0;
           while (!$recordSet2->EOF) {
                    $ototal=1;
                    $action=$recordSet2->fields[0];
                    unset($actionmsg);
                    $ender="</td>";
                    switch ($action) {
                         case 30:
                             $actionmsg='<th align="right">'.$lang['STR_STOP_FILLING_ORDER'].'</td><th>';
                             break;
                         case 20:
                             $actionmsg='<th align="left">'.$lang['STR_START_FILLING_ORDER'].'</th><td>';
                             break;
                         case 10:
                             $actionmsg='<td align="right"><i>'.$lang['STR_RETURN_ORDER_FROM_CHECKOUT'].'</i></td><th>';
                             $ender="</th>";
                             break;
                         case 0:
                             $actionmsg='<td align="left"><i>'.$lang['STR_CHECK_OUT_ORDER'].'</i></th><td>';
                             $ender="</th>";
                             break;
                         case 50:
                             $actionmsg='<td align="left"><i>'.$lang['STR_FINAL_SHIPMENT'].'</i></th><td>';
                             $ender="</th>";
                             break;
                         case 40:
                             $actionmsg='<td align="left"><i>'.$lang['STR_PARTIAL_SHIPMENT'].'</i></th><td>';
                             $ender="</th>";
                             break;

                         default:
                             $actionmsg='<td>'.$lang['STR_UNKNOWN_ACTION'].':'.$action.'</td><td>';
                             break;
                    };
                    echo '<td></td>'.$actionmsg.$recordSet2->fields[1].$ender.'<td>'.$recordSet2->fields[3].'</td></tr>';
                    $recordSet2->MoveNext();
           };
           if ($ototal==0) echo '<tr><td></td><td><center>--------</center></td></tr>';
           $recordSet->MoveNext();
     };
     echo '</table>';
?>
<?php include('includes/footer.php'); ?>
