<?
include("includes/main.php");
include("includes/arfunctions.php");
include("includes/invfunctions.php");
require("includes/barcode/barcode.php");
require("includes/barcode/c128aobject.php");
require("includes/barcode/c128bobject.php");
require("includes/barcode/i25object.php");
require("includes/barcode/c39object.php");

?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php    echo '<center>';
        if ($ordershipid) {
                $recordSet = &$conn->Execute("select arorder.id, arorder.ordernumber, arorder.ponumber, arorder.orderbycompanyid, ordercompany.companyname, ordercompany.address1, ordercompany.address2, ordercompany.city, ordercompany.state, ordercompany.zip, ordercompany.phone1, ordercompany.phone1comment, arorder.shiptocompanyid, shiptocompany.companyname, shiptocompany.address1, shiptocompany.address2, shiptocompany.city, shiptocompany.state, shiptocompany.zip, shiptocompany.phone1, shiptocompany.phone1comment, arorder.duedate, arorder.inventorylocationid, arorder.entrydate from arorder,customer, company as ordercompany, company as shiptocompany where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid and arorder.id=".sqlprep($orderid)." order by arorder.entrydate desc");
                if (!$recordSet->EOF) {
                        $ordernumber=$recordSet->fields[1];
                        echo '<table width="100%"><tr><tr><td align="left">';
                        $recordSet2 = &$conn->Execute("select gencompany.name,gencompany.address1,gencompany.address2,gencompany.city,gencompany.state,gencompany.zip,gencompany.country,gencompany.phone1,gencompany.web,gencompany.email,arcompany.imageurl from gencompany left join arcompany on arcompany.id=gencompany.id where gencompany.id=".sqlprep($active_company));
                        if (!$recordSet2->EOF) {
                                echo '<font size="+2">'.$recordSet2->fields[0].'</font></td><td valign="top" align="left">'.$recordSet2->fields[1]."<br>";
                                if ($recordSet2->fields[2]) echo $recordSet2->fields[2]."<br>";
                                echo $recordSet2->fields[3].", ".$recordSet2->fields[4]." ".$recordSet2->fields[5]."<br>".$recordSet2->fields[6].'<br><font size="-2">'.$recordSet2->fields[7]."<br>".$recordSet2->fields[8]."<br>".$recordSet2->fields[9]."</font>";
                        };
                        echo '</td><td align="right" valign="top">';
                        if ($recordSet2->fields[10]) echo '<img src="'.$recordSet2->fields[10].'">';
                        echo '</td></tr></table><table width="100%"><tr><td>'.texttitle($lang['STR_PACKING_LIST_ORDER_NUMBER'] .$recordSet->fields[1]).'</td></tr></table>';
                        echo '<table width="100%"><tr><td align="left" valign="top">';
                        if ($recordSet->fields[3]==$recordSet->fields[12]) {
                                echo '     <table border="1"><tr><th>'.$lang['STR_ORDER_BY'].' &<br>'.$lang['STR_SHIP_TO'].'</th></tr>';
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
                        $recordSet2 = &$conn->Execute("select count(arordershippackage.id),arordership.shipdate,company.companyname,carrierservice.description,carrier.trackingurlbase,carrier.trackingurlvarname,arordership.id from arordership,carrier,carrierservice,company,arordershippackage where company.id=carrier.companyid and carrierservice.carrierid=carrier.id and arordership.carrierserviceid=carrierservice.id and arordershippackage.ordershipid=arordership.id and arordership.orderid=".sqlprep($orderid)." group by arordership.shipdate,company.companyname,carrierservice.description,carrier.trackingurlbase,carrier.trackingurlvarname,arordership.id,arordershippackage.weight,arordershippackage.tracknumber order by arordership.shipdate,arordership.id,arordershippackage.weight,arordershippackage.tracknumber");
                        if (!$recordSet2->EOF) {
                                echo '<table width="100%" border="1"><tr><th colspan="4">'.$lang['STR_THIS_ORDER_HAS_THE_FOLLOWING_SHIPMENTS'].'</th></tr><tr><th>'.$lang['STR_SHIP_DATE'].'</th><th>'.$lang['STR_CARRIER_SERVICE'].'</th><th>'.$lang['STR_NUMBER_OF_PACKAGES'].'</th></tr>';
                                $priorshipments=1;
                        };
                        while (!$recordSet2->EOF) {
                                echo '<tr><td><a href="arordshipview.php?ordershipid='.$recordSet2->fields[6].'&printable=1&orderid='.$orderid.'">'.substr($recordSet2->fields[1],0,10).'</a></td><td>'.$recordSet2->fields[2].' '.$recordSet2->fields[3].'</td><td>'.$recordSet2->fields[0].'</td></tr>';
                                $recordSet2->MoveNext();
                        };
                        if ($priorshipments) echo '</table>';
                        $recordSet2 = &$conn->Execute("select note from arordernotes where orderid=".sqlprep($recordSet->fields[0]));
                        if (!$recordSet2->EOF) { // if there are notes to print
                                if ($recordSet2->fields[0]) {
                                        echo '<table><tr><td>Notes:</td><td>';
                                        echo nl2br($recordSet2->fields[0]);
                                        echo '</td></tr></table>';
                                };
                        };
                        echo '<table border="1" width="100%">';
                        $recordSet2 = &$conn->Execute("select arorderdetail.itemid,arorderdetail.qtyorder,arorderdetail.glaccountid,arorderdetail.taxflag,arorderdetail.priceach,arorderdetail.linenumber,item.description,item.itemcode,sum(arordershipdetail.shipqty),arorderdetail.id,arordershipdetail.shipqty from item,arorderdetail left join arordershipdetail on arordershipdetail.orderdetailid=arorderdetail.id and arordershipdetail.ordershipid=".sqlprep($ordershipid)." where arorderdetail.itemid=item.id and item.companyid=".sqlprep($active_company)." and arorderdetail.orderid=".sqlprep($recordSet->fields[0])." group by arorderdetail.itemid,arorderdetail.qtyorder,arorderdetail.glaccountid,arorderdetail.taxflag,arorderdetail.priceach,arorderdetail.linenumber,item.description,item.itemcode,arorderdetail.id,arordershipdetail.shipqty order by arorderdetail.linenumber");
                        echo '<tr><th>'.$lang['STR_LINE_NUMBER'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_DESCRIPTION'].'</th><th><font size="-2">'.$lang['STR_QUANTITY_ORDERED'].'</font></th><th><font size="-2">'.$lang['STR_QUANTITY_BACK_ORDERED'].'</font></th><th><font size="-2">'.$lang['STR_PREVIOUS_SHIPMENTS'].'</font></th><th><font size="-2">'.$lang['STR_QUANTITY_SHIPPED'].'</font></th></tr>';
                        $i=1;
                        if (SHOW_BARCODES_ON_SHIPTICKET&&extension_loaded("gd")) $rowspan=' rowspan="2"';
                        while (!$recordSet2->EOF) {
                                $recordSet3 = &$conn->Execute("select sum(arordershipdetail.shipqty) from arordershipdetail where arordershipdetail.orderdetailid=".sqlprep($recordSet2->fields[9]));
                                if (!$recordSet3->EOF) {
                                        $othershipqty=$recordSet3->fields[0];
                                } else {
                                        $othershipqty=0;
                                };
                                echo '<tr><td'.$rowspan.' valign="top">'.$recordSet2->fields[5].'</td><td valign="top">'.rtrim($recordSet2->fields[7]).'</td>';
                                echo '<td'.$rowspan.' valign="top">'.rtrim($recordSet2->fields[6]).'</td><td'.$rowspan.' valign="top">'.$recordSet2->fields[1].'</td><td'.$rowspan.' valign="top">';
                                echo ($recordSet2->fields[1]-$recordSet3->fields[0]); //qty backordered
                                echo '</td><td'.$rowspan.' valign="top">';
                                echo ($recordSet3->fields[0]-$recordSet2->fields[10]);
                                echo '</td><td'.$rowspan.' valign="top">'.($recordSet2->fields[10]+0).'</td></tr>';
                                if (SHOW_BARCODES_ON_SHIPTICKET&&extension_loaded("gd")) echo "<tr><td>".barcodedisplay(BARCODE_IMAGE_TYPE, rtrim($recordSet2->fields[7]), BARCODE_CODE_TYPE, BARCODE_IMAGE_WIDTH, BARCODE_IMAGE_HEIGHT, BARCODE_IMAGE_XRES, BARCODE_IMAGE_FONT)."</td></tr>";
                                $i++;
                                $recordSet2->MoveNext();
                        };
                } else {
                        die(texterror($lang['STR_NO_ORDERS_MATCHED_YOUR_SEARCH']));
                };
                echo '</table>'.($i-1).$lang['STR_LINE_ITEMS_IN_ORDER_NUMBER'].$ordernumber.'<br><br>';
                echo '<table border="1">';
                if ($shipdate) {
                        $todate=$shipdate;
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
                        $todate=date("Y-m-d", $timestamp);
                };
                $recordSet2 = &$conn->Execute("select arordershippackage.ordershipid,arordershippackage.weight,arordershippackage.cost,arordershippackage.tracknumber from arordershippackage where arordershippackage.ordershipid=".sqlprep($ordershipid)." order by arordershippackage.id");
                $i=1;
                while (!$recordSet2->EOF) {
                        echo '<tr><td colspan="3">Package #'.$i.'</td></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TRACKING_NUMBER'].':</td><td>'.$recordSet2->fields[3].'</td><td>';
                        if (extension_loaded("gd")) echo barcodedisplay(BARCODE_IMAGE_TYPE, rtrim($recordSet2->fields[3]), BARCODE_CODE_TYPE, BARCODE_IMAGE_WIDTH, BARCODE_IMAGE_HEIGHT, BARCODE_IMAGE_XRES, BARCODE_IMAGE_FONT);
                        echo '</td></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WEIGHT'].':</td><td>'.$recordSet2->fields[1].'</td><td></td></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FREIGHT_CHARGES'].':</td><td>'.CURRENCY_SYMBOL.num_format($recordSet2->fields[2],2).'</td><td></td></tr><tr><td colspan="3">&nbsp;</td></tr>';
                        $i++;
                        $recordSet2->MoveNext();
                };
        } elseif ($customerid||$ponumber||$ordernumber||$orderid||$location) { //lets try to find the order
                if ($orderid) {
                     $orderidstr=' and arorder.id='.sqlprep($orderid);
                } else {
                     if ($ordernumber) $orderstr=' and arorder.ordernumber='.sqlprep($ordernumber);
                     if ($ponumber) $ponumberstr=' and arorder.ponumber='.sqlprep($ponumber);
                     if ($customerid) $customeridstr=' and customer.id='.sqlprep($customerid);
                     if ($location) $locationstr=' and arorder.inventorylocationid='.sqlprep($location);
                };
                $recordSet = &$conn->Execute("select count(distinct arorder.id) from arorder,customer, company as ordercompany, company as shiptocompany where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderidstr.$orderstr.$ponumberstr.$customeridstr.$locationstr." and arorder.companyid=".sqlprep($active_company));
                if (!$recordSet->EOF) {
                        if ($recordSet->fields[0]>1) { //if there is more than one order matching criteria, display a list
                                echo texttitle($lang['STR_ORDER_LIST_FOR_SHIPMENTS']);
                                echo '<table border="1"><tr><th>'.$lang['STR_ORDER_NUMBER'].'</th><th>'.$lang['STR_PO_NUMBER'].'</th><th>'.$lang['STR_ORDER_COMPANY'].'</th><th>'.$lang['STR_SHIP_TO_COMPANY'].'</th><th>'.$lang['STR_STATUS'].'</th></tr>';
                                $recordSet = &$conn->Execute("select arorder.ordernumber, arorder.ponumber, arorder.orderbycompanyid, ordercompany.companyname, arorder.shiptocompanyid, shiptocompany.companyname, max(arordertrack.action) as action,arorder.id from arorder,customer, company as ordercompany, company as shiptocompany, arordertrack where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr.$locationstr." and arorder.companyid=".sqlprep($active_company)." and arordertrack.orderid=arorder.id group by arordertrack.orderid order by arorder.entrydate desc");
                                $href=1;
                                while (!$recordSet->EOF) {
                                        if ($recordSet->fields[6]>=50) {
                                                $statusstr='<font color="#00FF00">'.$lang['STR_SHIPPED'].'</font>';
                                        } elseif ($recordSet->fields[6]>=40) {
                                                $statusstr='<font color="#00FFFF">'.$lang['STR_PARTIAL'].'</font>';
                                        } else {
                                                $statusstr='<font color="#FF0000">'.$lang['STR_OPEN_NO_SHIPMENTS'].'</font>';
                                                $href=0;
                                        };
                                        echo '<tr><td>';
                                        if ($href==1) {
                                           echo '<a href="arordshipview.php?orderid='.$recordSet->fields[7].'&ordernumber='.$recordSet->fields[0].'">'.$recordSet->fields[0].'</a>';
                                        } else {
                                           echo $recordSet->fields[0];
                                        };
                                        echo '</td><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[5].'</td><td>'.$statusstr.'</td></tr>';
                                        $recordSet->MoveNext();
                                };
                                echo '</table>';
                        } elseif ($recordSet->fields[0]==1) { //we found the one and only order the user wants, display list of shipments
                                $recordSet1 = &$conn->Execute("select arorder.id,arorder.ordernumber from arorder,customer, company as ordercompany, company as shiptocompany, arordertrack where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$orderidstr.$ponumberstr.$customeridstr.$locationstr." and arorder.companyid=".sqlprep($active_company)." and arordertrack.orderid=arorder.id group by arorder.id,arorder.ordernumber,arorder.entrydate order by arorder.entrydate desc");
                                if (!$recordSet1||$recordSet1->EOF) die(texterror($lang['STR_NO_SHIPMENTS_FOUND']));
                                $orderid=$recordSet1->fields[0];
                                $ordernumber=$recordSet1->fields[1];
                                echo texttitle($lang['STR_ORDER_NUMBER'] .$ordernumber. $lang['STR_SHIPMENT_LIST']);
                                //echo '<table border="1"><tr><th>''.$lang['STR_SHIP_DATE'].''</th><th>''.$lang['STR_CARRIER_SLASH_SERVICE'].''</th><th>''.$lang['STR_NUMBER_OF_PACKAGES'].''</th></tr>';
                                $recordSet = &$conn->Execute("select arordership.id, substring(arordership.shipdate,1,10), company.companyname, carrierservice.description, count(arordershippackage.id), arordership.orderid from arordership,carrier,carrierservice,company,arordershippackage where arordership.orderid=".sqlprep($orderid)." and arordership.carrierserviceid=carrierservice.id and carrierservice.carrierid=carrier.id and carrier.companyid=company.id and arordershippackage.ordershipid=arordership.id group by arordership.id,arordership.shipdate,company.companyname,carrierservice.description,arordership.orderid order by arordership.shipdate,company.companyname");

                                while (!$recordSet->EOF) {
                                        echo '<tr><td><a href="arordshipview.php?printable=1&orderid='.$recordSet->fields[5].'&ordershipid='.$recordSet->fields[0].'">'.$recordSet->fields[1].'</a></td><td>'.$recordSet->fields[2].' - '.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</td></tr>';
                                        $recordSet->MoveNext();
                                };
                                echo '</table>';
                        } else { //no order matched search
                                echo texterror($lang['STR_NO_ORDERS_MATCHED_YOUR_SEARCH']);
                        };
                } else { //count query failed... this is bad
                        echo texterror($lang['STR_NO_ORDERS_MATCHED_YOUR_SEARCH'] ( $lang['STR_COUNT_QUERY_FAILED']));
                };
        } else {
                echo texttitle($lang['STR_SEARCH_FOR_ORDER_TO_SHIP']);
                echo '<form action="arordshipview.php" method="post" name="mainform"><table>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ORDER_NUMBER'].':</td><td><input type="text" name="ordernumber" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PURCHASE_ORDER'].':</td><td><input type="text" name="ponumber" size="30"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_NUMBER'].':</td><td><input type="text" name="customerid" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupcustomer.php?name=customerid\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Customer Lookup"></a></td></tr>';
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
                echo '</table><br><input type="submit" name="submit" value="'.$lang['STR_SEARCH'].'"></form>';
        };
  
echo '</center>';

include_once("includes/footer.php");
?>
