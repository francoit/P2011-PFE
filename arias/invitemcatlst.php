<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo texttitle($lang['STR_ITEM_CATEGORY_LIST']);
     echo '<center>';
     $recordSet = &$conn->Execute('select name,seasonname1,seasonname2,seasonname3,seasonname4,seasonbegin1,seasonbegin2,seasonbegin3,seasonbegin4,seasonend1,seasonend2,seasonend3,seasonend4 from itemcategory order by name');
     if ($recordSet->EOF) die(texterror($lang['STR_NO_ITEM_CATEGORIES_FOUND']));
     echo '<table border=1><tr><th rowspan="2">'.$lang['STR_CATEGORY_NAME'].'</th><th colspan=3>'.$lang['STR_SEASON_ONE'].'</th><th colspan=3>'.$lang['STR_SEASON_TWO'].'</th><th colspan=3>'.$lang['STR_SEASON_THREE'].'</th><th colspan=3>'.$lang['STR_SEASON_FOUR'].'</th></tr>';
     echo '<tr><th>'.$lang['STR_NAME'].'</th><th>'.$lang['STR_BEGIN_MONTH'].'</th><th>'.$lang['STR_END_MONTH'].'</th><th>'.$lang['STR_NAME'].'</th><th>'.$lang['STR_BEGIN_MONTH'].'</th><th>'.$lang['STR_END_MONTH'].'</th><th>'.$lang['STR_NAME'].'</th><th>'.$lang['STR_BEGIN_MONTH'].'</th><th>'.$lang['STR_END_MONTH'].'</th><th>'.$lang['STR_NAME'].'</th><th>'.$lang['STR_BEGIN_MONTH'].'</th><th>'.$lang['STR_END_MONTH'].'</th></tr>';
     while (!$recordSet->EOF) {
                    $name=$recordSet->fields[0];
                    $seasonname1=$recordSet->fields[1];
                    $seasonname2=$recordSet->fields[2];
                    $seasonname3=$recordSet->fields[3];
                    $seasonname4=$recordSet->fields[4];
                    $seasonbegin1=num2month($recordSet->fields[5]);
                    $seasonbegin2=num2month($recordSet->fields[6]);
                    $seasonbegin3=num2month($recordSet->fields[7]);
                    $seasonbegin4=num2month($recordSet->fields[8]);
                    $seasonend1=num2month($recordSet->fields[9]);
                    $seasonend2=num2month($recordSet->fields[10]);
                    $seasonend3=num2month($recordSet->fields[11]);
                    $seasonend4=num2month($recordSet->fields[12]);
                    echo '<tr><td><b>'.$name.'</b></td><td>'.$seasonname1.'</td><td>'.$seasonbegin1.'</td><td>'.$seasonend1.'</td><td>'.$seasonname2.'</td><td>'.$seasonbegin2.'</td><td>'.$seasonend2.'</td><td>'.$seasonname3.'</td><td>'.$seasonbegin3.'</td><td>'.$seasonend3.'</td><td>'.$seasonname4.'</td><td>'.$seasonbegin4.'</td><td>'.$seasonend4.'</td></tr>';
                    $recordSet->MoveNext();
     };
     echo '</table>';
     
     echo '<center>';
?>

<?php include('includes/footer.php'); ?>
