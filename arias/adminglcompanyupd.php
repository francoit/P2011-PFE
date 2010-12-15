<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?
     echo '<center>';
     echo texttitle($lang['STR_GENERAL_LEDGER_COMPANY_OPTIONS']);
     if ($fiscalbeginmonth) { // if the user has submitted info
          checkpermissions('gl');
          $conn->Execute('update glcompany set fiscalbeginmonth='.sqlprep($fiscalbeginmonth).' where id='.sqlprep($active_company));
          $conn->Execute('insert into glcompany (id,fiscalbeginmonth) values ('.sqlprep($active_company).', '.sqlprep($fiscalbeginmonth).')');
          echo textsuccess($lang['STR_COMPANY_OPTIONS_CHANGED_SUCCESSFULLY']);
     };
     echo '<form action="adminglcompanyupd.php" method="post"><table><tr><td>';
     $recordSet = &$conn->Execute('select fiscalbeginmonth from glcompany where id='.sqlprep($active_company));
     if (!$recordSet->EOF) $fiscalbeginmonth=$recordSet->fields[0];
     echo ' '.$lang['STR_FISCAL_BEGIN_MONTH'].' <select name="fiscalbeginmonth">';
     for ($i=1;$i<=12;$i++) {
          echo '<option value="'.$i.'"'.checkequal($i,$fiscalbeginmonth," selected").'>'.month2monthlong(num2month($i));
     };
     echo '</select></td></tr></table><br><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
     
     echo '</center>';
?>
<?php include('includes/footer.php'); ?>
