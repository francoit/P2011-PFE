<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //arcustlistview.php - April 2001 copyright Noguska - Fostoria, OH  44830
	if ($customershipto) {
		$query = 'select customer.id,ccompany.companyname,ccompany.address1,ccompany.address2,ccompany.mailstop,ccompany.city,ccompany.state,ccompany.zip,ccompany.country,ccompany.phone1,ccompany.phone1comment,ccompany.phone2,ccompany.phone2comment,ccompany.phone3,ccompany.phone3comment,ccompany.phone4,ccompany.phone4comment,ccompany.email1,ccompany.email1comment,ccompany.email2,ccompany.email2comment,ccompany.website,smcompany.companyname,srcompany.companyname from shipto cross join customer cross join company as ccompany left join salesman as smsalesman on customer.salesmanid=smsalesman.id left join salesman as srsalesman on customer.salesmanid=srsalesman.id left join company as smcompany on smsalesman.companyid=smcompany.id and (smsalesman.gencompanyid='.sqlprep($active_company).' or smsalesman.gencompanyid=0) left join company as srcompany on srsalesman.companyid=srcompany.id and (srsalesman.gencompanyid='.sqlprep($active_company).' or srsalesman.gencompanyid=0) where shipto.shiptocompanyid=ccompany.id and shipto.companyid=customer.companyid and customer.cancel=0 and customer.gencompanyid='.sqlprep($active_company).' order by ccompany.companyname';
		$titlestr='(Ship To) ';
	} else {
		$query = 'select customer.id,ccompany.companyname,ccompany.address1,ccompany.address2,ccompany.mailstop,ccompany.city,ccompany.state,ccompany.zip,ccompany.country,ccompany.phone1,ccompany.phone1comment,ccompany.phone2,ccompany.phone2comment,ccompany.phone3,ccompany.phone3comment,ccompany.phone4,ccompany.phone4comment,ccompany.email1,ccompany.email1comment,ccompany.email2,ccompany.email2comment,ccompany.website,smcompany.companyname,srcompany.companyname from customer cross join company as ccompany left join salesman as smsalesman on customer.salesmanid=smsalesman.id left join salesman as srsalesman on customer.salesmanid=srsalesman.id left join company as smcompany on smsalesman.companyid=smcompany.id and (smsalesman.gencompanyid='.sqlprep($active_company).' or smsalesman.gencompanyid=0) left join company as srcompany on srsalesman.companyid=srcompany.id and (srsalesman.gencompanyid='.sqlprep($active_company).' or srsalesman.gencompanyid=0) where customer.companyid=ccompany.id and customer.cancel=0 and customer.gencompanyid='.sqlprep($active_company).' order by ccompany.companyname';
	};
	$recordSet = &$conn->Execute($query);
	if (!recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_CUSTOMERS_FOUND']));
	echo texttitle($lang['STR_CUSTOMER_LIST'] .$titlestr.' for '.$companyname);
	echo '<table border="1"><tr><th>'.$lang['STR_COMPANY'].'</th><th>'.$lang['STR_ADDRESS'].'</th><th>'.$lang['STR_PHONE'].'</th><th>'.$lang['STR_INTERNET'].'</th><th>'.$lang['STR_SALES_PERSON'].'</th><th>'.$lang['STR_SERVICE_REP'].'</th><th>'.$lang['STR_FEDERAL_ID'].'</th></tr>';
	while (!$recordSet->EOF) {
		echo '<tr><td>'.$recordSet->fields[1].'</td><td>'.$recordSet->fields[2].'<br>'.$recordSet->fields[3].'<br>'.$recordSet->fields[5].', '.$recordSet->fields[6].' '.$recordSet->fields[7].' '.$recordSet->fields[8].'<br>'.$recordSet->fields[4].'</td><td>'.$recordSet->fields[9].' '.$recordSet->fields[10].'<br>'.$recordSet->fields[11].' '.$recordSet->fields[12].'<br>'.$recordSet->fields[13].' '.$recordSet->fields[14].'<br>'.$recordSet->fields[15].' '.$recordSet->fields[16].'</td><td>'.$recordSet->fields[17].' '.$recordSet->fields[18].'<br>'.$recordSet->fields[19].' '.$recordSet->fields[20].'<br>'.$recordSet->fields[21].'</td><td>'.$recordSet->fields[22].'</td><td>'.$recordSet->fields[23].'</td></tr>'."\n";
		$recordSet->MoveNext();
	};
	echo '</table>';
?>
<?php include('includes/footer.php'); ?>
