<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //helppremployeeadd.php
     echo '<center><b>Payroll Employee Add</b></center><br>';
     echo '<i>General employee information .</i><br><br>
<b>Name (First/Last):</b> This is actually two fields. If a person has a middle name include that with the first name box.<br><br>
<b>Address:</b> Employees home mailing address. There are two lines for address.<br><br>
<b>City:</b> Employees home mailing city.<br><br>
<b>State:</b> Employees home mailing state.<br><br>
<b>Postal Code:</b> Employees home postal zip code.<br><br>
<b>Country:</b> Employees home mailing country.<br><br>
<b>Mail Stop:</b> Use this only if the mailing address for the employee ../includes a mail stop.<br><br>
<b>Phone & Phone Description:</b> There are four different phone numbers that can be entered. Examples might be Office, FAX, CELL, Pager, etc. Enter phone extension information by prefixing the extension with the letter x.<br><br>
<b>E-mail & E-mail Description: </b> Two different e-mails can be entered along with a note as to the purpose (or person) for each.<br><br>
<b>Web Address:</b> If your employee maintains a web site, put that address here.<br><br>
<b>Federal ID:</b> This is actually the employee <b>Social Security Number</b>.<br><br>
<b>Date of birth:</b> You can enter simply a month and day if the employee does not want to include their age.<br><br>
<b>Hire date:</b> Employment beginning date. This is used for determining vacation allowed, unemployment information, etc.<br><br>
<b>Payment Type:</b> Select if the pay rate is hourly or is a salary amount.<br><br>
<b>Pay Period:</b> Frequency of payrolls: Pull-down choices are Weekly, Bi-weekly (every other week), Semi-monthly (twice a month), and Monthly.<br><br>
<b>Pay per hour/period:</b> If the Payment Type selected is hourly, then enter an hourly pay amount. If the Payment Type is salaried, then enter the amount of pay PER PERIOD CHOSEN (weekly for weekly payrolls, monthly for monthly payrolls, etc.).<br><br>
<b>GL Account:</b> General ledger account for posting payroll expense. This would be either a cost of goods sold or an expense type of account.<br><br>
<b>Vacation hours accrued:</b> This field will reduce automatically as vacation is used, and increased automatically as each new payroll is written. You can enter an amount here to start off with.<br><br>
<b>Sick leave hours accrued:</b> Available sick leave. This field will increase and decrease automatically as sick leave is used or new sick leave accrued.<br><br>

<b>CONTINUE</b> Press this button to enter more data for this employee. If pressing the continue button simply redisplays this screen, then some essential information, such as social security number, is missing.<br><br><hr><br>


<b>Marital Status:</b> Choices are single, head of household, married filing joint and married filing separate. For Federal taxes, both married selections are the same, and single is the same as head of household. However, your state or locality might distinguish between these other choices, so be sure to enter them correctly if they do.<br><br>
<b>Federal Exemptions:</b> Number of exemptions claimed on the employee W4 form.<br><br>
<b>Extra FIT per period:</b> If the employee wants extra Federal Income Tax withheld beyond the normal amount, you can enter the rate here. This rate, in combination with the <i>based on</i> selection will determine the amount of extra tax to withhold.<br><br>
<b>Extra FIT Based On:</b> Select from the pull-down screen how the extra tax is to be calculated. If no amount is entered on the line above this one, then no extra tax will calculate.<br><br>
<b>EIC:</b> Earned Income Credit can be claimed for low income employees. It provides them a reduction in federal income tax. If you employee is claiming low income relief, check this box.<br><br>
<b>State:</b> Select a state for withholding taxes. If none applies, leave this blank.<br><br>
<b>State Exemptions:</b> Number of exemptions claimed on the employee state tax exemption form.<br><br>
<b>Extra SIT per period:</b> If the employee wants extra State Income Tax withheld beyond the normal amount, you can enter the rate here. This rate, in combination with the <i>based on</i> selection will determine the amount of extra tax to withhold.<br><br>
<b>Extra SIT Based On:</b> Select from the pull-down screen how the extra tax is to be calculated. If no amount is entered on the line above this one, then no extra tax will calculate.<br><br>

<b>Local:</b> Select a local tax district for withholding taxes. If none applies, leave this blank.<br><br>
<b>Local Exemptions:</b> Number of exemptions claimed on the employee local tax exemption form.<br><br>
<b>Extra LIT per period:</b> If the employee wants extra local Income Tax withheld beyond the normal amount, you can enter the rate here. This rate, in combination with the <i>based on</i> selection will determine the amount of extra tax to withhold.<br><br>
<b>Extra LIT Based On:</b> Select from the pull-down screen how the extra tax is to be calculated. If no amount is entered on the line above this one, then no extra tax will calculate.<br><br>

<b>City:</b> Select a city for withholding taxes. If none applies, leave this blank.<br><br>
<b>City Exemptions:</b> Number of exemptions claimed on the employee city tax exemption form.<br><br>
<b>Extra CIT per period:</b> If the employee wants extra City Income Tax withheld beyond the normal amount, you can enter the rate here. This rate, in combination with the <i>based on</i> selection will determine the amount of extra tax to withhold.<br><br>
<b>Extra CIT Based On:</b> Select from the pull-down screen how the extra tax is to be calculated. If no amount is entered on the line above this one, then no extra tax will calculate.<br><br>
<b>CONTINUE</b> Press this button to enter more data for this employee.<br><br><hr><br>

<b>Workmans Compensation Rate:</b> percentage rate for calculation workmans comp if your company participates in a state-run program.<br><br>
<b>Deduction Group:</b> If this employee belongs to a special deduction/benefit group, select the group from the pull-down list.<br><br>
<b>PENSION/SAVINGS PLANS: Each employee may contribute to two different plans.</b><br><br>
<b>Plan:</b> Select a Pension/Savings plan from the pull-down list. Each employee can participate in two different plans.<br><br>
<b>Deduction Amount:</b> This can be either a percentage of pay, or a flat amount that is deducted from the employee pay.<br><br>
<b>Base:</b> How the plan deduction is to be calculated.<br><br>
<b>Finish Add:</b> Press this button to complete adding the employee.<br><br><hr><br>


';

?>

