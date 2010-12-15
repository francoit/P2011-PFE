<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
	echo '<center>';
	echo textsuccess($lang['STR_SAVING_DATA']);
    $fail=0;
	$recordSet = &$conn->Execute ('select voucher,description,wherefrom,status,cancel,companyid,standardset,entrydate,posteddate,post2date,canceldate,lastchangedate,lastchangeuserid,entryuserid from gltransvoucher'.$userid);
	if (!$recordSet->EOF) {
		$voucher=$recordSet->fields[0];
		if ($conn->Execute('insert into gltransvoucher (voucher,description,wherefrom,status,cancel,companyid,standardset,entrydate,posteddate,post2date,canceldate,lastchangedate,lastchangeuserid,entryuserid) values ('.sqlprep($recordSet->fields[0]).",".sqlprep($recordSet->fields[1]).",".sqlprep($recordSet->fields[2]).",".sqlprep($recordSet->fields[3]).",".sqlprep($recordSet->fields[4]).",".sqlprep($recordSet->fields[5]).",".sqlprep($recordSet->fields[6]).",".sqlprep($recordSet->fields[7]).",".sqlprep($recordSet->fields[8]).",".sqlprep($recordSet->fields[9]).",".sqlprep($recordSet->fields[10]).",".sqlprep($recordSet->fields[11]).",".sqlprep($recordSet->fields[12]).",".sqlprep($recordSet->fields[13]).")") === false) {
		    echo texterror('Error adding voucher to main database.');
			$fail=1;
		} else {
			echo textsuccess($lang['STR_VOUCHER_ADDED_SUCCESSFULLY']);
		};

	};

	if (!$fail) {
		echo $voucher;
		$recordSet=&$conn->Execute('select id from gltransvoucher where voucher='.sqlprep($voucher));
		if (!$recordSet->EOF) $voucherid=$recordSet->fields[0];
		$recordSet = &$conn->Execute('select glaccountid,voucherid,amount from gltransaction'.$userid);
		while ($recordSet&&!$recordSet->EOF) {
			if ($conn->Execute('insert into gltransaction (glaccountid,voucherid,amount) values ('.sqlprep($recordSet->fields[0]).",".sqlprep($voucherid).",".sqlprep($recordSet->fields[2]).')') === false) {
                echo texterror($lang['STR_ERROR_ADDING_VOUCHER_TO_DATABASE']);
				$fail=1;
			};
			echo '...';
			$recordSet->MoveNext();
		};
	};
	if ($userid>0) {
		$conn->Execute ('drop table gltransaction'.$userid);
		$conn->Execute ('drop table gltransvoucher'.$userid);
	};
	if (!$fail) {
		echo textsuccess($lang['STR_POSTED_VOUCHER_SUCCESSFULLY']);
		echo '<br>';
	} else {
		echo texterror($lang['STR_ERROR_COULD_NOT_POST_VOUCHER']);
		echo '<br>';
	};
	echo '</center>';
?>

<?php include('includes/footer.php'); ?>
