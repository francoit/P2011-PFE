<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php //arinvoiceview.php
     $recordSet = &$conn->Execute("select gencompany.name,gencompany.address1,gencompany.address2,gencompany.city,gencompany.state,gencompany.zip,gencompany.country,gencompany.phone1,gencompany.web,gencompany.email,arcompany.imageurl from gencompany left join arcompany on arcompany.id=gencompany.id where gencompany.id=".sqlprep($active_company));
     echo '<table width="100%"><tr><tr><td align="left">';
     if ($recordSet&&!$recordSet->EOF) {
         $origzip=$recordSet->fields[5];
         echo '<font size="+2">'.$recordSet->fields[0].'</font></td><td valign="top" align="left">'.$recordSet->fields[1]."<br>";
         if ($recordSet->fields[2]) echo $recordSet->fields[2]."<br>";
         echo $recordSet->fields[3].", ".$recordSet->fields[4]." ".$recordSet->fields[5]."<br>".$recordSet->fields[6].'<br><font size="-2">'.$recordSet->fields[7]."<br>".$recordSet->fields[8]."<br>".$recordSet->fields[9]."</font>";
     };
     echo '</td><td align="right" valign="top">';
     if ($recordSet->fields[10]) echo '<img src="'.$recordSet->fields[10].'">';
     echo '</td></tr></table><table width="100%"><tr><td>'.texttitle('Invoice # '.$invoicenumber).'</td></tr></table>';

     if (!$invoicenumber) die(texterror('Invoice number not passed.'));
     if ($post) { //post invoice
          $recordSet = &$conn->SelectLimit('select id,shipcost,invoicetotal from arinvoice where invoicenumber='.sqlprep($invoicenumber).' and cancel=0 and gencompanyid='.sqlprep($active_company).' and status<1 order by invoicedate desc',1);
          if (!$recordSet||$recordSet->EOF) die(texterror('Invoice #'.$invoicenumber.' not found.  Could not post.'));
          $invoiceid=$recordSet->fields[0];
          $shipcost=$recordSet->fields[1];
          $invoicetotal=$recordSet->fields[2];
          checkpermissions('ar');
          $conn->BeginTrans();
          $recordSet = &$conn->Execute('select cost,inventory,checking,receivables,shipliability from arcompany where id='.sqlprep($active_company));
          if (!$recordSet||$recordSet->EOF) {
               $conn->RollbackTrans();
               die(texterror("Error retrieving gl accounts from arcompany."));
          } else {
               $costgl=$recordSet->fields[0];
               $invgl=$recordSet->fields[1];
               $checkgl=$recordSet->fields[2];
               $argl=$recordSet->fields[3];
               $shipgl=$recordSet->fields[4];
          };
          if ($conn->Execute('insert into gltransvoucher (voucher,description,wherefrom,companyid,entrydate,lastchangeuserid,entryuserid) values ('.sqlprep('invoice'.$invoicenumber).', '.sqlprep('AR Invoice').','.sqlprep(moduleidfromnameshort('ar')).', '.sqlprep($active_company).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).')') === false) {
               $conn->RollbackTrans();
               die(texterror("Error adding voucher to main database."));
          };
          $recordSet = &$conn->SelectLimit('select id from gltransvoucher where voucher='.sqlprep('invoice'.$invoicenumber).' and companyid='.sqlprep($active_company).' order by lastchangedate desc',1);
          if (!$recordSet||$recordSet->EOF) {
               $conn->RollbackTrans();
               die(texterror("Error retrieving voucher info from main database."));
          };
          $voucherid=$recordSet->fields[0];
          //cost of goods
          $recordSet = &$conn->Execute('select costglaccountid,cost from arinvoicedetailcost where invoiceid='.sqlprep($invoiceid));
          while (!$recordSet->EOF) {
              $cost+=$recordSet->fields[1];
              if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($recordSet->fields[0]).', '.sqlprep($voucherid).', '.sqlprep($recordSet->fields[1]).')') === false) {
                   $conn->RollbackTrans();
                   die(texterror("Error adding voucher detail to main database."));
              };
              if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($invgl).', '.sqlprep($voucherid).', '.sqlprep(inv($recordSet->fields[1])).')') === false) {
                   $conn->RollbackTrans();
                   die(texterror("Error adding voucher detail to main database."));
              };
              $recordSet->MoveNext();
          };
          //line items
          $recordSet = &$conn->Execute('select glaccountid,totalprice from arinvoicedetail where invoiceid='.sqlprep($invoiceid));
          while (!$recordSet->EOF) {
              $lineitems+=$recordSet->fields[1];
              if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($recordSet->fields[0]).', '.sqlprep($voucherid).', '.sqlprep(inv($recordSet->fields[1])).')') === false) {
                   $conn->RollbackTrans();
                   die(texterror("Error adding voucher detail to main database."));
              };
              $recordSet->MoveNext();
          };
          //taxes
          $recordSet = &$conn->Execute('select salestax.glacctid,arinvoicetaxdetail.taxamount from arinvoicetaxdetail,salestax where salestax.id=arinvoicetaxdetail.taxid and arinvoicetaxdetail.invoiceid='.sqlprep($invoiceid));
          while (!$recordSet->EOF) {
              $tax+=$recordSet->fields[1];
              if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($recordSet->fields[0]).', '.sqlprep($voucherid).', '.sqlprep(inv($recordSet->fields[1])).')') === false) {
                   $conn->RollbackTrans();
                   die(texterror("Error adding voucher detail to main database."));
              };
              $recordSet->MoveNext();
          };
          //shipping
          if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($shipgl).', '.sqlprep($voucherid).', '.sqlprep(inv($shipcost)).')') === false) {
              $conn->RollbackTrans();
              die(texterror("Error adding voucher detail to main database."));
          };
          //net due
          if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($argl).', '.sqlprep($voucherid).', '.sqlprep($invoicetotal).')') === false) {
              $conn->RollbackTrans();
              die(texterror("Error adding voucher detail to main database."));
          };
          if ($conn->Execute('update arinvoice set status=1, lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($invoiceid)) === false) {
              $conn->RollbackTrans();
              die(texterror('Error updating invoice.'));
          };
          $conn->CommitTrans();
     };

