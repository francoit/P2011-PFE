<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
$debug=0;     // Set to 1 for verbose mode
$inserted=0;  // Tracks # of records inserted

echo '<center>';

if ($filename) {
  $gfilename=IMAGE_UPLOAD_DIR."impcust".strtolower(substr($filename, strrpos($filename,'.')));
  if (!move_uploaded_file($filename, $gfilename)){
    retrievefile($filename,$gfilename);
   }

  include("./includes/file_db.class.php");

  if ($debug) echo "debug - ".$gfilename."<br>";

  $rec = new file_db ;
  $rec->filename = $gfilename ;
  $rec->open_db();

  while (!$rec->EOF) {
    if ($debug)echo ">>".$rec->record["company"]."<br>";
    $rec->move_next();
  }

  $rec->move_first();

  //$fcontents = file ($gfilename);

  if ($debug) echo "debug - first company[".$rec["company"]."]<br>";

  while (!$rec->EOF) {
   echo '.';
   $creditlimit     =($rec->record["credit"]);
   $billtoattnname  =($rec->record["contact"]);
   $quoteattnname   =($rec->record["q_contact"]);
   $salestaxnum     =($rec->record["taxcode"]);  
   $companyname     =($rec->record["company"]);  
   $address1        =($rec->record["address1"]); 
   $address2        =($rec->record["address2"]); 
   $city            =($rec->record["city"]);     
   $state           =($rec->record["addrstate"]);
   $zip             =($rec->record["zip"]);      
   $phone1          =($rec->record["phone"]);    
   $phone2          =($rec->record["phone2"]);   
   $email1          =($rec->record["email"]);    
   $federalid       =($rec->record["taxdist"]);  
   $shipcompanyname =($rec->record["st_company"]);
   $shipaddress1    =($rec->record["st_address1"]);
   $shipaddress2    =($rec->record["st_address2"]);
   $shipcity        =($rec->record["st_city"]);    
   $shipstate       =($rec->record["st_addrstate"]);
   $shipzip         =($rec->record["st_zip"]);      

   if ($shipcompanyname<=" ") $shipcompanyname=$companyname;
   $identical=0;
   if ($companyname>"  ") {
   if ($shipcompanyname==$companyname&&$shipaddress1==$address1&&$shipaddress2==$address2) $identical=1;
   //create bill-to entry into company and then read id
   $recordSet2=&$conn->Execute('select id from company where companyname='.sqlprep($companyname));
   $companyid=0;
   if (!$recordSet2->EOF) {
         //update company file only, do not create
         $companyid=$recordSet2->fields[0];
   } else {
         //insert into company file, then read id
         $conn->Execute('insert into company (companyname,address1,address2,city,state,zip,phone1,phone2,phone2comment,federalid,email1) values ('.sqlprep($companyname).', '.sqlprep($address1).', '.sqlprep($address2).', '.sqlprep($city).', '.sqlprep($state).', '.sqlprep($zip).', '.sqlprep($phone1).', '.sqlprep($phone2).', '.sqlprep("FAX").', '.sqlprep($email1).', '.sqlprep($federalid).')');
   };
   $recordSet2=&$conn->Execute('select id from company where companyname='.sqlprep($companyname));
   if (!$recordSet2->EOF) {
      $companyid=$recordSet2->fields[0];
      //create customer entry then read id
      $customerid=0;
      if ($companyid>0) {
         //already had a company entry, so check for customer entry
         $recordSet2=&$conn->Execute('select id from customer where gencompanyid='.sqlprep($active_company).' and companyid='.sqlprep($companyid).' and cancel=0');
         if (!$recordSet2->EOF) $customerid=$recordSet2->fields[0];
      };
      if ($customerid>0) {
           //update existing
           $conn->Execute('update customer set billtoattnname='.sqlprep($billtoattnname).', quoteattnname='.sqlprep($quoteattnname).' where customerid='.sqlprep($customerid));
      } else {
           //create new
           $conn->Execute('insert into customer (companyid,gencompanyid,billtoattnname,quoteattnname) values ('.sqlprep($companyid).','.sqlprep($active_company).','.sqlprep($billtoattnname).','.sqlprep($quoteattnname).')');
	 $inserted++;
      };
      $recordSet2=&$conn->Execute('select id from customer where gencompanyid='.sqlprep($active_company).' and companyid='.sqlprep($companyid).' and cancel=0');
      if (!$recordSet2->EOF) $customerid=$recordSet2->fields[0];
      if ($customerid>0) {
          $recordSet2=&$conn->Execute('select id,shiptocompanyid from shipto where companyid='.sqlprep($companyid));
          $shiptoid=0;
          if (!$recordSet2->EOF) {
                $shiptoid=$recordSet2->fields[0];
                $shiptocompanyid=$recordSet2->fields[1];
                //update existing shipto
                if ($shiptocompanyid==$companyid&&$identical==1) {
                   //do not need to do anything
                } elseif ($identical==1&&$shiptocompanyid<>$companyid) {
                   //will need to change shiptocompanyid to be same as
                   //companyid
                   $conn->Execute('update shipto set companyid='.sqlprep($companyid).' where id='.sqlprep($shiptoid));

                } else {
                    // not identical, so simply update company pointed to
                    $conn->Execute('update company set companyname='.sqlprep($shipcompanyname).', address1='.sqlprep($shipaddress1).', address2='.sqlprep($shipaddress2).', city='.sqlprep($shipcity).', state='.sqlprep($shipstate).', zip='.sqlprep($shipzip).', phone1='.sqlprep($shipphone1).', phone2='.sqlprep($shipphone2).', phone2comment="FAX", email1='.sqlprep($shipemail1).', where companyid='.sqlprep($shiptocompanyid));
                };
          } else {
                //create ship-to entry
                //create company entry, then ship-to entry
                $recordSet2=&$conn->Execute('select id from company where companyname='.sqlprep($shipcompanyname).' and address1='.sqlprep($shipaddress1));
                if ($recordSet2->EOF) {
                     $conn->Execute('insert into company (companyname,address1,address2,city,state,zip,phone1,phone2,phone2comment,federalid,email1) values ('.sqlprep($shipcompanyname).', '.sqlprep($shipaddress1).', '.sqlprep($shipaddress2).', '.sqlprep($shipcity).', '.sqlprep($shipstate).', '.sqlprep($shipzip).', '.sqlprep($shipphone1).', '.sqlprep($shipphone2).', '.sqlprep("FAX").', '.sqlprep($shipemail1).', '.sqlprep($shipfederalid).')');
                };
                     $recordSet2=&$conn->Execute('select id from company where companyname='.sqlprep($shipcompanyname).' and address1='.sqlprep($shipaddress1));
                     if (!$recordSet2->EOF) $shiptocompanyid=$recordSet2->fields[0];
                     if ($shiptocompanyid>0) {
                        $conn->Execute('insert into shipto (companyid,shiptocompanyid) values ('.sqlprep($companyid).','.sqlprep($shiptocompanyid).')');
                     };
          };
      };
   };
   };
   $rec->move_next();
  };
  echo "\n";
  echo "<br>++".$inserted."<br";
  echo texttitle($lang['STR_CUSTOMERS_HAVE_BEEN_IMPORTED']).'<br><br>';
} else {
    echo texttitle($lang['STR_SELECT_CUSTOMER_FILE']);
    echo '<form enctype="multipart/form-data" action="importcustomer.php" name="mainform" method="POST"> ';
    echo '<input type="hidden" name="MAX_FILE_SIZE" value="300000"> ';
    echo $lang['STR_URL_FILE_FOR_CSV_FORMAT_CUSTOMER_FILE'] ;
    echo ': <input name="filename" type="file"> ';
    echo '<input type="submit" value="'.$lang['STR_SELECT_FILE'].'"> ';
    echo '</form>';
    };

echo '</center>';
?>

<?php include('includes/footer.php'); ?>
