<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helpadminestmachineupd.php

echo '<center><b>Machine Update - Part One</b></center><i>Cost Centers are the largest logical grouping of machines. Next are Families, and last are the machines themselves. Work can be moved from one machine to another within a Family, but not to a different Family within the same cost center. A cost center might be BINDERY, a family CUTTING, and the machines a POLAR 25in. and a POLAR 36in.</i><br><br>';
echo '<b>MACHINE: </b>Select a machine to edit from the pull-down list, then press the Edit Selection button.<br><br>';
echo '<b>Edit Selection: </b>Click here to edit the selection made.<br><br>';
echo '<b>Add New Machine: </b>Click here to add a new machine to the list.<br><br>';
echo '<b>Selection a New Cost Center: </b>Click here to return to the cost center selection screen.<br><br>';
echo '<b>Machine Family Selection: </b>Click this link to return to the Machine Family selection screen.<br><br>';
echo '<center><b>Machine Update - Part Two</b></center><br>';
echo '<b>NAME: </b>The name for this machine.<br><br>';
echo '<b>COST MACHINE/HR: </b>Cost of operating this machine per hour (not including labor). This can be calculated by taking the total cost of the machine plus maintenance costs over its life, dividing that by the number of years life (or depreciation years), and dividing that result by the number of hours used per year. You will not see this entry if the cost center type is INK or PAPER.<br><br>';
echo '<b>COST OPERATOR/HR </b>Cost of operator per hour. You can either include indirect labor costs, such as insurance, here or in factory overhead. You will not see this entry if the cost center type is INK or PAPER.<br><br>';
echo '<b>COST ASSISTANT/HR: </b>Cost of assistant per hour. Leave this blank if an assistant is NEVER used on this machine. You will not see this entry if the cost center type is INK or PAPER<br><br>';
echo '<b>FACTORY OVERHEAD %: </b>Indirect costs for the production area. If you do not want to separate out factory overhead from general overhead, leave this blank.<br><br>';
echo '<b>GENERAL OVERHEAD %: </b>Office/Sales overhead costs. If you do not want to separate out factory overhead from general overhead, put the entire overhead under this category.<br><br>';
echo '<b>MARKUP %: </b>Profit percent to be used when calculating pricing based on time & materials, and in comparing actual profit to expected profit. If you do not want to break this figure out by machine, then enter the exact same number under all machines.<br><br>';
echo '<b>SORT ORDER: </b>The order (lower numbers appear first) in which you would like to see lists of machines displayed.<br><br>';
echo '<bSave Changes: </b>Click this button to SAVE the changes made.<br><br>';
echo '<b>Delete this Machine: </b>Click here to remove this machine from the system.<br><br>';

?>

