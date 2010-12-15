<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //helpinvporecv.php - copyright 2001 by Noguska - Fostoria, Ohio 44830
     echo '<center><b>Inventory PO Receive</b></center><br>';
     echo '<i>Use to mark items received against POs.</i><br><br>
<b>PO #:</b> Enter PO# if specific PO received. If more than one PO in shipment received, leave this blank, and just enter the vendor number.<br><br>
<b>VENDOR:</b> Enter the vendor number, or select the vendor by clicking on the magnifier.<br><br>
<b>CARRIER SERVICE:</b> Which carrier brought the shipment. Select from pull-down list.<br><br>
<b>LOCATION:</b> Location where items received, select from pull-down list, or leave blank for all locations.<br><br>
<b>DUE DATE:</b> If you want your search to include only POs with specific due dates, enter a date here.<br><br>
<b>REQUISITION #:</b> If you want to limit your search to a specific requisition number, enter that number here.<br><br>
<b>ORDER #:</b> If you want to limit your search to a specific order number, enter that number here.<br><br>
<b>SHOW LIST:</b> Press this button to view list of POs waiting receipt.<br><br><hr><br><br>

<b>SELECT PO</b> To select a PO to receive items for, click on the PO number. This will bring up the PO details.<br><br><hr><br>
<b>QUANTITY RECEIVED:</b> The number that appears in this box is the amount waiting to be received. You can change it, or if you did not receive that item, enter a zero(0) for quantity.<br><br>
<b>PRICE:</b> This is the price per unit. You can leave the price unchanged, or update it now.<br><br>
<b>TRACKING #:</b> Bill of Lading or other tracking number associated with the item received.<br><br>
<b>PO COMPLETE: </b>Check this box if this is the final receipt for this PO. This tells the system to close the PO.<br><br>

<b>RECEIVE PO:</b> To finalize the receipt entries, press this button last.<br><br>



';

?>

