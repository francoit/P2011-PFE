<?
  require_once('includes/defines.php');
  require_once('includes/adodb/adodb.inc.php');
  require_once('includes/functions.php');
  ADOLoadCode(DB_TYPE);
  $conn = &ADONewConnection();
  $conn->PConnect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);

// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)

    /*************** prchecks.php
    This script writes a check to a PDF file.
    *****************/

/*** Get a random number to use for the file name  ***/
mt_srand((double)microtime() * 1000000);
$rand_nbr = mt_rand();
if (!isset($font)) $font="Times-Roman";
if (!isset($basesize)) $basesize="16";

/*** Begin our pdf file  ***/
$filename=IMAGE_UPLOAD_DIR."tempprcheck$rand_nbr.pdf";
$pdf = pdf_new();
if (!pdf_open_file($pdf, "")) {
    print error;
    exit;
};
//begin pdf header info
pdf_set_parameter($pdf, "warning", "true");
pdf_set_info($pdf, "Creator", "prchecks.php");
pdf_set_info($pdf, "Author", "NOLA Software");
pdf_set_info($pdf, "Title", "Checks");


/*** Log into the database and set up our select statements ***/
if (isset($checknbr)) {
        $i=0;
        if (!is_array($checknbr)) $checknbr=array($checknbr);
        foreach ($checknbr as $data) { //allow multiple checks
                if ($i>0) $checkstr.=' or ';
                $checkstr.='chk.id='.sqlprep($data);
                $i++;
        };
        $checkstr='('.$checkstr.') and ';
        $query = "select company.companyname, company.address1, company.address2, company.city, company.state, company.zip, chk.amount, chk.checkdate, chk.amount, premployee.ssnumber, ".sqlprep($endorser).", chk.checknumber, chk.id, premplweek.prperiodid, premplweek.periodbegindate, premplweek.periodenddate, premplweek.federaltax, premplweek.ficatax, premplweek.statetax, premplweek.localtax, premplweek.citytax, premplweek.miscdeduction, premplweek.medicarededuction, premplweek.misctaxablepay, premplweek.miscnontaxablepay, premplweek.id, premplweek.prstateid, premplweek.prlocalid, premplweek.prcityid from chk, premployee, premplweek, company, checkacct where checkacct.id=chk.checkaccountid and ".$checkstr." chk.id=premplweek.checkid and premplweek.employeeid=premployee.id and premployee.companyid=company.id";
};
if (!isset($onlydep)) {
    $recordSet = &$conn->Execute($query);
    if ($recordSet->EOF) die(texterror('No entries found.<br>'));
    while (!$recordSet->EOF) {
            /*** Here's where we write to our PDF file  ***/
            pdf_begin_page($pdf, 612, 792);  //8.5x11
            pdf_set_font($pdf, $font, $basesize, "host");
            pdf_show_xy($pdf, chop($recordSet->fields[0]), 72, 666); //employeename
            pdf_show_xy($pdf, ucfirst(chop(numtotext($recordSet->fields[6]))), 72, 705); //amounttext
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
            pdf_show_xy($pdf, 'SSN #'.chop($recordSet->fields[9]), 315, 500); //ssn
            pdf_show_xy($pdf, 'SSN #'.chop($recordSet->fields[9]), 315, 224); //ssn
            pdf_show_xy($pdf, $recordSet->fields[14].' - '.$recordSet->fields[15], 230, 480); //period
            pdf_show_xy($pdf, $recordSet->fields[14].' - '.$recordSet->fields[15], 230, 204); //period
            pdf_show_xy($pdf, 'Net Pay: '.CURRENCY_SYMBOL.num_format($recordSet->fields[6],2), 410, 480); //netpay
            pdf_show_xy($pdf, 'Net Pay: '.CURRENCY_SYMBOL.num_format($recordSet->fields[6],2), 410, 204); //netpay
            pdf_set_font($pdf, $font, ($basesize-4), "host");
            pdf_show_xy($pdf, 'Federal Tax: '.CURRENCY_SYMBOL.num_format($recordSet->fields[16],2), 230, 460); //federaltax
            pdf_show_xy($pdf, 'Federal Tax: '.CURRENCY_SYMBOL.num_format($recordSet->fields[16],2), 230, 184); //federaltax
            pdf_show_xy($pdf, 'FICA: '.CURRENCY_SYMBOL.num_format($recordSet->fields[17],2), 230, 445); //ficatax
            pdf_show_xy($pdf, 'FICA: '.CURRENCY_SYMBOL.num_format($recordSet->fields[17],2), 230, 169); //ficatax
            pdf_show_xy($pdf, 'State Tax: '.CURRENCY_SYMBOL.num_format($recordSet->fields[18],2), 230, 430); //statetax
            pdf_show_xy($pdf, 'State Tax: '.CURRENCY_SYMBOL.num_format($recordSet->fields[18],2), 230, 154); //statetax
            pdf_show_xy($pdf, 'Local Tax: '.CURRENCY_SYMBOL.num_format($recordSet->fields[19],2), 230, 415); //localtax
            pdf_show_xy($pdf, 'Local Tax: '.CURRENCY_SYMBOL.num_format($recordSet->fields[19],2), 230, 139); //localtax
            pdf_show_xy($pdf, 'City Tax: '.CURRENCY_SYMBOL.num_format($recordSet->fields[20],2), 230, 400); //citytax
            pdf_show_xy($pdf, 'City Tax: '.CURRENCY_SYMBOL.num_format($recordSet->fields[20],2), 230, 124); //citytax
            pdf_show_xy($pdf, 'Misc. Deductions: '.CURRENCY_SYMBOL.num_format($recordSet->fields[21],2), 370, 385); //miscded
            pdf_show_xy($pdf, 'Misc. Deductions: '.CURRENCY_SYMBOL.num_format($recordSet->fields[21],2), 370, 109); //miscded
            pdf_show_xy($pdf, 'Medicare: '.CURRENCY_SYMBOL.num_format($recordSet->fields[22],2), 370, 370); //medicare
            pdf_show_xy($pdf, 'Medicare: '.CURRENCY_SYMBOL.num_format($recordSet->fields[22],2), 370, 94); //medicare
            pdf_show_xy($pdf, 'Misc. Taxable Pay: '.CURRENCY_SYMBOL.num_format($recordSet->fields[23],2), 30, 355); //misctaxpay
            pdf_show_xy($pdf, 'Misc. Taxable Pay: '.CURRENCY_SYMBOL.num_format($recordSet->fields[23],2), 30, 79); //misctaxpay
            pdf_show_xy($pdf, 'Misc. Non Taxable Pay: '.CURRENCY_SYMBOL.num_format($recordSet->fields[24],2), 30, 340); //miscnontaxpay
            pdf_show_xy($pdf, 'Misc. Non Taxable Pay: '.CURRENCY_SYMBOL.num_format($recordSet->fields[24],2), 30, 64); //miscnontaxpay
            pdf_set_font($pdf, $font, ($basesize-2), "host");
            pdf_show_xy($pdf, chop($recordSet->fields[0]), 20, 500); //vendorname
            pdf_show_xy($pdf, chop($recordSet->fields[0]), 20, 224); //vendorname
            pdf_show_xy($pdf, chop($endorser), 480, 550); //endorser
            pdf_show_xy($pdf, chop($recordSet->fields[7]), 480, 525); //date
            pdf_show_xy($pdf, chop($recordSet->fields[7]), 480, 249); //date
            pdf_show_xy($pdf, 'Check #'.chop($recordSet->fields[11]), 505, 506); //checknum
            pdf_show_xy($pdf, 'Check #'.chop($recordSet->fields[11]), 505, 230); //checknum
            if (strtotime($recordSet->fields[15])>=strtotime($recordSet->fields[7])) {
                    $discount=$recordSet->fields[12];
            } else {
                    $discount=0;
            };
            $recordSet2=&$conn->Execute('select sum(premplweekdeddetail.amount) from premplweekdeddetail where dedtype=0 and premplweekdeddetail.premplweekid='.sqlprep($recordSet->fields[25]));

            $ded=$recordSet2->fields[0]+$recordSet->fields[22]+$recordSet->fields[21]+$recordSet->fields[20]+$recordSet->fields[19]+$recordSet->fields[18]+$recordSet->fields[17]+$recordSet->fields[16];
            pdf_show_xy($pdf, chop('Gross Pay: '.CURRENCY_SYMBOL.num_format($recordSet->fields[6]+$ded,2)), 30, 294); //gross pay
            pdf_show_xy($pdf, chop('Gross Pay: '.CURRENCY_SYMBOL.num_format($recordSet->fields[6]+$ded,2)), 30, 18); //gross pay
            pdf_show_xy($pdf, chop('Total Ded: '.CURRENCY_SYMBOL.checkdec($ded,2)), 198, 294); //deductions
            pdf_show_xy($pdf, chop('Total Ded: '.CURRENCY_SYMBOL.checkdec($ded,2)), 198, 18); //deductions

            $recordSet2 = &$conn->Execute('select count(*) from premplweekpaydetail left join prpaytype on premplweekpaydetail.prpaytypeid=prpaytype.id left join prbended on premplweekpaydetail.prbendedid=prbended.id where premplweekpaydetail.premplweekid='.sqlprep($recordSet->fields[25]));
            if ($recordSet2->fields[0]>5) $sizemod=-2; //make type tiny if many payments
            pdf_set_font($pdf, $font, ($basesize-4+($sizemod)), "host");
            $recordSet2 = &$conn->Execute('select sum(premplweekpaydetail.qty), sum(premplweekpaydetail.amount), prpaytype.name, prbended.name,premplweekpaydetail.prbendedid from premplweekpaydetail left join prpaytype on premplweekpaydetail.prpaytypeid=prpaytype.id left join prbended on premplweekpaydetail.prbendedid=prbended.id where premplweekpaydetail.premplweekid='.sqlprep($recordSet->fields[25]).' group by prbended.name,prpaytype.name');
            $x1=35;
            $y1=485;
            while (!$recordSet2->EOF) {
                    if ($recordSet2->fields[4]==0) { //if payment is from hours worked
                        pdf_show_xy($pdf, chop($recordSet2->fields[2].' Hrs: '.$recordSet2->fields[0]), $x1, $y1);
                        pdf_show_xy($pdf, chop($recordSet2->fields[2].' Hrs: '.$recordSet2->fields[0]), $x1, ($y1-276));
                    } else  { //if payment is from a benefit
                        pdf_show_xy($pdf, chop($recordSet2->fields[3]), $x1, $y1);
                        pdf_show_xy($pdf, chop($recordSet2->fields[3]), $x1, ($y1-276));
                    };
                    pdf_show_xy($pdf, 'Pay: '.CURRENCY_SYMBOL.chop(num_format($recordSet2->fields[1],2)), ($x1+80), $y1);
                    pdf_show_xy($pdf, 'Pay: '.CURRENCY_SYMBOL.chop(num_format($recordSet2->fields[1],2)), ($x1+80), ($y1-276));
                    $y1-=15;
                    if (isset($sizemod)) $y1-=5;
                    $recordSet2->MoveNext();
            };
            unset($sizemod);
            $x1=370;
            $y1=355;
            $recordSet2=&$conn->Execute('select count(*) from premplweekdeddetail where premplweekdeddetail.dedtype=0 and premplweekdeddetail.premplweekid='.sqlprep($recordSet->fields[25]));
            if ($recordSet2->fields[0]>5) $sizemod=-2; //make type tiny if many deductions
            pdf_set_font($pdf, $font, ($basesize-4+($sizemod)), "host");
            $recordSet2=&$conn->Execute('select sum(premplweekdeddetail.amount),prempldeduction.description,prbended.name,prpension.name,prbended.bendedtype,premplweekdeddetail.prbendedid from premplweekdeddetail left join prempldeduction on premplweekdeddetail.prempldeductionid=prempldeduction.id left join prbended on premplweekdeddetail.prbendedid=prbended.id left join prpension on premplweekdeddetail.prpensionid=prpension.id where premplweekdeddetail.premplweekid='.sqlprep($recordSet->fields[25]).' group by prbended.name, prpension.name');
            while (!$recordSet2->EOF) {
                if ($recordSet2->fields[4]<>2&&$recordSet2->fields[0]<>0) {
                  $dedstr=$recordSet2->fields[1].$recordSet2->fields[2].$recordSet2->fields[3].': $'.num_format($recordSet2->fields[0],2);
                  pdf_show_xy($pdf, chop($dedstr), $x1, $y1); //other deductions
                  pdf_show_xy($pdf, chop($dedstr), $x1, $y1-276); //other deductions
                  $y1-=15;
                  if (isset($sizemod)) $y1-=5;
                };
                $recordSet2->MoveNext();
            };
            unset($sizemod);
            pdf_end_page($pdf);
            $prperiodid=$recordSet->fields[13];
            $periodbegindate=$recordSet->fields[14];
            $periodenddate=$recordSet->fields[15];
            $recordSet->MoveNext();
    };
};

