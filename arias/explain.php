<?php include('includes/main.php'); ?> 
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     unset($sqlstr);
     unset($sqlstr2);
     if (!$extlogon) {
          if (!$usersupervisor) $sqlstr2.=' and supervisor=0';
          $mod=array('ap', 'ar', 'gl', 'pay', 'inv', 'est', 'fix', 'imp');
          foreach($mod as $data) {
              if ($usersupervisor) ${$data."_read"}=1;
              if (${$data."_read"}) {
                 if (($data=='ap'&&SOFTWARE_SHOW_AP)||($data=='ar'&&SOFTWARE_SHOW_AR)||($data=='gl'&&SOFTWARE_SHOW_GENERAL_LEDGER)||($data=='pay'&&SOFTWARE_SHOW_PAYROLL)||($data=='inv'&&SOFTWARE_SHOW_INVENTORY)||($data=='est'&&SOFTWARE_SHOW_PRINT_MANAGEMENT)||($data=='fix'&&SOFTWARE_SHOW_FIXED_ASSETS)||($data=='imp'&&SOFTWARE_SHOW_PRINT_MANAGEMENT_IMP)) {
                     $sqlstr.=' or access'.$data.'=1';
                 };
              };
          };
          foreach($mod as $data) {
              if ($usersupervisor) ${$data."_setup"}=1;
              if (${$data."_setup"}) {
                  if (($data=='ap'&&SOFTWARE_SHOW_AP)||($data=='ar'&&SOFTWARE_SHOW_AR)||($data=='gl'&&SOFTWARE_SHOW_GENERAL_LEDGER)||($data=='pay'&&SOFTWARE_SHOW_PAYROLL)||($data=='inv'&&SOFTWARE_SHOW_INVENTORY)||($data=='est'&&SOFTWARE_SHOW_PRINT_MANAGEMENT)||($data=='fix'&&SOFTWARE_SHOW_FIXED_ASSETS)||($data=='imp'&&SOFTWARE_SHOW_PRINT_MANAGEMENT_IMP)) {
                     $sqlstr.=' or setup'.$data.'=1';
                  };
              };
          };
          if ($usersupervisor) $sqlstr.=' or supervisor=1';
          $sqlstr=$sqlstr2.' and ('.substr($sqlstr,4).')';
     } else {
          if ($custcompanyid) { //if external customer
              $sqlstr.=' and extcust=1';
          } elseif ($vendcompanyid) { //if external vendor
              $sqlstr.=' and extvend=1';
          };
          $sqlstr.=' and nonext=0';
     };
     $recordSet = &$conn->Execute('select name, description from menucategory where id='.sqlprep($menucategoryid));
     if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_COULDNT_READ_MENU_EXITING']));
     echo '<table width="100%"><tr><td><b><font size=2">'.rtrim($recordSet->fields[0]).'</font></b></td></tr><tr><td><font size=2">'.rtrim($recordSet->fields[1]).'</font><hr></td></tr></table>';
     echo '<table><tr><td>';
     $recordSet = &$conn->Execute('select id, name, link, leftimageurl, rightimageurl from menufunction where menucategoryid='.sqlprep($menucategoryid).$sqlstr.' order by orderflag');
     if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_COULDNT_READ_MENU_EXITING']));
     echo '<table><tr>';
     $i=1;
     while (!$recordSet->EOF) {
         $tempSTR='STR_SUBMENU_'.$menucategoryid.'_'.$recordSet->fields[0];
//         $name=rtrim($recordSet->fields[1]);
         $name=rtrim($lang[$tempSTR]);
         $link=rtrim($recordSet->fields[2]);
         if (!$link) $link='construct.php';
         if (EXPLAIN_SHOW_PICTURES) {
             $leftimage=rtrim($recordSet->fields[3]);
             $rightimage=rtrim($recordSet->fields[4]);
         };
         if ($i%2) echo '</tr><tr>'."\n";
         echo '<td>'.dynimagerolloveraddl($leftimage,$rightimage,$link,$name).'</td>';
         $recordSet->MoveNext();
         $i++;
     };
     echo '</tr></table>';
     echo '</td></tr></table>';
?>

<?php include('includes/footer.php'); ?>
