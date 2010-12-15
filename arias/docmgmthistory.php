<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php // history.php - display revision history

// query to obtain document information and verify that user has rights
$recordSet = &$conn->Execute("SELECT genuser.name, docmgmtcategory.name, docmgmtdata.realname, docmgmtdata.created, docmgmtdata.description, docmgmtdata.comment, docmgmtdata.status, docmgmtdata.version FROM docmgmtdata, genuser, docmgmtcategory, docmgmtperms WHERE docmgmtdata.id = '$id' AND docmgmtdata.owner = genuser.id AND docmgmtdata.category = docmgmtcategory.id AND docmgmtperms.rights = '1' AND docmgmtperms.uid = '$userid' AND docmgmtdata.id = docmgmtperms.fid");
if ($recordSet->EOF) {
	die(texterror('File not found'));
} else {
	// obtain data from resultset
	$owner=$recordSet->fields[0];
	$category=$recordSet->fields[1];
	$realname=$recordSet->fields[2];
	$created=$recordSet->fields[3];
	$description=$recordSet->fields[4];
	$comments=$recordSet->fields[5];
	$status=$recordSet->fields[6];
	$version=$recordSet->fields[7];
};
// corrections
if ($description == "") $description = "No description available";
if ($comment == "") $comment = "No author comments available";
$filename = IMAGE_UPLOAD_DIR . $id . ".dat";
?>
<?php include("includes/docmgmt/menu.inc");?>

<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<th>
Document Revision History
</th>
</tr>
</table>

<p>

<table border="0" cellspacing="4" cellpadding="1">

<tr>
<td><?php 
// check file status, display appropriate icon
if ($status == 0) { ?><img src="images/a.jpg" width=40 height=33 alt="" border=0 align="absmiddle"><?php } else { ?><img src="images/na.jpg" width=40 height=33 alt="" border=0 align="absmiddle"> <?php } ?> &nbsp;&nbsp;<font size="+1"><?php echo $realname; ?></font></td>
</tr>

<tr>
<td>Category: <?php echo $category; ?></td>
</tr>

<tr>
<td>File size: <?php echo filesize($filename); ?> bytes</td>
</tr>

<tr>
<td>Created on: <?php echo fixDate($created); ?></td>
</tr>

<tr>
<td>Owned by: <?php echo $owner; ?></td>
</tr>

<tr>
<td>Description of contents: <?php echo $description; ?></td>
</tr>

<tr>
<td>Version: <?php echo $version; ?></td>
</tr>

<?
if ($final) {
    echo '<tr><td>Final Revision: Yes</td></tr>';
};
?>

<tr>
<td>Author comment: <?php echo $comment; ?></td>
</tr>

<tr>
<td>&nbsp;</td>
</tr>

<!-- history table -->
<tr>
<td>
<img src="images/history.jpg" width=40 height=40 alt="" border="0" align="absmiddle">&nbsp;&nbsp;Revision History
</td>
</tr>

<tr>
<td colspan=2>
	<table border="0" cellspacing="5" cellpadding="5">
	<tr>
	<td><font size="-1"><b>Modified on</b></font>
	<td><font size="-1"><b>By</b></font>
	<td><font size="-1"><b>Note</b></font>	</td>
	<td><font size="-1"><b>Old Version</b></font>	</td>
	<td><font size="-1"><b>New Version</b></font>	</td>
	</tr>
	<?
	$recordSet = &$conn->Execute("SELECT genuser.name, docmgmtlog.modified_on, docmgmtlog.note, docmgmtlog.oldversion, docmgmtlog.newversion FROM docmgmtlog, genuser WHERE docmgmtlog.id = '$id' AND genuser.id = docmgmtlog.modified_by ORDER BY docmgmtlog.modified_on DESC");
	while (!$recordSet->EOF) {
		$modified_by=$recordSet->fields[0];
		$modified_on=$recordSet->fields[1];
		$note = $recordSet->fields[2];
		$oldversion = $recordSet->fields[3];
		$newversion = $recordSet->fields[4];
	?>
	<tr>
	<td><font size="-1"><?php echo fixDate($modified_on); ?></font></td>
	<td><font size="-1"><?php echo $modified_by; ?></font></td>
	<td><font size="-1"><?php echo $note; ?></font></td>
	<td><font size="-1"><?php echo $oldversion; ?></font></td>
	<td><font size="-1"><?php echo $newversion; ?></font></td>
	</tr>
	<?
		$recordSet->MoveNext();
	};
	?>
	</table>
</td>
</tr>

</table>
</center>
<?php include('includes/footer.php'); ?>
