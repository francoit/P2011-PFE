<?php require_once('includes/main.php'); ?>
<?php include('includes/glfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
      var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?php   //gljourupd.php
      echo texttitle($lang['STR_GL_JOURNAL_UPDATE']);
      echo '<center>';
        if ($search) {
                if ($voucher) $voucherstr = ' and voucher='.sqlprep($voucher);
                if ($from) $fromstr = " and wherefrom=".sqlprep($from);
                $recordSet = &$conn->Execute("select id,voucher,description,entrydate from gltransvoucher where companyid=".sqlprep($active_company).$voucherstr.$fromstr." and cancel='0' and status='0' and entrydate>=".sqlprep($begindate)." and entrydate<=".sqlprep($todate." 23:59:59")." order by voucher");
                if ($recordSet&&!$recordSet->EOF) {
                     echo '<form action="gljourupd.php" method="post"><input type="hidden" name="selectv" value="1"><table><tr><td><select name="vid" size="15">';
                } else {
                     die(texterror($lang['STR_NO_UNPOSTED_MATCHES_FOUND']));
                };
                while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."  -  ".$recordSet->fields[2]."    ".substr($recordSet->fields[3],0,10)."  \n";
                        $recordSet->MoveNext();
                };
                echo '</select></td></tr></table><input type="submit" value="'.$lang['STR_SELECT_VOUCHER_TO_UPDATE'].'"></form>';
      } elseif ($selectv) {
            if (!$vid) die(texterror($lang['STR_NO_VOUCHERS_SELECTED']));
            $recordSet = &$conn->Execute('select id, voucher,description,entrydate,lastchangedate from gltransvoucher where id='.sqlprep($vid));
            if (!$recordSet->EOF) {
                  $voucherid=$recordSet->fields[0];
                  $vouchername=$recordSet->fields[1];
                  $voucherdescription=$recordSet->fields[2];
                  $voucherentrydate=substr($recordSet->fields[3],0,10);
                  $lastchangedate=$recordSet->fields[4];
            };
            echo '<form action="gljourupd.php" method="post"><table><tr><td colspan="2"><center><b>'.$lang['STR_UPDATE_GENERAL_VOUCHER_INFORMATION'].'</b></center></td></tr><tr><td>Journal Voucher:</td><td><input type="text" name="vouchername" size=20 value="'.$vouchername.'"></td></tr><tr><td>Voucher Description:</td><td><input type="text" name="voucherdescription" size="50" maxlength="30" value="'.$voucherdescription.'"></td></tr><tr><td>Transaction Date:</td></td><td><input type="text" name="voucherentrydate" onchange="formatDate(this)" value="'.$voucherentrydate.'" size="30"></td></tr><input type="hidden" name="updatev" value="1"><input type="hidden" name="vid" value="'.$vid.'"><input type="hidden" name="voucherid" value="'.$voucherid.'">';
            echo '<input type="hidden" name="lastchangedate" value="'.$lastchangedate.'">';
            echo '</table><br><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form><br><a href="javascript:confirmdelete(\'gljourupd.php?delete=1&vid='.$vid.'\')">Delete this voucher</a>';
      } elseif ($updatev) { //read in array of detail items to be edited
            $recordSet = &$conn->Execute("select gltransaction.id,gltransaction.glaccountid,gltransaction.amount,glaccount.description,glaccount.name from gltransaction,glaccount where gltransaction.voucherid=".sqlprep($vid)." and glaccount.id=gltransaction.glaccountid");
            for ($cntr=1;!$recordSet->EOF;$cntr++) {
                  ${"gltrid".$cntr}=$recordSet->fields[0]; //gltransaction.id
                  ${"gltracct".$cntr}=$recordSet->fields[1]; //gltransaction.glaccountid
                  ${"gltramt".$cntr}=$recordSet->fields[2]; //gltransaction.amount
                  ${"gltracctname".$cntr}=$recordSet->fields[4]." - ".$recordSet->fields[3];
                  $recordSet->MoveNext();
            };
            echo '<form action="gljourupd.php" method="post" name="mainform">';
            echo '<input type="hidden" name="lastchangedate" value="'.$lastchangedate.'">';
            echo '<table border="1"><tr><th>'.$lang['STR_VOUCHER'].': '.$vouchername.'</th><th>'.$voucherdescription.'</th></tr><tr><th>'.$lang['STR_ACCOUNT'].' - '.$lang['STR_DESCRIPTION'].'</th><th>'.$lang['STR_AMOUNT'].'</th></tr><input type="hidden" name="updatet" value="1"><input type="hidden" name="voucherid" value="'.$voucherid.'"><input type="hidden" name="vid" value="'.$vid.'"><input type="hidden" name="voucherdescription" value="'.$voucherdescription.'"><input type="hidden" name="voucherentrydate" value="'.$voucherentrydate.'"><input type="hidden" name="vouchername" value="'.$vouchername.'">';
            for ($pos=1;$pos<$cntr+2;$pos++) {
                  echo'<tr><td><select name="gltracct'.$pos.'">';
                  if ($pos>=$cntr) echo '<option value="0">'; //for last select box, allow user pick or not pick a glaccount
                  $recordSet = &$conn->Execute('select id, name,description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by name');
                  while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'"'.checkequal(${"gltracct".$pos},$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
                        $recordSet->MoveNext();
                  };
                  echo '</select></td><td><input type="hidden" name="gltrid'.$pos.'" value="'.${"gltrid".$pos}.'"><input type="text" name="gltramt'.$pos.'" onchange="validatenum(this)" value="'.checkdec(${"gltramt".$pos},PREFERRED_DECIMAL_PLACES).'">';
                  echo '</td></tr>';
            };
            echo '</table><input type="hidden" name="cntr" value="'.$cntr.'">';
            echo '<input type="submit" onClick="CheckBal()" value="'.$lang['STR_SAVE_CHANGES'].'">';
            echo '<input type="reset" value="'.$lang['STR_RESET_VALUES'].'">';
            echo '<script language="JavaScript">'."\n";
            echo '      function CheckBal() {'."\n";
            echo '            var numb = "0"'."\n";
            for ($i=1; $i<$cntr+2; $i++) echo '            numb = eval(numb) + parseInt(eval(document.mainform.gltramt'.$i.'.value) * 100)'."\n";
            echo '            if (numb) {'."\n";
            echo '                  alert("Form is not balanced.  Form is currently "+numb/100+" from being in balance.")'."\n";
            echo '            } else {'."\n";
            echo '                  mainform.submit()'."\n";
            echo '            }'."\n";
            echo '            return numb'."\n";
            echo '      }'."\n";
            echo '</script>'."\n";
      } elseif ($updatet) {
            for ($pos=1;$pos<$cntr+2;$pos++) $amount+=intval(${"gltramt".$pos}*100); //test for balance
            if (checkzero($amount)) {
                 checkpermissions('gl');
                 $recordSet=&$conn->Execute("select count(*) from gltransvoucher where id=".sqlprep($vid)." and lastchangedate=".sqlprep($lastchangedate));
                 if (!$recordSet->EOF) {
                       if ($recordSet->fields[0]==0) {
                            showwhochanged($vid,"gltransvoucher","id");
                       } else {
                              $conn->Execute("update gltransvoucher set voucher=".sqlprep($vouchername).", description=".sqlprep($voucherdescription).", entrydate=".sqlprep($voucherentrydate)." where id=".sqlprep($vid));
                              for ($pos=1;$pos<=($cntr-1);$pos++) $conn->Execute('update gltransaction set amount='.sqlprep(${"gltramt".$pos}).', glaccountid='.sqlprep(${"gltracct".$pos}).' where id='.sqlprep(${"gltrid".$pos}));
                              if (${"gltramt".$cntr}) $conn->Execute("insert into gltransaction (glaccountid, amount, voucherid) values (".sqlprep(${"gltracct".$cntr}).", ".sqlprep(${"gltramt".$cntr}).", ".sqlprep($vid).")");
                              $cntr++;
                              if (${"gltramt".$cntr}) $conn->Execute("insert into gltransaction (glaccountid, amount, voucherid) values (".sqlprep(${"gltracct".$cntr}).", ".sqlprep(${"gltramt".$cntr}).", ".sqlprep($vid).")");
                              echo textsuccess($lang['STR_VOUCHER_UPDATED_SUCCESSFULLY']);
                       };
                 };
            } else {
                   echo texterror($lang['STR_VOUCHER_DID_NOT_BALANCE_VOUCHER_NOT_UPDATED']);
            };
      } elseif ($delete) {
            checkpermissions('gl');
            $conn->Execute("delete from gltransvoucher where id=".sqlprep($vid));
            $conn->Execute('delete from gltransaction where voucherid='.sqlprep($vid));
            echo textsuccess($lang['STR_VOUCHER_DELETED_SUCCESSFULLY']);
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
                $today=date("Y-m-d", $timestamp);
                $timestamp =  mktime($hour, $minute, $second, $month-1, $day, $year);
                $lastmonth=date("Y-m-d", $timestamp);
                echo '<form action="gljourupd.php" method="post" name="mainform">';
                echo '<input type="hidden" name="search" value="1">';
                echo texttitle($lang['STR_FILL_IN_FIELDS_FOR_SEARCH_CAN_BE_LEFT_BLANK']);
                echo '<table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VOUCHER'].':</td><td><input type = "text" name="voucher" size="20"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ENTRY_FROM'].':</td><td><select name="from"'.INC_TEXTBOX.'><option value="0">'.$lang['STR_ANY_SOURCE'].'<option value="1">'.$lang['STR_ACCOUNTS_PAYABLE'].'<option value="2">'.$lang['STR_ACCOUNTS_RECEIVABLE'].'<option value="3">'.$lang['STR_GENERAL_LEDGER'].'<option value="4">'.$lang['STR_INVENTORY'].'<option value="5">'.$lang['STR_FIXED_ASSETS'].'<option value="6">'.$lang['STR_PAYROLL'].'</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FROM_DATE'].': </td><td><input type="text" name="begindate" onchange="formatDate(this)" size="10" value="'.$lastmonth.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.begindate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TO_DATE'].': </td><td><input type="text" name="todate" onchange="formatDate(this)" size="10" value="'.$today.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.todate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Journal Update"></a></td></tr>';
                echo '</table><input type="submit" value="'.$lang['STR_SEARCH'].'"></form>';
              
                echo '</center>';
        };
?>

<?php include('includes/footer.php'); ?>
