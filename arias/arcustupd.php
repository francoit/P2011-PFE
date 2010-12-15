<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<script language="JavaScript" src="js/validatephone.js">
</script>
<?
        echo '<center>';
        if ($custcompanyid) { //if external customer
           $customerid=$custcompanyid; //only allow them to edit their info
        };
        if ($customerid) { //if user has selected a company
                if (!$shiptoid) {
                        $recordSet=&$conn->Execute('select company.companyname, company.id from company,customer where company.id=customer.companyid and customer.id='.sqlprep($customerid));
                        if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_CUSTOMER_NOT_FOUND']));
                        $selname=rtrim($recordSet->fields[0]);
                        $companyid=$recordSet->fields[1];
                        $id=$companyid;
                };
                if ($delete) {
                        echo texttitle($lang['STR_CUSTOMER_UPDATE']);
                        $conn->BeginTrans();
                        if (!arcompanydelete($companyid)) {
                            $conn->RollbackTrans();
                            die();
                        };
                        if (!arcustomerdelete($id)) {
                            $conn->RollbackTrans();
                            die();
                        };
                        if (!arshiptodelete($companyid)) {
                            $conn->RollbackTrans();
                            die();
                        };
                        $conn->CommitTrans();
                        echo textsuccess($lang['STR_CUSTOMER_DELETED_SUCCESSFULLY']);
                        unset($name);
                        unset($shipto);
                        unset($customerid);
                } elseif ($name) {  //update customer
                        echo texttitle($lang['STR_CUSTOMER_UPDATE']);
                        echo texttitle('for '.$selname);
                        $conn->BeginTrans();
                        if (!arcompanyupdate($companyid, $address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name,$mailstop,$lastchangedate)) {
                            $conn->RollbackTrans();
                            die();
                        };
                        if (!arcustomerupdate($customerid, $companyid, $taxexemptid, $creditlimit, $salesglacctid, $salesmanid, $servicerepid, $invoicetermsid, $quotecommentid, $interest, $billtoattnname, $quoteattnname, $chargecode, $salestaxnum,$lastchangecustomerdate)) {
                            $conn->RollbackTrans();
                            die();
                        };
                        for ($taxcounter=1;$taxcounter<=MAX_CUSTOMER_SALESTAX;$taxcounter++) {
                                if (${"taxid".$taxcounter}+${"taxrecid".$taxcounter}>0) { //only do something if either a tax rate chosen, or previous tax rate entered.
                                        if (!arcustomertaxadd(${"taxid".$taxcounter},$customerid,${"taxrecid".$taxcounter})) {
                                            $conn->RollbackTrans();
                                            die();
                                        };
                                };
                        };
                        $conn->CommitTrans();
                        echo textsuccess($lang['STR_CUSTOMER_UPDATED_SUCCESSFULLY']);
                        if (!$custcompanyid) unset($customerid);
                        unset($name);
                        unset($shipto);
                        unset($companyid);
                        unset($id);
                };
                if ($shipto) { //user picked to edit shipto's
                        echo texttitle($lang['STR_CUSTOMER_SHIP_TO_UPDATE_FOR']." ".$selname);
                        if ($shiptoselected) { //display shipto's for company user picked
                                if ($shiptoid) { //if ing a shipto location
                                        if ($shiptodelete) { //delete shipto
                                                $recordSet = &$conn->Execute('select shipto.companyid,shipto.shiptocompanyid from shipto where id='.sqlprep($shiptoid));
                                                if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_CUSTOMER_SHIP_TO_NOT_FOUND']));
                                                if ($recordSet->fields[0]!=$recordSet->fields[1]) {
                                                    $conn->BeginTrans();
                                                    if (!arcompanydelete($recordSet->fields[1])) {
                                                        $conn->RollbackTrans();
                                                        die();
                                                    };
                                                    if (!arshiptodelete($shiptoid)) {
                                                        $conn->RollbackTrans();
                                                        die();
                                                    };
                                                    $conn->CommitTrans();
                                                    echo textsuccess($lang['STR_CUSTOMER_SHIP_TO_DELETED_SUCCESSFULLY']);
                                                } else {
                                                    echo texterror($lang['STR_SHIP_TO_IS_MAIN_SHIP_TO_CAN_NOT_REMOVE']);
                                                };
                                                unset($shiptodelete);
                                                unset($shiptoid);
                                                unset($shipname);
                                                unset($shiptoselected);
                                        } elseif ($shipname) { //update shipto
                                                $conn->BeginTrans();
                                                if (!arcompanyupdate($companyid, $address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $shipname,$mailstop,$lastchangedate)) {
                                                    $conn->RollbackTrans();
                                                    die();
                                                };
                                                if (!arshiptoupdate($shiptoid, $companyid, $shiptocompanyid, $defaultshipvia,$lastchangeshipdate)) {
                                                    $conn->RollbackTrans();
                                                    die();
                                                };
                                                $conn->CommitTrans();
                                                echo textsuccess($lang['STR_CUSTOMER_SHIP_TO_UPDATED_SUCCESSFULLY']);
                                                unset($shiptoid);
                                                unset($shipname);
                                                unset($shiptoselected);

                                        } else {;
                                              //display shipto
                                              //if editing a shipto location
                                              echo '<form action="arcustupd.php" method="post" name="mainform">';
                                              echo '<input type="hidden" name="customerid" value="'.$customerid.'">';
                                              echo '<input type="hidden" name="nonprintable" value="1">';
                                              echo '<input type="hidden" name="selname" value="'.$selname.'">';
                                              echo '<input type="hidden" name="shiptoselected" value="1">';
                                              echo '<table>';
                                              formarshiptoupdate($shiptoid);
                                              echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> ';
                                              if (!$custcompanyid) echo '<a href="javascript:confirmdelete(\'arcustupd.php?shipto=1&shiptoselected=1&shiptodelete=1&customeridid='.$customeridid.'&companyid='.$companyid.'&shiptoid='.$shiptoid.'\')">'.$lang['STR_DELETE_THIS_SHIP_LOCATION'].'</a>';
                                        };
                                } else { //if adding a new shipto
                                        if ($shipname) { //add ship to location
                                             $conn->BeginTrans();
                                             if (!arcompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $shipname,$mailstop)) {
                                                 $conn->RollbackTrans();
                                                 die();
                                             };
                                             $recordSet = &$conn->SelectLimit('select id from company where companyname='.sqlprep($shipname).' order by id desc',1);
                                             if (!$recordSet||$recordSet->EOF) {
                                                 $conn->RollbackTrans();
                                                 die(texterror($lang['STR_CUSTOMER_SHIP_TO_NOT_FOUND']));
                                             };
                                             $id=$companyid;
                                             $companyid=$recordSet->fields[0];
                                             if (!arshiptoadd($id, $companyid, $defaultshipvia)) {
                                                 $conn->RollbackTrans();
                                                 die();
                                             };
                                             $conn->CommitTrans();
                                             echo textsuccess(CUSTOMER_SHIP_TO_ADDED_SUCCESSFULLY);
                                             unset($shipname);
                                             unset($shiptoid);
                                             unset($shiptoselected);
                                        } else { //display shipto entry form add new
                                             echo '<form action="arcustupd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
                                             echo '<input type="hidden" name="customerid" value="'.$customerid.'">';
                                             echo '<input type="hidden" name="shipto" value="1">';
                                             echo '<input type="hidden" name="selname" value="'.$selname.'">';
                                             echo '<input type="hidden" name="shiptoselected" value="1">';
                                             formarshiptoadd();
                                             echo '</table><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
                                        };
                                };
                        };
                        if (!$shiptoselected) { //user picked a shipto to edit
                                echo '<form action="arcustupd.php" method="post" name="mainform"><table>';
                                echo '<input type="hidden" name="shipto" value="1">';
                                echo '<input type="hidden" name="selname" value="'.$selname.'">';
                                echo '<input type="hidden" name="shiptoselected" value="1">';
                                echo '<input type="hidden" name="customerid" value="'.$customerid.'">';
                                formarshiptoselect('shiptoid', $customerid);
                                echo '</table><input type="submit" value="'.$lang['STR_SELECT'].'"></form>';
                        };
                } elseif (!$name&&$id) { //let user update customer
                        echo texttitle($lang['STR_CUSTOMER_UPDATE']);
                        echo '<form action="arcustupd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
                        echo '<input type="hidden" name="customerid" value="'.$customerid.'">';
                        if (!formarcustomerupdate($customerid)) die(texterror($lang['STR_CUSTOMER_NOT_FOUND']));
                        echo '<input type="hidden" name="selname" value="'.$selname.'">';
                        echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
                        if (!$custcompanyid) {
                            echo '<a href="javascript:confirmdelete(\'arcustupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_CUSTOMER'].'</a>';
                        } else {
                            echo '<a href="arcustupd.php?shipto=1&customerid='.$customerid.'">'.$lang['STR_UDPATE_SHIP_TO_ADDRESSES'].'</a>';
                        };
                };
        };
        if (!$customerid) { //let user pick customer
                echo texttitle($lang['STR_CUSTOMER_UPDATE']);
                echo '<form action="arcustupd.php" method="post" name="mainform"><table>';
                if (formarcustomerselect('customerid')) {
                        echo '</table><table border=0><tr><th colspan="2">'.$lang['STR_PART_OF_CUSTOMER_INFO_TO_EDIT'].':</th></tr><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GENERAL_INFO'].'</td><td><input type="radio" name="shipto" value="0" checked></td></tr>';
                        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHIP_TO_LOCATIONS'].'</td><td><input type="radio" name="shipto" value="1"></td></tr>';
                        echo '<br><center><input type="submit" value="'.$lang['STR_EDIT'].'"></center><br>';
                };
                echo '</table></form><br><a href="arcustadd.php">'.$lang['STR_ADD_NEW_CUSTOMER'].'</a>';
               
        };
echo '<center>';
?>

<?php include('includes/footer.php'); ?>
