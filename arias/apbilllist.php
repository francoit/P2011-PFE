<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>


<script language="JavaScript">
      var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?
        echo '<center>';
        echo texttitle($lang['STR_AP_BILL_LIST']);
        if ($bgdateofinvoice&&$eddateofinvoice) echo texttitle('<font size="-1">'.$bgdateofinvoice.' - '.$eddateofinvoice.'</font>');
        if ($bgduedate&&$edduedate) echo texttitle($lang['STR_FOR_INVOICES_DUE']);
        if ($continue||$invoicenumber||$vendorid||$bgdateofinvoice||$eddateofinvoice||$bgduedate||$edduedate) {
                if ($invoicenumber) $invoicestr=' and apbill.invoicenumber='.sqlprep($invoicenumber);
                if ($vendorid) $vendorstr=' and apbill.vendorid='.sqlprep($vendorid);
                if ($bgdateofinvoice&&$eddateofinvoice) $dateofinvstr=' and apbill.dateofinvoice>='.sqlprep($bgdateofinvoice).' and apbill.dateofinvoice<='.sqlprep($eddateofinvoice);
                $passstr='continue='.$continue.'&&invoicenumber='.$invoicenumber.'&&vendorid='.$vendorid.'&&bgdateofinvoice='.$bgdateofinvoice.'&&eddateofinvoice='.$eddateofinvoice.'&&bgduedate='.$bgduedate.'&&edduedate='.$edduedate.'&&showall='.$showall;
                if ($bgduedate&&$edduedate) $duedatestr=' and apbill.duedate>='.sqlprep($bgduedate).' and apbill.duedate<='.sqlprep($edduedate);
                if (!$showall) $showstr=' and apbill.complete=0';
                if ($orderbyvend==1) {
                        $orderstr=' order by company.companyname,apbill.duedate';
                } elseif ($orderbyvend==2) {
                        $orderstr=' order by apbill.invoicenumber,company.companyname ';
                } elseif ($orderbyvend==3) {
                        $orderstr=' order by apbill.total,apbill.duedate ';
                } else {
                        $orderstr=' order by apbill.duedate,apbill.invoicenumber';
                };
                $recordSet = &$conn->Execute('select apbill.id,apbill.invoicenumber,apbill.description,apbill.entrydate,apbill.duedate,apbill.total,company.companyname,apbill.complete,sum(apbillpayment.amount*(apbillpayment.checkvoid!=0)),apbill.discountdate,apbill.discountamount from apbill,vendor,company left join apbillpayment on apbillpayment.apbillid=apbill.id where apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id and apbill.cancel=0 and apbill.gencompanyid='.sqlprep($active_company).$invoicestr.$vendorstr.$dateofinvstr.$duedatestr.$showstr.' group by apbill.id'.$orderstr);
                if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_BILLS_FOUND']));
                echo '<table border="1"><tr><th><a href="apbilllist.php?orderbyvend=1&&'.$passstr.'">'.$lang['STR_VENDOR'].'</a></th><th><a href="apbilllist.php?orderbyvend=0&&'.$passstr.'">'.$lang['STR_DUE_DATE'].'</a></th><th><a href="apbilllist.php?orderbyvend=2&&'.$passstr.'">'.$lang['STR_INVOICE'].'</a></th><th><a href="apbilllist.php?orderbyvend=3&&'.$passstr.'">'.$lang['STR_AMOUNT'].'</a></th><th>'.$lang['STR_DISCOUNT'].'</th><th>'.$lang['STR_PAID'].'</th><th>'.$lang['STR_NET_DUE'].'</th><th>'.$lang['STR_OPEN'].'</th></tr>';
                unset($oldvend);
                unset($vtot1);
                unset($vtot2);
                unset($vtot3);
                while (!$recordSet->EOF) {
                        if ($orderbyvend==1&&$oldvend<>$recordSet->fields[6]&&$oldvend) {
                             //do vendor totals
                             echo '<tr><th colspan="3" align="right">'.$oldvend.' '.$lang['STR_TOTALS'].'</th><th>'.CURRENCY_SYMBOL.checkdec($vtot1,PREFERRED_DECIMAL_PLACES).'</th>';
                             echo '<th>'.CURRENCY_SYMBOL.checkdec($vtot2,PREFERRED_DECIMAL_PLACES).'</th>';
                             echo '<th>'.CURRENCY_SYMBOL.checkdec($vtot3,PREFERRED_DECIMAL_PLACES).'</th>';
                             echo '<th>'.CURRENCY_SYMBOL.checkdec($vtot1-$vtot2-$vtot3,PREFERRED_DECIMAL_PLACES).'</th></tr>';
                             unset($vtot1);
                             unset($vtot2);
                             unset($vtot3);
                        };
                        if (strtotime($recordSet->fields[9])>=strtotime("now")) {
                                $discount=$recordSet->fields[10];
                        } else {
                                $discount=0;
                        };
                        if ($recordSet->fields[7]) {
                                $openstr='<font color="#FF0000">N</font>';
                        } else {
                                $openstr='<font color="#00FF00">Y</font>';
                        };
                        echo '<tr><td>'.$recordSet->fields[6].'</td><td>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[1].'</td><td>'.CURRENCY_SYMBOL.checkdec($recordSet->fields[5],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.checkdec($discount,PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.checkdec($recordSet->fields[8],PREFERRED_DECIMAL_PLACES).'</td><td>'.CURRENCY_SYMBOL.checkdec($recordSet->fields[5]-$discount-$recordSet->fields[8],PREFERRED_DECIMAL_PLACES).'</td><td>'.$openstr.'</td></tr>';
                        if ($detail) {
                                $recordSet2 = &$conn->Execute('select apbilldetail.amount, glaccount.name, glaccount.description, apbilldetail.invreceiveid from apbilldetail,glaccount where apbilldetail.glaccountid=glaccount.id and apbilldetail.apbillid='.sqlprep($recordSet->fields[0]));
                                while (!$recordSet2->EOF) {
                                        echo '<tr><td colspan="4" align="right">Account: '.$recordSet2->fields[1].' - '.$recordSet2->fields[2].'</td><td colspan="3" align="right">Amount: '.CURRENCY_SYMBOL.checkdec($recordSet2->fields[0],PREFERRED_DECIMAL_PLACES).'</td></tr>';
                                        $recordSet2->MoveNext();
                                };
                                echo '<tr><td colspan="7" align="right"> '.$lang['STR_INVOICE_TOTAL'].': '.CURRENCY_SYMBOL.checkdec($recordSet->fields[5]-$discount-$recordSet->fields[8],PREFERRED_DECIMAL_PLACES).'</td></tr>';
                                echo '<tr><td colspan="7">&nbsp;</td></tr>';
                        };
                        $tot+=$recordSet->fields[5]-$discount-$recordSet->fields[8];
                        $vtot1+=$recordSet->fields[5];
                        $vtot2+=$discount;
                        $vtot3+=$recordSet->fields[8];
                        $oldvend=$recordSet->fields[6] ;
                        $recordSet->MoveNext();
                };
                if ($orderbyvend==1&&$oldvend) {
                             //do vendor totals
                             echo '<tr><th colspan="3" align="right">'.$oldvend.' '.$lang['STR_TOTALS'].'</th><th>'.CURRENCY_SYMBOL.checkdec($vtot1,PREFERRED_DECIMAL_PLACES).'</th>';
                             echo '<th>'.CURRENCY_SYMBOL.checkdec($vtot2,PREFERRED_DECIMAL_PLACES).'</th>';
                             echo '<th>'.CURRENCY_SYMBOL.checkdec($vtot3,PREFERRED_DECIMAL_PLACES).'</th>';
                             echo '<th>'.CURRENCY_SYMBOL.checkdec($vtot1-$vtot2-$vtot3,PREFERRED_DECIMAL_PLACES).'</th></tr>';
                             unset($vtot1);
                             unset($vtot2);
                             unset($vtot3);
                };
                echo '<tr><td colspan="7" align="right">'.$lang['STR_NET_DUE_TOTAL'].': '.CURRENCY_SYMBOL.checkdec($tot,PREFERRED_DECIMAL_PLACES).'</td></tr>';
                echo '</table>';
                unset($continue);
        } else {
                echo '<form action="apbilllist.php" method="post" name="mainform"><table>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_NUMBER'].': </td align="'.TABLE_LEFT_SIDE_ALIGN.'"><td><input type="text" name="invoicenumber" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
                formapvendorselect('vendorid');
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_BEGIN_DATE'].': </td><td><input type="text" name="bgdateofinvoice"onchange="formatDate(this)" size="30" value="'.$monthago.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.bgdateofinvoice\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_END_DATE'].': </td><td><input type="text" name="eddateofinvoice" onchange="formatDate(this)" size="30" value="'.$today.'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.eddateofinvoice\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_DUE_DATE'].': </td><td><input type="text" name="bgduedate" onchange="formatDate(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.bgduedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_DUE_DATE'].': </td><td><input type="text" name="edduedate" onchange="formatDate(this)" size="30"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.edduedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="'.IMAGE_DATE_LOOKUP.'" border="0" alt="Display Calendar"></a></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHOW'].': </td><td><select name="showall"'.INC_TEXTBOX.'><option value="0">Open<option value="1">All</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"></td><td><select name="detail"'.INC_TEXTBOX.'><option value="0">'.$lang['STR_SUMMARY'].'<option value="1">Detail</select></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ORDER_BY'].'</td><td><select name="orderbyvend"'.INC_TEXTBOX.'>';
                echo '<option value="0">'.$lang['STR_DUE_DATE'].'';
                echo '<option value="1">'.$lang['STR_VENDOR'].'';
                echo '<option value="2">'.$lang['STR_INVOICE_NUMBER'].'</select></td></tr>';
                echo '</table><br><input type="submit" name="continue" value="'.$lang['STR_CONTINUE'].'"></form>';
                echo '</center>';
        };
?>

<?php include('includes/footer.php'); ?>
