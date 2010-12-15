<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helparordshipadd.php

echo '<center><b>New Shipment - Part One</b></center><br>';
echo '<i>Use to ship packages and create a packing slip.</i><br><br>';
echo '<i>You need only enter one of the below items to search for an order. If more than one order matches the search criteria entered, you will see a list of these orders, and you can then edit the one desired by clicking on the order number.</i><br><br>';
echo '<b>ORDER NUMBER: </b>Enter the specific order number to be edited.<br><br>';
echo '<b>PURCHASE ORDER: </b>Enter a specific PO Number to search<br><br>';
echo '<b>CUSTOMER #: </b>Enter the Customer Number to see a list of all orders pending for this customer. If you do not know the customer number, click on the icon to the right of the entry box to bring up a selectable list.<br><br>';
echo '<b>GROUP TO INCLUDE: </b>Select whether to include only Pending, only Shipped or ALL orders.<br><br>';
echo '<b>Search: </b>Start the search for the selected order<br><br><br>';

echo '<center><b>New Shipment - Part Two</b></center><br>';
echo '<i>You will not see this screen unless more than one order matches the search parameters.</i><br><br>';
echo 'From the list of orders shown, select the one you want to ship by clicking on the order number.<br><br>';

echo '<center><b>New Shipment - Part Three</b></center><br>';
echo '<i>For multiple boxes in the shipment, it is important to decide if you want to track weights and costs of each package to be shipped. Press CONTINUE button to procede with the shipment.<br><br>';

echo '<center><b>New Shipment - Part Four</b></center><br>';
echo '<i>The order will be displayed in packing slip style, with the amounts to ship that the computer thinks are right (based on on-hand quantities). If these are NOT right, change the quantity to be shipped. <br><br>';

echo '<b>Quantity Shipped: </b>If you can enter a quantity here it means that the order is not completed. The screen will come up with all quantities filled in assuming full shipment. You will need to change any that are NOT being shipped or change the quantity for a partial shipment.<br><br>';
echo '<b>SHIPPING DATE: </b>Change the date if not shipment is not for current date<br><br>';
echo '<b>SHIPPING METHOD: </b>Select method of shipment and carrier from the pull-down list.<br><br>';
echo '<b>TRACKING NUMBER: </b>If the carrier assigns a tracking number or PRO Number, you can enter that here to make future checking easier.<br><br>';
echo '<b>WEIGHT: </b>package weight.<br><br>';
echo '<b>FREIGHT CHARGES: </b>This can be set up to auto-insert the price from the shipper, or you can enter the amount.<br><br>';
echo '<b>Save Shipping Info: </b>Save Shipment details and create Packing Slip.<br><br>';

echo '<center><b>New Shipment - Part Five</b></center><br>';
echo '<i>If you clicked on the PRINT PACKING SLIP link, the packing slip will display. Print it by using your browser PRINT.</i><br><br>';
?>

