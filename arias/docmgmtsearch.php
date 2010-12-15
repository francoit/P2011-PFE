<?php include('includes/main.php'); ?>
<?php include("includes/docmgmt/menu.inc");?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
	
	<center>
	<table width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
	<th>
	Search
	</th>
	</tr>
	</table>

	<p>

	<table border="0" cellspacing="5" cellpadding="5">
	<form action="docmgmtout.php" method="POST">
	
	<tr>
	<td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top"><b>Search term</b></td>
	<td><input type="Text" name="keyword" size="50"></td>
	</tr>
	
	<tr>
	<td align="<?php echo TABLE_LEFT_SIDE_ALIGN; ?>" valign="top"><b>Search</b></td>
	<td><select name="where">
	<option value="1">Descriptions only</option>
	<option value="2">Filenames only</option>
	<option value="3">Comments only</option>
	<option value="4" selected>All</option>
	</select></td>
	</tr>

	<tr>
	<td colspan="2" align="center"><input type="Submit" name="submit" value="Search"></td>
	</tr>
	
	</form>
	</table>
	</center>
<?php include('includes/footer.php'); ?>
