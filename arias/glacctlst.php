<?php include('includes/main.php'); ?>


<?
	echo texttitle($lang['STR_CHART_OF_ACCOUNTS_FOR'] .$companyname);
	echo texttitle($lang['STR_CHART_OF_ACCOUNTS']);
	if ($description) {
		$queryord="glaccount.description";
	} elseif ($account) {
		$queryord="glaccount.name";
	} else {
		$queryord="accounttype.description, glaccount.name";
	};
	$recordSet = &$conn->Execute("select glaccount.name, glaccount.description, accounttype.description,glaccount.id from glaccount, accounttype where (glaccount.companyid=0 or glaccount.companyid=".sqlprep($active_company).") and accounttype.id=glaccount.accounttypeid order by ".$queryord);
	if ($recordSet->EOF) die(texterror($lang['STR_NO_MATCHING_GL_ACCOUNTS_FOUND']));
	echo '<table border=0><tr><th width="10%"><a href="glacctlst.php?account=1" class="blacklink">'.$lang['STR_ACCOUNT'].'</a></th><th><a href="glacctlst.php?description=1" class="blacklink">'.$lang['STR_DESCRIPTION'].'</a></th><th><a href="glacctlst.php" class="blacklink">'.$lang['STR_ACCOUNT_TYPE'].'</a></th></tr>';
	while (!$recordSet->EOF) {
		echo '<tr><td width="10%" align="center"><a href="glacctlst1.php?glaccountid='.$recordSet->fields[3].'">'.$recordSet->fields[0].'</a></td><td align="left">'.$recordSet->fields[1].'</td><td align="left">'.$recordSet->fields[2]."</td></tr>";
		$recordSet->MoveNext();
	};
	echo '</table>';
    
	echo '<center>';
?>

<?php include('includes/footer.php'); ?>
