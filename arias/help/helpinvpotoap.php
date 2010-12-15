<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //helpinvpotoap.php - copyright 2001 by Noguska - Fostoria, Ohio 44830
     echo '<center><b>Inventory PO Pass to Payables</b></center><br>';
     echo '<i>When a bill is received for items that were purchased using a PO, use this routine to pass details from the PO into payables.</i><br><br>

     <b>VENDOR:</b> Enter the vendor number, or select the vendor by clicking on the magnifier.<br><br>
<b>LOCATION:</b> Location where items received, select from pull-down list, or leave blank for all locations.<br><br>

<b>SHOW LIST:</b> Press this button to view list of POs received.<br><br><hr><br><br>
<b>INCLUDE?</b> Check this box if this PO and this item are billed on this invoice.<br><br>
<b>QTY:</b> Quantity billed at this time (cannot exceed quantity received!)<br><br>
<b>PRICE PER UNIT</b> Unit price for the item.<br><br>
<b>TOTAL $$ SELECTED</b>The calculated amounts of quantity times price per unit.<br><br>
<b>END SELECTIONS</b> When you have marked all the items for this invoice, Press the End Selections button to continue with the rest of the invoice entry.<br><br><hr><br>
<b>FREIGHT</b> Freight amount charged on this invoice.<br><br>
<b>TAX:</b> Any tax amount charged on this invoice.<br><br>
<b>DISCOUNT DATE:</b> This will calculate automatically based on the vendor terms, but you can over-ride the calculation now. Click on the calendar icon to bring up a calendar date selection.<br><br>
<b>DUE DATE:</b> This will calculate automatically based on the vendor terms, but you can over-ride the calculation now. Click on the calendar icon to bring up a calendar date selection.<br><br>
<b>DISCOUNT AMOUNT:</b> This will calculate automatically based on the vendor terms, but you may change the amount here.<br><br>
<b>PRE-PAID:</b> If this invoice was prepaid, check this box.<br><br>
<b>SAVE INVOICE:</b> To save the invoice entries made, press this button.<br><br>



';

?>

