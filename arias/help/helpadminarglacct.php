<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php 
//helpadminarglacct.php

echo '<center><b>Receivables Standard GL Accounts</b></center>';
echo '<i>These Accounts are used for determining how to post certain information to General Ledger. On any of the below entries you may select the right account from the pull-down list.</i><br><br>';
echo '<b>CASH: </b>General Ledger Account for Petty Cash (for cash income received from sales).<br><br>';
echo '<b>CHECKING: </b>General Ledger Account for Checks, Credit Card Deposits, etc. received as income from sales.<br><br>';
echo '<b>INTEREST INCOME: </b>General Ledger Account to use when interest is paid on an invoice paid after the due date.<br><br>';
echo '<b>DISCOUNT GIVEN: </b>General Ledger Account for the amount of DISCOUNT given if the invoice is paid within the discount terms.<br><br>';
echo '<b>COST OF GOODS SOLD: </b>General Ledger Account for the cost of the items sold.<br><br>';
echo '<b>INVENTORY: </b>General Ledger Account for Inventory reduction for items sold.<br><br>';

echo '<b>SHIPPING LIABILITY: </b>General Ledger Payable Account for Shipping charges passed along to the customer.<br><br>';
echo '<b>RECEIVABLES: </b>General Ledger Asset Account for Accounts Receivables (Sales made via Open Account - to be paid at a later date).<br><br>';
echo '<b>Save Changes: </b>SAVE the changes made.<br><br>';
?>

