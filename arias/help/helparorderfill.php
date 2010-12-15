<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helparorderfill.php

echo '<center><b>Fill Order Start-Stop - Part One</b></center><br>';
echo '<i>Use the start or stop filling an order, thus tracking who has the order and when they began working on it.</i><br><br>';
echo '<b>Operator: </b>The operator will default to the sign-on user, but it can be changed by selecting from the pull-down list.</b><br><br>';
echo '<i>You need only enter one of the below items to search for an order. If more than one order matches the search criteria entered, you will see a list of these orders, and you can then edit the one desired by clicking on the order number.</i><br><br>';
echo '<b>Order Number: </b>Enter the specific order number to be edited.<br><br>';
echo '<b>PURCHASE ORDER: </b>Enter a specific PO Number to search<br><br>';
echo '<b>CUSTOMER #: </b>Enter the Customer Number to see a list of all orders pending for this customer. If you do not know the customer number, click on the icon to the right of the entry box to bring up a selectable list.<br><br>';
echo '<b>Search: </b>Start the search for the selected order<br><br><br>';

echo '<center><b>Fill Order Start-Stop - Part Two</b></center><br>';
echo '<b>Select from List: </b>If more than one order fits the search parameters entered in part one, that list will show here. You will also see the last action entered for this order. Select the order you want from this list by clicking on the order number.<br><br>';

echo '<center><b>Fill Order Start-Stop - Part Three</b></center><br>';
echo '<b>Stop/Go Light: </b>A GREEN light for START or a RED light for STOP will appear. If the previous entry to the order was START, then the Red Stop light will show. If filling was not started, a Green GO light will show. Click on the light to start/stop the filling process.<br><br>';

?>

