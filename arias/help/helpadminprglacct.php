<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //helpadminprglacct.php
     echo '<center><b>Payroll Standard GL Accounts</b></center><br>';
     echo '<i>Posting accounts for passing data to General Ledger from Payroll. Also some general information for W2s.</i><br><br>
<b>FEDERAL TAX ID:</b> Company Federal Tax ID Number.<br><br>

<b>STATE UNEMPLOYMENT ID:</b> State Unemployment ID Number. This is NOT the same as the W2 State tax number, but rather a separate number used when reporting unemployment data.<br><br>
<b>W2 COMPANY NAME:</b> Company name as it should appear on W2s.<br><br>

<b>W2 ADDRESS 1:</b> Address line one for W2.<br><br>
<b>W2 ADDRESS 2:</b> Address line two for W2.<br><br>
<b>W2 CITY STATE & ZIP:</b> City State and Zip Code W2.<br><br>

<b>POST TO PAYABLES?:</b> Check this box if you want to auto-generate payables entries for taxes (other than federal), garnishments, savings, etc. deductions.<br><br>
<b>DEFAULT CHECKING ACCOUNT:</b> Checking account used most often when writing employee checks.<br><br>
<b>AUTO PRINT DEPOSIT CHECK:</b> Check this box if you want to automatically print a tax deposit check at the end of printing payroll checks.<br><br>

<b>VENDOR FOR PAYROLL DEPOSIT CHECK:</b> This should be the bank or other institution to whom the payroll tax deposit check should be made out.<br><br>

<b><center> General Ledger Accounts for Posting</center></b><br><br>
<b>CHECKING:</b> GL Posting account for amounts paid out.<br><br>
<b>FEDERAL INCOME TAX PAYABLE:</b> GL Posting account for amounts due for Federal Income Tax.<br><br>
<b>FICA PAYABLE:</b> GL Posting account for amounts due for FICA withheld from employees and contributed by company.<br><br>
<b>FICA COMPANY EXPENSE:</b> GL Posting account for amounts contributed by company.<br><br>
<b>MEDICARE PAYABLE:</b> GL Posting account for amounts due for MEDICARE withheld from employees and contributed by company.<br><br>
<b>MEDICARE COMPANY EXPENSE:</b> GL Posting account for amounts contributed by company.<br><br>
<b>FEDERAL UNEMPLOYMENT TAX PAYABLE:</b> GL Posting account for amounts due for FUI contributed by company.<br><br>
<b>FEDERAL UNEMPLOYMENT TAX EXPENSE:</b> GL Posting account for amounts contributed by company.<br><br>
<b>STATE UNEMPLOYMENT PAYABLE:</b> GL Posting account for amounts due for SUI contributed by company.<br><br>
<b>STATE UNEMPLOYMENT COMPANY EXPENSE:</b> GL Posting account for amounts contributed by company.<br><br>
<b>WORKMANS COMP PAYABLE:</b> GL Posting account for amounts due for Workmans Comp withheld from employees and contributed by company.<br><br>
<b>WORKMANS COMP COMPANY EXPENSE:</b> GL Posting account for amounts contributed by company.<br><br>
<b>MISC.DEDUCTION PAYABLE:</b> GL Posting account for amounts due for Miscellaneous Deductions withheld from employees.<br><br>
<b>MISC. TAX-EXEMPT PAY EXPENSE:</b> GL Posting account for amounts paid to an employee that represent an expense to the company <i>(usually reimbursements for company expenses paid for by an employee).</i><br><br>


<b>SAVE CHANGES:</b> If you want to SAVE, press this button.<br><br>';
?>

