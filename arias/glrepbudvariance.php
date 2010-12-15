<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<script language="JavaScript">
     var calDateFormat='yyyy-MM-DD';
</script>
<script language="JavaScript" src="js/calendar.js"></script>
<?
        echo texttitle($lang['STR_BUDGET_VARIANCE_STATEMENT_FOR'] .$companyname);
        echo '<center>';
        if ($beginyear&&$beginmonth&&$endyear&&$endmonth) { //if the user has already submitted info
                $begindate=$beginyear.'-'.$beginmonth;
                $enddate=$endyear.'-'.$endmonth;
                if ($begindate!=$enddate) {
                     echo texttitle('('.$lang['STR_PERIOD'].': '.$beginyear.'/'.$beginmonth.' - '.$endyear.'/'.$endmonth.')');
                } else {
                     echo texttitle('('.$lang['STR_PERIOD'].': '.$beginyear.'/'.$beginmonth.')');
                };
                $recordSet = &$conn->Execute('select fiscalbeginmonth from glcompany where id='.$active_company);
                if ($recordSet&&!$recordSet->EOF) {
                        $fiscalbeginmonth=$recordSet->fields[0];
                } else {
                        $fiscalbeginmonth=1;
                };
                $timestamp=mktime(0,0,0,$endmonth+1,1,$endyear);
                $enddate=date("Y",$timestamp).'-'.date("m",$timestamp);
                $timestamp =  time();
                $date_time_array = getdate($timestamp);
                $year =  $date_time_array["year"];
                $month =  $date_time_array["mon"];
                if ($month < $fiscalbeginmonth) $year+=-1;
                $recordSet = &$conn->Execute("select sum(gltransaction.amount) from gltransaction, gltransvoucher, glaccount where glaccount.accounttypeid >= 50 and gltransaction.glaccountid=glaccount.id and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($year.'-'.$fiscalbeginmonth.'-01')." and gltransvoucher.post2date <".sqlprep(($year+1).'-'.$fiscalbeginmonth.'-01'));
                if (!$recordSet->EOF) $ytdtotal = checkdec($recordSet->fields[0],PREFERRED_DECIMAL_PLACES); //read month-to-date for sales?
                $recordSet = &$conn->Execute("select sum(gltransaction.amount) from gltransaction, gltransvoucher, glaccount where glaccount.accounttypeid >= 50 and gltransaction.glaccountid=glaccount.id and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($begindate.'-01')." and gltransvoucher.post2date <".sqlprep($enddate.'-01'));
                if (!$recordSet->EOF) $pertotal = checkdec($recordSet->fields[0],PREFERRED_DECIMAL_PLACES);
                if (!$summary) {
                        $query = "select sum(gltransaction.amount), glaccount.name, glaccount.description, accounttype.id, accounttype.description, gltransaction.glaccountid from gltransaction, gltransvoucher, glaccount, accounttype where accounttype.id=glaccount.accounttypeid and glaccount.accounttypeid >= 50 and gltransaction.glaccountid=glaccount.id and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($begindate.'-01')." and gltransvoucher.post2date <".sqlprep($enddate.'-01')." group by gltransaction.glaccountid, glaccount.name, glaccount.description, accounttype.id, accounttype.description order by accounttype.id, glaccount.name";
                        $query2 = "select sum(gltransaction.amount), glaccount.name, glaccount.description, accounttype.id, accounttype.description, gltransaction.glaccountid from gltransaction, gltransvoucher, glaccount, accounttype where accounttype.id=glaccount.accounttypeid and glaccount.accounttypeid >= 50 and gltransaction.glaccountid=glaccount.id and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($year.'-'.$fiscalbeginmonth.'-01')." and gltransvoucher.post2date <".sqlprep(($year+1).'-'.$fiscalbeginmonth.'-01')." group by gltransaction.glaccountid, glaccount.name, glaccount.description, accounttype.id, accounttype.description order by accounttype.id, glaccount.name";
                } else {

						$conn->Execute("DROP TABLE tmp");
						$conn->Execute("DROP TABLE tmp2");

						$conn->Execute("CREATE TEMPORARY TABLE tmp (amount decimal(14,2) not null default 0, name char(6) not null default '', glaccount_description char(30) not null default '', accounttype_id int(11) default 0, accounttype_description	char(30) default '', glaccountid double default 0, summaryaccountid	double not null default 0)");

                        $query = "select ".
							"sum(gltransaction.amount), ".
							"glaccount.name, ".
							"glaccount.description AS glaccount_description, ".
							"accounttype.id AS accounttype_id, ".
							"accounttype.description AS accounttype_description, ".
							"gltransaction.glaccountid, ".
							"glaccount.summaryaccountid ".
							"from gltransaction, gltransvoucher, accounttype, glaccount ".
							"where accounttype.id=glaccount.accounttypeid ".
							"and glaccount.accounttypeid >= 50 ".
							"and gltransaction.glaccountid=glaccount.id ".
							"and gltransaction.voucherid=gltransvoucher.id ".
							"and gltransvoucher.status=1 ".
							"and gltransvoucher.cancel=0 ".
							"and gltransvoucher.companyid=".sqlprep($active_company)." ".
							"and gltransvoucher.post2date >=".sqlprep($begindate.'-01')." ".
							"and gltransvoucher.post2date <".sqlprep($enddate.'-01')." ".
							"group by gltransaction.glaccountid, glaccount.name, glaccount.description, accounttype.id, accounttype.description order by accounttype.id, glaccount.name";

						$conn->Execute("INSERT INTO tmp ".$query);

                        $query = "select ".
							"sum(tmp.amount), ".
							"glaccount.name, ".
							"glaccount.description, ".
							"tmp.accounttype_id, ".
							"tmp.accounttype_description, ".
							"tmp.glaccountid ".
							"from tmp, glaccount ".
							"where (tmp.summaryaccountid = 0 and glaccount.id = tmp.glaccountid) or (tmp.summaryaccountid = glaccount.id)".
							"group by glaccount.id";

						$conn->Execute("CREATE TEMPORARY TABLE tmp2 (amount decimal(14,2) not null default 0, name char(6) not null default '', glaccount_description char(30) not null default '', accounttype_id int(11) default 0, accounttype_description	char(30) default '', glaccountid double default 0, summaryaccountid	double not null default 0)");

                        $query2 = "select sum(gltransaction.amount), glaccount.name, glaccount.description, accounttype.id, accounttype.description, gltransaction.glaccountid, glaccount.summaryaccountid  from gltransaction, gltransvoucher, glaccount, accounttype where accounttype.id=glaccount.accounttypeid and glaccount.accounttypeid >= 50 and gltransaction.glaccountid=glaccount.id and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($year.'-'.$fiscalbeginmonth.'-01')." and gltransvoucher.post2date <".sqlprep(($year+1).'-'.$fiscalbeginmonth.'-01')." group by gltransaction.glaccountid, glaccount.name, glaccount.description, accounttype.id, accounttype.description order by accounttype.id, glaccount.name";

						$conn->Execute("INSERT INTO tmp2 ".$query2);

                        $query2 = "select ".
							"sum(tmp2.amount), ".
							"glaccount.name, ".
							"glaccount.description, ".
							"tmp2.accounttype_id, ".
							"tmp2.accounttype_description, ".
							"tmp2.glaccountid ".
							"from tmp2, glaccount ".
							"where (tmp2.summaryaccountid = 0 and glaccount.id = tmp2.glaccountid) or (tmp2.summaryaccountid = glaccount.id)".
							"group by glaccount.id";
                };

                $recordSet = &$conn->Execute($query);
                $recordSet2 = &$conn->Execute($query2);

				$conn->Execute("DROP TABLE tmp");
				$conn->Execute("DROP TABLE tmp2");

                if (!$recordSet||$recordSet->EOF||!$recordSet2||$recordSet2->EOF)  die(texterror($lang['STR_NO_ENTIES_FOUND_MATCHING_CRITERIA']));
                echo '<table border=0 cellspacing=2><tr><th colspan="6" rowspan="2" width="74%">'.$lang['STR_ACCOUNT_DESCRIPTION_NAME'].'</th><th width=30% colspan="3"><nobr>---- '.$lang['STR_THIS_PERIOD'].' ----</nobr></th><th width=30% colspan="3"><nobr>---- '.$lang['STR_YEAR_TO_DATE'].' ----</nobr></th></tr>';
                echo '<tr><th width="10%"><nobr>'.$lang['STR_ACTUAL'].'</nobr></th><th width="10%"><nobr>'.$lang['STR_BUDGET'].'</nobr></th><th width="10%"><nobr>'.$lang['STR_VARIANCE'].'</nobr></th>';
                echo '<th width="10%"><nobr>'.$lang['STR_ACTUAL'].'</nobr></th><th width="10%"><nobr>'.$lang['STR_BUDGET'].'</nobr></th><th width="10%"><nobr>'.$lang['STR_VARIANCE'].'</nobr></th></tr>';
                while (!$recordSet->EOF&&!$recordSet2->EOF) {
                        if ($oldaccounttypeid!=$recordSet->fields[3]) {
                                if ($subsetpertotal||$subsetytdtotal) echo '<tr><td colspan="6" width="69%" align="right"><b>&nbsp;'.$acctname.' '.$lang['STR_TOTAL'].' </b></td><td width="10%" align="right"><nobr>'.checkdec($subsetpertotal,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($budsubsetpertotal,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec(0-($subsetpertotal-$budsubsetpertotal),PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($subsetytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($budsubsetytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec(0-($budsubsetytdtotal-$subsetytdtotal),PREFERRED_DECIMAL_PLACES).'</nobr></td></tr>';
                                $acctname=$recordSet->fields[4];
                                echo '<tr><td colspan="10" width="100%"><b>&nbsp;'.$recordSet->fields[4].'</b></td></tr>';
                                $oldaccounttypeid=$recordSet->fields[3];
                                $subsetpertotal=0;
                                $subsetytdtotal=0;
                                $budsubsetpertotal=0;
                                $budsubsetytdtotal=0;
                        };
                        //now read budget for year/month for this account
                        $year2=$year;
                        for ($mo=1;$mo<=12;$mo++) {
                              $budpersub=0;
                              $budytdsub=0;
                        };
                        if ($fiscalbeginmonth>$curmonth) {
                           $recordSet3 = &$conn->Execute('select jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,decm from glbudgets where glaccountid='.sqlprep($recordSet->fields[5]).' and budgetyear='.sqlprep($year));
                           $year2=$year+1;
                           if (!$recordSet3->EOF) {
                               for ($mo=1;$mo<=12;$mo++) {
                                 if ($mo>=$fiscalbeginmonth) {
                                      $budytdsub+=$recordSet3->fields[$mo-1];
                                      if ($mo==$fiscalbeginmonth) $budpersub=$recordSet3->fields[$mo-1];
                                 };
                               };
                           };
                        };

                        $recordSet3 = &$conn->Execute('select jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,decm from glbudgets where glaccountid='.sqlprep($recordSet->fields[5]).' and budgetyear='.sqlprep($year2));
                        $year2=$year+1;
                        if (!$recordSet3->EOF) {
                               for ($mo=1;$mo<=12;$mo++) {
                                 if ($mo<=12-$fiscalbeginmonth) {
                                      $budytdsub+=$recordSet3->fields[$mo-1];
                                      if ($mo==$fiscalbeginmonth) $budpersub=$recordSet3->fields[$mo-1];
                                 };
                               };
                        };
                        $persub=$recordSet->fields[0];
                        $ytdsub=$recordSet2->fields[0];
                        if ($recordSet->fields[3] <= 60) {
                                $persub=0-$persub;
                                $ytdsub=0-$ytdsub;
                                $budpersub=0-$budpersub;
                                $budytdsub=0-$budytdsub;
                        };
                        $subsetpertotal+=$persub;
                        $subsetytdtotal+=$ytdsub;
                        $budsubsetpertotal+=$budpersub;
                        $budsubsetytdtotal+=$budytdsub;
                        echo '<tr><td colspan="5" width="69%">&nbsp;&nbsp;&nbsp;'.$recordSet->fields[2].'</td><td width="5%">'.$recordSet->fields[1].'</td><td width="10%" align="right"><nobr>'.checkdec($persub,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($budpersub,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec(0-($budpersub-$persub),PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($ytdsub,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($budytdsub,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec(0-($budytdsub-$ytdsub),PREFERRED_DECIMAL_PLACES).'</nobr></td></tr>';
                        $recordSet->MoveNext();
                        $recordSet2->MoveNext();
                };
               if ($subsetpertotal||$subsetytdtotal||$budsubsetpertotal||$budsubsetytdtotal) echo '<tr><td colspan="6" width="69%" align="right"><b>&nbsp;'.$acctname.' '.$lang['STR_TOTAL'].' </b></td><td width="10%" align="right"><nobr>'.num_format($subsetpertotal,2).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($budsubsetpertotal,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($budsubsetpertotal-$subsetpertotal,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($subsetytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($budsubsetytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($budsubsetytdtotal-$subsetytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></td></tr>';
               echo '<tr><td colspan="6" width="74%"><b>'.$lang['STR_TOTAL'].'</b></td><td width="10%" align="right"><nobr>'.checkdec($pertotal,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($budpertotal,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($budpertotal-$pertotal,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($ytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($budytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></td><td width="10%" align="right"><nobr>'.checkdec($budytdtotal-$ytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></td></tr>';
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
                echo '<form action="glrepbudvariance.php" method="post" name="mainform"><table>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_PERIOD'].':</td><td><input type="text" name="beginyear" onchange="validateint(this)" value="'.$bgdateyear.'" size="14" maxlength="4"'.INC_TEXTBOX.'><input type="text" name="beginmonth" onchange="validateint(this)" value="'.$bgdatemonth.'" size="14" maxlength="2"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_PERIOD'].':</td><td><input type="text" name="endyear" onchange="validateint(this)" value="'.$bgdateyear.'" size="14" maxlength="4"'.INC_TEXTBOX.'><input type="text" name="endmonth" onchange="validateint(this)" value="'.$bgdatemonth.'" size="14" maxlength="2"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SUMMARIZED'].':</td><td><input type="checkbox" name="summary" value="1"'.INC_TEXTBOX.'></td></tr>';
                echo '</table><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
                
                echo '</center>';
        };
?>

<?php include('includes/footer.php'); ?>
