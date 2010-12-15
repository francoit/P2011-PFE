<?php include('includes/main.php'); ?>
<?php include('includes/glfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?
     echo '<center>';
     echo texttitle($lang['STR_ACCOUNT_TYPE_UPDATE']);
     if ($id) { // if the user has submitted info
          if ($delete) { //if we should be deleting the entry
               if (glaccounttypedelete($id)) die(textsuccess($lang['STR_GL_ACCOUNT_TYPE_DELETED_SUCCESSFULLY']));
          } elseif ($accounttype) { //if we should update the entry
               glaccounttypeupdate($id, $accounttype, $lastchangedate);
          }; //if we should display more info about the entry that the user can edit
          echo '<form action="adminglaccttypeupd.php" method="post"><table><input type="hidden" name="id" value="'.$id.'">';
          formglaccounttypeupdate($id);
          echo '</table><br><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form><br><a href="javascript:confirmdelete(\'adminglaccttypeupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_ACCOUNT_TYPE'].'</a>';
     } else { //display account types, let the user pick one to edit
          echo '<form action="adminglaccttypeupd.php" method="post"><table>';
          formglaccounttypeselect('id');
          echo '</table><br><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></form>';
          echo '<br><a href="adminglaccttypeadd.php">'.$lang['STR_ADD_NEW_GL_ACCOUNT_TYPE'].'</a>';
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
