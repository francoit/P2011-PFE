<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php // docmgmtview.php - performs download without updating database

	$recordSet = &$conn->Execute("SELECT id, realname FROM docmgmtdata, docmgmtperms WHERE id = '$id' AND docmgmtperms.rights = '1' AND docmgmtperms.uid = '$userid' AND docmgmtperms.fid = docmgmtdata.id");
	if ($recordSet->EOF) {
		die(texterror('File not found.'));
	} else {
		$id = $recordSet->fields[0];
		$realname = $recordSet->fields[1];

		copy(IMAGE_UPLOAD_DIR . $id . ".dat", IMAGE_UPLOAD_DIR . $realname);
		$filename = IMAGE_UPLOAD_DIR . $realname;
?>
	<html>
	<head>
	<basefont face="Verdana">
	</head>
	
	<body>
	
	<?php include("includes/docmgmt/menu.inc");?>	
	
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<th>
	View Document
	</th>
	</tr>
	</table>
	
	<p>
	
	<a href="<?php echo $filename;?>" target="_new">Click here</a> to begin downloading the selected document to your local workstation.
	</form>
	Once the document has completed downloading, you may <a href="docmgmtout.php">continue browsing</a>.
	</body>
	</html>
<?
};
?>
<?php include('includes/footer.php'); ?>
