<?php include('includes/main.php'); ?>
<?php include('includes/apfunctions.php'); ?>
<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
	function checkChoice(i) {
		if (document.mainform.ap.checked == false) {
			if (document.mainform.pay.checked == false) {
				if (i=="1") {
					document.mainform.pay.checked = true;
				} else {
					document.mainform.ap.checked = true;
				}
			}
		}
	}
</script>
<?php
        echo '<center>';
	echo texttitle($lang['STR_CHECKING_ACCOUNT_ADD']);
	if ($name) apchkacctadd($name,$glaccountid,$lastchecknumberused,$defaultendorser,$ap,$pay);
	echo '<form action="adminapchkacctadd.php" method="post" name="mainform"><input type="hidden" name="nonprintable" value="1"><table>';
	formapchkacctadd();
	echo '</table><br><input type="submit" value="'.$lang['STR_ADD'].'"></form>';

        echo '</center>';
?>

<?php include('includes/footer.php'); ?>
