<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //helpprloghours.php
     echo '<center><b>Payroll Log Hours</b></center><br>';
     echo '<i>Entry of hours for a payroll my be done once during the pay period, or daily if that better suits your business. Each time you log hours for the same pay period THE HOURS AND PAY ADD TOGETHER... they do not replace previous entries for the same pay period.</i><br><br>
<b>Period:</b> Select pay period group from pull-down list.<br><br>
<b>Continue</b> Press this button to continue with log hours entries.<br><br><hr><br>

<b>Period Begin Date:</b> This is the pay period beginning date, and if you enter hours more than once for the same pay period, you must be sure to enter the beginning and ending dates the same each time.<br><br>
<b>Period End Date:</b> This is the pay period ending date, NOT necessarily your entry date, or the check date.<br><br>
<b>Continue</b> Press this button to continue with log hours entries.<br><br><hr><br>

<i>DEFAULTS: The items on this screen are intended to save entry time if most employees receive pay for the same number of hours.</i><br><br>
<b>Pay Type:</b> What will actually show in this list comes from the pay types your company uses. Enter the number of hours for defaults next to each one.<br><br>
<b>Continue</b> Press this button to continue with log hours entries.<br><br><hr><br>
<i>You will see a screen for each employee in the pay group (with their name listed at the top). If the default hours entered previously is all this employee is to receive, then simply press Next Employee button at the bottom of the display to bring up the next one. Otherwise, you can override the default entry, or enter any hours/pay needed.</i>

<b>Pay Type:</b> This is not a user entry, but will display the standard pay types your company uses.<br><br>
<b>Hours:</b> Enter the number of hours to be paid. <br><br>
<b>Rate:</b> This rate will default to the normal employee rate, but can be changed for this entry. Changing the rate here will NOT change it in the employee main file.<br><br>
<b>Total:</b> Total pay will calculate automatically based on pay rate and hours.<br><br>
<b>GL Account:</b> This is the payroll expense account, and will default to the one listed in the employee main file.<br><br>
<hr>
<b>Shift:</b> Select the shift (if not first) from the pull-down list. This can affect the pay rate used in the above entries for the employee. Although the rate change multiplier for shift work will show in the column for shift, it will not change the wages displayed until the calculations are complete.<br><br>
<b>Misc. Taxable Pay: </b> Taxable pay, such as bonus money could be entered here.<br><br>
<b>Comment:</b> An explanation of the reason for the taxable pay amount.<br><br>
<b>Misc.Nontaxable Pay:</b> Usually reimbursement for monies spent by the employee on behalf of the company, such as travel expenses.</b><br>
<b>Comment:</b> Explanation of the nontaxable pay amount.<br><br>
<b>Misc. Deduction:</b> Unusual amounts deducted from the employee pay, such as advances on pay given earlier in the period.<br><br>
<b>Payment Type:</b> Select if the pay rate is hourly or is a salary amount.<br><br>
<b>Comment:</b> Explanation for the misc. deduction.<br><br>
<b>Pay per hour/period:</b> If the Payment Type selected is hourly, then enter an hourly pay amount. If the Payment Type is salaried, then enter the amount of pay PER PERIOD CHOSEN (weekly for weekly payrolls, monthly for monthly payrolls, etc.).<br><br>

<b>Next Employee</b> Press this button to continue with the next employee in this group.<br><br>
<b>Enter More Hours</b> To enter additional hours at different pay rates, press this button. This will save the entries you have made so far, and let you enter a new set of entries for this same employee.<br><br>




';

?>

