<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
        echo '<center>';
	if ($amount<>0) {
		$recordSet = &$conn->Execute('select id, voucher,description,entrydate from gltransvoucher'.$userid);
		if (!$recordSet->EOF) {
			$voucherid=$recordSet->fields[0];
			$vouchername=$recordSet->fields[1];
			$voucherdescription=$recordSet->fields[2];
			$voucherentrydate=$recordSet->fields[3];
		};
		//---------detail not saved yet------------

		if ($conn->Execute("insert into gltransaction".$userid." (glaccountid,voucherid,amount ) VALUES (".sqlprep($glaccountid).",".sqlprep($voucherid).",".sqlprep($amount).")") === false) {
	        	echo texterror($lang['STR_ERROR_ADDING_VOUCHER_DETAIL']);
		} else {
			echo textsuccess($lang['STR_VOUCHER_DETAIL_ADDED_SUCCESSFULLY']);
			$amount=0;
			$recordSet = &$conn->Execute('select sum(amount) from gltransaction'.$userid);
			$sum=0;
			if (!$recordSet->EOF) $sum=$recordSet->fields[0];
			if ($sum==0) {
				//---------- check to see if in balance. Sum of amounts is 0
				//-----------if YES then ask if continue or finish at this point
			};
		};
	};

		//---- get the balance ------
		$recordSet = &$conn->Execute('select sum(amount) from gltransaction'.$userid);
		$sum=0;
		if (!$recordSet->EOF) $sum=0-$recordSet->fields[0];
		$sum=num_format($sum,PREFERRED_DECIMAL_PLACES);
		echo '<form action="gljouradd1.php" method="post">';
		//----- get info for general voucher -------
		$recordSet = &$conn->Execute('select id, voucher,description,entrydate from gltransvoucher'.$userid);
		if (!$recordSet->EOF) {
			$voucherid=$recordSet->fields[0];
			$vouchername=$recordSet->fields[1];
			$voucherdescription=$recordSet->fields[2];
			$voucherentrydate=$recordSet->fields[3];
		};

		echo '<b><font color="#FF0000">'.$lang['STR_VOUCHER'].':  '.$vouchername.'.      '.$lang['STR_FOR'].':  '.$voucherdescription.'</font></b><br>';
		echo '<b><font color="#FF0000">'.$lang['STR_DATED'].': '.$voucherentrydate.'</font></b><br>';


		//---now get new details-----
		echo '<table><input type="hidden" name="voucherid" value="'.$voucherid.'"><input type="hidden" name="vouchername" value="'.$vouchername.'"><input type="hidden" name="voucherdescription" value="'.$voucherdescription.'">';
        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GENERAL_LEDGER_ACCOUNT'].':</td><td><select name="glaccountid">';
		$recordSet = &$conn->Execute('select id, name,description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by name');
		echo '<option value="0">';
		while (!$recordSet->EOF) {
			echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
			$recordSet->MoveNext();
		};
		echo '</select></td></tr>';
        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AMOUNT'].': </td><td><input type="text" onchange="validatenum(this)" name="amount" size="20" maxlength="20" value="'.$sum.'"></td></tr>';
		echo '</table><br><input type="submit" value="'.$lang['STR_SAVE_ENTRY'].'">';
		echo '</form>';
		if ($sum==0) {
			echo '<br><a href="gljouradd2.php">'.$lang['STR_VOUCHER'].' '.$voucher.' '.$lang['STR_COMPLETE'].'</a>';
		} else {
			echo texterror($lang['STR_VOUCHER_NOT_IN_BALANCE']);
		};

		echo '<table border=1 cellspacing=0>';
		echo '<tr><th>'.$lang['STR_ACCOUNT'].'</th><th>'.$lang['STR_ACCOUNT_DESCRIPTION'].'</th><th>'.$lang['STR_AMOUNT'].'</th></tr>';
		//---- display any previously entered details
		$recordSet=&$conn->Execute('select glaccount.name,glaccount.description,gltransaction'.$userid.'.amount from gltransaction'.$userid.', glaccount where gltransaction'.$userid.'.glaccountid=glaccount.id');
		while ($recordSet&&!$recordSet->EOF) {
			echo '<tr><td>'.$recordSet->fields[0].'</td><td>'.$recordSet->fields[1].'</td><td>'.num_format($recordSet->fields[2],PREFERRED_DECIMAL_PLACES).'</td></tr>';
			$recordSet->MoveNext();
		};
		echo '</table>';
		echo '</center>';
?>

<?php include('includes/footer.php'); ?>
