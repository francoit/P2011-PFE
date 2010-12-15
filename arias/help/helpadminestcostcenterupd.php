<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helpadminestcostcenterupd.php

echo '<center><b>Cost Center Update - Part One</b></center><i>Cost Centers are the largest logical grouping of machines. Next are Families, and last are the machines themselves. Work can be moved from one machine to another within a Family, but not to a different Family within the same cost center. A cost center might be BINDERY, a family CUTTING, and the machines a POLAR 25in. and a POLAR 36in.</i><br><br>';
echo '<b>COST CENTER: </b>Select cost center to be edited from the pull-down menu.<br><br>';
echo '<b>Edit Selection: </b>Click this button to EDIT the selection.<br><br>';
echo '<b>Add new cost center</b> Click on this link to create a cost center.<br><br>';

echo '<center><b>Cost Center Update - Part Two</b></center><br>';
echo '<b>NAME: </b>The name for this cost center.<br><br>';
echo '<b>COST CENTER TYPE: </b>Select the type of cost center from the pull-down list. The purpose of this entry is for grouping correctly on the order entry screens.<br><br>';
echo '<b>SORT ORDER: </b>The order (lower numbers appear first) in which you would like to see lists of cost centers displayed.<br><br>';

echo '<b>Save Changes: </b>Click this button to SAVE the changes made.<br><br>';
echo '<b>Add/Update Machine Families: </b>Click this link to bring up the Machine Family add/update screen.<br><br>';
echo '<b>Delete this Cost Center: </b>Click this link to remove the cost center displayed from the system.<br><br>';

?>

