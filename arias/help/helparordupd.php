<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helparordupd.php

echo '<center><b>Order Update/Reprint Pick List  - Part One</b></center><br>';
echo '<i>You need only enter one of the below items to search for an order. If more than one order match the search criteria entered, you will see a list of these orders, and you can then edit the one desired by clicking on the order number.<br><br>';
echo '<b>Order Number: </b>Enter the specific order number to be edited.<br><br>';
echo '<b>PURCHASE ORDER: </b>Enter a specific PO Number to search<br><br>';
echo '<b>CUSTOMER #: </b>Enter the Customer Number to see a list of all orders pending for this customer. If you do not know the customer number, click on the icon to the right of the entry box to bring up a selectable list.<br><br>';
echo '<b>Search: </b>Start the search for the selected order';

echo '<center><b>Order Update/Reprint Pick List  - Part Two</b></center><br>';
echo '<i>Although all this information will be filled in from the order entry section, you can either change the information, or go to PICK LIST funtion.</i><br><br>';
echo '<b>ITEM CODE: </b>Enter the code of the item being ordered. To Select an item from a list, click on the icon to the right of the input section.<br><br>';
echo '<b>QUANTITY: </b>Quantity ordered. <br><br>';
echo '<b>PRICE/UNIT: </b>Enter the price PER UNIT. A price will already appear here if the item has a price listed in inventory.<br><br>';
echo '<b>GENERAL LEDGER ACCOUNT: </b>General Ledger SALES type account for the item.<br><br>';
echo '<b>TAXABLE: </b>Check this box if the item is taxable. Each item can be marked separately.<br><br>';
echo '<b>Save Changes: </b>Click this button to SAVE the changes made.<br><br>';
echo '<b>RESET: </b>Click on this button if you want to erase any changes made since you last hit SAVE.<br><br>';

echo '<b>PICK LIST: </b>To create a Pick Ticket, click on this link.<br><br>';
?>

