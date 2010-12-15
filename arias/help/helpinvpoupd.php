<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php  //helpinvpoupd.php - copyright 2001 - Noguska - Fostoria, OH 44830
     echo '<center><b>Update Inventory PO</b></center><br>';
     echo '<i>Change Purchase Order Information, add or delete items on PO.</i><br><br>

     <b>PO #:</b> If you want to limit the PO search to a specific PO, enter the number here.<br><br>
     <b>VENDOR #:</b> If you want to limit the PO search to a specific vendor, enter the number here.<br><br>
     <b>CARRIER SERVICE:</b> If you want to limit the PO search to a specific carrier, select the carrier from the pull-down list.<br><br>
     <b>LOCATION:</b> If you want to limit the PO search to a specific location, select the location from the pull-down list.<br><br>
     <b>DUE DATE:</b> If you want to limit the PO search to a specific due date, enter the date or click on the calendar icon to select a date.<br><br>
     <b>REQUISITION #:</b> If you want to limit the PO search to a requisition number, enter that number here.<br><br>
     <b>VENDOR ORDER #:</b> If you want to limit the PO search to a specific vendor order #, enter that number here.<br><br>
     <b>SHOW LIST:</b> Press this button when you are ready to view the list of POs.<br><br><hr><br>

     <i>If more than one PO matches the selections made, you will see a list. Click on the PO number of the one you want to edit.</i><br><br><hr><br>

     <b>VENDOR #:</b> To change the vendor, select from the pull-down list.<br><br>
     <b>CONTACT:</b> Name of vendor contact.<br><br>
     <b>VENDOR ORDER #:</b> This is your vendor confirmation or work order number.<br><br>
     <b>PO #:</b> It is best not to change this number to avoid duplications, but it may be changed here.<br><br>
     <b>DUE DATE:</b> Date by which you want to receive the items.<br><br>
     <b>REQUISITION #:</b> Internal requisition number associated with this PO.<br><br>
     <b>LOCATION:</b> Location where the goods purchased should be sent.<br><br>
     <b>SHIPPING METHOD:</b> Preferred method of shipping - select from pull down list.<br><br>
     <b>TRACKING #:</b> Shipper tracking number.<br><br>
     <b>UPDATE PO</b> Press UPDATE button when ready to edit item information for the PO.<br><br>
     <b>DELETE THIS PO:</b> If you can see this option, then clicking on this link will cancel the PO. You will NOT see this link if you have already received items for this PO. To cancel individual items, you must zero out the order quantities.<br><br><hr><br>

     <b>ITEM CODE:</b> To change an item code, enter a code, or click on the magnifier to look up an item.<br><br>
     <b>QUANTITY:</b> Quantity to purchase.<br><br>
     <b>PRICE/UNIT:</b> Price per unit purchased.<br><br>
     <b>UPDATE PO DETAILS:</b> Press this button when all items to be updated have been changed.<br><br><hr><br>

     <i><b>Note:</b> TO DELETE AN ITEM FROM A PO: - blank out the item code, or zero out the quantity ordered.</i><br><br>

    // <b>PRINT THIS PO:</b> Once the PO has updated successfully, you will be shown this link. Click on it to actually create the printable PO.<br><br>
'?>

