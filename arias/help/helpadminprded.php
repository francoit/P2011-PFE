<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //helpadminprded.php
     echo '<center><b>Payroll General Deductions</b></center><br>';
     echo '<i>Deductions that apply to all or groups of employees all having the same deduction calculation rather than specific employees.</i><br><br>
<b>HRLY/SAL.:</b> Select whether this contribution is for ONLY hourly or ONLY Salaried workers.<br><br>

<b>DESCRIPTION:</b> Name or Description.<br><br>

<b>HOW FIGURED:</b> Select from the pull-down list. Calculation could be based on: a percentage of taxable pay; a percentage of taxable pay minus taxes; a percentage of Straight-time pay; an amount per each hour worked; an amount per each hour paid (double-time = double hours, time and-a-half = 1.5 times hours); an amount per week; or an amount in the first pay period of each month.<br><br>
<b>GRP:</b> Deduction Group. If this deduction only affects a certain group of employees, use the employee group designation here (and on each employee record.<br><br>
<b>RATE:</b> A percentage or a dollar amount depending on which selection was made under HOW FIGURED.<br><br>
<b>YEARLY CEILING:</b> If this deduction is only calculated for the first so many dollars of wages (like Federal Unemployment) then enter the maximum wages here. If there is no cut-off, leave this entry zero.<br><br>
<b>GL PAYABLE ACCOUNT:</b> The General Ledger account to be posted to for the payables part of the transaction.<br><br>
<b>VENDOR:</b> Enter a vendor number, or press the magnifier to select from a list, or if this is a new vendor, press the plus sign to add now. The vendor is entered so that a payables bill can be created automatically for this deduction.<br><br>
<b>DELETE:</b> Check this box if this deduction is no longer needed. It will be removed from future lists.<br><br>
<b>UPDATE AND REDISPLAY:</b> Unless you press this button, the changes will not be saved. In addition, if you need more entries than you have room for on the screen, pressing this button will create a new blank entry for you to use.<br><br>
<b>SAVE CHANGES:</b> If you want to SAVE, but not add more lines, press this button.<br><br>';
?>

