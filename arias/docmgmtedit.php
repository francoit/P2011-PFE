<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
if (!$submit) { // form not yet submitted, display initial form
        // query to obtain current properties and rights
        $recordSet = &$conn->Execute("SELECT category, realname, description, comment, itemid, version, final FROM docmgmtdata WHERE id = '$id' AND status = '0' AND owner = '$userid'");
        if ($recordSet->EOF) {
                die(texterror("File not found."));
        } else {
                // obtain data from resultset
                $category = $recordSet->fields[0];
                $realname = $recordSet->fields[1];
                $description = $recordSet->fields[2];
                $comment = $recordSet->fields[3];
                $itemid = $recordSet->fields[4];
                $version = $recordSet->fields[5];
                $final = $recordSet->fields[6];
        };
        ?>
        <?php include("includes/docmgmt/menu.inc");?>

        <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr>
        <th>
        Edit Document Properties
        </th>
        </tr>
        </table>

        <p>
        <center>
        <table border="0" cellspacing="5" cellpadding="5">
        <form action="docmgmtedit.php" method="POST" name="mainform">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top">Name</td>
        <td colspan=3><b><?php echo $realname; ?></b></td>
        </tr>

        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top">Category</td>
        <td colspan=3><select name="category"<?php echo INC_TEXTBOX; ?>>
        <?
        // query for category list
        $recordSet = &$conn->Execute("SELECT id, name FROM docmgmtcategory ORDER BY name");
        while (!$recordSet->EOF) {
                $ID=$recordSet->fields[0];
                $CATEGORY = $recordSet->fields[1];
                echo "<option value=\"$ID\"".checkequal($CATEGORY,$category,' selected').">$CATEGORY</option>";
                $recordSet->MoveNext();
        };
        ?>
        </select></td>
        </tr>
        <?
        // get related item id
        $recordSet = &$conn->SelectLimit("select itemcode, description from item where id='$itemid' and companyid='$active_company' and cancel=0",1);
        if (!$recordSet->EOF) {
                $itemcode = $recordSet->fields[0];
                $itemdescription = ' ('.$recordSet->fields[1].')';
        };
        ?>
        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>">Related Item Code:</td>
        <td><input type="text" name="itemcode" onchange="validateint(this)" size="30" value="<?php echo $itemcode; ?>" maxlength="20"<?php echo INC_TEXTBOX; ?>><a href="javascript:doNothing()" onclick="top.newWin = window.open('lookupitem.php?name=itemcode','cal','dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes')"><img src="<?php echo IMAGE_ITEM_LOOKUP; ?>" border="0" alt="Item Lookup"></a><font size="-1"><?php echo $itemdescription; ?></font></td>
        </tr>

        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top">Description</td>
        <td colspan=3><input type="Text" name="description" size="50" value="<?php echo $description; ?>"<?php echo INC_TEXTBOX; ?>></td>
        </tr>

        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top">Version</td>
        <td colspan=3><input type="Text" name="version" size="50" maxlength="10" value="<?php echo $version; ?>"<?php echo INC_TEXTBOX; ?>></td>
        </tr>

        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top">Final?</td>
        <td colspan=3><input type="checkbox" name="final" value="1"<?php echo checkequal($final,1,' checked disabled') ?><?php echo INC_TEXTBOX; ?>></td>
        </tr>


        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top">Comment</td>
        <td colspan=3><textarea name="comment" rows="4"><?php echo $comment; ?></textarea></td>
        </tr>

        <tr>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top"><b><i>View</i> rights</b></td>
        <td><select name="view[]" multiple<?php echo INC_TEXTBOX; ?>>
        <?
        // query for view list
        $recordSet = &$conn->Execute("SELECT uid FROM docmgmtdata, docmgmtperms WHERE docmgmtperms.fid = '$id' AND docmgmtdata.id = docmgmtperms.fid AND status = '0' AND docmgmtperms.rights = '1'");
        $viewList = array();
        $y=0;
        while (!$recordSet->EOF) {
                $viewList[$y] = $recordSet->fields[0];
                $y++;
                $recordSet->MoveNext();
        };

        // now query to get a complete list of users and user IDs
        $recordSet = &$conn->Execute("SELECT id, name FROM genuser ORDER BY name");
        while (!$recordSet->EOF) {
                $ID=$recordSet->fields[0];
                $USERNAME=$recordSet->fields[1];
                $str = "<option value=\"$ID\"";
                // iterate through current list of users and select those that match
                foreach($viewList as $temp) {
                        if ($ID == $temp) { $str .= " selected"; }
                };
                $str .= ">$USERNAME</option>";
                echo $str;
                $recordSet->MoveNext();
        };
        ?>
        </select></td>
        <td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top"><b><i>Modify</i> rights</b></td>
        <td><select name="modify[]" multiple<?php echo INC_TEXTBOX; ?>>
        <?
        // do the same thing for the modify list
        $recordSet = &$conn->Execute("SELECT uid FROM docmgmtdata, docmgmtperms WHERE docmgmtperms.fid = '$id' AND docmgmtdata.id = docmgmtperms.fid AND status = '0' AND docmgmtperms.rights = '2'");
        $viewList = array();
        $y=0;
        while (!$recordSet->EOF) {
                $viewList[$y] = $recordSet->fields[0];
                $y++;
                $recordSet->MoveNext();
        };

        // now query to get a complete list of users and user IDs
        $recordSet = &$conn->Execute("SELECT id, name FROM genuser ORDER BY name");
        while (!$recordSet->EOF) {
                $ID=$recordSet->fields[0];
                $USERNAME=$recordSet->fields[1];
                $str = "<option value=\"$ID\"";
                // iterate through current list of users and select those that match
                foreach($viewList as $temp) {
                        if ($ID == $temp) { $str .= " selected"; }
                };
                $str .= ">$USERNAME</option>";
                echo $str;
                $recordSet->MoveNext();
        };
        ?>
        </select></td>
        </tr>
        <tr>
        <td colspan="4" align="center"><input type="Submit" name="submit" value="Update Document Properties"></td>
        </tr>
        </form>
        </table>
        </center>
<?
} else {
// form submitted, process data

        // check submitted data
        // at least one user must have "view" and "modify" rights
        if (sizeof($view) <= 0) die(texterror("At least one user must have view rights."));
        if (sizeof($modify) <= 0) die(texterror("At least one user must have modify rights."));

        // query to verify
        $recordSet = &$conn->Execute("SELECT status FROM docmgmtdata WHERE id = '$id' AND owner = '$userid' AND status = '0'");
        if ($recordSet->EOF) {
                die(texterror("File not found."));
        } else {
                // get related item id
                $recordSet = &$conn->SelectLimit("select id from item where itemcode='$itemcode' and companyid='$active_company' and cancel=0",1);
                if (!$recordSet->EOF) $itemid = $recordSet->fields[0];

                // update db with new information
                $conn->Execute("UPDATE docmgmtdata SET itemid='$itemid', category='$category', description='$description', comment='$comment', version='$version', final='$final' WHERE id = '$id'");

                // clean out old permissions
                $conn->Execute("DELETE FROM docmgmtperms WHERE fid = '$id'");


                // INSERT user permissions - view
                for($x=0; $x<sizeof($view); $x++) {
                        $conn->Execute("INSERT INTO docmgmtperms (fid, uid, rights) VALUES('$id', '$view[$x]', '1')");
                };

                // INSERT user permissions - modify
                for($x=0; $x<sizeof($modify); $x++) {
                        $conn->Execute("INSERT INTO docmgmtperms (fid, uid, rights) VALUES('$id', '$modify[$x]', '2')");
                };

                echo "<a href=\"docmgmtout.php\">Document successfully updated</a>";
        };
};
?>
<?php include('includes/footer.php'); ?>
