<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?php include('includes/glfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>


<?php   echo '<center>';
     echo texttitle($lang['STR_INVOICE_UPDATE']);
     if ($invoicenumber&&$delete) { //delete invoice
          checkpermissions('ar');
          $recordSet = &$conn->SelectLimit('select id from arinvoice where invoicenumber='.sqlprep($invoicenumber).' and cancel=0 and gencompanyid='.sqlprep($active_company).' and status<1 order by invoicedate desc',1);
          if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_INVOICE_NUMBER'] .$invoicenumber. $lang['STR_NOT_FOUND_COULD_NOT_DELETE']));
          $invoiceid=$recordSet->fields[0];
          if ($conn->Execute('update arinvoice set cancel=1, canceluserid='.sqlprep($userid).',canceldate=NOW(),lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($invoiceid)) === false) die(texterror('Error deleting invoice.'));
          echo textsuccess('Invoice deleted successfully.');
     };
     if ($invoicenumber&&$unpost) { //unpost invoice
          checkpermissions('ar');
          $recordSet = &$conn->SelectLimit('select id from arinvoice where invoicenumber='.sqlprep($invoicenumber).' and cancel=0 and gencompanyid='.sqlprep($active_company).' and status=1 order by invoicedate desc',1);
          if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_INVOICE_NUMBER'] .$invoicenumber. $lang['STR_NOT_FOUND_COULD_NOT_DELETE']));
          $invoiceid=$recordSet->fields[0];
          $recordSet = &$conn->SelectLimit('select status,id from gltransvoucher where voucher='.sqlprep('invoice'.$invoicenumber).' and cancel=0 and wherefrom='.sqlprep(moduleidfromnameshort('ar')).' and companyid='.sqlprep($active_company),1);
          if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_QUERY_FAILED']));
          if ($recordSet->fields[0]) { //invoice has been posted in GL.  reverse posting
               $conn->BeginTrans();
               $recordSet = &$conn->SelectLimit('select id from gltransvoucher where voucher='.sqlprep('invoice'.$invoicenumber).' and companyid='.sqlprep($active_company).' order by lastchangedate desc',1);
               if (!$recordSet||$recordSet->EOF) {
                   $conn->RollbackTrans();
                   die(texterror($lang['STR_ERROR_RETRIEVING_VOUCHER_FROM_DATABASE']));
               };
               $voucherid=$recordSet->fields[0];
               if ($conn->Execute('insert into gltransvoucher (voucher,description,wherefrom,companyid,entrydate,lastchangeuserid,entryuserid) values ('.sqlprep('invoice'.$invoicenumber.'reversal').', '.sqlprep('AR Invoice Reversal').','.sqlprep(moduleidfromnameshort('ar')).', '.sqlprep($active_company).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).')') === false) {
                    $conn->RollbackTrans();
                    die(texterror($lang['STR_ERROR_ADDING_VOUCHER_TO_DATABASE']));
               };
               $recordSet = &$conn->SelectLimit('select id from gltransvoucher where voucher='.sqlprep('invoice'.$invoicenumber.'reversal').' and companyid='.sqlprep($active_company).' order by lastchangedate desc',1);
               if (!$recordSet||$recordSet->EOF) {
                   $conn->RollbackTrans();
                   die(texterror($lang['STR_ERROR_RETRIEVING_VOUCHER_FROM_DATABASE']));
               };
               $newvoucherid=$recordSet->fields[0];
               $recordSet = &$conn->Execute('select glaccountid,amount from gltransaction where voucherid='.sqlprep($voucherid));
               while ($recordSet&&!$recordSet->EOF) {
                   if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($recordSet->fields[0]).', '.$newvoucherid.', '.sqlprep(inv($recordSet->fields[1])).')') === false) {
                      $conn->RollbackTrans();
                      die(texterror($lang['STR_ERROR_ADDING_VOUCHER_DETAIL_TO_DATABASE']));
                   };
                   $recordSet->MoveNext();
               };

               if ($conn->Execute('update arinvoice set status=0, lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($invoiceid)) === false) {
                   $conn->RollbackTrans();
                   die(texterror($lang['STR_ERROR_UPDATING_INVOICE']));
               };
               $conn->CommitTrans();
               echo textsuccess($lang['STR_INVOICE_NUMBER'] .$invoicenumber. $lang['STR_UNPOSTED_SUCCESSFULLY']);
          } else { //invoice has not been posted
              gltransactiondelete($recordSet->fields[1]);
              gltransvoucherdelete($recordSet->fields[1]);
              $recordSet = &$conn->SelectLimit('select id from arinvoice where invoicenumber='.sqlprep($invoicenumber).' and cancel=0 and gencompanyid='.sqlprep($active_company).' and status>=1 order by invoicedate desc',1);
              if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_INVOICE_NUMBER'] .$invoicenumber. $lang['STR_NOT_FOUND_COULD_NOT_UNPOST']));
              $invoiceid=$recordSet->fields[0];
              if ($conn->Execute('update arinvoice set status=0, lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($invoiceid)) === false) die(texterror('Error deleting invoice.'));
          };
     };
     if ($invoicenumber&&$submit) { //update invoice
          checkpermissions('ar');
          $recordSet = &$conn->SelectLimit('select id from arinvoice where invoicenumber='.sqlprep($invoicenumber).' and cancel=0 and gencompanyid='.sqlprep($active_company).' and status<1 order by invoicedate desc',1);
          if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_INVOICE_NUMBER'] .$invoicenumber. $lang['STR_NOT_FOUND_COULD_NOT_UPDATE']));
          $invoiceid=$recordSet->fields[0];
          $conn->Execute("delete from arinvoicedetail where invoiceid=".sqlprep($invoiceid));
          $j=1;
          for ($i=1; ${"description".$i}; $i++) {
               if (${"qty".$i}>0) {
                     if (!${"qtyunitperpriceunit".$i}) ${"qtyunitperpriceunit".$i}=1;
                     ${"totalprice".$i}=(${"qty".$i}/${"qtyunitperpriceunit".$i})*${"priceach".$i};
                     $invoicetotal+=${"totalprice".$i};
                     if ($conn->Execute("insert into arinvoicedetail (invoiceid,linenumber,description,qty,qtyunitnameid,glaccountid,taxflag,priceach,priceunitnameid,qtyunitperpriceunit,totalprice,entrydate,entryuserid,lastchangeuserid) VALUES (".sqlprep($invoiceid).", ".sqlprep($j).', '.sqlprep(${"description".$i}).", ".sqlprep(${"qty".$i}).", ".sqlprep(${"qtyunitnameid".$i}).", ".sqlprep(${"glaccountid".$i}).", ".sqlprep(${"taxflag".$i}).", ".sqlprep(${"priceach".$i}).", ".sqlprep(${"priceunitnameid".$i}).", ".sqlprep(${"qtyunitperpriceunit".$i}).", ".sqlprep(${"totalprice".$i}).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).")") === false) echo texterror('Error inserting invoice details. ('.$i.')');
                     $j++;
               };
          };
          $conn->Execute("delete from arinvoicetaxdetail where invoiceid=".sqlprep($invoiceid));
          for ($i=1; ${"taxid".$i}; $i++) {
               if (${"tax".$i}>0) { //if tax amount > 0
                     if ($conn->Execute("insert into arinvoicetaxdetail (invoiceid,taxid,taxamount) VALUES (".sqlprep($invoiceid).", ".sqlprep(${"taxid".$i}).', '.sqlprep(${"tax".$i}).")") === false) echo texterror('Error inserting invoice tax details. ('.$i.')');
                     $invoicetotal+=${"tax".$i};
               };
          };
          $conn->Execute("delete from arinvoicenotes where invoiceid=".sqlprep($invoiceid));
          for ($i=1; ${"notes".$i}; $i++) if ($conn->Execute("insert into arinvoicenotes (invoiceid,note,hide,lastchangeuserid) VALUES (".sqlprep($invoiceid).", ".sqlprep(${"notes".$i}).', '.sqlprep(${"hide".$i}).', '.sqlprep($userid).")") === false) echo texterror('Error inserting invoice note. ('.$i.')');
          $conn->Execute("update arinvoice set salesmanid=".sqlprep($salesmanid).", duedate=".sqlprep($duedate).", invoicedate=".sqlprep($invoicedate).",invoicetermsid=".sqlprep($invoicetermsid).", shiptocompanyid=".sqlprep($shiptocompanyid).", invoicetotal=".sqlprep($invoicetotal+$shipcost).", shipcost=".sqlprep($shipcost).", lastchangeuserid='.sqlprep($userid).' where id=".sqlprep($invoiceid));
          echo textsuccess($lang['STR_INVOICE_UPDATE_SUCCESSFULLY']);
     };
     if ($customerid||$ponumber||$invoicenumber||$notesfield) { //if the user has submitted initial info
          if ($invoicenumber) $invstr=' and arinvoice.invoicenumber='.sqlprep($invoicenumber);
          if ($ponumber) $ponumberstr=' and arinvoice.ponumber='.sqlprep($ponumber);
          if ($customerid) $customeridstr=' and customer.id='.sqlprep($customerid);
          if ($notesfield) $notesstr1='left join arinvoicenotes on arinvoicenotes.invoiceid=arinvoice.id and arinvoicenotes.note like '.sqlprep('%'.$notesfield.'%');
          $recordSet = &$conn->Execute("select count(distinct arinvoice.id) from arinvoice,customer, company as ordercompany, company as shiptocompany".$notesstr1." where customer.companyid=ordercompany.id and arinvoice.status>=0 and ordercompany.id=arinvoice.orderbycompanyid and shiptocompany.id=arinvoice.shiptocompanyid".$invstr.$ponumberstr.$customeridstr." and arinvoice.gencompanyid=".sqlprep($active_company));
          if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]>1) { //if there is more than one order matching criteria
               echo '<table border="1"><tr><th>'.$lang['STR_INVOICE_NUMBER'].'</th><th>'.$lang['STR_PO_NUMBER'].'</th><th>'.$lang['STR_SHIP_TO'].'</th><th>'.$lang['STR_STATUS'].'</th></tr>';
               $recordSet = &$conn->Execute("select distinct arinvoice.invoicenumber, arinvoice.ponumber, arinvoice.orderbycompanyid, ordercompany.companyname, arinvoice.shiptocompanyid, shiptocompany.companyname, arinvoice.status from arinvoice,customer, company as ordercompany, company as shiptocompany ".$notesstr1." where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and arinvoice.status>=0 and ordercompany.id=arinvoice.orderbycompanyid and shiptocompany.id=arinvoice.shiptocompanyid".$invstr.$ponumberstr.$customeridstr." and arinvoice.gencompanyid=".sqlprep($active_company)." order by arinvoice.invoicedate desc");
               while ($recordSet&&!$recordSet->EOF) {
                    if ($recordSet->fields[6]) {
                         $statusstr='<font color="#FF0000">'.$lang['STR_POSTED'].'</font>';
                    } else {
                         $statusstr='<font color="#00FF00">'.$lang['STR_OPEN'].'</font>';
                    };
                    echo '<tr><td><a href="arinvoiceupd.php?invoicenumber='.$recordSet->fields[0].'">'.$recordSet->fields[0].'</a></td><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[5].'</td><td>'.$statusstr.'</td></tr>';
                    $recordSet->MoveNext();
               };
               echo '</table>';
          } else {
               $recordSet = &$conn->SelectLimit("select arinvoice.id, arinvoice.invoicenumber, arinvoice.ponumber, arinvoice.orderbycompanyid, ordercompany.companyname, ordercompany.address1, ordercompany.address2, ordercompany.city, ordercompany.state, ordercompany.zip, ordercompany.phone1, ordercompany.phone1comment, arinvoice.shiptocompanyid, shiptocompany.companyname, shiptocompany.address1, shiptocompany.address2, shiptocompany.city, shiptocompany.state, shiptocompany.zip, shiptocompany.phone1, shiptocompany.phone1comment,arinvoice.status,arinvoice.duedate,ordercompany.id,shiptocompany.id,arinvoice.shipcost,customer.id,arinvoice.invoicetotal,arinvoice.salesmanid,salescomp.companyname,arinvoice.accruedinterest,arinvoice.invoicedate,arinvoice.invoicetermsid from arinvoice cross join customer cross join company as ordercompany cross join company as shiptocompany ".$notesstr1." left join salesman on arinvoice.salesmanid=salesman.id left join company as salescomp on salesman.companyid=salescomp.id where (customer.companyid=ordercompany.id or customer.companyid=shiptocompany.id) and ordercompany.id=arinvoice.orderbycompanyid and shiptocompany.id=arinvoice.shiptocompanyid".$invstr.$ponumberstr.$customeridstr." and arinvoice.gencompanyid=".sqlprep($active_company)." and arinvoice.status>=0 order by arinvoice.invoicedate desc",1);
               if ($recordSet&&!$recordSet->EOF) {
                    if ($recordSet->fields[21]) $orderclose=1;
                    echo '<form action="arinvoiceupd.php" method="post" name="mainform"><table border="1">';
                    echo '<input type="hidden" name="invoicenumber" value="'.$recordSet->fields[1].'">';
                    $invoiceid=$recordSet->fields[0];
                    $invoicenumber=$recordSet->fields[1];
                    if ($customerid) {
                        echo '<input type="hidden" name="customerid" value="'.$customerid.'">';
                    } else {
                        $customerid=$recordSet->fields[26];
                        echo '<input type="hidden" name="customerid" value="'.$recordSet->fields[26].'">';
                    };
                    echo '<table width="100%"><tr><td align="left" valign="top">';
                    if ($recordSet->fields[3]==$recordSet->fields[12]) {
                         echo '     <table border="1" width="50%"><tr><th>'.$lang['STR_ORDER_BY'].' &<br>'.$lang['STR_SHIP_TO'].'</th></tr>';
                         echo '     <tr><td>'.$recordSet->fields[4].'</td></tr>';
                         if ($recordSet->fields[5]) echo '     <tr><td>'.$recordSet->fields[5].'</td></tr>';
                         if ($recordSet->fields[6]) echo '     <tr><td>'.$recordSet->fields[6].'</td></tr>';
                         if ($recordSet->fields[7]||$recordSet->fields[8]||$recordSet->fields[9]) echo '     <tr><td>'.$recordSet->fields[7].', '.$recordSet->fields[8].' '.$recordSet->fields[9].'</td></tr>';
                         if ($recordSet->fields[10]) echo '     <tr><td>'.$recordSet->fields[10].'</td></tr>';
                         if ($recordSet->fields[11]) echo '     <tr><td>'.$recordSet->fields[11].'</td></tr>';
                    } else {
                         echo '     <table border="1" width="50%"><tr><th>'.$lang['STR_ORDER_BY'].'</th><th>'.$lang['STR_SHIP_TO'].'</th></tr>';
                         echo '     <tr><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[13].'</td></tr>';
                         if ($recordSet->fields[5]||$recordSet->fields[14]) echo '     <tr><td>'.$recordSet->fields[5].'</td><td>'.$recordSet->fields[14].'</td></tr>';
                         if ($recordSet->fields[6]||$recordSet->fields[15]) echo '     <tr><td>'.$recordSet->fields[6].'</td><td>'.$recordSet->fields[15].'</td></tr>';
                         if ($recordSet->fields[7]||$recordSet->fields[8]||$recordSet->fields[9]||$recordSet->fields[16]||$recordSet->fields[17||$recordSet->fields[18]]) echo '     <tr><td>'.$recordSet->fields[7].', '.$recordSet->fields[8].' '.$recordSet->fields[9].'</td><td>'.$recordSet->fields[16].', '.$recordSet->fields[17].' '.$recordSet->fields[18].'</td></tr>';
                         if ($recordSet->fields[10]||$recordSet->fields[19]) echo '     <tr><td>'.$recordSet->fields[10].'</td><td>'.$recordSet->fields[19].'</td></tr>';
                         if ($recordSet->fields[11]||$recordSet->fields[20]) echo '     <tr><td>'.$recordSet->fields[11].'</td><td>'.$recordSet->fields[20].'</td></tr>';
                         $shiptostr='</td><td>';
                    };
                    $recordSet2 = &$conn->Execute("select count(*) from company,shipto where shipto.shiptocompanyid=company.id and shipto.companyid=".sqlprep($recordSet->fields[23]));
                    if ($recordSet2&&!$recordSet2->EOF) if ($recordSet2->fields[0]>1) {
                            echo '<tr><td>'.$lang['STR_CHANGE_SHIP_TO'].' :'.$shiptostr;
                            echo '<select name="shiptocompanyid">';
                            $recordSet2 = &$conn->Execute("select company.id,company.companyname,company.address1,company.city,company.state,company.country from company,shipto where shipto.shiptocompanyid=company.id and shipto.companyid=".sqlprep($recordSet->fields[23])." and shipto.cancel=0 and company.cancel=0 order by company.companyname,company.country,company.address1,company.city,company.state");
                            while (!$recordSet2->EOF) {
                                 echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$recordSet->fields[24],' selected').'>'.rtrim($recordSet2->fields[1]).' - '.rtrim($recordSet2->fields[2]).', '.rtrim($recordSet2->fields[3]).', '.rtrim($recordSet2->fields[4]).' '.rtrim($recordSet2->fields[5]);
                                 $recordSet2->MoveNext();
                            };
                            echo '</td></tr>';
                    } else {
                            echo '<input type="hidden" name="shiptocompanyid" value="'.$recordSet->fields[24].'">';
                    };
                    echo '     </table>';
                    echo '</td><td align="right" valign="top">';
                    echo '     <table border="1"><tr><th>'.$lang['STR_INVOICE_NUMBER'].'</th><th>'.$lang['STR_PO_NUMBER'].'</th></tr>';
                    echo '     <tr><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[2].'</td></tr>';
                    if ($printable) {
                         echo '<tr><td colspan="2">'.$lang['STR_DATE'].': '.$recordSet->fields[31].'</td></tr>';
                         echo '<tr><td colspan="2">'.$lang['STR_DUE'].': '.$recordSet->fields[22].'</td></tr>';
                    } else {
                         echo '<tr><td align="right">'.$lang['STR_INVOICE_DATE'].'</td><td><input type="text" name="invoicedate" value="'.$recordSet->fields[31].'"></td></tr>';
                         echo '<tr><td align="right">'.$lang['STR_DUE_DATE'].'</td><td><input type="text" name="duedate" value="'.$recordSet->fields[22].'"></td></tr>';
                    };
                    //////////////////////////////////////////////////////
                    echo '<tr><td align="right">Terms</td><td><select name="invoicetermsid"'.INC_TEXTBOX.'>';
                   $recordSet5 = &$conn->Execute('select id,verbal from invoiceterms where ar=1 and cancel=0 order by id');
                   if (!$recordSet5||$recordSet5->EOF)  {
                      //do nothing
                   } else {
                      while (!$recordSet5->EOF) {
                          echo '<option value="'.$recordSet5->fields[0].'"'.checkequal($recordSet->fields[32],$recordSet5->fields[0],' selected').'>'.rtrim($recordSet5->fields[1])."\n";
                         $recordSet5->MoveNext();
                       };
                   };
                   echo '</select></td></tr>';
                    echo '<tr><td align="right">'.$lang['STR_SALESMAN'].'</td><td><select name="salesmanid"'.INC_TEXTBOX.'>';
                    $recordSet2 = &$conn->Execute('select salesman.id,company.companyname from company,salesman where salesman.companyid=company.id and salesman.cancel=0 order by company.companyname');
                    while (!$recordSet2->EOF) {
                        echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[28],$recordSet2->fields[0],' selected').'>'.$recordSet2->fields[1]."\n";
                        $recordSet2->MoveNext();
                    };
                    echo '</td></tr>';
                    echo '</table>';
                    echo '</td></tr></table>';
                    if ($orderclose) echo '<center><font color="#FF0000">'.$lang['STR_THIS_INVOICE_HAS_BEEN_POSTED_MUST_UNPOST'].'</font></center><br>';
                    $recordSet2 = &$conn->Execute("select note,hide from arinvoicenotes where invoiceid=".sqlprep($recordSet->fields[0]));
                    $i=1;
                    while ($recordSet2&&!$recordSet2->EOF) {
                        echo '<table><tr><td>'.$lang['STR_NOTES'].':</td><td><textarea name="notes'.$i.'" rows="3" cols="25">';
                        echo $recordSet2->fields[0];
                        echo '</textarea></td><td>'.$lang['STR_HIDE_WHEN_PRINTING'].'<input type="checkbox" name="hide'.$i.'" value="1"'.checkequal($recordSet2->fields[1],1,' checked').'></td></tr></table>';
                        $i++;
                        $recordSet2->MoveNext();
                    };
                    echo '<table><tr><td>'.$lang['STR_NOTES'].':</td><td><textarea name="notes'.$i.'" rows="3" cols="25"></textarea></td><td>Hide when printing? <input type="checkbox" name="hide" value="1"></td></tr></table>';
                    echo '<table border="1" width="100%">';
                    $i=1;
                    $recordSet2 = &$conn->Execute("select arinvoicedetail.description,arinvoicedetail.qty,arinvoicedetail.glaccountid,arinvoicedetail.taxflag,arinvoicedetail.priceach,arinvoicedetail.priceunitnameid,arinvoicedetail.qtyunitnameid,arinvoicedetail.qtyunitperpriceunit from arinvoicedetail where arinvoicedetail.invoiceid=".sqlprep($recordSet->fields[0])." order by arinvoicedetail.linenumber");
                    if ($recordSet2&&!$recordSet2->EOF) {
                         echo '<tr><th>'.$lang['STR_DESCRIPTION'].'</th><th>'.$lang['STR_TAXABLE'].' <input type="checkbox" checked></th><th rowspan="2">'.$lang['STR_QTY_UNIT_PER'].'<br>'.$lang['STR_PRICE_UNIT'].'</th></tr>';
                         echo '<tr><th>'.$lang['STR_PRICE'].'</th><th>'.$lang['STR_QUANTITY'].'</th></tr>';
                    };
                    while ($recordSet2&&!$recordSet2->EOF) {
                      if ($recordSet2->fields[1]>0) { //if quantity isn't 0
                        echo '<tr><td><input type="text" name="description'.$i.'" value="'.$recordSet2->fields[0].'" size="50" maxlength="100"'.INC_TEXTBOX.'></td>';
                        echo '<td align="center"><input type="checkbox" name="taxflag'.$i.'" value="1"'.checkequal($recordSet2->fields[3],1,' checked').INC_TEXTBOX.'></td><td></td></tr>';
                        echo '<tr><td><input type="text" name="priceach'.$i.'" value="'.num_format($recordSet2->fields[4],PREFERRED_DECIMAL_PLACES).'" onchange="validatenum(this)" size="10" maxlength="15"'.INC_TEXTBOX.'>';
                        echo '<select name="priceunitnameid'.$i.'"'.INC_TEXTBOX.'>';
                        $recordSet3 = &$conn->Execute('select id,unitname from unitname where cancel=0 order by id');
                        while ($recordSet3&&!$recordSet3->EOF) {
                          echo '<option value="'.$recordSet3->fields[0].'"'.checkequal($recordSet2->fields[5],$recordSet3->fields[0],' selected').'>'.$recordSet3->fields[1]."\n";
                          $recordSet3->MoveNext();
                        };
                        echo '</select>';
                        echo '<select name="glaccountid'.$i.'"'.INC_TEXTBOX.'>';
                        $recordSet3 = &$conn->Execute("select glaccount.id,glaccount.name, glaccount.description, customer.salesglacctid from glaccount,customer where customer.id=".sqlprep($customerid)." and glaccount.accounttypeid='50' and (glaccount.companyid=0 or glaccount.companyid=".sqlprep($active_company).") order by glaccount.name");
                        while ($recordSet3&&!$recordSet3->EOF) {
                          echo '<option value="'.$recordSet3->fields[0].'"'.checkequal($recordSet3->fields[0],$recordSet2->fields[2]," selected").'>'.rtrim($recordSet3->fields[1]).' - '.rtrim($recordSet3->fields[2])."\n";
                          $recordSet3->MoveNext();
                        };
                        echo '</select></td>';
                        echo '<td><input type="text" name="qty'.$i.'" value="'.checkdec($recordSet2->fields[1],0).'" onchange="validatenum(this)" size="5" maxlength="15"'.INC_TEXTBOX.'>';
                        echo '<select name="qtyunitnameid'.$i.'"'.INC_TEXTBOX.'>';
                        $recordSet3 = &$conn->Execute('select id,unitname from unitname where cancel=0 order by id');
                        while ($recordSet3&&!$recordSet3->EOF) {
                          echo '<option value="'.$recordSet3->fields[0].'"'.checkequal($recordSet3->fields[0],$recordSet2->fields[6],' selected').'>'.$recordSet3->fields[1]."\n";
                          $recordSet3->MoveNext();
                        };
                        echo '</select></td>';
                        echo '<td><input type="text" name="qtyunitperpriceunit'.$i.'" onchange="validatenum(this)" value="'.checkdec($recordSet2->fields[7],0).'" size="5" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                        $qpu=$recordSet2->fields[7];
                        if ($qpu<=0) $qpu=1;
                        $total+=$recordSet2->fields[1]*$recordSet2->fields[4]/$qpu; //add qty*price each to total
                        if ($recordSet2->fields[3]) $totaltaxprice+=$recordSet2->fields[1]*$recordSet2->fields[4]/$qpu; //add qty*price each to totaltaxprice if item is taxable
                        $i++;
                      };
                      $recordSet2->MoveNext();
                    };
                    if (!$orderclose) { //create blank entry line, for adding an item
                        echo '<tr><td><input type="text" name="description'.$i.'" size="50" maxlength="100"'.INC_TEXTBOX.'></td>';
                        $recordSet2 = &$conn->SelectLimit('select taxexemptid from customer where customer.id='.sqlprep($customerid),1);
                        if ($recordSet2&&!$recordSet2->EOF) if ($recordSet2->fields[0]) $taxstr=' checked';
                        echo '<td align="center"><input type="checkbox" name="taxflag'.$i.'" value="1"'.$taxstr.INC_TEXTBOX.'></td><td></td></tr>';
                        echo '<tr><td><input type="text" name="priceach'.$i.'" onchange="validatenum(this)" size="10" maxlength="15"'.INC_TEXTBOX.'>';
                        echo '<select name="priceunitnameid'.$i.'"'.INC_TEXTBOX.'>';
                        $recordSet2 = &$conn->Execute('select id,unitname from unitname where cancel=0 order by id');
                        while ($recordSet2&&!$recordSet2->EOF) {
                            echo '<option value="'.$recordSet2->fields[0].'">'.$recordSet2->fields[1]."\n";
                            $recordSet2->MoveNext();
                        };
                        echo '</select>';
                        echo '<select name="glaccountid'.$i.'"'.INC_TEXTBOX.'>';
                        $recordSet2 = &$conn->Execute("select glaccount.id,glaccount.name, glaccount.description, customer.salesglacctid from glaccount,customer where customer.id=".sqlprep($customerid)." and glaccount.accounttypeid='50' and (glaccount.companyid=0 or glaccount.companyid=".sqlprep($active_company).") order by glaccount.name");
                        while ($recordSet2&&!$recordSet2->EOF) {
                            echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$recordSet2->fields[3]," selected").'>'.rtrim($recordSet2->fields[1]).' - '.rtrim($recordSet2->fields[2])."\n";
                            $recordSet2->MoveNext();
                        };
                        echo '</select></td>';
                        echo '<td><input type="text" name="qty'.$i.'" onchange="validatenum(this)" size="5" maxlength="15"'.INC_TEXTBOX.'>';
                        echo '<select name="qtyunitnameid'.$i.'"'.INC_TEXTBOX.'>';
                        $recordSet2 = &$conn->Execute('select id,unitname from unitname where cancel=0 order by id');
                        while ($recordSet2&&!$recordSet2->EOF) {
                            echo '<option value="'.$recordSet2->fields[0].'">'.$recordSet2->fields[1]."\n";
                            $recordSet2->MoveNext();
                        };
                        echo '</select></td>';
                        echo '<td><input type="text" name="qtyunitperpriceunit'.$i.'" onchange="validatenum(this)" value="1" size="5" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    };
                    echo '<tr><td colspan="2"><div align="right"><b>'.$lang['STR_SUBTOTAL'].':</b></div></td><td>'.CURRENCY_SYMBOL.num_format($total,2).'</td></tr>';
                    if ($orderclose) { //if order is closed, don't show tax calculations, just actual taxes charged
                       $recordSet2 = &$conn->Execute('select salestax.taxname,arinvoicetaxdetail.taxamount from salestax,arinvoicetaxdetail where arinvoicetaxdetail.invoiceid='.sqlprep($invoiceid).' and arinvoicetaxdetail.taxid=salestax.id order by salestax.taxname');
                       while ($recordSet2&&!$recordSet2->EOF) {
                           echo '<tr><td colspan="2"><div align="right"><b>'.$lang['STR_TAX'].' - '.rtrim($recordSet2->fields[0]).':</b></div></td><td>'.CURRENCY_SYMBOL.num_format($recordSet2->fields[1],PREFERRED_DECIMAL_PLACES).'</td></tr>';
                           $ttotal+=$totaltaxprice*$recordSet2->fields[2];
                           $recordSet2->MoveNext();
                       };
                    } else {
                       if ($totaltaxprice) { //only try to calc taxes if something was taxable
                           $recordSet2 = &$conn->Execute('select salestax.id,salestax.taxname,salestax.taxrate/100 from salestax,customersalestax,customer where customersalestax.salestaxid=salestax.id and customersalestax.customerid='.sqlprep($customerid).' and salestax.cancel=0 and customer.id='.sqlprep($customerid).' order by salestax.taxname');
                           $i=1;
                           while ($recordSet2&&!$recordSet2->EOF) {
                             $recordSet3 = &$conn->Execute('select taxamount from arinvoicetaxdetail where invoiceid='.sqlprep($invoiceid).' and taxid='.sqlprep($recordSet2->fields[0]));
                             if ($recordSet3->fields[0]) {
                                 $tax=$recordSet3->fields[0]; //tax entered
                             } else {
                                 $tax=$totaltaxprice*$recordSet2->fields[2]; //calculated tax
                             };
                             echo '<tr><td colspan="2"><div align="right"><b>Tax - '.rtrim($recordSet2->fields[1]).':<input type="hidden" name="taxid'.$i.'" value="'.$recordSet2->fields[0].'"></b></div></td><td><input type="text" name="tax'.$i.'" size="30" maxlength="20" onchange="validatenum(this)" value="'.num_format($tax,PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'>';
                             if (num_format($tax,PREFERRED_DECIMAL_PLACES)==num_format($recordSet3->fields[0],PREFERRED_DECIMAL_PLACES)&&num_format($tax,PREFERRED_DECIMAL_PLACES)<>num_format(($totaltaxprice*$recordSet2->fields[2]),PREFERRED_DECIMAL_PLACES)) echo ' <font size="-1">('.CURRENCY_SYMBOL.num_format(($totaltaxprice*$recordSet2->fields[2]),PREFERRED_DECIMAL_PLACES).')</font>';
                             echo '</td></tr>';
                             $ttotal+=$totaltaxprice*$tax;
                             $recordSet2->MoveNext();
                             $i++;
                           };
                       };
                    };
                    echo '<tr><td colspan="2"><div align="right"><b>'.$lang['STR_SHIPPING'].':</b></div></td><td><input type="text" name="shipcost" size="10" maxlength="15" value="'.num_format($recordSet->fields[25],PREFERRED_DECIMAL_PLACES).'" onchange="validatenum(this)"'.INC_TEXTBOX.'></td></tr>';
                    if ($recordSet->fields[30]) echo '<tr><td colspan="2"><div align="right"><b>'.$lang['STR_INTEREST'].':</b></div></td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[30],PREFERRED_DECIMAL_PLACES).'</td></tr>';
                    echo '<tr><td colspan="2"><div align="right"><b>'.$lang['STR_TOTAL'].':</b></div></td><td>'.CURRENCY_SYMBOL.num_format($recordSet->fields[27]+$recordSet->fields[30],PREFERRED_DECIMAL_PLACES).'</td></tr>';
                    echo '</table>';
                    if (!$orderclose) echo '<input type="submit" name="submit" value="'.$lang['STR_SAVE_CHANGES'].'"><input type="reset" value="Reset">';
                    echo '</form> ';
                    echo '<a href="arinvoiceaddcogs.php?invoiceid='.$invoiceid.'&customerid='.$customerid.'&invoicenumber='.$invoicenumber.'">'.$lang['STR_ENTER_COST_OF_GOODS'].'</a><br>';
                    if (!$orderclose) {
                        echo '<a href="arinvoiceview.php?printable=1&post=1&invoicenumber='.$invoicenumber.'">'.$lang['STR_POST_PRINT_THIS_INVOICE'].'</a><br><br>';
                        echo '<a href="javascript:confirmdelete(\'arinvoiceupd.php?delete=1&invoicenumber='.$invoicenumber.'\')">'.$lang['STR_DELETE_INVOICE'].'</a>';
                    } else {
                        echo '<a href="arinvoiceview.php?printable=1&invoicenumber='.$invoicenumber.'">'.$lang['STR_PRINT_INVOICE'].'</a><br><br>';
                        echo '<a href="javascript:confirmunpost(\'arinvoiceupd.php?unpost=1&invoicenumber='.$invoicenumber.'\')">'.$lang['STR_UNPOST_INVOICE'].'</a>';
                    };
               } else {
                    die(texterror($lang['STR_NO_MATCHING_INVOICES_FOUND']));
               };
          };
     } else {
          echo '<form action="arinvoiceupd.php" method="post" name="mainform"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_NUMBER'].':</td><td><input type="text" name="invoicenumber" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PURCHASE_ORDER'].':</td><td><input type="text" name="ponumber" size="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_NUMBER'].':</td><td><input type="text" name="customerid" onchange="validateint(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupcustomer.php?name=customerid\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Customer Lookup"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NOTES'].':</td><td><input type="text" name="notesfield" size="30"'.INC_TEXTBOX.'></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_SEARCH'].'"></form>';
     };
     
          echo '</center>';
?>

<?php include_once("includes/footer.php"); ?>
