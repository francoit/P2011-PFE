<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php') ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //invitemupd1.php - add/update item locations
	echo texttitle('Item Location Add/Update for '.$itemcode." - ".$description);
	if (!$inventorylocationid) { //this if statement sets inventorylocationid if there is only one valid one
		$recordSet = &$conn->Execute('select count(*) from inventorylocation, company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company));
		if (!$recordSet->EOF) {
			if ($recordSet->fields[0]==1) {
				$recordSet=&$conn->Execute('select inventorylocation.id from inventorylocation, company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
				if (!$recordSet->EOF) $inventorylocationid=$recordSet->fields[0];
			};
		} else {
			die(texterror('No inventory locations found. (query failed)'));
		};
	};

	if ($id&&!$inventorylocationid) {
		if (!$categoryname) {
			$recordSet=&$conn->Execute('select sunitname.unitname, punitname.unitname, item.compositeitemyesno, item.categoryid, sunitname.id, punitname.id from item,unitname as sunitname,unitname as punitname where item.stockunitnameid=sunitname.id and item.priceunitnameid=punitname.id and item.itemcode='.sqlprep($itemcode));
			if (!$recordSet->EOF) {
				$stockunit=$recordSet->fields[0];
				$priceunit=$recordSet->fields[1];
				$composityesno=$recordSet->fields[2];
				$categoryid=$recordSet->fields[3];
			};
			$recordSet=&$conn->Execute("select name, seasonname1, seasonname2, seasonname3, seasonname4 from itemcategory where id=".$categoryid);
			if (!$recordSet->EOF) {
				$categoryname=$recordSet->fields[0];
				$seasonname1=$recordSet->fields[1];
				$seasonname2=$recordSet->fields[2];
				$seasonname3=$recordSet->fields[3];
				$seasonname4=$recordSet->fields[4];
			};
		}; // finished reading item information needed
		echo '<form action="invitemupd1.php" method="post"><input type="hidden" name="nonprintable" value="1"><table border=1>';
		echo '<input type="hidden" name="itemcode" value="'.$itemcode.'">';
		echo '<input type="hidden" name="id" value="'.$id.'">';
		echo '<input type="hidden" name="categoryid" value="'.$categoryid.'">';
		echo '<input type="hidden" name="description" value="'.$description.'">';
		echo '<input type="hidden" name="composityesno" value="'.$composityesno.'">';
		echo '<input type="hidden" name="categoryname" value="'.$categoryname.'">';
		echo '<input type="hidden" name="seasonname1" value="'.$seasonname1.'">';
		echo '<input type="hidden" name="seasonname2" value="'.$seasonname2.'">';
		echo '<input type="hidden" name="seasonname3" value="'.$seasonname3.'">';
		echo '<input type="hidden" name="seasonname4" value="'.$seasonname4.'">';
		echo '<input type="hidden" name="stockunit" value="'.$stockunit.'">';
		echo '<input type="hidden" name="priceunit" value="'.$priceunit.'">';
		if (!$seasonname1) $seasonname1='All';
		echo '<tr><td>Location Name:</td><td><select name="inventorylocationid"'.INC_TEXTBOX.'>';
		$recordSet = &$conn->Execute('select inventorylocation.id, company.companyname, company.id from inventorylocation, company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
		while (!$recordSet->EOF) {
			echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
			$companyid=$recordSet->fields[2];
			$recordSet->MoveNext();
		};
		echo '</select></td></tr><input type="hidden" name="companyid" value="'.$companyid.'">';
		echo '</table><input type="submit" value="Update Location"></form>';
	} elseif ($inventorylocationid&&($delete||$savedata)) {
		if ($delete) { //delete record if that field has been set  1=delete, 2=activate
			invitemlocationdelete($delete,$id,$inventorylocationid);
		} elseif ($savedata) { //save data if flag has been set to SAVE
			$conn->BeginTrans();
			if (!invitemlocationaddupdate(1,$itemid,$inventorylocationid,$onhandqty,$maxstocklevelseason1,$minstocklevelseason1,$orderqtyseason1,$maxstocklevelseason2,$minstocklevelseason2,$orderqtyseason2,$maxstocklevelseason3,$minstocklevelseason3,$orderqtyseason3,$maxstocklevelseason4,$minstocklevelseason4,$orderqtyseason4,$markupsetid,$id)) {
				$conn->RollbackTrans();
				die();
			};
			if (!$markupsetid) { //now add pricing per level info for this item
				for ($counter=1;$counter<=$pricecounter;$counter++) {
					if (!invitemlocationprice(1,$itemid,$inventorylocationid,$id,${"price".$counter},${"pricelevelid".$counter})) {
						$conn->RollbackTrans();
						die();
					};
				};
			};
			for ($counter=1;$counter<=3;$counter++) { //save price discounts information
				if (${"discount".$counter}>0) if (!invitemlocationdiscount(1,$id,$inventorylocationid,${"discount".$counter},${"quantity".$counter})) {
					$conn->RollbackTrans();
					die();
				};
			};
			echo textsuccess($lang['STR_ITEM_LOCATION_UPDATED_SUCCESSFULLY']);
		}; //end of process
		echo '<br><br><a href="invitemupd1.php?id='.$id.'&&itemcode='.urlencode($itemcode).'&&description='.urlencode($description).'">Add/Update Another Location For Item</a>';
	} elseif ($inventorylocationid) { //    user has selected a location display & get updates
		$recordSet=&$conn->Execute('select maxstocklevelseason1,minstocklevelseason1,orderqtyseason1, maxstocklevelseason2,minstocklevelseason2,orderqtyseason2, maxstocklevelseason3,minstocklevelseason3,orderqtyseason3, maxstocklevelseason4,minstocklevelseason4,orderqtyseason4,markupsetid,cancel,onhandqty from itemlocation where inventorylocationid='.sqlprep($inventorylocationid).' and itemid='.sqlprep($id));
		if (!$recordSet->EOF) {
			$maxstocklevelseason1=$recordSet->fields[0];
			$minstocklevelseason1=$recordSet->fields[1];
			$orderqtyseason1=$recordSet->fields[2];
			$maxstocklevelseason2=$recordSet->fields[3];
			$minstocklevelseason2=$recordSet->fields[4];
			$orderqtyseason2=$recordSet->fields[5];
			$maxstocklevelseason3=$recordSet->fields[6];
			$minstocklevelseason3=$recordSet->fields[7];
			$orderqtyseason3=$recordSet->fields[8];
			$maxstocklevelseason4=$recordSet->fields[9];
			$minstocklevelseason4=$recordSet->fields[10];
			$orderqtyseason4=$recordSet->fields[11];
			$markupsetid=$recordSet->fields[12];
			$cancel=$recordSet->fields[13];
			$onhandqty=$recordSet->fields[14];
		};
		$recordSet=&$conn->Execute('select discount,quantity from pricediscount where itemid='.sqlprep($id).' and  itemlocationid='.sqlprep($inventorylocationid));
		$counter=0;
		while (!$recordSet->EOF) {
			$counter=$counter+1;
			${"discount".$counter}=$recordSet->fields[0];
			${"quantity".$counter}=$recordSet->fields[1];
			$recordSet->MoveNext();
		};
		$recordSet = &$conn->Execute('select company.companyname from inventorylocation, company where inventorylocation.companyid=company.id and inventorylocation.id='.sqlprep($inventorylocationid));
		if ($recordSet->EOF) die(texterror("could not find location name"));
		$locationname=$recordSet->fields[0];
		echo '<form action="invitemupd1.php" method="post"><input type="hidden" name="nonprintable" value="1"><table border=1>';
		echo '<input type="hidden" name="itemcode" value="'.$itemcode.'">';
		echo '<input type="hidden" name="id" value="'.$id.'">';
		echo '<input type="hidden" name="inventorylocationid" value="'.$inventorylocationid.'">';
		echo '<input type="hidden" name="categoryid" value="'.$categoryid.'">';
		echo '<input type="hidden" name="description" value="'.$description.'">';
		echo '<input type="hidden" name="composityesno" value="'.$composityesno.'">';
		echo '<input type="hidden" name="categoryname" value="'.$categoryname.'">';
		echo '<input type="hidden" name="seasonname1" value="'.$seasonname1.'">';
		echo '<input type="hidden" name="seasonname2" value="'.$seasonname2.'">';
		echo '<input type="hidden" name="seasonname3" value="'.$seasonname3.'">';
		echo '<input type="hidden" name="seasonname4" value="'.$seasonname4.'">';
		echo '<input type="hidden" name="stockunit" value="'.$stockunit.'">';
		echo '<input type="hidden" name="priceunit" value="'.$priceunit.'">';
		echo '<input type="hidden" name="onhandqty" value="'.$onhandqty.'">';
		echo '<input type="hidden" name="savedata" value="1">';
		if (!$seasonname1) $seasonname1="All";
		echo '<tr><td>Location Name:</td><td>'.$locationname.'</td></tr>';
		echo '<tr><td>Markup Set (0=none):</td><td><select name="markupsetid"'.INC_TEXTBOX.'><option value="0">0=No Automatic Pricing';
		$recordSet=&$conn->Execute('select id, description from markupset order by description');
		while (!$recordSet->EOF) {
			echo '<option value="'.$recordSet->fields[0].'"'.checkequal($markupsetid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1]."\n";
			$recordSet->MoveNext();
		};
		echo '</select></td></tr>';
		echo '<tr><th rowspan="2">Season</th><th colspan="2" align="center">Stocking Levels in '.$stockunit.'s</th></tr>';
		echo '<tr><th>Maximum</th><th>Minimum</th><th>Order Qty. in '.$stockunit.'s</th></th>';
		for ($i=1;${"seasonname".$i}&&$i<=4;$i++) {
			echo '<tr><td>'.${"seasonname".$i}.'</td>';
			echo '<td><input type="text" name="maxstocklevelseason'.$i.'" onchange="validatenum(this)" value="'.${"maxstocklevelseason".$i}.'" size="20"  maxlength="20"'.INC_TEXTBOX.'></td>';
			echo '<td><input type="text" name="minstocklevelseason'.$i.'" onchange="validatenum(this)" value="'.${"minstocklevelseason".$i}.'" size="20"  maxlength="20"'.INC_TEXTBOX.'></td>';
			echo '<td><input type="text" name="orderqtyseason'.$i.'" onchange="validatenum(this)" value="'.${"orderqtyseason".$i}.'" size="20"  maxlength="20"'.INC_TEXTBOX.'></td></tr>';
		};
		echo '<tr><td colspan="4">&nbsp;</td></tr>';
		echo '<tr><th>Price Level</th><th>Sell Price/'.$priceunit.'</th>';
		echo '<th colspan="2" rowspan="2"><i>(Note: If Using Markup Set, Ignore this section)</i></th></tr>';
		$pricecounter=0;
		$recordSet=&$conn->Execute('select description, id from pricelevel');
		while (!$recordSet->EOF) {
			$pricecounter=$pricecounter+1;
			echo '<tr><td>'.$recordSet->fields[0].'</td>';
			$recordSet1=&$conn->Execute('select price from priceperpriceunit where itemid='.sqlprep($id).' and itemlocationid='.sqlprep($inventorylocationid).' and pricelevelid='.sqlprep($recordSet->fields[1]));
			if (!$recordSet1->EOF) {
				$price=$recordSet1->fields[0];
			} else {
				$price=0;
			};
			echo '<td><input type="text" name="price'.$pricecounter.'" value="'.$price.'"'.INC_TEXTBOX.'></td></tr>';
			echo '<input type="hidden" name="pricelevelid'.$pricecounter.'" value="'.$recordSet->fields[1].'">';
			$recordSet->MoveNext();
		};
		echo '<input type="hidden" name="pricecounter" value="'.$pricecounter.'"><tr></tr>';
		echo '<tr><th>Discount Percent</th><th>On Quantities Over</th></tr>';
		echo '<tr><td><input type="text" name="discount1" onchange="validatenum(this)" value="'.$discount1.'"'.INC_TEXTBOX.'></td>';
		echo '<td><input type="text" name="quantity1" onchange="validatenum(this)" value="'.$quantity1.'"'.INC_TEXTBOX.'></td></tr>';
		echo '<tr><td><input type="text" name="discount2" onchange="validatenum(this)" value="'.$discount2.'"'.INC_TEXTBOX.'></td>';
		echo '<td><input type="text" name="quantity2" onchange="validatenum(this)" value="'.$quantity2.'"'.INC_TEXTBOX.'></td></tr>';
		echo '<tr><td><input type="text" name="discount3" onchange="validatenum(this)" value="'.$discount3.'"'.INC_TEXTBOX.'></td>';
		echo '<td><input type="text" name="quantity3" onchange="validatenum(this)" value="'.$quantity3.'"'.INC_TEXTBOX.'></td></tr>';
		echo '</table><input type="submit" value="Save/Update Location"><br><br>';
		if (!$cancel) {
			echo '<a href="javascript:confirmdelete(\'invitemupd1.php?id='.$id.'&&inventorylocationid='.$inventorylocationid.'&&delete=1&&savedata=0&&itemcode='.urlencode($itemcode).'&&description='.urlencode($description).'\')">Delete Location Information</a>';
		} else {
			echo '<a href="invitemupd1.php?id='.$id.'&&inventorylocationid='.$inventorylocationid.'&&delete=2&&savedata=0&&itemcode='.urlencode($itemcode).'&&description='.urlencode($description).'">Activate Deleted Location Information</a>';
		};
		echo '</form>';
	};
?>
<?php include('includes/footer.php'); ?>
