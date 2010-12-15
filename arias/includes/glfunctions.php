<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     function glaccountadd($name, $companyid, $description, $accounttypeid, $summaryaccountid) {
          global $conn, $lang, $userid, $active_company;
          checkpermissions('gl');
          $recordSet = &$conn->Execute('select name, description from glaccount where name='.sqlprep($name)." and (companyid='0' or companyid=".sqlprep($companyid).")");
          if ($recordSet->EOF) { //if the account doesn't exist
               if ($conn->Execute("insert into glaccount (name, description, accounttypeid, companyid, summaryaccountid, lastchangeuserid ) VALUES (".sqlprep($name).", ".sqlprep($description).", ".sqlprep($accounttypeid).", ".sqlprep($companyid).", ".sqlprep($summaryaccountid).", ".sqlprep($userid).")") === false) {
                    echo texterror($lang['STR_ERROR_ADDING_ACCOUNT']);
                    return 0;
               } else {
                    echo textsuccess($lang['STR_ACCOUNT_ADDED_SUCCESSFULLY']);
                    return 1;
                    if (!$summaryaccountid) {
                         $recordSet=&$conn->Execute ('select id from glaccount where name='.sqlprep($name).' and companyid='.sqlprep($companyid));
                         if ($recordSet&&!$recordSet->EOF) {
                              $glaccountid=$recordSet->fields[0];
                              if ($conn->Execute("update glaccount SET summaryaccountid=".sqlprep($glaccountid)." where name=".sqlprep($name).' and companyid='.sqlprep($companyid)) === false) {
                                   echo texterror($lang['STR_ERROR_UPDATING_SUMMARY_ACCOUNT_ID']);
                                   return 0;
                              };
                         };
                    };
               };
          } else {
               echo texterror($lang['STR_ERROR_ADDING_ACCOUNT'].'. '.$lang['STR_ENTRY_CONFLICTS_WITH_ACCOUNT'].' '.rtrim($recordSet->fields[0]).' - '.rtrim($recordSet->fields[1]));
               return 0;
          };
     };

     function glaccountdelete($id) {
          global $conn, $lang, $active_company;
          checkpermissions('gl');
          if ($conn->Execute('delete from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') and id='.sqlprep($id)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_ACCOUNT']);
               return 0;
          } else {
               echo textsuccess($lang['STR_ACCOUNT_DELETED_SUCCESSFULLY']);
               return 1;
          };
     };

     function glaccountupdate($id, $account, $name, $accounttypeid, $companyid, $summaryaccountid,$lastchangedate) {
          global $conn, $lang, $userid;
          checkpermissions('gl');
          $recordSet=&$conn->Execute("select count(*) from glaccount where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate));
          if ($recordSet&&!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"glaccount","id") ;
                    return 0;
               } else {
                    if ($conn->Execute("update glaccount set description=".sqlprep($account).", name=".sqlprep($name).", accounttypeid=".sqlprep($accounttypeid).", companyid=".sqlprep($companyid).", summaryaccountid=".sqlprep($summaryaccountid).", lastchangeuserid=".sqlprep($userid)." where id=".sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_ACCOUNT']);
                         return 0;
                    } else {
                         echo textsuccess($lang['STR_ACCOUNT_UPDATED_SUCCESSFULLY']);
                         return 1;
                    };
               };
          };
     };


     function glaccounttypeadd($accounttype) {
          global $conn, $lang;
          checkpermissions('gl');
          if ($conn->Execute("insert into accounttype (description) VALUES (".sqlprep($accounttype).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_ACCOUNT_TYPE']);
               return 0;
          } else {
               echo textsuccess($lang['STR_ACCOUNT_TYPE_ADDED_SUCCESSFULLY']);
               return 1;
          };
     };

     function glaccounttypeupdate($id, $accounttype,$lastchangedate) {
          global $conn, $lang;
          checkpermissions('gl');
          if ($conn->Execute("update accounttype set description=".sqlprep($accounttype)." where id=".sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_ACCOUNT_TYPE']);
               return 0;
          } else {
                         echo textsuccess($lang['STR_ACCOUNT_TYPE_UPDATED_SUCCESSFULLY']);
               return 1;
          };
     };

     function glaccounttypedelete($id) {
          global $conn, $lang;
          checkpermissions('gl');
          if ($conn->Execute("delete from accounttype where id=".sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_DELETING_ACCOUNT_TYPE']);
               return 0;
          } else {
                         echo textsuccess($lang['STR_ACCOUNT_TYPE_DELETED_SUCCESSFULLY']);
               return 1;
          };
     };

     function formglaccountadd($accounttypeid) {
          global $conn, $lang, $multi_company, $active_company;
          checkpermissions('gl');
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_GENERAL_LEDGER_ACCOUNT'].':</td><td><input type="text" name="name" size="10" maxlength="6"'.INC_TEXTBOX.'></td></tr><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Account Description:</td><td><input type="text" name="description" size="40" maxlength="30"'.INC_TEXTBOX.'></td></tr><tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Summarize to Account:</td><td><select name="summaryaccountid"'.INC_TEXTBOX.'><option value="0">0 - not summarized';
          $recordSet = &$conn->Execute('select id, name,description from glaccount where accounttypeid='.$accounttypeid.' and (companyid=0 or companyid='.sqlprep($active_company).') order by name');
          while ($recordSet&&!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1]).' - '.rtrim($recordSet->fields[2])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          if ($multi_company) {
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SPECIFIC_COMPANY'].':</td><td><select name="companyid"'.INC_TEXTBOX.'><option value="0">All companies';
               $recordSet = &$conn->Execute('select id, name from gencompany order by name');
               while ($recordSet&&!$recordSet->EOF) {
                    echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
                    $recordSet->MoveNext();
               };
               echo '</select></td></tr>';
          };
          return 1;
     };

     function formglaccountupdate($id) {
          global $conn, $lang, $multi_company, $active_company;
          checkpermissions('gl');
          $recordSet = &$conn->Execute('select description, name, accounttypeid, companyid, summaryaccountid,lastchangedate from glaccount where id='.sqlprep($id).' and (companyid=0 or companyid='.sqlprep($active_company).')');
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ACCOUNT_DESCRIPTION'].' :</td><td><input type="text" name="account" size="30" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'>';
          echo '<input type="hidden" name="lastchangedate" value="'.$recordSet->fields[5].'">';
          $recordSet2 = &$conn->Execute('select id,description from accounttype order by id');
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_ACCOUNT_TYPE'].':</td><td><select name="accounttypeid"'.INC_TEXTBOX.'>';
          while ($recordSet2&&!$recordSet2->EOF) {
               echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[2],$recordSet2->fields[0]," selected").'>'.rtrim($recordSet2->fields[1])."\n";
               $recordSet2->MoveNext();
          };
          echo '</select></td></tr>';
          if ($multi_company) {
               $recordSet2 = &$conn->Execute('select id,name from gencompany order by id');
               echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPANY'].':</td><td><select name="companyid"'.INC_TEXTBOX.'><option value="0">All';
               while ($recordSet2&&!$recordSet2->EOF) {
                    echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[3],$recordSet2->fields[0]," selected").'>'.rtrim($recordSet2->fields[1])."\n";
                    $recordSet2->MoveNext();
               };
               echo '</select></td></tr>';
          };
          $recordSet2 = &$conn->Execute('select id, name,description from glaccount where accounttypeid='.$recordSet->fields[2].' and (companyid=0 or companyid='.sqlprep($active_company).') order by name');
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SUMMARIZE_TO_ACCOUNT'].':</td><td><select name="summaryaccountid"'.INC_TEXTBOX.'><option value="0">0 - not summarized';
          while ($recordSet2&&!$recordSet2->EOF) {
               echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet->fields[4],$recordSet2->fields[0]," selected").'>'.rtrim($recordSet2->fields[1]).' - '.rtrim($recordSet2->fields[2])."\n";
               $recordSet2->MoveNext();
          };
          echo '</select></td></tr>';
     };

     function formglaccountselect($name) {
          global $conn, $lang, $active_company;
          checkpermissions('gl');
          echo '<tr><td>'.$lang['STR_GENERAL_LEDGER_ACCOUNT'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id,name,description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by name');
          while ($recordSet&&!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])." - ".rtrim($recordSet->fields[2])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
     };

     function formglaccounttypeupdate($id) {
          global $conn, $lang;
          checkpermissions('gl');
          echo '<tr><td>'.$lang['STR_GENERAL_LEDGER_ACCOUNT_TYPE'].':</td><td>';
          $recordSet = &$conn->Execute('select description from accounttype where id='.sqlprep($id));
          if ($recordSet&&!$recordSet->EOF) echo '<input type="text" name="accounttype" size="30" value="'.rtrim($recordSet->fields[0]).'"'.INC_TEXTBOX.'>';
          echo '</td></tr>';
          return 1;
     };

     function formglaccounttypeselect($name) {
          global $conn, $lang;
          checkpermissions('gl');
          echo '<tr><td>'.$lang['STR_GENERAL_LEDGER_ACCOUNT_TYPE'].':</td><td><select name="'.$name.'"'.INC_TEXTBOX.'>';
          $recordSet = &$conn->Execute('select id,description from accounttype order by description');
          while ($recordSet&&!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.rtrim($recordSet->fields[1])."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          return 1;
     };

     function formglPie($id,$editprint) {
         global $conn, $lang;
         if ($editprint&&$id) { //read existing pie data
               $recordSet = &$conn->Execute('select name, description,begindate,findate,lastchangedate from glpie where id='.sqlprep($id));
               if ($recordSet&&!$recordSet->EOF) {
                     $name=rtrim($recordSet->fields[0]);
                     $description=rtrim($recordSet->fields[1]);
                     $begindate=$recordSet->fields[2];
                     $findate=$recordSet->fields[3];
                     $lastchangedate=$recordSet->fields[4];
                     $AddUpdate=1;
                     $recordSet1=&$conn->Execute('select id,name,begindate,findate,lastchangedate from glpieslice where glpieid='.sqlprep($id).' order by name');
                     while ($recordSet1&&!$recordSet1->EOF) { //read array of pie slice general data
                             $slicecount+=1;
                             ${"sliceid".$slicecount}=$recordSet1->fields[0];
                             ${"slicename".$slicecount}=rtrim($recordSet1->fields[1]);
                             ${"slicebegindate".$slicecount}=$recordSet1->fields[2];
                             ${"slicefindate".$slicecount}=$recordSet1->fields[3];
                             ${"slicelastchangedate".$slicecount}=$recordSet1->fields[4];
                             $recordSet1->MoveNext();
                     };
               } else {
                 die(texterror($lang['STR_COULD_NOT_FIND_PIE']));//something went wrong - did not find record
               };
          } else {
            $AddUpdate=0;
          };
          if ($slicecount<12) $slicecount=12;//set up at least space for max 12 slices.
          //display editable info
          echo '<input type="hidden" name="lastchangedate" value="'.$lastchangedate.'">';
          echo '<tr><td>'.$lang['STR_PIE_NAME'].':</td><td><input type="text" name="name" maxlen="30" maxsize="30" value="'.$name.'"'.INC_TEXTBOX.'>';
          echo '<tr><td>'.$lang['STR_PIE_DESCRIPTION'].':</td><td colspan="3"><input type="text" name="description" maxlen="100" maxsize="50" value="'.$description.'"'.INC_TEXTBOX.'>';
          echo '<tr><td>'.$lang['STR_BEGIN_DATE'].':</td><td><input type="text" name="begindate" maxlen="10" maxsize="10" value="'.$begindate.'" onchange="formatDate(this)"'.INC_TEXTBOX.'>';
          echo '<tr><td>'.$lang['STR_END_DATE'].':</td><td><input type="text" name="findate" maxlen="10" maxsize="10" value="'.$findate.'" onchange="formatDate(this)"'.INC_TEXTBOX.'>';
          echo '<tr><th>'.$lang['STR_SLICE_NAME'].'</th><th>'.$lang['STR_SLICE_BEGIN_DATE'].'</th><th>'.$lang['STR_SLICE_END_DATE'].'</th><th>'.$lang['STR_DELETE_SLICE'].' <input type="checkbox" checked'.INC_TEXTBOX.'></th></tr>';
          for ($scount=1;$scount<=12;$scount++) {
                echo '<tr><td><input type="text" name="slicename'.$scount.'" value="'.${"slicename".$scount}.'"'.INC_TEXTBOX.'></td>';
                echo '<td><input type="text" name="slicebegindate'.$scount.'" value="'.${"slicebegindate".$scount}.'" onchange="formatDate(this)"'.INC_TEXTBOX.'></td>';
                echo '<td><input type="text" name="slicefindate'.$scount.'" value="'.${"slicefindate".$scount}.'" onchange="formatDate(this)"'.INC_TEXTBOX.'></td>';
                echo '<td><input name="slicedelete'.$scount.'" type="checkbox"'.INC_TEXTBOX.'></td></tr>';
                echo '<input type="hidden" name="sliceid'.$scount.'" value="'.${"sliceid".$scount}.'">';
                echo '<input type="hidden" name="slicelastchangedate'.$scount.'" value="'.${"slicelastchangedate".$scount}.'">';
          };

     };

     function DeletePie($id){
              checkpermissions('gl');
              global $conn, $lang,$userid;
              $recordSet=&$conn->Execute('update glpie set cancel=1, canceluserid='.sqlprep($userid).' where id='.sqlprep($id));
     };

     function DeletePieSlice($id,$sliceid) {
          checkpermissions('gl');
          global $conn, $lang;
          if ($sliceid>0) {
            if ($conn->Execute("delete from glpieslice where id=".sqlprep($sliceid)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_GL_PIE_SLICE']);
               return 0;
            } else {
               echo textsuccess($lang['STR_GL_PIE_SLICE_DELETED_SUCCESSFULLY']);
               return 1;
            };
          } else { //delete all slices this pie
            if ($conn->Execute("delete from glpieslice where glpieid=".sqlprep($id)) === false) {
               return 0;
            } else {
               return 1;
            };

          };
     };

     function DeletePieSliceDetail($id,$sliceid,$slicedetailid) {
          global $conn, $lang,$userid;
          checkpermissions('gl');
          if ($slicedetailid>0) {//delete specific slice detail only
            if ($conn->Execute("delete from glpieslicedetail where id=".sqlprep($slicedetailid)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_GL_PIE_SLICE_DETAIL']);
               return 0;
            } else {
               echo textsuccess($lang['STR_GL_PIE_SLICE_DETAIL_DELETED_SUCCESSFULLY']);
               return 1;
            };

          } elseif ($sliceid>0){ //delete all slice details this slice
            if ($conn->Execute("delete from glpieslicedetail where glpiesliceid=".sqlprep($sliceid)) === false) {
               return 0;
            } else {
               return 1;
            };
          } else { //delete all slice details this pie
            $recordSet=&$conn->Execute('select id from glpieslice where glpieid='.sqlprep($id));
            while ($recordSet&&!$recordSet->EOF) {
               if ($conn->Execute("delete from glpieslicedetail where glpiesliceid=".sqlprep($recordSet->fields[0])) === false) {
                    return 0;
               } else {
                    return 1;
               };
               $recordSet->MoveNext();
            };
          };
     };

     function GlPieAddUpdate ($AddUpdate,$id,$name,$description,$begindate,$findate,$lastchangedate) {
           global $conn, $lang,$userid;
           checkpermissions('gl');
           if ($AddUpdate==0) {          //1=update, 0=add
                if ($conn->Execute('insert into glpie (name,description,begindate,findate,entrydate,entryuserid,lastchangeuserid) values ('.sqlprep($name).', '.sqlprep($description).', '.sqlprep($begindate).', '.sqlprep($findate).', NOW(), '.sqlprep($userid).', '.sqlprep($userid).')') === false) {
                     return 0;
                } else {
                     return 1;
                };
           } else {
             $recordSet=&$conn->Execute("select count(*) from glpie where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate));
             if ($recordSet&&!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"glpie","id") ;
                    return 0;
               } else {
                    if ($conn->Execute('update glpie set name='.sqlprep($name).', description='.sqlprep($description).', begindate='.sqlprep($begindate).', findate='.sqlprep($findate).', lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_GL_PIE']);
                         return 0;
                    } else {
                         echo textsuccess($lang['STR_GL_PIE_UPDATED_SUCCESSFULLY']);
                         return 1;
                    };
               };
             };
           };
     };

     function GlPieSliceAddUpdate ($AddUpdate,$pieid,$id,$name,$begindate,$findate,$lastchangedate) {
           global $conn, $lang,$userid;
           checkpermissions('gl');
           if ($AddUpdate==0) {          //1=update, 0=add
                if ($conn->Execute('insert into glpieslice (glpieid,name,begindate,findate,lastchangeuserid) values ('.sqlprep($pieid).', '.sqlprep($name).', '.sqlprep($begindate).', '.sqlprep($findate).', '.sqlprep($userid).')') === false) {
                     return 0;
                } else {
                     return 1;
                };
           } else {
             $recordSet=&$conn->Execute("select count(*) from glpieslice where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate));
             if ($recordSet&&!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"glpieslice","id") ;
                    return 0;
               } else {
                    if ($conn->Execute('update glpieslice set name='.sqlprep($name).',  begindate='.sqlprep($begindate).', findate='.sqlprep($findate).', lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_GL_PIE_SLICE']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
             };
          };
     };

     function GLPieSliceDetailAddUpdate($AddUpdate,$pieid,$sliceid,$id,$glaccountid,$companyid,$lastchangedate) {
           global $conn, $lang,$userid;
           checkpermissions('gl');
           if ($AddUpdate==0){          //1=update, 0=add
                $recordSet=&$conn->Execute('select * from glpieslicedetail where glaccountid='.sqlprep($glaccountid).' and glpiesliceid='.sqlprep($sliceid).' and companyid='.sqlprep($companyid));
                if ($recordSet&&!$recordSet->EOF) {
                      echo texterror($lang['STR_ACCOUNT_ALREADY_EXISTS_IN_THIS_PIE_SLICE']);
                      return 0;// already have this account/company combo for this slice
                };
                if ($conn->Execute('insert into glpieslicedetail (glpiesliceid,glaccountid,companyid,lastchangeuserid) values ('.sqlprep($sliceid).', '.sqlprep($glaccountid).', '.sqlprep($companyid).', '.sqlprep($userid).')') === false) {
                     echo texterror($lang['STR_ERROR_ADDING_GL_PIE_SLICE_DETAIL']);
                     return 0;
                } else {
                     echo textsuccess('added OK');
                     return 1;
                };
           } else {
             $recordSet=&$conn->Execute("select count(*) from glpieslicedetail where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate));
             if ($recordSet&&!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"glpieslicedetail","id") ;
                    return 0;
               } else {
                    if ($conn->Execute('update glpieslicedetail set glaccountid='.sqlprep($glaccountid).',  companyid='.sqlprep($companyid).',  lastchangeuserid='.sqlprep($userid).' where id='.sqlprep($id)) === false) {
                         echo texterror($lang['STR_ERROR_UPDATING_GL_PIE_SLICE_DETAIL']);
                         return 0;
                    } else {
                         return 1;
                    };
               };
             };
          };


     };

     function gltransvoucheradd($invoicenumber,$description,$dateofinvoice,$wherefrom) {
     global $conn, $lang, $userid, $active_company;
          if ($conn->Execute('insert into gltransvoucher (voucher,description,wherefrom,status,cancel,companyid,standardset,entrydate,lastchangedate,lastchangeuserid,entryuserid) values ('.sqlprep($invoicenumber).",".sqlprep("Add: ".$description).",".sqlprep($wherefrom).",".sqlprep("0").",".sqlprep("0").",".sqlprep($active_company).",".sqlprep("0").",".sqlprep($dateofinvoice).",NOW(),".sqlprep($userid).",".sqlprep($userid).")") === false) {
               return 0;
          } else {
               $recordSet=$conn->SelectLimit("select id from gltransvoucher where voucher=".sqlprep($invoicenumber)." and wherefrom=".sqlprep($wherefrom)." and companyid=".sqlprep($active_company)." and cancel=0 order by lastchangedate desc",1);
               if ($recordSet&&!$recordSet->EOF) $id = $recordSet->fields[0];
               return $id;
          };
     };

     function gltransvoucherdelete($voucherid)  {
     global $conn, $lang, $userid, $active_company ;
          if ($conn->Execute('update gltransvoucher set cancel=1,canceluserid='.sqlprep($userid).',canceldate=NOW() where status=0 and companyid='.sqlprep($active_company).' and id='.sqlprep($voucherid)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_GL_TRANSVOUCHER']);
               return 0;
          } else {
               return 1;
          };
     };

     function gltransactionadd($voucherid,$amount,$glaccountid) {
     global $conn, $lang, $userid, $active_company;
          if ($conn->Execute('insert into gltransaction (voucherid,glaccountid,amount) values ('.sqlprep($voucherid).",".sqlprep($glaccountid).",".sqlprep($amount).")") === false) {
               echo texterror($lang['STR_ERROR_ADDING_GL_TRANSACTION']);
               return 0;
          } else {
               return 1;
          };
      };

     function gltransactiondelete($voucherid)  {
     global $conn, $lang, $userid ;
          if ($conn->Execute('delete from gltransaction where voucherid='.sqlprep($voucherid)) === false) {
               echo texterror($lang['STR_ERROR_DELETING_GL_TRANSACTIONS']);
               return 0;
          } else {
               return $id;
          };
     };
?>
