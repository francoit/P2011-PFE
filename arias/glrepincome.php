<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
echo texttitle($lang['STR_INCOME_STATEMENT_FOR']  .$companyname);
	echo '<center>';
if ($beginyear&&$beginmonth&&$endyear&&$endmonth&&!$compare)
	{ //if the user has already submitted info

	$begindate=$beginyear.'-'.$beginmonth;
	$enddate=$endyear.'-'.$endmonth;

	if ($begindate!=$enddate)
	{
		  echo texttitle('(Period: '.$beginyear.'/'.$beginmonth.' - '.$endyear.'/'.$endmonth.')');
	} else
	{
		  echo texttitle('(Period: '.$beginyear.'/'.$beginmonth.')');
	}
	$timestamp=mktime(0,0,0,$endmonth+1,1,$endyear);
	$enddate=date("Y",$timestamp).'-'.date("m",$timestamp);

	if ($compareset)
	{
		$begindate2=$beginyear2.'-'.$beginmonth2;
		$enddate2=$endyear2.'-'.$endmonth2;

		echo '<center>Compared to</center><br>';

		if ($begindate2!=$enddate2)
		{
			echo texttitle('(Period: '.$beginyear2.'/'.$beginmonth2.' - '.$endyear2.'/'.$endmonth2.')');
		} else
		{
			echo texttitle('(Period: '.$beginyear2.'/'.$beginmonth2.')');
		}

		$timestamp=mktime(0,0,0,$endmonth2+1,1,$endyear2);
		$enddate2=date("Y",$timestamp).'-'.date("m",$timestamp);
	}

	$recordSet = &$conn->Execute('select fiscalbeginmonth from glcompany where id='.$active_company);
	if ($recordSet&&!$recordSet->EOF)
	{
			$fiscalbeginmonth=$recordSet->fields[0];
	}
	else
	{
			$fiscalbeginmonth=1;
	}

	$recordSet = &$conn->Execute("select abs(sum(gltransaction.amount)) from gltransaction, gltransvoucher, glaccount where glaccount.accounttypeid >= 50 and glaccount.accounttypeid <= 60 and gltransaction.glaccountid=glaccount.id and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($beginyear.'-'.$fiscalbeginmonth.'-01')." and gltransvoucher.post2date <=".sqlprep(($beginyear+1).'-'.$fiscalbeginmonth.'-01'));

	if (!$recordSet->EOF)
	{
		$ytdtotal = checkdec($recordSet->fields[0],PREFERRED_DECIMAL_PLACES);
	}

	if ($ytdtotal<=0)
	{
		$ytdtotal=1;
	}

	$recordSet = &$conn->Execute("select abs(sum(gltransaction.amount)) from gltransaction, gltransvoucher, glaccount where glaccount.accounttypeid >= 50 and glaccount.accounttypeid <= 60 and gltransaction.glaccountid=glaccount.id and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($begindate.'-01')." and gltransvoucher.post2date <".sqlprep($enddate.'-01'));

	if (!$recordSet->EOF)
	{
		$pertotal = checkdec($recordSet->fields[0],PREFERRED_DECIMAL_PLACES);
	}

	if ($pertotal<=0)
	{
		$pertotal=1;
	}

	if ($compareset)
	{
		  $recordSet = &$conn->Execute("select abs(sum(gltransaction.amount)) from gltransaction, gltransvoucher, glaccount where glaccount.accounttypeid >= 50 and glaccount.accounttypeid <= 60 and gltransaction.glaccountid=glaccount.id and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($beginyear2.'-'.$fiscalbeginmonth.'-01')." and gltransvoucher.post2date <=".sqlprep(($beginyear2+1).'-'.$fiscalbeginmonth.'-01'));
		  if (!$recordSet->EOF) $ytdtotal2 = checkdec($recordSet->fields[0],PREFERRED_DECIMAL_PLACES);
		  $recordSet = &$conn->Execute("select abs(sum(gltransaction.amount)) from gltransaction, gltransvoucher, glaccount where glaccount.accounttypeid >= 50 and glaccount.accounttypeid <= 60 and gltransaction.glaccountid=glaccount.id and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($begindate2.'-01')." and gltransvoucher.post2date <".sqlprep($enddate2.'-01'));
		  if (!$recordSet->EOF) $pertotal2 = checkdec($recordSet->fields[0],PREFERRED_DECIMAL_PLACES);
	}

	if ($compareset) $comparesetstr = '<th width="10%"><nobr>Period 2</nobr></th><th width="3%"><nobr>%</nobr></th><th width="10%"><nobr>YTD</nobr></th><th width="3%"><nobr>%</nobr></th>';

	echo '<table border=1><tr><th colspan="6" width="69%">Account Description - Name</th><th width="10%"><nobr>Period 1</nobr></th><th width="3%"><nobr>%</nobr></th><th width="10%"><nobr>YTD</nobr></th><th width="3%"><nobr>%</nobr></th>'.$comparesetstr.'</tr>';

	if ($summary)
	{
		$conn->Execute("DROP TABLE IF EXISTS tmp");

		$conn->Execute("CREATE TABLE tmp (glaccountid double default 0, name char(6) not null default '',glaccount_description char(30) not null default '', accounttype_id int(11) default 0, accounttype_description	char(30) default '',  summaryaccountid	double not null default 0)");

		$conn->Execute('INSERT INTO tmp select glaccount.id, glaccount.name, glaccount.description, accounttype.id, accounttype.description, glaccount.summaryaccountid from accounttype left join glaccount on accounttype.id=glaccount.accounttypeid where glaccount.accounttypeid >=50  order by accounttype.id, glaccount.id');

		$recordSetacct = &$conn->Execute("select distinct ".
			"glaccount.id, ".
			"glaccount.name, ".
			"glaccount.description, ".
			"tmp.accounttype_id, ".
			"tmp.accounttype_description, ".
			"glaccount.summaryaccountid ".
			"from tmp, glaccount ".
			"where (tmp.summaryaccountid = 0 and glaccount.id = tmp.glaccountid) or (tmp.summaryaccountid = glaccount.id) ".
			"");
	}
	else
	{
		$recordSetacct = &$conn->Execute('select glaccount.id, glaccount.name, glaccount.description, accounttype.id, accounttype.description from accounttype left join glaccount on accounttype.id=glaccount.accounttypeid where glaccount.accounttypeid >=50 group by glaccount.id, glaccount.name, glaccount.description, accounttype.id, accounttype.description order by accounttype.id, glaccount.id');
	}

	while ($recordSetacct&&!$recordSetacct->EOF)
	{
		if (!$summary)
		{
			$query = "select sum(gltransaction.amount) from gltransaction, gltransvoucher, glaccount where glaccount.id=gltransaction.glaccountid and gltransaction.glaccountid='".$recordSetacct->fields[0]."' and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($begindate.'-01')." and gltransvoucher.post2date <".sqlprep($enddate.'-01')." group by gltransaction.glaccountid order by glaccount.accounttypeid";

			$query2 = "select sum(gltransaction.amount) from gltransaction, gltransvoucher, glaccount where glaccount.id=gltransaction.glaccountid and gltransaction.glaccountid='".$recordSetacct->fields[0]."' and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($beginyear.'-'.$fiscalbeginmonth.'-01')." and gltransvoucher.post2date <=".sqlprep(($beginyear+1).'-'.$fiscalbeginmonth.'-01')." group by gltransaction.glaccountid order by glaccount.accounttypeid";

			$query3 = "select sum(gltransaction.amount) from gltransaction, gltransvoucher, glaccount where glaccount.id=gltransaction.glaccountid and gltransaction.glaccountid='".$recordSetacct->fields[0]."' and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($begindate2.'-01')." and gltransvoucher.post2date <".sqlprep($enddate2.'-01')." group by gltransaction.glaccountid order by glaccount.accounttypeid";

			$query4 = "select sum(gltransaction.amount) from gltransaction, gltransvoucher, glaccount where glaccount.id=gltransaction.glaccountid and gltransaction.glaccountid='".$recordSetacct->fields[0]."' and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($beginyear2.'-'.$fiscalbeginmonth.'-01')." and gltransvoucher.post2date <=".sqlprep(($beginyear2+1).'-'.$fiscalbeginmonth.'-01')." group by gltransaction.glaccountid order by glaccount.accounttypeid";
		}
		else
		{
			$query = "select sum(gltransaction.amount) ".
				"from gltransaction, ".
				"gltransvoucher, ".
				"glaccount ".
				"where ".
				"glaccount.id=gltransaction.glaccountid ".
				"and (gltransaction.glaccountid='".$recordSetacct->fields[0]."' or glaccount.summaryaccountid='".$recordSetacct->fields[0]."') ".
				"and gltransaction.voucherid=gltransvoucher.id ".
				"and gltransvoucher.status=1 ".
				"and gltransvoucher.cancel=0 ".
				"and gltransvoucher.companyid=".sqlprep($active_company)." ".
				"and gltransvoucher.post2date >=".sqlprep($begindate.'-01')." ".
				"and gltransvoucher.post2date <".sqlprep($enddate.'-01')." ".
				"group by glaccount.summaryaccountid order by glaccount.accounttypeid";


				$query2 = "select sum(gltransaction.amount) from gltransaction, gltransvoucher, glaccount where glaccount.id=gltransaction.glaccountid and (gltransaction.glaccountid='".$recordSetacct->fields[0]."'  or glaccount.summaryaccountid='".$recordSetacct->fields[0]."')and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($beginyear.'-'.$fiscalbeginmonth.'-01')." and gltransvoucher.post2date <=".sqlprep(($beginyear+1).'-'.$fiscalbeginmonth.'-01')." group by glaccount.summaryaccountid order by glaccount.accounttypeid";

				$query3 = "select sum(gltransaction.amount) from gltransaction, gltransvoucher, glaccount where glaccount.id=gltransaction.glaccountid and (gltransaction.glaccountid='".$recordSetacct->fields[0]."'  or glaccount.summaryaccountid='".$recordSetacct->fields[0]."') and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($begindate2.'-01')." and gltransvoucher.post2date <".sqlprep($enddate2.'-01')." group by glaccount.summaryaccountid order by glaccount.accounttypeid";

				$query4 = "select sum(gltransaction.amount) from gltransaction, gltransvoucher, glaccount where glaccount.id=gltransaction.glaccountid and (gltransaction.glaccountid='".$recordSetacct->fields[0]."'  or glaccount.summaryaccountid='".$recordSetacct->fields[0]."') and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".sqlprep($active_company)." and gltransvoucher.post2date >=".sqlprep($beginyear2.'-'.$fiscalbeginmonth.'-01')." and gltransvoucher.post2date <=".sqlprep(($beginyear2+1).'-'.$fiscalbeginmonth.'-01')." group by glaccount.summaryaccountid order by glaccount.accounttypeid";
		}

		$recordSet = &$conn->Execute($query);
		$recordSet2 = &$conn->Execute($query2);

		if ($compareset)
		{
			$recordSet3 = &$conn->Execute($query3);
			$recordSet4 = &$conn->Execute($query4);
		}

		if ($oldaccounttypeid!=$recordSetacct->fields[3])
		{
			   switch ($oldaccounttypeid)
			   {
				  case 50:
						$salespertotal=$subsetpertotal;
						$salesytdtotal=$subsetytdtotal;
						if ($compareset)
						{
							  $salespertotal2=$subsetpertotal2;
							  $salesytdtotal2=$subsetytdtotal2;
							  $comparesetstr='<td width="10%"><div align="right"><nobr>'.checkdec($subsetpertotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($subsetpertotal2/$pertotal2*100),1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($subsetytdtotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($subsetytdtotal2/$ytdtotal2*100),1).'</nobr></div></td>';
						}

						echo '<tr><td colspan="6" width="69%">&nbsp;'.$acctname.' '.$lang['STR_TOTAL'].'</td><td width="10%"><div align="right"><nobr>'.checkdec($subsetpertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetpertotal/$pertotal*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($subsetytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($subsetytdtotal/$ytdtotal*100),1).'</nobr></div></td>'.$comparesetstr.'</tr>';
				  break;
				  case 60:
						$salesadjpertotal=$subsetpertotal;
						$salesadjytdtotal=$subsetytdtotal;
						if ($compareset)
						{
							  $salesadjpertotal2=$subsetpertotal2;
							  $salesadjytdtotal2=$subsetytdtotal2;
							  $comparesetstr='<td width="10%"><div align="right"><nobr>'.checkdec($subsetpertotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetpertotal2/$pertotal2*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($subsetytdtotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetytdtotal2/$ytdtotal2*100,1).'</nobr></div></td>';
							  $comparesetstr2='<td width="10%"><div align="right"><nobr>'.checkdec($salespertotal2+$salesadjpertotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salespertotal2+$salesadjpertotal2)/$pertotal2*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($salesytdtotal2+$salesadjytdtotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salesytdtotal2+$salesadjytdtotal2)/$ytdtotal2*100,1).'</nobr></div></td>';
						}

						echo '<tr><td colspan="6" width="69%">&nbsp;'.$acctname.' '.$lang['STR_TOTAL'].'</td><td width="10%"><div align="right"><nobr>'.checkdec($subsetpertotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetpertotal/$pertotal*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.num_format($subsetytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetytdtotal/$ytdtotal*100,1).'</nobr></div></td>'.$comparesetstr.'</tr>';

						echo '<tr><td colspan="6" width="69%">&nbsp;'.$lang['STR_TOTAL_SALES'].'</td><td width="10%"><div align="right"><nobr>'.checkdec($salespertotal+$salesadjpertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salespertotal+$salesadjpertotal)/$pertotal*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($salesytdtotal+$salesadjytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salesytdtotal+$salesadjytdtotal)/$ytdtotal*100,1).'</nobr></div></td>'.$comparesetstr2.'</tr>';
				  break;
				  case 70:
						$costgoodspertotal=$subsetpertotal;
						$costgoodsytdtotal=$subsetytdtotal;
						if ($compareset)
						{
							  $costgoodspertotal2=$subsetpertotal2;
							  $costgoodsytdtotal2=$subsetytdtotal2;
							  $comparesetstr='<td width="10%"><div align="right"><nobr>'.checkdec($salespertotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetpertotal2/$pertotal2*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($subsetytdtotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetytdtotal2/$ytdtotal2*100,1).'</nobr></div></td>';
							  $comparesetstr2='<td width="10%"><div align="right"><nobr>'.checkdec($salespertotal2+$salesadjpertotal2-$costgoodspertotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salespertotal2+$salesadjpertotal2-$costgoodspertotal2)/$pertotal2*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($salesytdpertotal2-$costgoodsytdtotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salesytdtotal2+$salesadjytdtotal2-$costgoodsytdtotal2)/$ytdtotal2*100,1).'</nobr></div></td>';
						}
						echo '<tr><td colspan="6" width="69%">&nbsp;'.$acctname.' Total</td><td width="10%"><div align="right"><nobr>'.checkdec($subsetpertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetpertotal/$pertotal*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($subsetytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetytdtotal/$ytdtotal*100,1).'</nobr></div></td>'.$comparesetstr.'</tr>';
						echo '<tr><td colspan="6" width="69%">&nbsp;Gross Profit</td><td width="10%"><div align="right"><nobr>'.checkdec($salespertotal+$salesadjpertotal-$costgoodspertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salespertotal+$salesadjpertotal-$costgoodspertotal)/$pertotal*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($salesytdtotal+$salesadjytdtotal-$costgoodsytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salesytdtotal+$salesadjytdtotal-$costgoodsytdtotal)/$ytdtotal*100,1).'</nobr></div></td>'.$comparesetstr2.'</tr>';
				  break;
				  case 80:
						$expensepertotal=$subsetpertotal;
						$expenseytdtotal=$subsetytdtotal;
						if ($compareset)
						{
							  $expensepertotal2=$subsetpertotal2;
							  $expenseytdtotal2=$subsetytdtotal2;
							  $comparesetstr='<td width="10%"><div align="right"><nobr>'.checkdec($subsetpertotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetpertotal2/$pertotal2*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($subsetytdtotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetytdtotal2/$ytdtotal2*100,1).'</nobr></div></td>';
							  $comapresetstr2='<td width="10%"><div align="right"><nobr>'.checkdec($salespertotal2+$salesadjpertotal2-$costgoodspertotal2-$expensepertotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salespertotal2+$salesadjpertotal2-$costgoodspertotal2-$expensepertotal2)/$pertotal2*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($salesytdtotal2+$salesadjytdtotal2-$costgoodsytdtotal2-$expenseytdtotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salesytdtotal2+$salesadjytdtotal2-$costgoodsytdtotal2-$expenseytdtotal2)/$ytdtotal2*100,1).'</nobr></div></td>';
						}
						echo '<tr><td colspan="6" width="69%">&nbsp;'.$acctname.' Total</td><td width="10%"><div align="right"><nobr>'.num_format($subsetpertotal,2).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetpertotal/$pertotal*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.num_format($subsetytdtotal,2).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetytdtotal/$ytdtotal*100,1).'</nobr></div></td>'.$comparesetstr.'</tr>';
						echo '<tr><td colspan="6" width="69%">&nbsp;Operating Profit</td><td width="10%"><div align="right"><nobr>'.checkdec($salespertotal+$salesadjpertotal-$costgoodspertotal-$expensepertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salespertotal+$salesadjpertotal-$costgoodspertotal-$expensepertotal)/$pertotal*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($salesytdtotal+$salesadjytdtotal-$costgoodsytdtotal-$expenseytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salesytdtotal+$salesadjytdtotal-$costgoodsytdtotal-$expenseytdtotal)/$ytdtotal*100,1).'</nobr></div></td>'.$comparesetstr2.'</tr>';
				  break;
				}
				$acctname=$recordSetacct->fields[4];

				if ($compareset)
				{
				   echo '<tr><td colspan="14" width="100%">&nbsp;'.$acctname.'</td></tr>';
				}
				else
				{
					   echo '<tr><td colspan="10" width="100%">&nbsp;'.$acctname.'</td></tr>';
				}
				$oldaccounttypeid=$recordSetacct->fields[3];
				$subsetpertotal=0;
				$subsetytdtotal=0;
				$subsetpertotal2=0;
				$subsetytdtotal2=0;
		  }
		  $persub=$recordSet->fields[0];
		  $ytdsub=$recordSet2->fields[0];
		  if ($compareset)
		  {
			  $persub2=$recordSet3->fields[0];
			  $ytdsub2=$recordSet4->fields[0];
		  }
		  if ($recordSetacct->fields[3]<=60||$recordSetacct->fields[3]==90)
    	  {
				$persub=0-$persub;
				$ytdsub=0-$ytdsub;
				if ($compareset)
				{
					$persub2=0-$persub2;
					$ytdsub2=0-$ytdsub2;
				}
		  }
		  $subsetpertotal+=$persub;
		  $subsetytdtotal+=$ytdsub;
		  if ($compareset)
		  {
			  $subsetpertotal2+=$persub2;
			  $subsetytdtotal2+=$ytdsub2;
		  }
		  if ($ytdsub<>0||$ytdsub2<>0)
	      {
				if ($compareset) $comparesetstr='<td width="10%"><div align="right">X<nobr>'.checkdec($persub2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($persub2*100)/$pertotal2,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($ytdsub2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($ytdsub2*100)/$ytdtotal2,1).'</nobr></div></td>';
				echo '<tr><td colspan="5" width="69%">&nbsp;&nbsp;&nbsp;'.$recordSetacct->fields[2].'</td><td width="5%">'.$recordSetacct->fields[1].'</td><td width="10%"><div align="right"><nobr>'.checkdec($persub,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($persub*100)/$pertotal,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($ytdsub,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($ytdsub*100)/$ytdtotal,1).'</nobr></div></td>'.$comparesetstr.'</tr>';
		  }
		  $recordSetacct->MoveNext();
	   }

	   //Create Other Income And Expense, and Net Income Rows
	   $otherexpensepertotal=$subsetpertotal;
	   $otherexpenseytdtotal=$subsetytdtotal;

	   if ($compareset)
 	   {
			 $otherexpensepertotal2=$subsetpertotal2;
			 $otherexpenseytdtotal2=$subsetytdtotal2;
			 $comparesetstr='<td width="10%"><div align="right"><nobr>'.checkdec($subsetpertotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetpertotal2/$pertotal2*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($subsetytdtotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetytdtotal2/$ytdtotal2*100,1).'</nobr></td>';
			 $comparesetstr2='<td width="10%"><div align="right"><nobr>'.checkdec($salespertotal2+$salesadjpertotal2-$costgoodspertotal2-$expensepertotal2-$otherexpensepertotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salespertotal2+$salesadjpertotal2-$costgoodspertotal2-$expensepertotal2-$otherexpensepertotal2)/$pertotal2*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($salesytdtotal2+$salesadjytdtotal2-$costgoodsytdtotal2-$expenseytdtotal2-$otherexpenseytdtotal2,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salesytdtotal2+$salesadjytdtotal2-$costgoodsytdtotal2-$expenseytdtotal2-$otherexpenseytdtotal2)/$ytdtotal2*100,1).'</nobr></div></td>';
	   }

	   echo '<tr><td colspan="6" width="69%">&nbsp;'.$acctname.' '.$lang['STR_TOTAL'].'</td><td width="10%"><div align="right"><nobr>'.checkdec($subsetpertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetpertotal/$pertotal*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($subsetytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format($subsetytdtotal/$ytdtotal*100,1).'</nobr></div></td>'.$comparesetstr.'</tr>';

	   echo '<tr><td colspan="6" width="69%">'.$lang['STR_NET_INCOME'].'</td><td width="10%"><div align="right"><nobr>'.checkdec($salespertotal+$salesadjpertotal-$costgoodspertotal-$expensepertotal-$otherexpensepertotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salespertotal+$salesadjpertotal-$costgoodspertotal-$expensepertotal-$otherexpensepertotal)/$pertotal*100,1).'</nobr></div></td><td width="10%"><div align="right"><nobr>'.checkdec($salesytdtotal+$salesadjytdtotal-$costgoodsytdtotal-$expenseytdtotal-$otherexpenseytdtotal,PREFERRED_DECIMAL_PLACES).'</nobr></div></td><td width="3%"><div align="right"><nobr>'.num_format(($salesytdtotal+$salesadjytdtotal-$costgoodsytdtotal-$expenseytdtotal-$otherexpenseytdtotal)/$ytdtotal*100,1).'</nobr></div></td>'.$comparesetstr2.'</tr>';

}
else if ($compare)
{
	$begindate=$beginyear.'-'.$beginmonth;
	$enddate=$endyear.'-'.$endmonth;
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
	if ($begindate!=$enddate)
	{
		echo '<center>(Compare to '.$beginyear.'/'.$beginmonth.' - '.$endyear.'/'.$endmonth.')</center><br>';
	}
	else
	{
	    echo '<center>(Compare to '.$beginyear.'/'.$beginmonth.')</center><br>';
	}
	$recordSet = &$conn->CacheExecute(10, 'select fiscalbeginmonth from glcompany where id='.sqlprep($active_company));
	if ($recordSet&&!$recordSet->EOF) $fiscalmonth=$recordSet->fields[0];
	if (!$fiscalmonth) $fiscalmonth=1;
	if ($fiscalmonth>$bgdatemonth)
	{
		$fiscalyear=$bgdateyear-1;
	}
	else
	{
		$fiscalyear=$bgdateyear;
	}
	echo '<form action="glrepincome.php" method="post"><table><input type="hidden" name="beginyear" value="'.$beginyear.'"><input type="hidden" name="beginmonth" value="'.$beginmonth.'"><input type="hidden" name="endyear" value="'.$endyear.'"><input type="hidden" name="endmonth" value="'.$endmonth.'"><input type="hidden" name="summary" value="'.$summary.'"><input type="hidden" name="compareset" value="1">';
	echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Begin Period:</td><td><input type="text" name="beginyear2" value="'.$fiscalyear.'" size="14" maxlength="4" onchange="validateint(this)"'.INC_TEXTBOX.'><input type="text" name="beginmonth2" value="'.$fiscalmonth.'" size="14" maxlength="2" onchange="validateint(this)"'.INC_TEXTBOX.'></td></tr>';
	echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">End Period:</td><td><input type="text" name="endyear2" value="'.$bgdateyear.'" size="14" maxlength="4" onchange="validateint(this)"'.INC_TEXTBOX.'><input type="text" name="endmonth2" value="'.$bgdatemonth.'" size="14" maxlength="2" onchange="validateint(this)"'.INC_TEXTBOX.'></td></tr>';
	echo '</table><input type="submit" value="Continue"></form>';
}
else
{
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

	$recordSet = &$conn->CacheExecute(10, 'select fiscalbeginmonth from glcompany where id='.sqlprep($active_company));

	if ($recordSet&&!$recordSet->EOF)
	{
		$fiscalmonth=$recordSet->fields[0];
	}

	if (!$fiscalmonth)
	{
		$fiscalmonth=1;
	}

	if ($fiscalmonth>$bgdatemonth)
	{
		$fiscalyear=$bgdateyear-1;
	}
	else
	{
		$fiscalyear=$bgdateyear;
	}
	echo '<form action="glrepincome.php" method="post"><table>';
	echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_BEGIN_PERIOD'].':</td><td><input type="text" name="beginyear" value="'.$fiscalyear.'" size="14" maxlength="4" onchange="validateint(this)"'.INC_TEXTBOX.'><input type="text" name="beginmonth" value="'.$fiscalmonth.'" size="14" maxlength="2" onchange="validateint(this)"'.INC_TEXTBOX.'></td></tr>';
	echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_END_PERIOD'].':</td><td><input type="text" name="endyear" value="'.$bgdateyear.'" size="14" maxlength="4" onchange="validateint(this)"'.INC_TEXTBOX.'><input type="text" name="endmonth" value="'.$bgdatemonth.'" size="14" maxlength="2" onchange="validateint(this)"'.INC_TEXTBOX.'></td></tr>';
	echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_SUMMARIZED'].':</td><td><input type="checkbox" name="summary" value="1"'.INC_TEXTBOX.'></td></tr>';
	echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">'.$lang['STR_COMPARE_TO_OTHER_PERIOD'].':</td><td><input type="checkbox" name="compare" value="1"'.INC_TEXTBOX.'></td></tr>';
	echo '</table><br><input type="submit" value="'.$lang['STR_CONTINUE'].'"></form>';
	
	echo '</center>';
}
?>

<?php include('includes/footer.php'); ?>
