<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helpgljouradd.php

echo '<center><b>Add GL Journal Entries - Part One</b></center><br>';
echo '<i>Use this section to create journal entries for General Ledger that are not covered by other packages (Payables, Receivables, etc.)</i><br><br>';

echo '<b>JOURNAL VOUCHER: </b>This number can be anything you want: a check number, partial description, etc. It will be used when referring back to this entry to correct or summarize.<br><br>';
echo '<b>VOUCHER DESCRIPTION: </b>Description of the purpose for this voucher.<br><br>';
echo '<b>TRANSACTION DATE: </b>Enter the date or click on the calendar icon to the right of the input section to help select a date.<br><br>';
echo '<b>Continue: </b>Proceed to the rest of the voucher input.<br><br>';

echo '<center><b>Add GL Journal Entries - Part Two</b></center><br>';
echo '<b>GENERAL LEDGER ACCOUNT: </b>Use the pull-down list to select the account number for the first part of the voucher entry.<br><br>';
echo '<b>AMOUNT: </b>Amount of transaction - NOTE: All credit entries are entered as negative, debit entries as positive amounts. NOTE: Use Help Table below to determine which is which.<br><br>';
echo '<b>Continue: </b>Proceed to the rest of the voucher input. <br><br>';
echo '<table border="1"><tr><th>Account Type</th><th>Increase</th><th>Decrease</th></tr>';
echo '<tr><td>Assets</td><td>Debit +</td><td>Credit -</td></tr>';
echo '<tr><td>Cost of Goods Sold</td><td>Debit +</td><td>Credit -</td><tr>';
echo '<tr><td>Expenses</td><td>Debit +</td><td>Credit -</td><tr>';
echo '<tr><td>Liabilities</td><td>Credit -</td><td>Debit +</td><tr>';
echo '<tr><td>Capital</td><td>Credit -</td><td>Debit +</td><tr>';
echo '<tr><td>Retained Earnings</td><td>Credit -</td><td>Debit +</td><tr>';
echo '<tr><td>Sales & Sales Adjustments</td><td>Credit -</td><td>Debit +</td><tr>';
echo '<tr><td>Other Income & Expenses</td><td>Credit -</td><td>Debit +</td><tr>';
echo '</table><br><br>';

echo '<center><b>Add GL Journal Entries - Part Three</b></center><br>';
echo '<i>From this point on, each screen will appear the same except the table at the bottom of the display will grow with each new account you assign to this voucher.</i><br><br>';
echo '<b>GENERAL LEDGER ACCOUNT: </b>Use the pull-down list to select the account number for the first part of the voucher entry.<br><br>';
echo '<b>AMOUNT: </b>Amount of transaction - NOTE: All credit entries are entered as negative, debit entries as positive amounts. NOTE: Use Help Table above to determine which is which.<br><br>';
echo '<b>Continue: </b>Add Another Account Distribution to voucher. <br><br>';
echo '<b>Voucher XX Complete: </b>When you see this message, you are in balance and you can click here to end voucher entries and SAVE the information.<br><br>';

?>

