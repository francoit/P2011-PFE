<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php // docmgmtdetails.php - displays details for a file, given file id

$recordSet = &$conn->Execute("SELECT docmgmtdata.id, docmgmtdata.owner, docmgmtcategory.name, genuser.name, docmgmtdata.realname, docmgmtdata.created, docmgmtdata.description, docmgmtdata.comment, docmgmtdata.status, docmgmtdata.itemid, docmgmtdata.version, docmgmtdata.final FROM docmgmtdata, genuser, docmgmtcategory, docmgmtperms WHERE docmgmtdata.id = '$id' AND docmgmtdata.owner = genuser.id AND docmgmtdata.category = docmgmtcategory.id AND docmgmtperms.rights = '1' AND docmgmtperms.uid = '$userid' AND docmgmtdata.id = docmgmtperms.fid");
if ($recordSet->EOF) {
        die(texterror('File not found.'));
} else {
?>
<?php include("includes/docmgmt/menu.inc");?>

<table width="100%" border="0" cellspacing="0" cellpadding="3">
<tr>
<th>
Document Properties
</th>
</tr>
</table>

<p>

<table border="0" cellspacing="4" cellpadding="1">
<?
// display details
$id = $recordSet->fields[0];
$ownerId = $recordSet->fields[1];
$category = $recordSet->fields[2];
$owner = $recordSet->fields[3];
$realname = $recordSet->fields[4];
$created = $recordSet->fields[5];
$description = $recordSet->fields[6];
$comment = $recordSet->fields[7];
$status = $recordSet->fields[8];
$itemid = $recordSet->fields[9];
$version = $recordSet->fields[10];
$final = $recordSet->fields[11];
// corrections
if ($description == "") $description = "No description available";
if ($comment == "") $comment = "No author comments available";
$filename = IMAGE_UPLOAD_DIR . $id . ".dat";

?>
<tr>
<td><?
// display red or green icon depending on file status
if ($status == 0) { ?> <img src="images/a.jpg" width=40 height=33 alt="" border=0 align="absmiddle"><?php } else { ?> <img src="images/na.jpg" width=40 height=33 alt="" border=0 align="absmiddle"> <?php } ?> &nbsp;&nbsp;<font size="+1"><?php echo $realname; ?></font></td>
</tr>

<tr>
<td>Category: <?php echo $category; ?></td>
</tr>
<?
        // get related item id
        $recordSet = &$conn->SelectLimit("select itemcode,description from item where id='$itemid' and companyid='$active_company' and cancel=0",1);
        if (!$recordSet->EOF) {
                $itemcode = $recordSet->fields[0];
                $itemdescription = $recordSet->fields[1];
        };
?>
<tr>
<td>Related Item: <?php if ($itemcode) echo $itemcode.' - '.$itemdescription; ?></td>
</tr>


<tr>
<td>File size: <?php echo filesize($filename); ?> bytes</td>
</tr>

<tr>
<td>Created on: <?php echo fixDate($created); ?></td>
</tr>

<tr>
<td>Owner: <?php echo $owner; ?></td>
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

<?
if ($status != 0) { // status != 0 -> file checked out to another user
        $recordSet = &$conn->Execute("SELECT genuser.name FROM docmgmtdata, genuser WHERE genuser.id = docmgmtdata.status");
        if (!$recordSet->EOF) $checkedTo=$recordSet->fields[0];
};

if ($checkedTo) {
    echo '<tr><td>Currently checked out to: '.$checkedTo.'</td></tr>';
};
?>

<!-- available actions -->
<tr>
<td>
        <table border="0" cellspacing="5" cellpadding="5">
        <tr>
        <!-- inner table begins -->
        <!-- view option available at all time, place it outside the block -->
        <td align="center"><a href="docmgmtview.php?id=<?php echo $id; ?>"><img src="images/view.jpg" width=40 height=40 alt="" border="0"><br><font size="-1">View Document</font></a></td>

                <?
                if ($status == 0 && !$final) {
                // status = 0 -> file available for checkout
                // check if user has modify rights
                        $recordSet = &$conn->Execute("SELECT status FROM docmgmtdata, docmgmtperms WHERE docmgmtperms.fid = '$id' AND docmgmtperms.uid = '$userid' AND docmgmtperms.rights = '2' AND docmgmtdata.status = '0' AND docmgmtdata.id = docmgmtperms.fid");
                        if (!$recordSet->EOF) {
                        ?>
                        <td align="center"><a href="docmgmtcheck-out.php?id=<?php echo $id; ?>"><img src="images/co.jpg" width=40 height=40 alt="" border="0"><br><font size="-1">Check Document Out</font></a></td>
                        <?
                        }

                        if ($ownerId == $userid) {
                                // if user is also the owner of the file AND file is not checked out
                                // additional actions are available
                                ?>
                                <td align="center"><a href="docmgmtedit.php?id=<?php echo $id; ?>"><img src="images/info.jpg" width=40 height=40 alt="" border="0"><br><font size="-1">Edit Document Properties</font></a></td>
                                <td align="center"><a href="javascript:confirmdelete('docmgmtdelete.php?id=<?php echo $id; ?>')"><img src="images/delete.jpg" width=40 height=40 alt="" border="0"><br><font size="-1">Delete Document</font></a></td>
                        <?
                        };
                        ?>
                <?
                };
                ?>
        <td align="center"><a href="docmgmthistory.php?id=<?php echo $id; ?>"><img src="images/history.jpg" width=40 height=40 alt="" border="0"><br><font size="-1">Revision History</font></a></td>

        </tr>
        <!-- inner table ends -->
        </table>
</td>
</tr>

</table>
<?
};
?>
<?php include('includes/footer.php'); ?>
