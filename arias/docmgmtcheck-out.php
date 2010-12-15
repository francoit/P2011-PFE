<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php // check-out.php - performs checkout and updates database

	$recordSet = &$conn->Execute("SELECT id, realname, final FROM docmgmtdata, docmgmtperms WHERE id = '$id' AND docmgmtperms.rights = '2' AND docmgmtperms.uid = '$userid' AND docmgmtperms.fid = docmgmtdata.id AND status = '0'");
	if (!$recordSet||$recordSet->EOF) die(texterror('Document not found or already checked out.'));
    if ($recordSet->fields[2]) die(texterror('Document has been marked as final.  No further revisions are possible.'));
	$id = $recordSet->fields[0];
	$realname = $recordSet->fields[1];

		
	// since this user has checked it out and will modify it
	// update db to reflect new status
	$conn->Execute("UPDATE docmgmtdata SET status = '$userid' WHERE id = '$id'");

	// get the filename
	copy(IMAGE_UPLOAD_DIR . $id . ".dat", IMAGE_UPLOAD_DIR . $realname);
	$filename = IMAGE_UPLOAD_DIR . $realname;
?>
	<?php include("includes/docmgmt/menu.inc");?>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<th>
	Check Document Out
	</th>
	</tr>
	</table>
	
	<p>
	
	<a href="<?php echo $filename; ?>" target="_new">Click here</a> to check out the selected document and begin downloading it to your local workstation.
	Once the document has completed downloading, you may <a href="docmgmtout.php">continue browsing</a>.
<?php include('includes/footer.php'); ?>
