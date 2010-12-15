<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php // docmgmtadd.php - add a new document to system
if (!$submit) { // form has not been submitted yet -> display form
?>
        <?php include("includes/docmgmt/menu.inc");?>

        <center>
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr>
        <th>
        Add New Document
        </th>
        </tr>
        </table>

        <table border="0" cellspacing="5" cellpadding="5">
        <!-- for file upload, note ENCTYPE -->
        <form action="docmgmtadd.php" method="POST" enctype="multipart/form-data" name="mainform">

        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>"><b>Location</b></td>
        <td colspan=3><input name="file" type="file"'.INC_TEXTBOX.'<?php echo INC_TEXTBOX; ?>></td>
        </tr>

        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>"><b>Category</b></td>
        <td colspan=3><select name="category"<?php echo INC_TEXTBOX; ?>>
        <?
                $recordSet = &$conn->Execute("select id,name from docmgmtcategory order by name");
                while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                        $recordSet->MoveNext();
                };
        ?>
        </select></td>
        </tr>

        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>">Related Item Code:</td>
        <td><input type="text" name="itemcode" onchange="validateint(this)" size="30" maxlength="20"<?php echo INC_TEXTBOX; ?>><a href="javascript:doNothing()" onclick="top.newWin = window.open('lookupitem.php?name=itemcode','cal','dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes')"><img src="<?php echo IMAGE_ITEM_LOOKUP; ?>" border="0" alt="Item Lookup"></a></td>
        </tr>

        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top">Description</td>
        <td colspan=3><input type="Text" name="description" size="50"<?php echo INC_TEXTBOX; ?>></td>
        </tr>

        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top">Version</td>
        <td colspan=3><input type="Text" name="version" size="50" maxlength="10" value="1.0.0"<?php echo INC_TEXTBOX; ?>></td>
        </tr>
        
        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top">Final?</td>
        <td colspan=3><input type="checkbox" name="final" value="1"<?php echo INC_TEXTBOX; ?>></td>
        </tr>

        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top">Comment</td>
        <td colspan=3><textarea name="comment" rows="4"></textarea></td>
        </tr>

        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top"><b><i>View</i> rights</b></td>
        <td><select name="view[]" multiple<?php echo INC_TEXTBOX; ?>>
        <?
                $recordSet = &$conn->Execute("select id,name from genuser where active=1 order by name");
                while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],$userid,' selected').'>'.$recordSet->fields[1]."\n";
                        $recordSet->MoveNext();
                };
        ?>
        </select></td>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top"><b><i>Modify</i> rights</b></td>
        <td><select name="modify[]" multiple<?php echo INC_TEXTBOX; ?>>
        <?
                $recordSet = &$conn->Execute("select id,name from genuser where active=1 order by name");
                while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],$userid,' selected').'>'.$recordSet->fields[1]."\n";
                        $recordSet->MoveNext();
                };
        ?>
        </select></td>
        </tr>

        <tr>
        <td colspan="4" align="center"><input type="Submit" name="submit" value="Add Document"></td>
        </tr>

        </form>
        </table>
        </center>

        </body>
        </html>
<?
} else { // form has been submitted -> process data

        // checks
        // no file!
        if ($file_size <= 0) die(texterror('Uploaded file can not be empty.'));

        // no users with view rights!
        if (sizeof($view) <= 0) die(texterror('You must allow view access to at least one user.'));

        // no users with modify rights!
        if (sizeof($modify) <= 0) die(texterror('You must allow modify access to at least one user.'));

        // check file type
        foreach($allowedFileTypes as $this) {
                if ($file_type == $this) {
                        $allowedFile = 1;
                        break;
                };
        };

        // illegal file type!
        if ($allowedFile != 1) die(texterror('This file type is not supported.'));

       
        //Fix written by Ryan Fox and downloaded from insecure.org
         $nondisallowedfile=1;
         foreach($disallowedfileext as $this) {
                 if (substr_count($file_name, $this)) {
                      $nondisallowedfile=0;
                      break;
                 };
         };
 
         // illegal file type!
         if ($nondisallowedfile != 1) die(texterror('This file type is not supported.'));

        // all checks completed, proceed!
        // get related item id
        $recordSet = &$conn->SelectLimit("select id from item where itemcode='$itemcode' and companyid='$active_company' and cancel=0",1);
        if (!$recordSet->EOF) $itemid = $recordSet->fields[0];

        // INSERT into db
        $conn->Execute("INSERT INTO docmgmtdata (category, owner, realname, created, itemid, description, comment, version, final) VALUES('$category', '$userid', '$file_name', NOW(), '$itemid', '$description', '$comment', '$version', '$final')");

        // get id from INSERT operation
        $recordSet = &$conn->SelectLimit("select id from docmgmtdata where realname='$file_name' order by created desc",1);
        if (!$recordSet->EOF) $fileId = $recordSet->fields[0];

        // INSERT user permissions - view
        for($x=0; $x<sizeof($view); $x++) {
                $conn->Execute("INSERT INTO docmgmtperms (fid, uid, rights) VALUES('$fileId', '$view[$x]', '1')");
        };

        // INSERT user permissions - modify
        for($x=0; $x<sizeof($modify); $x++) {
                $conn->Execute("INSERT INTO docmgmtperms (fid, uid, rights) VALUES('$fileId', '$modify[$x]', '2')");
        };

        // use id to generate a file name
        // save uploaded file with new name
        $newFileName = $fileId . ".dat";
        copy($file, IMAGE_UPLOAD_DIR . $newFileName);

        // back to main page
        echo "<a href=\"docmgmtout.php\">Document successfully added</a>";
};
?>
<?php include('includes/footer.php'); ?>
