<?
  require_once('includes/defines.php');
  require_once('includes/adodb/adodb.inc.php');
  require_once('includes/functions.php');

// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)


  ADOLoadCode(DB_TYPE);
  $conn = &ADONewConnection();
  $conn->PConnect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);

    /*************** arinvoicestateview.php
    This script writes ar invoices to an PDF file.
    *****************/
    $timestamp =  time();
    $today=date("Y-m-d", $timestamp);

/*** Get a random number to use for the file name  ***/
mt_srand((double)microtime() * 1000000);
$rand_nbr = mt_rand();
if (!isset($font)) $font="Times-Roman";
if (!isset($basesize)) $basesize="16";

/*** Begin our pdf file  ***/
$filename=IMAGE_UPLOAD_DIR."tempinv$rand_nbr.pdf";
$pdf = pdf_new();
if (!pdf_open_file($pdf, "")) {
    print error;
    exit;
};


//begin pdf header info
pdf_set_parameter($pdf, "warning", "true");
pdf_set_info($pdf, "Creator", "arinvoicestateview.php");
pdf_set_info($pdf, "Author", "NOLA Software");
pdf_set_info($pdf, "Title", "Invoice Statements");


unset($invstr);
unset($companystr);
unset($daysstr);
if ($inv) $invstr.=' and arinvoice.duedate<=NOW()';
if ($customerid) {
    $recordSet = $conn->Execute("select companyid from customer where id=".sqlprep($customerid));
    if ($recordSet&&!$recordSet->EOF) $obcompanyid=$recordSet->fields[0];
    if ($obcompanyid) $companystr=' and arinvoice.orderbycompanyid='.sqlprep($obcompanyid);
};
if ($invdays) $daysstr.=' and arinvoice.invoicedate<=DATE_SUB('.sqlprep($today).', INTERVAL '.sqlprep($invdays).' DAY)';
$recordSet = $conn->Execute("select distinct customer.id, customer.companyid, company.companyname, customer.billtoattnname, company.address1, company.address2, company.city, company.state, company.zip from arinvoice,customer,company where customer.companyid=arinvoice.orderbycompanyid and customer.companyid=company.id and arinvoice.status<2 and arinvoice.cancel=0".$companystr.$invstr.$daysstr.' order by customer.id');
if (!$recordSet||$recordSet->EOF) die(texterror('No matching invoices found.'));
$recordSet2 = $conn->Execute("select customer.id, arinvoice.invoicenumber, arinvoice.orderbycompanyid,arinvoice.invoicetotal,arinvoice.invoicedate,arinvoice.accruedinterest from arinvoice left join customer on customer.companyid=arinvoice.orderbycompanyid where arinvoice.status<2 and arinvoice.status>=0 and arinvoice.cancel=0".$companystr.' order by customer.id, arinvoice.invoicedate');
if (!$recordSet2||$recordSet2->EOF) die(texterror('No matching invoices found.'));
$recordSet3 = $conn->Execute("select customer.id, arinvoice.invoicenumber, arinvoicepaymentdetail.amount, arinvoicepaymentdetail.datereceived from arinvoice,arinvoicepaymentdetail left join customer on customer.companyid=arinvoice.orderbycompanyid where arinvoice.id=arinvoicepaymentdetail.invoiceid and arinvoice.status<2 and arinvoice.status>=0 and arinvoice.cancel=0".$companystr.' order by customer.id, arinvoice.invoicedate');

