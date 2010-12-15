<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php //adminestquotecblquotenotesadd.php
     echo texttitle('Quote Notes Add');
     if ($quotebold) { //if the user has submitted info
//            checkpermissions('est');
//echo 'insert into estquotecblquotenotes (quotebold,quotetext,showwhen,orderflag,gencompanyid,entrydate,entryuserid,lastchangeuserid) VALUES ('.sqlprep($quotebold).', '.sqlprep($quotetext).', '.sqlprep($showwhen).', '.sqlprep($orderflag).', '.sqlprep($active_company).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).')';
             if ($conn->Execute('insert into estquotecblquotenotes (quotebold,quotetext,showwhen,orderflag,gencompanyid,entrydate,entryuserid,lastchangeuserid) VALUES ('.sqlprep($quotebold).', '.sqlprep($quotetext).', '.sqlprep($showwhen).', '.sqlprep($orderflag).', '.sqlprep($active_company).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).')') === false) {
                echo texterror("Error adding notes for quote.");
             } else {
                echo textsuccess("Quote Notes added successfully.");
             };
     };
     echo '<form action="adminestquotecblquotenotesadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Bold Intro Portion of Note:</td><td><input type="text" name="quotebold" size="30" maxlength="50"'.INC_TEXTBOX.'"></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Text Portion of Note:</td><td><textarea name="quotetext" rows=5 cols=40></textarea></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Show on quote when:</td><td><select name="showwhen"'.INC_TEXTBOX.'>';
          echo '<option value="0">Always Show';
          echo '<option value="1">One Ink Only';
          echo '<option value="2">More than One Ink';
     echo '</select></td></tr>';
     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Display Order:</td><td><input type="text" name="orderflag" size="6" maxlength="6"'.INC_TEXTBOX.'"></td></tr>';

     echo '</table><input type="submit" value="Add"></form>';
?>
<?php include('includes/footer.php'); ?>
