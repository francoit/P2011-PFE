<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //admininvglacct.php
     echo texttitle($lang['STR_INVENTORY_STANDARD_GL_ACCOUNTS']);
     echo '<center>';
     if ($cash&&$id) {
          checkpermissions('inv');
          $conn->Execute("update invcompany set cash=".sqlprep($cash).", sales=".sqlprep($sales).", loss=".sqlprep($loss).", cost=".sqlprep($cost).", freight=".sqlprep($freight).", tax=".sqlprep($tax).", custoritemglacct=".sqlprep($custoritemglacct)." where id=".sqlprep($active_company));
     };
     if ($cash&&!$id) {
          checkpermissions('inv');
          $conn->Execute("insert into invcompany (id,cash,sales,loss,cost,freight,tax,custoritemglacct) values (".sqlprep($active_company).", ".sqlprep($cash).", ".sqlprep($sales).", ".sqlprep($loss).", ".sqlprep($cost).", ".sqlprep($freight).", ".sqlprep($tax).",".sqlprep($custoritemglacct).")");
     };
     $recordSet=&$conn->SelectLimit('select cash,sales,loss,cost,freight,tax,custoritemglacct from invcompany where id='.sqlprep($active_company),1);
     if (!$recordSet->EOF) {
          $cash=$recordSet->fields[0];
          $sales=$recordSet->fields[1];
          $loss=$recordSet->fields[2];
          $cost=$recordSet->fields[3];
          $freight=$recordSet->fields[4];
          $tax=$recordSet->fields[5];
          $custoritemglacct=$recordSet->fields[6];
          $id=$active_company;
     };
     echo '<form action="admininvglacct.php" method="post"><table>';
     if ($id) echo '<input type="hidden" name="id" value="'.$id.'">';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CASH'].':</td><td><select name="cash"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=10 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($cash,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SALES'].':</td><td><select name="sales"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid>=50 and glaccount.accounttypeid<=60 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($sales,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVENTORY_LOSS'].':</td><td><select name="loss"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($loss,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COST_OF_GOODS'].':</td><td><select name="cost"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid=70 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($cost,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FREIGHT_EXPENSE'].':</td><td><select name="freight"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid>=70 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($freight,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_PAID_EXPENSE'].':</td><td><select name="tax"'.INC_TEXTBOX.'>';
     $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where glaccount.accounttypeid>=70 and (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
     while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'"'.checkequal($tax,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
          $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WHICH_GL_ACCT'].':</td>';
     if ($custoritemglacct==0) {
           echo '<td><input type="checkbox" name="custoritemglacct" value="1" '.INC_TEXTBOX.'></td></tr>';
     } else {
           echo '<td><input type="checkbox" name="custoritemglacct" checked value="1" '.INC_TEXTBOX.'></td></tr>';
     };

     echo '</table><br><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
     
     echo '</center>';

     ?>
<?php include('includes/footer.php'); ?>
