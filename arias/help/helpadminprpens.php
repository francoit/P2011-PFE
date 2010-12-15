<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
     echo '<center><b>Payroll Pension/Cafeteria/Savings Plan Entry/Change</b></center><br>';
     echo '<i>Define payroll pension, savings, or cafeteria plans and how the amounts are to be calculated.</i><br><br>
<center><b>SELECT PLAN</b></center><br><br>
<b>SELECT PLAN:</b> You will see a pull-down list of plans. Choose one if you want to edit a plan, then press EDIT PLAN button.<br><br>

<b>ADD NEW PLAN:</b> Press this button if you want to create a new plan.<br><br>

<center><b>ENTER/CHANGE PLAN</b></center><br><br>

<b>PLAN NAME:</b> Name will appear on the payroll summaries and on the employee check stubs.<br><br>
<b>GENERAL LEDGER PAYABLES ACCOUNT:</b> Select a payables type of account for posting to general ledger plan amounts to be paid.<br><br>
<b>GENERAL LEDGER EXPENSE ACCOUNT:</b> for Company Portion: The General Ledger expense account for posting the amounts paid into the plan by the company.<br><br>
<b>VENDOR:</b> In order for the payroll program to automatically generate entries into payables, you will need to select a vendor -- the company you will be sending the checks to for this plan.<br><br>
<b>W2 USE WHAT BOX FOR PLAN:</b> When printing the plan amounts on the W2 forms, you will need to select from the pull-down list, which area of the W2 should carry the plan data.<br><br>
<b>If Box 13 Qualified Plan - LETTER FOR W2 PRINT:</b><i> If you selected Box 13 </i>on the above question, then you will need to enter a letter to indicate the type of qualified plan this is (see IRS instructions for W2).<br><br>
<b>EMPLOYER CONTRIBUTES HOW:</b> Select from the pull-down list.<br><br>
<b>MAXIMUM % MATCHED BY EMPLOYER:</b> If you selected that the employer contributes by matching the employee deduction, then this is the MAXIMUM amount the employer will match.<br><br>
<b>EMPLOYEE MUST PARTICIPATE:</b> Check this box if the employee must contribute in order for the company to contribute to the plan.<br><br>
<b>BASIS FOR CALCULATION:</b> Select from the pull-down list which pay will be used as the basis for calculating percentages.<br><br>
<b>ONLY FOR THIS DEDUCTION GROUP:</b> If only employees having a matching group code are to be included in this plan, then enter that group code here. Be sure you ALWAYS enter the group code the same way in order for this to work correctly.<br><br>
<b>COVERS WHICH WORKERS:</b> Select from the pull-down list whether all, salaried only or hourly only are included in this plan.<br><br>
<b>WHICH TAXES ARE CALCULATED ON WAGES LESS DEDUCTION FOR PLAN?:</b> Below this are a list of check boxes. Check any that should have plan amounts subtracted BEFORE the tax calculation is done.<br><br>
<b>SAVE CHANGES:</b> Unless you press this button, the changes will not be saved.<br><br>
<b>DELETE THIS PLAN: </b>Select this link ONLY if you want to delete all the information about this plan.<br><br>';
?>

