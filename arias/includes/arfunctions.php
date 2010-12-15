<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     function arcompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name,$mailstop) {
          global $conn, $lang, $userid, $custcompanyid;
          if (!$custcompanyid) checkpermissions('ar');
          if (!$city&&!$state&&$zip) {
               $cityst=getaddress($zip);
               $city=substr($cityst,0,strlen($cityst)-4);
               $state=substr($cityst,-2);
          };
          if ($conn->Execute("insert into company (address1, address2, city, state, zip, country, phone1, phone1comment, phone2, phone2comment, phone3, phone3comment, phone4, phone4comment, email1, email1comment, email2, email2comment, website, federalid, companyname, entrydate, entryuserid, lastchangedate, lastchangeuserid, mailstop) VALUES (".sqlprep($address1).", ".sqlprep($address2).", ".sqlprep($city).", ".sqlprep($state).", ".sqlprep($zip).", ".sqlprep($country).", ".sqlprep($phone1).", ".sqlprep($phone1comment).", ".sqlprep($phone2).", ".sqlprep($phone2comment).", ".sqlprep($phone3).", ".sqlprep($phone3comment).", ".sqlprep($phone4).", ".sqlprep($phone4comment).", ".sqlprep($email1).", ".sqlprep($email1comment).", ".sqlprep($email2).", ".sqlprep($email2comment).", ".sqlprep($website).", ".sqlprep($federalid).", ".sqlprep($name).", NOW(), ".sqlprep($userid).", NOW(), ".sqlprep($userid).", ".sqlprep($mailstop).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_COMPANY']);
               return 0;
          } else {
               return 1;
          };
     };

     function arcompanyupdate($id, $address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name, $mailstop, $lastchangedate) {
          global $conn, $lang, $userid, $custcompanyid;
          if (!$custcompanyid) checkpermissions('ar');
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
                    if ($conn->Execute("update company set address1=".sqlprep($address1).", address2=".sqlprep($address2).", city=".sqlprep($city).", state=".sqlprep($state).", zip=".sqlprep($zip).", country=".sqlprep($country).", phone1=".sqlprep($phone1).", phone1comment=".sqlprep($phone1comment).", phone2=".sqlprep($phone2).", phone2comment=".sqlprep($phone2comment).", phone3=".sqlprep($phone3).", phone3comment=".sqlprep($phone3comment).", phone4=".sqlprep($phone4).", phone4comment=".sqlprep($phone4comment).", email1=".sqlprep($email1).", email1comment=".sqlprep($email1comment).", email2=".sqlprep($email2).", email2comment=".sqlprep($email2comment).", website=".sqlprep($website).", companyname=".sqlprep($name).", federalid=".sqlprep($federalid).", lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid).", mailstop=".sqlprep($mailstop)." where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate)) === false) {
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

     function arcompanydelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("update company set cancel='1', canceldate=NOW(), canceluserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_COMPANY']);
               return 0;
          } else {
               return 1;
          };
     };

     function arcustomeradd($companyid, $taxexemptid, $creditlimit, $salesglacctid, $salesmanid, $servicerepid, $invoicetermsid, $quotecommentid, $interest, $billtoattnname, $quoteattnname, $chargecode, $salestaxnum) {
          global $conn, $lang, $userid, $active_company;
          checkpermissions('ar');
          if ($conn->Execute("insert into customer (companyid, taxexemptid, creditlimit, salesglacctid, salesmanid, servicerepid, invoicetermsid, quotecommentid, interest, billtoattnname, quoteattnname, chargecode, salestaxnum, entrydate, entryuserid, lastchangedate, lastchangeuserid, gencompanyid) VALUES (".sqlprep($companyid).", ".sqlprep($taxexemptid).", ".sqlprep($creditlimit).", ".sqlprep($salesglacctid).", ".sqlprep($salesmanid).", ".sqlprep($servicerepid).", ".sqlprep($invoicetermsid).", ".sqlprep($quotecommentid).", ".sqlprep($interest).", ".sqlprep($billtoattnname).", ".sqlprep($quoteattnname).", ".sqlprep($chargecode).", ".sqlprep($salestaxnum).", NOW(), ".sqlprep($userid).", NOW(), ".sqlprep($userid).", ".sqlprep($active_company).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_CUSTOMER']);
               return 0;
          } else {
               return 1;
          };
     };

     function arcarrieradd($companyid, $customernumber, $trackingurlbase, $trackingurlvarname) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("insert into carrier (companyid, customernumber, trackingurlbase, trackingurlvarname) VALUES (".sqlprep($companyid).", ".sqlprep($customernumber).", ".sqlprep($trackingurlbase).", ".sqlprep($trackingurlvarname).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_CARRIER']);
               return 0;
          } else {
               echo textsuccess($lang['STR_CARRIER_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function arcarrierupdate($id, $customernumber, $trackingurlbase, $trackingurlvarname) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("update carrier set customernumber=".sqlprep($customernumber).",trackingurlbase=".sqlprep($trackingurlbase).",trackingurlvarname=".sqlprep($trackingurlvarname)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_CARRIER']);
               return 0;
          } else {
               echo textsuccess($lang['STR_CARRIER_UPDATED_SUCCESSFULLY']);
               return 1;
          };
     };

     function arcarrierdelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("delete from carrier where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_CARRIER']);
               return 0;
          } else {
               return 1;
          };
     };

     function arcarriermethodadd($carrierid, $description) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("insert into carrierservice (carrierid,description) VALUES (".sqlprep($carrierid).", ".sqlprep($description).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_SHIPPING_METHOD']);
               return 0;
          } else {
               return 1;
          };
     };

     function arcarriermethodupdate($carriermethodid, $description) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("update carrierservice set description=".sqlprep($description)." where id=".sqlprep($carriermethodid)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_SHIPPING_METHOD']);
               return 0;
          } else {
               return 1;
          };
     };

     function arcarriermethoddelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("delete from carrierservice where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_SHIPPING_METHOD']);
               return 0;
          } else {
               return 1;
          };
     };


     function arcustomertaxadd($taxid,$id,$taxrecid) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          $recordSet=&$conn->Execute('select id from customersalestax where salestaxid='.sqlprep($taxid).' and customerid='.sqlprep($id));
          if ($recordSet&&!$recordSet->EOF) {
             if ($recordSet->fields[0] == $taxrecid) {
                 // this is the same exact data - do nothing
                 return 1;
             } else {
                 // data has a different taxrecid, so do nothing - assume duplicate
                 return 1;
             };
          } else {
            // now check to see if this taxrecid has different data
            $recordSet=&$conn->Execute('select * from customersalestax where id='.sqlprep($taxrecid));
            if (!$recordSet->EOF) {
                // already in file but with changed data - update
                if ($conn->Execute('update customersalestax set salestaxid='.sqlprep($taxid).', customerid='.sqlprep($id).' where id='.sqlprep($taxrecid)) === false) {
                   echo texterror($lang['STR_ERROR_UPDATING_SALES_TAX']);
                   return 0;
                } else {
                   return 1;
                };

            } else {
                //insert into file - this is a new record
                if ($conn->Execute("insert into customersalestax (customerid, salestaxid) values (".sqlprep($id).",".sqlprep($taxid).")") === false) {
                   echo texterror($lang['STR_ERROR_ADDING_SALES_TAX']);
                   return 0;
                } else {
                   return 1;
                };
            };
          };
     };

     function arcustomerupdate($id, $companyid, $taxexemptid, $creditlimit, $salesglacctid, $salesmanid, $servicerepid, $invoicetermsid, $quotecommentid, $interest, $billtoattnname, $quoteattnname, $chargecode, $salestaxnum,$lastchangecustomerdate) {
          global $conn, $lang, $userid, $custcompanyid;
          if (!$custcompanyid) checkpermissions('ar');
          $recordSet=&$conn->Execute("select count(*) from customer where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangecustomerdate));
          if ($recordSet&&!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"customer","companyid");
                    return 0;
               } else {
                    if ($conn->Execute("update customer set taxexemptid=".sqlprep($taxexemptid).", creditlimit=".sqlprep($creditlimit).", salesglacctid=".sqlprep($salesglacctid).", salesmanid=".sqlprep($salesmanid).", servicerepid=".sqlprep($servicerepid).", invoicetermsid=".sqlprep($invoicetermsid).", quotecommentid=".sqlprep($quotecommentid).", interest=".sqlprep($interest).", billtoattnname=".sqlprep($billtoattnname).", quoteattnname=".sqlprep($quoteattnname).", chargecode=".sqlprep($chargecode).", salestaxnum=".sqlprep($salestaxnum).", lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid)." where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangecustomerdate)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_CUSTOMER']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
          } else {
               echo texterror($lang['STR_ERROR_UPDATING_CUSTOMER']);
               return 0;
          };
     };

     function arcustomerdelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("update customer set cancel='1', canceldate=NOW(), canceluserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_CUSTOMER']);
               return 0;
          } else {
               return 1;
          };
     };

     function arshiptoadd($companyid, $shiptocompanyid, $defaultshipvia) {
          global $conn, $lang, $userid, $custcompanyid;
          if (!$custcompanyid) checkpermissions('ar');
          if ($conn->Execute("insert into shipto (companyid, shiptocompanyid, defaultshipvia, entrydate, entryuserid, lastchangedate, lastchangeuserid) VALUES (".sqlprep($companyid).", ".sqlprep($shiptocompanyid).", ".sqlprep($defaultshipvia).", NOW(), ".sqlprep($userid).", NOW(), ".sqlprep($userid).")")=== false) {
               echo texterror($lang['STR_ERROR_ADDING_CUSTOMER_SHIPTO']);
               return 0;
          } else {
               return 1;
          };
     };

     function arshiptoupdate($id, $companyid, $shiptocompanyid, $defaultshipvia,$lastchangeshipdate) {
          global $conn, $lang, $userid, $custcompanyid;
          if (!$custcompanyid) checkpermissions('ar');
          $recordSet=&$conn->Execute("select count(*) from shipto where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangeshipdate));
          if (!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"shipto","id");
               } else {
                   if ($conn->Execute("update shipto set defaultshipvia=".sqlprep($defaultshipvia).", lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid)." where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangeshipdate)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_CUSTOMER_SHIPTO']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
          };
     };


     function arshiptodelete($id) {
          global $conn, $lang, $userid, $custcompanyid;
          if (!$custcompanyid) checkpermissions('ar');
          if ($conn->Execute("update shipto set cancel='1', canceldate=NOW(), canceluserid=".sqlprep($userid)." where companyid=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_CUSTOMER_SHIPTO']);
               return 0;
          } else {
               return 1;
          };
     };

     function arsalestaxadd($taxname, $taxrate, $taxbase, $glacctid) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("insert into salestax (taxname, taxrate, taxbase, glacctid, entrydate, entryuserid, lastchangedate, lastchangeuserid) VALUES (".sqlprep($taxname).", ".sqlprep($taxrate).", ".sqlprep($taxbase).", ".sqlprep($glacctid).", NOW(), ".sqlprep($userid).", NOW(), ".sqlprep($userid).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_SALES_TAX']);
               return 0;
          } else {
               echo textsuccess($lang['STR_SALES_TAX_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function artaxexemptionadd($exemptname) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("insert into taxexempt (exemptname, entrydate, entryuserid, lastchangedate, lastchangeuserid) VALUES (".sqlprep($exemptname).", NOW(), ".sqlprep($userid).", NOW(), ".sqlprep($userid).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_TAX_EXEMPTION']);
               return 0;
          } else {
               echo textsuccess($lang['STR_TAX_EXEMPTION_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function artaxexemptionupdate($id, $exemptname) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("update taxexempt set exemptname=".sqlprep($exemptname).", lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_TAX_EXEMPTION']);
               return 0;
          } else {
               echo textsuccess($lang['STR_TAX_EXEMPTION_UPDATED_SUCCESSFULLY']);
               return 1;
          };
     };

     function artaxexemptiondelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("update taxexempt set cancel='1', canceldate=NOW(), canceluserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_TAX_EXEMPTION']);
               return 0;
          } else {
               echo textsuccess($lang['STR_TAX_EXEMPTION_DELETED_SUCCESSFULLY']);
               return 1;
          };
     };

     function arsalestaxupdate($id, $taxname, $taxrate, $taxbase, $glacctid) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("update salestax set taxname=".sqlprep($taxname).", taxrate=".sqlprep($taxrate).", taxbase=".sqlprep($taxbase).", glacctid=".sqlprep($glacctid).", lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_SALES_TAX']);
               return 0;
          } else {
               echo textsuccess($lang['STR_SALES_TAX_UPDATED_SUCCESSFULLY']);
               return 1;
          };
     };

     function arsalestaxdelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("update salestax set cancel=1, canceldate=NOW(), canceluserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_SALES_TAX']);
               return 0;
          } else {
               echo textsuccess($lang['STR_SALES_TAX_DELETED_SUCCESSFULLY']);
               return 1;
          };
     };

     function arsalesmanadd($companyid, $payrollid, $commissionrate, $commissionbase, $servicerep, $salesman) {
          global $conn, $lang, $userid, $active_company;
          checkpermissions('ar');
          if ($conn->Execute("insert into salesman (companyid, payrollid, commissionrate, commissionbase, servicerep, salesman, gencompanyid, entrydate, entryuserid, lastchangedate, lastchangeuserid) VALUES (".sqlprep($companyid).", ".sqlprep($payrollid+0).", ".sqlprep($commissionrate+0).", ".sqlprep($commissionbase).", ".sqlprep($servicerep).", ".sqlprep($salesman).", ".sqlprep($active_company).", NOW(), ".sqlprep($userid).", NOW(), ".sqlprep($userid).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_SALES_PERSONNEL']);
               return 0;
          } else {
               echo textsuccess($lang['STR_SALES_PERSONNEL_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function arsalesmanupdate($companyid, $payrollid, $commissionrate, $commissionbase, $servicerep, $salesman) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("update salesman set lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid).", commissionrate=".sqlprep($commissionrate+0).", commissionbase=".sqlprep($commissionbase).", servicerep=".sqlprep($servicerep).", salesman=".sqlprep($salesman)." where companyid=".sqlprep($companyid)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_SALES_PERSONNEL']);
               return 0;
          } else {
               echo textsuccess($lang['STR_SALES_PERSONNEL_UPDATED_SUCCESSFULLY']);
               return 1;
          };
     };

     function arsalesmandelete($companyid) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("update salesman set cancel='1', canceldate=NOW(), canceluserid=".sqlprep($userid)." where companyid=".sqlprep($companyid)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_SALES_PERSONNEL']);
               return 0;
          } else {
               echo textsuccess($lang['STR_SALES_PERSONNEL_DELETED_SUCCESSFULLY']);
               return 1;
          };
     };

     function arquotecommentadd($comments) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("insert into quotecomment (comments, entrydate, entryuserid, lastchangedate, lastchangeuserid) VALUES (".sqlprep($comments).", NOW(), ".sqlprep($userid).", NOW(), ".sqlprep($userid).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_QUOTE_COMMENT']);
               return 0;
          } else {
               echo textsuccess($lang['STR_QUOTE_COMMENT_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function arquotecommentupdate($id, $comments) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("update quotecomment set comments=".sqlprep($comments).", lastchangedate=NOW(), lastchangeuserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_QUOTE_COMMENT']);
               return 0;
          } else {
               echo textsuccess($lang['STR_QUOTE_COMMENT_UPDATED_SUCCESSFULLY']);
               return 1;
          };
     };

     function arquotecommentdelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("update quotecomment set cancel='1', canceluserid=".sqlprep($userid).", canceldate=NOW() where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_QUOTE_COMMENT']);
               return 0;
          } else {
               echo textsuccess($lang['STR_QUOTE_COMMENT_DELETED_SUCCESSFULLY']);
               return 1;
          };
     };

     function arinvoicetermsadd($verbal, $discountpercent, $discountdays, $netduedays) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("insert into invoiceterms (verbal, discountpercent, discountdays, netduedays, ar, entrydate, entryuserid, lastchangedate, lastchangeuserid) VALUES (".sqlprep($verbal).", ".sqlprep($discountpercent).", ".sqlprep($discountdays).", ".sqlprep($netduedays).", '1', NOW(), '".$userid."', NOW(), '".$userid."')") === false) {
               echo texterror($lang['STR_ERROR_ADDING_INVOICE_TERMS']);
               return 0;
          } else {
               echo textsuccess($lang['STR_INVOICE_TERMS_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function arinvoicetermsupdate($id, $verbal, $discountpercent, $discountdays, $netduedays) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          if ($conn->Execute("update invoiceterms set verbal=".sqlprep($verbal).", discountpercent=".sqlprep($discountpercent).", discountdays=".sqlprep($discountdays).", netduedays=".sqlprep($netduedays).", lastchangedate=NOW(), lastchangeuserid='".$userid."' where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_INVOICE_TERMS']);
               return 0;
          } else {
               echo textsuccess($lang['STR_INVOICE_TERMS_UPDATED_SUCCESSFULLY']);
               return 1;
          };
     };

     function arinvoicetermsdelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('ar');
          $recordSet = &$conn->Execute('select ap from invoiceterms where id='.sqlprep($id));
          if ($recordSet&&!$recordSet->EOF) {
               if ($recordSet->fields[0]) { //if we're using this for ap too, just set ar=0
                    if ($conn->Execute("update invoiceterms set ar='0', lastchangedate=NOW(), lastchangeuserid='".$userid."' where id=".sqlprep($id)) === false) {
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
               echo texterror($lang['STR_ERROR_DELETING_INVOICE_TERMS']);
               return 0;
          };
     };

     function orderstatontimegraph($bgdate, $eddate, $customerid) {
          global $conn, $lang, $active_company;
          if ($customerid) $customerstr=' and arorder.orderbycompanyid='.sqlprep($customerid);
          $recordSet = &$conn->Execute("select count(distinct arorder.id) from arorder,arordership,arordershipdetail where arordershipdetail.ordershipid=arordership.id and arorder.id=arordership.orderid and arorder.cancel=0 and arorder.companyid=".sqlprep($active_company).$customerstr." and substring(arordership.shipdate from 1 for 10)>=".sqlprep($bgdate)." and substring(arordership.shipdate from 1 for 10)<=".sqlprep($eddate));
          if ($recordSet->EOF) {
               die(texterror($lang['STR_NO_ORDERS_FOUND']));
          } else {
               $shiporditems=$recordSet->fields[0];
          };
          if ($recordSet->fields[0]==0) die(texterror($lang['STR_NO_ORDERS_FOUND']));
          $recordSet = &$conn->Execute('select count(distinct arorderdetail.orderid), sum(arorderdetail.priceach*arordershipdetail.shipqty), count(distinct arorderdetail.id) from arorder,arorderdetail,arordership,arordershipdetail where arordershipdetail.ordershipid=arordership.id and arorder.companyid='.sqlprep($active_company).' and arorder.id=arordership.orderid and substring(arordership.shipdate from 1 for 10)<arorder.duedate '.$customerstr.' and arorderdetail.id=arordershipdetail.orderdetailid and arorder.cancel=0 and (substring(arordership.shipdate from 1 for 10)>='.sqlprep($bgdate).' and substring(arordership.shipdate from 1 for 10)<='.sqlprep($eddate).')');
          if (!$recordSet->EOF) {
               $earlyordnum=$recordSet->fields[0];
               if ($earlyordnum>0) {
                    $okone=1;
                    $earlyorddols=$recordSet->fields[1];
                    if ($earlyorddols>0) $oktwo=1;
                    $earlyord=$recordSet->fields[2];
                    if ($earlyord>0) $okthree=1;
               };
          };
          $recordSet = &$conn->Execute("select count(distinct arorderdetail.orderid), sum(arorderdetail.priceach*arordershipdetail.shipqty), count(distinct arorderdetail.id) from arorder,arorderdetail,arordership,arordershipdetail where arordershipdetail.ordershipid=arordership.id and arorder.companyid=".sqlprep($active_company)." and arorder.id=arordership.orderid and substring(arordership.shipdate from 1 for 10)=arorder.duedate ".$customerstr." and arorderdetail.id=arordershipdetail.orderdetailid and arorder.cancel=0 and (substring(arordership.shipdate from 1 for 10)>=".sqlprep($bgdate)." and substring(arordership.shipdate from 1 for 10)<=".sqlprep($eddate).")");
          if (!$recordSet->EOF) {
               $otordnum=$recordSet->fields[0];
               if ($otordnum>0) {
                    $okone=1;
                    $otorddols=$recordSet->fields[1];
                    if ($otorddols>0) $oktwo=1;
                    $otord=$recordSet->fields[2];
                    if ($otord>0) $okthree=1;
               };
          };
          $recordSet = &$conn->Execute("select count(distinct arorderdetail.orderid), sum(arorderdetail.priceach*arordershipdetail.shipqty), count(distinct arorderdetail.id) from arorder,arorderdetail,arordership,arordershipdetail where arordershipdetail.ordershipid=arordership.id and arorder.companyid=".sqlprep($active_company)." and arorder.id=arordership.orderid and substring(arordership.shipdate from 1 for 10)>arorder.duedate ".$customerstr." and arorderdetail.id=arordershipdetail.orderdetailid and arorder.cancel=0 and (substring(arordership.shipdate from 1 for 10)>=".sqlprep($bgdate)." and substring(arordership.shipdate from 1 for 10)<=".sqlprep($eddate).")");
          if (!$recordSet->EOF) {
               $lateordnum=$recordSet->fields[0];
               if ($lateordnum>0) {
                    $okone=1;
                    $lateorddols=$recordSet->fields[1];
                    if ($lateorddols>0) $oktwo=1;
                    $lateord=$recordSet->fields[2];
                    if ($lateord>0) $okthree=1;
               };
          };
      echo '<table><tr>';
      unset($datastring);
      if ($earlyordnum>0) $datastring='&data[]='.$earlyordnum;
      if ($otordnum>0) $datastring=$datastring.'&data[]='.$otordnum;
      if ($lateordnum>0) $datastring=$datastring.'&data[]='.$lateordnum;
      if ($earlyordnum>0) $datastring=$datastring.'&dataname[]='.urlencode($lang['STR_EARLY']);
      if ($otordnum>0) $datastring=$datastring.'&dataname[]='.urlencode($lang['STR_ON_TIME']);
      if ($lateordnum>0) $datastring=$datastring.'&dataname[]='.urlencode($lang['STR_LATE']);
      if ($datastring) echo '<td><center>'.$lang['STR_ORDERS'].'</center><img src="images/graphpie.php?name=arorderstatontimegraphord'.$datastring.'"></td>';

      unset($datastring);
      if ($earlyord>0) $datastring='&data[]='.$earlyord;
      if ($otord>0) $datastring=$datastring.'&data[]='.$otord;
      if ($lateord>0) $datastring=$datastring.'&data[]='.$lateord;
      if ($earlyord>0) $datastring=$datastring.'&dataname[]='.urlencode($lang['STR_EARLY']);
      if ($otord>0) $datastring=$datastring.'&dataname[]='.urlencode($lang['STR_ON_TIME']);
      if ($lateord>0) $datastring=$datastring.'&dataname[]='.urlencode($lang['STR_LATE']);
      if ($datastring) echo '<td><center>'.$lang['STR_ITEMS'].'</center><img src="images/graphpie.php?name=arorderstatontimegraphitems'.$datastring.'"></td>';

      unset($datastring);
      if ($earlyorddols>0) $datastring='&data[]='.$earlyorddols;
      if ($otorddols>0) $datastring=$datastring.'&data[]='.$otorddols;
      if ($lateorddols>0) $datastring=$datastring.'&data[]='.$lateorddols;
      if ($earlyorddols>0) $datastring=$datastring.'&dataname[]='.urlencode($lang['STR_EARLY']);
      if ($otorddols>0) $datastring=$datastring.'&dataname[]='.urlencode($lang['STR_ON_TIME']);
      if ($lateorddols>0) $datastring=$datastring.'&dataname[]='.urlencode($lang['STR_LATE']);
      if ($datastring) echo '<td><center>'.$lang['STR_DOLLARS'].'</center><img src="images/graphpie.php?name=arorderstatontimegraphdols'.$datastring.'"></td>';
      echo '</tr></table>';

          echo '<table border=0><tr><th colspan="4">'.$lang['STR_EARLY'].'</th><th colspan="4">'.$lang['STR_ON_TIME'].'</th><th colspan="4">'.$lang['STR_LATE'].'</th></tr><tr><th>'.$lang['STR_ORDERS'].'</th><th>'.$lang['STR_ITEMS'].'</th><th>%</th><th>'.CURRENCY_SYMBOL.'</th><th>'.$lang['STR_ORDERS'].'</th><th>'.$lang['STR_ITEMS'].'</th><th>%</th><th>'.CURRENCY_SYMBOL.'</th><th>'.$lang['STR_ORDERS'].'</th><th>'.$lang['STR_ITEMS'].'</th><th>%</th><th>'.CURRENCY_SYMBOL.'</th></tr>';
          if ($shiporditems==0) $shiporditems=1;
          echo '<tr><td>'.$earlyordnum.'</td><td>'.$earlyord.'</td><td>'.num_format((($earlyordnum/$shiporditems)*100),1).'%</td><td>'.CURRENCY_SYMBOL.num_format($earlyorddols,2).'</td><td>'.$otordnum.'</td><td>'.$otord.'</td><td>'.num_format((($otordnum/$shiporditems)*100),1).'%</td><td>'.CURRENCY_SYMBOL.num_format($otorddols,2).'</td><td>'.$lateordnum.'</td><td>'.$lateord.'</td><td>'.num_format((($lateordnum/$shiporditems)*100),1).'%</td><td>'.CURRENCY_SYMBOL.num_format($lateorddols,2).'</td></tr>';
          echo '</table><br>';
          return 1;
     };

     function orderstatdailygraph($bgdate, $eddate, $customerid) {
          global $conn, $lang,$userid,$active_company;
          if ($customerid) $customerstr=' and arorder.orderbycompanyid='.sqlprep($customerid);
          $conn->Execute('drop table arorderstats'.$userid);
          $conn->Execute('create table arorderstats'.$userid.' (entrydate date not null unique, recvordnum double, recvorditems double, recvorddols double, shipordnum double, shiporditems double, shiporddols double, openordnum double, openorditems double, openorddols double, topenordnum double, topenorditems double, topenorddols double)');
          $recordSet = &$conn->Execute("select date_format(arorder.entrydate, '%Y-%m-%d') as entrydate, count(distinct arorder.id) as recvordnum, count(arorderdetail.id) as recvorditems, sum(arorderdetail.priceach*arorderdetail.qtyorder) as recvorddols from arorder,arorderdetail where arorder.companyid=".sqlprep($active_company).$customerstr." and arorder.id=arorderdetail.orderid and arorder.cancel=0 and (substring(arorder.entrydate from 1 for 10)>=".sqlprep($bgdate)." and substring(arorder.entrydate from 1 for 10)<=".sqlprep($eddate).") group by substring(arorder.entrydate from 1 for 10)");
          while (!$recordSet->EOF) {
               $conn->Execute('insert into arorderstats'.$userid.' (entrydate,recvordnum,recvorditems,recvorddols) VALUES ('.sqlprep($recordSet->fields[0]).', '.$recordSet->fields[1].', '.$recordSet->fields[2].', '.$recordSet->fields[3].')');
               $recordSet->MoveNext();
          };
          $recordSet = &$conn->Execute("select substring(arordership.shipdate from 1 for 10) as entrydate, count(distinct arordership.id) as shipordnum, count(arordershipdetail.id) as shiporditems, sum(arorderdetail.priceach*arordershipdetail.shipqty) as shiporddols from arorder,arordership,arordershipdetail,arorderdetail where arordershipdetail.ordershipid=arordership.id  and arorder.companyid=".sqlprep($active_company).$customerstr." and arorder.id=arordership.orderid and arorderdetail.id=arordershipdetail.orderdetailid and arorder.cancel=0 and (substring(arordership.shipdate from 1 for 10)>=".sqlprep($bgdate)." and substring(arordership.shipdate from 1 for 10)<=".sqlprep($eddate).") group by substring(arordership.shipdate from 1 for 10)");
          while (!$recordSet->EOF) {
               $conn->Execute('update arorderstats'.$userid.' set shipordnum='.sqlprep($recordSet->fields[1]).', shiporditems='.$recordSet->fields[2].', shiporddols='.$recordSet->fields[3].' where entrydate='.sqlprep($recordSet->fields[0]));
               $conn->Execute('insert into arorderstats'.$userid.' (entrydate,shipordnum,shiporditems,shiporddols) VALUES ('.sqlprep($recordSet->fields[0]).', '.$recordSet->fields[1].', '.$recordSet->fields[2].', '.$recordSet->fields[3].')');
               $recordSet->MoveNext();
          };
          $recordSet = &$conn->Execute("select substring(arorder.entrydate from 1 for 10) as entrydate, count(distinct arorder.id) as openordnum, count(arorderdetail.id) as openorditems, sum(arorderdetail.priceach*arorderdetail.qtyorder) as openorddols from arorder,arorderdetail where (arorder.status=0 or arorder.status is null) and arorder.id=arorderdetail.orderid and arorder.companyid=".sqlprep($active_company).$customerstr." and arorder.cancel=0 and (substring(arorder.entrydate from 1 for 10)>=".sqlprep($bgdate)." and substring(arorder.entrydate from 1 for 10)<=".sqlprep($eddate).") group by substring(arorder.entrydate from 1 for 10)");
          while (!$recordSet->EOF) {
               $conn->Execute('update arorderstats'.$userid.' set openordnum='.sqlprep($recordSet->fields[1]).', openorditems='.$recordSet->fields[2].', openorddols='.$recordSet->fields[3].' where entrydate='.sqlprep($recordSet->fields[0]));
               $conn->Execute('insert into arorderstats'.$userid.' (entrydate,openordnum,openorditems,openorddols) VALUES ('.sqlprep($recordSet->fields[0]).', '.$recordSet->fields[1].', '.$recordSet->fields[2].', '.$recordSet->fields[3].')');
               $recordSet->MoveNext();
          };
          $conn->Execute('update arorderstats'.$userid.' set recvordnum=0 where recvordnum is null');
          $conn->Execute('update arorderstats'.$userid.' set recvorditems=0 where recvorditems is null');
          $conn->Execute('update arorderstats'.$userid.' set shipordnum=0 where shipordnum is null');
          $conn->Execute('update arorderstats'.$userid.' set shiporditems=0 where shiporditems is null');
          $conn->Execute('update arorderstats'.$userid.' set openordnum=0 where openordnum is null');
          $conn->Execute('update arorderstats'.$userid.' set openorditems=0 where openorditems is null');

          $recordSet = &$conn->Execute("select entrydate,recvordnum,recvorditems,recvorddols,shipordnum,shiporditems,shiporddols,openordnum,openorditems,openorddols from arorderstats".$userid." order by entrydate");
          if (!$recordSet->EOF) {
               echo '<table border=0><tr><th rowspan="2">'.$lang['STR_DATE'].'</th><th colspan="3">'.$lang['STR_RECEIVED'].'</th><th colspan="3">'.$lang['STR_SHIPPED'].'</th><th colspan="3">'.$lang['STR_OPEN'].'</th></tr><tr><th>'.$lang['STR_ORDERS'].'</th><th>'.$lang['STR_ITEMS'].'</th><th>'.CURRENCY_SYMBOL.'</th><th>'.$lang['STR_ORDERS'].'</th><th>'.$lang['STR_ITEMS'].'</th><th>'.CURRENCY_SYMBOL.'</th><th>'.$lang['STR_ORDERS'].'</th><th>'.$lang['STR_ITEMS'].'</th><th>'.CURRENCY_SYMBOL.'</th></tr>';
          } else {
               die(texterror($lang['STR_NO_MATCHING_ORDERS_FOUND']));
          };
          while (!$recordSet->EOF) {
               $orders=1;
               echo '<tr><td align="right">'.$recordSet->fields[0].'</td><td align="right">'.$recordSet->fields[1].'</td><td align="right">'.$recordSet->fields[2].'</td><td align="right">$'.num_format($recordSet->fields[3],2).'</td><td align="right">'.$recordSet->fields[4].'</td><td align="right">'.$recordSet->fields[5].'</td><td align="right">$'.num_format($recordSet->fields[6],2).'</td><td align="right">'.$recordSet->fields[7].'</td><td align="right">'.$recordSet->fields[8].'</td><td align="right">$'.num_format($recordSet->fields[9],2).'</td></tr>';
               $recvordnum+=$recordSet->fields[1];
               $recvorditems+=$recordSet->fields[2];
               $recvorddols+=$recordSet->fields[3];
               $shipordnum+=$recordSet->fields[4];
               $shiporditems+=$recordSet->fields[5];
               $shiporddols+=$recordSet->fields[6];
               $openordnum+=$recordSet->fields[7];
               $openorditems+=$recordSet->fields[8];
               $openorddols+=$recordSet->fields[9];
               $recordSet->MoveNext();
          };
          $conn->Execute('drop table arorderstats'.$userid);
          if ($orders) {
               echo '<tr><td align="right"><b>'.$lang['STR_TOTALS'].'</b></td><td align="right">'.$recvordnum.'</td><td align="right">'.$recvorditems.'</td><td align="right">'.CURRENCY_SYMBOL.num_format($recvorddols,2).'</td><td align="right">'.$shipordnum.'</td><td align="right">'.$shiporditems.'</td><td align="right">'.CURRENCY_SYMBOL.num_format($shiporddols,PREFERRED_DECIMAL_PLACES).'</td><td align="right">'.$openordnum.'</td><td align="right">'.$openorditems.'</td><td align="right">'.CURRENCY_SYMBOL.num_format($openorddols,PREFERRED_DECIMAL_PLACES).'</td></tr>';
               echo '</table><br>';
          };
          return 1;
     };


     function formarshiptoadd() {
          global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY_NAME'].':</td><td><input type="text" name="shipname" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"></td><td><input type="text" name="address2" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAIL_STOP'].':</td><td><input type="text" name="mailstop" size="20" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"></td><th>'.$lang['STR_PHONE'].'</th><th>'.$lang['STR_PHONE_DESCRIPTION'].'</th></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="text" name="phone1comment" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FAX'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
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
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEFAULT_SHIP_METHOD'].':</td><td><select name="defaultshipvia"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute("select carrierservice.id,company.companyname,carrierservice.description from company,carrier,carrierservice where company.id=carrier.companyid and carrierservice.carrierid=carrier.id order by company.companyname,carrierservice.description");
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
     };

     function formarshiptoupdate($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select company.address1, company.address2, company.city, company.state, company.zip, company.country, company.phone1, company.phone1comment, company.phone2, company.phone2comment, company.phone3, company.phone3comment, company.phone4, company.phone4comment, company.email1, company.email1comment, company.email2, company.email2comment, company.website, company.federalid, company.companyname, shipto.defaultshipvia, company.id , company.mailstop, company.lastchangedate,shipto.lastchangedate,shipto.shiptocompanyid from company, shipto where company.id=shipto.shiptocompanyid and shipto.id='.sqlprep($id));
          if ($recordSet&&!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY_NAME'].':</td><td><input type="text" name="shipname" size="30" maxlength="50" value="'.rtrim($recordSet->fields[20]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<input type="hidden" name="companyid" value="'.rtrim($recordSet->fields[22]).'">';
               echo '<input type="hidden" name="shipto" value="1">';
               echo '<input type="hidden" name="shiptoid" value="'.$id.'">';
               echo '<input type="hidden" name="shiptoselected" value="1">';
               echo '<input type="hidden" name="lastchangeshipdate" value="'.$recordSet->fields[25].'">';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"></td><td><input type="text" name="address2" size="30" maxlength="100" value="'.rtrim($recordSet->fields[1]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50" value="'.rtrim($recordSet->fields[2]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20" value="'.rtrim($recordSet->fields[3]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15" value="'.rtrim($recordSet->fields[4]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20" value="'.rtrim($recordSet->fields[5]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAIL_STOP'].':</td><td><input type="text" name="mailstop" size="20" maxlength="20" value="'.rtrim($recordSet->fields[23]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td></td><th>'.$lang['STR_PHONE'].'</th><th>'.$lang['STR_PHONE_DESCRIPTION'].'</th></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" value="'.rtrim($recordSet->fields[6]).'"  onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="hidden" name="phone1comment" size="30" maxlength="20" value="Telephone"'.INC_TEXTBOX.'>'.$lang['STR_PHONE'].'</td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FAX'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" value="'.rtrim($recordSet->fields[8]).'"  onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="hidden" name="phone2comment" size="30" maxlength="20" value="Fax"'.INC_TEXTBOX.'>'.$lang['STR_FAX'].'</td></tr>';
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
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Default Ship Method:</td><td><select name="defaultshipvia"'.INC_TEXTBOX.'>';
               $recordSet2 = &$conn->Execute("select carrierservice.id,company.companyname,carrierservice.description from company,carrier,carrierservice where company.id=carrier.companyid and carrierservice.carrierid=carrier.id order by company.companyname,carrierservice.description");
               while (!$recordSet2->EOF) {
                    echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$recordSet->fields[21]," selected").'>'.rtrim($recordSet2->fields[1]).' - '.rtrim($recordSet2->fields[2])."\n";
                    $recordSet2->MoveNext();
               };
               echo '</select></td></tr>';
               echo '<input type="hidden" name="lastchangedate" value="'.$recordSet->fields[24].'">';
               return 1;
          } else {
               return 0;
          };
     };

     function formarshiptoselect($name, $customerid) {
          global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Ship To Location:</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select shipto.id,company.companyname,company.address1,company.city,company.state,company.country from company,shipto,customer where company.id=shipto.shiptocompanyid and shipto.companyid=customer.companyid and customer.id='.sqlprep($customerid).' and shipto.cancel=0 and company.cancel=0 order by company.companyname,company.country,company.address1,company.city,company.state');
          if ($recordSet) {
               while (!$recordSet->EOF) {
                    echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2]).', '.rtrim($recordSet->fields[3]).', '.rtrim($recordSet->fields[4]).' '.rtrim($recordSet->fields[5])."\n";
                    $recordSet->MoveNext();
               };
          };
          echo '<option value="0">Create New';
          echo '</select></td></tr>';
          return 1;
     };

     function formarcustomeradd() {
          global $conn, $lang,$active_company;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td></td><td><input type="text" name="address2" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAIL_STOP'].':</td><td><input type="text" name="mailstop" size="20" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td></td><th>Phone</th><th>'.$lang['STR_PHONE_DESCRIPTION'].'</th></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="hidden" name="phone1comment" size="30" maxlength="20" value="Telephone"'.INC_TEXTBOX.'>'.$lang['STR_PHONE'].'</td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FAX'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="hidden" name="phone2comment" size="30" maxlength="20" value="Fax"'.INC_TEXTBOX.'>'.$lang['STR_FAX'].'</td></tr>';
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
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEFAULT_SHIP_METHOD'].':</td><td><select name="defaultshipvia"'.INC_TEXTBOX.'>';
          $recordSet2 = &$conn->Execute("select carrierservice.id,company.companyname,carrierservice.description from company,carrier,carrierservice where company.id=carrier.companyid and carrierservice.carrierid=carrier.id order by company.companyname,carrierservice.description");
          while (!$recordSet2->EOF) {
               echo '<option value="'.$recordSet2->fields[0].'">'.rtrim($recordSet2->fields[1]).' - '.rtrim($recordSet2->fields[2])."\n";
               $recordSet2->MoveNext();
          };
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_EXEMPTION'].':</td><td><select name="taxexemptid"'.INC_TEXTBOX.'><option value="0" selected>Not Exempt';
          $recordSet = &$conn->Execute('select id, exemptname from taxexempt where cancel=0 order by id');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SALES_TAX_ID'].':</td><td><input type="text" name="salestaxnum" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td></td><th>'.$lang['STR_SALES_TAX_DISTRICT'].'</th></tr>';
          for ($counter=1;$counter<=MAX_CUSTOMER_SALESTAX;$counter++) {
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SALES_TAX'].' '.$counter.'</td><td><select name="taxid'.$counter.'"'.INC_TEXTBOX.'><option value="0" selected>No Tax Selected';
                $recordSet = &$conn->Execute('select id, taxname from salestax where cancel=0 order by taxname');
                while (!$recordSet->EOF) {
                         echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
                         $recordSet->MoveNext();
                };
                echo '</select></td></tr>';
          };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CREDIT_LIMIT'].':</td><td><input type="text" name="creditlimit" size="30" maxlength="20" onChange="validatenum(this)"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SALES_GL_ACCOUNT'].':</td><td><select name="salesglacctid">';
          $recordSet = &$conn->Execute('select id, name, description from glaccount where (companyid='.sqlprep($active_company).' or companyid=0) and accounttypeid=50 order by name');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SALES_PERSON'].':</td><td><select name="salesmanid"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select salesman.id, company.companyname from salesman,company where salesman.companyid=company.id and salesman.salesman=1 and salesman.cancel=0 order by company.companyname');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SERVICE_REP'].':</td><td><select name="servicerepid"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select salesman.id, company.companyname from salesman,company where salesman.companyid=company.id and salesman.servicerep=1 and salesman.cancel=0 order by company.companyname');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_TERMS'].':</td><td><select name="invoicetermsid"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id, verbal from invoiceterms where ar=1 and cancel=0');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_QUOTE_COMMENT'].':</td><td><select name="quotecommentid"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id, comments from quotecomment where cancel=0');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHARGE_INTEREST'].'?:</td><td><select name="interest"'.INC_TEXTBOX.'><option value="0">No<option value="1">Yes</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BILL_TO_ATTN'].':</td><td><input type="text" name="billtoattnname" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_QUOTE_ATTN'].':</td><td><input type="text" name="quoteattnname" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHARGE_CODE'].':</td><td><input type="text" name="chargecode" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
    };

     function formarcustomerselect($name) {
          global $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER'].' #:</td><td><input type="text" name="'.$name.'" size="30" onchange="validateint(this)"'.INC_TEXTBOX.'><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'lookupcustomer.php?name='.$name.'\',\'cal\',\'dependent=yes,width=450,height=230,screenX=200,screenY=300,titlebar=yes\')"><img src="images/spyglass.png" border="0" alt="Customer Lookup"></a><a href="javascript:doNothing()" onclick="top.newWin = window.open(\'arcustadd.php\',\'cal\',\'dependent=yes,width=600,height=400,screenX=200,screenY=300,titlebar=yes,resizable=yes,scrollbars=yes\')"></td></tr>';
          return 1;
     };

     function formarcustomerupdate($id) {
          global $conn, $lang,$active_company,$active_custcompany;
          if ($active_custcompany) $id=$active_custcompany;
          $recordSet = &$conn->Execute('select company.address1, company.address2, company.city, company.state, company.zip, company.country, company.phone1, company.phone1comment, company.phone2, company.phone2comment, company.phone3, company.phone3comment, company.phone4, company.phone4comment, company.email1, company.email1comment, company.email2, company.email2comment, company.website, company.federalid, company.companyname, customer.taxexemptid, customer.creditlimit, customer.salesglacctid, customer.salesmanid, customer.servicerepid, customer.invoicetermsid, customer.quotecommentid, customer.interest, customer.billtoattnname, customer.quoteattnname, customer.chargecode, customer.salestaxnum, company.mailstop, company.lastchangedate, customer.lastchangedate from company, customer where company.id=customer.companyid and customer.id='.sqlprep($id));
          if ($recordSet&&!$recordSet->EOF) {
               //read data for sales taxes for this customer
               $recordSet4=&$conn->Execute('select id, salestaxid from customersalestax where customerid='.sqlprep($id)) ;
               while ($recordSet4&&!$recordSet4->EOF) {
                      $salescount++;
                      if ($salescount<=MAX_CUSTOMER_SALESTAX) {
                           ${"taxid".$salescount}=$recordSet4->fields[1];
                           ${"taxrecid".$salescount}=$recordSet4->fields[0];
                      };
                      $recordSet4->MoveNext();
               };
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="50" value="'.rtrim($recordSet->fields[20]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td></td><td><input type="text" name="address2" size="30" maxlength="100" value="'.rtrim($recordSet->fields[1]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50" value="'.rtrim($recordSet->fields[2]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20" value="'.rtrim($recordSet->fields[3]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15" value="'.rtrim($recordSet->fields[4]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20" value="'.rtrim($recordSet->fields[5]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAIL_STOP'].':</td><td><input type="text" name="mailstop" size="20" maxlen="20" value="'.rtrim($recordSet->fields[33]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<input type="hidden" name="lastchangedate" value="'.$recordSet->fields[34].'">';
               echo '<input type="hidden" name="lastchangecustomerdate" value="'.$recordSet->fields[35].'">';
               echo '<tr><td></td><th>Phone</th><th>'.$lang['STR_PHONE_DESCRIPTION'].'</th></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" value="'.rtrim($recordSet->fields[6]).'"onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="hidden" name="phone1comment" size="30" maxlength="20" value="Telephone"'.INC_TEXTBOX.'>'.$lang['STR_PHONE'].'</td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FAX'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" value="'.rtrim($recordSet->fields[8]).'"  onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="hidden" name="phone2comment" size="30" maxlength="20" value="Fax"'.INC_TEXTBOX.'>'.$lang['STR_FAX'].'</td></tr>';
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
               if (!$active_custcompany) {
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FEDERAL_ID'].':</td><td><input type="text" name="federalid" size="30" maxlength="100" value="'.rtrim($recordSet->fields[19]).'"'.INC_TEXTBOX.'></td></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_EXEMPTION'].':</td><td><select name="taxexemptid"'.INC_TEXTBOX.'><option value="0">Not Exempt';
                 $recordSet2 = &$conn->Execute('select id, exemptname from taxexempt where cancel=0 order by id');
                 while (!$recordSet2->EOF) {
                    echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$recordSet->fields[21]," selected").'>'.rtrim($recordSet2->fields[1])."\n";
                    $recordSet2->MoveNext();
                 };
                 echo '</select></td></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SALES_TAX_ID'].':</td><td><input type="text" name="salestaxnum" size="30" maxlength="30" value="'.rtrim($recordSet->fields[32]).'"'.INC_TEXTBOX.'></td></tr>';
                 echo '<tr><td></td><th>Sales Tax District</th></tr>';
                 for ($counter=1;$counter<4;$counter++) {
                     echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SALES_TAX'].' '.$counter.':</td><td><select name="taxid'.$counter.'"'.INC_TEXTBOX.'><option value="0">No Tax Selected';
                     $recordSet2 = &$conn->Execute('select id, taxname from salestax where cancel=0 order by taxname');
                     while (!$recordSet2->EOF) {
                              echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],${"taxid".$counter}," selected").'>'.rtrim($recordSet2->fields[1])."\n";
                              $recordSet2->MoveNext();
                     };
                     echo '</select></td><input type="hidden" name="taxrecid'.$counter.'" value="'.${"taxrecid".$counter}.'"></tr>';
                  };


                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CREDIT_LIMIT'].':</td><td><input type="text" name="creditlimit" size="30" maxlength="20" onChange="validatenum(this)" value="'.rtrim($recordSet->fields[22]).'"'.INC_TEXTBOX.'></td></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SALES_GL_ACCOUNT'].':</td><td><select name="salesglacctid"'.INC_TEXTBOX.'>';
                 $recordSet2 = &$conn->Execute('select id, name, description from glaccount where (companyid='.sqlprep($active_company).' or companyid=0) and accounttypeid=50 order by name');
                 while (!$recordSet2->EOF) {
                    echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$recordSet->fields[23]," selected").'>'.rtrim($recordSet2->fields[1]).' - '.rtrim($recordSet2->fields[2])."\n";
                    $recordSet2->MoveNext();
                 };
                 echo '</select></td></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SALES_PERSON'].':</td><td><select name="salesmanid"'.INC_TEXTBOX.'>';
                 $recordSet2 = &$conn->Execute('select salesman.id, company.companyname from salesman,company where salesman.companyid=company.id and salesman.salesman=1 and salesman.cancel=0 and company.cancel=0 order by company.companyname');
                 while (!$recordSet2->EOF) {
                    echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$recordSet->fields[24]," selected").'>'.rtrim($recordSet2->fields[1])."\n";
                    $recordSet2->MoveNext();
                 };
                 echo '</select></td></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SERVICE_REP'].':</td><td><select name="servicerepid"'.INC_TEXTBOX.'>';
                 $recordSet2 = &$conn->Execute('select salesman.id, company.companyname from salesman,company where salesman.companyid=company.id and salesman.servicerep=1 and salesman.cancel=0 and company.cancel=0 order by company.companyname');
                 while (!$recordSet2->EOF) {
                    echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$recordSet->fields[25]," selected").'>'.rtrim($recordSet2->fields[1])."\n";
                    $recordSet2->MoveNext();
                 };
                 echo '</select></td></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_TERMS'].':</td><td><select name="invoicetermsid"'.INC_TEXTBOX.'>';
                 $recordSet2 = &$conn->Execute('select id, verbal from invoiceterms');
                 while (!$recordSet2->EOF) {
                    echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$recordSet->fields[26]," selected").'>'.rtrim($recordSet2->fields[1])."\n";
                    $recordSet2->MoveNext();
                 };
                 echo '</select></td></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_QUOTE_COMMENT'].':</td><td><select name="quotecommentid"'.INC_TEXTBOX.'>';
                 $recordSet2 = &$conn->Execute('select id, comments from quotecomment');
                 while (!$recordSet2->EOF) {
                    echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$recordSet->fields[27]," selected").'>'.rtrim($recordSet2->fields[1])."\n";
                    $recordSet2->MoveNext();
                 };
                 echo '</select></td></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHARGE_INTEREST'].'?:</td><td><select name="interest"'.INC_TEXTBOX.'>';
                 echo '<option value="0">No';
                 if ($recordSet->fields[28]) {
                    echo '<option value="1" selected>Yes';
                 } else {
                    echo '<option value="1">Yes';
                 };
                 echo '</select></td></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BILL_TO_ATTN'].':</td><td><input type="text" name="billtoattnname" size="30" maxlength="30" value="'.rtrim($recordSet->fields[29]).'"'.INC_TEXTBOX.'></td></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_QUOTE_ATTN'].':</td><td><input type="text" name="quoteattnname" size="30" maxlength="30" value="'.rtrim($recordSet->fields[30]).'"'.INC_TEXTBOX.'></td></tr>';
                 echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CHARGE_CODE'].':</td><td><input type="text" name="chargecode" size="30" maxlength="30" value="'.rtrim($recordSet->fields[31]).'"'.INC_TEXTBOX.'></td></tr>';
               };
               return 1;
          } else {
               return 0;
          };
     };

     function formarcarrieradd() {
     	  global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CARRIER_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td></td><td><input type="text" name="address2" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td></td><th>Phone</th><th>'.$lang['STR_PHONE_DESCRIPTION'].'</th></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="text" name="phone1comment" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FAX'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
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
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_NUMBER'].':</td><td><input type="text" name="customernumber" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TRACKING_URL'].':</td><td><input type="text" name="trackingurlbase" size="30" maxlength="150"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TRACKING_NUMBER_VARIABLE'].':</td><td><input type="text" name="trackingurlvarname" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';

     };

     function formarcarrierupdate($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select company.address1, company.address2, company.city, company.state, company.zip, company.country, company.phone1, company.phone1comment, company.phone2, company.phone2comment, company.phone3, company.phone3comment, company.phone4, company.phone4comment, company.email1, company.email1comment, company.email2, company.email2comment, company.website, company.federalid, company.companyname, company.mailstop, company.lastchangedate,carrier.id,company.id from company,carrier where company.id=carrier.companyid and carrier.id='.$id);
          if ($recordSet&&!$recordSet->EOF) {
               $recordSet4=&$conn->Execute('select customernumber,trackingurlbase,trackingurlvarname from carrier where id='.sqlprep($id)) ;
               if ($recordSet4&&!$recordSet4->EOF) {
                    $customernumber=rtrim($recordSet4->fields[0]);
                    $trackingurlbase=rtrim($recordSet4->fields[1]);
                    $trackingurlvarname=rtrim($recordSet4->fields[2]);
               };
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CARRIER_NAME'].':</td><td><input type="text" name="name" size="30" maxlength="50" value="'.rtrim($recordSet->fields[20]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td></td><td><input type="text" name="address2" size="30" maxlength="100" value="'.rtrim($recordSet->fields[1]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50" value="'.rtrim($recordSet->fields[2]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20" value="'.rtrim($recordSet->fields[3]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15" value="'.rtrim($recordSet->fields[4]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20" value="'.rtrim($recordSet->fields[5]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<input type="hidden" name="carrierid" value="'.$recordSet->fields[23].'">';
               echo '<input type="hidden" name="companyid" value="'.$recordSet->fields[24].'">';
               echo '<input type="hidden" name="lastchangedate" value="'.$recordSet->fields[22].'">';
               echo '<tr><td></td><th>Phone</th><th>'.$lang['STR_PHONE_DESCRIPTION'].'</th></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" value="'.rtrim($recordSet->fields[6]).'"  onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="phone1comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[7]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FAX'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" value="'.rtrim($recordSet->fields[8]).'"  onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
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
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CUSTOMER_NUMBER'].':</td><td><input type="text" name="customernumber" size="30" maxlength="100" value="'.$customernumber.'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TRACKING_URL'].':</td><td><input type="text" name="trackingurlbase" size="30" maxlength="150" value="'.$trackingurlbase.'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TRACKING_NUMBER_VARIABLE'].':</td><td><input type="text" name="trackingurlvarname" size="30" maxlength="30" value="'.$trackingurlvarname.'"'.INC_TEXTBOX.'></td></tr>';
               return 1;
          } else {
               return 0;
          };
     };

     function formarcarrierselect($name) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select carrier.id,company.companyname from company,carrier where company.cancel=0 and carrier.companyid=company.id order by company.companyname');
          if (!$recordSet||$recordSet->EOF) return 0;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CARRIER'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

     function formarcarriermethodupdate($carriermethodid) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select company.companyname, carrierservice.description from company,carrier,carrierservice where company.cancel=0 and carrier.companyid=company.id and carrierservice.carrierid=carrier.id and carrierservice.id='.sqlprep($carriermethodid));
          if ($recordSet&&!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CARRIER'].':</td><td>'.rtrim($recordSet->fields[0]).'</td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DESCRIPTION'].':</td><td><input type="text" name="description" value="'.rtrim($recordSet->fields[1]).'" size="30" maxlength="30"></td></tr>';
               return 1;
          } else {
               return 0;
          };
     };

     function formarcarriermethodselect($name, $carrierid) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select id,description from carrierservice where carrierid='.sqlprep($carrierid).' order by description');
          if (!$recordSet||$recordSet->EOF) return 0;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CARRIER_METHOD'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

     function formartaxexemptionupdate($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select exemptname from taxexempt where id='.$id);
          if ($recordSet&&!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_EXEMPTION_REASON'].':</td><td><input type="text" name="exemptname" size="30" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
               return 1;
          } else {
               return 0;
          };
     };

     function formartaxexemptionselect($name) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select id,exemptname from taxexempt where cancel=0 order by id');
          if (!$recordSet||$recordSet->EOF) return 0;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_EXEMPTION_REASON'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

     function formarsalestaxadd() {
          global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_NAME'].':</td><td><input type="text" name="taxname" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_RATE'].':</td><td><input type="text" name="taxrate" size="30" maxlength="10" onChange="validatenum(this)"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_BASE'].':</td><td><select name="taxbase"'.INC_TEXTBOX.'><option value="0">Materials Only<option value="1" selected>Total Sale</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_ACCOUNT'].':</td><td><select name="glacctid"'.INC_TEXTBOX.'>';
          $recordSet2 = &$conn->Execute('select id, name, description from glaccount where (companyid='.sqlprep($active_company).' or companyid=0) and accounttypeid=21 order by name');
          while (!$recordSet2->EOF) {
             echo '<option value="'.$recordSet2->fields[0].'">'.rtrim($recordSet2->fields[1]).' - '.rtrim($recordSet2->fields[2])."\n";
             $recordSet2->MoveNext();
          };
          echo '</select></td></tr>';
     };

     function formarsalestaxupdate($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select taxname, taxrate, taxbase, glacctid from salestax where id='.$id);
          if (!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_NAME'].':</td><td><input type="text" name="taxname" size="30" maxlength="30" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_RATE'].':</td><td><input type="text" name="taxrate" size="30" maxlength="10" onChange="validatenum(this)" value="'.rtrim($recordSet->fields[1]).'"'.INC_TEXTBOX.'></td></tr>';
               if ($recordSet->fields[2]) $selectstr=" selected";
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_BASE'].':</td><td><select name="taxbase"'.INC_TEXTBOX.'><option value="0">Materials Only<option value="1"'.$selectstr.'>Total Sale</select></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GL_ACCOUNT'].':</td><td><select name="glacctid"'.INC_TEXTBOX.'>';
               $recordSet2 = &$conn->Execute('select id, name, description from glaccount where (companyid='.sqlprep($active_company).' or companyid=0) and accounttypeid=21 order by name');
               while (!$recordSet2->EOF) {
                   echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$recordSet->fields[3]," selected").'>'.rtrim($recordSet2->fields[1]).' - '.rtrim($recordSet2->fields[2])."\n";
                   $recordSet2->MoveNext();
               };
               echo '</select></td></tr>';
          };
     };

     function formarsalestaxselect($name) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select id,taxname from salestax where cancel=0 order by id');
          if (!$recordSet||$recordSet->EOF) return 0;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TAX_NAME'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

     function formarsalesmanadd() {
     	  global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Name:</td><td><input type="text" name="name" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'"></td><td><input type="text" name="address2" size="30" maxlength="100"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAIL_STOP'].':</td><td><input type="text" name="mailstop" size="20" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td></td><th>Phone</th><th>'.$lang['STR_PHONE_DESCRIPTION'].'</th></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
          echo '<td><input type="text" name="phone1comment" size="30" maxlength="20"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FAX'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
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
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMMISSION_RATE'].':</td><td><input type="text" name="commissionrate" size="30" onChange="validatenum(this)" maxlength="8"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMMISSION_BASE'].':</td><td><select name="commissionbase"'.INC_TEXTBOX.'><option value="1">Total Sale<option value="0">Profit from sale only</select></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SALES_PERSON'].':</td><td><input type="checkbox" name="salesman" value="1" checked onclick="checkChoice(1)"></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SERVICE_REP'].':</td><td><input type="checkbox" name="servicerep" value="1" onclick="checkChoice(2)"></td></tr>';
     };

     function formarsalesmanselect($name) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select company.id,company.companyname from company,salesman where salesman.companyid=company.id and salesman.cancel=0 order by company.companyname');
          if (!$recordSet||$recordSet->EOF) return 0;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SALES_PERSON'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],$name," selected").'>'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

     function formarsalesmanupdate($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select company.address1, company.address2, company.city, company.state, company.zip, company.country, company.phone1, company.phone1comment, company.phone2, company.phone2comment, company.phone3, company.phone3comment, company.phone4, company.phone4comment, company.email1, company.email1comment, company.email2, company.email2comment, company.website, company.federalid, company.companyname, salesman.commissionrate, salesman.commissionbase, salesman.salesman, salesman.servicerep, company.lastchangedate, company.mailstop from company, salesman where company.id=salesman.companyid and company.id='.$id);
          if ($recordSet&&!$recordSet->EOF) {
               echo '<input type="hidden" name="lastchangedate" value="'.$recordSet->fields[25].'">';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SALES_PERSON'].':</td><td><input type="text" name="name" size="30" maxlength="50" value="'.rtrim($recordSet->fields[20]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ADDRESS'].':</td><td><input type="text" name="address1" size="30" maxlength="100" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td></td><td><input type="text" name="address2" size="30" maxlength="100" value="'.rtrim($recordSet->fields[1]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_CITY'].':</td><td><input type="text" name="city" size="30" maxlength="50" value="'.rtrim($recordSet->fields[2]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_STATE'].':</td><td><input type="text" name="state" size="30" maxlength="20" value="'.rtrim($recordSet->fields[3]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_POSTAL_CODE'].':</td><td><input type="text" name="zip" size="30" maxlength="15" value="'.rtrim($recordSet->fields[4]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COUNTRY'].':</td><td><input type="text" name="country" size="30" maxlength="20" value="'.rtrim($recordSet->fields[5]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MAIL_STOP'].':</td><td><input type="text" name="mailstop" size="20" maxlength="20" value="'.rtrim($recordSet->fields[26]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td></td><th>Phone</th><th>'.$lang['STR_PHONE_DESCRIPTION'].'</th></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PHONE'].':</td><td><input type="text" name="phone1" size="30" maxlength="20" value="'.rtrim($recordSet->fields[6]).'"  onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
               echo '<td><input type="text" name="phone1comment" size="30" maxlength="20" value="'.rtrim($recordSet->fields[7]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_FAX'].':</td><td><input type="text" name="phone2" size="30" maxlength="20" value="'.rtrim($recordSet->fields[8]).'"  onChange="validatePhone(this)"'.INC_TEXTBOX.'></td>';
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
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMMISSION_RATE'].':</td><td><input type="text" name="commissionrate" size="30" onChange="validatenum(this)" maxlength="8" value="'.rtrim($recordSet->fields[21]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMMISSION_BASE'].':</td><td><select name="commissionbase"'.INC_TEXTBOX.'>';
               if (!$recordSet->fields[22]) $salestr=' selected';
               echo '<option value="1">Total Sale<option value="0"'.$salestr.'>Profit from sale only</select></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SALES_PERSON'].':</td><td><input type="checkbox" name="salesman" value="1" '.checkequal(true,$recordSet->fields[23]," checked").' onclick="checkChoice(1)"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SERVICE_REP'].':</td><td><input type="checkbox" name="servicerep" value="1" '.checkequal(true,$recordSet->fields[24]," checked").' onclick="checkChoice(2)"'.INC_TEXTBOX.'></td></tr>';
               return 1;
          } else {
               return 0;
          };
     };

     function formarquotecommentupdate($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select comments from quotecomment where id='.$id);
          if ($recordSet&&!$recordSet->EOF) $comment=rtrim($recordSet->fields[0]);
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_QUOTE_COMMENT'].':</td><td><input type="text" name="comment" size="30" maxlength="100" value="'.$comment.'"'.INC_TEXTBOX.'>';
          return 1;
     };

     function formarquotecommentselect($name) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select id,comments from quotecomment where cancel=0 order by id');
          if (!$recordSet||$recordSet->EOF) return 0;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_QUOTE_COMMENT'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

     function formarinvoicetermsadd() {
          global $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VERBAL'].':</td><td><input type="text" name="verbal" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_PERCENT'].':</td><td><input type="text" name="discountpercent" size="30" onChange="validatenum(this)" maxlength="10"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_DAYS'].':</td><td><input type="text" name="discountdays" size="30" onChange="validatenum(this)" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NET_DUE_DAYS'].':</td><td><input type="text" name="netduedays" size="30" onChange="validatenum(this)" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
     };

     function formarinvoicetermsupdate($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select verbal, discountpercent, discountdays, netduedays from invoiceterms where id='.$id);
          if ($recordSet&&!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_VERBAL'].':</td><td><input type="text" name="verbal" size="30" maxlength="30" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_PERCENT'].':</td><td><input type="text" name="discountpercent" onChange="validatenum(this)" size="30" maxlength="10" value="'.$recordSet->fields[1].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DISCOUNT_DAYS'].':</td><td><input type="text" name="discountdays" onChange="validatenum(this)" size="30" maxlength="4" value="'.$recordSet->fields[2].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NET_DUE_DAYS'].':</td><td><input type="text" name="netduedays" onChange="validatenum(this)" size="30" maxlength="4" value="'.$recordSet->fields[3].'"'.INC_TEXTBOX.'></td></tr>';
               return 1;
          } else {
               return 0;
          };
     };

     function formarinvoicetermsselect($name) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select id,verbal from invoiceterms where ar=1 and cancel=0 order by id');
          if (!$recordSet||$recordSet->EOF) return 0;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_INVOICE_TERMS'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'"'.checkequal($recordSet->fields[0],$name," selected").'>'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

     function stripquotes($text) {
          return substr($text,1,strlen($text)-2);
     };

?>
