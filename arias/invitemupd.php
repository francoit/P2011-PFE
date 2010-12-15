<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php'); ?>
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
<?

     echo texttitle($lang['STR_ITEM_UPDATE']);
     echo '<center>';
     if ($itemcode&&!$id) { //if user entered an itemcode to update, get the id and use it for reference
         $recordSet = &$conn->Execute('select id from item where itemcode='.sqlprep($itemcode).' and companyid='.sqlprep($active_company));
         if ($recordSet&&!$recordSet->EOF) {
             $id=$recordSet->fields[0];
             $itemcode=''; //because we check for itemcode later on, for updates
         } else {
             echo texterror($lang['STR_ITEM_NOT_FOUND']);
         };
     };
     if ($id) { // if the user has submitted info
          if (isset($delete)) {
               itemDelete($itemcode,$delete);
          } elseif ($itemcode) { //update the item entry
               if ($graphic_name) { //grab the graphic file
                    if ($dir = @opendir(IMAGE_UPLOAD_DIR)) {
                         while($file = readdir($dir)) {
                              if (substr_count($file, "graphic".$id.".")==1) unlink(IMAGE_UPLOAD_DIR.$file);
                         };
                         closedir($dir);
                    };
                    $gfilename=IMAGE_UPLOAD_DIR."graphic".$id.strtolower(substr($graphic_name, strrpos($graphic_name,'.')));
                    if (!move_uploaded_file($graphic, $gfilename)) retrievefile($graphic_name,$gfilename);
               } else {
                    $recordSet = &$conn->Execute('select graphicurl from item where id='.sqlprep($id).' and companyid='.sqlprep($active_company));
                    if (!$recordSet->EOF) $gfilename=rtrim($recordSet->fields[0]);
               };
               if ($catalogsheet_name) { //grab the catalog file
                    if ($dir = @opendir(IMAGE_UPLOAD_DIR)) {
                         while($file = readdir($dir)) {
                              if (substr_count($file, "catalog".$id.".")==1) unlink(IMAGE_UPLOAD_DIR.$file);
                         };
                         closedir($dir);
                    };
                    $cfilename=IMAGE_UPLOAD_DIR."catalog".$id.strtolower(substr($catalogsheet_name, strrpos($catalogsheet_name,'.')));
                    if (!move_uploaded_file($catalogsheet, $cfilename)) retrievefile($catalogsheet_name,$cfilename);
               } else {
                    $recordSet = &$conn->Execute('select catalogsheeturl from item where id='.sqlprep($id).' and companyid='.sqlprep($active_company));
                    if (!$recordSet->EOF) $cfilename=rtrim($recordSet->fields[0]);
               };
                     if (!itemAddUpdate(0,$itemcode,$description,$categoryid, $stockunitnameid, $priceunitnameid, $lbsperpriceunit, $priceunitsperstockunit, $inventoryglacctid, $composityesno, $catalogdescription, $cfilename, $gfilename, $companyid, $cancel, $id,$salesglacctid, $lastchangedate)) {
                          echo '<br><br><a href="invitemupd.php">'.$lang['STR_UPDATE_ANOTHER_ITEM'].'</a><br><br>';
                     } else {
                          echo textsuccess($lang['STR_ITEM_UPDATED_SUCCESSFULLY']);
                          echo '<br><br><a href="invitemupd.php">'.$lang['STR_UPDATE_ANOTHER_ITEM'].'</a>';
                          echo '<br><br><a href="invitemupd1.php?id='.$id.'&&itemcode='.urlencode($itemcode).'&&description='.urlencode($description).'">Add/Update Location For Item</a>';
                          if ($composityesno) echo '<br><br><a href="invitemadd2.php?id='.$id.'&&itemcode='.urlencode($itemcode).'&&description='.urlencode($description).'">Add/Update Component List for Item</a>';
                          echo '<br><br>';
                     };
               if (IMAGE_SHOW&&$gfilename) echo '<img src="'.$gfilename.'">,'.$gfilename.',';
          } else { // display more info about the entry that the user can edit
               echo '<form action="invitemupd.php" method="post" name="mainform" ENCTYPE="multipart/form-data"><table><input type="hidden" name="id" value="'.$id.'"><INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="'.IMAGE_UPLOAD_SIZE_MAX.'">';
               $recordSet = &$conn->Execute('select itemcode, compositeitemyesno, description,categoryid, stockunitnameid, priceunitnameid, lbsperpriceunit, priceunitsperstockunit, inventoryglacctid, catalogdescription, catalogsheeturl, graphicurl,cancel,lastchangedate,salesglacctid  from item where id='.sqlprep($id).' and companyid='.sqlprep($active_company));
               if (!$recordSet->EOF) {
                     $itemcode=rtrim($recordSet->fields[0]);
                     $composityesno=$recordSet->fields[1];
                     $description=rtrim($recordSet->fields[2]);
                     $categoryid=$recordSet->fields[3];
                     $stockunitnameid=$recordSet->fields[4];
                     $priceunitnameid=$recordSet->fields[5];
                     $lbsperpriceunit=$recordSet->fields[6];
                     $priceunitsperstockunit=$recordSet->fields[7];
                     $inventoryglacctid=$recordSet->fields[8];
                     $catalogdescription=$recordSet->fields[9];
                     $catalogsheeturl=rtrim($recordSet->fields[10]);
                     $graphicurl=rtrim($recordSet->fields[11]);
                     $cancel=$recordSet->fields[12];
                     $lastchangedate=$recordSet->fields[13] ;
                     $salesglacctid=$recordSet->fields[14] ;
                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ITEM_CODE'].': </td><td><input type="text" name="itemcode" value="'.$itemcode.'" size="30" maxlength="20"'.INC_TEXTBOX.'></td>';;
                     if ($cancel) echo '<th>ITEM DELETED</th></tr>';
                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DESCRIPTION'].':</td><td><input type="text" name="description" value="'.$description.'" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CATEGORY'].':</td><td><select name="categoryid"'.INC_TEXTBOX.'>';
                     $recordSet = &$conn->Execute('select itemcategory.id, itemcategory.name from itemcategory order by itemcategory.name');
                     while (!$recordSet->EOF) {
                              echo '<option value="'.$recordSet->fields[0].'"'.checkequal($categoryid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1]."\n";
                              $recordSet->MoveNext();
                     };
                     echo '</select></td></tr>';
                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STOCK_UNIT'].':</td><td><select name="stockunitnameid"'.INC_TEXTBOX.'>';
                     $recordSet = &$conn->Execute('select unitname.id, unitname.unitname from unitname order by unitname.unitname');
                     while (!$recordSet->EOF) {
                              echo '<option value="'.$recordSet->fields[0].'"'.checkequal($stockunitnameid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1]."\n";
                              $recordSet->MoveNext();
                     };
                     echo '</select></td></tr>';
                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PRICE_UNIT'].':</td><td><select name="priceunitnameid"'.INC_TEXTBOX.'>';
                     $recordSet = &$conn->Execute('select unitname.id, unitname.unitname from unitname order by unitname.unitname');
                     while (!$recordSet->EOF) {
                              echo '<option value="'.$recordSet->fields[0].'"'.checkequal($priceunitnameid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1]."\n";
                              $recordSet->MoveNext();
                     };
                     echo '</select></td></tr>';
                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WEIGHT_PRICE_UNIT'].':</td><td><input type="text" name="lbsperpriceunit" onchange="validatenum(this)" value="'.$lbsperpriceunit.'" size="30" maxlength="10"'.INC_TEXTBOX.'></td></tr>';
                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PRICE_UNIT_STOCK_UNIT'].':</td><td><input type="text" name="priceunitsperstockunit" onchange="validatenum(this)" value="'.$priceunitsperstockunit.'" size="30" maxlength="10" value="1"'.INC_TEXTBOX.'></td></tr>';
                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_INVENTORY_ACCOUNT'].':</td><td><select name="inventoryglacctid"'.INC_TEXTBOX.'>';
                     $recordSet = &$conn->Execute('select glaccount.id, glaccount.name, glaccount.description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') and accounttypeid>=10 and accounttypeid <20 order by glaccount.name');
                     while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'"'.checkequal($inventoryglacctid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
                        $recordSet->MoveNext();
                     };
                     echo '</select></td></tr>';
                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_SALES_ACCOUNT'].':</td><td><select name="salesglacctid"'.INC_TEXTBOX.'>';
                     $recordSet = &$conn->Execute('select glaccount.id, glaccount.name, glaccount.description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') and accounttypeid>=50 and accounttypeid <60 order by glaccount.name');
                     while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'"'.checkequal($salesglacctid,$recordSet->fields[0]," selected").'>'.$recordSet->fields[1].' - '.$recordSet->fields[2]."\n";
                        $recordSet->MoveNext();
                     };
                     echo '</select></td></tr>';
                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'" rowspan="2">'.$lang['STR_COMPOSITE_ITEM_SUCH_AS_KIT_OR_CATLOG'].'</td>';
                     if ($composityesno) {
                          echo '<td><input type="radio" name="composityesno" value="0"'.INC_TEXTBOX.'>'.$lang['STR_NO'].'</td></tr><tr></tr>';
                          echo '<tr><td></td><td><input type="radio" checked name="composityesno" value="1"'.INC_TEXTBOX.'>'.$lang['STR_YES'].'</td></tr>';
                     } else {
                          echo '<td><input type="radio" checked name="composityesno" value="0"'.INC_TEXTBOX.'>'.$lang['STR_NO'].'</td></tr><tr></tr>';
                          echo '<tr><td></td><td><input type="radio" name="composityesno" value="1"'.INC_TEXTBOX.'>'.$lang['STR_YES'].'</td></tr>';
                     };

                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DESCRIPTION_FOR_PRODUCT_CATALOG'].':</td><td><textarea name="catalogdescription" wrap="virtual" rows="5" cols="50"'.INC_TEXTBOX.'>'.$catalogdescription.'</textarea></td></tr>';
                     if ($catalogsheeturl) $catalogsheeturlstr=' <font size="-1">(<a href="javascript:doNothing()" onclick="top.newWin = window.open(\''.$catalogsheeturl.'\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')">'.$catalogsheeturl.'</a>)</font>';
                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_URL_FILE_FOR_PRODUCT_SHEET'].':</td><td><input type="file" name="catalogsheet"'.INC_TEXTBOX.'>'.$catalogsheeturlstr.'</td></tr>';
                     if ($graphicurl) $graphicurlstr=' <font size="-1">(<a href="javascript:doNothing()" onclick="top.newWin = window.open(\''.$graphicurl.'\',\'cal\',\'dependent=yes,width=210,height=230,screenX=200,screenY=300,titlebar=yes\')">'.$graphicurl.'</a>)</font>';
                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_URL_FILE_FOR_PRODUCT_GRAPHIC'].':</td><td><input type="file" name="graphic"'.INC_TEXTBOX.'>'.$graphicurlstr.'</td></tr>';
               };
               echo '<input type="hidden" name="lastchangedate" value="'.$lastchangedate.'">';
               echo '</select></td></tr></table><br><center><input type="submit" value="'.$lang['STR_UPDATE'].'"></center></form>';
               if ($cancel) {
                    echo '<a href="invitemupd.php?delete=0&id='.$id.'&itemcode='.urlencode($itemcode).'">'.$lang['STR_ACTIVATE_THIS_ITEM'].'</a>';
               } else {
                    echo '<br><center><a href="javascript:confirmdelete(\'invitemupd.php?delete=1&id='.$id.'&itemcode='.urlencode($itemcode).'\')">'.$lang['STR_DELETE_THIS_ITEM'].'</a></center>';
               };
          };
     } else { //display items, let the user pick one to edit
          echo '<form action="invitemupd.php" method="post" name="mainform"><table><tr><td>'.$lang['STR_ITEM_CODE'].': </td><td><input type="text" name="itemcode" size="30" maxlength="20"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupitem.php?name=itemcode\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Item Lookup"></a></td></tr></table><br><input type="submit" value="'.$lang['STR_SELECT'].'"></form>';
          echo '<br><a href="invitemadd.php">'.$lang['STR_ADD_NEW_ITEM'].'</a>';
     };
      
      echo '</center>';
?>

<?php include('includes/footer.php'); ?>
