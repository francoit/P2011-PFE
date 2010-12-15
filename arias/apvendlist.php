<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>

<?
        echo '<center>';
        $recordSet = &$conn->Execute('select count(*) from vendor where vendor.gencompanyid='.sqlprep($active_company).' and vendor.cancel=0');
        if ($recordSet->EOF) die(texterror($lang['STR_NO_VENDORS_FOUND']));
        echo '<a href="apvendcsvlist.php"'.tooltip('This link generates a comma delimited (CSV) list of vendors.  This file may be displayed in your browser if Microsft Excel or another handler is installed, else you will be prompted to save the file.').'>'.$lang['STR_CSV_LIST'].'</a><br>'."\n";
        echo '<a href="apvendlistview.php"'.tooltip('This link generates an on screen list of the Order From information for your vendors.').'>'.$lang['STR_VENDOR_LIST_ORDER_FROM'].'</a><br>'."\n";
        echo '<a href="apvendlistview.php?payto=1"'.tooltip('This link generates an on screen list of the Pay To information for your vendors.').'>'.$lang['STR_VENDOR_LIST_PAY_TO'].'</a><br>'."\n";
        for ($i=1; $recordSet->fields[0]-(30*($i-1))>=0; $i++) {
            echo '<a href="labels.php?vendor=1&companyid='.$active_company.'" target="_new"'.tooltip('This link generates a page of mailing labels for your Order From vendors.  This file may be displayed as a PDF document in a new window.  It requires Adobe Acrobat Reader or another PDF viewer to display itself correctly.').'>'.$lang['STR_MAILING_LABELS'].' ('.$lang['STR_ORDER_FROM_PAGE'].' '.$i.')</a><br>'."\n";
            echo '<a href="labels.php?vendorpayto=1&companyid='.$active_company.'" target="_new"'.tooltip('This link generates a page of mailing labels for your Pay To vendors.  This file may be displayed as a PDF document in a new window.  It requires Adobe Acrobat Reader or another PDF viewer to display itself correctly.').'>'.$lang['STR_MAILING_LABELS'].' ('.$lang['STR_PAY_TO_PAGE'].' '.$i.')</a><br>'."\n";
        echo '</center>';
        };
?>

<?php include('includes/footer.php'); ?>
