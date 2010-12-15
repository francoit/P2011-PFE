<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //invlevellst.php
	echo texttitle($lang['STR_PRICE_LEVEL_LIST']);
	if ($order) {
		$orderstr=" order by percent";
	} else {
		$orderstr=" order by description";
	};
	$recordSet = &$conn->Execute('select pricelevel.description, avg(markupsetlevel.markuppercent) as percent from pricelevel left join markupsetlevel on pricelevel.id=markupsetlevel.pricelevelid group by pricelevel.description,markupsetlevel.pricelevelid'.$orderstr);
	if ($recordSet->EOF) die(texterror($lang['STR_NO_PRICE_LEVELS_FOUND']));
	echo '<table align="center" border=1><tr><th><a class="blacklink" href="invlevellst.php">'.$lang['STR_PRICE_LEVEL_DESCRIPTION'].'</a></th><th><a class="blacklink" href="invlevellst.php?order=1">'.$lang['STR_AVERAGE_MARKUP_PERCENTAGE'].'</a></th></tr>';
	while (!$recordSet->EOF) {
		echo '<tr><td><b>'.$recordSet->fields[0].'</b></td><td>'.num_format($recordSet->fields[1], 3).'</td></tr>';
		$recordSet->MoveNext();
	};
	echo '</table>';
?>
<?php include('includes/footer.php'); ?>
