<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helparordshiptick.php

echo '<center><b>QC/Ship - Part One</b></center><br>';
echo '<i>Use when finalizing the package preparation for shipping, or to reprint any packing slip. This process will generate a Packing List to be attached to the package (or placed inside).</i><br><br>';
echo '<i>You need only enter one of the below items to search for an order. If more than one order matches the search criteria entered, you will see a list of these orders, and you can then edit the one desired by clicking on the order number.</i><br><br>';
echo '<b>Order Number: </b>Enter the specific order number to be edited.<br><br>';
echo '<b>PURCHASE ORDER: </b>Enter a specific PO Number to search<br><br>';
echo '<b>CUSTOMER #: </b>Enter the Customer Number to see a list of all orders pending for this customer. If you do not know the customer number, click on the icon to the right of the entry box to bring up a selectable list.<br><br>';
echo '<b>Search: </b>Start the search for the selected order<br><br><br>';

echo '<center><b>QC/Ship - Part Two</b></center><br>';
echo '<i>After filling in the information on this screen and pressing SAVE SHIPPING INFO, the Packing Slip format will appear. Use the browser print a packing slip to insert with the packages.</i><br><br>';
echo '<i>Directly below the ship-to information, you may see a list of prior shipments made for this order. <br><br>REPRINT: To reprint one of those packing slips (or simply look at the details), click on the underlined shipment date).<br><br>TRACK SHIPMENT: Click on the tracking number to bring up the carrier web site (if they have one) to see where the package is and when or if it was delivered.</i><br><br>';
echo '<b>Quantity Shipped: </b>If you can enter a quantity here it means that the order is not completed. The screen will come up with all quantities filled in assuming full shipment. You will need to change any that are NOT being shipped or change the quantity for a partial shipment.<br><br>';
echo '<b>SHIPPING DATE: </b>Change the date if not shipment is not for current date<br><br>';
echo '<b>SHIPPING METHOD: </b>Select method of shipment and carrier from the pull-down list.<br><br>';
echo '<b>TRACKING NUMBER: </b>If the carrier assigns a tracking number or PRO Number, you can enter that here to make future checking easier.<br><br>';
echo '<b>WEIGHT: </b>If the carrier assigns a tracking number or PRO Number, you can enter that here to make future checking easier.<br><br>';
echo '<b>FREIGHT CHARGES: </b>If the carrier assigns a tracking number or PRO Number, you can enter that here to make future checking easier.<br><br>';
echo '<b>NUMBER OF PACKAGES: </b>Number of packages in this shipment.<br><br>';
echo '<b>Save Shipping Info: </b>Save Shipment details and create Packing Slip.<br><br>';

echo '<center><b>QC/Ship - Part Three</b></center><br>';
echo '<i>This is the packing slip. Print it by using your browser PRINT.</i><br><br>';
?>

