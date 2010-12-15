<?php require_once('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript" src="js/lookup.js">
</script>
<?php  echo '<center>';
	if ($category||$desc||$inventorylocationid||!MANY_ITEMS) {
		if ($category) $catstr=' and item.categoryid='.sqlprep($category);
		if ($desc) $descstr=' and item.description like '.sqlprep('%'.$desc.'%');
		if ($inventorylocationid) {
		    $locationidstr0=' , itemlocation';
		    $locationidstr1=' and itemlocation.itemid=item.id';
		    $locationidstr2=' and itemlocation.inventorylocationid ='.sqlprep($inventorylocationid);
		}
		$recordSet = &$conn->Execute('select item.id, item.itemcode, item.description, itemcategory.name from item, itemcategory'.$locationidstr0.' where item.categoryid=itemcategory.id'.$locationidstr1.' and item.companyid='.sqlprep($active_company).$catstr.$descstr.$locationidstr2.'order by itemcategory.name, item.description' );
		if ($recordSet->EOF) die(texterror($lang['STR_NO_ITEMS_FOUND']));
		echo texttitle($lang['STR_SELECT_ITEM']);
		echo '<form name="mainform"><select name="'.$name.'"'.INC_TEXTBOX.'>';
		while (!$recordSet->EOF) {
			echo '<option value="'.rtrim($recordSet->fields[1]).'">'.rtrim($recordSet->fields[3]).' - '.rtrim($recordSet->fields[2]).' - '.rtrim($recordSet->fields[1])."\n";
			$recordSet->MoveNext();
		};
		echo '</select><input type="button" onClick="setField('.sqlprep($name).')" value="'.$lang['STR_SELECT'].'">';
	} else {
		$recordSet = &$conn->Execute('select distinct itemcategory.id, itemcategory.name from item, itemcategory where item.companyid='.sqlprep($active_company).' and item.categoryid=itemcategory.id order by itemcategory.name');
		if ($recordSet->EOF) die(texterror($lang['STR_NO_ITEMS_FOUND']));
		echo texttitle($lang['STR_ITEM_SEARCH']);
		echo '<form name="mainform" action="lookupitem.php" method="post"><input type="hidden" name="name" value="'.$name.'">';
		echo '<font size="-1">'.$lang['STR_DESCRIPTION'].':</font><br><input type="text" name="desc" size="20"'.INC_TEXTBOX.'><br><br>';
		echo '<font size="-1">'.$lang['STR_CATEGORY'].'</font><br><select name="category"><option value="0" selected'.INC_TEXTBOX.'>';
		while (!$recordSet->EOF) {
			echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
			$recordSet->MoveNext();
		};
		echo '</select><br><br><input type="submit" value="'.$lang['STR_SELECT'].'"></form>';
	};
	echo '</center>';
?>

<?php require_once('includes/footer.php');?>
