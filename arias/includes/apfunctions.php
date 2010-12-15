<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     function apcompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name,$mailstop) {
          global $conn, $lang, $userid;
          checkpermissions('ap');
          if (!$city&&!$state&&$zip) {
               $cityst=getaddress($zip);
               $city=substr($cityst,0,strlen($cityst)-4);
               $state=substr($cityst,-2);
          };
          if ($conn->Execute("insert into company (address1, address2, city, state, zip, country, phone1, phone1comment, phone2, phone2comment, phone3, phone3comment, phone4, phone4comment, email1, email1comment, email2, email2comment, website, federalid, companyname, entrydate, entryuserid, lastchangeuserid,mailstop) VALUES (".sqlprep($address1).", ".sqlprep($address2).", ".sqlprep($city).", ".sqlprep($state).", ".sqlprep($zip).", ".sqlprep($country).", ".sqlprep($phone1).", ".sqlprep($phone1comment).", ".sqlprep($phone2).", ".sqlprep($phone2comment).", ".sqlprep($phone3).", ".sqlprep($phone3comment).", ".sqlprep($phone4).", ".sqlprep($phone4comment).", ".sqlprep($email1).", ".sqlprep($email1comment).", ".sqlprep($email2).", ".sqlprep($email2comment).", ".sqlprep($website).", ".sqlprep($federalid).", ".sqlprep($name).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).", ".sqlprep($mailstop).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_COMPANY']);
               return 0;
          } else {
               return 1;
          };
     };

     function apcompanyupdate($id, $address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $companyname,$mailstop,$lastchangedate) {
          global $conn, $lang, $userid;
          checkpermissions('ap');
          $recordSet=&$conn->Execute("select count(*) from company where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate));
          if (!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"company","id");
                    return 0;
               } else {
                    if (!$city&&!$state&&$zip) {
                        $cityst=getaddress($zip);
                        $city=substr($cityst,0,strlen($cityst)-4);
                        $state=substr($cityst,-2);
                    };
                    if ($conn->Execute("update company set address1=".sqlprep($address1).", address2=".sqlprep($address2).", city=".sqlprep($city).", state=".sqlprep($state).", zip=".sqlprep($zip).", country=".sqlprep($country).", phone1=".sqlprep($phone1).", phone1comment=".sqlprep($phone1comment).", phone2=".sqlprep($phone2).", phone2comment=".sqlprep($phone2comment).", phone3=".sqlprep($phone3).", phone3comment=".sqlprep($phone3comment).", phone4=".sqlprep($phone4).", phone4comment=".sqlprep($phone4comment).", email1=".sqlprep($email1).", email1comment=".sqlprep($email1comment).", email2=".sqlprep($email2).", email2comment=".sqlprep($email2comment).", website=".sqlprep($website).", companyname=".sqlprep($companyname).", federalid=".sqlprep($federalid).", lastchangeuserid=".sqlprep($userid).", mailstop=".sqlprep($mailstop)." where id=".sqlprep($id).' and lastchangedate='.sqlprep($lastchangedate)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_COMPANY']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
          } else {
               echo texterror($lang['STR_ERROR_UPDATING_COMPANY']);
               return 0;
          };
      };


     function apcompanydelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('ap');
          if ($conn->Execute("update company set cancel='1', canceldate=NOW(), canceluserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_COMPANY']);
               return 0;
          } else {
               return 1;
          };
     };

     function apvendoradd($paytocompanyid, $orderfromcompanyid, $orderfromname, $paytermsid, $paynone, $defaultglacctid, $defaultbilldescription, $customeraccount) {
          global $conn, $lang, $userid,$active_company;
          checkpermissions('ap');
          if ($conn->Execute("insert into vendor (paytocompanyid, orderfromcompanyid, orderfromname, paytermsid, paynone, defaultglacctid, defaultbilldescription, customeraccount, entrydate, entryuserid, lastchangeuserid,gencompanyid) VALUES (".sqlprep($paytocompanyid).", ".sqlprep($orderfromcompanyid).", ".sqlprep($orderfromname).", ".sqlprep($paytermsid).", ".sqlprep($paynone).", ".sqlprep($defaultglacctid).", ".sqlprep($defaultbilldescription).", ".sqlprep($customeraccount).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).", ".sqlprep($active_company).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_VENDOR']);
               return 0;
          } else {
               return 1;
          };
     };


     function apvendorupdate($id, $paytocompanyid, $orderfromcompanyid, $orderfromname,$paytermsid, $paynone, $defaultglacctid, $defaultbilldescription, $customeraccount,$lastchangevendordate) {
          global $conn, $lang, $userid;
          checkpermissions('ap');
          $recordSet=&$conn->Execute("select count(*) from vendor where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangevendordate));
          if (!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"vendor","id");
                    return 0;
               } else {
                    if ($conn->Execute("update vendor set paytocompanyid=".sqlprep($paytocompanyid).", orderfromcompanyid=".sqlprep($orderfromcompanyid).", orderfromname=".sqlprep($orderfromname).", paytermsid=".sqlprep($paytermsid).", paynone=".sqlprep($paynone).", defaultglacctid=".sqlprep($defaultglacctid).", defaultbilldescription=".sqlprep($defaultbilldescription).", customeraccount=".sqlprep($customeraccount).", lastchangeuserid=".sqlprep($userid)." where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangevendordate)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_VENDOR']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
          } else {
               echo texterror($lang['STR_ERROR_UPDATING_VENDOR']);
               return 0;
          };
     };

     function apvendordelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('ap');
          if ($conn->Execute("update vendor set cancel='1', canceldate=NOW(), canceluserid=".sqlprep($userid)." where orderfromcompanyid=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_VENDOR']);
               return 0;
          } else {
               return 1;
          };
     };

     function apvendordefaultbilldescription($vendorid) {
          global $conn, $lang, $active_company;
          $recordSet=&$conn->Execute("select defaultbilldescription from vendor where id=".sqlprep($vendorid)." and companyid=".sqlprep($active_company));
          if (!$recordSet->EOF) return rtrim($recordSet->fields[0]);
     };

     function appaytermsadd($verbal, $discountpercent, $discountdays, $netduedays,$discountdayofmonth) {
          global $conn, $lang, $userid;
          checkpermissions('ap');
          if ($discountdays>0) $discountdayofmonth=0;
          if ($conn->Execute("insert into invoiceterms (verbal, discountpercent, discountdays, discountdayofmonth,netduedays, ap, entrydate, entryuserid, lastchangeuserid) VALUES (".sqlprep($verbal).", ".sqlprep($discountpercent).", ".sqlprep($discountdays).", ".sqlprep($discountdayofmonth).", ".sqlprep($netduedays).", '1', NOW(), '".$userid."', '".$userid."')") === false) {
                     echo texterror($lang['STR_ERROR_ADDING_INVOICE_TERMS']);
               return 0;
          } else {
                     echo textsuccess($lang['STR_INVOICE_TERMS_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function appaytermsupdate($id, $verbal, $discountpercent, $discountdays, $netduedays,$discountdayofmonth) {
          global $conn, $lang, $userid;
          checkpermissions('ap');
          if ($discountdays>0) $discountdayofmonth=0;
          if ($conn->Execute("update invoiceterms set verbal=".sqlprep($verbal).", discountpercent=".sqlprep($discountpercent).", discountdayofmonth=".sqlprep($discountdayofmonth).", discountdays=".sqlprep($discountdays).", netduedays=".sqlprep($netduedays).", lastchangeuserid='".$userid."' where id=".sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_INVOICE_TERMS']);
               return 0;
          } else {
                         echo textsuccess($lang['STR_INVOICE_TERMS_UPDATED_SUCCESSFULLY']);
               return 1;
          };
     };

     function apchkacctadd($name,$glaccountid,$lastchecknumberused,$defaultendorser,$ap,$pay) {
          global $conn, $lang, $active_company;
          checkpermissions('ap');
          if ($conn->Execute("insert into checkacct (name,glaccountid,lastchecknumberused,defaultendorser,ap,pay,gencompanyid) VALUES (".sqlprep($name).", ".sqlprep($glaccountid).", ".sqlprep($lastchecknumberused).", ".sqlprep($defaultendorser).", ".sqlprep($ap).", ".sqlprep($pay).", ".sqlprep($active_company).")") === false) {
                     echo texterror($lang['STR_ERROR_ADDING_CHECKING_ACCOUNT']);
               return 0;
          } else {
                     echo textsuccess($lang['STR_CHECKING_ACCOUNT_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function apchkacctupdate($id, $name, $glaccountid, $lastchecknumberused, $defaultendorser, $ap, $pay) {
          global $conn, $lang, $active_company;
          checkpermissions('ap');
          if ($conn->Execute("update checkacct set name=".sqlprep($name).", glaccountid=".sqlprep($glaccountid).", lastchecknumberused=".sqlprep($lastchecknumberused).", defaultendorser=".sqlprep($defaultendorser).", ap=".sqlprep($ap).", pay=".sqlprep($pay).", gencompanyid='".$active_company."' where id=".sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_CHECKING_ACCOUNT']);
               return 0;
          } else {
                         echo textsuccess($lang['STR_CHECKING_ACCOUNT_UPDATED_SUCCESSFULLY']);
               return 1;
          };
     };

     function apchkacctdelete($id) {
          global $conn, $lang, $active_company;
          checkpermissions('ap');
          if ($conn->Execute("delete from checkacct where gencompanyid=".sqlprep($active_company)." and id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_CHECKING_ACCOUNT']);
               return 0;
          } else {
               return 1;
          };
     };

     function appaytermsdelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('ap');
          $recordSet = &$conn->Execute('select ar from invoiceterms where id='.sqlprep($id));
          if (!$recordSet->EOF) {
               if ($recordSet->fields[0]) { //if we're using this for ap too, just set ar=0
                    if ($conn->Execute("update invoiceterms set ap='0', lastchangedate=NOW(), lastchangeuserid='".$userid."' where id=".sqlprep($id)) === false) {
                               echo texterror($lang['STR_ERROR_DELETING_INVOICE_TERMS']);
                         return 0;
                    } else {
                               echo textsuccess($lang['STR_INVOICE_TERMS_DELETED_SUCCESSFULLY']);
                         return 1;
                    };
               } else { //set cancel=1
                    if ($conn->Execute("update invoiceterms set cancel='1', canceldate=NOW(), canceluserid='".$userid."' where id=".sqlprep($id)) === false) {
                               echo texterror($lang['STR_ERROR_DELETING_INVOICE_TERMS']);
                         return 0;
                    } else {
                               echo textsuccess($lang['STR_INVOICE_TERMS_DELETED_SUCCESSFULLY']);
                         return 1;
                    };
               };
          } else {
               return 0;
          };
     };


     function ReceiveAddUpdate($AddUpdate,$id,$itemid,$vendorid,$recsource,$invpoid,$receivedate,$itemqty,$itemprice,$conversion,$track,$recunitnameid,$lastchangedate,$glacct,$composit,$locationid) {
          global $conn, $lang, $userid, $active_company;
          if ($AddUpdate) { // Add New Receipt after make sure not already exists
             $recordSet=&$conn->Execute("select id from invreceive where vendorid=".sqlprep($vendorid)." and itemid=".sqlprep($itemid)." and receivedate=".sqlprep($today)." and track=".sqlprep($track)." and gencompanyid=".sqlprep($active_company));
             if (!$recordSet->EOF) {
                //aleady have receipt same vendor, same date, same item, same tracking - quit now without adding
                echo texterror($lang['STR_RECEIPT_FOR_THIS_ITEM_ALREADY_IN_FILE']);
                return 0;
             } else { //insert new receipt into file
                if ($conn->Execute('insert into invreceive (recsource,invpoid,receivedate,vendorid,itemid,itemqty,itemprice,itemqtyused,conversion,track,receiveunitnameid,locationid,entrydate,entryuserid,lastchangeuserid, gencompanyid) values ('.sqlprep($recsource).", ".sqlprep($invpoid).", ".sqlprep($receivedate).", ".sqlprep($vendorid).", ".sqlprep($itemid).", ".sqlprep($itemqty).", ".sqlprep($itemprice).", ".sqlprep($itemqtyused).", ".sqlprep($conversion).", ".sqlprep($track).", ".sqlprep($recunitnameid).", ".sqlprep($locationid).",  NOW(),".sqlprep($userid).", ".sqlprep($userid).", ".sqlprep($active_company).')') === false) {
                   echo texterror($lang['STR_ERROR_ADDING_RECEIPT_FOR_THIS_ITEM']);
                   return 0;
                } else {
                   return 1;
                };
             };
          } else {
             $recordSet=&$conn->Execute("select count(*) from invreceive where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate));
             if (!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"invreceive","id");
                    return 0;
               } else {
                    if ($conn->Execute("update invreceive set receivedate=".sqlprep($receivedate).", vendorid=".sqlprep($vendorid).", itemid=".sqlprep($itemid).", itemqty=".sqlprep($itemqty).", itemprice=".sqlprep($itemprice).", itemqtyused=".sqlprep($itemqtyused).", conversion=".sqlprep($conversion).", track=".sqlprep($track).", receiveunitnameid=".sqlprep($receiveunitnameid).", locationid=".sqlprep($locationid).", lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_RECEIPT_FOR_THIS_ITEM']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
            };
          };




     };

     function billadd($invoicenumber,$total,$description,$dateofinvoice,$duedate,$discountamount,$discountdate,$vendorid,$wherefrom,$cancel) {
          global $conn, $lang,$userid,$active_company;
          checkpermissions('ap');
          $recordSet=&$conn->Execute('select id from apbill where invoicenumber='.sqlprep($invoicenumber).' and vendorid='.sqlprep($vendorid));
          if (!$recordSet||$recordSet->EOF) {
            if ($conn->Execute('insert into apbill (invoicenumber,total,description,dateofinvoice,duedate,discountamount,discountdate,vendorid,wherefrom,cancel,gencompanyid,entrydate,entryuserid,lastchangeuserid) values ('.sqlprep($invoicenumber).", ".sqlprep($total).", ".sqlprep($description).", ".sqlprep($dateofinvoice).", ".sqlprep($duedate).", ".sqlprep($discountamount).", ".sqlprep($discountdate).", ".sqlprep($vendorid).', '.sqlprep($wherefrom).", ".sqlprep($cancel).", ".sqlprep($active_company).",  NOW(),".sqlprep($userid).", ".sqlprep($userid).')') === false) {
               echo texterror($lang['STR_ERROR_ADDING_BILL']);
               return 0;
            } else {
               $recordSet=&$conn->SelectLimit("select id from apbill where invoicenumber=".sqlprep($invoicenumber)." and gencompanyid=".sqlprep($active_company)." and entryuserid=".sqlprep($userid)." order by entrydate desc",1);
               if (!$recordSet->EOF) {
                    return $recordSet->fields[0];
               } else {
                    return 0;
               };
            };
          } else {
               echo texterror($lang['STR_BILL_WITH_THIS_INVOICE_NUMBER_ALREADY_ENTERED']);
            return 0;
          };
     };

     function billupd($id,$invoicenumber,$total,$description,$dateofinvoice,$duedate,$discountamount,$discountdate,$vendorid,$wherefrom,$cancel) {
          global $conn, $lang,$userid,$active_company;
          checkpermissions('ap');
          if ($conn->Execute('update apbill set invoicenumber='.sqlprep($invoicenumber).', total='.sqlprep($total).', description='.sqlprep($description).', dateofinvoice='.sqlprep($dateofinvoice).', duedate='.sqlprep($duedate).', discountdate='.sqlprep($discountdate).', vendorid='.sqlprep($vendorid).', wherefrom='.sqlprep($wherefrom).', lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_BILL']);
               return 0;
          } else {
               return $id;
          };
     };

     function billdelete($id) {
          global $conn, $lang,$userid,$active_company;
          checkpermissions('ap');
          if ($conn->Execute('update apbill set cancel=1,lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_BILL']);
               return 0;
          } else {
               return $id;
          };
     };

     function billdetailadd($apbillid,$amount,$glaccountid,$invreceiveid) {
          global $conn, $lang,$userid,$active_company;
          checkpermissions('ap');
          if ($conn->Execute('insert into apbilldetail (apbillid,amount,glaccountid,invreceiveid) values ('.sqlprep($apbillid).", ".sqlprep($amount).", ".sqlprep($glaccountid).", ".sqlprep($invreceiveid).')') === false) {
               echo texterror($lang['STR_ERROR_ADDING_BILL_DETAIL']);
               return 0;
          } else {
               $recordSet=&$conn->SelectLimit("select id from apbilldetail where apbillid=".sqlprep($apbillid)." order by id desc",1);
               if (!$recordSet->EOF) {
                    return $recordSet->fields[0];
               } else {
                    return 0;
               };
          };
     };
     function billdetailupd ($apbillid,$amount,$glaccountid,$id) {
          global $conn, $lang,$userid,$active_company;
          checkpermissions('ap');
          if ($conn->Execute('update apbilldetail set amount='.sqlprep($amount).', glaccountid='.sqlprep($glaccountid).' where id='.sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_BILL_DETAIL']);
               return 0;
          } else {
               return $id;
          };
     };


     function billdeleteadd($id) {
              global $conn, $lang;
              checkpermissions('ap');
              if ($conn->Execute('delete from apbill where id='.sqlprep($id)) === false) {
                   echo texterror($lang['STR_ERROR_DELETING_BILL']);
                   return 0;
              } else {
                   return $id;
              };
     };


     function billdeletebyid($id) {
          global $conn, $lang,$userid,$active_company;
          checkpermissions('ap');
          if ($conn->Execute('delete from apbilldetail where id='.sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_BILL_DETAIL']);
               return 0;
          } else {
               return $id;
          };
     };

     function billdeletebybillid($apbillid) {
          global $conn, $lang,$userid,$active_company;
          checkpermissions('ap');
          if ($conn->Execute('delete from apbilldetail where apbillid='.sqlprep($apbillid)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_BILL_DETAIL']);
               return 0;
          } else {
               return 1;
          };
     };

     function billdiscountamount($invoicetermsid,$amount) {
          global $conn, $lang;
          $recordSet=&$conn->Execute("select discountpercent from invoiceterms where id=".sqlprep($invoicetermsid));
          if (!$recordSet->EOF) {
               return checkdec($amount*($recordSet->fields[0]/100),2);
          } else {
               return checkdec($amount,2);
          };
     };

     function billdiscountdate($invoicetermsid,$dateofinvoice) {
          global $conn, $lang;
          //dateofinvoice needs to be in a timestamp format, either unix or mysql
          $recordSet=&$conn->Execute("select discountdays,discountdayofmonth from invoiceterms where id=".sqlprep($invoicetermsid));
          if ($recordSet&&!$recordSet->EOF&&($recordSet->fields[0]||$recordSet->fields[1])) {
               $year=substr($dateofinvoice,0,4);
               $month=substr($dateofinvoice,5,2);
               $day=substr($dateofinvoice,8,2);
               if ($recordSet->fields[0]) { //if the discount is a set # of days
                    $timestamp =  mktime(1, 1, 1, $month, $day+$recordSet->fields[0], $year);
               } else {
                    if ($day>=$recordSet->fields[1]) $month++;
                    $timestamp =  mktime(1, 1, 1, $month, $recordSet->fields[1], $year);
               };
               return date("Y-m-d", $timestamp);
          } else {
               return $dateofinvoice;
          };
     };

     function billduedate($invoicetermsid,$dateofinvoice) {
          global $conn, $lang;
          //dateofinvoice needs to be in a timestamp format, either unix or mysql
          $recordSet=&$conn->Execute("select netduedays from invoiceterms where id=".sqlprep($invoicetermsid));
          if (!$recordSet->EOF) {
               $year=substr($dateofinvoice,0,4);
               $month=substr($dateofinvoice,5,2);
               $day=substr($dateofinvoice,8,2);
               $timestamp =  mktime(1, 1, 1, $month, $day+$recordSet->fields[0], $year);
               return date("Y-m-d", $timestamp);
          } else {
               return $dateofinvoice;
          };
     };

     function formappaytermsadd() {
     	  global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VERBAL'].':</td><td><input type="text" name="verbal" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_PERCENT'].':</td><td><input type="text" onchange="validatenum(this)" name="discountpercent" size="30" maxlength="10"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_DAYS'].':</td><td><input type="text" onchange="validatenum(int)" name="discountdays" size="30" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_DAY_OF_MONTH_PROX'].':</td><td><input type="text" onchange="validateint(this)" name="discountdayofmonth" size="30" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NET_DUE_DAYS'].':</td><td><input type="text" name="netduedays" onchange="validateint(this)" size="30" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
          return 1;
     };

     function formappaytermsupdate($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select verbal, discountpercent, discountdays, netduedays,discountdayofmonth from invoiceterms where id='.$id);
          if ($recordSet&&!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VERBAL'].':</td><td><input type="text" name="verbal" size="30" maxlength="30" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_PERCENT'].':</td><td><input type="text" name="discountpercent" onchange="validatenum(this)" size="30" maxlength="10" value="'.$recordSet->fields[1].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_DAYS'].':</td><td><input type="text" name="discountdays" onchange="validateint(this)" size="30" maxlength="4" value="'.$recordSet->fields[2].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_DAY_OF_MONTH_PROX'].':</td><td><input type="text" name="discountdayofmonth" onchange="validateint(this)" value="'.$recordSet->fields[4].'" size="30" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NET_DUE_DAYS'].':</td><td><input type="text" name="netduedays" onchange="validateint(this)" size="30" maxlength="4" value="'.$recordSet->fields[3].'"'.INC_TEXTBOX.'></td></tr>';
               return 1;
          } else {
               return 0;
          };
     };

     function formappaytermsselect($name) {
          global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_TERMS'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id,verbal from invoiceterms where ap=1 and cancel=0 order by id');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

     function formapcompanyadd() {
     	  global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td></td><td><input type="text" name="address2" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAIL_STOP'].':</td><td><input type="text" name="mailstop" size="20" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td></td><th>'.$lang['STR_PHONE'].'</th><th>'.$lang['STR_PHONE_DESCRIPTION'].'</th></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" onChange="validatephone(this)"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="hidden" name="phone1comment" size="30" maxlength="20"  value="Telephone"'.INC_TEXTBOX.'>Telephone</td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FAX'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" onChange="validatephone(this)"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="hidden" name="phone2comment" size="30" maxlength="20" value="Fax"'.INC_TEXTBOX.'>Fax</td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE_THREE'].':</td><td><input type="text" name="phone3" size="30" maxlength="20" onChange="validatephone(this)"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="text" name="phone3comment" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE_FOUR'].':</td><td><input type="text" name="phone4" size="30" maxlength="20" onChange="validatephone(this)"'.INC_TEXTBOX.'></td>';
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

     function formapvendoradd() {
          global $conn, $lang, $active_company;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAYMENT_TERMS'].':</td><td><select name="paytermsid"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id, verbal from invoiceterms where cancel=0 and ap=1 order by id');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAY_NONE'].':</td><td><input type="checkbox" name="paynone" value="1"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEFAULT_GL_ACCOUNT'].':</td><td><select name="defaultglacctid"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id, name, description from glaccount where (companyid='.sqlprep($active_company).' or companyid=0) and (accounttypeid>=70 or (accounttypeid>20 and accounttypeid<24)) order by name');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEFAULT_BILL_DESCRIPTION'].':</td><td><input type="text" name="defaultbilldescription" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_ACCOUNT'].':</td><td><input type="text" name="customeraccount" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          return 1;
     };

     function formapcompanyupdate($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select company.address1, company.address2, company.city, company.state, company.zip, company.country, company.phone1, company.phone1comment, company.phone2, company.phone2comment, company.phone3, company.phone3comment, company.phone4, company.phone4comment, company.email1, company.email1comment, company.email2, company.email2comment, company.website, company.federalid, company.companyname, company.mailstop, company.lastchangedate from company where company.id='.$id);
          if (!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="50" value="'.rtrim($recordSet->fields[20]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td></td><td><input type="text" name="address2" size="30" maxlength="100" value="'.rtrim($recordSet->fields[1]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50" value="'.rtrim($recordSet->fields[2]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20" value="'.rtrim($recordSet->fields[3]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15" value="'.rtrim($recordSet->fields[4]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20" value="'.rtrim($recordSet->fields[5]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAIL_STOP'].':</td><td><input type="text" name="mailstop" size="20" maxlength="20" value="'.rtrim($recordSet->fields[21]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td></td><th>'.$lang['STR_PHONE'].'</th><th>'.$lang['STR_PHONE_DESCRIPTION'].'</th></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" value="'.rtrim($recordSet->fields[6]).'"  onChange="validatephone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="hidden" name="phone1comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[7]).'"'.INC_TEXTBOX.'>'.rtrim($recordSet->fields[7]).'</td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FAX'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" value="'.rtrim($recordSet->fields[8]).'"  onChange="validatephone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="hidden" name="phone2comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[9]).'"'.INC_TEXTBOX.'>'.rtrim($recordSet->fields[9]).'</td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE_THREE'].':</td><td><input type="text" name="phone3" size="30" maxlength="20" value="'.rtrim($recordSet->fields[10]).'"  onChange="validatephone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="phone3comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[11]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE_FOUR'].':</td><td><input type="text" name="phone4" size="30" maxlength="20" value="'.rtrim($recordSet->fields[12]).'"  onChange="validatephone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="phone4comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[13]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td></td><th>'.$lang['STR_EMAIL'].'</th><th>'.$lang['STR_EMAIL_DESCRIPTION'].'</th></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMAIL_ONE'].':</td><td><input type="text" name="email1" size="30" maxlength="50" value="'.rtrim($recordSet->fields[14]).'"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="email1comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[15]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_EMAIL_ONE'].':</td><td><input type="text" name="email2" size="30" maxlength="50" value="'.rtrim($recordSet->fields[16]).'"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="email2comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[17]).'"'.INC_TEXTBOX.'></td></tr><tr></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_WEB_ADDRESS'].':</td><td><input type="text" name="website" size="30" maxlength="100" value="'.rtrim($recordSet->fields[18]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FEDERAL_ID'].':</td><td><input type="text" name="federalid" size="30" maxlength="100" value="'.rtrim($recordSet->fields[19]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<input type="hidden" name="lastchangedate" value="'.$recordSet->fields[22].'">';
               return 1;
          } else {
               return 0;
          };
     };

     function formapvendorupdate($id) {
          global $conn, $lang, $active_company;
          $recordSet = &$conn->Execute('select vendor.orderfromname, vendor.paytermsid, vendor.paynone, vendor.defaultglacctid, vendor.defaultbilldescription, vendor.customeraccount, vendor.lastchangedate from vendor where vendor.id='.sqlprep($id));
          if (!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAYMENT_TERMS'].':</td><td><select name="paytermsid"'.INC_TEXTBOX.'>';
               $recordSet2 = &$conn->Execute('select id, verbal from invoiceterms where cancel=0 and ap=1 order by id');
               while (!$recordSet2->EOF) {
                    echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[1],$recordSet2->fields[0]," selected").'>'.rtrim($recordSet2->fields[1])."\n";
                    $recordSet2->MoveNext();
               };
               echo '</select></td></tr>';
               if ($recordSet->fields[2]) $paynonestr=" checked";
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAY_NONE'].':</td><td><input type="checkbox" name="paynone" value="1"'.$paynonestr.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEFAULT_GL_ACCOUNT'].':</td><td><select name="defaultglacctid"'.INC_TEXTBOX.'>';
               $recordSet2 = &$conn->Execute('select id, name, description from glaccount where (companyid='.sqlprep($active_company).' or companyid=0) and (accounttypeid>=70 or (accounttypeid>20 and accounttypeid<24) ) order by name');
               while (!$recordSet2->EOF) {
                    echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[3],$recordSet2->fields[0]," selected").'>'.rtrim($recordSet2->fields[1]).' - '.rtrim($recordSet2->fields[2])."\n";
                    $recordSet2->MoveNext();
               };
               echo '</select></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEFAULT_BILL_DESCRIPTION'].':</td><td><input type="text" name="defaultbilldescription" size="30" maxlength="50" value="'.rtrim($recordSet->fields[4]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_ACCOUNT'].':</td><td><input type="text" name="customeraccount" size="30" maxlength="20" value="'.rtrim($recordSet->fields[5]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<input type="hidden" name="lastchangevendordate" value="'.$recordSet->fields[6].'">';
               return 1;
          } else {
               return 0;
          };
     };

     function formapvendorselect($name) {
          global $conn, $lang,$active_company;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VENDOR_NUMBER'].':</td><td><input type="text" onchange="validateint(this)" length="20" maxsize="30" name="'.$name.'"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupvendor.php?name='.$name.'\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Vendor Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'apvendadd.php\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"><img src="images/addbutton.png" border="0" alt="Vendor Add"></a></td></tr>';
          return 1;
     };

     function formapchkacctadd() {
     global $conn, $lang, $active_company;
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ACCOUNT_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LAST_CHECK_NUMBER_USED'].':</td><td><input type="text" name="lastchecknumberused" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEFAULT_ENDORSER'].':</td><td><input type="text" name="defaultendorser" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AP'].':</td><td><input type="checkbox" name="ap" value="1" onclick="checkChoice(1)" checked'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAYROLL'].':</td><td><input type="checkbox" name="pay" value="1" onclick="checkChoice(2)" checked'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_ACCOUNT'].':</td><td><select name="glaccountid"'.INC_TEXTBOX.'>';
               $recordSet=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
               while (!$recordSet->EOF) {
                   echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2])."\n";
                   $recordSet->MoveNext();
               };
               echo '</select></td></tr>';
     };

     function formapchkacctupdate($id) {
     global $conn, $lang, $active_company;
               $recordSet=&$conn->Execute('select name, glaccountid, lastchecknumberused, ap, pay, defaultendorser from checkacct where gencompanyid='.sqlprep($active_company).' and id='.sqlprep($id));
               echo '<input type="hidden" name="id" value="'.$id.'">';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ACCOUNT_NAME'].':</td><td><input type="text" name="name" size="30" value="'.rtrim($recordSet->fields[0]).'" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_LAST_CHECK_NUMBER_USED'].':</td><td><input type="text" name="lastchecknumberused" value="'.$recordSet->fields[2].'" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEFAULT_ENDORSER'].':</td><td><input type="text" name="defaultendorser" value="'.rtrim($recordSet->fields[5]).'" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_AP'].':</td><td><input type="checkbox" name="ap" value="1" onclick="checkChoice(1)"'.checkequal($recordSet->fields[3],1,' checked').INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAYROLL'].':</td><td><input type="checkbox" name="pay" value="1" onclick="checkChoice(2)"'.checkequal($recordSet->fields[4],1,' checked').INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_ACCOUNT'].':</td><td><select name="glaccountid"'.INC_TEXTBOX.'>';
               $recordSet2=&$conn->Execute('select glaccount.id,glaccount.name,glaccount.description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by glaccount.name');
               while (!$recordSet2->EOF) {
                   echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$recordSet->fields[1]," selected").'>'.rtrim($recordSet2->fields[1]).' - '.rtrim($recordSet2->fields[2])."\n";
                   $recordSet2->MoveNext();
               };
               echo '</select></td></tr>';
     };

     function formapchkacctselect($name) {
     global $conn, $lang, $active_company;
               $recordSet=&$conn->Execute('select id, name from checkacct where gencompanyid='.sqlprep($active_company));
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHECKING_ACCOUNT'].'</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
               while (!$recordSet->EOF) {
                   echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
                   $recordSet->MoveNext();
               };
               echo '</select></td></tr>';
     };

      function apvoidcheck($checkid) {
            global $conn, $lang, $active_company;
            $conn->BeginTrans();
            $recordSet = &$conn->Execute('select apbill.id,apbill.invoicenumber,apbill.description,check.checknumber,check.checkdate from apbill,apbillpayment,check,vendor,company where check.checkvoid=0 and apbillpayment.checkid=check.id and apbillpayment.apbillid=apbill.id and apbill.paynone=0 and apbill.vendorid=vendor.id and vendor.paytocompanyid=company.id and apbill.gencompanyid='.sqlprep($active_company).' and check.id='.sqlprep($checkid).' order by check.checkdate desc, check.checknumber');
            while (!$recordSet->EOF) {
              $recordSet2 = &$conn->SelectLimit('select gltransvoucher.id, gltransvoucher.voucher, gltransvoucher.description, gltransvoucher.status from gltransvoucher where gltransvoucher.cancel=0 and gltransvoucher.wherefrom=1 and gltransvoucher.voucher='.sqlprep('billpay'.$recordSet->fields[1]).'  and  substring(gltransvoucher.entrydate,1,10)='.sqlprep(substr($recordSet->fields[4],0,10)).' order by gltransvoucher.entrydate desc',1);
              if (!$recordSet2||$recordSet2->EOF) {
                  $conn->RollbackTrans();
                  die(texterror($lang['STR_GL_ENTRIES_FOR_THIS_CHECK_NOT_FOUND']));
              };
              if (!$recordSet2->fields[3]==1) { //if check has posted
                  $voucherid=gltransvoucheradd($recordSet2->fields[1].'v',$recordSet->fields[2],createtime('Y-m-d'),1);
                  $recordSet3 = &$conn->Execute('select glaccountid, amount from gltransaction where voucherid='.sqlprep($recordSet2->fields[0]));
                  $fail=array();
                  while (!$recordSet3->EOF) { //create reverse entry for each gltransaction
                        $fail[]=gltransactionadd($voucherid, -$recordSet3->fields[1],$recordSet3->fields[0]);
                        $recordSet3->MoveNext();
                  };
                  $totfail=0;
                  foreach ($fail as $data) {
                        if (!$data) { //if any entry failed
                              $totfail=1;
                              gltransvoucherdelete($voucherid);
                              $conn->RollbackTrans();
                              die(texterror($lang['STR_ERROR_ADDING_GL_TRANSACTION']));
                        };
                  };
                  if ($totfail==0) {
                      if ($conn->Execute('update gltransvoucher set cancel=1, canceldate=NOW() where id='.sqlprep($recordSet2->fields[0])) === false) {
                              $conn->RollbackTrans();
                              die(texterror($lang['STR_ERROR_ADDING_GL_VOUCHER']));
                      };
                  };
              } else { //if check hasn't posted yet, we can just delete the gl entries
                  gltransvoucherdelete($recordSet2->fields[0]);
              };
              if ($totfail<>1) {
                   if ($conn->Execute('update apbill set complete=0 where id='.sqlprep($recordSet->fields[0])) === false) {
                         $conn->RollbackTrans();
                         die(texterror($lang['STR_ERROR_UPDATING_BILL']));
                   };
              };
              $recordSet->MoveNext();
            };
            if ($conn->Execute('update apbillpayment set checkvoid=1 where apbillpayment.checkid='.sqlprep($checkid)) === false) {
                 $conn->RollbackTrans();
                 die(texterror($lang['STR_ERROR_UPDATING_BILL_PAYMENT']));
            };
            if ($conn->Execute('update check set checkvoid=1 where id='.sqlprep($checkid)) === false) {
                 $conn->RollbackTrans();
                 die(texterror($lang['STR_ERROR_UPDATING_CHECK']));
            };
            $conn->CommitTrans();
            return 1;
      };
?>
