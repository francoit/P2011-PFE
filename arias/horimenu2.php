<?
//$bodystyle = "menu";
//$menubdstr = ";StartClock()";
//whether to highlight active fields in forms.
//define('FIELD_HIGHLIGHT', '0');
//require_once($PATH_TO_ROOT."includes/defines.php");
//require_once($PATH_TO_ROOT."includes/main.php");
//require_once($PATH_TO_ROOT."includes/horimenu.css");
//require_once($PATH_TO_ROOT."js/horimenu2.js");

// **********************************************************************
// Revenge of the Menu Bar Demo                                         *
//                                                                      *
// Copyright 2000 by Mike Hall                                          *
// Please see http://www.brainjar.com for terms of use.                 *
// **********************************************************************

//<script language="JavaScript" src="js/time.js"></script>
?>
</td></tr>
<?
     echo "\n".'<div class="menuBar" style="width:95%;">';
//The Code From Nola to get Users Right and Menu Functions
//don't know change anything.
     $recordSet = &$conn->Execute('select count(*) from gencompany where active=1');
     if (!$recordSet||$recordSet->EOF||!$recordSet->fields[0]>0) die(texterror('No companies found. Cannot continue.'));
//     echo "\n".'<div style="position: absolute; top:0px; left:0px;">';
     $mod=array('ap', 'ar', 'gl', 'pay', 'inv', 'est', 'fix', 'imp');
     if (!$extlogon) { //if this is not an extranet logon
          if ($recordSet->fields[0]>1)  {
               $multi_company=$recordSet->fields[0];
               $recordSet = &$conn->Execute('select id,name from gencompany where active=1 order by name');
               if ($recordSet&&!$recordSet->EOF) {
                    echo '
                    <a class="menuButton"
                    href="" onclick="return buttonClick(event, \'Company\');"
                    onmouseover="buttonMouseover(event, \'Company\');"
                    >'.$lang['STR_COMPANY'].'</a>';
               }
          } elseif ($recordSet->fields[0]==1) {     //there is exactly 1 company
               $multi_company=0;
               $recordSet = &$conn->Execute('select id,name from gencompany where active=1');
               $active_company=$recordSet->fields[0];
               $companyname=$recordSet->fields[1];
          }
          session_register("multi_company");
          session_register("active_company");
     }
     session_register("companyname");
     if (!$extlogon) {
          $recordSet = &$conn->Execute('select raccessap, raccessar, raccessgl, raccesspay, raccessinv, raccessest, raccessfix, raccessimp, waccessap, waccessar, waccessgl, waccesspay, waccessinv, waccessest, waccessfix, waccessimp,  saccessap, saccessar, saccessgl, saccesspay, saccessinv, saccessest, saccessfix, saccessimp, supervisor from genuser where id='.$userid);
          if (!$recordSet||$recordSet->EOF) die(texterror('Couldnt read user permissions.  Exiting.'));
          $i=0;
          foreach(array("_read","_write","_setup") as $type) {
               foreach($mod as $accessdata) {
                    ${$accessdata.$type}=$recordSet->fields[$i];
                    session_register($accessdata.$type);
                    $i++;
               }
          }
          $usersupervisor=$recordSet->fields[24];
          session_register("usersupervisor");
     }
     unset($sqlstr);
     unset($sqlstr2);
     unset($sqlstr3);
     if (!$extlogon) {
          if (!$usersupervisor) {
               $sqlstr.=' and supervisor=0';
               $sqlstr3.=' and supervisor=0';
          }
          if ($usersupervisor) $sqlstr.=' and nonsupervisor=0';
          foreach($mod as $accessdata) {
               if (${$accessdata."_read"}) {
                    if (($accessdata=='ap'&&SOFTWARE_SHOW_AP)||($accessdata=='ar'&&SOFTWARE_SHOW_AR)||($accessdata=='gl'&&SOFTWARE_SHOW_GENERAL_LEDGER)||($accessdata=='pay'&&SOFTWARE_SHOW_PAYROLL)||($accessdata=='inv'&&SOFTWARE_SHOW_INVENTORY)||($accessdata=='est'&&SOFTWARE_SHOW_PRINT_MANAGEMENT)||($accessdata=='fix'&&SOFTWARE_SHOW_FIXED_ASSETS)||($accessdata=='imp'&&SOFTWARE_SHOW_PRINT_MANAGEMENT_IMP)) {
                         $sqlstr.=' and (access'.$accessdata.'=0 or access'.$accessdata.'=1)';
                         $sqlstr2.=' or access'.$accessdata.'=1';
                    } else {
                    }
               }
          }
          foreach($mod as $accessdata) {
               if (${$accessdata."_setup"}) {
                    if (($accessdata=='ap'&&SOFTWARE_SHOW_AP)||($accessdata=='ar'&&SOFTWARE_SHOW_AR)||($accessdata=='gl'&&SOFTWARE_SHOW_GENERAL_LEDGER)||($accessdata=='pay'&&SOFTWARE_SHOW_PAYROLL)||($accessdata=='inv'&&SOFTWARE_SHOW_INVENTORY)||($accessdata=='est'&&SOFTWARE_SHOW_PRINT_MANAGEMENT)||($accessdata=='fix'&&SOFTWARE_SHOW_FIXED_ASSETS)||($accessdata=='imp'&&SOFTWARE_SHOW_PRINT_MANAGEMENT_IMP)) {
                         $sqlstr.=' and (setup'.$accessdata.'=0 or setup'.$accessdata.'=1)';
                         $sqlstr2.=' or setup'.$accessdata.'=1';
                    } else {
                    }
               }
          }
          if ($usersupervisor) $sqlstr2.=' or supervisor=1';
          $sqlstr2=$sqlstr3.' and ('.substr($sqlstr2,4).')';

     } else {
          if ($custcompanyid) { //if external customer
               $sqlstr.=' and extcust=1';
               $sqlstr2.=' and extcust=1';
          } elseif ($vendcompanyid) { //if external vendor
               $sqlstr.=' and extvend=1';
               $sqlstr2.=' and extcust=1';
          }
          $sqlstr.=' and nonext=0';
          $sqlstr2.=' and extcust=1';
     }
