<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //helpadminprtaxtypeupd.php - copyright 2001 by Noguska - Fostoria, Ohio 44830
     echo '<center><b>Payroll Tax Update - General Information</b></center><br>';
     echo '<i>Exemption Allowances and other general information about tax.</i><br><br>
<b>TAX TYPE:</b> Select a tax type from the pull-down list.<br><br>
<b>SELECT:</b> After selecting a tax type, press select button to move to the next screen.<br><br><hr>

<b> **  TAX TYPES OTHER THAN FEDERAL **</b><br><br>

<b>TAX:</b> Select a Tax from the pull-down list.<br><br>
<b>TAX NAME:</b> Full Name of Taxing District.<br><br>
<b>SUI %:</b> State Unemployment rate. This only shows for STATE tax types.<br><br>
<b>MAX SUI WAGES PER YEAR:</b>State unemployment based on wages up to this ceiling durint the calendar year. This only shows for STATE tax types<br><br>
<b>SELECT:</b> After selecting a tax type, press select button to move to the next screen.<br><br><hr>

<b>TAX CHECK ABBR:</b> Abbreviated name of tax district to be used on check stubs.<br><br>
<b>TAX#:</b> Tax number to print on W2 for this tax district.<br><br>

<b>DEDUCT FEDERAL TAX FIRST?</b> Check this box if federal tax should be deducted from wages before calculating this tax.<br><br>
<b>MAXIMUM FEDERAL DEDUCTION:</b> If you select to deduct Federal tax first, then what is the maximum amount of federal tax than can be deducted.<br><br>

<b>GL PAYABLES ACCOUNT:</b> General Ledger posting account for tax payable.<br><br>
<b><center>** EXEMPTIONS before Tax Calculated **</center></b><br><br>

<b>AMOUNT FOR 1 EXEMPTION:</b> Amount of wages that are exempt (annual) when claiming one exemption.<br><br>
<b>AMOUNT PER EXEMPTION FOR 2:</b> Amount of wages that are exempt PER EXEMPTION(annual) when claiming two exemptions.<br><br>
<b>AMOUNT PER EXEMPTION FOR 3:</b> Amount of wages that are exempt PER EXEMPTION(annual) when claiming three exemptions.<br><br>
<b>AMOUNT PER EXEMPTION FOR 4 & up:</b> Amount of wages that are exempt PER EXEMPTION(annual) when claiming four and more exemptions.<br><br>
<b>MAXIMUM EXEMPTION %:</b> Maximum exemption amount allowed as a percentage of wages. If no maximum, leave this entry zero.<br><br>
<b>MAXIMUM EXEMPTION AMOUNT PER YEAR:</b> Maximum exemption amount allowed annually as a dollar amount.<br><br>

<b><center>** TAX CREDITS After Tax Calculated **</center></b><br><br>

<b>CREDIT FOR 1 EXEMPTION:</b> Tax credit when claiming one exemption.<br><br>
<b>CREDIT PER EXEMPTION FOR 2:</b> Tax credit PER EXEMPTION(annual) when claiming two exemptions.<br><br>
<b>CREDIT PER EXEMPTION FOR 3:</b> Tax credit PER EXEMPTION(annual) when claiming three exemptions.<br><br>
<b>CREDIT PER EXEMPTION FOR 4 & up:</b> Tax credit PER EXEMPTION(annual) when claiming four and more exemptions.<br><br>

<b>SAVE CHANGES:</b> Press this button to save changes made.<br><br><hr>

<b> **  FEDERAL TAX TYPE  **</b><br><br>

<b>ANNUAL EXEMPTION ALLOWANCE:</b> Taken times the number of federal exemptions claimed, the result is used to reduce annualized wages before using tax withholding table.<br><br>
<b>FICA - Max. Wages: </b>The maximum amount of wages on which FICA is to be taken.<br><br>
<b>FICA - Employee %:</b> Percentage of wages deducted for FICA tax<br><br>
<b>FICA - Company %: </b>Percentage of wages company has to pay into FICA<br><br>

<b>MEDICARE - Max. Wages: </b>The maximum amount of wages on which MEDICARE is to be taken.<br><br>
<b>MEDICARE - Employee %:</b> Percentage of wages deducted for MEDICARE tax<br><br>
<b>MEDICARE - Company %: </b>Percentage of wages company has to pay into MEDICARE<br><br>

<b>FUI - Max. Wages: </b>The maximum amount of wages on which FUI (Federal Unemployment tax) is to be paid.<br><br>
<b>FUI - Company %: </b>Percentage of wages company has to pay into FUI.<br><br>

<hr><br><br>
<b><center>EIC (Earned Income Credit)</center></b><br>
<i>For low income employees - this is a REDUCTION in tax. See SCHEDULE E for table. Be sure to use ANNUAL table.)></i><br><br>
<b>SAVE CHANGES:</b> Press this button to save changes made.<br><br><hr>


';

?>

