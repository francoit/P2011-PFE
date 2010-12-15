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
$filename=IMAGE_UPLOAD_DIR."tempw2$rand_nbr.pdf";
$pdf = pdf_new();
if (!pdf_open_file($pdf, "")) {
    print error;
    exit;
};
//begin pdf header info
pdf_set_parameter($pdf, "warning", "true");
pdf_set_info($pdf, "Creator", "prw2print.php");
pdf_set_info($pdf, "Author", "NOLA Software");
pdf_set_info($pdf, "Title", "W2");
    $recordSet = &$conn->Execute('select prcompany.w2companyname,prcompany.w2companyaddress1,prcompany.w2companyaddress2,prcompany.w2citystatezip,prcompany.fedtaxnum,prcompany.stateunemplnum from prcompany where id='.sqlprep($active_company));
    if (!$recordSet->EOF) {
        $companynameaddress1=$recordSet->fields[0];
        $companynameaddress2=$recordSet->fields[1];
        $companynameaddress3=$recordSet->fields[2];
        $companynameaddress4=$recordSet->fields[3];
        $fedtaxnum=$recordSet->fields[4];
        $statetaxnum=$recordSet->fields[5];
    };
    $recordSet2=&$conn->Execute('select prfederal.maxwagesfica, prfederal.maxwagesmedicare from prfederal where gencompanyid='.sqlprep($active_company));
    if (!$recordSet2->EOF) { // read federal max's for medicare & fica
      $maxwagesfica=$recordSet2->fields[0];
      $maxwagesmed=$recordSet2->fields[1];
    };
