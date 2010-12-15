<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
// Defaults
$InventoryAcctId = 10;    // Default Inventory GL Account ID
$SalesAcctId     = 59;    // Default Sales account ID
$DefaultLoctId   = 1 ;    // Default location ID

// Set to 1 for verbose mode
$debug    =0; 
// Tracks # of records inserted
$inserted =0; 
// add to queries to select company, not cancelled
$sql_comp =' and companyid='.sqlprep($active_company).' and cancel=0';   
   
echo '<center>';

if ($filename) {
  $gfilename = IMAGE_UPLOAD_DIR."impitem".strtolower(substr($filename, strrpos($filename,'.')));
  if (!move_uploaded_file($filename, $gfilename)){
    retrievefile($filename,$gfilename);
   }

  include("./includes/file_db.class.php");

  if ($debug) echo "debug - ".$gfilename."<br>";

  $rec = new file_db ;
  $rec->filename = $gfilename ;
  $rec->open_db();

  while (!$rec->EOF) {
    if ($debug)echo ">>".$rec->record["itemid"]."-".$rec->record["itemid"]."<br>";
    $rec->move_next();
  }

  $rec->move_first();

  //$fcontents = file ($gfilename);

  if ($debug) echo "debug - first item [".$rec["description"]."]<br>";
  //itemid,description,catalogurl,graphicurl,category,stockunit,priceunit,supplier,price,avgcost,lastcost,onhand

  while (!$rec->EOF) {
   $itemid      = $rec->record["itemid"];
   $description = $rec->record["description"];
   $supplier    = $rec->record["supplier"];
   $category    = $rec->record["category"];
   $stockunit   = $rec->record["stockunit"];
   $priceunit   = $rec->record["priceunit"];
   $price       = $rec->record["price"];
   $cost        = $rec->record["avgcost"];
   $lastcost    = $rec->record["lastcost"];
   $onhand      = $rec->record["onhand"];
   $catalogurl  = $rec->record["catalogurl"];
   $graphicurl  = $rec->record["graphicurl"];

   # Handle blank link fields
   if (!$category)  $category  = "DEFAULT";
   if (!$stockunit) $stockunit = "EACH";
   if (!$stockunit) $priceunit = "EACH";

   //Handle the inserts
   if ($itemid) {
       $stkpk  = add_unit($stockunit);
	   $prcpk  = add_unit($priceunit);
	   $catpk  = add_category($category);
	   
	   if (!$stkpk) $stkpk = get_unitid();
	   if (!$prcpk) $prcpk = get_unitid();
	   if (!$catpk) $catpk = get_categoryid();
	   
	   $itempk = add_item($itemid,$description,$catpk,$stkpk,$prcpk,$catalogurl,$graphicurl);
	   $locpk  = add_item_location($itempk,$cost,$lastcost,$onhand);
	   $newprc = add_pricelevel($itempk, $price, $locpk);
   } else {
     echo "error-invalid item ID: >$itemid<<br>";
   }
   
   $rec->move_next();
  }
  echo "\n";
  echo "<br>++".$inserted."<br";
  echo texttitle($lang['STR_ITEMS_HAVE_BEEN_IMPORTED']).'<br><br>';
} else {
    echo texttitle($lang['STR_SELECT_ITEM_FILE']);
    echo '<form enctype="multipart/form-data" action="importitem.php" name="mainform" method="POST"> ';
    echo '<input type="hidden" name="MAX_FILE_SIZE" value="300000"> ';
    echo $lang['STR_URL_FILE_FOR_CSV_FORMAT_ITEM_FILE'] ;
    echo ': <input name="filename" type="file"> ';
    echo '<input type="submit" value="'.$lang['STR_SELECT_FILE'].'"> ';
    echo '</form>';
    };

echo '</center>';
?>

