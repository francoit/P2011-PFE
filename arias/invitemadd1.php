<?php include('includes/main.php'); ?>
<?php include('includes/invfunctions.php') ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //invitemadd1.php

        echo texttitle($lang['STR_ITEM_LOCATION_ADD_FOR'] .$itemcode." - ".$description);
        echo '<center>';
        if ($itemcode&&!$inventorylocationid) { // user has submitted info on item, but no location info
            $conn->BeginTrans();
            if (!itemAddUpdate(1,$itemcode,$description,$categoryid, $stockunitnameid, $priceunitnameid, $lbsperpriceunit, $priceunitsperstockunit, $inventoryglacctid, $composityesno, $catalogdescription, $cfilename, $gfilename,$companyid,$cancel,0,$salesglacctid,$lastchangedate)) {
                $conn->RollbackTrans();
                die ();
            };
            $recordSet=&$conn->Execute('select item.id,sunitname.unitname,punitname.unitname from item,unitname as sunitname,unitname as punitname where sunitname.id=item.stockunitnameid and punitname.id=item.priceunitnameid and item.itemcode='.sqlprep($itemcode));
            if (!$recordSet||$recordSet->EOF) {
                $conn->RollbackTrans();
                die(texterror($lang['STR_ITEM_NOT_FOUND']));
            };
            $id=$recordSet->fields[0];
            $stockunit=rtrim($recordSet->fields[1]);
            $priceunit=rtrim($recordSet->fields[2]);
            if ($graphic_name) {
               $nondisallowedfile=1;
                 foreach($disallowedfileext as $this) {
                    if (substr_count($graphic_name, $this)) {
                        $nondisallowedfile=0;
                        break;
                    };
                 };
                 // illegal file type!
                if ($nondisallowedfile != 1) die(texterror($lang['STR_THIS_FILE_TYPE_IS_NOT_SUPPORTED']));
                $gfilename=IMAGE_UPLOAD_DIR."graphic".$id.strtolower(substr($graphic_name, strrpos($graphic_name,'.')));
                if (!move_uploaded_file($graphic, $gfilename)) retrievefile($graphic_name,$gfilename);
                if ($docmgmtgraphic) {
                    $conn->Execute("INSERT INTO docmgmtdata (category, owner, realname, created, itemid, description, comment, version, final) VALUES('".INV_DOCMGMT_DEF_CATEGORY."', '$userid', 'graphic".$id.strtolower(substr($graphic_name, strrpos($graphic_name,'.')))."', NOW(), '$id', '$description', '', '1.0.0', '0')");
                    $recordSet = &$conn->SelectLimit("select id from docmgmtdata where realname='graphic".$id.strtolower(substr($graphic_name, strrpos($graphic_name,'.')))."' order by created desc",1);
                    if (!$recordSet->EOF) $fileId = $recordSet->fields[0];
                    $conn->Execute("INSERT INTO docmgmtperms (fid, uid, rights) VALUES('$fileId', '$userid', '1')");
                    $conn->Execute("INSERT INTO docmgmtperms (fid, uid, rights) VALUES('$fileId', '$userid', '2')");
                    $newFileName = $fileId . ".dat";
                    copy($gfilename, IMAGE_UPLOAD_DIR.$newFileName);
                };
            };
            if ($catalogsheet_name) {
                $cfilename=IMAGE_UPLOAD_DIR."catalog".$id.strtolower(substr($catalogsheet_name, strrpos($catalogsheet_name,'.')));
                if (!move_uploaded_file($catalogsheet, $cfilename)) retrievefile($catalogsheet_name,$cfilename);
                $nondisallowedfile=1;
                 foreach($disallowedfileext as $this) {
                    if (substr_count($catalogsheet_name, $this)) {
                        $nondisallowedfile=0;
                        break;
                    };
                 };

                 // illegal file type!
                if ($nondisallowedfile != 1) die(texterror($lang['STR_THIS_FILE_TYPE_IS_NOT_SUPPORTED']));
                if ($docmgmtdoc) {
                    $conn->Execute("INSERT INTO docmgmtdata (category, owner, realname, created, itemid, description, comment, version, final) VALUES('".INV_DOCMGMT_DEF_CATEGORY."', '$userid', 'catalog".$id.strtolower(substr($catalogsheet_name, strrpos($catalogsheet_name,'.')))."', NOW(), '$id', '$description', '', '1.0.0', '0')");
                    $recordSet = &$conn->SelectLimit("select id from docmgmtdata where realname='catalog".$id.strtolower(substr($catalogsheet_name, strrpos($catalogsheet_name,'.')))."' order by created desc",1);
                    if (!$recordSet->EOF) $fileId = $recordSet->fields[0];
                    $conn->Execute("INSERT INTO docmgmtperms (fid, uid, rights) VALUES('$fileId', '$userid', '1')");
                    $conn->Execute("INSERT INTO docmgmtperms (fid, uid, rights) VALUES('$fileId', '$userid', '2')");
                    $newFileName = $fileId . ".dat";
                    copy($cfilename, IMAGE_UPLOAD_DIR.$newFileName);
                };
           };
           if ($gfilename || $cfilename) {
                if ($conn->Execute('update item set catalogsheeturl='.sqlprep($cfilename).', graphicurl='.sqlprep($gfilename).' where id='.sqlprep($id)) === false) {
                    $conn->RollbackTrans();
                    die(texterror($lang['STR_ERROR_UPDATING_ITEM']));
                };
           };
           echo '<center><big><font color=#00CC00>'.$lang['STR_ITEM_ADDED_SUCCESSFULLY'].'</font></big></center><br>';
           $conn->CommitTrans();
        } elseif ($inventorylocationid) {//    user has submitted location info
           $conn->BeginTrans();
           if (!invitemlocationaddupdate(0,$itemid,$inventorylocationid,$onhandqty,$maxstocklevelseason1,$minstocklevelseason1,$orderqtyseason1,$maxstocklevelseason2,$minstocklevelseason2,$orderqtyseason2,$maxstocklevelseason3,$minstocklevelseason3,$orderqtyseason3,$maxstocklevelseason4,$minstocklevelseason4,$orderqtyseason4,$markupsetid,$id)) {
                $conn->RollbackTrans();
                die();
           };
           if (!$markupsetid) { // added new record successfully
                for ($counter=1;$counter<=$pricecounter;$counter++) {
                     if (!invitemlocationprice(0,$itemid,$inventorylocationid,$id,${"price".$counter},${"pricelevelid".$counter})) {
                          $conn->RollbackTrans();
                          die();
                     };
                };
           };
           for ($counter=1;$counter<4;$counter++) { //save price discounts information
                if (${"discount".$counter}>0) if (!invitemlocationdiscount(0,$id,$inventorylocationid,${"discount".$counter},${"quantity".$counter})) {
                     $conn->RollbackTrans();
                     die();
                };
           };
           echo '<center><big><font color=#00CC00>'.$lang['STR_ADDED_INVENTORY_ITEM_LOCATION_SUCCESSFULLY'].'</font></big></center><br>';
           $conn->CommitTrans();
           unset($inventorylocationid);
        };

        if (!$categoryname) {
                $recordSet=&$conn->Execute("select name, seasonname1, seasonname2, seasonname3, seasonname4 from itemcategory where id=".$categoryid);
                if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_CATEGORY_NOT_FOUND']));
                $categoryname=rtrim($recordSet->fields[0]);
                $seasonname1=rtrim($recordSet->fields[1]);
                $seasonname2=rtrim($recordSet->fields[2]);
                $seasonname3=rtrim($recordSet->fields[3]);
                $seasonname4=rtrim($recordSet->fields[4]);
        };

        // ask location information
        echo '<form action="invitemadd1.php" method="post"><input type="hidden" name="nonprintable" value="1"><table border=0>';
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

        if (!$seasonname1) $seasonname1="All";
        $recordSet = &$conn->Execute('select count(*) from inventorylocation, company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company));
        if (!$recordSet->EOF) if ($recordSet->fields[0]>1) {
                echo '<tr><td>'.$lang['STR_LOCATION_NAME'].':</td><td><select name="inventorylocationid"'.INC_TEXTBOX.'>';
                $recordSet = &$conn->Execute('select inventorylocation.id, company.companyname from inventorylocation, company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
                echo '<option value="0">'."\n";
                while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],$locationid," selected").'>'.rtrim($recordSet->fields[1])."\n";
                        $recordSet->MoveNext();
                };
                echo '</select></td></tr>';
        } else {
                $recordSet = &$conn->Execute('select inventorylocation.id from inventorylocation, company where inventorylocation.companyid=company.id and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
                echo '<input type="hidden" name="inventorylocationid" value="'.$recordSet->fields[0].'">';
        };
        echo '<tr><td>'.$lang['STR_MARKUP_SET'].' (0=none):</td><td><select name="markupsetid"'.INC_TEXTBOX.'><option value="0">0=No Automatic Pricing';
        $recordSet=&$conn->Execute('select id, description from markupset order by description');
        while (!$recordSet->EOF) {
                echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                $recordSet->MoveNext();
        };
        echo '</select></td></tr>';
        echo '<tr><th rowspan="2">'.$lang['STR_SEASON'].'</th><th colspan="2" align="center">'.$lang['STR_STOCKING_LEVELS_IN'].' '.$stockunit.'s</th></tr>';
        echo '<tr><th>'.$lang['STR_MAXIMUM'].'</th><th>'.$lang['STR_MINIMUM'].'</th><th>'.$lang['STR_ORDER_QUANTITY_IN'].' '.$stockunit.'s</th></th>';
        for ($i=1;${"seasonname".$i};$i++) {
                echo '<tr><td>'.${"seasonname".$i}.'</td>';
                echo '<td><input type="text" name="maxstocklevelseason'.$i.'" onchange="validatenum(this)" size="20"  maxlength="20"'.INC_TEXTBOX.'></td>';
                echo '<td><input type="text" name="minstocklevelseason'.$i.'" onchange="validatenum(this)" size="20"  maxlength="20"'.INC_TEXTBOX.'></td>';
                echo '<td><input type="text" name="orderqtyseason'.$i.'" onchange="validatenum(this)" size="20"  maxlength="20"'.INC_TEXTBOX.'></td></tr>';
        };
        echo '<tr><td colspan="4">&nbsp;</td></tr>';
        echo '<tr><th>'.$lang['STR_PRICE_LEVEL'].'</th><th>'.$lang['STR_SELL_PRICE_FORWARD_SLASH'].' '.$priceunit.'</th>';
        echo '<th colspan="2" rowspan="2"><i>'.$lang['STR_NOTE_IF_USING_MARKUP_SET_IGNORE_THIS_SECTION'].'</i></th></tr>';

        $recordSet = &$conn->Execute('select description, id from pricelevel');
    $pricecounter = 0;
    while (!$recordSet->EOF) {
      $pricecounter=$pricecounter+1;
      echo '<tr>
	          <td>'.
			    rtrim($recordSet->fields[0]).
			 '</td>';
      echo '  <td>
	            <input type="text" name="price'.$pricecounter.
		        '" onchange="validatenum(this)" onKeyPress="return handleEnter(this, event)" onFocus="highlightField(this)" onBlur="normalField(this)"
			  </td>';
      echo '  <td>
	            <input type="hidden" name="pricelevelid'.$pricecounter.'" value='.$recordSet->fields[1].'>
		      </td>
			</tr>';
      $recordSet->MoveNext();
    };

        echo '<input type="hidden" name="pricecounter" value="'.$pricecounter.'"><tr></tr>';
        echo '<tr><th>'.$lang['STR_DISCOUNT_PERCENT'].'</th><th>'.$lang['STR_ON_QUANTITIES_OVER'].'</th></tr>';
        echo '<tr><td><input type="text" name="discount1" onchange="validatenum(this)"'.INC_TEXTBOX.'></td>';
        echo '<td><input type="text" name="quantity1" onchange="validatenum(this)"'.INC_TEXTBOX.'</td></tr>';
        echo '<tr><td><input type="text" name="discount2" onchange="validatenum(this)"'.INC_TEXTBOX.'</td>';
        echo '<td><input type="text" name="quantity2" onchange="validatenum(this)"'.INC_TEXTBOX.'</td></tr>';
        echo '<tr><td><input type="text" name="discount3" onchange="validatenum(this)"'.INC_TEXTBOX.'</td>';
        echo '<td><input type="text" name="quantity3" onchange="validatenum(this)"'.INC_TEXTBOX.'</td></tr>';
        echo '</table><input type="submit" name="submit" value="Save Location"></form>';

        if ($composityesno&&$submit=="Save Location") echo '<a href="invitemadd2.php?id='.$id.'">'.$lang['STR_ENTER_COMPOSITE_ITEMS_NOW'].'</a>';

        echo '</center>';
        
?>

<?php include('includes/footer.php'); ?>
