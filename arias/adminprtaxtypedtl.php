<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center>';
     echo texttitle($lang['STR_PAYROLL_TAX_TABLES']);
     if ($final&&$locale&&isset($localeid)) { //write update to table
          if (!$locale=='City'&&!$locale=='Local'&&!$locale=='State'&&!$locale=='Federal') die(texterror($lang['STR_BAD_TAX_TYPE']));
          $str1='pr'.strtolower($locale).'id='.sqlprep($localeid).' and ';
          $str2='pr'.strtolower($locale).'id,';
          $str3=sqlprep($localeid).',';
          $conn->Execute('delete from pr'.strtolower($locale).'detail where '.$str1.'maritalstatus='.sqlprep($maritalstatus).' and deductiontable='.sqlprep($deductiontable));
          if (!is_array($tax)) $tax=array($tax);
          if (!is_array($percent)) $tax=array($percent);
          if (!is_array($over)) $tax=array($over);
          foreach ($tax as $taxa) {
               if ($taxa>0||current($percent)>0||current($over)>0) $conn->Execute('insert into pr'.strtolower($locale).'detail ('.$str2.'maritalstatus,deductiontable,tax,percent,over) values ('.$str3.sqlprep($maritalstatus).','.sqlprep($deductiontable).','.sqlprep($taxa).','.sqlprep(current($percent)).','.sqlprep(current($over)).')');
               next($percent);
               next($over);
          };
          echo textsuccess($lang['STR_TAX_TABLES_UPDATED_SUCCESSFULLY']);
     };
     if ($locale) {
          if (isset($localeid)&&$maritalstatus) {
               if ($locale=='State') {
                    $str='genstateid';
               } elseif ($locale=='City'||$locale=='Local') {
                    $str='name';
               } elseif ($locale=='Federal') {
                    $localeid=$prfederalid;
               } else {
                    die(texterror($lang['STR_BAD_TAX_TYPE']));
               };
               if ($locale=='Federal') $localeid=1;
               $recordSet=&$conn->Execute('select '.$str.' from pr'.strtolower($locale).' where cancel=0 and gencompanyid='.sqlprep($active_company).' and id='.sqlprep($localeid));
               if ($recordSet&&!$recordSet->EOF) {
                    echo '<form action="adminprtaxtypedtl.php" method="post" name="mainform"><input type="hidden" name="locale" value="'.$locale.'"><input type="hidden" name="localeid" value="'.$localeid.'"><input type="hidden" name="maritalstatus" value="'.$maritalstatus.'"><input type="hidden" name="final" value="1"><input type="hidden" name="deductiontable" value="'.$deductiontable.'"><table>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_TYPE'].':</td><td>'.$locale.'</td></tr>';
                    if ($locale=='State') {
                         $name=statenamefromid($recordSet->fields[0]);
                    } else {
                         $name=$recordSet->fields[0];
                    };
                    if ($locale<>'Federal') {
                         echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$locale.' '.$lang['STR_NAME'].':</td><td>'.$name.'</td></tr>';
                    };
                    $str1='pr'.strtolower($locale).'id='.sqlprep($localeid).' and ';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MARITAL_STATUS'].':</td><td>'.prmaritalstatusfromid($maritalstatus).'</td></tr>';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEDUCTION_ALLOWANCE_TABLE'].':</td><td>';
                    if ($deductiontable) { echo $lang['STR_YES']; } else { echo $lang['STR_NO']; };
                    echo '</td></tr>';
                    echo '</table><table><tr><th>'.$lang['STR_TAX_AMOUNT'].'</th><th>'.$lang['STR_PLUS_TAX'].' %</th><th>'.$lang['STR_ON_WAGES_OVER'].'</th></tr>';
                    $recordSet2=&$conn->Execute('select tax,percent,over from pr'.strtolower($locale).'detail where '.$str1.'maritalstatus='.sqlprep($maritalstatus).' and deductiontable='.sqlprep($deductiontable).' order by over,tax,percent');
                    while (!$recordSet2->EOF) {
                         echo '<tr><td><input type="text" name="tax[]" onchange="validatenum(this)" size="20" maxlength="15" value="'.$recordSet2->fields[0].'"'.INC_TEXTBOX.'></td><td><input type="text" name="percent[]" onchange="validatenum(this)" size="20" maxlength="15" value="'.$recordSet2->fields[1].'"'.INC_TEXTBOX.'></td><td><input type="text" name="over[]" onchange="validatenum(this)" size="20" maxlength="15" value="'.$recordSet2->fields[2].'"'.INC_TEXTBOX.'></td></tr>';
                         $recordSet2->MoveNext();
                    };
                    echo '<tr><td><input type="text" name="tax[]" onchange="validatenum(this)" size="20" maxlength="15"'.INC_TEXTBOX.'></td><td><input type="text" name="percent[]" onchange="validatenum(this)" size="20" maxlength="15"'.INC_TEXTBOX.'></td><td><input type="text" name="over[]" onchange="validatenum(this)" size="20" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
                    echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
               } else {
                    die(texterror($lang['STR_TAX_TYPE_NOT_FOUND']));
               };
          } else {
               echo '<form action="adminprtaxtypedtl.php" method="post" name="mainform"><input type="hidden" name="locale" value="'.$locale.'"><input type="hidden" name="localeid" value="'.$localeid.'"><table>';
               if ($locale<>'Federal') {
                    if ($locale=='State') {
                         $recordSet = &$conn->Execute('select id,genstateid,\'\' from prstate where cancel=0 and gencompanyid='.sqlprep($active_company));
                    } elseif ($locale=='Local'||$locale=='City') {
                         $recordSet = &$conn->Execute('select id,name,abrev from pr'.strtolower($locale).' where cancel=0 and gencompanyid='.sqlprep($active_company));
                    };
                    if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_TAXES_FOUND']));
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_TYPE'].':</td><td><select name="localeid"'.INC_TEXTBOX.'>';
                    while ($recordSet&&!$recordSet->EOF) {
                         if ($locale=='State') {
                              $str=statenamefromid($recordSet->fields[1]);
                         } else {
                              $str=$recordSet->fields[1];
                         };
                         echo '<option value="'.$recordSet->fields[0].'"'.checkequal($localeid,$recordSet->fields[0],' selected').'>'.$str;
                         $recordSet->MoveNext();
                    };
                    echo '</select></td></tr>';
               } else {
                    $recordSet = &$conn->Execute('select id from prfederal where gencompanyid='.sqlprep($active_company).' and cancel=0');
                    $prfederalid=$recordSet->fields[0];
                    echo '<input type="hidden" name="prfederalid" value="'.$prfederalid.'">';
               };
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MARITAL_STATUS'].':</td><td><select name="maritalstatus"'.INC_TEXTBOX.'>';
               for ($i=1;$i<=4;$i++) echo '<option value="'.$i.'">'.prmaritalstatusfromid($i); //display tax table options
               echo '</select>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEDUCTION_ALLOWANCE_TABLE'].':</td><td><input type="checkbox" name="deductiontable" value="1"'.INC_TEXTBOX.'>';
               echo '</table><input type="submit" value="'.$lang['STR_SELECT'].'"></form>';
          };
     } else {
          echo '<form action="adminprtaxtypedtl.php" method="post"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_TYPE'].': </td><td><select name="locale"'.INC_TEXTBOX.'><option value="Local">'.$lang['STR_LOCAL'].'<option value="City">'.$lang['STR_CITY'].'<option value="State">'.$lang['STR_STATE'].'<option value="Federal">'.$lang['STR_FEDERAL'].'</select></td></tr>';
          echo '</table><br><input type="submit" value="'.$lang['STR_SELECT'].'"></form>';
           
          echo '</center>';
     };
?>
<?php include('includes/footer.php'); ?>
