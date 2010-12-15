<?php include('includes/main.php'); ?>
<?php require("includes/barcode/barcode.php");
   require("includes/barcode/c128aobject.php");
   require("includes/barcode/c128bobject.php");
   require("includes/barcode/i25object.php");
   require("includes/barcode/c39object.php"); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //invpoview.php
        if ($invpoid) {
                $recordSet = &$conn->Execute('select vcompany.companyname,vcompany.address1,vcompany.address2,vcompany.city,vcompany.state,vcompany.zip,vcompany.phone1,vcompany.phone1comment,vcompany.phone2,vcompany.phone2comment,vcompany.email1,vcompany.email1comment,vcompany.website,invpo.ponumber,invpo.ordernumber,invpo.duedate from company as vcompany,vendor,invpo where vcompany.id=vendor.orderfromcompanyid and vendor.id=invpo.vendorid and invpo.id='.sqlprep($invpoid));
                if (!$recordSet->EOF) {
                        $ponumber=$recordSet->fields[13];
                        echo '<table width="100%"><tr><tr><td align="left">';
                        $recordSet2 = &$conn->Execute("select gencompany.name,gencompany.address1,gencompany.address2,gencompany.city,gencompany.state,gencompany.zip,gencompany.country,gencompany.phone1,gencompany.web,gencompany.email from gencompany where gencompany.id=".sqlprep($active_company));
                        if (!$recordSet2->EOF) {
                                echo '<font size="+2">'.$recordSet2->fields[0].'</font></td><td valign="top" align="left">'.$recordSet2->fields[1]."<br>";
                                if ($recordSet2->fields[2]) echo $recordSet2->fields[2]."<br>";
                                echo $recordSet2->fields[3].", ".$recordSet2->fields[4]." ".$recordSet2->fields[5]."<br>".$recordSet2->fields[6].'<br><font size="-2">'.$recordSet2->fields[7]."<br>".$recordSet2->fields[8]."<br>".$recordSet2->fields[9]."</font>";
                        };
                        echo '</td></tr></table><table width="100%"><tr><td>'.texttitle('Purchase Order #'.$ponumber).'</td></tr></table>';
                        echo '<table width="100%"><tr><td align="left" valign="top">';
                        echo '     <table border="1"><tr><th>Vendor</th></tr>';
                        echo '     <tr><td>'.$recordSet->fields[0].'</td></tr>';
                        if ($recordSet->fields[1]) echo '     <tr><td>'.$recordSet->fields[1].'</td></tr>';
                        if ($recordSet->fields[2]) echo '     <tr><td>'.$recordSet->fields[2].'</td></tr>';
                        if ($recordSet->fields[3]||$recordSet->fields[4]||$recordSet->fields[5]) echo '     <tr><td>'.$recordSet->fields[3].', '.$recordSet->fields[4].' '.$recordSet->fields[5].'</td></tr>';
                        if ($recordSet->fields[6]) echo '     <tr><td>'.$recordSet->fields[6].'</td></tr>';
                        if ($recordSet->fields[7]) echo '     <tr><td>'.$recordSet->fields[7].'</td></tr>';
                        if ($recordSet->fields[8]) echo '     <tr><td>'.$recordSet->fields[8].'</td></tr>';
                        if ($recordSet->fields[9]) echo '     <tr><td>'.$recordSet->fields[9].'</td></tr>';
                        if ($recordSet->fields[10]) echo '     <tr><td>'.$recordSet->fields[10].'</td></tr>';
                        if ($recordSet->fields[11]) echo '     <tr><td>'.$recordSet->fields[11].'</td></tr>';
                        if ($recordSet->fields[12]) echo '     <tr><td>'.$recordSet->fields[12].'</td></tr>';
                        echo '     </table>';
                        echo '</td><td align="right" valign="top">';
                        echo '     <table border="1"><tr><th>PO #</th><th>Order #</th></tr>';
                        echo '     <tr><td>'.rtrim($ponumber).'</td><td>'.rtrim($recordSet->fields[14]).'</td></tr>';
                        if (extension_loaded("gd")) echo '<tr><td colspan="2" align="center">'.barcodedisplay(BARCODE_IMAGE_TYPE, $ponumber, BARCODE_CODE_TYPE, 175, BARCODE_IMAGE_HEIGHT, BARCODE_IMAGE_XRES, BARCODE_IMAGE_FONT).'</td></tr>';
                        echo '<tr><td colspan="2">Due: '.$recordSet->fields[15].'</td></tr></table>';
                        echo '</td></tr></table>';
                        echo '<table border="1" width="100%">';
                        $recordSet2 = &$conn->Execute("select item.itemcode,item.description,invpodetail.itemqty,invpodetail.itemprice,itemvendor.vordernumber,invpodetail.unitperpack from invpodetail cross join vendor cross join invpo cross join item left join itemvendor on itemvendor.itemid=item.id and itemvendor.vendorid=vendor.id where invpo.id=invpodetail.invpoid and invpo.vendorid=vendor.id and invpodetail.itemid=item.id and item.companyid=".sqlprep($active_company)." and invpodetail.invpoid=".sqlprep($invpoid));
                        //echo '<tr><th>Item Code</th><th>'.$recordSet->fields[0].'<br>Item Code</th><th>Description</th><th><font size="-2">Quantity Ordered</font></th><th>Price</th></tr>';
                        echo '<tr><th>'.$lang['STR_NUMBER'].'</th><th>'.$lang['STR_ITEM_CODE'].'</th><th>'.$lang['STR_DESCRIPTION'].'</th><th><font size="-2">'.$lang['STR_UNIT_PER_PACKAGE'].'</font></th><th><font size="-2">'.$lang['STR_QUANTITY'].'</font></th><th>'.$lang['STR_PRICE_PER_PACKAGE'].'</th><th>'.$lang['STR_TOTAL'].'</th></tr>';                        
                        $i=0;
                        while (!$recordSet2->EOF) {
                                $i++;
                                $tempsum=$recordSet2->fields[3]*$recordSet2->fields[2];
                                echo '<tr><td>'.$i.'.&nbsp;</td><td>'.$recordSet2->fields[0].'</td><td align="Right">'.$recordSet2->fields[1].'</td><td align="Right">'.$recordSet2->fields[5].'</td><td align="Right">'.checkdec($recordSet2->fields[2],0).'</td><td align="Right">$'.checkdec($recordSet2->fields[3],PREFERRED_DECIMAL_PLACES).'</td><td align="Right">$'.checkdec($tempsum,PREFERRED_DECIMAL_PLACES).'</td>';
                                $totalsum=$tempsum+$totalsum;
                                $recordSet2->MoveNext();
                        };
                        $recordSet = &$conn->Execute("select invponotes.note from invponotes where invponotes.invpoid=".sqlprep($invpoid));
                        echo '<tr><td colspan="5">'.$lang['STR_REMARKS'].' :&nbsp;'.$recordSet->fields[0].'</td><td  align="Right"><b>'.$lang['STR_TOTAL_PRICE'].'&nbsp;&nbsp;</b></td><td align="Right">$'.checkdec($totalsum,PREFERRED_DECIMAL_PLACES).'</td>';

                };
                echo '</table>'.$i.' Line items in PO #: '.$ponumber.'<br><br>';
        } else {
                die(texterror('Inventory PO Number not found.'));
        };
?>
<?php include('includes/footer.php'); ?>
