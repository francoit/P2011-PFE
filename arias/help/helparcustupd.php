<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
     echo '<center><b>Customer Update</b></center><br>';


     echo 'This page allows update of Customers for use in Receivables, Estimating, and Inventory Sales.<br><br>';

     echo '<b>FIRST: </b> select the customer to be updated from the pull-down list.<br><br>';
     echo '<b>SECOND: </b> check whether what you are updating is General Info or Shipping Info.<br><br>';
     echo '<b>THIRD: </b> if you selected SHIP-TO, then you must select from the pull-down list an existing ship-to or create a new one. You may have as many ship-to addresses for a customer as needed.<br><br>';
     echo '-------------------------------------------<br><br>';
     echo '<b>Company Name:</b> Full name of company.<br><br>';
     echo '<b>Address: </b>Two lines are provided for entering address information.<br><br>';
     echo '<b>City:</b> City portion of address.<br><br>';
     echo '<b>State:</b> It is best to use the two-letter state abbreviation here.<br><br>';
     echo '<b>Country:</b> You need only fill this out if you have customers in more than one country.<br><br>';
     echo '<b>Mail Stop:</b> If this customer uses internal Mail Stop Codes, enter that info here.<br><br>';
     echo '<b>Phone & Phone Description:</b> There are four different phone numbers that can be entered. Examples might be Office, FAX, CELL, Pager, etc. Enter phone extension information by prefixing the extension with the letter x.<br><br>';
     echo '<b>E-mail & E-mail Description: </b> Two different e-mails can be entered along with a note as to the purpose (or person) for each.<br><br>';
     echo '<b>Web Address:</b> If your customer maintains a web site, put that address here.<br><br>';
     echo '<b>Federal ID:</b> For tax purposes, you may need to carry this information.<br><br>';

     echo '<b>SHIPPING UPDATE WILL NOT CONTAIN THE BELOW ITEMS, ONLY GENERAL INFO SCREEN WILL INCLUDE THESE</b><br><br>';

     echo '<b>Tax Exemption:</b> This is a pull-down list of possible reasons why a customer is NORMALLY tax exempt. You enter the list under Admin-Accounting-Receivables-Tax Exemptions.<br><br>';
     echo '<b>Sales Tax ID:</b> Your customers sales tax exemption number (a state provided number).<br><br>';
     echo '<b>Sales Tax District: </b>The number of sales tax districts displayed will be determined by an set field. You can enter up to that number of sales tax districts for each customer. The sales tax district names and rates are entered under admin-accounting-receivables-sales tax.<br><br>';
     echo '<b>Credit Limit: </b>Maximum amount of credit you want to extend to this customer.<br><br>';
     echo '<b>Sales GL Account: </b>These accounts are entered under General Ledger. When sales are made to this customer, this is the DEFAULT account to use when posting the sales amount. On the pull-down you will only see accounts of the account type SALES.<br><br>';
     echo '<b>Sales Person:</b> What sales person is assigned to this customer.<br><br>';
     echo '<b>Sales Rep:</b> If you use Customer Sales Reps in addition to sales persons, select that person now.<br><br>';
     echo '<b>Invoice Terms:</b> Terms you wish to extend to this customer. Enter this list under Admin-Accounting-Receivables-Invoice Terms.<br><br>';
     echo '<b>Quote Comment:</b> In the estimating systems, you can issue quotes. If you select a comment here, it will ALWAYS print on quotes issued to this customer.<br><br>';
     echo '<b>Charge Interest?:</b> When calculating interest, should this customer have interest charged on overdue accounts?<br><br>';
     echo '<b>Bill-to Attn:</b> Who should invoices be sent to the attention of.<br><br>';
     echo '<b>Quote Attn:</b> Who should quotes be sent to the attention of?<br><br>';
     echo '<b>Charge Code:</b> This is your CUSTOMERs internal billing code. Only enter a number here if you need to produce some kind of summarizing report by billing code for your customer.<br><br>';
     echo '<b>GENERAL INFORMATION SCREEN WILL NOT CONTAIN THE BELOW ITEM, ONLY THE SHIP-TO SCREEN WILL INCLUDE THIS ITEM</b><br><br>';
     echo '<b>Default Ship Via</b> Preferred method of shipment when sending to this ship-to location.<br><br>';


     echo '';
?>

