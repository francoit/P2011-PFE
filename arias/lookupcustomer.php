<?php require_once('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript" src="js/lookup.js">
</script>
<?
        echo texttitle($lang['STR_LOOKUP_CUSTOMER']);
    	echo '<center>';
	if (isset($comp)||!MANY_CUSTOMERS) {
		if ($comp) $compstr=' and company.companyname like '.sqlprep('%'.$comp.'%');
		$recordSet = &$conn->Execute('select customer.id, company.companyname from customer, company where customer.companyid=company.id and company.cancel=0 and customer.gencompanyid='.sqlprep($active_company).$compstr.' and customer.cancel=0 order by company.companyname');
		if ($recordSet->EOF) die(texterror('No matching customers found.').'<br><font size="-1"><a href="javascript:history.back(1)">Back</a></font>');
		echo '<form name="mainform"><select name="'.$name.'"'.INC_TEXTBOX.'>';
		while (!$recordSet->EOF) {
			echo '<option value="'.$recordSet->fields[0].'"'.checkequal($default,$recordSet->fields[0],' selected').'>'.$recordSet->fields[1].' - '.$recordSet->fields[0]."\n";
			$recordSet->MoveNext();
		};
		echo '</select><br><br><input type="button" onClick="setField('.sqlprep($name).')" value="'.$lang['STR_SELECT'].'"></form>';
        if ($comp) echo '<font size="-1"><a href="javascript:history.back(1)">Back</a></font>';
	} else {
		echo '<form name="mainform" action="lookupcustomer.php" method="get"><input type="hidden" name="name" value="'.$name.'">';
		echo texttitle('Customer Company Name');
		echo '<input type="text" name="comp" size="20"'.INC_TEXTBOX.'>'."\n";
		echo '<input type="submit" value="Search"></form>';
	};
	echo '</center>';
?>

<?php require_once('includes/footer.php');?>
