<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helpinvitemupd.php
     echo '<center><b>Inventory Item Update</b></center><br>';
     echo 'Enter an item Code or highlight an item and press SELECT from the lookup window. Then you can press SELECT to edit the information about the item.<br><br>';
     echo '<b>Add New Item</b> Click here to create a new item in inventory.<br><br><br>';

     echo '<b>ITEM CODE: </b>The product number for the item being entered. This can alpha-numeric.<br><br>';
     echo '<b>DESCRIPTION:</b> A summarized description for the item. A more complete description can be entered under Description for Product Catalog. However, since this is the description you will see when looking at lists and on lookups, make it meaningful. You can enter up to 100 characters for this description.<br><br>';
     echo '<b>CATEGORY: </b>If you wish to categorize your inventory by the TYPE of item it is, then you must first enter a category list. Then you will simply choose from the pull-down list shown. A quick way is to press the first letter of the category.<br><br>';
     echo '<b>STOCK UNIT: </b>When you see inventory counts, this is the name of the unit size.<br><br>';
     echo '<b>PRICE UNIT: </b>When showing prices on price lists and invoices, this is the unit name to be used. NOTE: This does NOT need to be the same as the stocking unit. You may stock paper (for example) by sheet, but price it by the M (1000 sheets).<br><br>';
     echo '<b>WEIGHT/PRICE UNIT: </b> The weight of one unit of the unit size used for pricing. This is used for shipping weight calculations.<br><br>';
     echo '<b>PRICE UNIT/STOCK UNIT:</b> The ratio of the price unit to the stock unit in numbers. In the example above of paper, where stocking unit was SHEET and pricing unit was M (1000), the number of pricing units per stock unit would be 1/1000 = .001<br><br>';
     echo '<b>GL INVENTORY ACCOUNT: </b> Inventory type General Ledger account to use when posting usages/receipts.<br><br>';
     echo '<b>GL SALES ACCOUNT: </b> Sales type General Ledger account to use when posting sales/usages.<br><br>';
     echo '<b>COMPOSITE ITEM (such as Kit or catalog):</b> If you answer YES to this question, then you must specify the other items in inventory that go to make up this composite item.<br><br>';
     echo '<b>DESCRIPTION FOR PRODUCT CATALOG: </b> Full description as you would like to see in a catalog of your items. A description for your customers rather than your staff.<br><br>';
     echo '<b>URL/FILE FOR PRODUCT SHEET: </b>If you want to create a file containing a product sheet (either locally or somewhere on the web at a manufacturers site) this can point to that place to pull up the information to be printed. Click on the BROWSE button to help you locate the file/graphic.<br><br>';
     echo '<b>URL/FILE FOR PRODUCT GRAPHIC: </b>Pointer to where a picture of the product can be found -- to be used in a catalog. Click on the BROWSE button to help you locate the file/graphic.<br><br>';
     echo '<b>Update: </b>Press this button to SAVE the changes you have made.<br><br>';
     echo '<b>Delete this item</b> Click on this link to remove the item from inventory.<br><br>';

?>

