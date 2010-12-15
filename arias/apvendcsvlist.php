<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //apvendlist.php - April 2001 copyright Noguska - Fostoria, OH  44830
	$nonprintable=1; //this page just generates a csv and redirects to it.. no need for a printable link
	$recordSet = &$conn->Execute('select company.companyname,company.address1,company.address2,company.city,company.state,company.zip,company.country,company.phone1,company.phone1comment,company.phone2,company.phone2comment,company.phone3,company.phone3comment,company.phone4,company.phone4comment,company.email1,company.email1comment,company.email2,company.email2comment,company.website,company.federalid,company.mailstop,vendor.orderfromname,vendor.customeraccount,invoiceterms.verbal,glaccount.name,glaccount.description from vendor,company,invoiceterms,glaccount where vendor.orderfromcompanyid=company.id and invoiceterms.id=vendor.paytermsid and glaccount.id=vendor.defaultglacctid');
	if ($recordSet->EOF) die(texterror('No vendors found.'));
	$fp = fopen(IMAGE_UPLOAD_DIR.'vend.csv', 'w');
	while (!$recordSet->EOF) {
		$line='';
		for ($i=0;$i<=26;$i++) {
			$line.=stripcomma($recordSet->fields[$i]).',';
		};
		fputs($fp, $line."\n");
		$recordSet->MoveNext();
	};
	echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=http://'.getenv(SERVER_NAME).'/'.IMAGE_UPLOAD_DIR.'vend.csv">';
	echo 'You should automatically be forwarded to the Vendor List when it is ready.  If this does not happen, <a href="'.IMAGE_UPLOAD_DIR.'vend.csv">click here</a> to continue.<br>';
	function stripcomma($str) { //because we're using comma as a delimiter
		return str_replace(",","",$str);
	};
?>
<?php include('includes/footer.php'); ?>
