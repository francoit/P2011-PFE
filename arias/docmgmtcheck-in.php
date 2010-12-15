<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php // docmgmtcheck-in.php - uploads a new version of a file
if (!$submit) {// form not yet submitted, display initial form

	// pre-fill the form with some information so that user knows which file is being updated
	$recordSet = &$conn->Execute("SELECT description, realname, version from docmgmtdata WHERE id = '$id' AND status = '$userid'");
	if (!$recordSet->EOF) {
		$description = $recordSet->fields[0];
		$realname = $recordSet->fields[1];
        $version = $recordSet->fields[2];
		if ($description == "") $description = "No description available";
		$recordSet->MoveNext();
	};	
	?>
	<center>
	<?php include("includes/docmgmt/menu.inc"); ?>
	
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<th>
	Document Check In
	</th>
	</tr>
	</table>
	
	<p>
	
	<table border="0" cellspacing="5" cellpadding="5">
	<form action="docmgmtcheck-in.php" method="POST" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php echo $id; ?>">
	<tr>
	<td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>"><b>Document</b></td>
	<td><b><?php echo $realname; ?></b></td>
	</tr>
	
	<tr>
	<td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>"><b>Description</b></td>
	<td><?php echo $description; ?></td>
	</tr>
 
    <input type="hidden" name="oldversion" value="<?php echo $version; ?>">
    
    <tr>
    <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top">New Version</td>
    <td colspan=3><input type="Text" name="newversion" size="50" maxlength="10" value="<?php echo $version; ?>"<?php echo INC_TEXTBOX; ?>></td>
    </tr>

    <tr>
    <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top">Final?</td>
    <td colspan=3><input type="checkbox" name="final" value="1"<?php echo INC_TEXTBOX; ?>></td>
    </tr>
	
	<tr>
	<td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>"><b>Location</b></td>
	<td><input name="file" type="file"></td>
	</tr>
	
	<tr>
	<td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>">Note (for revision log)</td>
	<td><textarea name="note"></textarea></td>
	</tr>
	
	<tr>
	<td colspan="4" align="center"><input type="Submit" name="submit" value="Check  Document In"></td>
	</tr>

	</form>
	</table>
	</center>
	<?
} else {
// form has been submitted, process data
	
	// checks
	
	// no file!
	if ($file_size <= 0) die(texterror('File must not be empty.'));
	
	// check file type
	foreach($allowedFileTypes as $this) {
		if ($file_type == $this) { 
			$allowedFile = 1;
			break; 
		}; 
	};
	// illegal file type!
	if ($allowedFile != 1) die(texterror('This file type is not supported.'));
	
	// query to ensure that user has modify rights
	$recordSet = &$conn->Execute("SELECT rights FROM docmgmtperms WHERE fid = '$id' AND uid = '$userid' AND rights = '2'");
	if (!$recordSet||$recordSet->EOF) die(texterror('You do not have modify rights to this file.'));

	// update revision log
	$conn->Execute("INSERT INTO docmgmtlog (id, modified_on, modified_by, note, oldversion, newversion) VALUES('$id', NOW(), '$userid', '$note', '$oldversion', '$newversion')");

	// update file status
	$conn->Execute("UPDATE docmgmtdata SET status = '0', version='$newversion', final='$final' WHERE id='$id'");
		
	// rename and save file
	$newFileName = $id . ".dat";
	copy($file, IMAGE_UPLOAD_DIR . $newFileName);

	// clean up and back to main page
	echo "<a href=\"docmgmtout.php\">Document successfully checked in</a>";
};
?>
<?php include('includes/footer.php'); ?>
