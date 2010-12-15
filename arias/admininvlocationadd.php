<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript" src="js/validatephone.js">
</script>
<?
	echo texttitle($lang['STR_INVENTORY_LOCATION_ADD']);
	if ($name) {
		if (invcompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name)) {
			$recordSet = &$conn->Execute('select id from company where companyname='.sqlprep($name).' order by id desc');
			if (!$recordSet->EOF) $companyid=$recordSet->fields[0];
			invinventorylocationadd($companyid);
		};
	};
	echo '<form action="admininvlocationadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
	forminvinventorylocationadd();
	echo '</table><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
?>
<?php include('includes/footer.php'); ?>
