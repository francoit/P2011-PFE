<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     function gencompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone2, $phone3, $phone4, $email, $web, $name) {
        global $conn, $lang;
        if (!$city&&!$state&&$zip) {
               $cityst=getaddress($zip);
               $city=substr($cityst,0,strlen($cityst)-4);
               $state=substr($cityst,-2);
        };
          if ($conn->Execute("insert into gencompany (address1, address2, city, state, zip, country, phone1, phone2, phone3, phone4, email, web, name) VALUES (".sqlprep($address1).", ".sqlprep($address2).", ".sqlprep($city).", ".sqlprep($state).", ".sqlprep($zip).", ".sqlprep($country).", ".sqlprep($phone1).", ".sqlprep($phone2).", ".sqlprep($phone3).", ".sqlprep($phone4).", ".sqlprep($email).", ".sqlprep($web).", ".sqlprep($name).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_COMPANY']);
               return 0;
          } else {
               echo textsuccess($lang['STR_COMPANY_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function gencompanyupdate($id, $address1, $address2, $city, $state, $zip, $country, $phone1, $phone2, $phone3, $phone4, $email, $web, $name) {
        global $conn, $lang;
        if (!$city&&!$state&&$zip) {
                $cityst=getaddress($zip);
                $city=substr($cityst,0,strlen($cityst)-4);
                $state=substr($cityst,-2);
        };
          if ($conn->Execute("update gencompany set address1=".sqlprep($address1).", address2=".sqlprep($address2).", city=".sqlprep($city).", state=".sqlprep($state).", zip=".sqlprep($zip).", country=".sqlprep($country).", phone1=".sqlprep($phone1).", phone2=".sqlprep($phone2).", phone3=".sqlprep($phone3).", phone4=".sqlprep($phone4).", email=".sqlprep($email).", web=".sqlprep($web).", name=".sqlprep($name)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_COMPANY']);
               return 0;
          } else {
               echo textsuccess($lang['STR_COMPANY_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function gencompanydelete($id) {
          global $conn, $lang;
          if ($conn->Execute("delete from gencompany where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_COMPANY']);
               return 0;
          } else {
               echo textsuccess($lang['STR_COMPANY_DELETED_SUCCESSFULLY']);
               return 1;
          };
     };

     function genuseradd($name, $newpassword, $newpassword2, $raccessap, $raccessar, $raccessgl, $raccesspay, $raccessinv, $raccessest, $raccessfix, $raccessimp, $waccessap, $waccessar, $waccessgl, $waccesspay, $waccessinv, $waccessest, $waccessfix, $waccessimp, $saccessap, $saccessar, $saccessgl, $saccesspay, $saccessinv, $saccessest, $saccessfix, $saccessimp, $supervisoro, $active, $stylesheetid, $deflanguage) {
          global $conn, $lang;
          if ($newpassword==$newpassword2&&$newpassword!="") {
               if ($conn->Execute("insert into genuser (name, password, raccessap, raccessar, raccessgl, raccesspay, raccessinv, raccessest, raccessfix, raccessimp, waccessap, waccessar, waccessgl, waccesspay, waccessinv, waccessest, waccessfix, waccessimp, saccessap, saccessar, saccessgl, saccesspay, saccessinv, saccessest, saccessfix, saccessimp, supervisor, active, stylesheetid, deflanguage) VALUES (".sqlprep($name).", ".sqlprep(pwencrypt($newpassword)).", ".sqlprep($raccessap).", ".sqlprep($raccessar).", ".sqlprep($raccessgl).", ".sqlprep($raccesspay).", ".sqlprep($raccessinv).", ".sqlprep($raccessest).", ".sqlprep($raccessfix).", ".sqlprep($raccessimp).", ".sqlprep($waccessap).", ".sqlprep($waccessar).", ".sqlprep($waccessgl).", ".sqlprep($waccesspay).", ".sqlprep($waccessinv).", ".sqlprep($waccessest).", ".sqlprep($waccessfix).", ".sqlprep($waccessimp).", ".sqlprep($saccessap).", ".sqlprep($saccessar).", ".sqlprep($saccessgl).", ".sqlprep($saccesspay).", ".sqlprep($saccessinv).", ".sqlprep($saccessest).", ".sqlprep($saccessfix).", ".sqlprep($saccessimp).", ".sqlprep($supervisoro).", ".sqlprep($active).", ".sqlprep($stylesheetid).", ".sqlprep($dlanguage).")")=== false) {
                    echo texterror($lang['STR_ERROR_ADDING_USER']);
                    return 0;
               } else {
                    echo textsuccess($lang['STR_USER_ADDED_SUCCESSFULLY']);
                    return 1;
               };
          } else {
               if ($newpassword!=$newpassword2) echo texterror($lang['STR_ERROR_ADDING_USER'].' '.$lang['STR_PASSWORD_NOT_CONFIRMED']);
               if ($newpassword=="") echo texterror($lang['STR_ERROR_ADDING_USER'].' '.$lang['STR_PASSWORD_IS_BLANK']);
               return 0;
          };
     };

     function genuserupdate($id, $name, $newpassword, $newpassword2, $stylesheetid, $dlanguage) {
          global $conn, $lang;
          if ($newpassword==$newpassword2&&$newpassword!="") $passwordstr=", password=".sqlprep(pwencrypt($newpassword));
          if ($conn->Execute("update genuser set name=".sqlprep($name).$passwordstr.", stylesheetid=".sqlprep($stylesheetid).", deflanguage=".sqlprep($dlanguage)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_USER']);
               return 0;
          } else {
               echo textsuccess($lang['STR_USER_UPDATED_SUCCESSFULLY']);
               return 1;
          };
     };


     function genuserupdaterights($id, $raccessap, $raccessar, $raccessgl, $raccesspay, $raccessinv, $raccessest, $raccessfix, $raccessimp, $waccessap, $waccessar, $waccessgl, $waccesspay, $waccessinv, $waccessest, $waccessfix, $waccessimp, $saccessap, $saccessar, $saccessgl, $saccesspay, $saccessinv, $saccessest, $saccessfix, $saccessimp, $supervisoro, $active) {
          global $conn, $lang;
          if ($conn->Execute("update genuser set raccessap=".sqlprep($raccessap).", raccessar=".sqlprep($raccessar).", raccessgl=".sqlprep($raccessgl).", raccesspay=".sqlprep($raccesspay).", raccessinv=".sqlprep($raccessinv).", raccessest=".sqlprep($raccessest).", raccessfix=".sqlprep($raccessfix).", raccessimp=".sqlprep($raccessimp).", waccessap=".sqlprep($waccessap).", waccessar=".sqlprep($waccessar).", waccessgl=".sqlprep($waccessgl).", waccesspay=".sqlprep($waccesspay).", waccessinv=".sqlprep($waccessinv).", waccessest=".sqlprep($waccessest).", waccessfix=".sqlprep($waccessfix).", waccessimp=".sqlprep($waccessimp).", saccessap=".sqlprep($saccessap).", saccessar=".sqlprep($saccessar).", saccessgl=".sqlprep($saccessgl).", saccesspay=".sqlprep($saccesspay).", saccessinv=".sqlprep($saccessinv).", saccessest=".sqlprep($saccessest).", saccessfix=".sqlprep($saccessfix).", saccessimp=".sqlprep($saccessimp).", active=".sqlprep($active).", supervisor=".sqlprep($supervisoro)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_USER_RIGHTS']);
               return 0;
          } else {
               echo textsuccess($lang['STR_USER_RIGHTS_UPDATED_SUCCESSFULLY']);
               return 1;
          };


     };

     function genuserdelete($id) {
          global $conn, $lang;
          if ($conn->Execute("delete from genuser where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_USER']);
               return 1;
          } else {
               echo textsuccess($lang['STR_USER_DELETED_SUCCESSFULLY']);
               return 1;
          };
     };

     function formgencompanyadd() {
          global $conn, $lang;
               
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"></td><td><input type="text" name="address2" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAIN_OFFICE_PHONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" onChange="validatephone(this)"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FAX'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" onChange="validatephone(this)"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_SERVICE_PHONE'].':</td><td><input type="text" name="phone3" size="30" maxlength="20" onChange="validatephone(this)"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AFTER_HOURS_PHONE'].':</td><td><input type="text" name="phone4" size="30" maxlength="20" onChange="validatephone(this)"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMAIL'].':</td><td><input type="text" name="email" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WEB_ADDRESS'].':</td><td><input type="text" name="web" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          return 1;
     };

     function formgencompanyupdate($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select address1, address2, city, state, zip, country, phone1, phone2, phone3, phone4, email, web, name from gencompany where id='.$id);
          if (!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="50" value="'.rtrim($recordSet->fields[12]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"></td><td><input type="text" name="address2" size="30" maxlength="100" value="'.rtrim($recordSet->fields[1]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50" value="'.rtrim($recordSet->fields[2]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20" value="'.rtrim($recordSet->fields[3]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15" value="'.rtrim($recordSet->fields[4]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20" value="'.rtrim($recordSet->fields[5]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAIN_OFFICE_PHONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" value="'.rtrim($recordSet->fields[6]).'" onChange="validatephone(this)"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FAX'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" value="'.rtrim($recordSet->fields[7]).'" onChange="validatephone(this)"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_SERVICE_PHONE'].':</td><td><input type="text" name="phone3" size="30" maxlength="20" value="'.rtrim($recordSet->fields[8]).'" onChange="validatephone(this)"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AFTER_HOURS_PHONE'].':</td><td><input type="text" name="phone4" size="30" maxlength="20" value="'.rtrim($recordSet->fields[9]).'" onChange="validatephone(this)"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMAIL'].':</td><td><input type="text" name="email" size="30" maxlength="50" value="'.rtrim($recordSet->fields[10]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WEB_ADDRESS'].':</td><td><input type="text" name="web" size="30" maxlength="100" value="'.rtrim($recordSet->fields[11]).'"'.INC_TEXTBOX.'></td></tr>';
               return 1;
          } else {
               return 0;
          };
     };

     function formgencompanyselect($name) {
          global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id,name from gencompany order by id');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

     function formgenuseradd() {
          global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_USER_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PASSWORD'].':</td><td><input type="password" name="newpassword" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">('.$lang['STR_CONFIRM'].'):</td><td><input type="password" name="newpassword2" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
          //echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STYLE'].':</td><td><select name="stylesheetid"'.INC_TEXTBOX.'>';
          //$recordSet = &$conn->Execute('select id,name from genstylesheet order by name');
          //while (!$recordSet->EOF) {
              // echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
              // $recordSet->MoveNext();
          //};
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LANGUAGE'].':</td><td><select name="dlanguage"'.INC_TEXTBOX.'>
          <option value="'.SD_ENGLISH.'">English
          <option value="'.SD_PORTUGUESE.'">Portuguese
          <option value="'.SD_URDU.'">Urdu
          <option value="'.SD_PERSIAN.'">Persian
          <option value="'.SD_ARABIC.'">Arabic
          <option value="'.SD_INDONESIAN.'">Indonesian
          <option value="'.SD_SPANISH.'">Spanish
          <option value="'.SD_FRENCH.'">French
          <option value="'.SD_ZHBIG5.'">Chinese Traditional Big5
          <option value="'.SD_ZHUTF8.'">Chinese Traditional UTF-8
          <option value="'.SD_CNUTF8.'">Chinese Simplified UTF-8
          <option value="'.SD_CNGB2312.'">Chinese Simplified GB2312
          </select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ACTIVE'].':</td><td><input type="checkbox" name="active" value="1" checked'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SUPERVISOR'].':</td><td><input type="checkbox" name="supervisoro" value="1"'.INC_TEXTBOX.'></td></tr>';
          echo '</table><table><tr><th colspan="4" align="center"><center>'.$lang['STR_MODULE_ACCESS'].'</center></th></tr>';
          echo '<tr><th>'.$lang['STR_MODULE'].'</th><th>'.$lang['STR_READ'].'</th><th>'.$lang['STR_WRITE'].'</th><th>'.$lang['STR_SETUP'].'</th></tr>';
          if (SOFTWARE_SHOW_AP) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ACCOUNTS_PAYABLE'].':</td><td><input type="checkbox" name="raccessap" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessap" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessap" value="1"'.INC_TEXTBOX.'></td></tr>';
          if (SOFTWARE_SHOW_AR) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ACCOUNTS_RECEIVABLE'].':</td><td><input type="checkbox" name="raccessar" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessar" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessar" value="1"'.INC_TEXTBOX.'></td></tr>';
          if (SOFTWARE_SHOW_GENERAL_LEDGER) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GENERAL_LEDGER'].':</td><td><input type="checkbox" name="raccessgl" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessgl" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessgl" value="1"'.INC_TEXTBOX.'></td></tr>';
          if (SOFTWARE_SHOW_PAYROLL) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAYROLL'].':</td><td><input type="checkbox" name="raccesspay" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="waccesspay" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="saccesspay" value="1"'.INC_TEXTBOX.'></td></tr>';
          if (SOFTWARE_SHOW_INVENTORY) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVENTORY'].':</td><td><input type="checkbox" name="raccessinv" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessinv" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessinv" value="1"'.INC_TEXTBOX.'></td></tr>';
          if (SOFTWARE_SHOW_PRINT_MANAGEMENT) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ESTIMATING'].':</td><td><input type="checkbox" name="raccessest" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessest" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessest" value="1"'.INC_TEXTBOX.'></td></tr>';
          if (SOFTWARE_SHOW_FIXED_ASSETS) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FIXED_ASSETS'].':</td><td><input type="checkbox" name="raccessfix" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessfix" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessfix" value="1"'.INC_TEXTBOX.'></td></tr>';
          if (SOFTWARE_SHOW_PRINT_MANAGEMENT_IMP) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_IMPOSITION'].':</td><td><input type="checkbox" name="raccessimp" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessimp" value="1"'.INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessimp" value="1"'.INC_TEXTBOX.'></td></tr>';
          return 1;
     };

     function formgenuserupdate($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select name,stylesheetid,deflanguage from genuser where id='.$id);
          if (!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_USER_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="50" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PASSWORD'].':</td><td><input type="password" name="newpassword" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">('.$lang['STR_CONFIRM'].'):</td><td><input type="password" name="newpassword2" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
               //echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STYLE'].':</td><td><select name="stylesheetid"'.INC_TEXTBOX.'>';
               //$recordSet2 = &$conn->Execute('select id,name from genstylesheet order by name');
              // while (!$recordSet2->EOF) {
                  // echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[1],$recordSet2->fields[0], " selected").'>'.rtrim($recordSet2->fields[1])."\n";
                  // $recordSet2->MoveNext();
              // };
               echo '</select></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LANGUAGE'].':</td><td><select name="dlanguage"'.INC_TEXTBOX.'>
               <option value="'.SD_ENGLISH.'"'.checkequal($recordSet->fields[2],SD_ENGLISH,' selected').'>English
               <option value="'.SD_PORTUGUESE.'"'.checkequal($recordSet->fields[2],SD_PORTUGUESE,' selected').'>Portuguese
               <option value="'.SD_URDU.'"'.checkequal($recordSet->fields[2],SD_URDU,' selected').'>Urdu
               <option value="'.SD_PERSIAN.'"'.checkequal($recordSet->fields[2],SD_PERSIAN,' selected').'>Persian
               <option value="'.SD_ARABIC.'"'.checkequal($recordSet->fields[2],SD_ARABIC,' selected').'>Arabic
               <option value="'.SD_INDONESIAN.'"'.checkequal($recordSet->fields[2],SD_INDONESIAN,' selected').'>Indonesian
               <option value="'.SD_SPANISH.'"'.checkequal($recordSet->fields[2],SD_SPANISH,' selected').'>Spanish
               <option value="'.SD_FRENCH.'"'.checkequal($recordSet->fields[2],SD_FRENCH,' selected').'>French
               <option value="'.SD_ZHBIG5.'"'.checkequal($recordSet->fields[2],SD_ZHBIG5,' selected').'>Chinese Traditional Big5
               <option value="'.SD_ZHUTF8.'"'.checkequal($recordSet->fields[2],SD_ZHUTF8,' selected').'>Chinese Traditional UTF-8
               <option value="'.SD_CNUTF8.'"'.checkequal($recordSet->fields[2],SD_CNUTF8,' selected').'>Chinese Simplifed UTF-8
               <option value="'.SD_CNGB2312.'"'.checkequal($recordSet->fields[2],SD_CNGB2312,' selected').'>Chinese Simplifed GB2312
               </select></td></tr>';
               return 1;
          } else {
               return 0;
          };
     };

     function formgenuserupdaterights($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select raccessap, raccessar, raccessgl, raccesspay, raccessinv, raccessest, \'\', \'\', \'\', raccessfix, raccessimp, waccessap, waccessar, waccessgl, waccesspay, waccessinv, waccessest, \'\', \'\', \'\', waccessfix, waccessimp, saccessap, saccessar, saccessgl, saccesspay, saccessinv, saccessest,saccessfix, saccessimp, supervisor, active from genuser where id='.$id);
          if (!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ACTIVE'].':</td><td><input type="checkbox" name="active" value="1" '.checkequal($recordSet->fields[31],1,' checked').INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SUPERVISOR'].':</td><td><input type="checkbox" name="supervisoro" value="1" '.checkequal($recordSet->fields[30],1,' checked').INC_TEXTBOX.'></td></tr>';
               echo '</table><table><tr><th colspan="4" align="center"><center>'.$lang['STR_MODULE_ACCESS'].'</center></th></tr>';
               echo '<tr><th>'.$lang['STR_MODULE'].'</th><th>'.$lang['STR_READ'].'</th><th>'.$lang['STR_WRITE'].'</th><th>'.$lang['STR_SETUP'].'</th></tr>';
               if (SOFTWARE_SHOW_AP) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ACCOUNTS_PAYABLE'].':</td><td><input type="checkbox" name="raccessap" value="1" '.checkequal($recordSet->fields[0],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessap" value="1" '.checkequal($recordSet->fields[11],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessap" value="1" '.checkequal($recordSet->fields[22],1,' checked').INC_TEXTBOX.'></td></tr>';
               if (SOFTWARE_SHOW_AR) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ACCOUNTS_RECEIVABLE'].':</td><td><input type="checkbox" name="raccessar" value="1" '.checkequal($recordSet->fields[1],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessar" value="1" '.checkequal($recordSet->fields[12],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessar" value="1" '.checkequal($recordSet->fields[23],1,' checked').INC_TEXTBOX.'></td></tr>';
               if (SOFTWARE_SHOW_GENERAL_LEDGER) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GENERAL_LEDGER'].':</td><td><input type="checkbox" name="raccessgl" value="1" '.checkequal($recordSet->fields[2],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessgl" value="1" '.checkequal($recordSet->fields[13],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessgl" value="1" '.checkequal($recordSet->fields[24],1,' checked').INC_TEXTBOX.'></td></tr>';
               if (SOFTWARE_SHOW_PAYROLL) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAYROLL'].':</td><td><input type="checkbox" name="raccesspay" value="1" '.checkequal($recordSet->fields[3],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="waccesspay" value="1" '.checkequal($recordSet->fields[14],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="saccesspay" value="1" '.checkequal($recordSet->fields[25],1,' checked').INC_TEXTBOX.'></td></tr>';
               if (SOFTWARE_SHOW_INVENTORY) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVENTORY'].':</td><td><input type="checkbox" name="raccessinv" value="1" '.checkequal($recordSet->fields[4],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessinv" value="1" '.checkequal($recordSet->fields[15],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessinv" value="1" '.checkequal($recordSet->fields[26],1,' checked').INC_TEXTBOX.'></td></tr>';
               if (SOFTWARE_SHOW_PRINT_MANAGEMENT) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ESTIMATING'].':</td><td><input type="checkbox" name="raccessest" value="1" '.checkequal($recordSet->fields[5],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessest" value="1" '.checkequal($recordSet->fields[16],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessest" value="1" '.checkequal($recordSet->fields[27],1,' checked').INC_TEXTBOX.'></td></tr>';
               if (SOFTWARE_SHOW_FIXED_ASSETS) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FIXED_ASSETS'].':</td><td><input type="checkbox" name="raccessfix" value="1" '.checkequal($recordSet->fields[9],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessfix" value="1" '.checkequal($recordSet->fields[20],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessfix" value="1" '.checkequal($recordSet->fields[28],1,' checked').INC_TEXTBOX.'></td></tr>';
               if (SOFTWARE_SHOW_PRINT_MANAGEMENT_IMP) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_IMPOSITION'].':</td><td><input type="checkbox" name="raccessimp" value="1" '.checkequal($recordSet->fields[10],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="waccessimp" value="1" '.checkequal($recordSet->fields[21],1,' checked').INC_TEXTBOX.'></td><td><input type="checkbox" name="saccessimp" value="1" '.checkequal($recordSet->fields[29],1,' checked').INC_TEXTBOX.'></td></tr>';
               return 1;
          } else {
               return 0;
          };
     };

     function formgenuserselect($name) {
          global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_USER'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id,name from genuser order by name');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

?>
