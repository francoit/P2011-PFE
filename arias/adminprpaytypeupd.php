<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center>';
     echo texttitle($lang['STR_PAYROLL_PAY_TYPE_UPDATE']);
     if ($delete) {
          prpaytypedelete($id);
          unset($id);
     };
     if ($id) {
          if ($name) {
               if ($type==1) $vacation=1; //set correct variable from type select box
               if ($type==2) $sick=1;
               prpaytypeupdate($id, $name, $description, $multiplier, $vacation, $sick);
          };
          echo '<form action="adminprpaytypeupd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
          echo '<input type="hidden" name="id" value="'.$id.'">';
          formprpaytypeupdate($id);
          echo '</table><br><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'adminprpaytypeupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_PAYMENT_TYPE'].'</a>';
     } else {
          echo '<form action="adminprpaytypeupd.php" method="post"><table>';
          formprpaytypeselect('id');
          echo '</table><br><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></form><br>';
          echo '<a href="adminprpaytypeadd.php">'.$lang['STR_ADD_NEW_PAYMENT_TYPE'].'</a>';
          echo '</center>';
     };
?>
<?php include('includes/footer.php'); ?>
