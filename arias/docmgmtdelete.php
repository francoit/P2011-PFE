<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php // docmgmtdelete.php - delete a file from the respository and the db

$recordSet = &$conn->Execute("SELECT status FROM docmgmtdata WHERE id = '$id' AND owner = '$userid' AND status = '0'");
if ($recordSet->EOF) die(texterror('File not found.'));

// delete from db
$conn->Execute("DELETE FROM docmgmtdata WHERE id = '$id'");

// delete from db
$conn->Execute("DELETE FROM docmgmtperms WHERE fid = '$id'");

$conn->Execute("DELETE FROM docmgmtlog WHERE id = '$id'");

// delete from directory
unlink(IMAGE_UPLOAD_DIR . $id . ".dat");

// clean up and back to main page
echo "<a href=\"docmgmtout.php\">Document successfully deleted</a>";
?>
<?php include('includes/footer.php'); ?>
