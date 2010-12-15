<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php echo '<center><b>Bill Update</b></center><br>';
     echo '<i>Update vendor invoices and distribution amounts whether entered in payables or brought into payables automatically by one of the other modules.</i><br><br> 

     <b>INVOICE #:</b> To search for specific invoice, enter vendor invoice number.<br><br>
     <b>VENDOR #:</b> To search for specific vendor, enter vendor code. Click on magnifier to search for vendor, or click on plus sign to add a new vendor.<br><br>
     <b>BEGIN DATE OF INVOICE:</b> To limit search, enter a beginning invoice date to include in list.<br><br>
     <b>END DATE OF INVOICE:</b> To limit search, enter an ending invoice date to include in list.<br><br>
     <b>BEGIN DUE DATE:</b> To limit search, enter a beginning due date to include in list.<br><br>
     <b>END DUE DATE:</b> To limit search, enter an ending due date to include in list.<br><br>
     <b>CONTINUE</b> Press continue button to search for invoice.<br><br><hr><br>

     <i>Select invoice from list by clicking on the invoice number.</i><br><br><hr><br>

     <b>INVOICE #:</b> Vendor invoice number.<br><br>
     <b>VENDOR #:</b> Vendor code.<br><br>
     <b>TOTAL:</b> Invoice total.<br><br>
     <b>DATE OF INVOICE:</b> Date printed on vendor invoice.<br><br>
     <b>DUE DATE:<b> Date after which the invoice is overdue.<br><br>
     <b>DESCRIPTION:</b> Reference description. This is for information only.<br><br>
     <b>DISCOUNT AMOUNT:</b> Amount of discount if invoice paid by discount date below.<br><br>
     <b>DISCOUNT DATE:<b> This is the date through which you can still take any discount offered.<br><br>
     <b>GO TO DETAILS</b> Press this button to bring up invoice details.<br><br>
     <b>DELETE</b> Click on this link to delete the entire bill. <b>NOTE:</b> You will NOT see the link if checks have already been written in payment of this bill.<br><br>
     <hr><br>


     <b>AMOUNT:</b> Distribution amount (you can distribute each bill into as many accounts as required).<br><br>
     <b>GL ACCOUNT:</b> Select a GL account - usually an expense or cost of good sold type of account - for the amount entered to post to.<br><br>
     <b>CURRENT TOTAL:</b> Total distribution entered so far - display only.<br><br>
     <b>NEEDED TOTAL:</b> Original invoice total - this can be changed if entered incorrectly on the first screen. NOTE: If you change the total, you will automatically recalculate the discount!<br><br>
     <b>DIFFERENCE:</b> Amount remaining to distribute - display only.<br><br>
     <b>UPDATE:</b> Press continue to update the entries and open up a new line for further distributions if needed.<br><br>
     <b>COMPLETE BILL:</b> You will ONLY see this if the difference is zero ($0.00). Press this button to finalize your entries and save.<br>'; ?> 
