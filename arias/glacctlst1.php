<?php include('includes/main.php'); ?>
<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
 echo texttitle("General Ledger Account by Month for ".$companyname);
if ($glaccountid) {
   $recordSet=&$conn->Execute('select id,name,description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') and id='.$glaccountid);
   if (!$recordSet->EOF) {
         $glaccountname=$recordSet->fields[1]." ".$recordSet->fields[2];
   } else {
        die(texterror('Gl Account Not Found'));
   };

   echo '<table align="center" border="1"><tr><th colspan="2">ACCOUNT = '.$glaccountname.'</th></tr><tr><th>YEAR - MONTH</font></th><th>AMOUNT</font></th></tr>';
   $timestamp =  time();
   $date_time_array = getdate($timestamp);
   $year = $date_time_array["year"];
   $hours = $date_time_array["hours"];
   $minutes = $date_time_array["minutes"];
   $seconds = $date_time_array["seconds"];
   $month = $date_time_array["mon"];
   $bgmonth = date("m",$timestamp);
   $day = $date_time_array["mday"];
   $amount=0;
   for ($curmonth=1;$curmonth<=24;$curmonth++) {
         $timestamp=time();
         $timestamp = mktime($hour, $minute, $second, $bgmonth-$curmonth+1, $day, $year);
         $date_time_array=getdate($timestamp);
         $curyear=$date_time_array["year"];
         $displaymonth=$date_time_array["mon"];
         $monthnow=date("m",$timestamp);
         echo '<tr><td>'.$curyear." - ".$monthnow.'</td>';
         $recordSet = &$conn->Execute("select sum(gltransaction.amount) from gltransaction, gltransvoucher where gltransaction.glaccountid=".sqlprep($glaccountid)." and gltransaction.voucherid=gltransvoucher.id and gltransvoucher.status=1 and gltransvoucher.cancel=0 and gltransvoucher.companyid=".$active_company." and gltransvoucher.post2date =".sqlprep($curyear.'-'.$monthnow.'-01'));
         if (!$recordSet->EOF) {
               echo '<td>'.num_format($recordSet->fields[0],2).'</td></tr>';
		if ($recordSet->fields[0]>0) $valid=1;
		$data[]=$recordSet->fields[0];
         } else {
               echo '<td>'.num_format($amount,2).'</td></tr>';
		$data[]=0;
         };
   };
   echo '</table>';
   if ($valid) {
      echo '<center><img src="images/graphbar.php?';
      while ( list($key, $val) = each($data) ) { 
	   echo 'data[]='.$val.'&';
      };
      echo '"></center></table>';
   };
};
include('includes/footer.php'); 
php?>