<?php
  function add_category($cat_name) {
  // Checks for a category name, adds if not found, returns the category PK
  global $conn, $active_company;

  	$sql    = sprintf("SELECT id FROM itemcategory WHERE name=%s",sqlprep($cat_name));
  	$result = $conn->Execute($sql) or print($conn->ErrorMsg());
  	$catpk  = $result->fields[0];
  
  	if (!$catpk and $cat_name) {
      $sql    = sprintf("INSERT INTO itemcategory (name) VALUES (%s)",sqlprep($cat_name));
	  $result = $conn->Execute($sql) or print($conn->ErrorMsg());
	  $catpk = $conn->Insert_ID();
  	}
	return $catpk ;
  }
  
  function add_unit($unit_name) {
  	// Checks for a unit name, adds if not found, returns the unit_pk
  	global $conn, $active_company;

  	$sql    = sprintf("SELECT id FROM unitname WHERE unitname=%s",sqlprep($unit_name));
  	$result = $conn->Execute($sql) or print($conn->ErrorMsg());
  	$unitpk = $result->fields[0];
  
  	if (!$unitpk and $unit_name) {
      $sql    = sprintf("INSERT INTO unitname (unitname) VALUES (%s)",sqlprep($unit_name));
	  $result = $conn->Execute($sql) or print($conn->ErrorMsg());
	  $unitpk = $conn->Insert_ID();
  	}	
	return $unitpk ;
  }
  
  function add_item($itemid,$description,$catpk,$stkpk,$prcpk,$catalogurl,$graphicurl){
  // Adds the item record if it does not already exist
  // (assumes itemID is unique). Returns the item pk.
  global $conn, $active_company, $sql_comp, $InventoryAcctId, $SalesAcctId ;

  	$sql    = sprintf("SELECT id FROM item WHERE itemcode=%s %s",sqlprep($itemid),$sql_comp);
  	$result = $conn->Execute($sql) or print($conn->ErrorMsg());
  	$itempk = $result->fields[0];
  
  	if (!$itempk and $itemid) {
      $sql    = sprintf("INSERT INTO item 
	              (itemcode,description,categoryid,stockunitnameid,
				   priceunitnameid,catalogsheeturl,graphicurl,
				   catalogdescription,priceunitsperstockunit,
				   inventoryglacctid, salesglacctid, companyid) 
				  VALUES 
				  (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
				  sqlprep($itemid), 
				  sqlprep($description), 
				  sqlprep($catpk), 
				  sqlprep($stkpk), 
				  sqlprep($prcpk), 
				  sqlprep($catalogurl), 
				  sqlprep($graphicurl), 
				  sqlprep($description),
				  sqlprep(1),
				  sqlprep($InventoryAcctId),
				  sqlprep($SalesAcctId),
				  sqlprep($active_company)		  
				  ); 
	  $result = $conn->Execute($sql) or print($conn->ErrorMsg());
	  echo "+";
	  $itempk = $conn->Insert_ID();
  	}
	return $itempk ;  
  }

  function add_item_location($itempk,$avgcost,$lastcost,$onhand) {
  // Adds an item location if it does not already exist.
  // Also sets costs.
  	global $conn, $active_company, $DefaultLoctId;

  	$sql    = sprintf("SELECT id FROM itemlocation 
	          WHERE itemid=%s and inventorylocationid=%s",
			  sqlprep($itemid),sqlprep($DefaultLoctId));
  	$result = $conn->Execute($sql) or print($conn->ErrorMsg());
  	$loctpk = $result->fields[0];
  
  	if (!$loctpk and $itempk) {
      $sql    = sprintf("INSERT INTO itemlocation 
	             (itemid,inventorylocationid,onhandqty,
				  firstcost,midcost,lastcost,firstqty) 
				 VALUES 
				 (%s,%s,%s,%s,%s,%s,%s)",
				  sqlprep($itempk),
				  sqlprep($DefaultLoctId),
				  sqlprep($onhand),
				  sqlprep($avgcost),
				  sqlprep($avgcost),
				  sqlprep($lastcost),				 
				  sqlprep($onhand)				 
				 );
	  $result = $conn->Execute($sql) or print($conn->ErrorMsg());
	  $loctpk = $conn->Insert_ID();
  	} elseif ($loctpk and $itempk) {
	
	    $sql = sprintf("UPDATE itemlocation SET onhandqty = onhandqty + %s
	           WHERE itemid=%s and inventorylocationid=%s",
			   sqlprep($onhand),
			   sqlprep($itempk),
			   sqlprep($DefaultLoctId));
	   $result = $conn->Execute($sql) or print($conn->ErrorMsg());
	}
	return $loctpk ;  
  }

  function add_pricelevel($itempk,$price, $loctpk) {
  // Adds an item price level if it doesn't already exist
  // Returns the pricelevel pk
  	global $conn, $active_company;

  	$sql    = sprintf("SELECT id FROM priceperpriceunit 
	            WHERE itemid=%s and itemlocationid=%s",
				sqlprep($itempk),sqlprep($loctpk));
  	$result = $conn->Execute($sql) or print($conn->ErrorMsg());
  	$plvlpk = $result->fields[0];
  
  	if (!$plvlpk and $itempk) {
      $sql    = sprintf("INSERT INTO priceperpriceunit 
	               (itemid,itemlocationid,pricelevelid,price) 
				   VALUES 
				   (%s,%s,%s,%s)",
				   sqlprep($itempk),
				   sqlprep($loctpk),
				   sqlprep(1),
				   sqlprep($price)
				   );
	  $result = $conn->Execute($sql) or print($conn->ErrorMsg());
	  $plvlpk = $conn->Insert_ID();
  	}	
	return $plvlpk ;
  }

  function get_unitid() {
  // Gets the EACH unit ID or the first if no EACH
  	global $conn;
  
  	$sql = "SELECT id FROM unitname WHERE unitname='each'";
  	$result = $conn->Execute($sql);
  	$unitpk = $result->fields[0];
  
  	if (!$unitpk) {
      $sql    = "SELECT id FROM unitname";
	  $result = $conn->Execute($sql);
	  $unitpk = $result->fields[0];
  	}
	if (!$unitpk) $unitpk = 1;
	return $unitpk;
  }

function get_categoryid() {
  // Gets the first category ID from the itemcategory table
  	global $conn;
  
    $sql    = "SELECT id FROM itemcategory";
    $result = $conn->Execute($sql);
    $catpk  = $result->fields[0];
	//print_r($result);

//	if (!$catpk) $catpk = 1;
  return $catpk;
  }
?>

<?php include('includes/footer.php'); ?>
