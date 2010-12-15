<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center>';
     echo texttitle($lang['STR_INVOICE_TERMS_UPDATE']);
     if ($id) { //if invoice term selected
          if ($delete) { //if invoice term should be deleted
               if (arinvoicetermsdelete($id)) die(textsuccess($lang['STR_INVOICE_TERMS_DELETED_SUCCESSFULLY'])); //OK
          } elseif ($verbal) { //if invoice term should be updated
               arinvoicetermsupdate($id, $verbal, $discountpercent, $discountdays, $netduedays);
          };
          // edit invoice term
          echo '<form action="adminarinvtermsupd.php" method="post"><input type="hidden" name="id" value="'.$id.'"><table>';
          formarinvoicetermsupdate($id);
          echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form><a href="javascript:confirmdelete(\'adminarinvtermsupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_INVOICE_TERMS'].'</a>';
     } else { //select invoice term
          echo '<form action="adminarinvtermsupd.php" method="post"><table>';
          if (formarinvoicetermsselect('id')) echo '<center><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></center><br>';
          echo '</table></form><br><a href="adminarinvtermsadd.php">'.$lang['STR_ADD_NEW_INVOICE_TERMS'].'</a>';
     };
     
          echo '</center>';
?>
<?php include('includes/footer.php'); ?>
