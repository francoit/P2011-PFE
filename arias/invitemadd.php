<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
     function checkvalue() {
          if (document.mainform.priceunitsperstockunit.value==0) {
               document.mainform.priceunitsperstockunit.value=1;
          }
     }
</script>
<?php //invitemadd.php
       echo texttitle($lang['STR_ITEM_ADD']);
       echo '<center>';
       echo '<form action="invitemadd1.php" method="post" name="mainform" ENCTYPE="multipart/form-data"><input type="hidden" name="nonprintable" value="1"><table><input type="hidden" name="MAX_FILE_SIZE" value="'.IMAGE_UPLOAD_SIZE_MAX.'">';
       if ($locationid) echo '<input type="hidden" name="locationid" value="'.$locationid.'">';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ITEM_CODE'].':</td><td><input type="text" name="itemcode" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ITEM_DESCRIPTION'].':</td><td><input type="text" name="description" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CATEGORY'].':</td><td><select name="categoryid"'.INC_TEXTBOX.'>';
       $recordSet = &$conn->Execute('select itemcategory.id, itemcategory.name from itemcategory where cancel=0 order by itemcategory.name');
       while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
          $recordSet->MoveNext();
       };
       echo '</select><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'invitemcatadd.php\',\'cal\',\'dependent=yes,width=800,height=400,screenX=300,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="images/addbutton.png" border="0" alt="Category Add"></a></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STOCK_UNIT'].':</td><td><select name="stockunitnameid"'.INC_TEXTBOX.'>';
       $recordSet = &$conn->Execute('select unitname.id, unitname.unitname from unitname order by unitname.unitname');
       while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
          $recordSet->MoveNext();
       };
       echo '</select></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PRICE_UNIT'].':</td><td><select name="priceunitnameid"'.INC_TEXTBOX.'>';
       $recordSet = &$conn->Execute('select unitname.id, unitname.unitname from unitname order by unitname.unitname');
       while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
       };
       echo '</select></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WEIGHT_PRICE_UNIT'].':</td><td><input type="text" name="lbsperpriceunit" onchange="validatenum(this)" size="30" maxlength="10"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PRICE_UNIT_STOCK_UNIT'].':</td><td><input type="text" name="priceunitsperstockunit" onchange="validatenum(this)" size="30" maxlength="10" value="1"'.INC_TEXTBOX.'></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_INVENTORY_ACCOUNT'].':</td><td><select name="inventoryglacctid"'.INC_TEXTBOX.'>';
       $recordSet = &$conn->Execute('select glaccount.id, glaccount.name, glaccount.description from glaccount where (companyid=0 or companyid='.sqlprep($companyid).') and accounttypeid>=10 and accounttypeid <20 order by glaccount.name');
       while (!$recordSet->EOF) {
          echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2])."\n";
          $recordSet->MoveNext();
       };
       echo '</select></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_SALES_ACCOUNT'].':</td><td><select name="salesglacctid"'.INC_TEXTBOX.'>';
       $recordSet = &$conn->Execute('select glaccount.id, glaccount.name, glaccount.description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') and accounttypeid>=50 and accounttypeid <60 order by glaccount.name');
       while (!$recordSet->EOF) {
             echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2])."\n";
             $recordSet->MoveNext();
       };
       echo '</select></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'" rowspan="2">'.$lang['STR_COMPOSITE_ITEM_SUCH_AS_KIT_OR_CATLOG'].'</td>';
       echo '<td><input type="radio" name="composityesno" checked value="0"'.INC_TEXTBOX.'>'.$lang['STR_NO'].'</td></tr><tr></tr>';
       echo '<tr><td></td><td><input type="radio" name="composityesno" value="1"'.INC_TEXTBOX.'>'.$lang['STR_YES'].'</td></tr>';

       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DESCRIPTION_FOR_PRODUCT_CATALOG'].':</td><td><textarea name="catalogdescription" rows="10" cols="50"'.INC_TEXTBOX.'></textarea></td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_URL_FILE_FOR_PRODUCT_SHEET'].':</td><td><input type="file" name="catalogsheet"'.INC_TEXTBOX.'>';
       if (SOFTWARE_SHOW_DOCMGMT)  echo ' '.$lang['STR_ADD_TO_DOCUMENT_MANAGER'].':&nbsp;<input type="checkbox" name="docmgmtdoc" value="1"'.INC_TEXTBOX.'>';
       echo '</td></tr>';
       echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_URL_FILE_FOR_PRODUCT_GRAPHIC'].':</td><td><input type="file" name="graphic"'.INC_TEXTBOX.'>';
       if (SOFTWARE_SHOW_DOCMGMT)  echo ' '.$lang['STR_ADD_TO_DOCUMENT_MANAGER'].':&nbsp;<input type="checkbox" name="docmgmtgraphic" value="1"'.INC_TEXTBOX.'>';
       echo '</td></tr></table>';
       echo '<br><input type="submit" value="'.$lang['STR_ADD_NEW_ITEM'].'"></form>';
       echo '</center>';
?>

<?php include('includes/footer.php'); ?>