//echo "select arinvoice.id, arinvoice.invoicenumber, arinvoice.ponumber, arinvoice.orderbycompanyid, ordercompany.companyname, ordercompany.address1, ordercompany.address2, ordercompany.city, ordercompany.state, ordercompany.zip, ordercompany.phone1, ordercompany.phone1comment, arinvoice.shiptocompanyid, shiptocompany.companyname, shiptocompany.address1, shiptocompany.address2, shiptocompany.city, shiptocompany.state, shiptocompany.zip, shiptocompany.phone1, shiptocompany.phone1comment,arinvoice.status,arinvoice.duedate,ordercompany.id,shiptocompany.id,arinvoice.shipcost,customer.id,arinvoice.invoicetotal,salescomp.companyname,arinvoice.accruedinterest,arinvoice.invoicedate,arinvoice.invoicetermsid from arinvoice cross join customer cross join company as ordercompany cross join company as shiptocompany left join salesman on salesman.id=arinvoice.salesmanid left join company as salescomp on salesman.companyid=salescomp.id where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arinvoice.orderbycompanyid and shiptocompany.id=arinvoice.shiptocompanyid and arinvoice.invoicenumber=".sqlprep($invoicenumber)." and arinvoice.cancel=0 and arinvoice.gencompanyid=".sqlprep($active_company)." and arinvoice.status>=0 order by arinvoice.invoicedate desc<br>";
     $recordSet = &$conn->Execute("select arinvoice.id, arinvoice.invoicenumber, arinvoice.ponumber, arinvoice.orderbycompanyid, ordercompany.companyname, ordercompany.address1, ordercompany.address2, ordercompany.city, ordercompany.state, ordercompany.zip, ordercompany.phone1, ordercompany.phone1comment, arinvoice.shiptocompanyid, shiptocompany.companyname, shiptocompany.address1, shiptocompany.address2, shiptocompany.city, shiptocompany.state, shiptocompany.zip, shiptocompany.phone1, shiptocompany.phone1comment,arinvoice.status,arinvoice.duedate,ordercompany.id,shiptocompany.id,arinvoice.shipcost,customer.id,arinvoice.invoicetotal,salescomp.companyname,arinvoice.accruedinterest,arinvoice.invoicedate,arinvoice.invoicetermsid from arinvoice cross join customer cross join company as ordercompany cross join company as shiptocompany left join salesman on salesman.id=arinvoice.salesmanid left join company as salescomp on salesman.companyid=salescomp.id where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arinvoice.orderbycompanyid and shiptocompany.id=arinvoice.shiptocompanyid and arinvoice.invoicenumber=".sqlprep($invoicenumber)." and arinvoice.cancel=0 and arinvoice.gencompanyid=".sqlprep($active_company)." and arinvoice.status>=0 order by arinvoice.invoicedate desc");
     if (!$recordSet||$recordSet->EOF) die(texterror('Invoice #'.$invoicenumber.' not found.'));
     $invoiceid=$recordSet->fields[0];
     if ($recordSet->fields[21]) $orderclose=1;
     echo '<table width="100%"><tr><td align="left" valign="top" width="80%">';
     if ($recordSet->fields[3]==$recordSet->fields[12]) {
         echo '     <table border="1" width="100%"><tr><th>Order By &<br>Ship To</th></tr>';
         echo '     <tr><td>'.$recordSet->fields[4].'</td></tr>';
         if ($recordSet->fields[5]) echo '     <tr><td>'.$recordSet->fields[5].'</td></tr>';
         if ($recordSet->fields[6]) echo '     <tr><td>'.$recordSet->fields[6].'</td></tr>';
         if ($recordSet->fields[7]||$recordSet->fields[8]||$recordSet->fields[9]) echo '     <tr><td>'.$recordSet->fields[7].', '.$recordSet->fields[8].' '.$recordSet->fields[9].'</td></tr>';
         if ($recordSet->fields[10]) echo '     <tr><td>'.$recordSet->fields[10].'</td></tr>';
         if ($recordSet->fields[11]) echo '     <tr><td>'.$recordSet->fields[11].'</td></tr>';
     } else {
         echo '     <table border="1" width="75%"><tr><th>Order By</th><th>Ship To</th></tr>';
         echo '     <tr><td width="25%">'.$recordSet->fields[4].'</td><td width="50%">'.$recordSet->fields[13].'</td></tr>';
         if ($recordSet->fields[5]||$recordSet->fields[14]) echo '     <tr><td width="50%">'.$recordSet->fields[5].'</td><td width="50%">'.$recordSet->fields[14].'</td></tr>';
         if ($recordSet->fields[6]||$recordSet->fields[15]) echo '     <tr><td width="50%">'.$recordSet->fields[6].'</td><td width="50%">'.$recordSet->fields[15].'</td></tr>';
         if ($recordSet->fields[7]||$recordSet->fields[8]||$recordSet->fields[9]||$recordSet->fields[16]||$recordSet->fields[17||$recordSet->fields[18]]) echo '     <tr><td width="50%">'.$recordSet->fields[7].', '.$recordSet->fields[8].' '.$recordSet->fields[9].'</td><td width="50%">'.$recordSet->fields[16].', '.$recordSet->fields[17].' '.$recordSet->fields[18].'</td></tr>';
         if ($recordSet->fields[10]||$recordSet->fields[19]) echo '     <tr><td width="50%">'.$recordSet->fields[10].'</td><td width="50%">'.$recordSet->fields[19].'</td></tr>';
         if ($recordSet->fields[11]||$recordSet->fields[20]) echo '     <tr><td width="50%">'.$recordSet->fields[11].'</td><td width="50%">'.$recordSet->fields[20].'</td></tr>';
         $shiptostr='</td><td>';
     };
     echo '     </table>';
     echo '</td><td align="right" valign="top" width="20%">';
     echo '     <table border="1">'; //<tr><th>PO #</th></tr>';
     echo '     <tr><td align="right">PO:</td><td>'.$recordSet->fields[2].'</td></tr>';
     if ($recordSet->fields[30]) echo '<tr><td align="right">Date:</td><td>'.$recordSet->fields[30].'</td></tr>';
