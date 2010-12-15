<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
     function checkChoice(i) {
          if (document.mainform.ap.checked == false) {
               if (document.mainform.pay.checked == false) {
                    if (i=="1") {
                         document.mainform.pay.checked = true;
                    } else {
                         document.mainform.ap.checked = true;
                    }
               }
          }
     }
</script>
<?
     echo '<center>';
     echo texttitle($lang['STR_CHECKING_ACCOUNT_UPDATE']);
     if ($id) {
          if ($delete) {
               if (apchkacctdelete($id)) die(textsuccess($lang['STR_CHECKING_ACCOUNT_DELETED_SUCCESSFULLY'].'.'));
          } elseif ($name) {
               apchkacctupdate($id, $name, $glaccountid, $lastchecknumberused, $defaultendorser, $ap, $pay);
          };
          echo '<form action="adminapchkacctupd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
          echo '<input type="hidden" name="id" value="'.$id.'">';
          formapchkacctupdate($id);
          echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'adminapchkacctupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_ACCOUNT'].'</a>';
     } else {
          $recordSet = &$conn->Execute('select count(*) from checkacct where gencompanyid='.sqlprep($active_company));
          if (!$recordSet->EOF) if ($recordSet->fields[0]==0) $noaccts=1;
          if (!$noaccts==1) {
             echo '<form action="adminapchkacctupd.php" method="post"><table>';
             formapchkacctselect('id');
             echo '</table><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></form>';
          };
          echo '<a href="adminapchkacctadd.php">'.$lang['STR_ADD_NEW_CHECKING_ACCOUNT'].'</a>';
     };
         echo '</center>';
?>

<?php include('includes/footer.php'); ?>
