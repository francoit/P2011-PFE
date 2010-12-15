<?php include('includes/main.php'); ?>
<?php if ($printable) $printablestr='printable='.$printable.'&'; ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
function loca()
{
        location.href = 'arorderitemlst.php?<?=$printablestr;?>location='+document.mainform.location.value;
}
</script>
<?php  //arorderitemlst.php - April 2001 copyright Noguska-Fostoria, OH  44380
    // prints list of open items on orders sorted by item code, item description
     unset($companystr);
     if ($custcompanyid) { //if external customer
          $customerid=$custcompanyid; //only allow them to edit their info
          $recordSet = &$conn->Execute('select companyid from customer where id='.sqlprep($customerid));
          if ($recordSet->EOF) die(texterror($lang['STR_COMPANY_NOT_FOUND']));
          $companyid=$recordSet->fields[0];
          $companystr=' and arorder.orderbycompanyid='.sqlprep($companyid);
     };
     echo texttitle($lang['STR_OPEN_ITEM_LIST_FOR'] .$companyname);
     echo texttitle($lang['STR_OPEN_ITEMS']);
     if ($sortorder) {
          $queryord="item.description, arorder.duedate desc";
     } else {
          $queryord="item.itemcode,arorder.duedate desc";
     };
     if ($location) $locationstr=' and arorder.inventorylocationid='.sqlprep($location);
     if ($itemcode) $locationstr=$locationstr.' and item.itemcode='.sqlprep($itemcode);
     $recordSet = &$conn->Execute('select item.itemcode, item.description, arorder.id, company.companyname, arorder.ordernumber, arorder.duedate, arorder.entrydate, arorderdetail.qtyorder, arorderdetail.qtyship, arorderdetail.priceach from arorder,item,arorderdetail,company where arorder.id=arorderdetail.orderid and arorder.companyid='.sqlprep($active_company).' and arorder.cancel=0 and arorder.status=0 and company.id=arorder.orderbycompanyid and item.companyid='.sqlprep($active_company).$locationstr.$companystr.' and item.id=arorderdetail.itemid order by '.$queryord);
     if ($recordSet->EOF) die(texterror($lang['STR_NO_OPEN_ITEMS_FOUND']));
     $recordSet2 = &$conn->Execute('select count(*) from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company));
     if (!$recordSet2->EOF) if ($recordSet2->fields[0]>1) { //if more than 1 location, allow to restrict by location
         echo '<form name="mainform">'.$lang['STR_LOCATION'].':&nbsp;&nbsp;<select name="location"'.INC_TEXTBOX.' onChange="loca()"><option value="0">'.$lang['STR_ALL'].' ';
         echo '<input type="hidden" name="itemcode" value="'.$itemcode.'">';
         $recordSet2 = &$conn->Execute('select inventorylocation.id,company.companyname from inventorylocation,company where company.id=inventorylocation.companyid and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
          while (!$recordSet2->EOF) {
              echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($location,$recordSet2->fields[0]," selected").'>'.$recordSet2->fields[1]."\n";
              $recordSet2->MoveNext();
          };
          echo '</select></form>';
     };

     echo '<table border=0><tr><th rowspan="2">'.$lang['STR_ORDER_NUMBER'].'</th><th width="10%"><a href="arorderitemlst.php?printable='.$printable.'" class="blacklink" >'.$lang['STR_ITEM_CODE'].'</a></th><th colspan="5"><a href="arorderitemlst.php?sortorder=1&&printable='.$printable.'" class="blacklink" >'.$lang['STR_DESCRIPTION'].'</a></th></tr>';
     echo '<tr><th colspan="2">'.$lang['STR_ORDER_BY'].'</th><th>'.$lang['STR_DUE_DATE'].'</th><th>'.$lang['STR_QUANTITY'].'</th><th>'.$lang['STR_PRICE_PER_UNIT'].'</th><th>'.$lang['STR_EXTENDED_PRICE'].'</th></tr>';
     while (!$recordSet->EOF) {
           if ($lastitem<>$recordSet->fields[0]) {
               // total of previous item here
               if ($itotal) {
                  echo '<tr><td></td><td></td><td colspan="2">'.$lang['STR_ITEM'].' '.$lastitem.' '.$lang['STR_TOTALS'].'</td><td>'.checkdec($icount,0).'</td>';
                  echo '<td></td><td align="right">'.CURRENCY_SYMBOL.checkdec($itotal,PREFERRED_DECIMAL_PLACES).'</td><tr>';
               };
               $lastitem=$recordSet->fields[0]; // header for new item
               echo '<tr><td></td><th nowrap><a name="'.$lastitem.'">'.$lastitem.'</a></th><th colspan="4">'.$recordSet->fields[1].'</th><tr>';
               $itotal=0;
               $icount=0;
           };
           if (!checkzero($recordSet->fields[7]-$recordSet->fields[8])) {
                echo '<tr><td>'.$recordSet->fields[4].'</td><td colspan="2">'.$recordSet->fields[3].'</td>';
                echo '<td>'.$recordSet->fields[5].'</td>';
                $ltotal=$recordSet->fields[9] * ($recordSet->fields[7]-$recordSet->fields[8]);
                $itotal+=$ltotal;
                $icount+=$recordSet->fields[7]-$recordSet->fields[8];
                echo '<td>'.($recordSet->fields[7]-$recordSet->fields[8]).'</td>';
                echo '<td align="right">'.CURRENCY_SYMBOL.checkdec($recordSet->fields[9],PREFERRED_DECIMAL_PLACES).'</td>';
                echo '<td align="right">'.CURRENCY_SYMBOL.checkdec($ltotal,PREFERRED_DECIMAL_PLACES).'</td></tr>';
            };
            $recordSet->MoveNext();
      };
      if ($itotal) {
            echo '<tr><td></td><td></td><td colspan="2">'.$lang['STR_ITEM'].' '.$lastitem.' '.$lang['STR_TOTALS'].'</td><td>'.checkdec($icount,0).'</td>';
            echo '<td></td><td align="right">'.CURRENCY_SYMBOL.checkdec($itotal,PREFERRED_DECIMAL_PLACES).'</td><tr>';
      };
       echo '</table>';
?>
<?php include('includes/footer.php'); ?>
