<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php  //helpapbillpay.php - copyright 2001 - Noguska - Fostoria, OH 44830
     echo '<center><b>Pay Bills</b></center><br>';
     echo '<i>Enter payment information and print checks for bills to be paid.</i><br><br>

     <b>INVOICE #:</b> To pay a specific invoice, enter an invoice number here.<br><br>
     <b>VENDOR #:</b> To limit payments to a specific vendor, enter a vendor number.<br><br>
     <b>DUE DATE:</b> To limit list of bills to be paid to only those due by a given date, enter that date here.<br><br>
     <b>END DATE OF INVOICE:</b> To limit report to invoices between two given invoice dates, enter a date here and for begin invoice date.<br><br>
     <b>APPLY DISCOUNTS:</b> Check this box to include in list any bills not yet due, but whose discount dates fall on the due-date entered.<br><br>
     <b>CONTINUE</b> Press continue button when ready to create the report.<br><br><hr><br>
     <b>VENDOR (Hold):</b> If you check a vendor, it will place all bills for that vendor on hold, not to be paid until the hold check is removed at some future date.<br><br>
     <b>BILL ON HOLD:</b> To place just one bill on hold, check this box, and it will not allow payment until this box is unchecked at some future date.<br><br>
     <b>PAY INVOICE:<b> To include the invoice in the payments to be made at this time, check this box.<br><br>
     <b>CONTINUE:</b> Press the continue button at the bottom when all invoices are marked.<br><br><hr><br>
     <i>One by one the checks that will be written (based on the check-offs on the previous screen) will be displayed, along with the total, the check number, date, etc.<br><br>
'?>

