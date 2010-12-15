<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo texttitle($lang['STR_AR_STANDARD_GL_ACCOUNTS']);
     echo '<center>';
     if ($cash) {
         checkpermissions('ar');
             $recordSet=&$conn->Execute('select count(*) from arcompany where id='.sqlprep($active_company));
             if ($recordSet&&!$recordSet->EOF&&$recordSet->fields[0]) {
                  if ($conn->Execute("update arcompany set cash=".sqlprep($cash).", checking=".sqlprep($checking).", interest=".sqlprep($interest).", discount=".sqlprep($discount).", cost=".sqlprep($cost).", inventory=".sqlprep($inventory).", shipliability=".sqlprep($shipliability).", receivables=".sqlprep($receivables)." where id=".sqlprep($active_company)) === false) die(texterror($lang['STR_AR_COMPANY_UPDATE_FAILED']));
             } else {
                  if ($conn->Execute("insert into arcompany (id,cash,checking,interest,discount,cost,inventory,shipliability,receivables) values (".sqlprep($active_company).", ".sqlprep($cash).", ".sqlprep($checking).", ".sqlprep($interest).", ".sqlprep($discount).", ".sqlprep($cost).", ".sqlprep($inventory).", ".sqlprep($shipliability).", ".sqlprep($receivables).")") === false) die(texterror($lang['STR_AR_COMPANY_INSERT_FAILED']));
             };
             echo textsuccess($lang['STR_COMPANY_OPTIONS_CHANGED_SUCCESSFULLY']);
     };
     $recordSet=&$conn->Execute('select cash,checking,interest,discount,cost,inventory,shipliability,receivables from arcompany where id='.sqlprep($active_company));
     if ($recordSet&&!$recordSet->EOF) {
          $cash=$recordSet->fields[0];
          $checking=$recordSet->fields[1];
          $interest=$recordSet->fields[2];
          $discount=$recordSet->fields[3];
          $cost=$recordSet->fields[4];
          $inventory=$recordSet->fields[5];
          $shipliability=$recordSet->fields[6];
          $receivables=$recordSet->fields[7];
     };
     echo '<form action="adminarglacct.php" method="post"><table>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CASH'].': </td><td><select name="cash"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=10 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while ($recordSet&&!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($cash,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHECKING'].': </td><td><select name="checking"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=10 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while ($recordSet&&!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($checking,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INTEREST_INCOME'].': </td><td><select name="interest"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (glaccount.accounttypeid=50 or glaccount.accounttypeid=90) and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while ($recordSet&&!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($interest,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_GIVEN'].': </td><td><select name="discount"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (glaccount.accounttypeid=50 or glaccount.accounttypeid=60) and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while ($recordSet&&!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($discount,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COST_OF_GOODS_SOLD'].': </td><td><select name="cost"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=70 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while ($recordSet&&!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($cost,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVENTORY'].': </td><td><select name="inventory"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=10 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while ($recordSet&&!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($inventory,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHIPPING_LIABILITY'].': </td><td><select name="shipliability"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=21 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while ($recordSet&&!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($shipliability,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_RECEIVABLES'].': </td><td><select name="receivables"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=10 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while ($recordSet&&!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($receivables,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '</table><br><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
     
     echo '</center>';
?>

<?php include('includes/footer.php'); ?>
