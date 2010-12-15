<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helparinvoiceupd.php

echo '<center><b>Invoice Update - Part One</b></center><br>';
echo '';
echo '<b>INVOICE #: </b>Enter the Invoice Number if you want to search for a specific invoice.<br><br>';
echo '<b>PURCHASE ORDER: </b>Enter a PO Number if you want the search to include only a specific PO Number.<br><br>';
echo '<b>CUSTOMER #: </b>Enter the Customer NumberIf you want the search limited to a specific customer.<br><br>';
echo '<b>NOTES: </b>Enter a part of a note if you want a search limited to a specific note.<br><br>';

echo '<b>Search: </b>Click this button to SEARCH for the invoice.<br><br>';

echo '<center><b>Update Invoice - Part Two</b></center><br>';
echo '<font="-1"><b>LIST OF MATCHES: </b>If more than one invoice meets the search criteria entered on the previous page, you will see a list. If only one match, this screen will be skipped. Select the invoice you wish to edit, by clicking on the invoice number. The order STATUS will be displayed as well as other information about the invoice.<br><br>';

echo '<b>Next Screen: </b>Click this button to Continue on to the next section for order entry.<br><br>';

echo '<center><b>Update Invoice - Part Three</b></center><br>';
echo '<b>DATE: </b>Due Date for invoice.<br><br>';
echo '<b>SALESMAN: </b>Select from a pull-down list if you wish to change.<br><br>';
echo '<b>NOTES: </b>You can enter notes you wish to print on the invoice or NOT print on the invoice. If you enter a note then SAVE, you will be able to enter another note.<br><br>';
echo '<b>HIDE WHEN PRINTING: </b>Refers to the note to the immediate left of the check-box. If checked, the note will NOT appear on the printed invoice for the customer.<br><br>';
echo '<b><center>LINE ENTRIES</center></b><i><br>You will always see one blank line that you can use to add a new entry. If you add a new entry then press SAVE, you will have room to enter yet another new line.<br><br></i>';
echo '<b>DESCRIPTION: </b>Description of the item begin billed.<br><br>';
echo '<b>PRICE/UNIT: </b>Enter the price PER UNIT. <br><br>';
echo '<b>UNIT NAME: </b>Select a PRICING unit size (if you do not see the one you need, you will have to enter a new one under BILLING--SETUP<br><br>';
echo '<b>GL SALES ACCOUNT: </b>General leger sales account for posting data - this will default to the usual sales account used by this customer.<br><br>';
echo '<b>QUANTITY: </b>Quantity ordered. <br><br>';
echo '<b>UNIT NAME: </b>Select a QUANTITY unit size (if you do not see the one you need, you will have to enter a new one under BILLING--SETUP<br><br>';
echo '<b>QTY.UNIT PER PRICE UNIT: </b>The number of items in a quantity unit size that are contained in a pricing unit size. For example if you sell paper priced by the 1000 sheets but list the quantity in sheets, then the entry here would be 1000.<br><br>';

echo '<b>SHIPPING: </b>Shipping amount to charge.<br><br>';
echo '<b>Save Changes: </b>Pressing this button will save the changes made.<br><br>';
echo '<b>Reset: </b>Pressing this button will cancel any changes made since the last SAVE.<br><br>';
echo '<b>Enter cost of goods sold: </b>Click on this link to edit Costs associated with this invoice.<br><br>';
echo '<b>Post/Print Invoice: </b>You MUST perform this step to finalize the changes you have made. If you do not, then General Ledger will not be updated.<br><br>';

echo '<b>Unpost Invoice: </b>you must do this step before editing any of the data on the invoice. If you do not see this message, then the invoice was not yet posted.<br><br>';
echo '<b>Delete Invoice: </b>If you want to remove the invoice from the system, click on this link. If you do not see this option, you must UNPOST before you can delete.<br><br>';


?>

