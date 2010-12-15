<?php include('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
  //GLPIE.PHP - copyright 2001, Noguska, Fostoria, OH 44830
  // calculate totals from pie definition and create a pie chart
  $recordSet=&$conn->Execute('select name,description,begindate,findate from glpie where id='.$id);
  if (!$recordSet->EOF) { //read in main pie info
     $piename=$recordSet->fields[0];
     $piedescription=$recordSet->fields[1];
     $piebegin=$recordSet->fields[2];
     $piefin=$recordSet->fields[3];
  } else {
     die(TextError("Invalid Pie ID"));
  };

  $recordSet=&$conn->Execute('select name,begindate,findate,id from glpieslice where glpieid='.sqlprep($id));
  while (!$recordSet->EOF) {
      //read slice info for pie
      $dc+=1;
      ${"slicename".$dc}=$recordSet->fields[0];
      ${"slicebegin".$dc}=$recordSet->fields[1];
      ${"slicefin".$dc} = $recordSet->fields[2];
      if (${"slicebegin".$dc}<'1900-01-01') {
             ${"slicebegin".$dc}=$piebegin;
             ${"slicefin".$dc}=$piefin;
      };
      $sliceid=$recordSet->fields[3];
      $recordSet1=&$conn->Execute('select glpieslicedetail.glaccountid, glpieslicedetail.companyid,glaccount.accounttypeid from glpieslicedetail,glaccount where glpieslicedetail.glaccountid=glaccount.id and glpieslicedetail.glpiesliceid='.sqlprep($sliceid));
      ${"slicetotal".$dc}=0;
      while (!$recordSet1->EOF) {
             $glaccountid=$recordSet1->fields[0];
             $companyid=$recordSet1->fields[1];
             $accounttype=$recordSet1->fields[2];
             $mult=1;
             if (($accounttype>20&&$accounttype<70)||$accounttype>80) $mult=-1;
             unset($comp);
             if ($companyid>0) $comp=" and gltransvoucher.companyid=".sqlprep($companyid)." ";
             $recordSet2=&$conn->Execute('select sum(gltransaction.amount) from gltransaction,gltransvoucher where gltransvoucher.id=gltransaction.voucherid '.$comp.' and gltransaction.glaccountid='.sqlprep($glaccountid).' and gltransvoucher.cancel=0 and gltransvoucher.status=1 and gltransvoucher.post2date>='.sqlprep(${"slicebegin".$dc}).' and gltransvoucher.post2date<='.sqlprep(${"slicefin".$dc}));
             if (!$recordSet2->EOF) ${"slicetotal".$dc}=${"slicetotal".$dc}+($mult * $recordSet2->fields[0]);
             $recordSet1->MoveNext();
      };
      if (${"slicetotal".$dc}>0) $valid=1; //check that at least one of the slices has a value, if not, we'll die before making graph
      $urlstr.='data[]='.urlencode(${"slicetotal".$dc}).'&dataname[]='.urlencode(${"slicename".$dc}.' - $'.num_format(${"slicetotal".$dc},2)).'&';
      $recordSet->MoveNext();
  };
  if (!$valid) die(texterror('All members of pie chart = 0, cannot create graph.'));
  if (strlen($urlstr)>0) $urlstr=substr($urlstr,0,strlen($urlstr)-1);

  echo '<table><tr>';
  echo '<td>'.texttitle($piename).'<img src="images/graphpiebig.php?name='.htmlentities(urlencode($piename)).'&'.$urlstr.'"></td>';
  echo '</tr></table>';
?>
<?php include('includes/footer.php'); ?>
