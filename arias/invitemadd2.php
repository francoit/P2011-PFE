<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php') ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //invitemadd2.php
     echo '<center>';
     if (!$itemcode) {
           $recordSet=&$conn->Execute('select itemcode,description from item where id='.sqlprep($id));
           if (!$recordSet->EOF) {
                 $itemcode=$recordSet->fields[0];
                 $description=$recordSet->fields[1];
           } else {
              die (texterror("How did you arrive here with no item id??"));
           };
     };
     echo texttitle('Composite Item Component List for '.$itemcode." - ".$description);

     if ($subitemcodeid) {// have entered composite items
              // save to file then return to composite item screen
              $addupdate=0; //set flag to ADD
              CompositAddUpdate($addupdate,$id,$subitemcodeid,$quantity);
              $subitemcodeid="";
     };


     // ask component information
     echo '<form action="invitemadd2.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
     //implement as hidden data to be carried forward in form
     echo '<input type="hidden" name="itemcode" value="'.$itemcode.'">';
     echo '<input type="hidden" name="id" value="'.$id.'">';
     echo '<input type="hidden" name="description" value="'.$description.'">';

     echo '<tr><td>Component Item:</td><td><select name="subitemcodeid" onKeyPress="return handleEnter(this, event)" onFocus="highlightField(this)" onBlur="normalField(this)">';
     $recordSet = &$conn->Execute('select id, itemcode, description from item where id<>'.sqlprep($id).' order by itemcode');
     while (!$recordSet->EOF) {
            echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."-".$recordSet->fields[2]."\n";
            $recordSet->MoveNext();
     };
     echo '</select></td></tr>';
     echo '<tr><td>Quantity Required: </td><td><input type="text" name="quantity" onchange="validatenum(this)" value="1" onKeyPress="return handleEnter(this, event)" onFocus="highlightField(this)" onBlur="normalField(this)"></td></tr>';
     echo '</table><input type="submit" value="Add/Remove Selected Component"><br><br>';
     echo '<table border=1><tr><th colspan=3>Component Items Already Selected<br>(select again to remove from list)</th></tr>';
     echo '<tr><th>Item Code</th><th>Description</th><th>Quantity Required</th></tr>';
     $recordSet = &$conn->Execute('select compositeitemid.subitemcodeid, item.id, item.itemcode, item.description, compositeitemid.quantity from item, compositeitemid where compositeitemid.itemcodeid='.sqlprep($id).' and compositeitemid.subitemcodeid=item.id and compositeitemid.cancel=0 order by item.description');
     while (!$recordSet->EOF) {
            echo '<tr><td>'.$recordSet->fields[2].'</td><td>'.$recordSet->fields[3].'</td><td>'.$recordSet->fields[4].'</tr>';
            $recordSet->MoveNext();
     };
     echo '</select></td></tr></table></form>';
     
     echo '</center>';
?>

<?php include('includes/footer.php'); ?>