//The Main Menu (1st Level Menu)
     $recordSet = &$conn->Execute('select id, name from menucategory where menu=1 '.$sqlstr2.' order by orderflag');
     if (!$recordSet||$recordSet->EOF) die(texterror('Couldnt read menu.  Exiting.'));
     $Orderprt = 1;
     while (!$recordSet->EOF) {
          if  (($recordSet->fields[0]>=1)&&($recordSet->fields[0]<=3)&&($Orderprt==1)) {
               echo '
                    <a class="menuButton"
                    href="" onclick="return buttonClick(event, \'Order\');"
                    onmouseover="buttonMouseover(event, \'Order\');"
                    >'.$lang['STR_ORDER'].'</a>';
               $Orderprt = 0;
          }
          if  ($recordSet->fields[0]>3) {
               $recordSet2 = &$conn->Execute('select count(*) from menufunction where menucategoryid='.$recordSet->fields[0]);
               if ($recordSet2 > 0) {
                    $tempSTR='STR_MENU_'.$recordSet->fields[0];
                    $menuname=rtrim($lang[$tempSTR]);
                    echo '
                    <a class="menuButton"
                    href="" onclick="return buttonClick(event, \''.$recordSet->fields[1].'\');"
                    onmouseover="buttonMouseover(event, \''.$recordSet->fields[1].'\');"
                    >'.$menuname.'</a>';
               }
          }
          $recordSet->MoveNext();
     }
     if (!$usersupervisor) {
     echo '
                    <a class="menuButton"
                    href="genuserupd.php" onclick="return buttonClick(event, \'preferences\');"
                    onmouseover="buttonMouseover(event, \'preferences\');"
                    >'.$lang['STR_MENU_12'].'</a>';
     }
     echo '
                    <a class="menuButton"
                    href="" onclick="return buttonClick(event, \'otherMenu\');"
                    onmouseover="buttonMouseover(event, \'otherMenu\');"
                    >'.$lang['STR_OTHER_MENU'].'</a>
                    <a class="menuButton"
                    href="index.php?logout=1" target="_parent" onclick="return buttonClick(event, \'logoutMenu\');"
                    onmouseover="buttonMouseover(event, \'logoutMenu\');"
                    >'.$lang['STR_LOG_OUT'].'</a>
