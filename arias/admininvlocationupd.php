<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<script language="JavaScript" src="js/validatephone.js">
</script>
<?
     echo texttitle($lang['STR_INVENTORY_LOCATION_UPDATE']);
     echo '<center>';
     if ($id) {
          if ($delete) {
               if (invcompanydelete($id)) if (invinventorylocationdelete($id)) die(textsuccess($lang['STR_INVENTORY_LOCATION_DELETED_SUCCESSFULLY']));
          } elseif ($name) {
               if (invcompanyupdate($id, $address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name,$lastchangedate)) echo textsuccess($lang['STR_INVENTORY_LOCATION_UPDATED_SUCCESSFULLY']);
          };
          echo '<form action="admininvlocationupd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
          echo '<input type="hidden" name="id" value="'.$id.'">';
          forminvinventorylocationupdate($id);
          echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form> <a href="javascript:confirmdelete(\'admininvlocationupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_LOCATION'].'</a>';
     } else {
          echo '<form action="admininvlocationupd.php" method="post"><table>';
          forminvinventorylocationselect('id');
          echo '</table><br><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'"></form>';
          echo '<br><a href="admininvlocationadd.php">'.$lang['STR_ADD_NEW_LOCATION'].'</a>';
          
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
