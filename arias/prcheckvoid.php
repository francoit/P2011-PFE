<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>
<?php include('includes/glfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
        echo texttitle($companyname.' Check Void');
        echo texttitle('<font size="-1">'.$name.' - Check #'.$check.'</font>');
        if ($newchecknbr&&$checknbr) { // changing check number - no posting changes required
            checkpermissions('pay');
            $recordSet=&$conn->Execute('select chk.checkaccountid from chk where id='.sqlprep($checknbr));
            $recordSet=&$conn->Execute('select count(*) from chk where checknumber='.sqlprep($newchecknbr).' and checkaccountid='.sqlprep($recordSet->fields[0]));
            if ($recordSet->fields[0]==0) { //check if new check number exists
                if ($conn->Execute('update chk set checknumber='.sqlprep($newchecknbr).', lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($checknbr).' and wherefrom=6') === false) echo texterror('Check update failed.');
                $recordSet=&$conn->Execute('select checkacct.id, checkacct.lastchecknumberused from checkacct,chk where checkacct.id=chk.checkaccountid and chk.id='.sqlprep($checknbr));
                if (!$recordSet->EOF) {
                    $checkacctid=$recordSet->fields[0];
                    $lastchecknbr=$recordSet->fields[1];
                };
                if ($newchecknbr>$lastchecknbr) $conn->Execute('update checkacct set lastchecknumberused='.sqlprep($newchecknbr).' where id='.sqlprep($checkacctid));
                echo textsuccess('Check #'.$check.' changed to '.$newchecknbr.' successfully.');
            } else {
                echo texterror('Check #'.$newchecknbr.' already exists.  Not updated.');
            };
            echo '<br><a href="javascript:window.close();">Close this window</a>';
        } elseif ($delete&&$checknbr) { //voiding check
            checkpermissions('pay');
            if ($conn->Execute('update chk set checkvoid=1, lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($checknbr).' and wherefrom=6') === false) echo texterror('Check update failed.');
            //---------------------------------------------------------------
            //remove check number and calculate status flag from premplweek
            //-----------------------------------------------------------------
            if ($conn->Execute('update premplweek set checkid=0, calculatestatus=0, lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($emplweekid)) === false) echo texterror('premplweek1 update failed.');
            //increase decrementing deduction counters
            $recordSet2=&$conn->Execute('select prempldeduction.id from prempldeduction,premplweek,premplweekdeddetail where premplweekdeddetail.premplweekid=premplweek.id and premplweekdeddetail.prempldeductionid>0 and premplweek.employeeid=prempldeduction.employeeid and premplweekdeddetail.prempldeductionid=prempldeduction.id and premplweek.id='.sqlprep($emplweekid).' and prempldeduction.periodsremain>=0');
            while (!$recordSet2->EOF) {
                     $conn->Execute('update prempldeduction set prempldeduction.periodsremain=prempldeduction.periodsremain+1 where id='.sqlprep($recordSet2->fields[0]));
                     $recordSet2->MoveNext();
            };
            //increase vacation/sick leave accruals??
            $recordSet2=&$conn->Execute('select vacaccrue,sickaccrue,employeeid from premplweek where id='.sqlprep($emplweekid));
            if (!$recordSet2->EOF) {
                  $vac=$recordSet2->fields[0];
                  $sick=$recordSet2->fields[1];
                  if ($conn->Execute('update premployee set vacationhoursaccrued=vacationhoursaccrued-'.sqlprep($vac).',sickleavehoursaccrued=sickleavehoursaccrued-'.sqlprep($sick).' where id='.sqlprep($recordSet2->fields[2])) === false) echo texterror('premployee vacation/sick leave update failed.');
                  if ($conn->Execute('update premplweek set vacaccrue=0,sickaccrue=0  where id='.sqlprep($emplweekid)) === false) echo texterror('premployeeweek vacation/sick leave update failed.');
            };

            //zero out deductions and company benefits
            $recordSet2=&$conn->Execute('delete from premplweekdeddetail where premplweekdeddetail.premplweekid='.sqlprep($emplweekid));
            if ($delete==1) { //only remove calculated totals, leave pay, hours and standard deductions
                 if ($conn->Execute('update premplweek set federaltax=0, statetax=0, citytax=0,localtax=0,ficatax=0,eiccredit=0,medicarededuction=0,fuitax=0,cficatax=0,cmedicarededuction=0,suitax=0, lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($emplweekid)) === false) echo texterror('premplweek2 update failed.');
                 $recordSet2=&$conn->Execute('delete from premplweekpaydetail where premplweekpaydetail.premplweekid='.sqlprep($emplweekid).' and premplweekpaydetail.prbendedid>0');
            } else { //cancel premplweek entirely
                 $recordSet2=&$conn->Execute('delete from premplweekpaydetail where premplweekpaydetail.premplweekid='.sqlprep($emplweekid));
                 if ($conn->Execute('update premplweek set cancel=1, canceluserid='.sqlprep($userid).', canceldate=NOW(), lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($emplweekid)) === false) echo texterror('premplweek3 update failed.');
            };



            //-----------------------------------------------------------------
            // gl posting reversals, etc. here
            //first check to see if voucher was posted. If not, simply cancel
            //If it has been posted, then create opposite postings for every
            //entry for this check.
            //--------------------------------------------------------

            $recordSet=&$conn->Execute('select id,status,posteddate,voucher,description from gltransvoucher where wherefrom=6 and cancel=0 and voucher='.sqlprep($checknbr));
            if (!$recordSet->EOF) {
                  if ($recordSet->fields[1]==1) {//it has been posted
                       //create a new voucher to hold the reverse transactions
                       $timestamp =  time();
                       $date_time_array =  getdate($timestamp);
                       $hours =  $date_time_array["hours"];
                       $minutes =  $date_time_array["minutes"];
                       $seconds =  $date_time_array["seconds"];
                       $month =  $date_time_array["mon"];
                       $day =  $date_time_array["mday"];
                       $year =  $date_time_array["year"];
                       $timestamp =  mktime($hour, $minute, $second, $month, $day, $year);
                       $today=date("Y-m-d", $timestamp);

                       $oldid=$recordSet->fields[0];
                       $newid=gltransvoucheradd($checknbr,$recordSet->fields[4],$today,6);
                       if ($newid) { // it was created
                            $recordSet2=&$conn->Execute('select glaccountid,amount from gltransaction where voucherid='.sqlprep($oldid));
                            while (!$recordSet2->EOF) {
                                $result=gltransactionadd($newid,-($recordSet2->fields[1]),$recordSet2->fields[0]);
                                $recordSet2->MoveNext();
                            };
                       };
                  } else { //not yet posted
                       $result=gltransvoucherdelete($recordSet->fields[0]);
                  };
            };
            // now check to see if calculations should also be removed

            echo textsuccess('Check #'.$check.' voided successfully.');
            echo '<br><a href="javascript:window.close()">Close this window</a>';
        } else if ($checknbr) {
            echo '<form method="post" name="mainform" action="prcheckvoid.php"><input type="hidden" name="checknbr" value="'.$checknbr.'"><input type="hidden" name="name" value="'.$name.'"><input type="hidden" name="check" value="'.$check.'">';
            echo '<a href="javascript:confirmdelete(\'prcheckvoid.php?checknbr='.$checknbr.'&delete=1&check='.$check.'&name='.$name.'&emplweekid='.$emplweekid.'\')">Void Check and Calculations</a><br>';
            echo '<a href="javascript:confirmdelete(\'prcheckvoid.php?checknbr='.$checknbr.'&delete=2&check='.$check.'&name='.$name.'&emplweekid='.$emplweekid.'\')">Void Check, Calculations AND all Pay Data</a><br>';
            echo '<br>Or Reprint as Check # <input type="text" name="newchecknbr"'.INC_TEXTBOX.'"> <input type="submit" value="Reprint"></form>';
            echo 'Or <a href="javascript:window.close();">Close Window</a><br>';
        } else {
            echo texterror('Sorry, the previous page did not pass the check number you wish to edit.  Please return and try again.');
        };
?>
<?php include('includes/footer.php'); ?>