</div>';
// Menu End
// The Menu to read Company Name
     $recordSet = &$conn->Execute('select count(*) from gencompany where active=1');
     if ($recordSet->fields[0]>1)  {
          $recordSet = &$conn->Execute('select id,name from gencompany where active=1 order by name');
          echo "\n".'
          <div id="Company" class="menu"
          onmouseover="menuMouseover(event)">'."\n";
          while ($recordSet&&!$recordSet->EOF) {
               $recordSet2 = &$conn->Execute('select imageurl from arcompany where id='.$recordSet->fields[0]);
               if ($companyid==$recordSet->fields[0]||(!isset($companyid)&&$active_company==$recordSet->fields[0])) {
                    echo '<a class="menuItem" href="'.$docfilename.'?companyid='.$recordSet->fields[0].'">';
                    if ($recordSet2&&!$recordSet2->EOF&&strlen(rtrim($recordSet2->fields[0]))) echo "\n".'<img src="'.rtrim($recordSet2->fields[0]).'" width="10" height="10">';
                    echo '<span class="menuItemText">'.$recordSet->fields[1].'</span><span class="menuItemArrow">&#9654;</span></a>'."\n";
                    $active_company=$recordSet->fields[0];
               } else {
                    echo '<a class="menuItem" href="'.$docfilename.'?companyid='.$recordSet->fields[0].'">';
                    if ($recordSet2&&!$recordSet2->EOF&&strlen(rtrim($recordSet2->fields[0]))) echo "\n".'<img src="'.rtrim($recordSet2->fields[0]).'" width="10" height="10">';
                    echo '<span class="menuItemText">'.$recordSet->fields[1].'</span></a>'."\n";
               }
               $recordSet->MoveNext();
          }
          echo "\n".'</div>';
     }
// Menu end
// Sub Menu for Order
     $recordSet = &$conn->Execute('select id, name from menucategory where menu=1 '.$sqlstr2.' order by orderflag');
     $Orderprt = 1;
     while (!$recordSet->EOF) {
          if  (($recordSet->fields[0]>=1)&&($recordSet->fields[0]<=3)) {
               if ($Orderprt==1) echo '<div id="Order" class="menu" onmouseover="menuMouseover(event)">';
               $tempSTR='STR_MENU_'.$recordSet->fields[0];
               $menuname=rtrim($lang[$tempSTR]);
               echo "\n".'
               <a class="menuItem" href=""
               onclick="return false;"
               onmouseover="menuItemMouseover(event, \''.$recordSet->fields[1].'\');"
               ><span class="menuItemText">'.$menuname.'</span><span class="menuItemArrow">&#9654;</span></a>';
               $Orderprt = 0;
          }
          $recordSet->MoveNext();
     }
     echo "\n".'</div>'."\n";
// Sub Menu End
// The Drop Down Menu with link
     $expmenu=0;
     $recordSet = &$conn->Execute('select id, name from menucategory where menu=1 '.$sqlstr2.' order by orderflag');
     if (!$recordSet||$recordSet->EOF) die(texterror('Couldnt read menu.  Exiting.'));
     while (!$recordSet->EOF) {
          $menucategoryid=rtrim($recordSet->fields[0]);
          $recordSet2 = &$conn->Execute('select id, name, link, leftimageurl, rightimageurl from menufunction where menucategoryid='.sqlprep($menucategoryid).$sqlstr2.' order by orderflag');
          if (!$recordSet2||$recordSet2->EOF) die(texterror('Couldnt read Sub menu.  Exiting.'));
          echo '
          <div id="'.$recordSet->fields[1].'" class="menu"
          onmouseover="menuMouseover(event)">'."\n";
          while (!$recordSet2->EOF) {
               $tempSTR='STR_SUBMENU_'.$menucategoryid.'_'.$recordSet2->fields[0];
               $menuname=rtrim($lang[$tempSTR]);
               $link=rtrim($recordSet2->fields[2]);
               if (!$link) $link='construct.php';
               if (EXPLAIN_SHOW_PICTURES) {
                    $leftimage=rtrim($recordSet2->fields[3]);
                    $rightimage=rtrim($recordSet2->fields[4]);
               }
               if (!(strncmp($link,explain,7) <> 0)) {
                   $expmenu += 1;
                   $menucat[$expmenu] = substr($link,"-2");
                   $submenuname[$expmenu] = $recordSet2->fields[1];
                   echo "\n".'
                   <a class="menuItem" href=""
                   onclick="return false;"
                   onmouseover="menuItemMouseover(event, \''.$recordSet2->fields[1].'\');"
                   ><span class="menuItemText">'.$menuname.'</span><span class="menuItemArrow">&#9654;</span></a>';
               } else {
                   echo '<a class="menuItem" href="'.$link.'">'.$menuname.'</a>'."\n";
               }
               $recordSet2->MoveNext();
          }
     echo '</div>';
     $recordSet->MoveNext();
     }
     $expmenu;
