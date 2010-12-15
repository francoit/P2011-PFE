<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript" src="js/validatephone.js">
</script>
<?

     echo texttitle($lang['STR_CARRIER_UPDATE']);
     echo '<center>';
     if ($companyid||$carrierid) { //if carrier selected
          if ($delete) { //if carrier should be deleted
               $conn->BeginTrans();
               if (arcompanydelete($companyid)&&arcarrierdelete($carrierid)) {
                    $conn->CommitTrans();
                    die(); //OK
               };
               $conn->RollbackTrans();
          } elseif ($name) {  //if carrier should be updated
               $conn->BeginTrans();
               if (arcompanyupdate($companyid, $address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name,$mailstop,$lastchangedate)&&arcarrierupdate($carrierid, $customernumber, $trackingurlbase, $trackingurlvarname)) {
                    $conn->CommitTrans();
               } else {
                    $conn->RollbackTrans();
               };
          };
          //edit carrier
          echo '<form action="adminarcarrierupd.php" method="post"><table>';
          formarcarrierupdate($carrierid);
          echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
          echo '<br><a href="adminarcarriermethupd.php?carrierid='.$carrierid.'">'.$lang['STR_UPDATE_CARRIER_METHOD'].'</a>';

          echo '<br><br><a href="javascript:confirmdelete(\'adminarcarrierupd.php?delete=1&companyid='.$companyid.'&carrierid='.$carrierid.'\')">'.$lang['STR_DELETE_THIS_CARRIER'].'</a>';
     } else { //select carrier
          echo '<form action="adminarcarrierupd.php" method="post"><table>';
          if (formarcarrierselect('carrierid'));
          echo '<br></table><input type="submit" value="'.$lang['STR_EDIT'].'"><table>';
          echo '</table></form><br><a href="adminarcarrieradd.php">'.$lang['STR_ADD_NEW_CARRIER'].'</a>';
     };
          echo '</center>';

?>

<?php include('includes/footer.php'); ?>
