<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //arinvoiceaddcogs.php
     echo texttitle('Invoice Enter Cost of Goods');
     if ($invoicenumber||$customerid) {
         $titlestr='<font size="-1">';
         if ($customerid) {
             $recordSet = &$conn->Execute('select company.companyname from company,customer where company.id=customer.companyid and customer.id='.sqlprep($customerid));
             if ($recordSet&&!$recordSet->EOF) $titlestr.=$recordSet->fields[0];
         };
         if ($invoicenumber) {
             if ($customerid) $titlestr.=' - ';
             $titlestr.='Invoice #'.$invoicenumber;
         };
         $titlestr.='</font>';
         echo texttitle($titlestr);
     };
     if ($customerid&&$invoiceid) { //if the user has submitted initial info
          if ($cost1) {
              $recordSet = &$conn->Execute("delete from arinvoicedetailcost where invoiceid=".sqlprep($invoiceid));
              for ($i=1; ${"cost".$i}; $i++) {
                  $recordSet = &$conn->Execute("insert into arinvoicedetailcost (cost,costglaccountid,invoiceid,entrydate,entryuserid,lastchangeuserid) values (".sqlprep(${"cost".$i}).", ".sqlprep(${"costglaccountid".$i}).", ".sqlprep($invoiceid).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).")");
              }; //just increment i, nothing else
          };
          for ($i=1; ${"cost".$i}; $i++) {
          }; //just increment i, nothing else
          echo '<form action="arinvoiceaddcogs.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table border="1">';
          echo '<input type="hidden" name="invoiceid" value="'.$invoiceid.'">';
          echo '<input type="hidden" name="customerid" value="'.$customerid.'">';
          echo '<input type="hidden" name="invoicenumber" value="'.$invoicenumber.'">';

          echo '<tr><th>Cost</th><th>Cost GL Account</th></tr>';
          echo '<tr><td><input type="text" name="cost'.$i.'" onchange="validatenum(this)" size="10" maxlength="15"'.INC_TEXTBOX.'></td>';
          echo '<td><select name="costglaccountid'.$i.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute("select glaccount.id,glaccount.name, glaccount.description from glaccount where glaccount.accounttypeid='70' and (glaccount.companyid=0 or glaccount.companyid=".sqlprep($active_company).") order by glaccount.name");
          while ($recordSet&&!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          $cost=0;
          $i=1;
          $recordSet2 = &$conn->Execute("select cost,costglaccountid from arinvoicedetailcost where invoiceid=".sqlprep($invoiceid).' order by cost desc, costglaccountid');
          while ($recordSet2&&!$recordSet2->EOF) {
              echo '<tr><td><input type="text" name="cost'.$i.'" onchange="validatenum(this)" size="10" maxlength="15" value="'.num_format($recordSet2->fields[0],PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td>';
              echo '<td><select name="costglaccountid'.$i.'"'.INC_TEXTBOX.'>';
              $recordSet = &$conn->Execute("select glaccount.id,glaccount.name, glaccount.description from glaccount where glaccount.accounttypeid='70' and (glaccount.companyid=0 or glaccount.companyid=".sqlprep($active_company).") order by glaccount.name");
              while ($recordSet&&!$recordSet->EOF) {
                  echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet2->fields[1],$recordSet->fields[0],' selected').'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
                  $recordSet->MoveNext();
              };
              echo '</select></td></tr>';
              $cost+=$recordSet2->fields[0];
              $i++;
              $recordSet2->MoveNext();
          };
          echo '<tr><td><div align="right"><b>Total Cost:</b></div></td><td>'.CURRENCY_SYMBOL.checkdec($cost,PREFERRED_DECIMAL_PLACES).'</td></tr>';
          echo '</table><input type="submit" name="submit" value="Add Cost of Goods"></form>';
//          echo '<a href="arinvoiceview.php?printable=1&invoicenumber='.$invoicenumber.'">Print This Invoice</a><br>';
          echo '<a href="arinvoiceview.php?printable=1&post=1&invoicenumber='.$invoicenumber.'">Post/Print This Invoice</a><br>';

     } else {
        die(texterror('Customerid or invoiceid not passed.'));
     };
?>
<?php include('includes/footer.php'); ?>
