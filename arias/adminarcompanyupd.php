<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
echo texttitle($lang['STR_AR_COMPANY_OPTIONS']);
echo '<center>';
if ($imageurl||$interestrate) { // if the user has submitted info
        checkpermissions('ar');
        $recordSet=&$conn->Execute('select count(*) from arcompany where id='.sqlprep($active_company));
        if ($recordSet&&!$recordSet->EOF&&$recordSet->fields[0]) {
              if ($conn->Execute('update arcompany set imageurl='.sqlprep($imageurl).',nextinvoicenum='.sqlprep($nextinvoicenum).',servicecharge='.sqlprep($servicecharge).',interestrate='.sqlprep($interestrate).' where id='.sqlprep($active_company)) === false) die(texterror($lang['STR_AR_COMPANY_UPDATE_FAILED']));
        } else {
              if ($conn->Execute('insert into arcompany (id,imageurl,nextinvoicenum,servicecharge,interestrate) values ('.sqlprep($active_company).', '.sqlprep($imageurl).','.sqlprep($nextinvoicenum).', '.sqlprep($servicecharge).', '.sqlprep($interestrate).')') === false) die(texterror($lang['STR_AR_COMPANY_INSERT_FAILED']));
        };
        echo textsuccess($lang['STR_COMPANY_OPTIONS_CHANGED_SUCCESSFULLY']);
};
echo '<form action="adminarcompanyupd.php" method="post"><table>';
$recordSet = &$conn->Execute('select imageurl,nextinvoicenum,servicecharge,interestrate from arcompany where id='.sqlprep($active_company));
if ($recordSet&&!$recordSet->EOF) {
     $imageurl=rtrim($recordSet->fields[0]);
     $nextinvoicenum=$recordSet->fields[1];
     $servicecharge=$recordSet->fields[2];
     $interestrate=$recordSet->fields[3];
} else {
     $nextinvoicenum=1;
};
echo '<table>';
echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_IMAGE_URL'].': </td><td><input type="text" name="imageurl" size="50" maxlength="150" value="'.$imageurl.'"'.INC_TEXTBOX.'></td></tr>';
echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_PAST_DUE_SERVICE_CHARGE'].': </td><td><input type="text" name="servicecharge" size="20" maxlength="10" onchange="validatenum(this)" value="'.checkdec($servicecharge,PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td></tr>';
echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_INTEREST_RATE'].': </td><td><input type="text" name="interestrate" size="20" maxlength="10" onchange="validatenum(this)" value="'.num_format($interestrate,PREFERRED_DECIMAL_PLACES).'"'.INC_TEXTBOX.'></td></tr>';
echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NEXT_INVOICE_NUMBER'].': </td><td><input type="text" name="nextinvoicenum" onchange="validateint(this)" value="'.$nextinvoicenum.'" size="20" maxlength="20" '.INC_TEXTBOX.'></td></tr>';
echo '<tr><td></td><td><i>'.$lang['STR_WILL_ONLY_BE_USED_IF_HIGHER_THAN'].'</i></td></tr>';
echo '</table><br><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';

echo '</center>';
?>

<?php include('includes/footer.php'); ?>
