<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>


<?
     echo texttitle($lang['STR_SHIPPING_METHOD_UPDATE']);
     echo '<center>';
     if ($carrierid) { //if carrier selected
          $recordSet = &$conn->Execute('select carrier.id,company.companyname from company,carrier where company.cancel=0 and carrier.companyid=company.id and carrier.id='.sqlprep($carrierid));
          if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_COMPANY_NOT_FOUND']));
          echo texttitle(" for Carrier: ".rtrim($recordSet->fields[1]));
          if ($carriermethodid) { //if shipping method selected
               if ($delete) { //if shipping method should be deleted
                    if (arcarriermethoddelete($carriermethodid)) echo textsuccess($lang['STR_SHIPPING_METHOD_DELETED_SUCCESSFULLY']);
                    $carriermethodid="";
               } elseif ($description) {  //if shipping method should be updated
                    if (arcarriermethodupdate($carriermethodid, $description)) echo textsuccess($lang['STR_SHIPPING_METHOD_UPDATED_SUCCESSFULLY']);
                    $carriermethodid="";
               } else { //edit shipping method
                 echo '<form action="adminarcarriermethupd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
                 echo '<input type="hidden" name="carrierid" value="'.$carrierid.'">';
                 echo '<input type="hidden" name="carriermethodid" value="'.$carriermethodid.'">';
                 formarcarriermethodupdate($carriermethodid);
                 echo '</table><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form><a href="javascript:confirmdelete(\'adminarcarriermethupd.php?delete=1&id='.$carriermethodid.'\')">'.$lang['STR_DELETE_THIS_METHOD'].'</a>';
               };
          } elseif ($new) { //add a new shipping method
               if ($description) {
                    if (arcarriermethodadd($carrierid,$description)) echo textsuccess($lang['STR_SHIPPING_METHOD_ADDED_SUCCESSFULLY']);
                    $new="";
               } else {
                    echo '<form action="adminarcarriermethupd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
                    echo '<input type="hidden" name="carrierid" value="'.$carrierid.'">';
                    echo '<input type="hidden" name="new" value="1">';
                    echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DESCRIPTION'].':</td><td><input type="text" name="description" size="30" maxlength="30"></td></tr>';
                    echo '</table><input type="submit" value="'.$lang['STR_ADD'].'"></form>';
               };
          };
          if (!$carriermethodid&&!$new) { //select shipping method
               echo '<form action="adminarcarriermethupd.php" method="post"><table>';
               if (formarcarriermethodselect('carriermethodid', $carrierid)) echo '<input type="submit" value="'.$lang['STR_EDIT'].'">';
               echo '</table></form><a href="adminarcarriermethupd.php?new=1&carrierid='.$carrierid.'">Add a new method</a>';
               echo '<br><br><a href="adminarcarriermethupd.php">Go to Carrier Selection</a>';
          };
     } else { //select carrier
          echo '<form action="adminarcarriermethupd.php" method="post"><table>';
          if (formarcarrierselect('carrierid')) echo '<input type="submit" value="'.$lang['STR_EDIT'].'">';
          echo '</table></form>';
          echo '</center>';
     };
?>

<?php include('includes/footer.php'); ?>
