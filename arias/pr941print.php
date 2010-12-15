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
$filename=IMAGE_UPLOAD_DIR."temp941$rand_nbr.pdf";
$pdf = pdf_new();
if (!pdf_open_file($pdf, "")) {
    print error;
    exit;
};
//begin pdf header info
pdf_set_parameter($pdf, "warning", "true");
pdf_set_info($pdf, "Creator", "pr941print.php");
pdf_set_info($pdf, "Author", "NOLA Software");
pdf_set_info($pdf, "Title", "941");
pdf_begin_page($pdf, 612, 792);  //8.5x11
pdf_set_font($pdf, $font, $basesize, "host");
$year941=substr($yearqtr941,0,4);
$qtr941=substr($yearqtr941,-1);

$prevqtr941=3 ; //3rd quarter previous year for totals for teletax
//adjust year
$prevyear941=$year941-1;

$recordSet2=&$conn->Execute('select prfederal.maxwagesfica, prfederal.maxwagesmedicare from prfederal where gencompanyid='.sqlprep($active_company));
if (!$recordSet2->EOF) { // read federal max's for medicare & fica
      $maxwagesfica=$recordSet2->fields[0];
      $maxwagesmed=$recordSet2->fields[1];
};

    $recordSet = &$conn->Execute('select sum(check.amount) from check,prdepositchecks where check.id=prdepositchecks.checkid and prdepositchecks.gencompanyid='.sqlprep($active_company).' and year(check.checkdate)='.sqlprep($prevyear941).' and quarter(check.checkdate)='.sqlprep($prevqtr941));
    if (!$recordSet->EOF) pdf_show_xy($pdf, chop($recordSet->fields[0]), 375, 500); //total deposits, 3rd quarters prev.year
    $recordSet1=&$conn->Execute('select count(distinct(premployee.id)) from premployee,premplweek left join check on check.id=premplweek.checkid and quarter(check.checkdate)='.sqlprep($qtr941).' where premployee.id=premplweek.employeeid and premplweek.checkid>0');
    $recordSet = &$conn->Execute('select premployee.id, sum(premplweek.misctaxablepay), sum(premplweek.federaltax), sum(premplweek.ficatax), sum(premplweek.medicarededuction),sum(premplweek.eiccredit),sum(premplweek.tipsaswages) from premplweek,premployee,check where  check.id=premplweek.checkid and premployee.gencompanyid='.sqlprep($active_company).' and premplweek.employeeid=premployee.id and year(check.checkdate)='.sqlprep($year941).' and quarter(check.checkdate)='.sqlprep($qtr941).' group by premployee.id');
    while (!$recordSet->EOF) {
             $totaleiccredit+=$recordSet->fields[6];
             $totaltipsaswages+=$recordSet->fields[7];
             $totalfit+=$recordSet->fields[2];
             $totalwages+=$recordSet->fields[1];
             $ficathis=$recordSet->fields[1];
             $recordSet2 = &$conn->Execute('select sum(premplweekpaydetail.amount) from premplweekpaydetail,premplweek,check where premplweek.checkid=check.id and premplweek.id=premplweekpaydetail.premplweekid and premplweek.cancel=0 and year(check.checkdate)='.sqlprep($year941).' and quarter(check.checkdate)='.sqlprep($qtr941).' and premplweek.employeeid='.sqlprep($recordSet->fields[0]));
             if (!$recordSet2->EOF) {
                   $totalwages+=$recordSet2->fields[0];
                   $ficathis+=$recordSet2->fields[0];
             };
             $recordSet2 = &$conn->Execute('select sum(premplweek.tipsaswages)+sum(premplweek.misctaxablepay) from premplweek,premployee,check where  check.id=premplweek.checkid and premployee.gencompanyid='.sqlprep($active_company).' and premplweek.employeeid=premployee.id and year(check.checkdate)='.sqlprep($year941).' and quarter(check.checkdate)<='.sqlprep($qtr941).' and premplweek.employeeid='.sqlprep($recordSet->fields[0]));
             if (!$recordSet2->EOF) $totalficawages=$recordSet2->fields[0];
             $recordSet2 = &$conn->Execute('select sum(premplweekpaydetail.amount) from premplweekpaydetail,premplweek,check where premplweek.checkid=check.id and premplweek.id=premplweekpaydetail.premplweekid and premplweek.cancel=0 and year(check.checkdate)='.sqlprep($year941).' and quarter(check.checkdate)<='.sqlprep($qtr941).' and premplweek.employeeid='.sqlprep($recordSet->fields[0]));
             if (!$recordSet2->EOF) $totalficawages+=$recordSet2->fields[0];
             if ($totalficawages<=$maxwagesfica) {
                  //entire amount is fica wages
                  $totalfica+=$ficathis;
             } else if ($totalficawages-$ficathis>$maxwagesfica) {
                  //do nothing, no fica wages this period
             } else {
                  //calculate amount this period that is fica wages
                  $totalfica+=$maxwagesfica-($totalficawages-$ficathis);
             };
             if ($totalficawages<=$maxwagesmed) {
                  //entire amount is medicare wages
                  $totalmed+=$ficathis;
             } else if ($totalficawages-$ficathis>$maxwagesmed) {
                  //do nothing, no medicare wages this period
             } else {
                  //calculate amount this period that is medicare wages
                  $totalmed+=$maxwagesmed-($totalficawages-$ficathis);
             };
             $recordSet->MoveNext();
    };
        pdf_set_font($pdf, $font, $basesize, "host");
        pdf_show_xy($pdf, chop($recordSet1->fields[0]), 340, 435); //number of employees
        pdf_set_font($pdf, $font, $basesize-8, "host");
        pdf_show_xy($pdf, chop("# Employees"), 340, 447);
        pdf_set_font($pdf, $font, $basesize, "host");
        pdf_show_xy($pdf, chop($totalwages), 340, 416); //total wages
        pdf_set_font($pdf, $font, $basesize-8, "host");
        pdf_show_xy($pdf, chop("Total Wages"), 340, 428);
        pdf_set_font($pdf, $font, $basesize, "host");
        pdf_show_xy($pdf, chop($totalfit), 340, 397); //tax withheld
        pdf_set_font($pdf, $font, $basesize-8, "host");
        pdf_show_xy($pdf, chop("Tax Withheld"), 340, 409);
        pdf_set_font($pdf, $font, $basesize, "host");
        pdf_show_xy($pdf, chop($totalfica), 340, 378); //total fica wages
        pdf_set_font($pdf, $font, $basesize-8, "host");
        pdf_show_xy($pdf, chop("Fica Wages"), 340, 390);
        pdf_set_font($pdf, $font, $basesize, "host");
        pdf_show_xy($pdf, chop($totalmed), 340, 340); //total medicare wages
        pdf_set_font($pdf, $font, $basesize-8, "host");
        pdf_show_xy($pdf, chop("Medicare Wages"), 340, 352);
        pdf_set_font($pdf, $font, $basesize, "host");
        pdf_show_xy($pdf, chop($totaleiccredit), 340, 268); //eic credit
        pdf_set_font($pdf, $font, $basesize-8, "host");
        pdf_show_xy($pdf, chop("Eic Credit"), 340, 280);
        pdf_set_font($pdf, $font, $basesize, "host");

    $recordSet = &$conn->Execute('select sum(check.amount) from check,prdepositchecks where check.id=prdepositchecks.checkid and prdepositchecks.gencompanyid='.sqlprep($active_company).' and year(check.checkdate)='.sqlprep($year941).' and quarter(check.checkdate)='.sqlprep($qtr941));
    if (!$recordSet->EOF) {
        pdf_show_xy($pdf, chop($recordSet->fields[0]), 340, 227); //total deposits, this quarter
        pdf_set_font($pdf, $font, $basesize-8, "host");
        pdf_show_xy($pdf, chop("Total Deposits"), 340, 239);
        pdf_set_font($pdf, $font, $basesize, "host");
    };
    $recordSet = &$conn->Execute('select sum(premplweek.federaltax+premplweek.ficatax+premplweek.cficatax+premplweek.medicarededuction+premplweek.cmedicarededuction), sum(premplweek.ficatax), sum(premplweek.medicarededuction) from premplweek,premployee,check where check.id=premplweek.checkid and premployee.gencompanyid='.sqlprep($active_company).' and premplweek.employeeid=premployee.id and year(check.checkdate)='.sqlprep($year941).' and quarter(check.checkdate)='.sqlprep($qtr941).' group by month(check.checkdate) order by month(check.checkdate)');
    $x=0;
    while (!$recordSet->EOF) {
        pdf_show_xy($pdf, chop($recordSet->fields[0]), 140+($x*72), 140); //month 1 liability
        pdf_set_font($pdf, $font, $basesize-8, "host");
        pdf_show_xy($pdf, chop("Month ".($x+1)), 140+($x*72), 152);
        pdf_set_font($pdf, $font, $basesize, "host");

        $x++;
        $recordSet->MoveNext();
    };
    pdf_end_page($pdf);

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
