<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //arcustlist.php - April 2001 copyright Noguska - Fostoria, OH  44830
        $recordSet = &$conn->Execute('select count(*) from customer where customer.cancel=0 and customer.gencompanyid='.sqlprep($active_company));
        if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_NO_CUSTOMERS_FOUND']));
        echo '<a href="arcustcsvlist.php"'.tooltip('This link generates a comma delimited (CSV) list of customers.  This file may be displayed in your browser if Microsoft Excel or another handler is installed, else you will be prompted to save the file.').'>CSV List</a><br>'."\n";
        echo '<a href="arcustlistview.php"'.tooltip('This link generates an on screen list of the Main Location information for your customers.').'>Customer List (Main Location)</a><br>'."\n";
        echo '<a href="arcustlistview.php?customershipto=1"'.tooltip('This link generates an on screen list of the Shipping Location information for your customers.').'>Customer List (Ship To Locations)</a><br>'."\n";
        for ($i=1; $recordSet->fields[0]-(30*($i-1))>=0; $i++) {
           echo '<a href="labels.php?customer=1&companyid='.$active_company.'" target="_new"'.tooltip('This link generates a page of mailing labels for your Main Location customer addresses.  This file may be displayed as a PDF document in a new window.  It requires Adobe Acrobat Reader or another PDF viewer to display itself correctly.').'>Mailing Labels (Customer Locations: Page '.$i.')</a><br>'."\n";
           echo '<a href="labels.php?customershipto=1&companyid='.$active_company.'" target="_new"'.tooltip('This link generates a page of mailing labels for your Shipping Location customer addresses.  This file may be displayed as a PDF document in a new window.  It requires Adobe Acrobat Reader or another PDF viewer to display itself correctly.').'>Mailing Labels (Ship To Locations: Page '.$i.')</a><br>'."\n";
        };
?>
<?php include('includes/footer.php'); ?>
