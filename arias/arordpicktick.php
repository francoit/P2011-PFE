<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?php require("includes/barcode/barcode.php");
   require("includes/barcode/c39object.php"); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php //arordpicktick.php
     if ($customerid||$ponumber||$ordernumber) { //if the user has submitted initial info
          if ($ordernumber) $orderstr=' and arorder.ordernumber='.sqlprep($ordernumber);
          if ($ponumber) $ponumberstr=' and arorder.ponumber='.sqlprep($ponumber);
          if ($customerid) $customeridstr=' and customer.id='.sqlprep($customerid);
          $recordSet = &$conn->Execute("select count(distinct arorder.ordernumber) from arorder,customer, company as ordercompany, company as shiptocompany where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr);
          if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]>1) { //if there is more than one order matching criteria
               echo texttitle($lang['STR_INVENTORY_PICKLIST']);
               echo '<table><tr><th>'.$lang['STR_ORDER_NUMBER'].'</th><th>'.$lang['STR_PO_NUMBER'].'</th><th>'.$lang['STR_ORDER_COMPANY'].'</th><th>'.$lang['STR_SHIP_TO_COMPANY'].'</th></tr>';
               $recordSet = &$conn->Execute("select distinct arorder.ordernumber, arorder.ponumber, arorder.orderbycompanyid, ordercompany.companyname, arorder.shiptocompanyid, shiptocompany.companyname, arorder.entrydate from arorder,customer, company as ordercompany, company as shiptocompany where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr." order by arorder.entrydate desc");
               while ($recordSet&&!$recordSet->EOF) {
                    echo '<tr><th><a href="arordpicktick.php?ordernumber='.$recordSet->fields[0].'">'.$recordSet->fields[0].'</a></th><th>'.$recordSet->fields[1].'</th><th>'.$recordSet->fields[3].'</th><th>'.$recordSet->fields[5].'</th></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } else {
               $recordSet = &$conn->Execute("select distinct arorder.id, arorder.ordernumber, arorder.ponumber, arorder.orderbycompanyid, ordercompany.companyname, ordercompany.address1, ordercompany.address2, ordercompany.city, ordercompany.state, ordercompany.zip, ordercompany.phone1, ordercompany.phone1comment, arorder.shiptocompanyid, shiptocompany.companyname, shiptocompany.address1, shiptocompany.address2, shiptocompany.city, shiptocompany.state, shiptocompany.zip, shiptocompany.phone1, shiptocompany.phone1comment, arorder.duedate, arorder.entrydate from arorder,customer, company as ordercompany, company as shiptocompany where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr." order by arorder.entrydate desc");
               if (!$recordSet->EOF) {
                    $ordernumber=$recordSet->fields[1];
                    echo '<input type="hidden" name="ordernumber" value="'.$recordSet->fields[1].'">';
                    echo '<table width="100%"><tr><td><h4>'.texttitle($lang['STR_INVENTORY_PICKLIST_ORDER_NUMBER'].$recordSet->fields[1]).'</h4></td></tr></table>';
                    echo '<table width="100%"><tr><td align="left" valign="top">';
                    if ($recordSet->fields[3]==$recordSet->fields[12]) {
                         echo '     <table border="1"><tr><th>Order By &<br>Ship To</th></tr>';
                         echo '     <tr><td>'.$recordSet->fields[4].'</td></tr>';
                         if ($recordSet->fields[5]) echo '     <tr><td>'.$recordSet->fields[5].'</td></tr>';
                         if ($recordSet->fields[6]) echo '     <tr><td>'.$recordSet->fields[6].'</td></tr>';
                         if ($recordSet->fields[7]||$recordSet->fields[8]||$recordSet->fields[9]) echo '     <tr><td>'.$recordSet->fields[7].', '.$recordSet->fields[8].' '.$recordSet->fields[9].'</td></tr>';
                         if ($recordSet->fields[10]) echo '     <tr><td>'.$recordSet->fields[10].'</td></tr>';
                         if ($recordSet->fields[11]) echo '     <tr><td>'.$recordSet->fields[11].'</td></tr>';
                    } else {
                         echo '     <table border="1"><tr><th>'.$lang['STR_ORDER_BY'].'</th><th>'.$lang['STR_SHIP_TO'].'</th></tr>';
                         echo '     <tr><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[13].'</td></tr>';
                         if ($recordSet->fields[5]||$recordSet->fields[14]) echo '     <tr><td>'.$recordSet->fields[5].'</td><td>'.$recordSet->fields[14].'</td></tr>';
                         if ($recordSet->fields[6]||$recordSet->fields[15]) echo '     <tr><td>'.$recordSet->fields[6].'</td><td>'.$recordSet->fields[15].'</td></tr>';
                         if ($recordSet->fields[7]||$recordSet->fields[8]||$recordSet->fields[9]||$recordSet->fields[16]||$recordSet->fields[17||$recordSet->fields[18]]) echo '     <tr><td>'.$recordSet->fields[7].', '.$recordSet->fields[8].' '.$recordSet->fields[9].'</td><td>'.$recordSet->fields[16].', '.$recordSet->fields[17].' '.$recordSet->fields[18].'</td></tr>';
                         if ($recordSet->fields[10]||$recordSet->fields[19]) echo '     <tr><td>'.$recordSet->fields[10].'</td><td>'.$recordSet->fields[19].'</td></tr>';
                         if ($recordSet->fields[11]||$recordSet->fields[20]) echo '     <tr><td>'.$recordSet->fields[11].'</td><td>'.$recordSet->fields[20].'</td></tr>';
                    };
                    echo '     </table>';
                    echo '</td><td align="right" valign="top">';
                    echo '     <table border="1"><tr><th>'.$lang['STR_ORDER_NUMBER'].'</th><th>'.$lang['STR_PO_NUMBER'].'</th></tr>';
                    echo '     <tr><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[2].'</td></tr>';
                    if (extension_loaded("gd")) echo '<tr><td colspan="2" align="center">'.barcodedisplay(BARCODE_IMAGE_TYPE, $recordSet->fields[1], BARCODE_CODE_TYPE, 175, BARCODE_IMAGE_HEIGHT, BARCODE_IMAGE_XRES, BARCODE_IMAGE_FONT).'</td></tr>';
                    echo '<tr><td colspan="2">'.$lang['STR_DUE'].': '.$recordSet->fields[21].'</td></tr></table>';
                    echo '</td></tr></table>';
                    $recordSet2 = &$conn->Execute("select note from arordernotes where orderid=".sqlprep($recordSet->fields[0]));
                    if (!$recordSet2->EOF) {
                         echo '<table><tr><td>'.$lang['STR_NOTES'].':</td><td>';
                         echo nl2br($recordSet2->fields[0]);
                         echo '</td></tr></table>';
                    };
                    echo '<table border="1" width="100%">';
                    $recordSet2 = &$conn->Execute("select arorderdetail.itemid,arorderdetail.qtyorder,arorderdetail.glaccountid,arorderdetail.taxflag,arorderdetail.priceach,arorderdetail.linenumber,item.description,item.itemcode from arorderdetail,item where arorderdetail.itemid=item.id and arorderdetail.orderid=".sqlprep($recordSet->fields[0])." and item.companyid=".sqlprep($active_company)." order by arorderdetail.linenumber");
                    echo '<tr><th>'.$lang['STR_LINE_NUMBER'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_DESCRIPTION'].'</th><th>'.$lang['STR_QUANTITY_ORDERED'].'</th><th>'.$lang['STR_QUANTITY_TO_SHIP'].'</th><th>'.$lang['STR_QTY_BO'].'</th><th>'.$lang['STR_SHIP_QTY'].'</th></tr>';
                    $i=0;

                    if (extension_loaded("gd")) $rowspan=' rowspan="2"';
                    while (!$recordSet2->EOF) {
                         echo '<tr><td'.$rowspan.' valign="top">'.$recordSet2->fields[5].'</td><td valign="top">'.rtrim($recordSet2->fields[7]).'</td>';
                         echo '<td'.$rowspan.' valign="top">'.rtrim($recordSet2->fields[6]).'</td><td'.$rowspan.' valign="top">'.$recordSet2->fields[1].'</td><td'.$rowspan.' valign="top">&nbsp;</td><td'.$rowspan.' valign="top">&nbsp;</td><td'.$rowspan.' valign="top">&nbsp;</td></tr>';
                         if (extension_loaded("gd")) echo "<tr><td>".barcodedisplay(BARCODE_IMAGE_TYPE, rtrim($recordSet2->fields[7]), BARCODE_CODE_TYPE, BARCODE_IMAGE_WIDTH, BARCODE_IMAGE_HEIGHT, BARCODE_IMAGE_XRES, BARCODE_IMAGE_FONT)."</td></tr>";
                         $i++;
                         $recordSet2->MoveNext();
                    };

               };
               echo '</table>'.$i.' '.$lang['STR_LINE_ITEMS_IN_ORDER_NUMBER'].': '.$ordernumber.'<br>';
          };
     } else {

          echo '<form action="arordpicktick.php" method="post" name="mainform"><table>';
          echo '<tr><td>Order #:</td><td><input type="text" name="ordernumber" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'>';
          echo '<tr><td>Purchase Order:</td><td><input type="text" name="ponumber" size="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td>Customer #:</td><td><input type="text" name="customerid" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupcustomer.php\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/calendar.gif" border="0" alt="Customer Lookup"></a></td></tr>';
          echo '</table><input type="submit" value="Search"></form>';
     };

?>
<?php include('includes/footer.php'); ?>
