<?  //invfunctions.php - to be used as an include file where needed

// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)

     function invunitnameadd($unitname) {
          global $conn, $lang, $userid;
          checkpermissions('inv');
          if ($conn->Execute("insert into unitname (unitname, entrydate, entryuserid, lastchangeuserid) VALUES (".sqlprep($unitname).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).")") === false) {
           echo texterror($lang['STR_ERROR_ADDING_UNIT_NAME']);
           return 0;
          } else {
           echo textsuccess($lang['STR_UNIT_NAME_ADDED_SUCCESSFULLY']);
           return 1;
          };
     };

     function invunitnameupdate($id, $unitname) {
          global $conn, $lang, $userid;
          checkpermissions('inv');
          if ($conn->Execute("update unitname set unitname=".sqlprep($unitname).", lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_UNIT_NAME']);
               return 0;
          } else {
               echo textsuccess($lang['STR_UNIT_NAME_UPDATED_SUCCESSFULLY']);
               return 1;
          };
     };

     function invunitnamedelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('inv');
          if ($conn->Execute("update unitname set cancel='1', canceldate=NOW(), canceluserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_UNIT_NAME']);
               return 0;
          } else {
               echo textsuccess($lang['STR_UNIT_NAME_DELETED_SUCCESSFULLY']);
               return 1;
          };
     };

     function invcompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name) {
          global $conn, $lang, $userid;
          checkpermissions('inv');
          if (!$city&&!$state&&$zip) {
               $cityst=getaddress($zip);
               $city=substr($cityst,0,strlen($cityst)-4);
               $state=substr($cityst,-2);
          };
          if ($conn->Execute("insert into company (address1, address2, city, state, zip, country, phone1, phone1comment, phone2, phone2comment, phone3, phone3comment, phone4, phone4comment, email1, email1comment, email2, email2comment, website, federalid, companyname, entrydate, entryuserid, lastchangeuserid) VALUES (".sqlprep($address1).", ".sqlprep($address2).", ".sqlprep($city).", ".sqlprep($state).", ".sqlprep($zip).", ".sqlprep($country).", ".sqlprep($phone1).", ".sqlprep($phone1comment).", ".sqlprep($phone2).", ".sqlprep($phone2comment).", ".sqlprep($phone3).", ".sqlprep($phone3comment).", ".sqlprep($phone4).", ".sqlprep($phone4comment).", ".sqlprep($email1).", ".sqlprep($email1comment).", ".sqlprep($email2).", ".sqlprep($email2comment).", ".sqlprep($website).", ".sqlprep($federalid).", ".sqlprep($name).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_COMPANY']);
               return 0;
          } else {
               return 1;
          };
     };

     function invcompanyupdate($id, $address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name,$lastchangedate) {
          global $conn, $lang, $userid;
          checkpermissions('inv');
          $recordSet=&$conn->Execute("select count(*) from company where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate));
          if ($recordSet&&!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"company","id");
                    return 0;
               } else {
               if (!$city&&!$state&&$zip) {
                    $cityst=getaddress($zip);
                    $city=substr($cityst,0,strlen($cityst)-4);
                    $state=substr($cityst,-2);
               };
                    if ($conn->Execute("update company set address1=".sqlprep($address1).", address2=".sqlprep($address2).", city=".sqlprep($city).", state=".sqlprep($state).", zip=".sqlprep($zip).", country=".sqlprep($country).", phone1=".sqlprep($phone1).", phone1comment=".sqlprep($phone1comment).", phone2=".sqlprep($phone2).", phone2comment=".sqlprep($phone2comment).", phone3=".sqlprep($phone3).", phone3comment=".sqlprep($phone3comment).", phone4=".sqlprep($phone4).", phone4comment=".sqlprep($phone4comment).", email1=".sqlprep($email1).", email1comment=".sqlprep($email1comment).", email2=".sqlprep($email2).", email2comment=".sqlprep($email2comment).", website=".sqlprep($website).", companyname=".sqlprep($name).", federalid=".sqlprep($federalid).", lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_COMPANY']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
          };
     };

     function invcompanydelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('inv');
          if ($conn->Execute("update company set cancel='1', canceldate=NOW(), canceluserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_COMPANY']);
               return 0;
          } else {
               return 1;
          };
     };

     function invinventorylocationadd($companyid) {
          global $conn, $lang, $active_company;
          checkpermissions('inv');
          if ($conn->Execute("insert into inventorylocation (companyid, gencompanyid) VALUES (".sqlprep($companyid).", ".sqlprep($active_company).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_INVENTORY_LOCATION']);
               return 0;
          } else {
               echo textsuccess($lang['STR_INVENTORY_LOCATION_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function invinventorylocationdelete($id) {
          global $conn, $lang;
          checkpermissions('inv');
          if ($conn->Execute("delete from inventorylocation where companyid=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_INVENTORY_LOCATION']);
               return 0;
          } else {
                  echo textsuccess($lang['STR_INVENTORY_LOCATION_DELETED_SUCCESSFULLY']);
               return 1;
          };
     };

     function itemVendorDelete($id,$delete) {
        global $conn, $lang,$userid;
          checkpermissions('inv');
          if ($conn->Execute("update itemvendor set cancel='1', canceldate=NOW(), canceluserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_ITEM_VENDOR_INFORMATION']);
               return 0;
          } else {
               return 1;
          };
     }

     function itemVendorAddUpdate($AddUpdate,$id,$vendorid,$itemid, $vordernumber,$vitemunitnameid, $vitemconversion, $vitemcost1, $vitemqty1, $vitemcost2, $vitemqty2, $vitemcost3, $vitemqty3,$vitemcost4,$lastchangedate) {
          // $AddUpdate should be set as follows:
          //  1=Add New Item
          //  0=Update Data on Item
          global $conn, $lang, $userid, $active_company;
          checkpermissions('inv');
          if ($AddUpdate) { // Add New Vendor Item after make sure not already exists
                if ($conn->Execute('insert into itemvendor (vendorid,itemid,vordernumber,vitemunitnameid,vitemconversion,vitemcost1,vitemqty1,vitemcost2,vitemqty2,vitemcost3,vitemqty3,vitemcost4,entrydate,entryuserid,lastchangeuserid) values ('.sqlprep($vendorid).", ".sqlprep($itemid).", ".sqlprep($vordernumber).", ".sqlprep($vitemunitnameid).", ".sqlprep($vitemconversion).", ".sqlprep($vitemcost1).", ".sqlprep($vitemqty1).", ".sqlprep($vitemcost2).", ".sqlprep($vitemqty2).", ".sqlprep($vitemcost3).", ".sqlprep($vitemqty3).", ".sqlprep($vitemcost4).", NOW(),".sqlprep($userid).", ".sqlprep($userid).')') === false) {
                   echo texterror($lang['STR_ERROR_ADDING_VENDOR_ITEM_INFORMATION']);
                   return 0;
                } else {
                   return 1;
                };
          } else {
             $recordSet=&$conn->Execute("select count(*) from itemvendor where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate));
             if ($recordSet&&!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"itemvendor","id");
                    return 0;
               } else {
                    if ($conn->Execute("update itemvendor set vordernumber=".sqlprep($vordernumber).", vitemunitnameid=".sqlprep($vitemunitnameid).", vitemconversion=".sqlprep($vitemconversion).", vitemcost1=".sqlprep($vitemcost1).", vitemqty1=".sqlprep($vitemqty1).", vitemcost2=".sqlprep($vitemcost2).", vitemqty2=".sqlprep($vitemqty2).", vitemcost3=".sqlprep($vitemcost3).", vitemqty3=".sqlprep($vitemqty3).", vitemcost4=".sqlprep($vitemcost4).", lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_VENDOR_ITEM_INFORMATION']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
            };
          };
     };


     function invitemcompositeparentquantityupdate($subitemcodeid, $locationid) { //update composite item on-hand figures where the item received is a sub item
          global $conn, $lang;
          $recordSet=&$conn->Execute('select compositeitemid.itemcodeid,itemlocation.onhandqty from compositeitemid,itemlocation where compositeitemid.itemcodeid=itemlocation.itemid and itemlocation.inventorylocationid='.sqlprep($locationid).' and compositeitemid.subitemcodeid='.sqlprep($subitemcodeid));
          if (!$recordSet||$recordSet->EOF) return 1;
          while (!$recordSet->EOF) {
               $recordSet2=&$conn->Execute('select compositeitemid.subitemcodeid,compositeitemid.quantity from compositeitemid where compositeitemid.itemcodeid='.sqlprep($recordSet->fields[0]));
               while ($recordSet2&&!$recordSet2->EOF) {
                    $recordSet3=&$conn->Execute('select itemlocation.onhandqty from itemlocation where itemlocation.itemid='.sqlprep($recordSet2->fields[0]).' and itemlocation.inventorylocationid='.sqlprep($locationid));
                    if ($recordSet3&&!$recordSet3->EOF) $maxqty[] = $recordSet3->fields[0]/$recordSet2->fields[1];
                    $recordSet2->MoveNext();
               };
               $minmaxqty=0; //safety net in case composite item has no sub items
               while(list($key,$value)=each($maxqty)) { //get the lowest qty of sub item, and use it to determine parent on hand qty
                    if ($minmaxqty<$value) $minmaxqty=floor($value);
               };
               if ($conn->Execute('update itemlocation set itemlocation.onhandqty='.$minmaxqty.' where itemlocation.inventorylocationid='.sqlprep($locationid).' and itemlocation.itemid='.sqlprep($recordSet->fields[0])) === false) {
                    return 0;
               } else {
                    return 1;
               };
               $recordSet->MoveNext();
          };
     };

     function invitemvendorqtypricingupdate($itemid, $vendorid, $itemqty, $itemprice) {
          global $conn, $lang;
          $recordSet=&$conn->SelectLimit('select id,vitemqty1,vitemqty2,vitemqty3 from itemvendor where vendorid='.sqlprep($vendorid).' and itemid='.sqlprep($itemid).' order by entrydate desc',1);
          if ($recordSet&&!$recordSet->EOF) {
               $field='vitemcost1';
               if ($itemqty>$recordSet->fields[1]&&$recordSet->fields[1]>0) $field='vitemcost2';
               if ($itemqty>$recordSet->fields[2]&&$recordSet->fields[2]>0) $field='vitemcost3';
               if ($itemqty>$recordSet->fields[3]&&$recordSet->fields[3]>0) $field='vitemcost4';
               if ($conn->Execute("update itemvendor set ".$field."=".sqlprep($itemprice)." where id=".sqlprep($recordSet->fields[0])) === false) {
                    return 0;
               } else {
                    return 1;
               };
          } else {
               return 0;
          };
     };

     function forminvunitnameupdate($id) {
          global $conn, $lang;
          checkpermissions('inv');
          $recordSet = &$conn->Execute('select unitname from unitname where id='.$id);
          if ($recordSet&&!$recordSet->EOF) {
                echo '<tr><td>'.$lang['STR_INVENTORY_ITEM_UNIT'].':</td><td><input type="hidden" name="id" value="'.$id.'">';
                echo '<input type="text" name="unitname" size="30" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
                return 1;
          } else {
                return 0;
          };
     };

     function forminvunitnameselect($name) {
          global $conn, $lang;
          echo '<tr><td>'.$lang['STR_INVENTORY_ITEM_UNIT'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id,unitname from unitname where cancel=0 order by id');
          while ($recordSet&&!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
     };

     function forminvinventorylocationadd() {
     	  global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVENTORY_LOCATION_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"></td><td><input type="text" name="address2" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAIL_STOP'].':</td><td><input type="text" name="mailstop" size="20" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"></td><th>'.$lang['STR_PHONE'].'</th><th>'.$lang['STR_PHONE_DESCRIPTION'].'</th></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE_ONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="text" name="phone1comment" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE_TWO'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="text" name="phone2comment" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE_THREE'].':</td><td><input type="text" name="phone3" size="30" maxlength="20" onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="text" name="phone3comment" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE_FOUR'].':</td><td><input type="text" name="phone4" size="30" maxlength="20" onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="text" name="phone4comment" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td></td><th>'.$lang['STR_EMAIL'].'</th><th>'.$lang['STR_EMAIL_DESCRIPTION'].'</th></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMAIL_ONE'].':</td><td><input type="text" name="email1" size="30" maxlength="50"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="text" name="email1comment" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMAIL_TWO'].':</td><td><input type="text" name="email2" size="30" maxlength="50"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="text" name="email2comment" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr><tr></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WEB_ADDRESS'].':</td><td><input type="text" name="website" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FEDERAL_ID'].':</td><td><input type="text" name="federalid" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          return 1;
     };

     function forminvinventorylocationselect($name) {
          global $conn, $lang, $active_company;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVENTORY_LOCATION'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select company.id,company.companyname from company,inventorylocation where inventorylocation.companyid=company.id and company.cancel=0 and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
          while ($recordSet&&!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

     function forminvinventorylocationupdate($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select company.address1, company.address2, company.city, company.state, company.zip, company.country, company.phone1, company.phone1comment, company.phone2, company.phone2comment, company.phone3, company.phone3comment, company.phone4, company.phone4comment, company.email1, company.email1comment, company.email2, company.email2comment, company.website, company.federalid, company.companyname,company.lastchangedate from company where company.id='.$id);
          if ($recordSet&&!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVENTORY_LOCATION_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="50" value="'.rtrim($recordSet->fields[20]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"></td><td><input type="text" name="address2" size="30" maxlength="100" value="'.rtrim($recordSet->fields[1]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50" value="'.rtrim($recordSet->fields[2]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20" value="'.rtrim($recordSet->fields[3]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15" value="'.rtrim($recordSet->fields[4]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20" value="'.rtrim($recordSet->fields[5]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAIL_STOP'].':</td><td><input type="text" name="mailstop" size="20" maxlength="20" value="'.rtrim($recordSet->fields[23]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td></td><th>'.$lang['STR_PHONE'].'</th><th>'.$lang['STR_PHONE_DESCRIPTION'].'</th></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE_ONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" value="'.rtrim($recordSet->fields[6]).'"  onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="phone1comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[7]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE_TWO'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" value="'.rtrim($recordSet->fields[8]).'"  onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="phone2comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[9]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE_THREE'].':</td><td><input type="text" name="phone3" size="30" maxlength="20" value="'.rtrim($recordSet->fields[10]).'"  onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="phone3comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[11]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE_FOUR'].':</td><td><input type="text" name="phone4" size="30" maxlength="20" value="'.rtrim($recordSet->fields[12]).'"  onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="phone4comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[13]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td></td><th>'.$lang['STR_EMAIL'].'</th><th>'.$lang['STR_EMAIL_DESCRIPTION'].'</th></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMAIL_ONE'].':</td><td><input type="text" name="email1" size="30" maxlength="50" value="'.rtrim($recordSet->fields[14]).'"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="email1comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[15]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMAIL_TWO'].':</td><td><input type="text" name="email2" size="30" maxlength="50" value="'.rtrim($recordSet->fields[16]).'"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="email2comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[17]).'"'.INC_TEXTBOX.'></td></tr><tr></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WEB_ADDRESS'].':</td><td><input type="text" name="website" size="30" maxlength="100" value="'.rtrim($recordSet->fields[18]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FEDERAL_ID'].':</td><td><input type="text" name="federalid" size="30" maxlength="100" value="'.rtrim($recordSet->fields[19]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<input type="hidden" name="lastchangedate" value="'.$recordSet->fields[21].'">';
               return 1;
          } else {
               return 0;
          };
     };

     function forminvpoadd() {
          global $conn, $lang,$active_company,$userid;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CONTACT'].'</td><td><input type="text" name="contact" maxlength="20" size="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VENDOR_ORDER_NUMBER'].'</td><td><input type="text" name="ordernumber" maxlength="20" size="30"'.INC_TEXTBOX.'></td></tr>';
          $recordSet = &$conn->Execute('select max(ponumber) from invpo where gencompanyid='.sqlprep($active_company));
          if ($recordSet&&!$recordSet->EOF&&is_numeric($recordSet->fields[0])) $ponumber=$recordSet->fields[0] + 1;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PO_NUMBER'].'</td><td><input type="text" name="ponumber" maxlength="20" size="30"'.INC_TEXTBOX.' value="'.$ponumber.'"></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DUE_DATE'].'</td><td><input type="text" name="duedate" maxlength="20" size="30" value="'.createtime("Y-m-d").'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.duedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="images/calendar.gif" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_REQUISITION_NUMBER'].'</td><td><input type="text" name="requisition" maxlength="20" size="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVENTORY_LOCATION'].':</td><td><select name="locationid"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select inventorylocation.id,company.companyname from company,inventorylocation where company.id=inventorylocation.companyid and company.cancel=0 and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
          while ($recordSet&&!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHIPPING_METHOD'].':</td><td><select name="carrierserviceid"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select carrierservice.id,company.companyname,carrierservice.description from company,carrier,carrierservice where company.id=carrier.companyid and carrierservice.carrierid=carrier.id and company.cancel=0 order by company.companyname,carrierservice.description');
          while ($recordSet&&!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TRACKING_NUMBER'].'</td><td><input type="text" name="tracknumber" maxlength="20" size="30"'.INC_TEXTBOX.'></td></tr>';
          return 1;
     };

     function forminvpoupdate($vendorid,$contact,$ordernumber,$ponumber,$duedate,$requisition,$locationid,$carrierserviceid,$tracknumber) {
          global $conn, $lang,$active_company;
          $recordSet = &$conn->Execute('select count(*) from company,vendor where company.id=vendor.orderfromcompanyid and company.cancel=0 and vendor.gencompanyid='.sqlprep($active_company));
          if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]>1) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VENDOR'].':</td><td><select name="vendorid"'.INC_TEXTBOX.'>';
               $recordSet = &$conn->Execute('select vendor.id,company.companyname from company,vendor where company.id=vendor.orderfromcompanyid and company.cancel=0 and vendor.gencompanyid='.sqlprep($active_company).' order by company.companyname');
               while ($recordSet&&!$recordSet->EOF) {
                    echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],$vendorid," selected").'>'.rtrim($recordSet->fields[1])."\n";
                    $recordSet->MoveNext();
               };
               echo '</select></td></tr>';
          } else {
               $recordSet = &$conn->Execute('select vendor.id from company,vendor where company.id=vendor.orderfromcompanyid and company.cancel=0 and vendor.gencompanyid='.sqlprep($active_company).' order by company.companyname');
                echo '<input type="hidden" name="vendorid" value="'.$recordSet->fields[0].'">';
         };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CONTACT'].'</td><td><input type="text" name="contact" maxlength="20" value="'.$contact.'" size="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ORDER_NUMBER'].'</td><td><input type="text" name="ordernumber" maxlength="20" value="'.$ordernumber.'" size="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PO_NUMBER'].'</td><td><input type="text" name="ponumber" maxlength="20" value="'.$ponumber.'" size="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DUE_DATE'].'</td><td><input type="text" name="duedate" maxlength="20" size="30" value="'.$duedate.'" value="'.createtime("Y-m-d").'"'.INC_TEXTBOX.'><a href="javascript:show_calendar(\'mainform.duedate\');" onMouseOver="window.status=\'Date Picker\';" onMouseOut="window.status=\'\'; nd(); return true;"><img src="images/calendar.gif" border="0" alt="Display Calendar"></a></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_REQUISITION_NUMBER'].'</td><td><input type="text" name="requisition" maxlength="20" value="'.$requisition.'" size="30"'.INC_TEXTBOX.'></td></tr>';
          $recordSet = &$conn->Execute('select count(*) from company,inventorylocation where company.id=inventorylocation.companyid and company.cancel=0 and inventorylocation.gencompanyid='.sqlprep($active_company));
          if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]>1) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVENTORY_LOCATION'].':</td><td><select name="locationid"'.INC_TEXTBOX.'>';
               $recordSet = &$conn->Execute('select inventorylocation.id,company.companyname, inventorylocation.companyid from company,inventorylocation where company.id=inventorylocation.companyid and company.cancel=0 and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
               while ($recordSet&&!$recordSet->EOF) {
                    echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[2],$locationid," selected").'>'.rtrim($recordSet->fields[1])."\n";
                    $recordSet->MoveNext();
               };
               echo '</select></td></tr>';
         } else {
               $recordSet = &$conn->Execute('select inventorylocation.id from company,inventorylocation where company.id=inventorylocation.companyid and company.cancel=0 and inventorylocation.gencompanyid='.sqlprep($active_company).' order by company.companyname');
                echo '<input type="hidden" name="locationid" value="'.$recordSet->fields[0].'">';
         };
          $recordSet = &$conn->Execute('select count(*) from company,carrier,carrierservice where company.id=carrier.companyid and carrierservice.carrierid=carrier.id and company.cancel=0');
          if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]>1) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SHIPPING_METHOD'].':</td><td><select name="carrierserviceid"'.INC_TEXTBOX.'>';
               $recordSet = &$conn->Execute('select carrierservice.id,company.companyname,carrierservice.description from company,carrier,carrierservice where company.id=carrier.companyid and carrierservice.carrierid=carrier.id and company.cancel=0 order by company.companyname,carrierservice.description');
               while ($recordSet&&!$recordSet->EOF) {
                    echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],$carrierserviceid," selected").'>'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2])."\n";
                    $recordSet->MoveNext();
               };
               echo '</select></td></tr>';
         } else {
               $recordSet = &$conn->Execute('select carrierservice.id from company,carrier,carrierservice where company.id=carrier.companyid and carrierservice.carrierid=carrier.id and company.cancel=0 order by company.companyname,carrierservice.description');
               echo '<input type="hidden" name="carrierserviceid" value="'.$recordSet->fields[0].'">';
         };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TRACKING_NUMBER'].'</td><td><input type="text" name="tracknumber" maxlength="20" value="'.$tracknumber.'" size="30"'.INC_TEXTBOX.'></td></tr>';
          return 1;
     };



     //function for deleting item
     function itemDelete($itemcode,$delete) {
          global $conn, $lang, $userid, $active_company;
          checkpermissions('inv');
               if (!$delete) { // set to UNDELETE
                      if ($conn->Execute("update item set cancel='0', canceldate=NULL, canceluserid=NULL where itemcode=".sqlprep($itemcode)." and companyid=".sqlprep($active_company)) === false) {
                           echo texterror($lang['STR_ITEM_NOT_FOUND']);
                           return 0;
                      } else {
                           return 1;
                      };
              } else { // update userid and date for cancel
                      if ($conn->Execute('update item set cancel="1", canceldate=NOW(), canceluserid='.sqlprep($userid).' where itemcode='.sqlprep($itemcode).' and companyid='.sqlprep($active_company)) === false) {
                           echo texterror($lang['STR_ITEM_NOT_FOUND']);
                           return 0;
                      } else {
                           return 1;
                      };
              };
     };

     // function for adding/updating item data
      function itemAddUpdate($AddUpdate,$itemcode,$description,$categoryid, $stockunitnameid, $priceunitnameid, $lbsperpriceunit, $priceunitsperstockunit, $inventoryglacctid, $composityesno, $catalogdescription, $catalogsheeturl, $graphicurl,$companyid,$cancel,$id,$salesglacctid,$lastchangedate) {
          // $AddUpdateDelete should be set as follows:
          //  1=Add New Item
          //  0=Update Data on Item
          global $conn, $lang, $userid, $active_company;
          checkpermissions('inv');
          if ($AddUpdate) { // Add New Item after make sure not already exists
             $recordSet=&$conn->Execute("select itemcode from item where itemcode=".sqlprep($itemcode).' and companyid='.sqlprep($active_company));
             if ($recordSet&&!$recordSet->EOF) {
                                //aleady have item - quit now without adding
                                echo texterror($lang['STR_ITEM_ALREADY_EXISTS']);
                                return 0;
             } else { //insert new item into file
                                 if ($conn->Execute('insert into item (itemcode, description, categoryid, stockunitnameid, priceunitnameid, lbsperpriceunit, priceunitsperstockunit, inventoryglacctid,compositeitemyesno, catalogdescription, catalogsheeturl, graphicurl,salesglacctid,companyid,entrydate,entryuserid) VALUES ('.sqlprep($itemcode).", ".sqlprep($description).", ".sqlprep($categoryid).", ".sqlprep($stockunitnameid).", ".sqlprep($priceunitnameid).", ".sqlprep($lbsperpriceunit).", ".sqlprep($priceunitsperstockunit).", ".sqlprep($inventoryglacctid).", ".sqlprep($composityesno).",".sqlprep($catalogdescription).",".sqlprep($catalogsheeturl).",".sqlprep($graphicurl).",".sqlprep($salesglacctid).",".sqlprep($active_company).",NOW(),".sqlprep($userid).")") === false) {
                                      echo texterror($lang['STR_ERROR_ADDING_ITEM']);
                                      return 0;
                                 } else {
                                      return 1;
                                 };
             };
           } else {
             $recordSet=&$conn->Execute("select count(*) from item where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate));
             if ($recordSet&&!$recordSet->EOF) {
               if (!$recordSet->fields[0]) {
                    showwhochanged($id,"item","id");
                    return 0;
               } else {
                    if ($conn->Execute('update item set itemcode='.sqlprep($itemcode).", compositeitemyesno=".sqlprep($composityesno).", description=".sqlprep($description).", categoryid=".sqlprep($categoryid).", stockunitnameid=".sqlprep($stockunitnameid).", priceunitnameid=".sqlprep($priceunitnameid).", lbsperpriceunit=".sqlprep($lbsperpriceunit).", priceunitsperstockunit=".sqlprep($priceunitsperstockunit).", inventoryglacctid=".sqlprep($inventoryglacctid).", salesglacctid=".sqlprep($salesglacctid).", catalogdescription=".sqlprep($catalogdescription).", catalogsheeturl=".sqlprep($catalogsheeturl).", graphicurl=".sqlprep($graphicurl).", lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_ITEM']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
             };
             echo texterror($lang['STR_ITEM_NOT_FOUND']);
             return 0;
          };
      }; ///// end of Item Update/Add function



      //function for deleting item location info
      function invitemlocationdelete($delete,$id,$inventorylocationid) {
               global $conn, $lang, $userid, $active_company;
               checkpermissions('inv');
               if ($delete == 1) { // active item, cancel now  .
                    $query='update itemlocation  set cancel=1, canceldate=NOW(), canceluserid='.sqlprep($userid).', lastchangedate=NOW(), lastchangeuserid='.sqlprep($userid).' where inventorylocationid='.sqlprep($inventorylocationid).' and itemid='.sqlprep($id);
               } else { //activate cancelleted item   .
                    $query='update itemlocation  set cancel=0, canceldate=NULL, canceluserid=NULL, lastchangedate=NOW(), lastchangeuserid='.sqlprep($userid).' where inventorylocationid='.sqlprep($inventorylocationid).' and itemid='.sqlprep($id);
               };
               if ($conn->Execute($query) === false) {
                    echo texterror($lang['STR_ERROR_UPDATING_ITEM_LOCATION']);
                    return 0;
               } else {
                    echo textsuccess($lang['STR_ITEM_LOCATION_UPDATED_SUCCESSFULLY']);
                    return 1;
               };
      }; // end item location delete



      //function for adding/updating item location
      function invitemlocationaddupdate($addupdate,$itemid,$inventorylocationid,$onhandqty,$maxstocklevelseason1,$minstocklevelseason1,$orderqtyseason1,$maxstocklevelseason2,$minstocklevelseason2,$orderqtyseason2,$maxstocklevelseason3,$minstocklevelseason3,$orderqtyseason3,$maxstocklevelseason4,$minstocklevelseason4,$orderqtyseason4,$markupsetid,$id) {
          global $conn, $lang,$userid,$active_company;
          checkpermissions('inv');
           //first make sure location for this item not already in file
           $recordSet=&$conn->Execute('select * from itemlocation where inventorylocationid='.sqlprep($inventorylocationid).' and itemid='.sqlprep($id));
           if ($recordSet&&!$recordSet->EOF) {
                 // already in file
                 if (!$addupdate) { // we are trying to add a new one!! $addupdate=0
                       echo texterror($lang['STR_ITEM_LOCATION_ALREADY_EXISTS']) ;
                       return 0;
                 } else {
                       //need to update information
                       $query='update itemlocation set itemid='.sqlprep($id);
                       $query=$query.', maxstocklevelseason1='.sqlprep($maxstocklevelseason1).', minstocklevelseason1='.sqlprep($minstocklevelseason1).', orderqtyseason1='.sqlprep($orderqtyseason1);
                       $query=$query.', maxstocklevelseason2='.sqlprep($maxstocklevelseason2).', minstocklevelseason2='.sqlprep($minstocklevelseason2).', orderqtyseason2='.sqlprep($orderqtyseason2);
                       $query=$query.', maxstocklevelseason3='.sqlprep($maxstocklevelseason3).', minstocklevelseason3='.sqlprep($minstocklevelseason3).', orderqtyseason3='.sqlprep($orderqtyseason3);
                       $query=$query.', maxstocklevelseason4='.sqlprep($maxstocklevelseason4).', minstocklevelseason4='.sqlprep($minstocklevelseason4).', orderqtyseason4='.sqlprep($orderqtyseason4);
                       $query=$query.', markupsetid='.sqlprep($markupsetid).', lastchangedate=NOW(), lastchangeuserid='.sqlprep($userid).' where  inventorylocationid='.sqlprep($inventorylocationid).' and itemid='.sqlprep($id);
                       if ($conn->Execute($query) === false) {
                            echo texterror($lang['STR_ERROR_UPDATING_ITEM_LOCATION']);
                            return 0;
                       } else {
                            return 1;
                       };
                 };
           } else {
                   //insert new record into file
                 $query='insert into itemlocation (itemid, inventorylocationid, onhandqty, maxstocklevelseason1, minstocklevelseason1, orderqtyseason1';
                 $query=$query.', maxstocklevelseason2, minstocklevelseason2, orderqtyseason2';
                 $query=$query.', maxstocklevelseason3, minstocklevelseason3, orderqtyseason3';
                 $query=$query.', maxstocklevelseason4, minstocklevelseason4, orderqtyseason4';
                 $query=$query.', markupsetid, firstcost, midcost, lastcost, entrydate, entryuserid, lastchangeuserid) VALUES ('.sqlprep($id).",".sqlprep($inventorylocationid).",".sqlprep($onhandqty).",".sqlprep($maxstocklevelseason1).",".sqlprep($minstocklevelseason1).",".sqlprep($orderqtyseason1).",";
                 $query=$query.sqlprep($maxstocklevelseason2).",".sqlprep($maxstocklevelseason2).",".sqlprep($orderqtyseason2).",";
                 $query=$query.sqlprep($maxstocklevelseason3).",".sqlprep($minstocklevelseason3).",".sqlprep($orderqtyseason3).",";
                 $query=$query.sqlprep($maxstocklevelseason4).",".sqlprep($minstocklevelseason4).",".sqlprep($orderqtyseason4).",";
                 $query=$query.sqlprep($markupsetid).", '0', '0', '0', NOW(),".sqlprep($userid).",".sqlprep($userid).')';
                 if ($conn->Execute($query) === false) {
                     echo texterror($lang['STR_ERROR_ADDING_ITEM_LOCATION']);
                     return 0;
                 } else {
                     return 1;
                 };
            };
      };



      function invitemlocationprice($addupdate,$itemid,$inventorylocationid,$id,$price,$pricelevelid){
          global $conn, $lang, $userid, $active_company;
          $recordSet=&$conn->Execute('select * from priceperpriceunit where itemid='.sqlprep($id).' and  itemlocationid='.sqlprep($inventorylocationid).' and pricelevelid='.sqlprep($pricelevelid));
          if ($recordSet&&!$recordSet->EOF) { //update info, already have in file
             if ($addupdate) $query='update priceperpriceunit set price='.sqlprep($price).',  lastchangedate=NOW(), lastchangeuserid='.sqlprep($userid).' where itemid='.sqlprep($id).' and  itemlocationid='.sqlprep($inventorylocationid).' and pricelevelid='.sqlprep($pricelevelid);
          } elseif (!checkzero($price)) { //insert into file - new data  but only if price not zero
             $query='insert into priceperpriceunit (itemid, itemlocationid, pricelevelid, price, cancel, entrydate, entryuserid, lastchangeuserid) VALUES ('.sqlprep($id).", ".sqlprep($inventorylocationid).", ".sqlprep($pricelevelid).", ".sqlprep($price).', 0, NOW(), '.sqlprep($userid).', '.sqlprep($userid).')';
          };
          if ($query) {
             if ($conn->Execute($query) === false) {
                  echo texterror($lang['STR_ERROR_ADDING_ITEM_LOCATION_PRICING']);
                  return 0;
             };
          };
          return 1;
      };


      function invitemlocationdiscount($addupdate,$id,$inventorylocationid,$discount,$quantity) {
          global $conn, $lang, $userid, $active_company;
          checkpermissions('inv');
          $recordSet=&$conn->Execute('select * from pricediscount where itemid='.sqlprep($id).' and  itemlocationid='.sqlprep($inventorylocationid));
          if ($recordSet&&!$recordSet->EOF) { //update info, already have in file
             if ($addupdate) $query='update pricediscount set discount='.sqlprep($discount).', quantity='.sqlprep($quantity).', lastchangedate=NOW(), lastchangeuserid='.sqlprep($userid).' where itemid='.sqlprep($id).' and  itemlocationid='.sqlprep($inventorylocationid);
          } else { //insert into file - new data
             $query='insert into pricediscount (itemid, itemlocationid, quantity, discount, entrydate, entryuserid, lastchangeuserid) VALUES ('.sqlprep($id).", ".sqlprep($inventorylocationid).", ".sqlprep($quantity).", ".sqlprep($discount).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).')';
          };
          if ($query) {
             if ($conn->Execute($query) === false) {
                  echo texterror($lang['STR_ERROR_ADDING_ITEM_LOCATION_PRICING_DISCOUNTS']);
                  return 0;
             };
          };
          return 1;
      };


      function CompositAddUpdate($addupdate,$id,$subitemcodeid,$quantity) {
          global $conn, $lang, $userid, $active_company;
          checkpermissions('inv');
              if ($subitemcodeid<>$id) {//only save if not same code as main item
                   $recordSet=&$conn->Execute('select cancel from compositeitemid where subitemcodeid='.sqlprep($subitemcodeid).' and itemcodeid='.sqlprep($id));
                   if ($recordSet&&!$recordSet->EOF) {
                         //already in file
                         if (!$addupdate) { //in ADD mode, cancel the entry if it exists
                               //or if already cancelled, activate it
                               if (!$recordSet->fields[0]) { // active item, cancel now
                                    $query='update compositeitemid  set quantity=0, cancel=1, canceldate=NOW(), canceluserid='.sqlprep($userid).', lastchangedate=NOW(), lastchangeuserid='.sqlprep($userid).' where subitemcodeid='.sqlprep($subitemcodeid).' and itemcodeid='.sqlprep($id);
                               } else { // cancelled item, update to active now
                                    $query='update compositeitemid  set quantity='.sqlprep($quantity).', cancel=0, canceldate=NULL, canceluserid=NULL, lastchangedate=NOW(), lastchangeuserid='.sqlprep($userid).' where subitemcodeid='.sqlprep($subitemcodeid).' and itemcodeid='.sqlprep($id);
                               };
                               if ($conn->Execute($query) === false) {
                                    echo texterror($lang['STR_ERROR_UPDATING_ITEM_COMPOSITE']);
                                    return 0;
                               } else {
                                    return 1;
                               };
                         };
                   } else {
                         if ($conn->Execute('insert into compositeitemid (itemcodeid,subitemcodeid,quantity,entrydate,entryuserid,lastchangeuserid) VALUES ('.sqlprep($id).",".sqlprep($subitemcodeid).",".sqlprep($quantity).",NOW(),".sqlprep($userid).",".sqlprep($userid).')') === false) {
                              echo texterror($lang['STR_ERROR_ADDING_ITEM_COMPOSITE']);
                              return 0;
                         } else {
                              return 1;
                         };
                   };
              };
       };

     function invitemprice($itemid, $markupset, $pricelevel, $quantity) {
          global $conn, $lang;
          if ($markupset) {
               $recordSet = &$conn->Execute('select itemlocation.firstcost, itemlocation.midcost, itemlocation.lastcost,costbased,markupsetlevel.markuppercent from itemlocation, markupset, markupsetlevel where itemlocation.itemid='.sqlprep($itemid).' and itemlocation.markupsetid=markupsetlevel.markupsetid and markupset.id=itemlocation.markupsetid');
               if (!$recordSet->EOF) {
                    $costbased=$recordSet->fields[3];
                    if ($costbased==1) { //first cost
                         $price=$recordSet->fields[0];
                    } elseif ($costbased==2) { //mid
                         $price=$recordSet->fields[1];
                    } elseif ($costbased==3) { //last cost
                         $price=$recordSet->fields[2];
                    };
                    $markup=$recordSet->fields[4]/100 + 1;
                    $price=$price*$markup;
               } else {
                    return 0;
               };
          } else {
               $recordSet = &$conn->Execute('select priceperpriceunit.price from priceperpriceunit where priceperpriceunit.itemid='.sqlprep($itemid).' and priceperpriceunit.pricelevelid='.sqlprep($pricelevel));
               if ($recordSet&&!$recordSet->EOF) {
                     $price=$recordSet->fields[0];
               } else {
                     return 0;
               };
          };
          if ($price) {
               $recordSet = &$conn->Execute('select max(discount) from pricediscount where itemid='.sqlprep($itemid).' and quantity<='.sqlprep($quantity));
               if ($recordSet&&!$recordSet->EOF) if ($recordSet->fields[0]) $price-=$price*($recordSet->fields[0]/100);
          };
          return $price;
     };

     function invitemlocationfirstmidlastupdate($id,$locationid,$itemqty,$itemprice) {
          global $conn, $lang, $userid, $active_company;
          $recordSet=&$conn->Execute('select firstcost,midcost,lastcost,firstqty,midqty,lastqty,id from itemlocation where inventorylocationid='.sqlprep($locationid).' and itemid='.sqlprep($id));
          if ($recordSet&&!$recordSet->EOF) { //echo 'found item location';
               $firstcost=$recordSet->fields[0];
               $midcost=$recordSet->fields[1];
               $lastcost=$recordSet->fields[2];
               $firstqty=$recordSet->fields[3];
               $midqty=$recordSet->fields[4];
               $lastqty=$recordSet->fields[5];
               $itemlocationid=$recordSet->fields[6];
               if ($firstqty==0) {
                    $firstqty=$midqty;
                    $firstcost=$midcost;
                    $midqty=$lastqty;
                    $midcost=$lastcost;
               } elseif ($midqty==0) {
                    $midqty=$lastqty;
                    $midcost=$lastcost;
               } elseif ($lastqty==0) {
                    //nothing now
               } else {
                    $midcost=($midqty*$midcost+$lastqty*$lastcost)/($midqty+$lastqty);
                    $midqty+=$lastqty;
               };
               $lastqty=$itemqty;
               $lastcost=$itemprice;
               if ($conn->Execute('update itemlocation set firstcost='.sqlprep($firstcost).', midcost='.sqlprep($midcost).', lastcost='.sqlprep($lastcost).', firstqty='.sqlprep($firstqty).', midqty='.sqlprep($midqty).', lastqty='.sqlprep($lastqty).', lastchangedate=NOW(), lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($itemlocationid)) === false) {
                    echo texterror($lang['STR_ERROR_UPDATING_ITEM_LOCATION_COST']);
                    return 0;
               } else {
                    return 1;
               };
          } else { //did not find location, so need to create entry for this item
            return invitemlocationaddupdate(0,$itemid,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,$id);
          };
     };
