<?php include('includes/main.php'); ?>
<?php include('includes/arfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>


<?php        echo '<center>';
          echo texttitle($lang['STR_INVOICE_STATEMETS']);
          echo '<form action="arinvoicestateview.php" method="post" name="mainform"><table>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHOW_INVOICES'].':</td><td><select name="inv"'.INC_TEXTBOX.'><option value="0">'.$lang['STR_ALL'].'<option value="1">'.$lang['STR_ALL_OVERDUE'].'</select></td></tr>';
          formarcustomerselect('customerid');
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DO_NOT_SHOW_INVOICES_ENTERED_IN_LAST'].' :</td><td><input type="text" name="invdays" onchange="validatenum(this)" value="0" size="30"'.INC_TEXTBOX.'>'.$lang['STR_DAYS'].'</td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'" colspan="2">'.$lang['STR_IF_ANY'].'</td></tr>';
          echo '</table><br><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
          
          echo '</center>';
?>

<?php include_once("includes/footer.php"); ?>
