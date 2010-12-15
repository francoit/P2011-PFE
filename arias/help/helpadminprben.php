<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //helpadminprben.php
     echo '<center><b>Payroll General Benefits (pay)</b></center><br>';
     echo '<i>Pay amounts, all calculated the same way, that apply to all or a group of employees.</i><br><br>
<b>HRLY/SAL.:</b> Select whether this benefit is for ONLY hourly or ONLY Salaried workers.<br><br>

<b>DESCRIPTION:</b> Name or Description.<br><br>

<b>HOW FIGURED:</b> Select from the pull-down list. Calculation could be based on: a percentage of taxable pay; a percentage of taxable pay minus taxes; a percentage of Straight-time pay; an amount per each hour worked; an amount per each hour paid (double-time = double hours, time and-a-half = 1.5 times hours); an amount per week; or an amount in the first pay period of each month.<br><br>
<b>GRP:</b> Deduction Group. If this benefit only affects a certain group of employees, use the employee group designation here (and on each employee record.<br><br>
<b>RATE:</b> A percentage or a dollar amount depending on which selection was made under HOW FIGURED.<br><br>
<b>GL EXPENSE ACCOUNT:</b> General Ledger account to post the expense side of this transaction to.<br><br>
<b>DELETE:</b> Check this box if this benefit is no longer needed. It will be removed from future lists.<br><br>
<b>UPDATE AND REDISPLAY:</b> Unless you press this button, the changes will not be saved. In addition, if you need more entries than you have room for on the screen, pressing this button will create a new blank entry for you to use.<br><br>
<b>SAVE CHANGES:</b> If you want to SAVE, but not add more lines, press this button.<br><br>';
?>

