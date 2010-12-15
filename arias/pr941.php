<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
      var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?

    echo texttitle($lang['STR_PAYROLL_941']);
    echo '<center>';
    $recordSet=&$conn->Execute('select distinct concat(year(check.checkdate),\' - \',quarter(check.checkdate)) as yr, concat(year(now()),\' - \',quarter(now())) from premplweek,chk,premployee where chk.id=premplweek.checkid and premplweek.employeeid=premployee.id and premployee.gencompanyid='.sqlprep($active_company).' order by yr desc');
    if ($recordSet->EOF) die(texterror($lang['STR_NO_PAYROLL_CHECKS_FOUND']));
    echo '<form action="pr941print.php" target="_new" method="post"><table>';
    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_YEAR_QUARTER'].':</td><td><select name="yearqtr941"'.INC_TEXTBOX.'>';
    while (!$recordSet->EOF) {
            //echo '<option'.checkequal($recordSet->fields[0],$recordSet->fields[1],' selected').'>'.$recordSet->fields[0]."\n";
            //echo checkequal($recordSet->fields[0],$recordSet->fields[1],' selected');
            echo '<option"'.$recordSet->fields[1].'">'.$recordSet->fields[0];
            $recordSet->MoveNext();
    }
    echo '</select></td></tr></table>';
    echo '</table><input type="submit" value="'.$lang['STR_PRINT_941S'].'"></form>';
    
    echo '</center>';
?>
<?php include('includes/footer.php'); ?>