while (!$recordSet->EOF) {
        $customerid=$recordSet->fields[0];
        unset($custamount);
        /*** Here's where we write to our PDF file  ***/
        pdf_begin_page($pdf, 612, 792);  //8.5x11
        if (strlen($companyname)<30) {
            pdf_set_font($pdf, $font, $basesize+8, "host");
        } else {
            pdf_set_font($pdf, $font, $basesize+6, "host");
        };
        pdf_show_xy($pdf, 'Statement From '.$companyname, 54, 736);
        pdf_set_font($pdf, $font, $basesize-2, "host");
        pdf_show_xy($pdf, 'Date: '.chop(createtime('m/d/Y')), 428, 684);
        if ($recordSet->fields[0]) pdf_continue_text($pdf, 'Account: '.chop($recordSet->fields[0])); //customerid
        pdf_show_xy($pdf, chop($recordSet->fields[2]), 126, 684); //customername
        if ($recordSet->fields[3]) pdf_continue_text($pdf, 'Attn: '.chop($recordSet->fields[3])); //attn
        if ($recordSet->fields[4]) pdf_continue_text($pdf, chop($recordSet->fields[4])); //addr1
        if ($recordSet->fields[5]) pdf_continue_text($pdf, chop($recordSet->fields[5])); //addr2
        if ($recordSet->fields[6]||$recordSet->fields[7]||$recordSet->fields[8]) pdf_continue_text($pdf, chop($recordSet->fields[6]).', '.chop($recordSet->fields[7]).' '.chop($recordSet->fields[8])); //city,state,zip

        pdf_set_font($pdf, $font, $basesize, "host");
        pdf_setcolor($pdf, 'fill', 'gray', 0.75); //set color to 30% gray
        pdf_rect ($pdf, 54, 561, 72, 15);
        pdf_rect ($pdf, 126, 561, 360, 15);
        pdf_rect ($pdf, 486, 561, 72, 15);
        pdf_fill_stroke ($pdf);

        pdf_setcolor($pdf, 'fill', 'gray', 0); //set color to black
        pdf_set_font($pdf, $font, $basesize-1, "host");
        unset($width);
        $width=pdf_stringwidth($pdf, 'Date');
        pdf_show_xy($pdf, 'Date', (126+54-$width)/2, 565); //date
        unset($width);
        $width=pdf_stringwidth($pdf, 'Charges and Credits');
        pdf_show_xy($pdf, 'Charges and Credits', (486+126-$width)/2, 565); //charges
        unset($width);
        $width=pdf_stringwidth($pdf, 'Amount');
        pdf_show_xy($pdf, 'Amount', (558+486-$width)/2, 565); //amount

        pdf_set_font($pdf, $font, $basesize-2, "host");
        $i=1;
        while ($customerid==$recordSet2->fields[0]||$customerid==$recordSet3->fields[0]) {
            unset($customerid2);
            unset($entrydate2);
            if (!$recordSet2->EOF) {
                $customerid2=$recordSet2->fields[0];
                $entrydate2=$recordSet2->fields[4]; //invoice date
                $amount2=$recordSet2->fields[3];
            };
            unset($customerid3);
            unset($entrydate3);
            if (!$recordSet3->EOF) {
                $customerid3=$recordSet3->fields[0];
                $entrydate3=$recordSet3->fields[3]; //payment date
                $amount3=$recordSet3->fields[2];
            };
            unset($amount);
            unset($entrydate);
            unset($desc);
            if ($customerid==$customerid2&&$customerid==$customerid3) {   //payment and invoice for customer
                if ($entrydate2>$entrydate3) {  //invoice date after payment date then print payment next
                    $entrydate=$entrydate3;
                    $amount=$amount3.'CR';
                    $custamount-=$amount3;
                    $desc='Payment - Invoice #'.$recordSet3->fields[1].' - Thank you!';
                    $recordSet3->MoveNext();
                } else { //print invoice info.
                    $entrydate=$entrydate2;
                    $amount=$amount2;
                    $custamount+=$amount2;
                    $interest+=$recordSet2->fields[5];
                    $desc='Invoice #'.$recordSet2->fields[1];
                    $recordSet2->MoveNext();
                };
            } elseif ($customerid==$customerid2) { //only invoice for customer
                    $entrydate=$entrydate2;
                    $amount=$amount2;
                    $custamount+=$amount2;
                    $interest+=$recordSet2->fields[5];
                    $desc='Invoice #'.$recordSet2->fields[1];
                    $recordSet2->MoveNext();
            } elseif ($customerid==$customerid3) { //only payment for customer
                    $entrydate=$entrydate3;
                    $amount=$amount3.'CR';
                    $custamount-=$amount3;
                    $desc='Payment - Invoice #'.$recordSet3->fields[1].' - Thank you!';
                    $recordSet3->MoveNext();
            };
            if ($amount) {
                pdf_rect ($pdf, 54, 561-(15*$i), 72, 15);
                pdf_rect ($pdf, 126, 561-(15*$i), 360, 15);
                pdf_rect ($pdf, 486, 561-(15*$i), 72, 15);
                pdf_stroke ($pdf);
                unset($width);
                $width=pdf_stringwidth($pdf, $entrydate);
                pdf_show_xy($pdf, $entrydate, (126+54-$width)/2, 565-(15*$i)); //date
                unset($width);
                $width=pdf_stringwidth($pdf, $desc);
                pdf_show_xy($pdf, $desc, 130, 565-(15*$i)); //charges
                unset($width);
                $width=pdf_stringwidth($pdf, CURRENCY_SYMBOL.$amount);
                pdf_show_xy($pdf, CURRENCY_SYMBOL.$amount, 554-$width, 565-(15*$i)); //amount
                $i++;
            };
        };
        if ($interest) {
            $width=pdf_stringwidth($pdf, 'Interest: ');
            pdf_show_xy($pdf, 'Interest: ', 486-$width, 565-(15*$i)); //charges
            pdf_rect ($pdf, 486, 561-(15*$i), 72, 15);
            pdf_stroke ($pdf);
            unset($width);
            $width=pdf_stringwidth($pdf, CURRENCY_SYMBOL.num_format($interest,PREFERRED_DECIMAL_PLACES));
            pdf_show_xy($pdf, CURRENCY_SYMBOL.num_format($interest,PREFERRED_DECIMAL_PLACES), 554-$width, 565-(15*$i)); //amount
            $i++;
        };
        unset($width);
        if ($custamount+$interest<0) {
            $desc='Credit: ';
        } else {
            $desc='Balance Due: ';
        };
        $width=pdf_stringwidth($pdf, $desc);
        pdf_show_xy($pdf, $desc, 486-$width, 565-(15*$i)); //charges
        pdf_rect ($pdf, 486, 561-(15*$i), 72, 15);
        pdf_stroke ($pdf);
        unset($width);
        $width=pdf_stringwidth($pdf, CURRENCY_SYMBOL.num_format($custamount+$interest,PREFERRED_DECIMAL_PLACES));
        pdf_show_xy($pdf, CURRENCY_SYMBOL.num_format($custamount+$interest,PREFERRED_DECIMAL_PLACES), 554-$width, 565-(15*$i)); //amount
        $i++;

        pdf_set_font($pdf, $font, $basesize-2, "host");
        unset($width);
        $width=pdf_stringwidth($pdf, 'Current Balance: ');
        pdf_show_xy($pdf, 'Current Balance: ', 450-$width, 628); //balance
        unset($width);
        $width=pdf_stringwidth($pdf, 'Amount Enclosed: ');
        pdf_show_xy($pdf, 'Amount Enclosed: ', 450-$width, 610); //amount
        pdf_rect ($pdf, 450, 624, 108, 18);
        pdf_rect ($pdf, 450, 606, 108, 18);
        pdf_stroke ($pdf);
        pdf_set_font($pdf, $font, $basesize, "host");
        unset($width2);
        $width2=pdf_stringwidth($pdf, CURRENCY_SYMBOL.num_format($custamount+$interest,PREFERRED_DECIMAL_PLACES));
        pdf_show_xy($pdf, CURRENCY_SYMBOL.num_format($custamount+$interest,PREFERRED_DECIMAL_PLACES), 446+$width-$width2, 628); //balance
        pdf_show_xy($pdf, CURRENCY_SYMBOL, 454, 610); //amount

        $timestamp=time();
        $date_time_array=getdate($timestamp);
        $hours=$date_time_array["hours"];
        $minutes=$date_time_array["minutes"];
        $seconds=$date_time_array["seconds"];
        $month=$date_time_array["mon"];
        $day=$date_time_array["mday"];
        $year=$date_time_array["year"];
        $timestamp=mktime($hour, $minute, $second, $month, $day, $year);
        $today=date("Y-m-d", $timestamp);
        $timestamp=mktime($hour, $minute, $second, $month, $day-31, $year);
        $monthago=date("Y-m-d", $timestamp);
        $timestamp=mktime($hour, $minute, $second, $month, $day-61, $year);
        $twomonthago=date("Y-m-d", $timestamp);
        $timestamp=mktime($hour, $minute, $second, $month, $day-91, $year);
        $threemonthago=date("Y-m-d", $timestamp);
        $timestamp=mktime($hour, $minute, $second, $month, $day-121, $year);
        $fourmonthago=date("Y-m-d", $timestamp);
        $recordSet4 = &$conn->Execute('select sum(arinvoice.invoicetotal)-sum(arinvoicepaymentdetail.amount) from arinvoice left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where arinvoice.orderbycompanyid='.sqlprep($recordSet->fields[1]).' and arinvoice.invoicedate>'.sqlprep($monthago).' and arinvoice.status<2 and arinvoice.cancel=0');
        if ($recordSet4&&!$recordSet4->EOF) if ($recordSet4->fields[0]) $current=$recordSet4->fields[0];
        $recordSet4 = &$conn->Execute('select sum(arinvoice.invoicetotal)-sum(arinvoicepaymentdetail.amount) from arinvoice left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where arinvoice.orderbycompanyid='.sqlprep($recordSet->fields[1]).' and arinvoice.invoicedate>'.sqlprep($twomonthago).' and arinvoice.invoicedate<='.sqlprep($monthago).' and arinvoice.status<2 and arinvoice.cancel=0');
        if ($recordSet4&&!$recordSet4->EOF) if ($recordSet4->fields[0]) $monthagototal=$recordSet4->fields[0];
        $recordSet4 = &$conn->Execute('select sum(arinvoice.invoicetotal)-sum(arinvoicepaymentdetail.amount) from arinvoice left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where arinvoice.orderbycompanyid='.sqlprep($recordSet->fields[1]).' and arinvoice.invoicedate>'.sqlprep($threemonthago).' and arinvoice.invoicedate<='.sqlprep($twomonthago).' and arinvoice.status<2 and arinvoice.cancel=0');
        if ($recordSet4&&!$recordSet4->EOF) if ($recordSet4->fields[0]) $twomonthagototal=$recordSet4->fields[0];
        $recordSet4 = &$conn->Execute('select sum(arinvoice.invoicetotal)-sum(arinvoicepaymentdetail.amount) from arinvoice left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where arinvoice.orderbycompanyid='.sqlprep($recordSet->fields[1]).' and arinvoice.invoicedate>'.sqlprep($fourmonthago).' and arinvoice.invoicedate<='.sqlprep($threemonthago).' and arinvoice.status<2 and arinvoice.cancel=0');
        if ($recordSet4&&!$recordSet4->EOF) if ($recordSet4->fields[0]) $threemonthagototal=$recordSet4->fields[0];
        $recordSet4 = &$conn->Execute('select sum(arinvoice.invoicetotal)-sum(arinvoicepaymentdetail.amount) from arinvoice left join arinvoicepaymentdetail on arinvoice.id=arinvoicepaymentdetail.invoiceid where arinvoice.orderbycompanyid='.sqlprep($recordSet->fields[1]).' and arinvoice.invoicedate<='.sqlprep($fourmonthago).' and arinvoice.status<2 and arinvoice.cancel=0');
        if ($recordSet4&&!$recordSet4->EOF) if ($recordSet4->fields[0]) $overthreemonthagototal=$recordSet4->fields[0];

        if ($current||$monthagototal||$twomonthagototal||$threemonthagototal||$overthreemonthtotal) {
            pdf_show_xy($pdf, 'Current', 72, 72);
            pdf_show_xy($pdf, CURRENCY_SYMBOL.num_format($current+$interest,PREFERRED_DECIMAL_PLACES), 72, 52);
        };
        if ($monthagototal||$twomonthagototal||$threemonthagototal||$overthreemonthtotal) {
            pdf_show_xy($pdf, '30 Days', 172, 72);
            pdf_show_xy($pdf, CURRENCY_SYMBOL.num_format($monthagototal,PREFERRED_DECIMAL_PLACES), 172, 52);
        };
        if ($twomonthagototal||$threemonthagototal||$overthreemonthtotal) {
            pdf_show_xy($pdf, '60 Days', 272, 72);
            pdf_show_xy($pdf, CURRENCY_SYMBOL.num_format($twomonthagototal,PREFERRED_DECIMAL_PLACES), 272, 52);
        };
        if ($threemonthagototal||$overthreemonthtotal) {
            pdf_show_xy($pdf, '90 Days', 372, 72);
            pdf_show_xy($pdf, CURRENCY_SYMBOL.num_format($threemonthagototal,PREFERRED_DECIMAL_PLACES), 372, 52);
        };
        if ($overmonthagototal) {
            pdf_show_xy($pdf, '120 + Days', 472, 72);
            pdf_show_xy($pdf, CURRENCY_SYMBOL.num_format($overthreemonthagototal,PREFERRED_DECIMAL_PLACES), 172, 52);
        };
        unset($addrstr);
        $recordSet4 = &$conn->Execute('select address1, address2, city, state, zip from gencompany where id='.sqlprep($active_company));
        if ($recordSet4&&!$recordSet4->EOF) {
            $addrstr.=$recordSet4->fields[0].' ';
            if ($recordSet4->fields[1]) $addrstr.=$recordSet4->fields[1].' ';
            $addrstr.=$recordSet4->fields[2].', '.$recordSet4->fields[3].' '.$recordSet4->fields[4];
        };
        pdf_set_font($pdf, $font, $basesize-6, "host");
        $width=pdf_stringwidth($pdf, 'Please remit to: '.$companyname.' '.$addrstr);
        pdf_show_xy($pdf, 'Please remit to: '.$companyname.' '.$addrstr, (612-$width)/2, 24);

        pdf_end_page($pdf);
        $recordSet->MoveNext();
};

/*  And now refresh to the populated PDF file  */
pdf_close($pdf);
$buf = pdf_get_buffer($pdf);
$len = strlen($buf);
$fp = fopen ($filename, "w");
fputs($fp,$buf,$len);
fclose($fp);
pdf_delete($pdf);
echo '<META HTTP-EQUIV="Refresh" CONTENT="0;URL=http://'.getenv(SERVER_NAME).'/'.$filename.'">';
?>
