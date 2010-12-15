<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //apvendlistview.php - April 2001 copyright Noguska - Fostoria, OH  44830
	if ($payto) {
		$query = 'select vendor.id,ccompany.companyname,ccompany.address1,ccompany.address2,ccompany.mailstop,ccompany.city,ccompany.state,ccompany.zip,ccompany.country,ccompany.phone1,ccompany.phone1comment,ccompany.phone2,ccompany.phone2comment,ccompany.phone3,ccompany.phone3comment,ccompany.phone4,ccompany.phone4comment,ccompany.email1,ccompany.email1comment,ccompany.email2,ccompany.email2comment,ccompany.website,vendor.orderfromname,vendor.customeraccount from vendor,company as ccompany where vendor.paytocompanyid=ccompany.id and vendor.cancel=0 and vendor.gencompanyid='.sqlprep($active_company).' order by ccompany.companyname';
		$titlestr='(Pay To)';
	} else {
		$query = 'select vendor.id,ccompany.companyname,ccompany.address1,ccompany.address2,ccompany.mailstop,ccompany.city,ccompany.state,ccompany.zip,ccompany.country,ccompany.phone1,ccompany.phone1comment,ccompany.phone2,ccompany.phone2comment,ccompany.phone3,ccompany.phone3comment,ccompany.phone4,ccompany.phone4comment,ccompany.email1,ccompany.email1comment,ccompany.email2,ccompany.email2comment,ccompany.website,vendor.orderfromname,vendor.customeraccount from vendor,company as ccompany where vendor.orderfromcompanyid=ccompany.id and vendor.cancel=0 and vendor.gencompanyid='.sqlprep($active_company).' order by ccompany.companyname';
		$titlestr='(Order From)';
	};
	$recordSet = &$conn->Execute($query);
	if ($recordSet->EOF) die(texterror('No vendors found.'));
	echo texttitle('Vendor List '.$titlestr.' for '.$companyname);
	echo '<table border="1"><tr><th>Company</th><th>Address</th><th>Phone</th><th>Internet</th><th>Sales Person</th><th>Customer #</th></tr>';
	while (!$recordSet->EOF) {
		echo '<tr><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[2].'<br>'.$recordSet->fields[3].'<br>'.$recordSet->fields[5].', '.$recordSet->fields[6].' '.$recordSet->fields[7].' '.$recordSet->fields[8].'<br>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[9].' '.$recordSet->fields[10].'<br>'.$recordSet->fields[11].' '.$recordSet->fields[12].'<br>'.$recordSet->fields[13].' '.$recordSet->fields[14].'<br>'.$recordSet->fields[15].' '.$recordSet->fields[16].'</td><td>'.$recordSet->fields[17].' '.$recordSet->fields[18].'<br>'.$recordSet->fields[19].' '.$recordSet->fields[20].'<br>'.$recordSet->fields[21].'</td><td>'.$recordSet->fields[22].'</td><td>'.$recordSet->fields[23].'</td></tr>'."\n";
		$recordSet->MoveNext();
	};
	echo '</table>';
?>
<?php include('includes/footer.php'); ?>
