<?
  require_once('includes/defines.php');
  require_once('includes/adodb/adodb.inc.php');
  require_once('includes/functions.php');

// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)


  ADOLoadCode(DB_TYPE);
  $conn = &ADONewConnection();
  $conn->PConnect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);

    /*************** checks.php
    This script writes a check to an PDF file.
    *****************/

/*** Get a random number to use for the file name  ***/
mt_srand((double)microtime() * 1000000);
$rand_nbr = mt_rand();
if (!isset($font)) $font="Times-Roman";
if (!isset($basesize)) $basesize="16";

/*** Begin our pdf file  ***/
$filename=IMAGE_UPLOAD_DIR."tempcheck$rand_nbr.pdf";
$pdf = pdf_new();
if (!pdf_open_file($pdf, "")) {
    print error;
    exit;
};
//begin pdf header info
pdf_set_parameter($pdf, "warning", "true");
pdf_set_info($pdf, "Creator", "checks.php");
pdf_set_info($pdf, "Author", "NOLA Software");
pdf_set_info($pdf, "Title", "Checks");


/*** Log into the database and set up our select statements ***/
if (isset($checknbr)) {
        $i=0;
        if (!is_array($checknbr)) $checknbr=array($checknbr);
        foreach ($checknbr as $data) {
                if ($i>0) $checkstr.=' or ';
                $checkstr.='chk.id='.sqlprep($data);
                $i++;
        };
        $checkstr='('.$checkstr.') and ';
        $query = "select company.companyname, company.address1, company.address2, company.city, company.state, company.zip, chk.amount, chk.checkdate, chk.amount, vendor.customeraccount, ".sqlprep($endorser).", chk.chknumber, apbill.discountamount, '0.00', '0.00', apbill.discountdate, apbill.id, chk.id from chk, apbillpayment, apbill, vendor, company, checkacct where checkacct.id=chk.checkaccountid and ".$checkstr." chk.id=apbillpayment.checkid and apbillpayment.apbillid=apbill.id and apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id";
};

