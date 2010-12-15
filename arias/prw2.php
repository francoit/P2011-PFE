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
<?php //prw2.php - copyright 2001 by Noguska - Fostoria, OH
    echo texttitle(capstest('Payroll').' W2');
    $recordSet=&$conn->Execute('select distinct extract(year from chk.checkdate) as yr, extract(year from NOW()) from premplweek,chk,premployee where chk.id=premplweek.checkid and premplweek.employeeid=premployee.id and premployee.gencompanyid='.sqlprep($active_company).' order by yr desc');
    if ($recordSet->EOF) die(texterror('No payroll checks found.'));
    echo '<form action="prw2print.php" target="_new" method="post"><table>';
    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Year:</td><td><select name="w2year"'.INC_TEXTBOX.'>';
    while (!$recordSet->EOF) {
            echo '<option'.checkequal($recordSet->fields[0],$recordSet->fields[1],' selected').'>'.$recordSet->fields[0]."\n";
            $recordSet->MoveNext();
    };
    echo '</select></td></tr></table>';
    echo '</table><input type="submit" value="Print W2s"></form>';
?>
<?php include('includes/footer.php'); ?>
