<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     echo '<center><b>Vendor Add</b></center><br>';


     echo 'This page allows adding of Vendors for use in Payables and Purchasing, and Inventory.<br><br>';
     echo '<b>ORDER FROM INFO</b> If you see this at the top of your page, then you are entering data about who to place orders with. If you see PAY TO at the top of the page, then you are entereing name and address information concerning payments to be made.<br><br>';
     echo '---------------------------<br><br>';

     echo '<b>Company Name:</b> Full name of company.<br><br>';
     echo '<b>Address: </b>Two lines are provided for entering address information.<br><br>';
     echo '<b>City:</b> City portion of address.<br><br>';
     echo '<b>State:</b> It is best to use the two-letter state abbreviation here.<br><br>';
     echo '<b>Country:</b> You need only fill this out if you have vendors located in more than one country.<br><br>';
     echo '<b>Mail Stop:</b> If this vendor uses internal Mail Stop Codes, enter that info here.<br><br>';
     echo '<b>Phone & Phone Description:</b> There are four different phone numbers that can be entered. Examples might be Office, FAX, CELL, Pager, etc. Enter phone extension information by prefixing the extension with the letter x.<br><br>';
     echo '<b>E-mail & E-mail Description: </b> Two different e-mails can be entered along with a note as to the purpose (or person) for each.<br><br>';
     echo '<b>Web Address:</b> If your supplier maintains a web site, put that address here.<br><br>';
     echo '<b>Federal ID:</b> For tax purposes, you may need to carry this information (ie: 1099 forms).<br><br>';

     echo '<b>Payment Terms:</b> Terms extended to you by your supplier. Enter this list under Admin-Accounting-Payables-Payment Terms.<br><br>';
     echo '<b>Pay None:</b> A checkbox to indicate that no checks should be written during automatic processing for this vendor.<br><br>';
     echo '<b>Default GL Account:</b> Which is the usual GL account to use when entering bills (usually an expense type of account). The Payables side of entering a bill will be taken care of automatically.<br><br>';
     echo '<b>Default Bill Description:</b> The usual explanation for bills received from this supplier.<br><br>';
     echo '<b>Customer Account:</b> If you have been assigned an account number by your vendor. This number will print on checks issued to this vendor.<br><br>';
     echo '<b>Make Pay To Same as Above:</b> If the pay-to information is the same as what you have just entered, leave this box checked. However, if it is different, UNCHECK THE BOX. If you UNCHECK the box, then after pressing the ADD button, you will see another screen with information about payments. These entries will be the same as just described but will end with Federal ID.<br><br>';


     echo '';
?>