$recordSet = &$conn->Execute($query);
if ($recordSet->EOF) die(texterror('No entries found.<br>'.$query));
while (!$recordSet->EOF) {
        /*** Here's where we write to our PDF file  ***/
        pdf_begin_page($pdf, 612, 792);  //8.5x11
        pdf_set_font($pdf, $font, $basesize, "host");
        pdf_show_xy($pdf, chop($recordSet->fields[0]), 72, 666); //vendorname
        pdf_show_xy($pdf, chop(numtotext($recordSet->fields[6])), 72, 705); //amounttext
        pdf_show_xy($pdf, CURRENCY_SYMBOL.chop($recordSet->fields[8]), 512, 675); //amount
        pdf_show_xy($pdf, chop('Total: '.CURRENCY_SYMBOL.checkdec($recordSet->fields[8],2)), 468, 294); //total
        pdf_show_xy($pdf, chop('Total: '.CURRENCY_SYMBOL.checkdec($recordSet->fields[8],2)), 468, 18); //total
        pdf_set_font($pdf, $font, ($basesize-2), "host");
        pdf_show_xy($pdf, chop($recordSet->fields[1]), 72, 650); //vendoraddress1
        pdf_show_xy($pdf, chop($recordSet->fields[2]), 72, 634); //vendoraddress2
        pdf_show_xy($pdf, chop($recordSet->fields[3].','), 72, 620); //vendorcity
        pdf_show_xy($pdf, chop($recordSet->fields[4]), 216, 620); //vendorstate
        pdf_show_xy($pdf, chop($recordSet->fields[5]), 250, 620); //vendorzip
        pdf_show_xy($pdf, chop($recordSet->fields[7]), 370, 675); //date
        pdf_show_xy($pdf, 'Account #'.chop($recordSet->fields[9]), 315, 500); //account
        pdf_show_xy($pdf, 'Account #'.chop($recordSet->fields[9]), 315, 224); //account
        pdf_show_xy($pdf, chop($recordSet->fields[0]), 20, 500); //vendorname
        pdf_show_xy($pdf, chop($recordSet->fields[0]), 20, 224); //vendorname
        pdf_show_xy($pdf, chop($recordSet->fields[7]), 455, 490); //date
        pdf_show_xy($pdf, chop($recordSet->fields[7]), 480, 249); //date
        pdf_show_xy($pdf, chop($recordSet->fields[10]), 480, 525); //auth
        pdf_show_xy($pdf, 'Check #'.chop($recordSet->fields[11]), 505, 506); //checknum
        pdf_show_xy($pdf, 'Check #'.chop($recordSet->fields[11]), 505, 230); //checknum
        if (strtotime($recordSet->fields[15])>=strtotime($recordSet->fields[7])) {
                $discount=$recordSet->fields[12];
        } else {
                $discount=0;
        };
        pdf_show_xy($pdf, chop('Discounts Taken: '.CURRENCY_SYMBOL.checkdec($discount,2)), 30, 294); //discount
        pdf_show_xy($pdf, chop('Discounts Taken: '.CURRENCY_SYMBOL.checkdec($discount,2)), 30, 18); //discount
        pdf_show_xy($pdf, chop('Interest Paid: '.CURRENCY_SYMBOL.checkdec($recordSet->fields[13],2)), 198, 294); //interest
        pdf_show_xy($pdf, chop('Interest Paid: '.CURRENCY_SYMBOL.checkdec($recordSet->fields[13],2)), 198, 18); //interest
        pdf_show_xy($pdf, chop('Credits Used: '.CURRENCY_SYMBOL.checkdec($recordSet->fields[14],2)), 324, 294); //interest
        pdf_show_xy($pdf, chop('Credits Used: '.CURRENCY_SYMBOL.checkdec($recordSet->fields[14],2)), 324, 18); //interest
        pdf_set_font($pdf, $font, ($basesize-6), "host");
        pdf_show_xy($pdf, chop('Invoice'), 30, 485);
        pdf_show_xy($pdf, chop('Invoice'), 30, 209);
        pdf_show_xy($pdf, chop('Amount'), 171, 485);
        pdf_show_xy($pdf, chop('Amount'), 171, 209);
        $x1=30;
        $y1=470;
        pdf_set_font($pdf, $font, ($basesize-2), "host");
        $recordSet2 = &$conn->Execute('select apbillpayment.amount, apbill.invoicenumber from apbillpayment, apbill where apbillpayment.checkid='.sqlprep($recordSet->fields[17]).' and apbillpayment.apbillid=apbill.id');
        while (!$recordSet2->EOF) {
                if ($y1==325) {
                    $x1+=20;
                    $y1=485;
                    pdf_set_font($pdf, $font, ($basesize-6), "host");
                    pdf_show_xy($pdf, chop('Invoice'), $x1, $y1);
                    pdf_show_xy($pdf, chop('Invoice'),$x1, ($y1-276));
                    pdf_show_xy($pdf, chop('Amount'), ($x1+141), $y1);
                    pdf_show_xy($pdf, chop('Amount'), ($x1+141), ($y1-276));
                    pdf_set_font($pdf, $font, ($basesize-2), "host");
                };
                pdf_show_xy($pdf, chop($recordSet2->fields[1]), $x1, $y1);
                pdf_show_xy($pdf, chop($recordSet2->fields[1]), $x1, ($y1-276));
                pdf_show_xy($pdf, CURRENCY_SYMBOL.chop($recordSet2->fields[0]), ($x1+141), $y1);
                pdf_show_xy($pdf, CURRENCY_SYMBOL.chop($recordSet2->fields[0]), ($x1+141), ($y1-276));
                $y1=$y1-15;
                $recordSet2->MoveNext();
        };
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
