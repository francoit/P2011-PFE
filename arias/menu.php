<?php require_once('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript" src="js/time.js"></script>

<center><b><big>
<?php echo $lang['STR_ARIA'];?>
</big></b></center>
<body bgcolor=#B0C4DE>
<BODY TEXT=#000000>
<BODY VLINK=#000000>
<BODY ALINK=#000000>
<FONT COLOR=#000000 FACE="Verdana, Tahoma, sans-serif">

<?
     echo '<center>';
     echo '<br>';
     $recordSet = &$conn->Execute('select count(*) from gencompany where active=1');
     if (!$recordSet||$recordSet->EOF||!$recordSet->fields[0]>0) die(texterror($lang['STR_NO_COMPANIES_FOUND_CANNOT_CONTINUE']));
     //echo '<div style="position: absolute; top:0px; left:0px;">';
     $mod=array('ap', 'ar', 'gl', 'pay', 'inv', 'est', 'fix', 'imp');
     if (!$extlogon) { //if this is not an extranet logon
        if ($recordSet->fields[0]>1)  {
           $multi_company=$recordSet->fields[0];
           $recordSet = &$conn->Execute('select id,name from gencompany where active=1 order by name');
           if ($recordSet&&!$recordSet->EOF) {
              echo '<form name="company"><select name="menu" onChange = "self.location = document.company.menu[document.company.menu.selectedIndex].value;">';
              while ($recordSet&&!$recordSet->EOF) {
                  if ($companyid==$recordSet->fields[0]||(!isset($companyid)&&$active_company==$recordSet->fields[0])) {
                      unset($companyname);
                      $active_company=$recordSet->fields[0];
                      //$companyname=$recordSet->fields[1];
/*                      if (strlen($recordSet->fields[1])>=12) {
                          $companyname=substr($recordSet->fields[1], 0, 12);
                          //$companyname=$recordSet->fields[1];
                      } else {
                          $companyname=$recordSet->fields[1];
                      };*/
                      echo '<option value="menu.php?companyid='.$recordSet->fields[0].'" selected>'.substr($recordSet->fields[1],0,12)."\n";
                  } else {
                      echo '<option value="menu.php?companyid='.$recordSet->fields[0].'">'.substr($recordSet->fields[1],0,12)."\n";
                  };
                  $recordSet->MoveNext();
              };
              echo '</select></from>';
           };
        } elseif ($recordSet->fields[0]==1) {     //there is exactly 1 company
            $multi_company=0;
            $recordSet = &$conn->Execute('select id,name from gencompany where active=1');
            $active_company=$recordSet->fields[0];
            $companyname=$recordSet->fields[1];
        };
        session_register("multi_company");
        session_register("active_company");
     };
     session_register("companyname");
     $recordSet = &$conn->Execute('select imageurl from arcompany where id='.sqlprep($active_company));
     if ($recordSet&&!$recordSet->EOF&&strlen(rtrim($recordSet->fields[0]))) echo '<img src="'.rtrim($recordSet->fields[0]).'" width="125" height="46"><br>';
     if (!$extlogon) {
         $recordSet = &$conn->Execute('select raccessap, raccessar, raccessgl, raccesspay, raccessinv, raccessest, raccessfix, raccessimp, waccessap, waccessar, waccessgl, waccesspay, waccessinv, waccessest, waccessfix, waccessimp,  saccessap, saccessar, saccessgl, saccesspay, saccessinv, saccessest, saccessfix, saccessimp, supervisor from genuser where id='.$userid);
         if (!$recordSet||$recordSet->EOF) die(texterror('Couldnt read user permissions.  Exiting.'));
         $i=0;
         foreach(array("_read","_write","_setup") as $type) {
             foreach($mod as $data) {
                 ${$data.$type}=$recordSet->fields[$i];
                 session_register($data.$type);
                 $i++;
             };
         };
         $usersupervisor=$recordSet->fields[24];
         session_register("usersupervisor");
      };

      unset($sqlstr);
      if (!$extlogon) {
          if (!$usersupervisor) $sqlstr.=' and supervisor=0';
          if ($usersupervisor) $sqlstr.=' and nonsupervisor=0';
          foreach($mod as $data) {
              if (${$data."_read"}) {
                  if (($data=='ap'&&SOFTWARE_SHOW_AP)||($data=='ar'&&SOFTWARE_SHOW_AR)||($data=='gl'&&SOFTWARE_SHOW_GENERAL_LEDGER)||($data=='pay'&&SOFTWARE_SHOW_PAYROLL)||($data=='inv'&&SOFTWARE_SHOW_INVENTORY)||($data=='est'&&SOFTWARE_SHOW_PRINT_MANAGEMENT)||($data=='fix'&&SOFTWARE_SHOW_FIXED_ASSETS)||($data=='imp'&&SOFTWARE_SHOW_PRINT_MANAGEMENT_IMP)) {
                      $sqlstr.=' and (access'.$data.'=0 or access'.$data.'=1)';
                  } else {
                  };
              };
          };
          foreach($mod as $data) {
              if (${$data."_setup"}) {
                  if (($data=='ap'&&SOFTWARE_SHOW_AP)||($data=='ar'&&SOFTWARE_SHOW_AR)||($data=='gl'&&SOFTWARE_SHOW_GENERAL_LEDGER)||($data=='pay'&&SOFTWARE_SHOW_PAYROLL)||($data=='inv'&&SOFTWARE_SHOW_INVENTORY)||($data=='est'&&SOFTWARE_SHOW_PRINT_MANAGEMENT)||($data=='fix'&&SOFTWARE_SHOW_FIXED_ASSETS)||($data=='imp'&&SOFTWARE_SHOW_PRINT_MANAGEMENT_IMP)) {
                      $sqlstr.=' and (setup'.$data.'=0 or setup'.$data.'=1)';
                  } else {
                  };
              };
          };
      } else {
          if ($custcompanyid) { //if external customer
              $sqlstr.=' and extcust=1';
          } elseif ($vendcompanyid) { //if external vendor
              $sqlstr.=' and extvend=1';
          };
          $sqlstr.=' and nonext=0';
      };
      $recordSet = &$conn->Execute('select id, name from menucategory where menu=1 '.$sqlstr.' order by orderflag');
      if (!$recordSet||$recordSet->EOF) die(texterror($lang['STR_COULDNT_READ_MENU_EXITING']));
	  echo "<table border=0 class=\"menu\" cellpadding=\"4\" cellspacing=\"0\">";
	  echo '<tr><td style="text-align:center" text-decoration: none>';

	  echo "</td></tr>";
	  echo "<tr><td>";
	  echo "</td></tr>";
      while (!$recordSet->EOF) {
		  echo "<tr><td>";
//          echo dynbuttonrollover("explain.php?menucategoryid=".$recordSet->fields[0],rtrim($recordSet->fields[1]));
          $tempSTR='STR_MENU_'.$recordSet->fields[0];
          echo dynbuttonrollover("explain.php?menucategoryid=".$recordSet->fields[0],rtrim($lang[$tempSTR]));
          $recordSet->MoveNext();

		  echo "</td></tr>";
      };
      echo '<br>';
      if (!$usersupervisor){
          echo "<tr><td>";
          echo dynbuttonrollover("genuserupd.php","Users Preferences");
          echo "</td></tr>";
      }
	  echo "<tr><td>";
      if (SOFTWARE_SHOW_MESSAGE) echo dynbuttonrollover("genmessage.php", $lang['STR_MESSAGE_CENTER']);
	  echo "</td></tr>";
	  echo "<tr><td>";
      if (SOFTWARE_SHOW_DOCMGMT) echo dynbuttonrollover("docmgmtout.php", $lang['STR_DOCUMENT_MANAGER']);
	  echo "</td></tr>";
	  echo "<tr><td>";
      echo dynbuttonrollover('index.php?logout=1', $lang['STR_LOG_OUT']).'<br>';
	  echo '</td></tr>';
	  echo '<form action="/" name="datetime">';
	  echo '</form>';
	  echo '</td></tr>';
	  ?>
	  <html>
	  <tr><td style="text-align:center"><a href="help/index.php" target="_blank"><strong><h1>?</h1></strong></a></td></tr></table></center></body>
	  </html>
 





