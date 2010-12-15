<?php include("includes/main.php"); ?>
<?php include("includes/invfunctions.php"); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center>';
     echo texttitle($lang['STR_FILL_ORDER_START_STOP']);
     if ($start) $action="20";
     if ($stop) $action="30";
     if ($start||$stop) { //insert into file
           $today=createtime("Y-m-d h:m");
           checkpermissions('ar');
           if ($conn->Execute('insert into arordertrack (orderid,action,trackdate,trackuserid,lastchangeuserid) values ('.sqlprep($orderid).', '.sqlprep($action).', NOW(), '.sqlprep($cuser).', '.sqlprep($userid).')') === false) {
                    echo texterror($lang['STR_ERROR_TRACKING_INFO']);
                    return 0;
           } else {
                    echo textsuccess($lang['STR_ORDER_UPDATED_SUCCESSFULLY']);
           };
//           $customerid="";
//           $ponumber="";
           $ordernumber="";
//           $stop="";
//           $start="";
     };

     if ($start||$stop||(!$gordernumber&&!$gponumber&&!$gcustomerid)) {
          unset($stop);
          unset($start);
          echo '<form action="arorderfill.php" method="post" name="mainform"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_OPERATOR'].':</td><td><select name="cuser"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id, name from genuser order by name');
          while ($recordSet&&!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],$userid," selected").'>'.$recordSet->fields[1]."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ORDER_NUMBER'].':</td><td><input type="text" name="gordernumber" value="'.$gordernumber.'" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PURCHASE_ORDER'].':</td><td><input type="text" name="gponumber" value="'.$gponumber.'" size="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_NUMBER'].':</td><td><input type="text" name="gcustomerid" value="'.$gcustomerid.'" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupcustomer.php?name=gcustomerid\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Customer Lookup"></a></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_SEARCH'].'"></form>';
     } else {
       if ($gcustomerid||$gponumber||$gordernumber) { //if the user has submitted initial info
          if ($ordernumber) {
             $orderstr=' and arorder.ordernumber='.sqlprep($ordernumber);
          } else {
             if ($gordernumber) $orderstr=' and arorder.ordernumber='.sqlprep($gordernumber);
             if ($gponumber) $ponumberstr=' and arorder.ponumber='.sqlprep($gponumber);
             if ($gcustomerid) $customeridstr=' and customer.id='.sqlprep($gcustomerid);
          };
          $recordSet = &$conn->Execute("select count(distinct arorder.id) from arorder,customer, company as ordercompany, company as shiptocompany where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr." and arorder.status=0 and arorder.companyid=".sqlprep($active_company));
          if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]>1) { //if there is more than one order matching criteria
               echo '<table border="1"><tr><th>'.$lang['STR_ORDER_NUMBER'].'</th><th>     '.$lang['STR_LAST_ACTION'].'     </th><th>'.$lang['STR_PO_NUMBER'].'</th><th>'.$lang['STR_ORDER_COMPANY'].'</th><th>'.$lang['STR_SHIP_TO_COMPANY'].'</th></tr>';
               $recordSet = &$conn->Execute("select arorder.ordernumber, arorder.ponumber, arorder.orderbycompanyid, ordercompany.companyname, arorder.shiptocompanyid, shiptocompany.companyname,arorder.id from arorder,customer, company as ordercompany, company as shiptocompany where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and arorder.status=0 and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr." and arorder.companyid=".sqlprep($active_company)." order by arorder.entrydate desc");
               while ($recordSet&&!$recordSet->EOF) {
                    $recordSet2=&$conn->Execute('select arordertrack.action,arordertrack.trackdate,genuser.name from arordertrack,genuser where genuser.id=arordertrack.trackuserid and arordertrack.trackuserid='.sqlprep($cuser).' and (arordertrack.action=20 or arordertrack.action=30) and arordertrack.orderid='.sqlprep($recordSet->fields[6]).' order by arordertrack.trackdate desc' );
                    echo '<tr><td><center><a href="arorderfill.php?ordernumber='.$recordSet->fields[0].'&&gordernumber='.$gorgernumber.'&&gponumber='.$gponumber.'&&gcustomerid='.$gcustomerid.'&&cuser='.$cuser.'">'.$recordSet->fields[0].'</a></center></td>';
                    if (!$recordSet2->EOF) {
                        if ($recordSet2->fields[0]==30) {
                              $action='<font color="red">'.$recordSet2->fields[2]." '.{$lang['STR_FILL_COMPLETED']}.' -".$recordSet2->fields[1];
                        } else {
                               $action='<font color="green">'.$recordSet2->fields[2]." '.{$lang['STR_STARTED_FILL']}.' -".$recordSet2->fields[1];
                        };
                        echo '<td>'.$action.'</td>';
                    } else {
                        echo '<td><center>------</center></td>';
                    };
                    echo '<td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[5].'<td>';
                    echo '</tr>';


                    $recordSet->MoveNext();
               };
               echo '</table>';

          } else {
               $recordSet = &$conn->Execute("select arorder.id, arorder.ordernumber, ordercompany.companyname from arorder,customer, company as ordercompany, company as shiptocompany where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and arorder.status=0 and ordercompany.id=arorder.orderbycompanyid and arorder.companyid=".sqlprep($active_company)." and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr);
               if ($recordSet&&!$recordSet->EOF) {
                    echo '<form action="arorderfill.php" method="post" name="mainform"><table border="1" align="center">';
                    echo '<input type="hidden" name="ordernumber" value="'.$recordSet->fields[1].'">';
                    echo '<input type="hidden" name="orderid" value="'.$recordSet->fields[0].'">';
                    echo '<input type="hidden" name="cuser" value="'.$cuser.'">';
                    echo '<input type="hidden" name="gponumber" value="'.$gponumber.'">';
                    echo '<input type="hidden" name="gcustomerid" value="'.$gcustomerid.'">';
                    echo '<input type="hidden" name="gordernumber" value="'.$gordernumber.'">';
                    $recordSet2=&$conn->Execute('select action from arordertrack where trackuserid='.sqlprep($cuser).' and (action=20 or action=30) and orderid='.sqlprep($recordSet->fields[0]).' order by trackdate DESC' );
                    if ($recordSet&&!$recordSet2->EOF) {
                        $checked=$recordSet2->fields[0];
                    } else {
                        $checked=30;
                    };
                    if ($checked==30) {
                         echo '<tr><th colspan="2">'.$lang['STR_START_FILLING_ORDER_NUMBERS'].': '.$recordSet->fields[1].'<br> for '.$recordSet->fields[2].'</th></tr>';
                         echo '<tr><td align="center"><button type="submit" name="start" value="20" ><img src="images/temp/Signal_Light_-_Green.jpg" height="30" width="30"></button></td></tr>';
                    } else {
                         echo '<tr><th colspan="2">'.$lang['STR_START_FILLING_ORDER_NUMBERS'].': '.$recordSet->fields[1].'<br> for '.$recordSet->fields[2].'</th></tr>';
                         echo '<tr><td align="center"><button type="submit" name="stop" value="30" ><img src="images/temp/Signal_Light_-_Red.jpg" height="30" width="30"></button></td></tr>';
                    };
                    echo '</td></tr></table>';
                    echo '</table>';
               } else {
                    die(texterror($lang['STR_NO_MATCHING_ORDERS_FOUND']));
               };
          };

       };
     };
     
echo '</center>';
?>

<?php include_once("includes/footer.php"); ?>
