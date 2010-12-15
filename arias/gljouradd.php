<?php require_once('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
     var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?
     echo texttitle($lang['STR_GL_JOURNAL_ADD']);
     echo '<center>';
     if (!$userid || $userid=="") die(texterror($lang['STR_USER_ID_NOT_FOUND'])); // to make SURE we're not dropping gltrans[voucher|action]
        if ($voucher) {
                checkpermissions('gl');
                if ($insertv) {
                        $voucher=str_replace(" ","",$voucher);
                        if ($conn->Execute("insert into gltransvoucher".$userid." (voucher,description,wherefrom,companyid,entrydate,posteddate,post2date,lastchangeuserid,entryuserid) VALUES (".sqlprep($voucher).", ".sqlprep($description).", ".sqlprep(moduleidfromnameshort('gl')).", ".sqlprep($active_company).", ".sqlprep($entrydate).", '0000-00-00 00:00:00', '0000-00-00', ".sqlprep($userid).", ".sqlprep($userid).")") === false) echo texterror($lang['STR_ERROR_INSERTING_VOUCHER_INFO']);
                } elseif ($insertt) {
                        $recordSet = &$conn->SelectLimit("select id from gltransvoucher".$userid." where voucher=".sqlprep($voucher)." order by lastchangedate desc",1);
                        if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_ERROR_RETRIEVING_VOUCHER_INFO']));
                        $voucherid=$recordSet->fields[0];
                        if ($conn->Execute("insert into gltransaction".$userid." (glaccountid, voucherid, amount) VALUES (".sqlprep($glaccountid).", ".sqlprep($voucherid).", ".sqlprep($amount).")") === false) echo texterror("Error adding voucher detail.");
                } elseif ($delete) {
                        $recordSet =&$conn->SelectLimit("select id from gltransvoucher".$userid." where voucher=".sqlprep($voucher)." order by lastchangedate desc",1);
                        if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_ERROR_RETRIEVING_VOUCHER_INF']));
                        $voucherid=$recordSet->fields[0];
                        if ($conn->Execute("delete from gltransaction".$userid." where glaccountid='".$glaccountid."' and voucherid='".$voucherid."' and amount='".$amount."'") === false) echo texterror("Error deleting voucher detail.");
                } elseif ($complete) {
                        $conn->BeginTrans();
                        if ($conn->Execute('insert into gltransvoucher (voucher,description,wherefrom,companyid,entrydate,posteddate,post2date,lastchangeuserid,entryuserid) select voucher,description,wherefrom,companyid,entrydate,posteddate,post2date,lastchangeuserid,entryuserid from gltransvoucher'.$userid.' where voucher='.sqlprep($voucher)) === false) {
                                $conn->RollbackTrans();
                                die(texterror($lang['STR_ERROR_ADDING_VOUCHER_TO_MAIN_DATABASE']));
                        };
                        $recordSet = &$conn->SelectLimit('select id from gltransvoucher where voucher='.sqlprep($voucher).' order by lastchangedate desc',1);
                        if (!$recordSet||$recordSet->EOF) {
                                $conn->RollbackTrans();
                                die(texterror($lang['STR_ERROR_RETRIEVING_VOUCHER_INFO_FROM_MAIN_DATABASE']));
                        };
                        $voucherid=$recordSet->fields[0];
                        if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) select glaccountid,'.sqlprep($voucherid).',amount from gltransaction'.$userid) === false) {
                                $conn->RollbackTrans();
                                die(texterror($lang['STR_ERROR_ADDING_VOUCHER_DETAIL_TO_MAIN_DATABASE']));
                        };
                        $conn->CommitTrans();
                        if ($conn->Execute('drop table gltransaction'.$userid) === false) echo texterror($lang['STR_TRANSACTION_TEMPORARY_TABLE_DROP_FAILED']);
                        if ($conn->Execute('drop table gltransvoucher'.$userid) === false) echo texterror($lang['STR_VOUCHER_TEMPORARY_TABLE_DROP_FAILED']);
                        die (textsuccess($lang['STR_VOUCHER_ADDED_SUCCESSFULLY']));
                };
                $recordSet = &$conn->SelectLimit("select voucher, description, entrydate, id from gltransvoucher".$userid." where voucher=".sqlprep($voucher),1);
                if ($recordSet&&!$recordSet->EOF) {
                        echo '<b>Voucher: '.$recordSet->fields[0].'</b><br>';
                        echo '<b>For: '.$recordSet->fields[1].'</b><br>';
                        echo '<b>Dated: '.substr($recordSet->fields[2],0,10).'</b><br>';
                        $voucherid=$recordSet->fields[3];
                };
                $recordSet = &$conn->Execute('select sum(amount) from gltransaction'.$userid.' where voucherid='.sqlprep($voucherid));
                $sumexists=0;
                if ($recordSet&&!$recordSet->EOF) {
                        if (is_numeric($recordSet->fields[0])) {
                            $sum=$recordSet->fields[0];
                            if ($sum<0) {
                                $sum=abs($sum);
                            } else {
                                $sum=0-$sum;
                            };
                            $sumexists=1;
                        };
                };
                echo '<form action="gljouradd.php" method="post"><table><input type="hidden" name="insertt" value="1"><input type="hidden" name="voucher" value="'.$voucher.'"><tr><td>'.$lang['STR_GENERAL_LEDGER_ACCOUNT'].':</td><td><select name="glaccountid"'.INC_TEXTBOX.'>';
                $recordSet = &$conn->Execute('select id, name,description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by name');
                echo '<option value="0">';
                while ($recordSet&&!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
                        $recordSet->MoveNext();
                };
                echo '</select></td></tr><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AMOUNT'].': </td><td><input type="text" name="amount" onchange="validatenum(this)" size="20" maxlength="20" value="'.checkdec($sum,PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td></tr>';
                echo '</table><br><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
                if (!$insertv) {
                       if ($sumexists) {
                                if (checkzero($sum)) {
                                        echo '<a href="gljouradd.php?voucher='.$voucher.'&complete=1">Voucher '.$voucher.' Complete</a>';
                                } else {
                                        echo 'Voucher not in balance (Sum='.$sum.")";
                                };
                        };
                        $recordSet=&$conn->Execute('select glaccount.name,glaccount.description,gltransaction'.$userid.'.amount,gltransaction'.$userid.'.voucherid,gltransaction'.$userid.'.glaccountid from gltransaction'.$userid.', glaccount where gltransaction'.$userid.'.glaccountid=glaccount.id');
                        if ($recordSet&&!$recordSet->EOF) echo '<br><br><table border="1"><tr><th>'.$lang['STR_ACCOUNT'].'</th><th>'.$lang['STR_ACCOUNT_DESCRIPTION'].'</th><th>'.$lang['STR_AMOUNT'].'</th><th>'.$lang['STR_OPTIONS'].'</th></tr>';
                        while ($recordSet&&!$recordSet->EOF) {
                                echo '<tr><td>'.$recordSet->fields[0].'</td><td>'.$recordSet->fields[1].'</td><td>'.checkdec($recordSet->fields[2],PREFERRED_DECIMAL_PLACES).'</td><td><a href="gljouradd.php?voucher='.$voucher.'&glaccountid='.$recordSet->fields[4].'&voucherid='.$recordSet->fields[3].'&amount='.checkdec($recordSet->fields[2],PREFERRED_DECIMAL_PLACES).'&delete=1">'.$lang['STR_DELETE'].'</a></td></tr>';
                                $recordSet->MoveNext();
                        };
                        echo '</table>';
                };
        } else {
                if ($conn->SelectLimit('select * from gltransvoucher'.$userid.' where 1 != 1',1) !== false && $conn->Execute('drop table gltransvoucher'.$userid) === false) echo "<b><center>'.{$lang['STR_ERROR_DROPPING_TABLE_VOUCHER']}.'</center></b><br>\n";
                if ($conn->Execute('create table gltransvoucher'.$userid.' as select * from gltransvoucher where 1 != 1') === false) echo "<b><center>'.{$lang['STR_ERROR_CREATING_TABLE_VOUCHER_AFTER_DROP']}.'</center></b><br>\n";
                if ($conn->SelectLimit('select * from gltransaction'.$userid.' where 1 != 1',1) !== false && $conn->Execute('drop table gltransaction'.$userid) === false) echo "<b><center>'.{$lang['STR_ERROR_DROPPING_TABLE_GLTRANSACTION']}.'</center></b><br>\n";
                if ($conn->Execute('create table gltransaction'.$userid.' as select * from gltransaction where 1 != 1') === false) echo "<b><center>'.{$lang['STR_ERROR_CREATING_TABLE_GLTRANSACTION_AFTER_DROP']}.'</center></b><br>\n";
                $bgdate=createtime("Y-m-d");
                $recordSet = &$conn->Execute('select max(voucher) from gltransvoucher');
                if ($recordSet&&!$recordSet->EOF&&is_numeric($recordSet->fields[0])) $voucher=$recordSet->fields[0]+$userid;
                echo '<form action="gljouradd.php" method="post" name="mainform"><input type="hidden" name="insertv" value="1">';
                echo '<table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_JOURNAL_VOUCHER'].':</td><td><input type="text" name="voucher" size="30" maxlength="20" value="'.$voucher.'"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VOUCHER_DESCRIPTION'].':</td><td><input type="text" name="description" size="50" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TRANSACTION_DATE'].':</td><td><input type="text" name="entrydate"  onchange="formatDate(this)" value="'.$bgdate.'" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.entrydate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr></table>';
                echo '<br><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
        };
                
                echo '</center>';
?>

<?php include('includes/footer.php'); ?>
