<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php  //GLREPBALANCE.PHP
        echo texttitle($lang['STR_BALANCE_SHEET_FOR'] .$companyname);
        echo '<center>';
        if ($endyear&&$endmonth) {
                $enddate=$endyear.'-'.$endmonth;
                echo texttitle('(Period: '.$endyear.'/'.$endmonth.')');
                $timestamp = mktime(0,0,0,$endmonth+1,1,$endyear);
                $enddate=date("Y",$timestamp).'-'.date("m",$timestamp);

                $recordSet = &$conn->Execute('select fiscalbeginmonth from glcompany where id='.sqlprep($active_company));
                if ($recordSet&&!$recordSet->EOF) $fiscalmonth=$recordSet->fields[0];
                if (!$fiscalmonth) $fiscalmonth=1;
                if ($fiscalmonth>$bgdatemonth) {
                    $fiscalyear=$bgdateyear-1;
                } else {
                    $fiscalyear=$bgdateyear;
                };
                $beginyear=$fiscalyear;
                $beginmonth=$fiscalmonth;
                $begindate=$beginyear.'-'.$beginmonth;

                //read each account into a totalling account
                if (!$summary) {
                        $query1 = "select sum(gltransaction.amount), glaccount.name, glaccount.description, accounttype.id, accounttype.description from gltransaction, gltransvoucher, glaccount, accounttype where accounttype.id=glaccount.accounttypeid and gltransaction.glaccountid=glaccount.id and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date <".sqlprep($enddate.'-01')." and accounttype.id<50 group by gltransaction.glaccountid, glaccount.name, glaccount.description, accounttype.id, accounttype.description order by accounttype.id, glaccount.name";
                        $query2 = "select sum(gltransaction.amount), glaccount.name, glaccount.description, accounttype.id, accounttype.description from gltransaction, gltransvoucher, glaccount, accounttype where accounttype.id=glaccount.accounttypeid and gltransaction.glaccountid=glaccount.id and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($begindate.'-01')." and gltransvoucher.post2date <".sqlprep($enddate.'-01')." and accounttype.id>=50 group by gltransaction.glaccountid, glaccount.name, glaccount.description, accounttype.id, accounttype.description order by accounttype.id, glaccount.name";
                } else {
						$conn->Execute("DROP TABLE tmp");
						$conn->Execute("DROP TABLE tmp2");

						$conn->Execute("CREATE TEMPORARY TABLE tmp (amount decimal(14,2) not null default 0, name char(6) not null default '', glaccount_description char(30) not null default '', accounttype_id int(11) default 0, accounttype_description	char(30) default '', glaccountid double default 0, summaryaccountid	double not null default 0)");

						$conn->Execute("CREATE TEMPORARY TABLE tmp2 (amount decimal(14,2) not null default 0, name char(6) not null default '', glaccount_description char(30) not null default '', accounttype_id int(11) default 0, accounttype_description	char(30) default '', glaccountid double default 0, summaryaccountid	double not null default 0)");

                        $query1 = "insert into tmp select sum(gltransaction.amount), glaccount.name, glaccount.description, accounttype.id, accounttype.description, glaccount.id, glaccount.summaryaccountid from gltransaction, gltransvoucher, glaccount, accounttype where accounttype.id=glaccount.accounttypeid and gltransaction.glaccountid=glaccount.id and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date <".sqlprep($enddate.'-01')." and accounttype.id<50 group by glaccount.id, glaccount.name, glaccount.description, accounttype.id, accounttype.description order by accounttype.id, glaccount.name";

                        $query2 = "insert into tmp select sum(gltransaction.amount), glaccount.name, glaccount.description, accounttype.id, accounttype.description, glaccount.id, glaccount.summaryaccountid from gltransaction, gltransvoucher, glaccount, accounttype where accounttype.id=glaccount.accounttypeid and gltransaction.glaccountid=glaccount.id and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($begindate.'-01')." and gltransvoucher.post2date <".sqlprep($enddate.'-01')." and accounttype.id>=50 group by glaccount.id, glaccount.name, glaccount.description, accounttype.id, accounttype.description order by accounttype.id, glaccount.name";

						$conn->Execute($query1);
						$conn->Execute($query2);

                        $query1 = "select ".
							"sum(tmp.amount), ".
							"glaccount.name, ".
							"glaccount.description, ".
							"tmp.accounttype_id, ".
							"tmp.accounttype_description, ".
							"tmp.glaccountid ".
							"from tmp, glaccount ".
							"where (tmp.summaryaccountid = 0 and glaccount.id = tmp.glaccountid) or (tmp.summaryaccountid = glaccount.id)".
							"group by glaccount.id order by accounttype_id";

                        $query2 = "select ".
							"sum(tmp2.amount), ".
							"glaccount.name, ".
							"glaccount.description, ".
							"tmp2.accounttype_id, ".
							"tmp2.accounttype_description, ".
							"tmp2.glaccountid ".
							"from tmp2, glaccount ".
							"where (tmp2.summaryaccountid = 0 and glaccount.id = tmp2.glaccountid) or (tmp2.summaryaccountid = glaccount.id)".
							"group by glaccount.id order by accounttype_id";

                };
                $recordSet = &$conn->Execute($query1);
                $recordSet2 = &$conn->Execute($query2);
                if ((!$recordSet||$recordSet->EOF)&&(!$recordSet2||$recordSet2->EOF)) die(texterror($lang['STR_NO_ENTIES_FOUND_MATCHING_CRITERIA']));
                echo '<table border=1>';
                while (!$recordSet->EOF) {
                        if ($oldaccounttypeid!=$recordSet->fields[3]) {
                             //change of account type - need to display total
                             //if total has a value and type<50, then print
                             if ($oldaccounttypeid<50)  if ($subsetpertotal) echo '<tr><td></td><td colspan="5" width="69%"><div align="right"><b>'.$lang['STR_TOTAL'].' '.$oldtype.'</b></div></td><td></td><td width="10%"><div align="right"><nobr>'.checkdec($subsetpertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td></tr>';
                             $subsetpertotal=0;$credit="&nbsp;";
                             if ($oldaccounttypeid<21&&$recordSet->fields[3]>=21) { //check to see if end of major grouping: ASSETS
                                        echo '<tr><td colspan="6" width="74%"><div align="right"><b>'.$lang['STR_TOTAL_ASSETS'].'</b></div></td><td></td><td></td><td width="10%"><div align="right"><nobr>'.checkdec($accttypepertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td></tr><tr></tr>';
                                        $accttypepertotal=0;
                             } elseif ($oldaccounttypeid<23&&$recordSet->fields[3]>=23) { //check to see if end of major grouping: LIABILITIES
                                        echo '<tr><td colspan="5" width="74%"><div align="right"><b>'.$lang['STR_TOTAL_LIABILITIES'].'</b></div></td><td></td><td></td><td></td><td width="10%"><div align="right"><nobr>'.checkdec($accttypepertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td></tr><tr></tr>';
                                        $accttypepertotal=0;
                             };
                             if ($recordSet->fields[3]<50) echo '<tr><td colspan="8" width="100%"><b>&nbsp;'.$recordSet->fields[4].'</b></td><td></td></tr>';
                        };
                        $oldaccounttypeid=$recordSet->fields[3];
                        $oldtype=$recordSet->fields[4];
                        $persub=$recordSet->fields[0];
                        if ($recordSet->fields[3]>=21&&$recordSet->fields[3]<=23) $persub=0-$persub;
                        if ($recordSet->fields[3]>=30) $persub=0-$persub;
                        $subsetpertotal+=$persub;
                        $accttypepertotal+=$persub;
                        if ($recordSet->fields[3]>=21) $totalliabequper+=$persub;
                        if ($recordSet->fields[3]>=50) $earnytd+=$persub;
                        if ($recordSet->fields[3]<50) echo '<tr><td colspan="5" width="69%">&nbsp;&nbsp;&nbsp;'.$recordSet->fields[2].'</td><td width="5%">'.$recordSet->fields[1].'</td><td width="10%"><div align="right"><nobr>'.checkdec($persub,PREFERRED_DECIMAL_PLACES).'</nobr></div></td></tr>';
                        $recordSet->MoveNext();
                };
                while (!$recordSet2->EOF) {
                        if ($oldaccounttypeid!=$recordSet2->fields[3]) {
                             //change of account type - need to display total
                             //if total has a value and type<50, then print
                             if ($oldaccounttypeid<50)  if ($subsetpertotal) echo '<tr><td></td><td colspan="5" width="69%"><div align="right"><b>Total '.$oldtype.'</b></div></td><td></td><td width="10%"><div align="right"><nobr>'.checkdec($subsetpertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td></tr>';
                             $subsetpertotal=0;
                             if ($oldaccounttypeid<21&&$recordSet2->fields[3]>=21) { //check to see if end of major grouping: ASSETS
                                        echo '<tr><td colspan="6" width="74%"><div align="right"><b>'.$lang['STR_TOTAL_ASSETS'].'</b></div></td><td></td><td></td><td width="10%"><div align="right"><nobr>'.checkdec($accttypepertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td></tr><tr></tr>';
                                        $accttypepertotal=0;
                             } elseif ($oldaccounttypeid<23&&$recordSet2->fields[3]>=23) { //check to see if end of major grouping: LIABILITIES
                                        echo '<tr><td colspan="5" width="74%"><div align="right"><b>'.$lang['STR_TOTAL_LIABILITIES'].'</b></div></td><td></td><td></td><td></td><td width="10%"><div align="right"><nobr>'.checkdec($accttypepertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td></tr><tr></tr>';
                                        $accttypepertotal=0;
                             };
                        };
                        $oldaccounttypeid=$recordSet2->fields[3];
                        $oldtype=$recordSet2->fields[4];
                        $persub=$recordSet2->fields[0];
                        if ($recordSet2->fields[3]>=21&&$recordSet2->fields[3]<=23) $persub=0-$persub;
                        if ($recordSet2->fields[3]>=30) $persub=0-$persub;
                        $subsetpertotal+=$persub;
                        $accttypepertotal+=$persub;
                        if ($recordSet2->fields[3]>=21) $totalliabequper+=$persub;
                        if ($recordSet2->fields[3]>=50) $earnytd+=$persub;
                        $recordSet2->MoveNext();
                };
                //end of entries - need to display totals
                //if total has a value, then print
                if ($oldaccounttypeid<50) if ($subsetpertotal) echo '<tr><td></td><td colspan="5" width="69%"><div align="right"><b>TOTAL '.$oldtype.'</b></div></td><td></td><td width="10%"><div align="right"><nobr>'.checkdec($subsetpertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td></tr>';
                if ($oldaccounttypeid<21) { //check to see if end of major grouping: ASSETS
                   echo '<tr><td colspan="6" width="74%"><div align="right"><b>'.$lang['STR_TOTAL_ASSETS'].'</b></div></td><td></td><td></td><td width="10%"><div align="right"><nobr>'.checkdec($accttypepertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td></tr><tr></tr>';
                   $accttypepertotal=0;
                } elseif ($oldaccounttypeid<23) { //check to see if end of major grouping: LIABILITIES
                   echo '<tr><td colspan="5" width="74%"><div align="right"><b>'.$lang['STR_TOTAL_LIABILITIES'].'</b></div></td><td></td><td></td><td></td><td width="10%"><div align="right"><nobr>'.checkdec($accttypepertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td></tr><tr></tr>';
                   $accttypepertotal=0;
                };
                if ($accttypepertotal) echo '<tr><td></td><td colspan="5" width="69%"><div align="right"><b>'.$lang['STR_SALES_YEARS_TO_DATE'].'</b></div></td><td></td><td width="10%"><div align="right"><nobr>'.checkdec($earnytd,PREFERRED_DECIMAL_PLACES).'</nobr></div></td></tr>';
               echo '<tr><td colspan="5" width="74%"><div align="right"><b>'.$lang['STR_TOTAL_EQUITY'].'</b></div></td><td></td><td></td><td></td><td width="10%"><div align="right"><nobr>'.checkdec($accttypepertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td></tr><tr></tr>';
               echo '<tr><td colspan="6" width="74%"><div align="right"><b>'.$lang['STR_TOTAL_LIABILITIES_AND_EQUITY'].'</b></div></td><td></td><td></td><td width="10%"><div align="right"><nobr>'.checkdec($totalliabequper,PREFERRED_DECIMAL_PLACES).'</nobr></div></td></tr>';
               echo '</table>';

        } else {
                $timestamp =  time();
                $date_time_array =  getdate($timestamp);
                $hours =  $date_time_array["hours"];
                $minutes =  $date_time_array["minutes"];
                $seconds =  $date_time_array["seconds"];
                $month =  $date_time_array["mon"];
                $day =  $date_time_array["mday"];
                $year =  $date_time_array["year"];
                $timestamp =  mktime($hour, $minute, $second, $month-1, $day, $year);
                $bgdateyear=date("Y", $timestamp);
                $bgdatemonth=date("m", $timestamp);
                echo '<form action="glrepbalance.php" method="post"><table>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_PERIOD'].':</td><td><input type="text" name="endyear" onchange="validateint(this)" value="'.$bgdateyear.'" size="14" maxlength="4"'.INC_TEXTBOX.'><input type="text" name="endmonth" onchange="validateint(this)" value="'.$bgdatemonth.'" size="14" maxlength="4"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SUMMARIZED'].':</td><td><input type="checkbox" name="summary" value="1"'.INC_TEXTBOX.'></td></tr>';
                echo '</table><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
                
                echo '</center>';
        };
?>

<?php include('includes/footer.php'); ?>
