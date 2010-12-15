<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php  //helpapbilladd.php - copyright 2001 - Noguska - Fostoria, OH 44830
     echo '<center><b>Bill Add</b></center><br>';
     echo '<i>Enter vendor invoices and distribution amounts if not brought into payables automatically by one of the other modules.</i><br><br>

     <b>INVOICE #:</b> Vendor invoice number.<br><br>
     <b>VENDOR #:</b> Vendor code. Click on magnifier to search for vendor, or click on plus sign to add a new vendor.<br><br>
     <b>TOTAL:</b> Invoice total (before any discounts for early payment).<br><br>
     <b>DATE OF INVOICE:</b> Date that appears on the vendor invoice. If it is a utility bill, then use last time-period covered.<br><br>
     <b>APPLY DISCOUNTS:</b> Check this box to include in list any bills not yet due, but whose discount dates fall on the due-date entered.<br><br>
     <b>CONTINUE</b> Press continue button to enter more invoice information.<br><br><hr><br>

     <b>DESCRIPTION:</b> Reference description (will auto-fill with default description for vendor). This is for information only.<br><br>
     <b>DISCOUNT AMOUNT:</b> Calculates automatically based on vendor terms, but can be changed at this time.<br><br>
     <b>DISCOUNT DATE:<b> Calculates automatically based on vendor terms, but can be changed at this time. This is the date through which you can still take any discount offered.<br><br>
     <b>DUE DATE:<b> Calculates automatically based on vendor terms, but can be changed at this time. This is the date after which the invoice is overdue.<br><br>

     <b>CONTINUE:</b> Press the continue button to enter invoice distribution details.<br><br><hr><br>
     <b>AMOUNT:</b> Distribution amount (you can distribute each bill into as many accounts as required).<br><br>
     <b>GL ACCOUNT:</b> Select a GL account - usually an expense or cost of good sold type of account - for the amount entered to post to.<br><br>
     <b>CURRENT TOTAL:</b> Total distribution entered so far - display only.<br><br>
     <b>NEEDED TOTAL:</b> Original invoice total - this can be changed if entered incorrectly on the first screen. NOTE: If you change the total, you will automatically recalculate the discount!<br><br>
     <b>DIFFERENCE:</b> Amount remaining to distribute - display only.<br><br>
     <b>CONTINUE:</b> Press continue to update the entries and open up a new line for further distributions if needed.<br><br>
     <b>COMPLETE BILL:</b> You will ONLY see this if the difference is zero ($0.00). Press this button to finalize your entries and save.<br><br>';
?>

