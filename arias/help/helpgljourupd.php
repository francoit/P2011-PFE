<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helpgljourupd.php

echo '<center><b>Update Journal Entries - Part One</b></center><br>';
echo '<i>Use this section to edit journal entries made in General Ledger or created by Receivables, Payables, Inventory, etc.</i><br><br>';
echo '<b>SEARCH DATA: </b>To limit the number of transactions to be selected from.<br><br>';
echo '<b>Search: </b>Start the search using the limits entered.<br><br>';

echo '<center><b>Update Journal Entries - Part Two</b></center><br>';
echo '<i>Highlight the voucher to be edited from the displayed list, then press the SELECT button.</i><br><br>';

echo '<center><b>Update Journal Entries - Part Three</b></center><br>';
echo '<b>JOURNAL VOUCHER: </b>This number can be anything you want: a check number, partial description, etc. It will be used when referring back to this entry to correct or summarize.<br><br>';
echo '<b>VOUCHER DESCRIPTION: </b>Description of the purpose for this voucher.<br><br>';
echo '<b>TRANSACTION DATE: </b>Enter the date or click on the calendar icon to the right of the input section to help select a date.<br><br>';
echo '<b>Continue: </b>Proceed to the rest of the voucher input.<br><br>';
echo '<b>Delete this voucher: </b>Click on this statement to delete the displayed voucher.<br><br>';

echo '<center><b>Update Journal Entries - Part Four</b></center><br>';
echo '<i>You will be displayed a list of all the entries for this voucher. Edit them as you wish. If you wish to start the edit over, abandoning the changes, press the RESET VALUES button. Otherwise press the SAVE CHANGES when Done.</i><br><br>';
echo '<b>GENERAL LEDGER ACCOUNT: </b>Use the pull-down list to select the account number for the first part of the voucher entry.<br><br>';
echo '<b>AMOUNT: </b>Amount of transaction - NOTE: All credit entries are entered as negative, debit entries as positive amounts. NOTE: Use Help Table below to determine which is which.<br><br>';
echo '<b>Save Changes: </b>Save the Changes made. NOTE: You CANNOT save until the voucher is in balance.<br><br>';
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


?>

