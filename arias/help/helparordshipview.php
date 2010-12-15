<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helparordshipview.php


echo '<center><b>Shipment View - Part One</b></center><br>';
echo '<i>Use to view/reprint packing slips.NOTE: If you arrived here from New Shipment entry, skip to Part Four!</i><br><br>';
echo '<i>You need only enter one of the below items to search for an order. If more than one order matches the search criteria entered, you will see a list of these orders, and you can then edit the one desired by clicking on the order number.</i><br><br>';
echo '<b>ORDER NUMBER: </b>Enter the specific order number to be viewed.<br><br>';
echo '<b>PURCHASE ORDER: </b>Enter a specific PO Number to search<br><br>';
echo '<b>CUSTOMER #: </b>Enter the Customer Number to see a list of all orders pending for this customer. If you do not know the customer number, click on the icon to the right of the entry box to bring up a selectable list.<br><br>';
echo '<b>LOCATION: </b>If you have multiply locations, select the location or ALL locations.<br><br>';
echo '<b>Search: </b>Start the search for the selected order<br><br><br>';

echo '<center><b>Shipment View - Part Two</b></center><br>';
echo '<i>You will not see this screen unless more than one order matches the search parameters.</i><br><br>';
echo 'From the list of orders shown, select the one you want to view packing slip for by clicking on the order number. The status of the order will show to the right as Open, Shipped, Partial, etc.<br><br>';

echo '<center><b>Shipment View - Part Three</b></center><br>';
echo '<i>Shipments made for this order.</i><br><br>';
echo 'From the list of shipments shown, select the one you want to view packing slip for by clicking on the shipment date.<br><br>';

echo '<center><b>Shipment View - Part Four</b></center><br>';
echo '<i>Above the item list lines, will appear a list of dates of shipments for this order. If you click on any of these you can bring up the other packing slips associated with this order.<br><br>To print this packing slip, use your browser PRINT button.</i><br><br>';





?>

