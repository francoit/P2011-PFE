<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php 
//helpadminarcompanyupd.php

echo '<center><b>AR Company Options</b></center><br>';
echo '<b>'.$lang['STR_IMAGE_URL'].': </b>Type in the URL. This is the image graphic that will be used when printing packing slips, invoices, etc. that might be sent to customers<br><br>';
echo '<b>'.$lang['STR_INVOICE_PAST_DUE_SERVICE_CHARGE'].': </b>Service charge that will be applied to AR Invoices after they have become past due.<br><br>';
echo '<b>'.$lang['STR_INVOICE_INTEREST_RATE'].': </b>Interest rate that will be applied every 30 days from Invoice date after invoice has become past due.</b><br><br>';
echo '<b>'.$lang['STR_NEXT_INVOICE_NUMBER'].': </b>Set this higher than your current invoice number to start a new invoice number sequence.<br><br>';
echo '<b>Save Changes: </b>Changes will not be saved until you click on this button.<br><br>';
?>

