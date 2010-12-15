<?PHP
     require_once('includes/defines.php');
	 require_once('includes/adodb/adodb.inc.php');
	 require_once('includes/functions.php');

// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)

	 check_system();

	 require_once('authentication/secure.php');
    //require_once('includes/lang/'.$deflanguage.'.php');

     session_unregister("deflanguage");
     if ($extlogon) { //if this is an extranet logon
        if ($custcompanyid) { //if external customer logon
          $recordSet = &$conn->SelectLimit('select company.companyname,gencompany.name,gencompany.id from company,customer,gencompany where customer.gencompanyid=gencompany.id and customer.companyid=company.id and customer.id='.sqlprep($custcompanyid),1);
          if (!$recordSet->EOF) {
               $active_custcompany=$custcompanyid;
               session_register("active_custcompany");
               $companyname=$recordSet->fields[0];
               $parcompname=$recordSet->fields[1];
               $parcompid=$recordSet->fields[2];
               $active_company=$parcompid;
          } else {
              die(texterror('Custcompanyid invalid.'));
          };
        } elseif ($vendcompanyid) { //if external vendor logon
          $recordSet = &$conn->SelectLimit('select company.companyname,gencompany.name,gencompany.id from company,vendor,gencompany where vendor.gencompanyid=company.id and vendor.orderfromcompanyid=company.id and vendor.id='.sqlprep($vendcompanyid),1);
          if (!$recordSet->EOF) {
               $active_vendcompany=$vendcompanyid;
               session_register("active_vendcompany");
               $companyname=$recordSet->fields[0];
               $parcompname=$recordSet->fields[1];
               $parcompid=$recordSet->fields[2];
               $active_company=$parcompid;
          } else {
              die(texterror('Vendcompanyid invalid.'));
          };
        } else {
           echo texterror('Extlogon passed, but custcompanyid or vendcompany id not.');
        };
        session_register("parcompid");
        session_register("active_company");
        $multicompany=0;
     } else {
       $extlogon=0;
       if ($recordSet->fields[0]>1)  {
          $multi_company=$recordSet->fields[0];
          $recordSet = &$conn->Execute('select id,name from gencompany order by name');
          if (!$recordSet->EOF) {
               if (!$active_company) {
                    $active_company=$recordSet->fields[0];
                    $companyname=$recordSet->fields[1];
                    session_register("companyname");
                    session_register("active_company");
               };
          };
        } elseif ($recordSet->fields[0]==1) {     //there is exactly 1 company
          $multi_company=0;
          $recordSet = &$conn->Execute('select id,name from gencompany');
          $active_company=$recordSet->fields[0];
          $companyname=$recordSet->fields[1];
          session_register("companyname");
          session_register("active_company");
        };
        session_register("multi_company");
        $userid=$ID;
        if (!$userid) die(texterror($lang['STR_NO_USER_ID_FOUND_PLEASE_CLOSE_YOUR_BROWSER_AND_LOG_BACK_IN']));
        session_register("userid");
        $recordSet = &$conn->Execute('select name,deflanguage from genuser where id='.sqlprep($userid));
        if ($recordSet->EOF) die(texterror('No user id found.  Please close your browser and log back in.'));
        $deflanguage=$recordSet->fields[1]; //set default language from defines.php
     };
     session_register("extlogon");
     if (defined("SYS_DEFAULT_LANG")) $deflanguage = SYS_DEFAULT_LANG; //Overwrite Default_lang
     if (!$deflanguage) $deflanguage = DEFAULT_LANG; //if default language isn't set in genuser, set it from defines.php
     if (!$deflanguage) $deflanguage = SD_ENGLISH; //if default language still isn't set, make it english
     if (!$deflanguage) $deflanguage = 1; //if default language still isn't set, make it english
     session_register("deflanguage");
     include_once('includes/lang/1.php');
     if (is_numeric($deflanguage)) include('includes/lang/'.$deflanguage.'.php');
     if (defined("SYS_DEFAULT_STR_CHAR_SET")) $lang['STR_CHAR_SET'] = SYS_DEFAULT_STR_CHAR_SET; //Overwrite Char_set
     //if (!defined("STR_CHAR_SET")) define('STR_CHAR_SET',DEFAULT_STR_CHAR_SET);



     echo '<html><head>';
     echo '<meta http-equiv="Content-Type" content="text/html; charset='.$lang['STR_CHAR_SET'].'">';
     echo '<title>ARIA '.VERSION.'</title></head>';
     if (CSS_MENU == 1) {
          echo '<frameset cols="0%,100%"  frameborder="no" border="0" BORDERCOLOR="#CCCCCC" framespacing="0">';
          echo '  <frame src="blank.php" name="menu" noresize scrolling="no">';
     } else {
          echo '<frameset cols="20%,*"  frameborder="yes" border="5" BORDERCOLOR="#CCCCCC" framespacing="5">';
          echo '  <frame src="menu.php" name="menu" noresize scrolling="auto">';
     }
     echo '  <frame src="main.php" name="main" noresize scrolling="auto">';
     echo '</frameset>';
     echo '</html>';
PHP?>