//     if ($recordSet->fields[22]) echo '<tr><td colspan="2">Due Date:    '.$recordSet->fields[22].'</td></tr>';
     if ($recordSet->fields[28]) echo '<tr><td align="right">Slsm:</td><td>'.$recordSet->fields[28].'</td></tr>';
     if ($recordSet->fields[31]) {
       $recordSet5 = &$conn->Execute('select id,verbal from invoiceterms where ar=1 and cancel=0 and id='.sqlprep($recordSet->fields[31]));
       if (!$recordSet5->EOF) echo '<tr><td align="right">Terms:</td><td>'.$recordSet5->fields[1].'</td></tr>';

     };

     echo '</table>';
     echo '</td></tr></table>';
     $recordSet2 = &$conn->Execute("select note from arinvoicenotes where hide=0 and invoiceid=".sqlprep($recordSet->fields[0]));
     while ($recordSet2&&!$recordSet2->EOF) {
         echo '<table><tr><td>Notes:</td><td>';
         echo $recordSet2->fields[0];
         echo '</td></tr></table>';
         $recordSet2->MoveNext();
     };
     echo '<table border="1" width="100%">';
     $recordSet2 = &$conn->Execute("select arinvoicedetail.description,arinvoicedetail.qty,arinvoicedetail.glaccountid,arinvoicedetail.taxflag,arinvoicedetail.priceach,arinvoicedetail.priceunitnameid,arinvoicedetail.qtyunitnameid,arinvoicedetail.qtyunitperpriceunit,arinvoicedetail.linenumber from arinvoicedetail where arinvoicedetail.invoiceid=".sqlprep($recordSet->fields[0])." order by arinvoicedetail.linenumber");
     if ($recordSet2&&!$recordSet2->EOF) echo '<tr><th>Ln&nbsp;#</th><th>Quantity</th><th>Description</th><th>Unit Price</th><th>Total</th></tr>';
     while ($recordSet2&&!$recordSet2->EOF) {
         if ($recordSet2->fields[1]>0) { //if quantity isn't 0
              echo '<tr><td>'.$recordSet2->fields[8].'</td>'; //line number
              echo '<td>'.checkdec($recordSet2->fields[1],0).' ';
              $recordSet3 = &$conn->SelectLimit('select unitname from unitname where id='.sqlprep($recordSet2->fields[6]),1);
              echo $recordSet3->fields[0].'</td>';

              echo '<td>'.$recordSet2->fields[0].'</td>'; // description
              echo '<td>'.CURRENCY_SYMBOL.num_format($recordSet2->fields[4],PREFERRED_DECIMAL_PLACES).' '; //price
              $recordSet3 = &$conn->SelectLimit('select unitname from unitname where id='.sqlprep($recordSet2->fields[5]),1);
              echo $recordSet3->fields[0];
              echo '</td>';
              echo '<td align="right">'.CURRENCY_SYMBOL.num_format($recordSet2->fields[4]*$recordSet2->fields[1]/$recordSet2->fields[7],PREFERRED_DECIMAL_PLACES); //extended price
              echo '</td></tr>';
              $total+=$recordSet2->fields[1]*$recordSet2->fields[4]/$recordSet2->fields[7];
         };
         $recordSet2->MoveNext();
     };
     echo '<tr><td colspan="4"><div align="right"><b>Subtotal:</b></div></td><td align="right">'.CURRENCY_SYMBOL.num_format($total,2).'</td></tr>';
     $recordSet2 = &$conn->Execute('select salestax.taxname,arinvoicetaxdetail.taxamount from salestax,arinvoicetaxdetail where arinvoicetaxdetail.invoiceid='.sqlprep($invoiceid).' and arinvoicetaxdetail.taxid=salestax.id order by salestax.taxname');
     while ($recordSet2&&!$recordSet2->EOF) {
         echo '<tr><td colspan="4"><div align="right"><b>Tax - '.$recordSet2->fields[0].':</b></div></td><td align="right">'.CURRENCY_SYMBOL.num_format($recordSet2->fields[1],PREFERRED_DECIMAL_PLACES).'</td></tr>';
         $recordSet2->MoveNext();
     };
     echo '<tr><td colspan="4"><div align="right"><b>Shipping:</b></div></td><td align="right">'.CURRENCY_SYMBOL.num_format($recordSet->fields[25],PREFERRED_DECIMAL_PLACES).'</td></tr>';
//     if ($recordSet->fields[29]) echo '<tr><td colspan="4"><div align="right"><b>Interest:</b></div></td><td align="right">'.CURRENCY_SYMBOL.num_format($recordSet->fields[29],PREFERRED_DECIMAL_PLACES).'</td></tr>';
// usually only show interest on statements
     echo '<tr><td colspan="4"><div align="right"><b>Total:</b></div></td><td align="right">'.CURRENCY_SYMBOL.num_format($recordSet->fields[27],PREFERRED_DECIMAL_PLACES).'</td></tr>';
     echo '</table>';
?>
<?php include('includes/footer.php'); ?>
