<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
<?php include('includes/genfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php  
     echo '<center>';
     echo texttitle($lang['STR_ITEM_TRANSFER']);
  if ($itemcode && !$id) { //if user entered an itemcode to update, get the id and use it for reference
    $recordSet = &$conn->Execute('select id, description from item where itemcode='.sqlprep($itemcode).' and companyid='.sqlprep($active_company));
    if ($recordSet && !$recordSet->EOF) {
      $id = $recordSet->fields[0];
      $description = $recordSet->fields[1];
      
    };
  };

  if ($description) echo texttitle('for '.$itemcode." - ".$description);
  if ($id) { // if the user has selected an item
    if ($fromlocation && $tolocation && $qtychange>0) { 
      $recordSet = &$conn->Execute('select onhandqty from itemlocation where inventorylocationid ='.sqlprep($fromlocation).'  and itemid='.sqlprep($id));
      if ((!$recordSet->EOF) && ($recordSet->fields[0]>0)) {
        $fromqty=$recordSet->fields[0];
        $recordSet = &$conn->Execute('select onhandqty from itemlocation where inventorylocationid ='.sqlprep($tolocation).'  and itemid='.sqlprep($id));
        if (!$recordSet->EOF) {
          $toqty=$recordSet->fields[0];
//          echo "itemid=".$id.", frominvenotrylocationid=".$fromlocation.", toinventorylocationid=".$tolocation.", qtytransfer=".$qtychange.", frominvenotryqty=".$fromqty.", toinventoryqty=".$toqty.", entryuserid=".$userid.", usersendid=".$senduserid.", notes=".$notes."<br>";
//          if ($conn->Execute("insert into itemlocationdetail (itemid,frominventorylocationid,toinventorylocationid,qtytransfer,frominventoryqty,toinventoryqty,entrydate,entryuserid,notes,sentuserid) VALUES (".sqlprep($id).", ".sqlprep($fromlocation).", ".sqlprep($tolocation).", ".sqlprep($qtychange).", ".sqlprep($fromqty).", ".sqlprep($toqty).", NOW(), ".sqlprep($userid).", ".sqlprep($notes).", ".sqlprep($senduserid).")") === false) {
            //back out the order if we error
//            die(texterror('Error inserting inventory details.'));
//          } else {
//            $recordSet = &$conn->SelectLimit("select id from itemlocationdetail where frominventorylocationid =".sqlprep($fromlocation)." and itemid=".sqlprep($id)." and toinventorylocationid =".sqlprep($tolocation)." and qtytransfer=".sqlprep($qtychange)." order by entrydate desc",1);
//            if (!$recordSet->EOF) $inventorydetailid=$recordSet->fields[0];
            $fromchange=$fromqty - $qtychange;
            $tochange=$toqty + $qtychange; 
            if ($conn->Execute("update itemlocation set onhandqty=".sqlprep($fromchange)." where inventorylocationid =".sqlprep($fromlocation)." and itemid=".sqlprep($id)) === false) {
//              $conn->Execute("delete from itemlocationdetail where id=".sqlprep($inventorydetailid));
              die(texterror('Error updating item inventory on location id '.$fromlocation.'.'));
            }
            if ($conn->Execute("update itemlocation set onhandqty=".sqlprep($tochange)." where inventorylocationid =".sqlprep($tolocation)." and itemid=".sqlprep($id)) === false) {
              $conn->Execute("update itemlocation set onhandqty=".sqlprep($fromqty)." where inventorylocationid =".sqlprep($fromlocation)." and itemid=".sqlprep($id));
//              $conn->Execute("delete from itemlocationdetail where id=".sqlprep($inventorydetailid));
              die(texterror('Error updating item inventory on location id '.$fromlocation.'.'));
            }
            if ($notes != "") 
              $conn->Execute('insert into genmessage (userid,sourceuserid,entrydate,message) values ('.sqlprep($senduserid).', '.sqlprep($userid).', NOW(), '.sqlprep($notes).')');
            echo textsuccess("Item Qty ".$qtychange." Transfered successfully.");

        }
      }
      unset($qtychange);
      unset($senduserid);
      unset($notes);
      unset($fromlocation);
      unset($tolocation);
    } else { 
      $recordSet = &$conn->Execute('select distinct count(*) from itemlocation where inventorylocationid <> 0 and itemid='.sqlprep($id));
      if ((!$recordSet->EOF) && ($recordSet->fields[0]>1)) {
        $recordSet = &$conn->Execute('select inventorylocation.id, company.companyname, itemlocation.onhandqty from inventorylocation, itemlocation, company where itemlocation.inventorylocationid=inventorylocation.id and itemlocation.itemid='.sqlprep($id).' and company.id=inventorylocation.companyid and company.cancel=0 and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
        $i=1;
        while (!$recordSet->EOF) {
          $warehousedata[$i]['id']   = $recordSet->fields[0];
          $warehousedata[$i]['name'] = $recordSet->fields[1];
          $warehousedata[$i]['qty']  = $recordSet->fields[2];
          $i++;
          $recordSet->MoveNext();
        };
      

        echo '<script language="JavaScript">
               function update_tolocation(theForm) {
                  var NumState = theForm.tolocation.options.length;
                  while(NumState > 0) {
                     NumState--;
                     theForm.tolocation.options[NumState] = null;
                  }
                  var SelectedFromLocationID = "";
                  SelectedFromLocationID = theForm.fromlocation.options[theForm.fromlocation.selectedIndex].value;
                ';
        for($j=1;$j<$i;$j++)
        {
          if ($j!=1)  echo 'else ';
          echo 'if (SelectedFromLocationID == "'.$warehousedata[$j]['id'].'"){
                   theForm.tolocation.options[0] = new Option("Please Select", "");
                   ';
          $count=1;
          for($k=1;$k<$i;$k++)
          {
            if ($k != $j)
            {
              if ($warehousedata[$k]['qty'] < 0) 
                echo 'theForm.tolocation.options['.$count.'] = new Option("'.$warehousedata[$k]['name'].' - qty  ('. $warehousedata[$k]['qty'].')", "'. $warehousedata[$k]['id'].'");
                      ';
              else 
                echo 'theForm.tolocation.options['.$count.'] = new Option("'.$warehousedata[$k]['name'].' - qty  '. $warehousedata[$k]['qty'].'", "'. $warehousedata[$k]['id'].'");
                      ';
              $count++;
            }
          }
          echo '
                }';
        }
        echo '
              }
              ';        
        echo 'function check_form() {
                 var error = 0;
                 var error_message = "";
                 var SelectedFromLocationID = 0;
                 var SelectedFromLocationID = document.locationform.fromlocation.options[document.locationform.fromlocation.selectedIndex].value;
                 var SelectedToLocationID = document.locationform.tolocation.options[document.locationform.tolocation.selectedIndex].value;
                 var QtyTransfer = document.locationform.qtychange.value;
                    if (SelectedToLocationID == 0 || SelectedFromLocationID == 0 || SelectedToLocationID == null || SelectedFromLocationID == "") { 
                      error_message += "Please Select both From and To Location.\n";
                      error = 1;
                    }
                    if (QtyTransfer == null || QtyTransfer == "" || QtyTransfer < 1) { 
                      error_message += "Qty must be a 1 or greater.\n";
                     error = 1;
                    }
                    ';
        for($j=1;$j<$i;$j++) {
          if ($j >= 1) echo 'if (SelectedFromLocationID == "'.$warehousedata[$j]['id'].'") {
                      if (QtyTransfer > '.$warehousedata[$j]['qty'].') {
                        error_message = "Qty must be in range of 1 to '.$warehousedata[$j]['qty'].'.";
                        error = 1;
                      }
                    }
                    ';
        };
        echo 'if (error == 1) {
                      alert(error_message);
                      return false;
                   } else { return true; }
                  }
</script>';


        echo '<form action="invitemtransfer.php" method="post" name="locationform" onSubmit="return check_form();">';
        echo '<table><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"> From Location:</td><td><select name="fromlocation" onChange="update_tolocation(this.form)";'.INC_TEXTBOX.'><option value="">Please Select';
        for($j=1;$j<$i;$j++) {
          if ( $warehousedata[$j]['qty'] > 0 )
            echo '<option value="'.$warehousedata[$j]['id'].'">'.$warehousedata[$j]['name']." - qty  ".$warehousedata[$j]['qty']."\n";
        };
        echo '</select></td></tr>';
        echo '<tr></tr><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"> To Location:</td><td><select name="tolocation"'.INC_TEXTBOX.'><option value="">Please Select';
        for($j=1;$j<$i;$j++) {
          if ( $warehousedata[$j]['qty'] > 0 )
            echo '<option value="'.$warehousedata[$j]['id'].'">'.$warehousedata[$j]['name']." - qty  ".$warehousedata[$j]['qty']."\n";
          else
            echo '<option value="'.$warehousedata[$j]['id'].'">'.$warehousedata[$j]['name']." - qty  (".$warehousedata[$j]['qty'].")\n";
        };
        echo '</select></td></tr>';
        echo '<input type="hidden" name="id" value="'.$id.'">';
        echo '<input type="hidden" name="itemcode" value="'.$itemcode.'">';
        echo '<input type="hidden" name="description" value="'.$description.'">';
        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_QTY'].':</td><td><input type="text" name="qtychange" onchange="validateintsigned(this)" size="30" maxlength="30"></td></tr>';
//        echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SEND_EMAIL']." ".$lang['STR_NOTES'].':</td><td><textarea name="notes" rows="3" cols="25">';
//        echo '</textarea></td></tr>';
//        formgenuserselect($userid);
        echo '</table><br><input type="submit" name="transfer" value="Transfer"></form>';
      } else {
          echo '<a href=invitemupd1.php?id='.$id.'&&itemcode='.$itemcode.'&&description='.$description.'>Add New Location</a>';
          die(texterror('There is only one location found for this item, add Another Location.'));
      };
    };
  } else { //  Displays dropdown box for selecting item.
    //  User comes to this code section first to 
    //  to select an item.  Afterwards, he user 
    //  must select a vendor.
    $recordSet = &$conn->Execute('select distinct count(*) from inventorylocation');
    if ((!$recordSet->EOF) && ($recordSet->fields[0]>1)) {    
        echo '<form action="invitemtransfer.php" method="post" name="mainform"><table><tr><td>'.$lang['STR_ITEM_CODE'].': </td><td><input type="text" name="itemcode" size="30" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a></td></tr></table><br><input type="submit" value="'.$lang['STR_SELECT'].'"></form>';
        } else {
            echo(texterror('There is only one location found, No Transfer is able to perform.'));
        }
    };
?>
<?php include('includes/footer.php'); ?>

