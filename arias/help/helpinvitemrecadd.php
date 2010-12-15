<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helpinvitemrecadd.php

echo '<center><b>Receive Items (no PO) - Part One</b></center><br>';
echo '<i>Receive items into inventory for which no Purchase Order was written. This will update item on-hand and cost figures.</i><br><br>';
echo '<b>VENDOR: </b>Select vendor from pull-down list (vendor must have been entered previously under Vendor ADD in Payables.<br><br>';
echo '<b>Continue: </b>Press this button to go to the item selection screen.<br><br>';

echo '<center><b>Receive Items (no PO) - Part Two</b></center><br>';
echo '<b>ITEM CODE: </b>Enter an item code, or click on the icon to the right of the input section to bring up a list of items.<br><br>';
echo '<b>DATE RECEIVED: </b>If a Date other that the current date needs to be entered, either enter the date in the same format as shown, or use the pop-up calendar by clicking on the icon to the right of the date input area.<br><br>';
echo '<b>TRACKING NUMBER (BOL or other): </b>Entering this information might help during future checking.<br><br>';
echo '<b>RECEIVE LOCATION: </b>Select the location into which this item is being received. Use the pull-down list to select.<br><br>';
echo '<b>Continue: </b>Press this button to proceed with the receiving.<br><br>';

echo '<center><b>Receive Items (no PO) - Part Three</b></center><br>';
echo '<i>If you selected a Vendor already assigned to this item, some of the information will be already filled in. However, you can edit that information and it will update the vendor-item information section.</i><br><br>';
echo '<b>VENDOR PRODUCT CODE: </b>Product Code used by the vendor for this item (for use when placing orders to this vendor).<br><br>';
echo '<b>QUANTITY UNITS RECEIVED: </b>Enter the quantity (pay attention to the UNITS, they will vary by item.<br><br>';
echo '<b>PRICE PER UNIT: </b> When you enter the quantity, this price will fill in automatically if the vendor-item information exists. Otherwise you may enter the price here.<br><br>';

echo '<b>COST: </b>Cost per Unit (up to four different prices based on quantity purchased.<br><br>';
echo '<b>UP TO THIS QUANTITY of Units: </b>Quantity break to get this cost.<br><br>';
echo '<b>Save Data: </b>Press SAVE to save entries made.<br><br>';
echo '<i>After SAVING, you will see two buttons, one to go to another vendor for receiving. The other is to use the same vendor but a different item.<br><br>';
?>

