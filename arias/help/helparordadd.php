<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helparordadd.php

echo '<center><b>Add New Order - Part One</b></center><br>';
echo '<b>LOCATION: </b>Which location with the ordered items be shipped FROM. Select from the pull-down list<br><br>';
echo '<b>CUSTOMER #: </b>Enter the Customer Number, or click on the icon to the right of the input section to bring up a select box for customers<br><br>';
echo '<b>PRICE LEVEL: </b>Select which SELLING price level to use for this order. Select from the pull-down list<br><br>';
echo '<b>PURCHASE ORDER: </b>Customer PO Number if they provided one<br><br>';
echo '<b>DUE DATE: </b>When the order is due. To bring up a calendar selection for dates, click on the icon to the right of the input section.<br><br>';
echo '<b>NOTES: </b>Any comments concerning this order. These comments WILL PRINT on the pick ticket.<br><br>';
echo '<b>ADD: </b>Click this button to ADD the order and procede to details about the order.<br><br>';

echo '<center><b>Add New Order - Part Two</b></center><br>';
echo '<font="-1"><b>SHIP TO: </b>Select the appropriate ship-to address from the pull-down list. To add a new ship-to address, click on the PLUS sign.<br><br>';
echo '<b>Next Screen: </b>Click this button to Continue on to the next section for order entry.<br><br>';

echo '<center><b>Add New Order - Part Three</b></center><br>';
echo '<b>ITEM CODE: </b>Enter the code of the item being ordered. To Select an item from a list, click on the icon to the right of the input section.<br><br>';
echo '<b>QUANTITY: </b>Quantity ordered. <br><br>';
echo '<b>PRICE/UNIT: </b>Enter the price PER UNIT. A price will already appear here if the item has a price listed in inventory.<br><br>';
echo '<br><b>NOTE: </b><i>the quantity available (on hand minus committed on other orders) will appear in parentheses after the quantity once you have ADDED the item.</i><br><br>';
//echo '<b>GENERAL LEDGER ACCOUNT: </b>General Ledger SALES type account for the item.<br><br>';
echo '<b>TAXABLE: </b>Check this box if the item is taxable. Each item can be marked separately.<br><br>';
echo '<b>Add New Line Item: </b>Click this button to Add the latest item to the order.<br><br>';
echo '<b>Complete Order: </b>Click on this button only AFTER clicking on the ADD button for the last item ordered. Clicking here will save the order and take you to the next screen.<br><br>';

echo '<center><b>Add New Order - Part Four</b></center><br>';
echo '<b>PICK LIST: </b>To create a Pick Ticket, click on this link.<br><br>';
?>