if ($depcheck) {
    //do federal deposit check
    if (isset($onlydep)) {
        $recordSet = &$conn->SelectLimit('select chk.amount, company.companyname, company.address1, company.address2, company.city, company.state, company.zip, chk.checkdate, prcompany.fedtaxnum, '.sqlprep($endorser).', chk.checknumber from prdepositchecks, chk, vendor, company, prcompany where prcompany.id='.sqlprep($active_company).' and prcompany.depositvendorid=vendor.id and vendor.paytocompanyid=company.id  and prdepositchecks.gencompanyid='.sqlprep($active_company).' and '.$checkstr.' prdepositchecks.checkid=chk.id',1);
    } else {
        $recordSet = &$conn->SelectLimit('select chk.amount, company.companyname, company.address1, company.address2, company.city, company.state, company.zip, chk.checkdate, prcompany.fedtaxnum, '.sqlprep($endorser).', chk.checknumber from prdepositchecks, chk, vendor, company, prcompany where prcompany.id='.sqlprep($active_company).' and prcompany.depositvendorid=vendor.id and vendor.paytocompanyid=company.id and prdepositchecks.checkid=chk.id and prdepositchecks.prperiodid='.sqlprep($prperiodid).' and prdepositchecks.periodbegindate='.sqlprep($periodbegindate).' and prdepositchecks.periodenddate='.sqlprep($periodenddate).' and prdepositchecks.gencompanyid='.sqlprep($active_company),1);
    };
    if (!$recordSet->EOF) {
            /*** Here's where we write to our PDF file  ***/
            pdf_begin_page($pdf, 612, 792);  //8.5x11
            pdf_set_font($pdf, $font, $basesize, "host");
            pdf_show_xy($pdf, chop($recordSet->fields[1]), 72, 666); //fed vendorname
            pdf_show_xy($pdf, ucfirst(chop(numtotext($recordSet->fields[0]))), 72, 705); //amounttext
            pdf_show_xy($pdf, '$'.chop($recordSet->fields[0]), 512, 675); //amount
            pdf_show_xy($pdf, chop('Total: '.CURRENCY_SYMBOL.checkdec($recordSet->fields[0],2)), 468, 294); //total
            pdf_show_xy($pdf, chop('Total: '.CURRENCY_SYMBOL.checkdec($recordSet->fields[0],2)), 468, 18); //total
            pdf_set_font($pdf, $font, ($basesize-2), "host");
            pdf_show_xy($pdf, chop($recordSet->fields[2]), 72, 650); //vendoraddress1
            pdf_show_xy($pdf, chop($recordSet->fields[3]), 72, 634); //vendoraddress2
            pdf_show_xy($pdf, chop($recordSet->fields[4].','), 72, 620); //vendorcity
            pdf_show_xy($pdf, chop($recordSet->fields[5]), 216, 620); //vendorstate
            pdf_show_xy($pdf, chop($recordSet->fields[6]), 250, 620); //vendorzip
            pdf_show_xy($pdf, chop($recordSet->fields[7]), 370, 675); //date
            pdf_show_xy($pdf, 'Federal ID #'.chop($recordSet->fields[8]), 315, 500); //account
            pdf_show_xy($pdf, 'Federal ID #'.chop($recordSet->fields[8]), 315, 224); //account
            pdf_show_xy($pdf, chop($recordSet->fields[1]), 20, 500); //vendorname
            pdf_show_xy($pdf, chop($recordSet->fields[1]), 20, 224); //vendorname
            pdf_show_xy($pdf, chop($recordSet->fields[7]), 480, 525); //date
            pdf_show_xy($pdf, chop($recordSet->fields[7]), 480, 249); //date
            pdf_show_xy($pdf, chop($endorser), 480, 550); //endorser
            pdf_show_xy($pdf, 'Check #'.chop($recordSet->fields[10]), 505, 506); //checknum
            pdf_show_xy($pdf, 'Check #'.chop($recordSet->fields[10]), 505, 230); //checknum
//            pdf_set_font($pdf, $font, ($basesize-6), "host");
//            pdf_show_xy($pdf, chop('Amount'), 171, 485);
//            pdf_show_xy($pdf, chop('Amount'), 171, 209);
            $x1=30;
            $y1=470;
            pdf_end_page($pdf);
    };
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
