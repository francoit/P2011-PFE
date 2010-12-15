<?
include('includes/main.php');
include('includes/arfunctions.php');
include('includes/invfunctions.php');
require("includes/barcode/barcode.php");
require("includes/barcode/c128aobject.php");
require("includes/barcode/c128bobject.php");
require("includes/barcode/i25object.php");
require("includes/barcode/c39object.php"); 

// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)


        echo '<center>';
        if ($submit1) {
                checkpermissions('ar');
                if ($orderid) {
                     $recordSet = &$conn->Execute("select arorder.inventorylocationid from arorder where arorder.id=".$orderid);
                     $locationid=$recordSet->fields[0];
                };
                if ($conn->Execute("insert into arordership (orderid,carrierserviceid,shipdate,locationid,entrydate,entryuserid,lastchangeuserid) VALUES (".sqlprep($orderid).", ".sqlprep($carrierserviceid).", ".sqlprep($shipdate).", ".sqlprep($locationid).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).")")) {
                        $recordSet = &$conn->SelectLimit("select id from arordership where orderid=".sqlprep($orderid)." order by entrydate desc",1);
                        if (!$recordSet->EOF) $ordershipid=$recordSet->fields[0];
                        for ($i=1; ${"orderdetailid".$i}; $i++) {
                                if (${"shipqty".$i}>0) {
                                        $conn->Execute("insert into arordershipdetail (orderdetailid,ordershipid,shipqty,entrydate,entryuserid,lastchangeuserid) VALUES (".sqlprep(${"orderdetailid".$i}).", ".sqlprep($ordershipid).", ".sqlprep(${"shipqty".$i}).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).")");
                                        $recordSet2 = &$conn->SelectLimit("select item.id from item,arorderdetail where arorderdetail.id=".sqlprep(${"orderdetailid".$i})." and arorderdetail.itemid=item.id",1);
                                        if (!$recordSet2->EOF) $itemid=$recordSet2->fields[0];
                                        $conn->Execute('update itemlocation set onhandqty=onhandqty-'.${"shipqty".$i}.' where inventorylocationid='.sqlprep($locationid).' and itemid='.sqlprep($itemid));
                                        $conn->Execute('update arorderdetail set qtyship=qtyship+'.${"shipqty".$i}.' where orderid='.sqlprep($orderid).' and itemid='.sqlprep($itemid));
                                };
                        };
                        $smallest=0;
                        $recordSet = &$conn->Execute("select sum(arordershipdetail.shipqty)-arorderdetail.qtyorder from arorderdetail,arordershipdetail where arordershipdetail.orderdetailid=arorderdetail.id and arorderdetail.orderid=".sqlprep($orderid)." group by arordershipdetail.orderdetailid,arorderdetail.qtyorder");
                        while (!$recordSet->EOF) {
                                if ($smallest>$recordSet->fields[0]) $smallest=$recordSet->fields[0]; //make largest=max of recordset
                                $recordSet->MoveNext();
                        };
                        if ($smallest>=0) { //set order as complete.  btw, smallest should never be >0 as that would require shipping more of an item than was ordered.  but lets test for it anyhow just to be safe
                                $action=50;
                        } else { //set it as partial shipment
                                $action=40;
                        };
                        $conn->Execute("insert into arordertrack (orderid,action,trackdate,trackuserid,lastchangeuserid) values (".sqlprep($orderid).",".sqlprep($action).",NOW(),".sqlprep($userid).",".sqlprep($userid).")");
                        if ($action==50) $conn->Execute("update arorder set status=1,lastchangeuserid=".sqlprep($userid)." where id=".sqlprep($orderid));
                        for ($i=1; $i<=$numboxes; $i++) { //insert package data
                                $conn->Execute("insert into arordershippackage (ordershipid,weight,cost,tracknumber) values (".sqlprep($ordershipid).",".sqlprep(${"weight".$i}).", ".sqlprep(${"cost".$i}).",".sqlprep(${"tracknumber".$i}).")");
                        };
                        echo textsuccess($lang['STR_ORDER_SHIPPED_SUCCESSFULLY']);
                        echo '<br><a href="arordshipview.php?printable=1&orderid='.$orderid.'&ordershipid='.$ordershipid.'">'.$lang['STR_PRINT_THIS_PACKING_LIST'].'</a>';
                };
        } elseif ($search) {
                if ($ordernumber) {
                    $orderstr=' and arorder.ordernumber='.sqlprep($ordernumber);
                } elseif ($orderid) {
                    $orderstr=' and arorder.id='.sqlprep($orderid);
                } else {
                  if ($gordernumber) $orderstr=' and arorder.ordernumber='.sqlprep($gordernumber);
                  if ($gponumber) $ponumberstr=' and arorder.ponumber='.sqlprep($gponumber);
                  if ($gcustomerid) $customeridstr=' and customer.id='.sqlprep($gcustomerid);
                  if ($group==1) {
                     $groupstr=' and action<40 and arorder.status=0';
                     $groupstr1=' and arorder.status=0';

                  } elseif ($group==2) {
                     $groupstr=' and action>=50 and arorder.status<>0';
                     $groupstr1=' and arorder.status<>0';
                  } else {
                  };
                };
                $recordSet = &$conn->Execute("select count(distinct arorder.id) from arorder,customer, company as ordercompany, company as shiptocompany where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$groupstr1.$orderstr.$ponumberstr.$customeridstr." and arorder.companyid=".sqlprep($active_company));
                if (!$recordSet->EOF) {
                        if ($recordSet->fields[0]>1) { //if there is more than one order matching criteria, display a list
                                echo texttitle($lang['STR_ORDER_LIST_TO_SELECT_NEW_SHIPMENT_VIEW_PRIOR_SHIPMENT']);
                                echo '<table border="1"><tr><th>'.$lang['STR_ORDER_NUMBER'].'</th><th>'.$lang['STR_PO_NUMBER'].'</th><th>'.$lang['STR_ORDER_COMPANY'].'</th><th>'.$lang['STR_SHIP_TO_COMPANY'].'</th><th>'.$lang['STR_STATUS'].'</th></tr>';
                              //  $recordSet = &$conn->Execute("select arorder.ordernumber, arorder.ponumber, arorder.orderbycompanyid, ordercompany.companyname, arorder.shiptocompanyid, shiptocompany.companyname,arorder.id                                                    from arorder,customer, company as ordercompany, company as shiptocompany where                                                            (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr." and arorder.companyid=".sqlprep($active_company)." order by arorder.entrydate desc");
                                $recordSet = &$conn->Execute('select arorder.ordernumber, arorder.ponumber, arorder.orderbycompanyid, ordercompany.companyname, arorder.shiptocompanyid, shiptocompany.companyname, max(arordertrack.action) as action,arorder.status,arorder.id from customer, company as ordercompany, company as shiptocompany, arorder left join arordertrack on arordertrack.orderid=arorder.id where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id)  '.$groupstr.' and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid'.$orderstr.$ponumberstr.$customeridstr.' and arorder.companyid='.sqlprep($active_company).' group by arorder.id, arordertrack.orderid order by arorder.entrydate desc');
                                while (!$recordSet->EOF) {
                                        if ($recordSet->fields[6]>=50||$recordSet->fields[7]==1) {
                                                $statusstr='<font color="#FF0000">'.$lang['STR_SHIPPED'].'</font>';

                                        } elseif ($recordSet->fields[6]>=40) {
                                                $statusstr='<font color="#FFFF00">'.$lang['STR_PARTIAL'].'</font>';
                                        } else {
                                                $statusstr='<font color="#00FF00">'.$lang['STR_OPEN'].'</font>';
                                        };
                                        echo '<tr><td>';
                                        if ($recordSet->fields[6]>=50||$recordSet->fields[7]==1) {
                                             echo '<a href="arordshipview.php?orderid='.$recordSet->fields[8].'">';
                                        } else {
                                             echo '<a href="arordshipadd.php?ordernumber='.$recordSet->fields[0].'&&search=specific">';
                                        };
                                        echo $recordSet->fields[0].'</a></td><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[5].'</td><td>'.$statusstr.'</td></tr>';
                                        $recordSet->MoveNext();
                                };
                                echo '</table>';
                        } elseif ($recordSet->fields[0]==1) { //we found the one and only order the user wants
                                $recordSet = &$conn->SelectLimit("select arorder.id, arorder.ordernumber from arorder,customer, company as ordercompany, company as shiptocompany where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and arorder.companyid=".sqlprep($active_company)." and arorder.status='0' and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr." order by arorder.entrydate desc",1);
                                if (!$recordSet->EOF) {
                                        $orderid=$recordSet->fields[0];
                                        $ordernumber=$recordSet->fields[1];
                                };
                                if (!$boxes) {
                                        //echo texttitle($lang['STR_ORDER_NUMBER'] '.$ordernumber.' $lang['STR_NUMBER_OF_BOXES_TO_SHIP']);
                                        echo texttitle($lang['STR_ORDER_NUMBER']);
                                        echo '<form action="arordshipadd.php" method="post" name="mainform"><table>';
                                        echo '<input type="hidden" name="ordernumber" value="'.$ordernumber.'">';
                                        echo '<input type="hidden" name="orderid" value="'.$orderid.'">';
                                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BOXES'].':</td><td><input type="text" name="boxes" size="30" maxlength="4" value="1"'.INC_TEXTBOX.'></td></tr>';
                                        if (AR_ORDER_SHIP_WEIGHT_PER_PACKAGE) $perpackagestr=" checked";
                                        echo '<tr><td>'.$lang['STR_SPECIFICY_COST_WEIGHT_PER_PACKAGE'].':</td><td><input type="checkbox" name="perpackage" value="1"'.$perpackagestr.'></td></tr>';
                                        echo '</table><input type="submit" name="search" value="'.$lang['STR_CONTINUE'].'"></form>';
                                } else {
                                        // The Problem of locationid Start here... I don't really if the $recordSet->MoveNext() is interupped in it
                                        
                                        $recordSet = &$conn->Execute("select arorder.id, arorder.ordernumber, arorder.ponumber, arorder.orderbycompanyid, ordercompany.companyname, ordercompany.address1, ordercompany.address2, ordercompany.city, ordercompany.state, ordercompany.zip, ordercompany.phone1, ordercompany.phone1comment, arorder.shiptocompanyid, shiptocompany.companyname, shiptocompany.address1, shiptocompany.address2, shiptocompany.city, shiptocompany.state, shiptocompany.zip, shiptocompany.phone1, shiptocompany.phone1comment, arorder.duedate, arorder.inventorylocationid, arorder.entrydate from arorder,customer, company as ordercompany, company as shiptocompany where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr." order by arorder.entrydate desc");
                                        if (!$recordSet->EOF) {
                                                $orderid=$recordSet->fields[0];
                                                $ordernumber=$recordSet->fields[1];
                                                $recordSet2 = &$conn->Execute("select max(action) as action from arordertrack where orderid=".sqlprep($orderid));
                                                if (!$recordSet2->EOF) if ($recordSet2->fields[0]>=50) die(texterror($lang['STR_THIS_ORDER_HAS_ALREADY_BEEN_FULLY_SHIPPED']));
                                                echo '<form name="mainform" method="post" action="arordshipadd.php">';
                                                $orderid = $recordSet->fields[0];
                                                echo '<input type="hidden" name="orderid" value="'.$recordSet->fields[0].'">';
                                                echo '<input type="hidden" name="nonprintable" value="1">';
                                                echo '<input type="hidden" name="submit1" value="1">';
                                                echo '<input type="hidden" name="ordernumber" value="'.$recordSet->fields[1].'">';
                                                $locationid = $recordSet->fields[22];
                                                echo '<input type="hidden" name="inventorylocationid" value="'.$recordSet->fields[22].'">';
                                                echo '<table width="100%"><tr><tr><td align="left">';
                                                $recordSet2 = &$conn->Execute("select gencompany.name,gencompany.address1,gencompany.address2,gencompany.city,gencompany.state,gencompany.zip,gencompany.country,gencompany.phone1,gencompany.web,gencompany.email,arcompany.imageurl from gencompany left join arcompany on arcompany.id=gencompany.id where gencompany.id=".sqlprep($active_company));
                                                if (!$recordSet2->EOF) {
                                                        $origzip=$recordSet2->fields[5];
                                                        echo '<font size="+2">'.$recordSet2->fields[0].'</font></td><td valign="top" align="left">'.$recordSet2->fields[1]."<br>";
                                                        if ($recordSet2->fields[2]) echo $recordSet2->fields[2]."<br>";
                                                        echo $recordSet2->fields[3].", ".$recordSet2->fields[4]." ".$recordSet2->fields[5]."<br>".$recordSet2->fields[6].'<br><font size="-2">'.$recordSet2->fields[7]."<br>".$recordSet2->fields[8]."<br>".$recordSet2->fields[9]."</font>";
                                                };
                                                echo '</td><td align="right" valign="top">';
                                                if ($recordSet2->fields[10]) echo '<img src="'.$recordSet2->fields[10].'">';
                                                echo '</td></tr></table><table width="100%"><tr><td>'.texttitle($lang['STR_PACKING_LIST'].' - '.$lang['STR_ORDER_NUMBER'].' : '.$recordSet->fields[1]).'</td></tr></table>';
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
                                                $destzip=$recordSet->fields[18];
                                                echo '     </table>';
                                                echo '</td><td align="right" valign="top">';
                                                echo '     <table border="1"><tr><th>'.$lang['STR_ORDER_NUMBER'].'</th><th>'.$lang['STR_PO_NUMBER'].'</th></tr>';
                                                echo '     <tr><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[2].'</td></tr>';
                                                if (extension_loaded("gd")) echo '<tr><td colspan="2" align="center">'.barcodedisplay(BARCODE_IMAGE_TYPE, $recordSet->fields[1], BARCODE_CODE_TYPE, 175, BARCODE_IMAGE_HEIGHT, BARCODE_IMAGE_XRES, BARCODE_IMAGE_FONT).'</td></tr>';
                                                echo '<tr><td colspan="2">'.$lang['STR_DUE'].': '.$recordSet->fields[21].'</td></tr></table>';
                                                echo '</td></tr></table>';
                                                $recordSet2 = &$conn->Execute("select arordershippackage.tracknumber, arordership.shipdate, company.companyname, carrierservice.description,carrier.trackingurlbase,carrier.trackingurlvarname,arordership.id from arordership,carrier,carrierservice,company,arordershippackage where company.id=carrier.companyid and carrierservice.carrierid=carrier.id and arordership.carrierserviceid=carrierservice.id and arordershippackage.ordershipid=arordership.id and arordership.orderid=".sqlprep($orderid)." group by arordershippackage.tracknumber,arordershippackage.weight,arordership.shipdate,company.companyname,carrierservice.description,carrier.trackingurlbase,carrier.trackingurlvarname,arordership.id order by arordership.shipdate,arordership.id,arordershippackage.weight,arordershippackage.tracknumber");
                                                if (!$recordSet2->EOF) echo '<table width="100%" border="1"><tr><th colspan="4">'.$lang['STR_THIS_ORDER_HAS_THE_FOLLOWING_SHIPMENTS'].'</th></tr><tr><th>'.$lang['STR_SHIP_DATE'].'</th><th>'.$lang['STR_CARRIER'].'</th><th>'.$lang['STR_SERVICE'].'</th><th>'.$lang['STR_TRACKING_NUMBER'].'</th></tr>';
                                                while (!$recordSet2->EOF) {
                                                        $priorshipments=1;
                                                        if ($recordSet2->fields[4]) $link='<a href="'.$recordSet2->fields[4].$recordSet2->fields[5].'='.$recordSet2->fields[0].'">';
                                                        echo '<tr><td><a href="arordshipview.php?arordershipid='.$recordSet2->fields[6].'&printable=1&ordernumber='.$ordernumber.'&submitted=1&reprint=1">'.substr($recordSet2->fields[1],0,10).'</a></td><td>'.$recordSet2->fields[2].'</td><td>'.$recordSet2->fields[3].'</td><td>'.$link.$recordSet2->fields[0].'</a></td></tr>';
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
                                                $recordSet2 = &$conn->Execute("select arorderdetail.itemid,arorderdetail.qtyorder,arorderdetail.glaccountid,arorderdetail.taxflag,arorderdetail.priceach,arorderdetail.linenumber,item.description,item.itemcode,sum(arordershipdetail.shipqty),arorderdetail.id,item.lbsperpriceunit from item,arorderdetail left join arordershipdetail on arordershipdetail.orderdetailid=arorderdetail.id where arorderdetail.itemid=item.id and item.companyid=".sqlprep($active_company)." and arorderdetail.orderid=".sqlprep($orderid)." group by arorderdetail.linenumber,arorderdetail.itemid,arorderdetail.qtyorder,arorderdetail.glaccountid,arorderdetail.taxflag,arorderdetail.priceach,item.description,item.itemcode,arorderdetail.id,item.lbsperpriceunit order by arorderdetail.linenumber");
                                                echo '<tr><th>'.$lang['STR_LINE_NUMBER'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_DESCRIPTION'].'</th><th><font size="-2">'.$lang['STR_QUANTITY_ORDERED'].'</font></th><th><font size="-2">'.$lang['STR_QUANTITY_BACK_ORDERED'].'</font></th><th><font size="-2">'.$lang['STR_PREVIOUS_SHIPMENTS'].'</font></th><th><font size="-2">'.$lang['STR_QUANTITY_SHIPPED'].'</font></th></tr>';
                                                $i=1;
                                                if (SHOW_BARCODES_ON_SHIPTICKET&&extension_loaded("gd")) $rowspan=' rowspan="2"';
                                                while (!$recordSet2->EOF) {
                                                        echo '<input type="hidden" name="linenumber'.$recordSet2->fields[5].'" value="'.$recordSet2->fields[5].'">';
                                                        echo '<input type="hidden" name="itemid'.$recordSet2->fields[5].'" value="'.$recordSet2->fields[0].'">';
                                                        echo '<input type="hidden" name="orderdetailid'.$recordSet2->fields[5].'" value="'.$recordSet2->fields[9].'">';
                                                        echo '<input type="hidden" name="priorship'.$recordSet2->fields[5].'" value="'.$recordSet2->fields[8].'">';
                                                        echo '<tr><td'.$rowspan.' valign="top">'.$recordSet2->fields[5].'</td><td valign="top">'.rtrim($recordSet2->fields[7]).'</td>';
                                                        echo '<td'.$rowspan.' valign="top">'.rtrim($recordSet2->fields[6]).'</td><td'.$rowspan.' valign="top">'.$recordSet2->fields[1].'</td><td'.$rowspan.' valign="top">';
                                                        echo ($recordSet2->fields[1]-$recordSet2->fields[8]); //qty backordered
                                                        echo '</td><td'.$rowspan.' valign="top">';
                                                        $oldship=$recordSet2->fields[8];
                                                        echo $oldship;
                                                        echo '</td><td'.$rowspan.' valign="top">';
                                                        if ($oldship>=$recordSet2->fields[1]) { //if it's already shipped all, don't let them ship anymore :)
                                                                echo '0';
                                                        } else {
                                                                $recordSet3 = &$conn->Execute("select itemlocation.onhandqty*item.priceunitsperstockunit from itemlocation,item where itemlocation.inventorylocationid=".sqlprep($locationid)." and item.id=".sqlprep($recordSet2->fields[0])." and itemlocation.itemid=".sqlprep($recordSet2->fields[0]));
                                                                if (!$recordSet3->EOF) if ($recordSet3->fields[0]<$recordSet2->fields[1]-$oldship) $canshipqty=$recordSet3->fields[0];
                                                                if (is_null($canshipqty)) $canshipqty=$recordSet2->fields[1]-$oldship;
                                                                echo '<input type="text" size="10" maxlength="10" name="shipqty'.$recordSet2->fields[5].'" value="'.$canshipqty.'"'.INC_TEXTBOX.'>';
                                                        };
                                                        echo '</td></tr>';
                                                        if (SHOW_BARCODES_ON_SHIPTICKET&&extension_loaded("gd")) echo "<tr><td>".barcodedisplay(BARCODE_IMAGE_TYPE, rtrim($recordSet2->fields[7]), BARCODE_CODE_TYPE, BARCODE_IMAGE_WIDTH, BARCODE_IMAGE_HEIGHT, BARCODE_IMAGE_XRES, BARCODE_IMAGE_FONT)."</td></tr>";
                                                        $i++;
                                                        $estweight+=$canshipqty*$recordSet2->fields[10];
                                                        unset($canshipqty);
                                                        unset($oldship);
                                                        $recordSet2->MoveNext();
                                                };
                                        };
                                        echo '</table>'.($i-1).' '.$lang['STR_LINE_ITEMS_IN_ORDER_NUMBER'].': '.$ordernumber.'<br><br>';
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
                                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHIPPING_DATE'].':</td><td><input type="text" name="shipdate" onchange="formatDate(this)" size="30" maxlength="10" value="'.$todate.'"'.INC_TEXTBOX.'></td></tr>';
                                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHIPPING_METHOD'].':</td><td><select name="carrierserviceid"'.INC_TEXTBOX.'>';
                                        $recordSet = &$conn->Execute("select carrierservice.id,company.companyname,carrierservice.description from company,carrier,carrierservice where company.id=carrier.companyid and carrierservice.carrierid=carrier.id order by company.companyname,carrierservice.description");
                                        while (!$recordSet->EOF) {
                                                echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],$carrierserviceid," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
                                                $recordSet->MoveNext();
                                        };
                                        echo '</td></tr>';
                                        $iboxes=1;
                                        if ($perpackage) $iboxes = $boxes;
                                        echo '<input type="hidden" name="numboxes" value="'.$iboxes.'">';
                                        if ($iboxes==1) { //get ups shipping info --NEEDS WORK (is correct now, but should be able to pull from multiple carriers, services
                                                $weight=$estweight;
                                                $cost=getupsrate(3, 'GND', $origzip, $destzip, $estweight, 'US', 'US', 'Regular+Daily+Pickup', '00', 1);
                                        };
                                        for ($i=1;$i<=$iboxes;$i++) {
                                                echo '<tr><td colspan="2">'.$lang['STR_PACKAGE_NUMBER'].''.$i.'</td></tr>';
                                                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TRACKING_NUMBER'].':</td><td><input type="text" name="tracknumber'.$i.'" size="30" maxlength="30" value="'.$tracknumber.'"'.INC_TEXTBOX.'></td></tr>';
                                                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WEIGHT'].':</td><td><input type="text" name="weight'.$i.'" onchange="validatenum(this)" size="30" maxlength="12" value="'.$weight.'"'.INC_TEXTBOX.'></td></tr>';
                                                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FREIGHT_CHARGES'].':</td><td><input type="text" name="cost'.$i.'" onchange="validatenum(this)" size="30" maxlength="14" value="'.$cost.'"'.INC_TEXTBOX.'></td></tr><tr><td colspan="2">&nbsp</td></tr>';
                                        };
                                        echo '</table><input type="submit" name="submit" value="'.$lang['STR_SAVE_SHIPPING_INFO'].'"></form>';
                                };
                        } else { //no order matched search
                                echo texterror($lang['STR_NO_OPEN_ORDERS_MATCHED_SEARCH']);
                        };
                };
        } else {
                echo texttitle($lang['STR_SEARCH_FOR_ORDER_TO_SHIP']);
                echo '<form action="arordshipadd.php" method="post" name="mainform"><table>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ORDER_NUMBER'].' :</td><td><input type="text" name="gordernumber" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PURCHASE_ORDER'].' :</td><td><input type="text" name="gponumber" size="30"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_NUMBER'].' :</td><td><input type="text" name="gcustomerid" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupcustomer.php?name=customerid\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Customer Lookup"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GROUP_TO_INCLUDE'].' :</td><td><input type="radio" name="group" value="1" checked>Pending Only<br>';
                echo '<input type="radio" name="group" value="2" >'.$lang['STR_SHIPPED_ONLY'].'<br>';
                echo '<input type="radio" name="group" value="3" >'.$lang['STR_ALL_ORDERS'].'</td></tr>';
                echo '</table><br><input type="submit" name="search" value="'.$lang['STR_SEARCH'].'"></form>';
        };
        
echo '</center>';

include("includes/footer.php");
?>

