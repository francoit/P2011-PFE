<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //invmarkuplst
	echo texttitle($lang['STR_STANDARD_MARKUP_SET_LIST']);
	$recordSet = &$conn->Execute("select markupset.description, pricelevel.description, markupsetlevel.markuppercent from pricelevel, markupset left join markupsetlevel on markupset.id=markupsetlevel.markupsetid where markupsetlevel.pricelevelid=pricelevel.id order by markupset.id, pricelevel.id");
	if (!$recordSet->EOF) {
		echo '<table border=0><tr><th width="10%">'.$lang['STR_MARKUP_SET_DESCRIPTION'].'</th><th>'.$lang['STR_PRICING_LEVEL'].'</th><th>'.$lang['STR_MARKUP_PERCENTAGE'].'</th></tr>';
	} else {
		die(texterror($lang['STR_NO_VALID_MARKUP_CODES_FOUND']));
	};
	while (!$recordSet->EOF) {
		echo '<tr><td width="10%" align="center">'.$recordSet->fields[0].'</td><td align="left">'.$recordSet->fields[1].'</td><td align="left">'.$recordSet->fields[2]."</td></tr>";
		$recordSet->MoveNext();
	};
        echo '</table>';
?>
<?php include('includes/footer.php'); ?>