//    $recordSet = &$conn->Execute('select distinct premployee.firstname,premployee.lastname,company.address1,company.address2,company.city,company.state,company.zip,premployee.ssnumber,sum(premplweek.tipsaswages)+sum(premplweek.misctaxablepay),sum(premplweek.federaltax),sum(premplweek.ficatax),sum(premplweek.medicarededuction),sum(premplweek.statetax),sum(premplweek.citytax),genstate.stateinit,prcity.abrev,premplweek.employeeid from premployee cross join premplweek cross join chk cross join company left join prstate on premplweek.prstateid=prstate.id and prstate.cancel=0 left join genstate on genstate.id=prstate.genstateid left join prcity on premplweek.prcityid=prcity.id and prcity.cancel=0 where premployee.companyid=company.id and premplweek.checkid=chk.id and extract(year from chk.checkdate)='.sqlprep($w2year).' and premplweek.employeeid=premployee.id group by premplweek.employeeid,premplweek.prstateid,premplweek.prlocalid,premplweek.prcityid,premployee.firstname,premployee.lastname,company.address1,company.address2,company.city,company.state,company.zip,premployee.ssnumber,genstate.stateinit,prcity.abrev order by premployee.lastname,premployee.firstname');
    $recordSet = &$conn->Execute('select distinct premployee.firstname,premployee.lastname,company.address1,company.address2,company.city,company.state,company.zip,premployee.ssnumber,sum(premplweek.tipsaswages)+sum(premplweek.misctaxablepay),sum(premplweek.federaltax),sum(premplweek.ficatax),sum(premplweek.medicarededuction),sum(premplweek.statetax),sum(premplweek.citytax),genstate.stateinit,prcity.abrev,premplweek.employeeid from premployee cross join premplweek cross join chk cross join company left join prstate on premplweek.prstateid=prstate.id and prstate.cancel=0 left join genstate on genstate.id=prstate.genstateid left join prcity on premplweek.prcityid=prcity.id and prcity.cancel=0 where premployee.companyid=company.id and premplweek.checkid=chk.id and extract(year from chk.checkdate)='.sqlprep($w2year).' and premplweek.employeeid=premployee.id group by premplweek.employeeid,premplweek.prstateid,premplweek.prlocalid,premplweek.prcityid,premployee.firstname,premployee.lastname,company.address1,company.address2,company.city,company.state,company.zip,premployee.ssnumber,genstate.stateinit,prcity.abrev order by premployee.lastname,premployee.firstname');
    while ($recordSet&&!$recordSet->EOF) {
            $recordSet2 = &$conn->Execute('select sum(premplweekpaydetail.amount) from premplweekpaydetail,premplweek,chk where premplweek.checkid=chk.id and premplweek.id=premplweekpaydetail.premplweekid and premplweek.cancel=0 and premplweek.employeeid='.sqlprep($recordSet->fields[16]).' and extract(year from chk.checkdate)='.sqlprep($w2year));
            /*** Here's where we write to our PDF file  ***/
            pdf_begin_page($pdf, 612, 792);  //8.5x11
            pdf_set_font($pdf, $font, $basesize-2, "host");
            $ymod=396;
            pdf_show_xy($pdf, chop($fedtaxnum), 72, 335+$ymod); //fedtaxnum
            pdf_set_font($pdf, $font, $basesize-4, "host");
            pdf_show_xy($pdf, chop($companynameaddress1), 72, 305+$ymod); //company name
            pdf_show_xy($pdf, chop($companynameaddress2), 72, 290+$ymod); //company address
            pdf_show_xy($pdf, chop($companynameaddress3), 72, 275+$ymod); //company address
            pdf_show_xy($pdf, chop($companynameaddress4), 72, 260+$ymod); //company city, state zip
            pdf_set_font($pdf, $font, $basesize-2, "host");
            pdf_show_xy($pdf, chop($recordSet->fields[7]), 72, 230+$ymod); //ssnumber
            pdf_show_xy($pdf, chop($recordSet->fields[1].', '.$recordSet->fields[0]), 72, 200+$ymod); //employee name
            pdf_show_xy($pdf, chop($recordSet->fields[2]), 72, 180+$ymod); //employee address
            pdf_show_xy($pdf, chop($recordSet->fields[3]), 72, 160+$ymod); //employee address
            pdf_show_xy($pdf, chop($recordSet->fields[4].', '.$recordSet->fields[5].' '.$recordSet->fields[6]), 72, 140+$ymod); //employee city, state zip
            $totalwages=$recordSet->fields[8]+$recordSet2->fields[0];
             if ($totalwages<=$maxwagesfica) { //entire amount is fica wages
                  $totalfica=$totalwages;
             } else { //calculate amount that is fica wages
                  $totalfica=$maxwagesfica-$totalwages;
             };
             if ($totalwages<=$maxwagesmed) { //entire amount is medicare wages
                  $totalmed=$totalwages;
             } else { //calculate amount this period that is medicare wages
                  $totalmed=$maxwagesmed-$totalwages;
             };
            pdf_show_xy($pdf, chop(num_format($totalwages,2)), 340, 330+$ymod); //wages
            pdf_set_font($pdf, $font, $basesize-8, "host");
            pdf_show_xy($pdf, chop("Wages"), 340, 342+$ymod);
            pdf_set_font($pdf, $font, $basesize-2, "host");
            pdf_show_xy($pdf, chop(num_format($recordSet->fields[9],2)), 460, 330+$ymod); //federal withheld
            pdf_set_font($pdf, $font, $basesize-8, "host");
            pdf_show_xy($pdf, chop("Federal WH"), 460, 342+$ymod);
            pdf_set_font($pdf, $font, $basesize-2, "host");
            pdf_show_xy($pdf, chop(num_format($totalfica,2)), 340, 305+$ymod); //fica wages
            pdf_show_xy($pdf, chop(num_format($recordSet->fields[10],2)), 460, 305+$ymod); //fica withheld
            pdf_set_font($pdf, $font, $basesize-8, "host");
            pdf_show_xy($pdf, chop("FICA WH"), 460, 317+$ymod);
            pdf_set_font($pdf, $font, $basesize-2, "host");
            pdf_show_xy($pdf, chop(num_format($totalmed,2)), 340, 280+$ymod); //wages
            pdf_show_xy($pdf, chop(num_format($recordSet->fields[11],2)), 460, 280+$ymod); //medicare withheld
            pdf_set_font($pdf, $font, $basesize-8, "host");
            pdf_show_xy($pdf, chop("Medicare WH"), 460, 292+$ymod);
            pdf_set_font($pdf, $font, $basesize-2, "host");
            pdf_show_xy($pdf, chop($recordSet->fields[14]), 68, 70+$ymod); //state name
            pdf_show_xy($pdf, chop($statetaxnum), 95, 70+$ymod); //state tax num
            pdf_show_xy($pdf, chop(num_format($recordSet->fields[8]+$recordSet2->fields[0],2)), 210, 70+$ymod); //state wages
            pdf_set_font($pdf, $font, $basesize-8, "host");
            pdf_show_xy($pdf, chop("State Wages"), 210, 82+$ymod);
            pdf_set_font($pdf, $font, $basesize-2, "host");
            pdf_show_xy($pdf, chop(num_format($recordSet->fields[12],2)), 290, 70+$ymod); //state tax
            pdf_set_font($pdf, $font, $basesize-8, "host");
            pdf_show_xy($pdf, chop("State Tax"), 290, 82+$ymod);
            pdf_set_font($pdf, $font, $basesize-2, "host");
            pdf_show_xy($pdf, chop($recordSet->fields[15]), 360, 70+$ymod); //local name
            pdf_show_xy($pdf, chop(num_format($recordSet->fields[8]+$recordSet2->fields[0],2)), 420, 70+$ymod); //local wages
            pdf_set_font($pdf, $font, $basesize-8, "host");
            pdf_show_xy($pdf, chop("Local Wages"), 420, 82+$ymod);
            pdf_set_font($pdf, $font, $basesize-2, "host");
            pdf_show_xy($pdf, chop(num_format($recordSet->fields[13],2)), 490, 70+$ymod); //local tax
            pdf_set_font($pdf, $font, $basesize-8, "host");
            pdf_show_xy($pdf, chop("Local Tax"), 490, 82+$ymod);
            pdf_set_font($pdf, $font, $basesize-2, "host");

            $recordSet->MoveNext();
            if (!$recordSet->EOF) {
                $recordSet2 = &$conn->Execute('select sum(premplweekpaydetail.amount) from premplweekpaydetail,premplweek,chk where premplweek.checkid=chk.id and premplweek.id=premplweekpaydetail.premplweekid and premplweek.cancel=0 and premplweek.employeeid='.sqlprep($recordSet->fields[16]).' and extract(year from chk.checkdate)='.sqlprep($w2year));
                $totalwages=$recordSet->fields[8]+$recordSet2->fields[0];
                if ($totalwages<=$maxwagesfica) {
                  //entire amount is fica wages
                  $totalfica=$totalwages;
                } else {
                  //calculate amount that is fica wages
                  $totalfica=$maxwagesfica-$totalwages;
                };
                if ($totalwages<=$maxwagesmed) {
                  //entire amount is medicare wages
                  $totalmed=$totalwages;
                } else {
                  //calculate amount this period that is medicare wages
                  $totalmed=$maxwagesmed-$totalwages;
                };
                $ymod=-25;
                pdf_show_xy($pdf, chop($fedtaxnum), 72, 335+$ymod); //fedtaxnum
                pdf_set_font($pdf, $font, $basesize-4, "host");
                pdf_show_xy($pdf, chop($companynameaddress1), 72, 305+$ymod); //company name
                pdf_show_xy($pdf, chop($companynameaddress2), 72, 290+$ymod); //company address
                pdf_show_xy($pdf, chop($companynameaddress3), 72, 275+$ymod); //company address
                pdf_show_xy($pdf, chop($companynameaddress4), 72, 260+$ymod); //company city, state zip
                pdf_set_font($pdf, $font, $basesize-2, "host");
                pdf_show_xy($pdf, chop($recordSet->fields[7]), 72, 230+$ymod); //ssnumber
                pdf_show_xy($pdf, chop($recordSet->fields[1].', '.$recordSet->fields[0]), 72, 200+$ymod); //employee name
                pdf_show_xy($pdf, chop($recordSet->fields[2]), 72, 180+$ymod); //employee address
                pdf_show_xy($pdf, chop($recordSet->fields[3]), 72, 160+$ymod); //employee address
                pdf_show_xy($pdf, chop($recordSet->fields[4].', '.$recordSet->fields[5].' '.$recordSet->fields[6]), 72, 140+$ymod); //employee city, state zip
                pdf_show_xy($pdf, chop(num_format($totalwages,2)), 340, 330+$ymod); //wages
                pdf_set_font($pdf, $font, $basesize-8, "host");
                pdf_show_xy($pdf, chop("Wages"), 340, 342+$ymod);
                pdf_set_font($pdf, $font, $basesize-2, "host");
                pdf_show_xy($pdf, chop(num_format($recordSet->fields[9],2)), 460, 330+$ymod); //federal withheld
                pdf_set_font($pdf, $font, $basesize-8, "host");
                pdf_show_xy($pdf, chop("Federal WH"), 460, 342+$ymod);
                pdf_set_font($pdf, $font, $basesize-2, "host");
                pdf_show_xy($pdf, chop(num_format($totalfica,2)), 340, 305+$ymod); //fica wages
                pdf_show_xy($pdf, chop(num_format($recordSet->fields[10],2)), 460, 305+$ymod); //fica withheld
                pdf_set_font($pdf, $font, $basesize-8, "host");
                pdf_show_xy($pdf, chop("FICA WH"), 460, 317+$ymod);
                pdf_set_font($pdf, $font, $basesize-2, "host");
                pdf_show_xy($pdf, chop(num_format($totalmed,2)), 340, 280+$ymod); //medicare wages
                pdf_show_xy($pdf, chop(num_format($recordSet->fields[11],2)), 460, 280+$ymod); //medicare withheld
                pdf_set_font($pdf, $font, $basesize-8, "host");
                pdf_show_xy($pdf, chop("Medicare WH"), 460, 292+$ymod);
                pdf_set_font($pdf, $font, $basesize-2, "host");
                pdf_show_xy($pdf, chop($recordSet->fields[14]), 68, 70+$ymod); //state name
                pdf_show_xy($pdf, chop($statetaxnum), 95, 70+$ymod); //state tax num
                pdf_show_xy($pdf, chop(num_format($recordSet->fields[8]+$recordSet2->fields[0],2)), 210, 70+$ymod); //state wages
                pdf_set_font($pdf, $font, $basesize-8, "host");
                pdf_show_xy($pdf, chop("State Wages"), 210, 82+$ymod);
                pdf_set_font($pdf, $font, $basesize-2, "host");
                pdf_show_xy($pdf, chop(num_format($recordSet->fields[12],2)), 290, 70+$ymod); //state tax
                pdf_set_font($pdf, $font, $basesize-8, "host");
                pdf_show_xy($pdf, chop("State Tax"), 290, 82+$ymod);
                pdf_set_font($pdf, $font, $basesize-2, "host");
                pdf_show_xy($pdf, chop($recordSet->fields[15]), 360, 70+$ymod); //local name
                pdf_show_xy($pdf, chop(num_format($recordSet->fields[8]+$recordSet2->fields[0],2)), 420, 70+$ymod); //local wages
                pdf_set_font($pdf, $font, $basesize-8, "host");
                pdf_show_xy($pdf, chop("Local Wages"), 420, 82+$ymod);
                pdf_set_font($pdf, $font, $basesize-2, "host");
                pdf_show_xy($pdf, chop(num_format($recordSet->fields[13],2)), 490, 70+$ymod); //local tax
                pdf_set_font($pdf, $font, $basesize-8, "host");
                pdf_show_xy($pdf, chop("Local Tax"), 490, 82+$ymod);
                pdf_set_font($pdf, $font, $basesize-2, "host");
                $recordSet->MoveNext();
            };
            pdf_end_page($pdf);
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
