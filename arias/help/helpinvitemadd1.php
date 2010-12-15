<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
     echo '<center><b>Item Location Add</b></center><br>';


     echo 'This page allows adding Location Details for Inventory Items.<br><br>';
     echo '<b>Location Name:</b> First you must select a location from the drop-down list. The list of locations is entered separately under Admin-Accounting-Inventory-Location<br><br>';
     echo '<b>Markup Set:</b> If you want selling prices to be calculated automatically from cost figures, select a markup set (sets are entered elsewhere). If you leave this at 0=No Automatic Pricing, then you must manually enter prices in the SELL PRICE section below.<br><br>';

     echo '<b>Stocking Level in XXXX:</b> Seasons (or ALL seasons) is displayed to the left, and you can enter the following by season:<br><br>';
     echo '<b>&nbsp;&nbsp;&nbsp;Maximum:</b> Maximum quantity of units (displayed above after stocking level) to have on-hand.<br>';
     echo '<b>&nbsp;&nbsp;&nbsp;Minimum:</b> Minimum quantity of units to keep on hand<br>';
     echo '<b>&nbsp;&nbsp;&nbsp;Order Quantity:</b> Recommended order quantity when re-ordering<br><br>';

     echo '<b>Sell Price:</b> By Price Level (levels are entered elsewhere). ONLY ENTER PRICES if using 0 under Markup Set. Enter the selling price per pricing unit (unit name displayed above after Sell Price/<br><br>';


     echo '<b>Discount Percent</b> If you want to give quantity discounts, you will need to enter the percentage discounts here. You can have up to three discount price breaks<br><br>';
     echo '<b>On Quantities Over</b> Quantity over which the percentage discount entered to the left will be applied.<br><br>';
     echo '---------------------------<br><br>';
     echo '<b>Enter Composite Items Now?</b> Use this only after you have SAVED any changes made for this location. You will only see this question if the item shown at the top of the page is a composite item.<br><br>';


     echo '';
?>

