<?
  require_once('includes/defines.php');
  require_once('includes/adodb/adodb.inc.php');
  require_once('includes/functions.php');
  require_once("includes/barcode/barcode.php");
  require_once("includes/barcode/c128aobject.php");
  require_once("includes/barcode/c128bobject.php");
  require_once("includes/barcode/i25object.php");
  require_once("includes/barcode/c39object.php");
  ADOLoadCode(DB_TYPE);
  $conn = &ADONewConnection();
  $conn->PConnect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);

// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)

    /*************** labels.php
    This script writes labels to a PDF file.
    *****************/

//This page set for 8.5"x11" Sheet of 1"x2.5/8" Labels, 30 per sheet
//Avery part #05160    

/*** Get a random number to use for the file name  ***/
mt_srand((double)microtime() * 1000000);
$rand_nbr = mt_rand();

/*** Begin our pdf file  ***/
$filename=IMAGE_UPLOAD_DIR."templabel$rand_nbr.pdf";
$pdf = pdf_new();
if (!pdf_open_file($pdf, "")) {
    print error;
    exit;
};
//begin pdf header info
pdf_set_parameter($pdf, "warning", "true");
pdf_set_info($pdf, "Creator", "labels.php");
pdf_set_info($pdf, "Author", "Arias Software");
pdf_set_info($pdf, "Title", "Labels");


if (!isset($font)) $font = PDF_load_font($pdf, "Times-Roman", "host", "");
//$font = "Times-Roman";
if (!isset($basesize)) $basesize="10";

/*** Log into the database and set up our select statements ***/
if ($vendor) $query = 'select company.companyname,vendor.orderfromname,company.address1,company.address2,company.city,company.state,company.zip,company.country from company,vendor where vendor.cancel=0 and company.id=vendor.orderfromcompanyid and vendor.gencompanyid='.sqlprep($companyid).' order by company.companyname';
if ($vendorpayto) $query = 'select company.companyname,"",company.address1,company.address2,company.city,company.state,company.zip,company.country from company,vendor where vendor.cancel=0 and company.id=vendor.paytocompanyid and vendor.gencompanyid='.sqlprep($companyid).' order by company.companyname';
if ($customer) $query = 'select company.companyname,customer.quoteattnname,company.address1,company.address2,company.city,company.state,company.zip,company.country from company,customer where customer.cancel=0 and company.id=customer.companyid and customer.gencompanyid='.sqlprep($companyid).' order by company.companyname';
if ($customershipto) $query = 'select company.companyname,"",company.address1,company.address2,company.city,company.state,company.zip,company.country from company,customer,shipto where customer.cancel=0 and shipto.cancel=0 and company.id=shipto.shiptocompanyid and customer.companyid=shipto.companyid and customer.gencompanyid='.sqlprep($companyid).' order by company.companyname';
if ($item) $query = 'select \'foo\'';
$recordSet = &$conn->Execute($query);
if ($recordSet->EOF) die(texterror('No entries found.<br>'.$query));

/*** Here's where we write to our PDF file  ***/
pdf_begin_page($pdf, 612, 792);  //8.5x11
//pdf_set_font($pdf, $font, $basesize, "host");
pdf_setfont($pdf, $font, $basesize);
$i=0;

