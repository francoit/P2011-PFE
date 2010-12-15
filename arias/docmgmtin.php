<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php include("includes/docmgmt/menu.inc"); ?>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<th>
Documents Currently Checked Out To You
</th>
</tr>
</table>
<p>

<table border="0" cellspacing="0" cellpadding="0">

<?
	$recordSet = &$conn->Execute("SELECT count(*) FROM docmgmtdata,genuser WHERE status = '$userid' AND docmgmtdata.owner = genuser.id");
	if (!$recordSet->EOF) $counter=$recordSet->fields[0];
	$recordSet = &$conn->Execute("SELECT docmgmtdata.id, genuser.name, realname, created, description, status, docmgmtdata.version FROM docmgmtdata,genuser WHERE status = '$userid' AND docmgmtdata.owner = genuser.id");
	while (!$recordSet->EOF) {
?>

<tr>
<td><?php echo $counter; ?> document(s) checked out to you<p></td>
</tr>
<?
	$id = $recordSet->fields[0];
	$owner = $recordSet->fields[1];
	$realname = $recordSet->fields[2];
	$created = $recordSet->fields[3];
	$description = $recordSet->fields[4];
	$status = $recordSet->fields[5];
    $version = $recordSet->fields[6];
	// correction
	if ($description == "") $description = "No information available";
	$filename = IMAGE_UPLOAD_DIR . $id . ".dat";
	?>
	<tr>
	<td><img src="images/na.jpg" width=40 height=33 alt="" border="0" align="absmiddle">&nbsp;&nbsp;<b><?php echo $realname; ?></b></td>
	<td rowspan="3" width="35">&nbsp;</td>
	<td rowspan="3" align="center" valign="bottom">
	<a href="docmgmtcheck-in.php?id=<?php echo $id; ?>"><img src="images/ci.jpg" width=40 height=40 alt="Check Document Back In" border="0" align="absmiddle"><br><font size="-1" color="">Check Document Back In</font></a></td>
	</tr>
	
	<tr>
	<td><font size="-1"><?php echo $description; ?></font></td>
	</tr>

	<tr>
	<td><font size="-1">Version: <?php echo $version; ?></font></td>
	</tr>

	<tr>
	<td><font size="-1">Document created on <?php echo fixDate($created); ?> by <b><?php echo $owner; ?></b> | <?php echo filesize($filename); ?> bytes</font>	</td>
	</tr>
	
	<tr>
	<td colspan=3>&nbsp;</td>
	</tr>
	<?
		$recordSet->MoveNext();
	};
?>
</table>

</center>
<?php include('includes/footer.php'); ?>
