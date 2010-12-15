<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center>';
     echo texttitle($lang['STR_SALES_TAX_UPDATE']);
     if ($id) { //if sales tax selected
          if ($delete) { //if sales tax should be deleted
               if (arsalestaxdelete($id)) die(); //OK
          } elseif ($taxname) { //if sales tax should be updated
               arsalestaxupdate($id, $taxname, $taxrate, $taxbase, $glacctid);
          };
          //edit sales tax
          echo '<form action="adminarsalestaxupd.php" method="post"><input type="hidden" name="id" value="'.$id.'"><table>';
          formarsalestaxupdate($id);
          echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form><a href="javascript:confirmdelete(\'adminarsalestaxupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_SALES_TAX'].'</a>';
     } else { //select sales tax
          echo '<form action="adminarsalestaxupd.php" method="post"><table>';
          if (formarsalestaxselect('id')) echo '<input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"><br>';
          echo '</table></form><br><a href="adminarsalestaxadd.php">'.$lang['STR_ADD_NEW_SALES_TAX'].'</a>';
     };
          
          echo '</center>';
?>
<?php include('includes/footer.php'); ?>
