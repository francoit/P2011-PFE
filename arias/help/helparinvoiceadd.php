<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helparinvoiceadd.php

echo '<center><b>Add Invoice Add - Part One</b></center><br>';
echo '';
echo '<b>CUSTOMER #: </b>Enter the Customer Number, or click on the icon to the right of the input section to bring up a select box for customers. Click on the PLUS sign to add a new customer.<br><br>';
echo '<b>INVOICE DATE: </b>Although this will default to the system date, you can change the date of the invoice. The date of the invoice will be used in determining the dicount and due dates.<br><br>';
echo '<b>Continue: </b>Click this button to ADD the order and procede to details about the order.<br><br>';

echo '<center><b>Add Invoice - Part Two</b></center><br>';
echo '<font="-1"><b>SHIP TO: </b>Select the appropriate ship-to address from the pull-down list. To add a new ship-to address, click on the PLUS sign.<br><br>';
echo '<b>INVOICE NUMBER: </b>This field will auto-fill in with the next highest number, but you can enter your own number if desired.<br><br>';
echo '<b>PURCHASE ORDER: </b>PO for this order.<br><br>';
echo '<b>CUSTOMER BILL CODE: </b>For customers who want an accounting code to show on their invoices.<br><br>';
echo '<b>SALES PERSON: </b>This field will auto-fill in with the usual salesman for this customer, but will allow you to change it for this invoice.<br><br>';
echo '<b>INVOICE TERMS: </b>This field will auto-fill in with the usual payment terms for this customer, but will allow you to change it for this invoice.<br><br>';

echo '<b>Next Screen: </b>Click this button to Continue on to the next section for order entry.<br><br>';

echo '<center><b>Add Invoice - Part Three</b></center><br>';
echo '<b>DESCRIPTION: </b>Description to appear on invoice.<br><br>';
echo '<b>TAXABLE: </b>Check this box if the line is taxable. Each line can be marked separately.<br><br>';
echo '<b>PRICE/UNIT: </b>Enter the price PER UNIT. <br><br>';
echo '<b>UNIT NAME: </b>Select a PRICING unit size (if you do not see the one you need, you will have to enter a new one under BILLING--SETUP<br><br>';
echo '<b>GL SALES ACCOUNT: </b>General leger sales account for posting data - this will default to the usual sales account used by this customer.<br><br>';
echo '<b>QUANTITY: </b>Quantity ordered. <br><br>';
echo '<b>UNIT NAME: </b>Select a QUANTITY unit size (if you do not see the one you need, you will have to enter a new one under BILLING--SETUP<br><br>';
echo '<b>QTY.UNIT PER PRICE UNIT: </b>The number of items in a quantity unit size that are contained in a pricing unit size. For example if you sell paper priced by the 1000 sheets but list the quantity in sheets, then the entry here would be 1000.<br><br>';
echo '<b>Add Line Item to Invoice: </b>Click this button to Add the lastest line to the invoice. This will also bring up a new space to enter another line item for invoice.<br><br>';
echo '<b>Complete Order: </b>Click on this button only AFTER clicking on the ADD button for the last line entered.<br><br>';

echo '<center><b>Add New Order - Part Four</b></center><br>';
echo '<b>DUE DATE:</b> Date when invoice will be considered due. This will be automatically filled in based on the invoice date and the customer terms, but you can change it for this invoice.<br><br>';
echo '<b>DISCOUNT DATE: </b>date after which any discount for early payment will be lost. This will be automatically filled in based on the invoice date and the customer terms, but you can change it for this invoice.<br><br>';
echo '<b>SHIPPING: </b>Amount to add to invoice for shipping charges. <br><br>';
echo '<b>Continue</b> Click on this button to continue with the entering the invoice.<br><br>';


echo '<center><b>Add New Order - Part Five</b>ssh </center><br>';
echo '<b>Enter Cost of Goods:</b> If you want to track Cost of Goods back to GL or for determining profit from sales, click here to enter details.<br><br>';
echo '<b>Post/Print this Invoice: </b>If you have completed all entries for this invoice, click here to post the amounts to General Ledger and also to print the invoice.<br><br>';
echo '<b>Add New Invoice: </b>Click here to start entering data on a new invoice. <br><br>';
?>

