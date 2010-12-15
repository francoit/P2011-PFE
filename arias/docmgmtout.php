<?php include('includes/main.php'); ?>
<?php include("includes/docmgmt/menu.inc");?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<th>
<?
echo $lang['STR_DOCUMENT_LISTING'];
?>
</th>
<!-- if last operation returned a status code, display here -->
<th align="right"><b><font size="-1" face="Arial" color="White"><?php if ($message) { echo "<i>Last message: $message</i>"; } ?></th></font></b>
</tr>
</table>

<p>

<table border="0" cellspacing="0" cellpadding="0">

<?
// get a list of documents the user has "view" permission for
$query = "SELECT docmgmtdata.id, genuser.name, docmgmtdata.realname, docmgmtdata.created, docmgmtdata.description, docmgmtdata.comment, docmgmtdata.status, docmgmtdata.final FROM docmgmtdata, genuser, docmgmtperms WHERE docmgmtdata.id = docmgmtperms.fid AND genuser.id = docmgmtdata.owner AND docmgmtperms.uid = '$userid' AND docmgmtperms.rights = '1'";
	// this page also serves as the "search results" page if coming from the search form, $keyword and $where will exist so modify the query with additional constraints
	if ($keyword != "" && isset($where)) {
		switch ($where) {
		// description search
			case 1:
				$query .= " AND (docmgmtdata.description LIKE '%$keyword%')";
				break;
	
		// filename search
			case 2:
				$query .= " AND (docmgmtdata.realname LIKE '%$keyword%')";
				break;
	
		// comment search
			case 3:
				$query .= " AND (docmgmtdata.comment LIKE '%$keyword%')";
				break;
	
		// search all!
			case 4:
				$query .= " AND (docmgmtdata.description LIKE '%$keyword%' OR docmgmtdata.realname LIKE '%$keyword%' OR docmgmtdata.comment LIKE '%$keyword%')";
				break;
		};
	};

$query .= " ORDER BY created DESC";

// iterate through resultset
$recordSet = &$conn->Execute($query);
while (!$recordSet->EOF) {
	$id=$recordSet->fields[0];
	$owner=$recordSet->fields[1];
	$realname=$recordSet->fields[2];
	$created=$recordSet->fields[3];
	$description=$recordSet->fields[4];
	$comment=$recordSet->fields[5];
	$status=$recordSet->fields[6];
	$final=$recordSet->fields[7];

	// correction for empty description
	if ($description == "") $description = "No description available";
	
	// set filename for filesize() call below
	$filename = IMAGE_UPLOAD_DIR . $id . ".dat";
	
	// begin displaying file list with basic information
	?>
	<tr>
	<td><b><?php echo "<a href=\"docmgmtdetails.php?id=$id\">$realname</a>"; ?></b></td>
	</tr>
	
	<tr>
	<td><font size="-1"><?php echo $description; ?></font></td>
	</tr>
	
	<tr>
	<td><font size="-1">Document created on <?php echo fixDate($created); ?> by <b><?php echo $owner; ?></b> | <?php echo filesize($filename); ?> bytes</font></td>
	</tr>
	
	<?php 
		// check the status of each file
		// 0 -> file is not checked out
		// display appropriate message and icon
		if ($status == 0&&!$final) {
		?>
		<tr>
		<td><img src="images/a.jpg" width=40 height=33 alt="" border=0 align="absmiddle"><font size="-1" color="#43c343"><b>This document is available to be checked out</b></font></td>
		</tr>
		<?
        } elseif ($final==1) {
		    echo '<tr><td><img src="images/na.jpg" width=40 height=33 alt="" border=0 align="absmiddle"><font size="-1" color="#e9202a">This document has been finalized.</b></font></td></tr>';
		} else {
		// not 0 -> implies file is checked out to another user
		// run a query to find out user's name
		$recordSet2 = &$conn->Execute("SELECT name FROM genuser WHERE id = '$status'");
		if (!$recordSet->EOF2) $user=$recordSet2->fields[0];
		?>
		<tr>
		<td>
		<img src="images/na.jpg" width=40 height=33 alt="" border=0 align="absmiddle"><font size="-1" color="#e9202a">This document is currently checked out to <b><?php echo $user; ?></b></font>
		</td>
		</tr>
		<?
		};
		?>
	
	<tr>
	<td>
	&nbsp;
	</td>
	</tr>
	<?
		$recordSet->MoveNext();
	};

?>
</table>
<?php include('includes/footer.php'); ?>
