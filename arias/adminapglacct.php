<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php include('includes/main.php'); ?>


<?php //adminapglacct.php
     echo '<center>';
     echo texttitle($lang['STR_AP_STANDARD_GL_ACCOUNTS']);
     if ($payable) {
         checkpermissions('ap');
	     $recordSet=&$conn->Execute('select count(*) from apcompany where id='.sqlprep($active_company));
	     if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]) {
	          if ($conn->Execute("update apcompany set payable=".sqlprep($payable).", interestexpense=".sqlprep($interestexpense).", discount=".sqlprep($discount).", discearn=".sqlprep($discearn).", usetransactiondate=".sqlprep($usetransactiondate)." where id=".sqlprep($active_company)) === false) die(texterror('Apcompany update failed'));
         } else {
	          if ($conn->Execute("insert into apcompany (id,payable,interestexpense,discount,discearn,usetransactiondate) values (".sqlprep($active_company).", ".sqlprep($payable).", ".sqlprep($interestexpense).", ".sqlprep($discount).", ".sqlprep($discearn).", ".sqlprep($usetransactiondate).")") === false) die(texterror('Apcompany insert failed'));
	     };
     };
     $recordSet=&$conn->Execute('select payable,interestexpense,discount,discearn,usetransactiondate from apcompany where id='.sqlprep($active_company));
     if ($recordSet&&!$recordSet->EOF) {
          $payable=$recordSet->fields[0];
          $interestexpense=$recordSet->fields[1];
          $discount=$recordSet->fields[2];
          $discearn=$recordSet->fields[3];
          $usetransactiondate=$recordSet->fields[4];
     };
     echo '<form action="adminapglacct.php" method="post"><table>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAYABLES'].':</td><td><select name="payable"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=21 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while ($recordSet&&!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($payable,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INTEREST_EXPENSE'].':</td><td><select name="interestexpense"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid>=70 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while ($recordSet&&!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($interestexpense,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_EARNED'].':</td><td><input type="radio" name="discearn" value="1"'.checkequal($discearn,1," checked").INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_LOST'].':</td><td><input type="radio" name="discearn" value="0"'.checkequal($discearn,0," checked").INC_TEXTBOX.'></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_ACCOUNT'].':</td><td><select name="discount"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid>=70 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while ($recordSet&&!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($discount,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_USE_TRANSACTION_DATE'].':</td><td><input type="checkbox" name="usetransactiondate" value="1"'.checkequal($usetransactiondate,1," checked").INC_TEXTBOX.'>';
     echo '</table><br><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
     echo '</center>';
?>

<?php include('includes/footer.php'); ?>
