<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //helppremplded.php
     echo '<center><b>Payroll Employee Deductions</b></center><br>';
     echo '<i>Specific deductions for specific employees can be entered here. Typical entries are United Way, Garnishment, Insurance, etc.</i><br><br>
<b>Name:</b> Select employee from pull-down list.<br><br>
<b>Edit Selection</b> Press this button to add/edit employee deductions.<br><br><hr><br>

<i>You will see a table for entering deduction information. There will always be one blank line for entering new deductions. If you need to enter more than one, enter the one, press the SAVE CHANGES button, and a new blank line will appear.</i><br><br>
<b>Description:</b> Description for deduction. Remember when entering the description that only the first few letters will appear on employee pay stubs, so try to make these unique.<br><br>
<b>Amount:</b> Amount to deduct each pay period.<br><br>
<b>Periods Remaining:</b> If the deduction is for a fixed number of times (as with a garnishment) then enter the number of times. This will automatically reduce in number with each payroll in which a deduction is made. If the deduction is to continue until removed from the employee, enter a -1 here.<br><br>
<b>GL Account:</b> Payables type General Ledger account.<br><br>
<b>Delete Deduction:</b> Check this box if you want to delete the deduction on that line.<br><br>
<b>Save Changes:</b> When you have entered/changed/deleted a deduction, press SAVE button to save the new information and provide a new line for entering more deductions for this employee.<br><br>


';

?>

