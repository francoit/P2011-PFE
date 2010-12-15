<?php include('includes/main.php'); ?>
<?php include('includes/glfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php  //GLACCTUPD.PHP
     echo texttitle($lang['STR_ACCOUNT_UPDATE']);
     echo '<center>';
     if ($id) {
          if ($delete) {
               glaccountdelete($id);
          } elseif ($account) {
               if ($accounttypeid==40) $companyid=0; //this one applies to all companies
               glaccountupdate($id, $account, $name, $accounttypeid, $companyid, $summaryaccountid,$lastchangedate);
          } else {
               echo '<form action="glacctupd.php" method="post">';
               $recordSet = &$conn->Execute('select name from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') and id='.$id);
               if (!$recordSet->EOF) {
                    echo '<table><tr><th colspan="2">'.$lang['STR_GENERAL_LEDGER_ACCOUNT'].' - '.$recordSet->fields[0].'</th></tr>';
                    echo '<input type="hidden" name="name" value="'.$recordSet->fields[0].'">';
                    echo '<input type="hidden" name="id" value="'.$id.'">';
                    formglaccountupdate($id);
                    echo '</select></td></tr></table><input type="submit" value="'.$lang['STR_UPDATE'].'"></form> <a href="javascript:confirmdelete(\'glacctupd.php?delete=1&id='.$id.'\')">Delete this Account</a>';
               };
          };
     } else {
          echo '<form action="glacctupd.php" method="post"><table>';
          formglaccountselect('id');
          echo '</table><br><input type="submit" value="'.$lang['STR_SELECT'].'"></form>';
          echo '<br><a href="glacctadd.php">'.$lang['STR_ADD_NEW_GL_ACCOUNT'].'</a>';
     };
          
          echo '</center>';
?>

<?php include('includes/footer.php'); ?>
