<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
if ($vid) {
    	if (!$voucherid) { //read header info and allow edit before details edited
		$recordSet = &$conn->Execute('select id, voucher,description,entrydate from gltransvoucher where id='.sqlprep($vid));
		if (!$recordSet->EOF) {
			$voucherid=$recordSet->fields[0];
			$vouchername=$recordSet->fields[1];
			$voucherdescription=$recordSet->fields[2];
			$voucherentrydate=$recordSet->fields[3];
		};
		echo '<form action="gljourupd1.php" method="get"><table><tr><td> </td><td><b>UPDATE GENERAL VOUCHER INFORMATION</b></td></tr><tr><td>Journal Voucher:</td><td><input type="text" name="vouchername" size=20 value="'.$vouchername.'"></td></tr><tr><td>Voucher Description:</td><td><input type="text" name="voucherdescription" size="50" maxlength="30" value="'.$voucherdescription.'"></td></tr><tr><td>Transaction Date:</td><input type="hidden" name="editflag" value="0"></td><td><input type="text" name="voucherentrydate" value="'.$voucherentrydate.'" onchange="formatDate(this)" size="30"></td></tr><td><input type="hidden" name="vid" value="'.$vid.'"></td><td><input type="hidden" name="voucherid" value="'.$voucherid.'">';
		echo '</table><br><br><input type="submit" value="Continue"></form>';
	} else {
		if (!$editflag) { //read in array of detail items to be edited 
			$recordSet = &$conn->Execute("select gltransaction.id,gltransaction.glaccountid,gltransaction.amount,glaccount.description,glaccount.name from gltransaction,glaccount where gltransaction.voucherid=".sqlprep($vid)." and glaccount.id=gltransaction.glaccountid");
			$cntr=1;
			while (!$recordSet->EOF) {
				${"gltrid".$cntr}=$recordSet->fields[0]; //gltransaction.id
				${"gltracct".$cntr}=$recordSet->fields[1]; //gltransaction.glaccountid
				${"gltramt".$cntr}=$recordSet->fields[2]; //gltransaction.amount
				${"gltracctname".$cntr}=$recordSet->fields[4]." - ".$recordSet->fields[3];
				$cntr++;
				$recordSet->MoveNext();
			};
			$cntr--;
			echo '<form action="gljourupd1.php" method="post">';
			echo '<table border="1"><tr><td><font color="#FF0000"><b>Voucher: '.$vouchername.'</b></font></td><td><font color="#FF0000"><b>'.$voucherdescription.'</b></font></td></tr></tr><tr><td>Account - Description</td><td>Amount</td><td><input type="hidden" name="editflag" value="1"></td><td><input type="hidden" name="voucherid" value="'.$voucherid.'"></td><input type="hidden" name="vid" value="'.$vid.'"></td><input type="hidden" name="voucherdescription" value="'.$voucherdescription.'"></td><input type="hidden" name="voucherentrydate" value="'.$voucherentrydate.'"></td><td><input type="hidden" name="vouchername" value="'.$vouchername.'"></td>';
			for ($pos=1;$pos<=$cntr;$pos++) {
				echo'<tr><td><select name="gltracct'.$pos.'">';
				$recordSet = &$conn->Execute('select id, name,description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by name');
				while (!$recordSet->EOF) {
					echo '<option value="'.$recordSet->fields[0].'"'.checkequal(${"gltracct".$pos},$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
					$recordSet->MoveNext();
				};
				echo '</select></td><td><input type="text" name="gltramt'.$pos.'" onchange="validatenum(this)" value="'.${"gltramt".$pos}.'"><td><input type="hidden" name="gltrid'.$pos.'" value="'.${"gltrid".$pos}.'"></td><td><input type="hidden" name="cntr" value="'.$cntr.'"></td>';
				echo '</tr>';
			};
			echo '</table><td><font color=red><button type="button" onClick="CheckBal('.$vip.') ">CHECK IF IN BALANCE</button></font></td>';
			echo '</table><input type="submit" value="Save Changes">';
		} else {
			echo 'UPDATING FILE';
			$conn->Execute("update gltransvoucher set voucher=".sqlprep($vouchername).", description=".sqlprep($voucherdescription).",entrydate=".sqlprep($voucherentrydate)." where id=".sqlprep($vid));
			for ($pos=1;$pos<=$cntr;$pos++) {
				$conn->Execute('UPDATE gltransaction SET amount='.sqlprep(${"gltramt".$pos}).', glaccountid='.sqlprep(${"gltracct".$pos}).' where id='.sqlprep(${"gltrid".$pos}));
			};
		};
	};
} else {
	echo texterror('Error: Voucher ID not passed');
};
?>
<?php include('includes/footer.php'); ?>