// Drop down Menu End

// The Drop Down Menu For Setup function
     if ($expmenu > 0) {
          for($j=1;$j<=$expmenu;$j++) {
//               echo $menucat[$j].'--'.$j; 
               $recordSet = &$conn->Execute('select id, name from menucategory where id='.$menucat[$j]);
               if (!$recordSet||$recordSet->EOF) die(texterror('Couldnt read menu.  Exiting.'));
               while (!$recordSet->EOF) {
                    $menucategoryid=$menucat[$j];
                    $recordSet2 = &$conn->Execute('select id, name, link, leftimageurl, rightimageurl from menufunction where menucategoryid='.sqlprep($menucategoryid).$sqlstr2.' order by orderflag');
                    if (!$recordSet2||$recordSet2->EOF) die(texterror('Couldnt read Sub menu.  Exiting.'));
                    echo '
                    <div id="'.$submenuname[$j].'" class="menu"
                    onmouseover="menuMouseover(event)">'."\n";
                    while (!$recordSet2->EOF) {
                         $tempSTR='STR_SUBMENU_'.$menucategoryid.'_'.$recordSet2->fields[0];
                         $menuname=rtrim($lang[$tempSTR]);
                         $link=rtrim($recordSet2->fields[2]);
                         if (!$link) $link='construct.php';
                         if (EXPLAIN_SHOW_PICTURES) {
                              $leftimage=rtrim($recordSet2->fields[3]);
                              $rightimage=rtrim($recordSet2->fields[4]);
                         }
                         echo '<a class="menuItem" href="'.$link.'">'.$menuname.'</a>'."\n";
                         $recordSet2->MoveNext();
                    }
               echo '</div>';
               $recordSet->MoveNext();
               }
          }
     }
     
// Drop down Setup Menu End
// Other Menu Function as it is too long
     echo '
     <div id="otherMenu" class="menu"
     onmouseover="menuMouseover(event)">
     <a class="menuItem" href="genmessage.php">'.$lang['STR_MESSAGE_CENTER'].'</a>
     <a class="menuItem" href="docmgmtout.php">'.$lang['STR_DOCUMENT_MANAGER'].'</a>
     </div>';
// Other Menu End

//Original Nola Code for time, Enable it if you like it
/*   echo "\n".'<tr><td class="dateandtime">'."\n";
     echo "\n".'<form action="/" name="datetime">'."\n";
     echo "\n".'<input class="dateandtime" readonly type="text" name="time" size="12">'."\n";
     echo "\n".'</td></tr><tr><td class="dateandtime">'."\n";
     echo "\n".'<input class="dateandtime" readonly type="text" name="date" size="12">'."\n";
     echo "\n".'</form>'."\n";
     echo "\n".'</td></tr>'."\n";
//   echo "\n".'<tr><td style="text-align:center">'."\n";
//   echo "\n".'<img src="images/noguska13.gif">'."\n";
//   echo "\n".'</td></tr>'."\n";
     echo "\n".'</table>'."\n";
     if (XBS_LOGON_SHOW) echo "\n".'<img src="images/temp/xrx1.png"><br>';*/

//     echo "\n".'</body></html>';
?>
<tr>
<td valign=top> 
