<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript" src="js/validatephone.js">
</script>
<script language="JavaScript">
     function checkChoice(i) {
          if (document.mainform.salesman.checked == false) {
               if (document.mainform.servicerep.checked == false) {
                    if (i=="1") {
                         document.mainform.servicerep.checked = true;
                    } else {
                         document.mainform.salesman.checked = true;
                    }
               }
          }
     }
</script>
<?
     echo '<center>';
     echo texttitle($lang['STR_SALES_PERSONNEL_UPDATE']);
     if ($id) { //if sales person selected
          if ($delete) { //if sales person should be deleted
               $conn->BeginTrans();
               if (arcompanydelete($id)&&arsalesmandelete($id)) {
                    $conn->CommitTrans();
                    die(); //OK
               };
               $conn->RollbackTrans();
          } elseif ($name) { //if sales person should be updated
               $conn->BeginTrans();
               if (arcompanyupdate($id, $address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name, $mailstop, $lastchangedate)&&arsalesmanupdate($id, $payrollid, $commissionrate, $commissionbase, $servicerep, $salesman)) {
                    $conn->CommitTrans();
               } else {
                    $conn->RollbackTrans();
               };
          };
          //edit sales person
          echo '<form action="adminarsalesmanupd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
          echo '<input type="hidden" name="id" value="'.$id.'">';
          formarsalesmanupdate($id);
          echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form><a href="javascript:confirmdelete(\'adminarsalesmanupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_SALESPERSON'].'</a>';
     } else { //select sales person
          echo '<form action="adminarsalesmanupd.php" method="post"><table>';
          if (formarsalesmanselect('id')) echo '<br><input type="submit" value="'.$lang['STR_EDIT_SELECTION'].'">';
          echo '</table></form><br><a href="adminarsalesmanadd.php">'.$lang['STR_ADD_NEW_SALES_PERSON'].'</a>';
     };
          echo '</center>';
?>

<?php include('includes/footer.php'); ?>
