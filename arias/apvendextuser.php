<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<script language="JavaScript" src="js/validatephone.js">
</script>
<?
    echo '<center>';
    if ($delete&&$extuserid) {
		if ($conn->Execute('update extuser set cancel=1,canceluserid='.sqlprep($userid).',canceldate=NOW() where id='.sqlprep($extuserid)) === false) die(texterror('Delete failed'));
		echo textsuccess($lang['STR_USER_DELETED_SUCCESSFULLY']);
		unset($extuser);
		unset($vendorid);
	};
        if ($vendorid) { //if user has selected a company
		if ($add) {
			if ($final&&$name&&($password1==$password2)) {
				unset($passstr);
				if ($password1&&($password1==$password2)) $passstr=','.sqlprep(pwencrypt($password1));
				if ($conn->Execute('insert into extuser (name,password,stylesheetid,vendor,entryuserid,lastchangeuserid,entrydate) values ('.sqlprep($name).$passstr.','.sqlprep($stylesheetid).','.sqlprep($vendorid).','.sqlprep($userid).','.sqlprep($userid).',NOW())') === false) die(texterror('Insert failed'));
				echo textsuccess($lang['STR_USER_ADDED_SUCCESSFULLY']);
			} else {
	                        echo '<form action="apvendextuser.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
		                echo '<input type="hidden" name="vendorid" value="'.$vendorid.'"><input type="hidden" name="add" value="1"><input type="hidden" name="final" value="1">';
				echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_USER_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
				echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PASSWORD'].':</td><td><input type="password" name="password1" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
				echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VERIFY'].':</td><td><input type="password" name="password2" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
				//echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STYLE'].':</td><td><select name="stylesheetid"'.INC_TEXTBOX.'>';
				$recordSet2 = &$conn->Execute('select id,name from genstylesheet order by name');
				while (!$recordSet2->EOF) {
					//echo '<option value="'.$recordSet2->fields[0].'">'.$recordSet2->fields[1]."\n";
					$recordSet2->MoveNext();
				};
				echo '</select></td></tr>';
				echo '</table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
			};
		} elseif ($extuserid) {
			if ($update&&$name) {
				unset($passstr);
				if ($password1&&($password1==$password2)) $passstr=', password='.sqlprep(pwencrypt($password1));
				if ($conn->Execute('update extuser set name='.sqlprep($name).', stylesheetid='.sqlprep($stylesheetid).', lastchangeuserid='.sqlprep($userid).$passstr.' where id='.sqlprep($extuserid)) === false) die(texterror('Update failed'));
				echo textsuccess('User updated successfully.');
			} else {
				$recordSet = &$conn->Execute('select name,stylesheetid from extuser where id='.sqlprep($extuserid));
				if (!$recordSet->EOF) {
	        	                echo '<form action="apvendextuser.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
		        	        echo '<input type="hidden" name="vendorid" value="'.$vendorid.'"><input type="hidden" name="update" value="1"><input type="hidden" name="extuserid" value="'.$extuserid.'">';
					echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_USER_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="30" value="'.$recordSet->fields[0].'"'.INC_TEXTBOX.'></td></tr>';
					echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PASSWORD'].':</td><td><input type="password" name="password1" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
					echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VERIFY'].':</td><td><input type="password" name="password2" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
					//echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STYLE'].':</td><td><select name="stylesheetid"'.INC_TEXTBOX.'>';
					$recordSet2 = &$conn->Execute('select id,name from genstylesheet order by name');
					while (!$recordSet2->EOF) {
						//echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[1],$recordSet2->fields[0],' selected').'>'.$recordSet2->fields[1]."\n";
						$recordSet2->MoveNext();
					};
					echo '</select></td></tr>';
					echo '</table><br><input type="submit" value="'.$lang['STR_UPDATE'].'"></form>';
					echo '<br><a href="apvendextuser.php?delete=1&extuserid='.$extuserid.'">'.$lang['STR_DELETE_THIS_USER'].'</a>';
				} else {
					die(texterror($lang['STR_INVALID_USER_ID']));
				};
			};
		} else { //let user pick login
                        echo texttitle($lang['STR_VENDOR_EXTERNAL_USER_UPDATE']);
        	        $recordSet=&$conn->Execute('select extuser.id,extuser.name from extuser where extuser.cancel=0 and extuser.vendor='.sqlprep($vendorid));
			if (!$recordSet->EOF) {
        	                echo '<form action="apvendextuser.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
	                        echo '<input type="hidden" name="vendorid" value="'.$vendorid.'">';
				echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_USER_NAME'].':</td><td><select name="extuserid">';
		                while (!$recordSet->EOF) {
					echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1];
					$recordSet->MoveNext();
		                };
				echo '</select></td></tr>';
	                        echo '</table><br><input type="submit" value="'.$lang['STR_SELECT'].'"></form>';
			};
			echo '<br><a href="apvendextuser.php?add=1&vendorid='.$vendorid.'">'.$lang['STR_ADD_EXTERNAL_USER'].'</a>';
                };
        } else { //let user pick vendor
                echo texttitle($lang['STR_VENDOR_EXTERNAL_USER_UPDATE']);
                echo '<form action="apvendextuser.php" method="post" name="mainform"><table>';
                formapvendorselect('vendorid');
                echo '</table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
                
                echo '</center>';
        };
?>

<?php include('includes/footer.php'); ?>
