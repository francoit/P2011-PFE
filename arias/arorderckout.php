<?php include("includes/main.php"); ?>
<?php include("includes/invfunctions.php"); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center>';
     echo texttitle($lang['STR_ORDER_CHECKOUT_CHECKIN']);
     if ($savedata) {
       //insert into file
           $today=createtime("Y-m-d h:m");
           checkpermissions('ar');
           if ($conn->Execute('insert into arordertrack (orderid,action,trackdate,trackuserid,lastchangeuserid) values ('.sqlprep($orderid).', '.sqlprep($action).', NOW(), '.sqlprep($cuser).', '.sqlprep($userid).')') === false) {
                    echo texterror($lang['STR_ERROR_ADDING_CHECK_OUT_IN_INFO']);
           } else {
                    echo textsuccess($lang['STR_ORDER_SUCCESSFULLY_CHECKED_IN_OR_OUT']);
           };
           unset($ordernumber);
     };
     if ($savedata||(!$gordernumber&&!$gponumber&&!$gcustomerid)) {
          echo '<form action="arorderckout.php" method="post" name="mainform"><table>';
          if ($usersupervisor) { //only let user pick user to checkout if they are a supervisor
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_OPERATOR'].':</td><td><select name="cuser"'.INC_TEXTBOX.'>';
              $recordSet = &$conn->Execute('select id, name from genuser order by name');
              while (!$recordSet->EOF) {
                   echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],$userid," selected").'>'.$recordSet->fields[1]."\n";
                   $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
          } else {
              echo '<input type="hidden" name="cuser" value="'.$userid.'">';
          };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ORDER_NUMBER'].':</td><td><input type="text" name="gordernumber" value="'.$gordernumber.'" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PURCHASE_ORDER'].':</td><td><input type="text" name="gponumber" value="'.$gponumber.'" size="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_NUMBER'].':</td><td><input type="text" name="gcustomerid" value="'.$gcustomerid.'" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupcustomer.php?name=gcustomerid\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Customer Lookup"></a></td></tr>';
          unset($savedata);
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
          if (!$recordSet->EOF) if ($recordSet->fields[0]>1) { //if there is more than one order matching criteria
               echo '<table border="1"><tr><th>'.$lang['STR_ORDER_NUMBER'].'</th><th>     '.$lang['STR_LAST_ACTION'].'     </th><th>'.$lang['STR_PO_NUMBER'].'</th><th>'.$lang['STR_ORDER_COMPANY'].'</th><th>'.$lang['STR_SHIP_TO_COMPANY'].'</th></tr>';
               $recordSet = &$conn->Execute("select arorder.ordernumber, arorder.ponumber, arorder.orderbycompanyid, ordercompany.companyname, arorder.shiptocompanyid, shiptocompany.companyname,arorder.id from arorder,customer, company as ordercompany, company as shiptocompany where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr." and arorder.companyid=".sqlprep($active_company)." order by arorder.entrydate desc");
               while (!$recordSet->EOF) {
                    $recordSet2=&$conn->Execute('select arordertrack.action,arordertrack.trackdate,genuser.name from arordertrack,genuser where genuser.id=arordertrack.trackuserid and arordertrack.trackuserid='.sqlprep($cuser).' and (arordertrack.action=0 or arordertrack.action=10) and arordertrack.orderid='.sqlprep($recordSet->fields[6]).' order by arordertrack.trackdate desc' );
                    echo '<tr><td><center><a href="arorderckout.php?ordernumber='.$recordSet->fields[0].'&&gordernumber='.$gorgernumber.'&&gponumber='.$gponumber.'&&gcustomerid='.$gcustomerid.'&&cuser='.$cuser.'">'.$recordSet->fields[0].'</a></center></td>';
                    if (!$recordSet2->EOF) {
                        if ($recordSet2->fields[0]==10) {
                              $action='<font color="red">'.$recordSet2->fields[2]." '.{$lang['STR_RETURNED']}.' -".$recordSet2->fields[1];
                        } else {
                               $action='<font color="green">'.$recordSet2->fields[2]." '.{$lang['STR_CHECKED_OUT']}.' -".$recordSet2->fields[1];
                        };
                        echo '<td><center>'.$action.'</center></td>';
                    } else {
                        echo '<td><center>------</center></td>';
                    };
                    echo '<td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[5].'<td>';
                    echo '</tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } else {
               $recordSet = &$conn->Execute("select arorder.ordernumber, arorder.ponumber, arorder.orderbycompanyid, ordercompany.companyname, arorder.shiptocompanyid, shiptocompany.companyname, arorder.id from arorder,customer, company as ordercompany, company as shiptocompany where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arorder.orderbycompanyid and shiptocompany.id=arorder.shiptocompanyid".$orderstr.$ponumberstr.$customeridstr." and arorder.status=0 and arorder.companyid=".sqlprep($active_company)." order by arorder.entrydate desc");
               if (!$recordSet->EOF) {
                    echo '<form action="arorderckout.php" method="post" name="mainform"><table border="1">';
                    echo '<tr><th colspan="2">'.$lang['STR_CHECK_OUT_IN_ORDER_NUMBER'].': '.$recordSet->fields[0].'<br> for '.rtrim($recordSet->fields[3]).'</th></tr>';
                    echo '<input type="hidden" name="ordernumber" value="'.$recordSet->fields[0].'">';
                    echo '<input type="hidden" name="orderid" value="'.$recordSet->fields[6].'">';
                    echo '<input type="hidden" name="cuser" value="'.$cuser.'">';
                    echo '<input type="hidden" name="gponumber" value="'.$gponumber.'">';
                    echo '<input type="hidden" name="gcustomerid" value="'.$gcustomerid.'">';
                    echo '<input type="hidden" name="gordernumber" value="'.$gordernumber.'">';
                    $recordSet2=&$conn->Execute('select action from arordertrack where trackuserid='.sqlprep($cuser).' and (action=0 or action=10) and orderid='.sqlprep($recordSet->fields[6]).' order by trackdate desc' );
                    if (!$recordSet2->EOF) {
                        $checked=$recordSet2->fields[0];
                    } else {
                        $checked=10;
                    };
                    if ($checked==10) {
                         echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHECK_OUT_ORDER'].'</td><td><input type="radio" checked name="action" value="0"></td></tr>';
                         echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_RETURN_FROM_CHECKOUT'].'</td><td><input type="radio" name="action" value="10"></td></tr>';
                    } else {
                         echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHECK_OUT_ORDER'].'</td><td><input type="radio" name="action" value="0"></td></tr>';
                         echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_RETURN_FROM_CHECKOUT'].'</td><td><input type="radio" checked name="action" value="10"></td></tr>';
                    };
                    echo '</td></tr></table>';
                    
                    if (!$recordSet->fields[21]) echo '<input type="submit" name="savedata" value="'.$lang['STR_SAVE'].'">';
               } else {
                    die(texterror($lang['STR_NO_MATCHING_ORDERS_FOUND']));
               };
          };

       };
     };
     
echo '</center>';
?>

<?php include_once("includes/footer.php"); ?>
