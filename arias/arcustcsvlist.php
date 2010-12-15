<?php include("includes/main.php"); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //arcustcsvlist.php
	$nonprintable=1;
	$recordSet = &$conn->Execute('select company.companyname,company.address1,company.address2,company.city,company.state,company.zip,company.country,company.phone1,company.phone1comment,company.phone2,company.phone2comment,company.phone3,company.phone3comment,company.phone4,company.phone4comment,company.email1,company.email1comment,company.email2,company.email2comment,company.website,company.federalid,company.mailstop,salescompany.companyname,servicecompany.companyname,customer.id,invoiceterms.verbal,glaccount.name,glaccount.description,taxexempt.exemptname,customer.creditlimit,customer.interest,customer.chargecode from company,invoiceterms,glaccount,customer left join taxexempt on taxexempt.id=customer.taxexemptid left join company as salescompany on service.id=customer.servicerepid left join salesman as sales on salescompany.id=sales.companyid, salesman as service,  company as servicecompany where sales.id=customer.salesmanid and servicecompany.id=service.companyid and customer.companyid=company.id and invoiceterms.id=customer.invoicetermsid and customer.gencompanyid='.sqlprep($active_company).' and glaccount.id=customer.salesglacctid');
	if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_CUSTOMERS_FOUND']));
	$fp = fopen(IMAGE_UPLOAD_DIR.'cust.csv', 'w');
	while (!$recordSet->EOF) {
		unset($line);
		for ($i=0;$i<=31;$i++) {
			$line.=stripcomma($recordSet->fields[$i]).',';
		};
		fputs($fp, $line."\n");
		$recordSet->MoveNext();
	};
	echo '<META HTTP-EQUIV="Refresh" CONTENT="0; URL=http://'.getenv(SERVER_NAME).'/'.IMAGE_UPLOAD_DIR.'cust.csv">';
	echo 'You should automatically be forwarded to the Customer List when it is ready.  If this does not happen, <a href="'.IMAGE_UPLOAD_DIR.'cust.csv">click here</a> to continue.<br>';

	function stripcomma($str) { //because we're using comma as a delimiter
		return str_replace(",","",$str);
	};
?>
<?php include("includes/footer.php"); ?>