if (!$item) {
 while (!$recordSet->EOF) {
  if ($i==30) {  //paginate after 30 labels
      pdf_end_page($pdf);
      pdf_begin_page($pdf, 612, 792);  //8.5x11
      $i=0;
  };
  pdf_show_xy($pdf, chop($recordSet->fields[0]), 14+(($i)%3)*205, 753-(floor(($i)/3)*72)); //companyname
  pdf_show_xy($pdf, chop($recordSet->fields[1]), 14+(($i)%3)*205, 739-(floor(($i)/3)*72)); //name
  pdf_show_xy($pdf, chop($recordSet->fields[2]), 14+(($i)%3)*205, 725-(floor(($i)/3)*72)); //address1
  pdf_show_xy($pdf, chop($recordSet->fields[3]), 14+(($i)%3)*205, 711-(floor(($i)/3)*72)); //address2
/*  $city = $recordSet->fields[4];
  $state = $recordSet->fields[5];
  $zip = $recordSet->fields[6];
  echo PRN_ADDRESS_STYLE;
  pdf_show_xy($pdf, chop(PRN_ADDRESS_STYLE), 14+(($i)%3)*205, 697-(floor(($i)/3)*72)); //address style
  pdf_show_xy($pdf, chop($recordSet->fields[4]), 14+(($i)%3)*205, 697-(floor(($i)/3)*72)); //city
  pdf_show_xy($pdf, chop($recordSet->fields[5]), 122+(($i)%3)*205, 697-(floor(($i)/3)*72)); //state
  pdf_show_xy($pdf, chop($recordSet->fields[6]), 158+(($i)%3)*205, 697-(floor(($i)/3)*72)); //zip
*/
  $citystatelabel = $recordSet->fields[4].', '.$recordSet->fields[5].', '.$recordSet->fields[6];
  pdf_show_xy($pdf, chop($citystatelabel), 14+(($i)%3)*205, 697-(floor(($i)/3)*72)); //city

  $i++;
  $recordSet->MoveNext();
 };
} else {
  $font='ARIAL.TTF';
  reset($item);
  while(current($item)) {
  if ($i==30) {  //paginate after 30 labels
      pdf_end_page($pdf);
      pdf_begin_page($pdf, 612, 792);  //8.5x11
      $i=0;
  };
//  retrievefile('http://'.getenv(SERVER_NAME).'/'.substr(substr(barcodedisplay("png", current($item), BARCODE_CODE_TYPE, 175, BARCODE_IMAGE_HEIGHT, BARCODE_IMAGE_XRES, BARCODE_IMAGE_FONT),10),0,-2),'uploads/tempitem'.current($item).'.png');
  $recordSet = &$conn->Execute('select item.itemcode,item.description,itemcategory.name from item,itemcategory where item.categoryid=itemcategory.id and item.id='.sqlprep(current($item)));
  if (!$recordSet->EOF) {
    $im = imagecreate(204,50);
    $bgc = ImageColorAllocate($im, 255,255,255);
    $t1c = ImageColorAllocate($im, 0,0,0);
    $code='*'.$recordSet->fields[0].'*';
    $size = imagettfbbox(35,0, FONT_PATH.'/BARCODE39.TTF',$code);
    if(!isset($dx))  $dx = abs($size[2]-$size[0]);
    if(!isset($dy))  $dy = abs($size[5]-$size[3]);
    ImageTTFText($im, 35, 0, (int)($xpad/2), $dy-1, $t1c, FONT_PATH.'/BARCODE39.TTF', $code);
    Imagepng($im, 'uploads/tempitem'.current($item).'.png');
    $im2 = imagecreate(204,72);
    $bgc = ImageColorAllocate($im2, 255,255,255);
    $t1c = ImageColorAllocate($im2, 0,0,0);
    ImageCopy ($im2, $im, 0, 0, 0, 0, 204, 50);
    $size = imagettfbbox(8,0, FONT_PATH.'/'.$font,'Item #'.$recordSet->fields[1].' - '.$recordSet->fields[2]);
    if(!isset($dx))  $dx = abs($size[2]-$size[0]);
    if(!isset($dy))  $dy = abs($size[5]-$size[3]);
    ImageTTFText($im2, 8, 0, (int)($xpad/2), 79-$dy, $t1c, FONT_PATH.'/'.$font, 'Item #'.$recordSet->fields[0].' - '.$recordSet->fields[2]);
    ImageTTFText($im2, 8, 0, (int)($xpad/2), 66-$dy/3, $t1c, FONT_PATH.'/'.$font, $recordSet->fields[1]);
    Imagepng($im2, 'uploads/tempitem'.current($item).'.png');
    ImageDestroy($im);
    ImageDestroy($im2);
    $pdfimage = pdf_open_image_file($pdf, "png", 'uploads/tempitem'.current($item).'.png');
    pdf_place_image($pdf, $pdfimage, 14+(($i)%3)*205, 719-(floor(($i)/3)*72), 1);
  };
  next($item);
  $i++;
  };
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
