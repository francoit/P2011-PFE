<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     function prcompanyadd($address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $name,$mailstop) {
          global $conn, $lang, $userid;
          checkpermissions('pay');
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

     function prcompanyupdate($id, $address1, $address2, $city, $state, $zip, $country, $phone1, $phone1comment, $phone2, $phone2comment, $phone3, $phone3comment, $phone4, $phone4comment, $email1, $email1comment, $email2, $email2comment, $website, $federalid, $companyname,$mailstop,$lastchangedate) {
          global $conn, $lang, $userid;
          checkpermissions('pay');
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
                    if ($conn->Execute("update company set address1=".sqlprep($address1).", address2=".sqlprep($address2).", city=".sqlprep($city).", state=".sqlprep($state).", zip=".sqlprep($zip).", country=".sqlprep($country).", phone1=".sqlprep($phone1).", phone1comment=".sqlprep($phone1comment).", phone2=".sqlprep($phone2).", phone2comment=".sqlprep($phone2comment).", phone3=".sqlprep($phone3).", phone3comment=".sqlprep($phone3comment).", phone4=".sqlprep($phone4).", phone4comment=".sqlprep($phone4comment).", email1=".sqlprep($email1).", email1comment=".sqlprep($email1comment).", email2=".sqlprep($email2).", email2comment=".sqlprep($email2comment).", website=".sqlprep($website).", companyname=".sqlprep($companyname).", federalid=".sqlprep($federalid).", lastchangeuserid=".sqlprep($userid).", mailstop=".sqlprep($mailstop)." where id=".sqlprep($id).' and lastchangedate='.sqlprep($lastchangedate)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_COMPANY']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
          };
      };


     function prcompanydelete($id) {
          global $conn, $lang, $userid;
          checkpermissions('pay');
          if ($conn->Execute("update company set cancel='1', canceldate=NOW(), canceluserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_DELETING_COMPANY']);
               return 0;
          } else {
               return 1;
          };
     };

     function prpaytypeadd($name,$description,$multiplier,$vacation,$sick) {
          global $conn, $lang, $active_company;
          checkpermissions('pay');
          if ($conn->Execute("insert into prpaytype (name, description, multiplier, vacation, sick, gencompanyid) VALUES (".sqlprep($name).", ".sqlprep($description).", ".sqlprep($multiplier).", ".sqlprep($vacation).", ".sqlprep($sick).", ".sqlprep($active_company).")") === false) {
               echo texterror("Error adding pay type.");
               return 0;
          } else {
               echo textsuccess($lang['STR_PAY_TYPE_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function prpaytypeupdate($id, $name, $description, $multiplier, $vacation, $sick) {
          global $conn, $lang;
          checkpermissions('pay');
          if ($conn->Execute("update prpaytype set name=".sqlprep($name).", description=".sqlprep($description).", multiplier=".sqlprep($multiplier).", vacation=".sqlprep($vacation).", sick=".sqlprep($sick)." where id=".sqlprep($id)) === false) {
               echo texterror("Error updating pay type.");
               return 0;
          } else {
               echo textsuccess($lang['STR_PAY_TYPE_UPDATED_SUCCESSFULLY']);
               return 1;
          };
     };

     function prpaytypedelete($id) {
          global $conn, $lang, $active_company;
          checkpermissions('pay');
          if ($conn->Execute("delete from prpaytype where gencompanyid=".sqlprep($active_company)." and id=".sqlprep($id)) === false) {
               echo texterror("Error deleting pay type.");
               return 0;
          } else {
               echo textsuccess($lang['STR_PAY_TYPE_DELETED_SUCCESSFULLY']);
               return 1;
          };
     };

     function prdedgroupadd($name) {
          global $conn, $lang, $active_company;
          checkpermissions('pay');
          if ($conn->Execute("insert into prdedgroup (name, gencompanyid) VALUES (".sqlprep($name).", ".sqlprep($active_company).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_DEDUCTION_GROUP']);
               return 0;
          } else {
               echo textsuccess($lang['STR_DEDUCTION_GROUP_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function prdedgroupupdate($id, $name) {
          global $conn, $lang;
          checkpermissions('pay');
          if ($conn->Execute("update prdedgroup set name=".sqlprep($name)." where id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_UPDATING_DEDUCTION_GROUP']);
               return 0;
          } else {
               echo textsuccess($lang['STR_DEDUCTION_GROUP_UPDATED_SUCCESSFULLY']);
               return 1;
          };
     };

     function prdedgroupdelete($id) {
          global $conn, $lang, $active_company;
          checkpermissions('pay');
          if ($conn->Execute("delete from prdedgroup where gencompanyid=".sqlprep($active_company)." and id=".sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_DEDUCTION_GROUP']);
               return 0;
          } else {
               echo textsuccess($lang['STR_DEDUCTION_GROUP_DELETED_SUCCESSFULLY']);
               return 1;
          };
     };


     function prmaritalstatusfromid($id) {
     	  global $conn, $lang;
          switch ($id) {
               case 1:
                    return $lang['STR_SINGLE'];
                    break;
               case 2:
                    return $lang['STR_MARRIED_JOINT'];
                    break;
               case 3:
                    return $lang['STR_MARRIED_SEPARATE'];
                    break;
               case 4:
                    return $lang['STR_HEAD_OF_HOUSEHOLD'];
                    break;
          };
     };

          function prcompanyupdatebended($shift2multiplier, $shift3multiplier, $sickleavehrsperyear, $maxsickleave, $minwageperhr ,$lastchangedate) {
          global $conn, $lang, $userid,$active_company;
          checkpermissions('pay');
          $recordSet=&$conn->Execute("select count(*) from prcompany where id=".sqlprep($active_company)." and lastchangedate=".sqlprep($lastchangedate));
          if ($recordSet&&!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($active_company,"prcompany","id");
                    return 0;
               } else {
                    if ($conn->Execute('update prcompany set shift2multiplier='.sqlprep($shift2multiplier).',shift3multiplier='.sqlprep($shift3multiplier).',sickleavehrsperyear='.sqlprep($sickleavehrsperyear).',maxsickleave='.sqlprep($maxsickleave).',minwagehr='.sqlprep($minwagehr).',lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($active_company)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_PRCOMPANY']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
          };
      };

      function prcompanyinfoupdate($fedtaxnum,$w2companyname,$w2companyaddress1,$w2companyaddress2,$w2citystatezip, $stateunemplnum,$glcheckaccountid,$glfitpayableid,$glficapayableid,$glficaexpenseid,$glfuipayableid,$glfuiexpenseid,$glmedicarepayableid,$glmedicareexpenseid,$glsuipayableid,$glsuiexpenseid,$glmiscdedpayableid,$gltaxexemptexpenseid,$glworkmanscomppayableid,$glworkmanscompexpenseid,$post2payables,$checkacctid,$autoprintdeposit,$depositvendorid,$lastchangedate) {
          global $conn, $lang, $userid,$active_company;
          checkpermissions('pay');
          $recordSet=&$conn->Execute("select count(*) from prcompany where id=".sqlprep($active_company)." and lastchangedate=".sqlprep($lastchangedate));
          if ($recordSet&&!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($active_company,"prcompany","id");
                    return 0;
               } else {
                    if ($conn->Execute('update prcompany set fedtaxnum='.sqlprep($fedtaxnum).',w2companyname='.sqlprep($w2companyname).',w2companyaddress1='.sqlprep($w2companyaddress1).',w2companyaddress2='.sqlprep($w2companyaddress2).',w2citystatezip='.sqlprep($w2citystatezip).', stateunemplnum='.sqlprep($stateunemplnum).',glcheckaccountid='.sqlprep($glcheckaccountid).',glfitpayableid='.sqlprep($glfitpayableid).',glficapayableid='.sqlprep($glficapayableid).',glficaexpenseid='.sqlprep($glficaexpenseid).',glfuipayableid='.sqlprep($glfuipayableid).',glfuiexpenseid='.sqlprep($glfuiexpenseid).',glmedicarepayableid='.sqlprep($glmedicarepayableid).',glmedicareexpenseid='.sqlprep($glmedicareexpenseid).',glsuipayableid='.sqlprep($glsuipayableid).',glsuiexpenseid='.sqlprep($glsuiexpenseid).',glmiscdedpayableid='.sqlprep($glmiscdedpayableid).',gltaxexemptexpenseid='.sqlprep($gltaxexemptexpenseid).',glworkmanscomppayableid='.sqlprep($glworkmanscomppayableid).',glworkmanscompexpenseid='.sqlprep($glworkmanscompexpenseid).',post2payables='.sqlprep($post2payables).',checkacctid='.sqlprep($checkacctid).',autoprintdeposit='.sqlprep($autoprintdeposit).',depositvendorid='.sqlprep($depositvendorid).',lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($active_company)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_PRCOMPANY']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
          };
      };

     function prvacationupdate($vacid, $yrsbeforeaccrue, $vacdaysperyear, $maxaccrue,$lastchangedate) {
          global $conn, $lang, $userid,$active_company;
          checkpermissions('pay');
          $recordSet=&$conn->Execute("select count(*) from prvacation where id=".sqlprep($vacid)." and lastchangedate=".sqlprep($lastchangedate));
          if ($recordSet&&!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($vacid,"prvacation","id");
                    return 0;
               } else {
                    if ($conn->Execute('update prvacation set yrsbeforeaccrue='.sqlprep($yearsbeforeaccrue).',vacdaysperyear='.sqlprep($vacdaysperyear).',maxaccrue='.sqlprep($maxaccrue).',lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($vacid)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_PRVACATION']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
          };
      };

     function prbendedupdate($id, $paytype, $name, $howfig,$prdedgroupid,$rate,$ceilingperyear,$payableglacctid,$expenseglacctid,$vendorid,$lastchangedate) {
          global $conn, $lang, $userid,$active_company;
          checkpermissions('pay');
          $recordSet=&$conn->Execute("select count(*) from prbended where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate));
          if ($recordSet&&!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"prbended","id");
                    return 0;
               } else {
                    if ($conn->Execute('update prbended set paytype='.sqlprep($paytype).', name='.sqlprep($name).', howfig='.sqlprep($howfig).', prdedgroupid='.sqlprep($prdedgroupid).', rate='.sqlprep($rate).', payableglacctid='.sqlprep($payableglacctid).',lastchangeuserid='.sqlprep($userid).',ceilingperyear='.sqlprep($ceilingperyear).',expenseglacctid='.sqlprep($expenseglacctid).',vendorid='.sqlprep($vendorid).',lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_PRBENDED']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
          };
      };
     function prpensionupdate($id, $name,$w2plantype,$w2plansubtype,$employercontribhow,$employercontribute,$employermaxmatchpercent,$mustbeinplan,$calcbasis,$prdedgroupid,$paytype,$payableglacctid,$expenseglacctid,$federalincometax,$stateincometax,$localincometax,$cityincometax,$employeefica,$companyfica,$fui,$sui,$workmanscomp,$vendorid,$lastchangedate) {
          global $conn, $lang, $userid,$active_company;
          checkpermissions('pay');
          $recordSet=&$conn->Execute("select count(*) from prpension where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate));
          if ($recordSet&&!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"prpension","id");
                    return 0;
               } else {
                    if ($conn->Execute('update prpension set name='.sqlprep($name).', w2plantype='.sqlprep($w2plantype).', w2plansubtype='.sqlprep($w2plansubtype).', employercontribhow='.sqlprep($employercontribhow).', employercontribute='.sqlprep($employercontribute).', payableglacctid='.sqlprep(${"payableglacctid".$i}).',lastchangeuserid='.sqlprep($userid).',employermaxmatchpercent='.sqlprep($employermaxmatchpercent).',expenseglacctid='.sqlprep(${"expenseglacctid".$i}).',vendorid='.sqlprep(${"vendorid".$i}).', mustbeinplan='.sqlprep($mustbeinplan).', calcbasis='.sqlprep($calcbasis).', prdedgroupid='.sqlprep($prdedgroupid).', paytype='.sqlprep($paytype).', federalincometax='.sqlprep($federalincometax).', stateincometax='.sqlprep($stateincometax).', localincometax='.sqlprep($localincometax).',cityincometax='.sqlprep($cityincometax).', employeefica='.sqlprep($employeefica).',companyfica='.sqlprep($companyfica).', fui='.sqlprep($fui).', sui='.sqlprep($sui).',workmanscomp='.sqlprep($workmanscomp).',lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_PRPENSION']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
          };
      };

     function formprcompanyadd() {
     	  global $conn, $lang;
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

     function formprcompanyupdate($id) {
          global $conn, $lang;
          $recordSet = &$conn->Execute('select company.address1, company.address2, company.city, company.state, company.zip, company.country, company.phone1, company.phone1comment, company.phone2, company.phone2comment, company.phone3, company.phone3comment, company.phone4, company.phone4comment, company.email1, company.email1comment, company.email2, company.email2comment, company.website, company.federalid, company.companyname, company.mailstop, company.lastchangedate from company where company.id='.$id);
          if ($recordSet&&!$recordSet->EOF) {
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
               echo '<input type="hidden" name="lastchangedate" value="'.$recordSet->fields[22].'">';
               return 1;
          } else {
               return 0;
          };
     };

     function formprpaytypeadd() {
          global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME'].':</td><td><input type="text" name="name" size="4" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DESCRIPTION'].':</td><td><input type="text" name="description" size="30" maxlength="40"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MULTIPLIER'].':</td><td><input type="text" name="multiplier" onChange="validatenum(this)" size="30" maxlength="8"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TYPE'].':</td><td><select name="type"'.INC_TEXTBOX.'><option value="1">'.$lang['STR_VACATION'].'<option value="2">'.$lang['STR_SICK'].'<option value="0" selected>'.$lang['STR_OTHER'].'</td></tr>';
          return 1;
     };

     function formprpaytypeselect($name) {
          global $conn, $lang, $active_company;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PAY_TYPE'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id, name, description from prpaytype where gencompanyid='.sqlprep($active_company).' order by name');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

     function formprpaytypeupdate($id) {
          global $conn, $lang, $active_company;
          $recordSet = &$conn->Execute('select name, description, multiplier, vacation, sick from prpaytype where id='.$id.' and gencompanyid='.sqlprep($active_company));
          if (!$recordSet->EOF) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME'].':</td><td><input type="text" name="name" size="4" maxlength="4" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DESCRIPTION'].':</td><td><input type="text" name="description" size="30" maxlength="40" value="'.rtrim($recordSet->fields[1]).'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_MULTIPLIER'].':</td><td><input type="text" name="multiplier" onChange="validatenum(this)" size="30" maxlength="8" value="'.$recordSet->fields[2].'"'.INC_TEXTBOX.'></td></tr>';
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_TYPE'].':</td><td><select name="type"'.INC_TEXTBOX.'><option value="1"'.checkequal($recordSet->fields[3],1,' selected').'>'.$lang['STR_VACATION'].'<option value="2"'.checkequal($recordSet->fields[4],1,' selected').'>'.$lang['STR_SICK'].'<option value="0"'.checkequal($recordSet->fields[3]+$recordSet->fields[4],0,' selected').'>'.$lang['STR_OTHER'].'</select></td></tr>';
          };
          return 1;
     };

     function formprdedgroupadd() {
     	  global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME'].':</td><td><input type="text" name="name" size="3" maxlength="3"'.INC_TEXTBOX.'></td></tr>';
          return 1;
     };

     function formprdedgroupselect($name) {
          global $conn, $lang, $active_company;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_DEDUCTION_GROUP'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id, name from prdedgroup where gencompanyid='.sqlprep($active_company).' order by name');
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

     function formprdedgroupupdate($id) {
          global $conn, $lang, $active_company;
          $recordSet = &$conn->Execute('select name from prdedgroup where id='.$id.' and gencompanyid='.sqlprep($active_company));
          if (!$recordSet->EOF) echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_NAME'].':</td><td><input type="text" name="name" size="3" maxlength="3" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'></td></tr>';
          return 1;
     };

     ?>
