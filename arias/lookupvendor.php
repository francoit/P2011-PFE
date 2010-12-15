<?php require_once('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript" src="js/lookup.js">
</script>
<?php      echo '<center>';
        echo texttitle('Vendor Lookup');
        if (isset($comp)||!MANY_VENDORS) {
                if ($comp) $compstr=' and (ptcompany.companyname like '.sqlprep('%'.$comp.'%').' or ofcompany.companyname like '.sqlprep('%'.$comp.'%').')';
                $recordSet = &$conn->Execute('select vendor.id, ptcompany.companyname from vendor, company as ptcompany, company as ofcompany where vendor.orderfromcompanyid=ofcompany.id and vendor.paytocompanyid=ptcompany.id and ofcompany.cancel=0 and ptcompany.cancel=0 and vendor.gencompanyid='.sqlprep($active_company).$compstr.' and vendor.cancel=0 order by ptcompany.companyname');
                if ($recordSet->EOF) die(texterror('No matching vendors found.').'<br><font size="-1"><a href="javascript:history.back(1)">Back</a></font>');
                echo '<form name="mainform"><select name="'.$name.'"'.INC_TEXTBOX.'>';
                while (!$recordSet->EOF) {
                        echo '<option value="'.$recordSet->fields[0].'"'.checkequal($default,$recordSet->fields[0],' selected').'>'.$recordSet->fields[1].' - '.$recordSet->fields[0]."\n";
                        $recordSet->MoveNext();
                };
                echo '</select><br><br><input type="button" onClick="setField('.sqlprep($name).')" value="Select">';
        } else {
                echo '<form name="mainform" action="lookupvendor.php" method="get"><input type="hidden" name="name" value="'.$name.'">';
                echo texttitle('Vendor Company Name');
                echo '<input type="text" name="comp" size="20"'.INC_TEXTBOX.'>'."\n";
                echo '<br><input type="submit" value="Search"></form>';
        };
        echo '</center>';
?>

<?php require_once('includes/footer.php');?>
