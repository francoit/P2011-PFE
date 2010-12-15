<?php include('includes/main.php'); ?>
<?php include('includes/prfunctions.php'); ?>


<?
     echo '<center>';
     echo texttitle($lang['STR_PAYROLL_DEDUCTION_GROUP_UPDATE']);
     if ($delete) {
          prdedgroupdelete($id);
          unset($id);
     };
     if ($id) {
          if ($name) prdedgroupupdate($id, $name);
          echo '<form action="adminprdedgroupupd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
          echo '<input type="hidden" name="id" value="'.$id.'">';
          formprdedgroupupdate($id);
          echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'adminprdedgroupupd.php?delete=1&id='.$id.'\')">Deduction Group: </a>';
     } else {
          echo '<form action="adminprdedgroupupd.php" method="post"><table>';
          formprdedgroupselect('id');
          echo '</table><br><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></form>';
          echo '<br><a href="adminprdedgroupadd.php">'.$lang['STR_ADD_NEW_DEDUCTION_GROUP'].'</a>';
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
