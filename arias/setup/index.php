<HTML>
<HEAD>
<TITLE>ARIA Install</TITLE>
<style>
td {font-size:small; font-family:Verdana; line-height:1.3em;}
body {background-color:#ddddda; margin:10; color:#2E221A;}
table {border:solid 1px #778899; background-color:#ddddda;}
</style>
<script language="Javascript">

function SetAction(action)
{
  eval('document.mainform.action.value='+action);
  document.mainform.submit();
}

</script>
</HEAD>
<BODY>

<?
require_once('../includes/adodb/adodb.inc.php');

ini_set("max_execution_time", 0);

$errostring = "";

// Lets initialize!

if (isset($_POST['action'])) { $action = $_POST['action']; }
if (isset($_POST['install_info'])) { $install_info = $_POST['install_info']; }

if (!isset($action))
{
  $action = "start";
}

if (!isset($install_info))
{
  // Default
  $install_info = array();
  $install_info["db_type"] = "MySQL";
  $install_info["db_server"] = "localhost";
  $install_info["db_name"] = "aria";
  $install_info["db_user"] = "root";
  $install_info["db_password"] = "";
}

switch($action)
{
 case "start":
   welcome_screen($install_info);
   break;
 case "check_writable":
   check_writable($install_info);
   break;
 case "encryptionkey":
   encryptionkey($install_info);
   break;case "select_db":
	   select_db($install_info);
	   break;
 case "db_access":
   db_access($install_info);
   break;
 case "create_db":
 case "db_import":
   create_db($_POST['install_info']);
   break;
}
?>

</BODY>
</HTML>

<?
function welcome_screen($install_info)
{
  global $PHP_SELF;
  
    ?>
    <table width="100%" height="100%" cellpadding="30">
       <tr>
       <td>
       <center><B><h2>Welcome to <b>ARIA</b></h2></B></center>
       <center><b>ARIA</b> is a web based accounting, receiving and inventory (ERP) solution for</center><br>
													  <center>small to medium business. With <b>ARIA</b> you can manage inventory levels, keep accounting ledgers,</center><br>
       <center>write payroll and much more. Following you will be asked several questions that will help install</center><br>
       <center><b>ARIA</b> on your system. If you are the administrator of the system that <b>ARIA</b> will be installed on</center><br>
       <center>you may want to make sure that the <b>ARIA</b> files are readable and writable before continuing.</center><br>
       <center>You can do this by performing a <i>chmod -R 666</i> on the <i>/arias</i> directory.</center><br>
			        
       <center>
			    <form name="mainform" method="POST" action="<?= $PHP_SELF ?>">
			    
			    <?
			    foreach($install_info as $key => $value)
	 {
	   ?>
	   <input type="hidden" name="install_info[<?= $key ?>]" value="<?= $value ?>">
	   <?
	 }
  ?>
       <input type="hidden" name="action" value="check_writable">
	  <input type="hidden" name="continue" value="1">
	  <input type="submit" name="continue" value="Continue">
	  </form>
	  </center>
	  </td></tr></table>
	  <?
	  }



function check_writable($install_info)
{
  global $PHP_SELF;
  
    ?>
    
    <form name="mainform" method="POST" action="<?= $PHP_SELF ?>">
       <table width="100%" height="100%" cellpadding="30">
       <tr>
       <td>
       <P>
       Checking write access to the file includes/my_defines.php. Database access information will be stored in that file.
       <P>
       Checking access..

       <?
       if (is_writable("../includes/my_defines.php"))
	 {
	      ?>
	      Ok, it is writable.<BR><BR><BR>
	      <input type="hidden" name="action" value="encryptionkey">
	      <input type="submit" name="continue" value="Next">
	      <?
	 }
  else
    {

      if (!chmod('../includes/my_defines.php', 666)) // I thought 'Why the hell not?'
	{       
	         print 'Not writable. You should do chmod 666 to that file.<BR><BR><BR>
   <input type="hidden" name="action" value="check_writable">
   <input type="submit" name="continue" value="Check Again">';
	}       
         
    }


  foreach($install_info as $key => $value)
    {
  ?>
<input type="hidden" name="install_info[<?= $key ?>]" value="<?= $value ?>">
   <?
   }
  ?>
    </td></tr></table>
	</form>
	<?
	}

function encryptionkey($install_info)
{
  global $PHP_SELF;

  ?>
    <form name="mainform" method="POST" action="<?= $PHP_SELF ?>">
       <table width="100%" height="100%" cellpadding="30">
       <tr>
       <td>
       <P>
       Change the encryption key. This is used in SSL connections.
       <P>

       <input type="text" size="50" maxlength="50" name="install_info[encryptionkey]" value="

<?php 
// Every distro was using a static crypto key!? This will at least generate a reasonably unique key.
print md5(time());
?>

">
       <BR><BR>
       <input type="hidden" name="action" value="select_db">
       <input type="submit" name="continue" value="Next">
       <?
       foreach($install_info as $key => $value)
      {
	?>
	<input type="hidden" name="install_info[<?= $key ?>]" value="<?= $value ?>">
	<?
      }
  ?>
       </td></tr></table>
	   </form>
	   <?
	   }




function select_db($install_info)
{
  global $PHP_SELF;

  // Rather than make a wild-ass guess, this scans the SQL ports and selects a server based on the result.
  // The user can, of course, select differentl.

  $sql_servers = array (
			"MySQL" => 3306,
			"MSSQL" => 1433,
			"Oracle" => 1525
			);

  foreach ($sql_servers as $name => $port)
    {
      $fp = @fsockopen ('127.0.0.1', $port, $errno, $errstr, 4); 
      if ($fp) { $server_type = $name; fclose($fp); } 
    }


  ?>
    <table width="100%" height="100%" cellpadding="30">
       <tr>
       <td>
       <P>
       Which database are you using? Select one.
       <P>
       <form name="mainform" method="POST" action="<?= $PHP_SELF ?>">
       <?
       foreach($install_info as $key => $value)
      {
	if ($key == "db_type") 
	{
	  continue;
	}
	?>
	<input type="hidden" name="install_info[<?= $key ?>]" value="<?= $value ?>">
	<?
      }
  ?>

       <input type="hidden" name="action" value="db_access">
	  <input type="radio" name="install_info[db_type]" value="MSSQL" <?php if ($server_type === "MSSQL") { print "checked"; } ?>> MS SQL<BR>
																	<input type="radio" name="install_info[db_type]" value="MySQL" <?php if ($server_type === "MySQL") { print "checked"; } ?>> MySQL<BR>
																																      <input type="radio" name="install_info[db_type]" value="Oracle" <?php if ($server_type === "Oracle") { print "checked"; } ?>> Oracle<BR><BR>
																																																      <input type="submit" name="continue" value="Next">
																																																      </form>
																																																      </td></tr></table>
																																																      <?
																																																      }

function db_access($install_info)
{
  global $PHP_SELF;

  ?>
    <form name="mainform" method="POST" action="<?= $PHP_SELF ?>">
       <table width="100%" height="100%" cellpadding="10">
       <tr>
       <td height="40%">&nbsp;</td>
				  </tr>
				  <tr>
				  <td colspan=3 align=left>
				  <B>Database access information.</B>
				  </td>
				  </tr>
				  <tr>
				  <td width="20%" align=right>Database Server (127.0.0.1 is local machine)</td>
				  <td width="20%"><input type="text" name="install_info[db_server]" value="<?= $install_info["db_server"] ?>"></td>
				  <td width="60%"></td>
				  </tr>
				  <tr>
				  <td width="20%" align=right>Database Name</td>
				  <td width="20%"><input type="text" name="install_info[db_name]" value="<?= $install_info["db_name"] ?>"></td>
				  <td width="60%"></td>
				  </tr>
				  <tr>
				  <td align=right>Database User</td>
				  <td><input type="text" name="install_info[db_user]" value="<?= $install_info["db_user"] ?>"></td>
				  </tr>
				  <tr>
				  <td align=right>Database Password</td>
				  <td><input type="text" name="install_info[db_password]" value="<?= $install_info["db_password"] ?>"></td>
				  </tr>
				  <tr>
				  <td align=right>Don't create database (I have created it already)</td>
<td><input type="checkbox" name="install_info[db_dontcreate]" value="root"></td>
</tr>
<tr>
<td align=right>Import database</td>
<td><input type="checkbox" name="install_info[db_import]" value="root1" checked></td>
</tr>

<!--<tr>
<td align=right>Fill the database with information in</td>
<td><select name="install_info[db_filldata]"><option value="us">US English</option></select></td>
</tr>-->
<tr>
<td colspan=2 align=left>
<input type="hidden" name="install_info[encryptionkey]" value="<?= $install_info["encryptionkey"] ?>">
<input type="hidden" name="install_info[db_type]" value="<?= $install_info["db_type"] ?>">
<input type="hidden" name="action" value="create_db">
<input type="hidden" name="action" value="db_import">
<input type="submit" name="action1" value="Previous" onclick="document.mainform.action.value='select_db'">
<input type="submit" name="action2" value="Next">
</td>
</tr>
<tr>
<td height="40%">&nbsp;</td>
</tr>
</table>
</form>
<?
}

function create_db($install_info)
{
global $PHP_SELF, $errorstring, $new_database, $rows;

$install_info = $_POST['install_info'];

//      What was this supposed to do?
//echo $install_info["db_dontcreate"]."--".$install_info["db_import"];

$existing_databse = database_exists(
                     $install_info["db_type"],
                     $install_info["db_server"],
                     $install_info["db_user"],
                     $install_info["db_password"],
                     $install_info["db_name"]
);


if (!database_exists(
                     $install_info["db_type"], 
                     $install_info["db_server"], 
                     $install_info["db_user"], 
                     $install_info["db_password"], 
                     $install_info["db_name"]))

                     {

                     if (isset($install_info["db_dontcreate"]))
                     { 
                           die("No ARIAS database exists. You selected 'do not create database' option."); 
                     }
  
                     else
                     {
                           @create_database(
           $install_info['db_type'], 
           $install_info['db_server'], 
           $install_info['db_user'], 
           $install_info['db_password'],
           $install_info['db_name']
      );

                     $new_database = 'true';
    }
}

print "<BR><BR>\n\nWriting database access information to the file includes/my_defines.php ...\n"; 

$success = write_dbinfo($install_info["encryptionkey"], $install_info["db_server"], $install_info["db_user"], $install_info["db_password"], $install_info["db_name"]);


echo $success? "Ok": "<div style=\"color:red\">Error: $errorstring</div> <br>";

if (!$success)
{
  echo '<div style=\"color:red\">Error on writting includes/my_defines.php, Please check your Files Permission</div>';
  die();
}



// Only create tables if a new database is also being created.

 if ($new_database === "true") {

   print '<BR><BR>Creating tables ...'; 

   $tables_created = @create_tables($install_info["db_type"], 
				    $install_info["db_server"], 
				    $install_info["db_user"], 
				    $install_info["db_password"], 
				    $install_info["db_name"]);
  
   echo $tables_created? "Ok, $tables_created tables created.": "<div style=\"color:red\">Error: $errorstring</div> <br>";

   if (!$tables_created)
     {
       echo '<div style=\"color:red\">Error on creatable inside Database, Please check your tables Files and Database Permission</div>';
       die();
     }

   print "<BR><BR>\nFilling tables with initial data ...\n";

   if ($install_info["db_type"] == "MySQL")
     {
       if (fill_tables_MySQL($install_info["db_type"], 
			     $install_info["db_server"], 
			     $install_info["db_user"], 
			     $install_info["db_password"], 
			     $install_info["db_name"], 
			     $install_info["db_filldata"])) 
	 { global $rows; $rows = "done"; }
     }

   else
     {
       $rows = fill_tables($install_info["db_type"], 
			   $install_info["db_server"], 
			   $install_info["db_user"], 
			   $install_info["db_password"], 
			   $install_info["db_name"], 
			   $install_info["db_filldata"]);
     }
 
   echo $rows? "Ok.": "<div style=\"color:red\">Error: $errorstring</div> <br>";
 } 

 else
   {
     print '<br>'.$install_info['db_type']." is already setup for ARIAS. No tables written.";
   }


 if (!$rows && $new_database)
   {
     echo '<div style=\"color:red\">Error on import Data into tables, filldata Files and Database Permission</div>';
     die();
   }
 //echo '<center><h4><br><br>Install complete. Please close your browser and log back in.</h4></center><br>';


 // Should I unset all the variables, first?
 print '<center><h4>Install Complete</h4> <a href="../">Click Here</a> to login</center>';

 echo '<center><h3>Thank you for using ARIA.</h3></center>';
}


function create_database($db_type, $server, $username, $password, $database_name)
{
  global $errorstring;
  ADOLoadCode($db_type);
  $test_conn = &ADONewConnection();
  $test_conn->debug=0;

  $success = $test_conn->PConnect($server, $username, $password, "");

  if ($success)
    {
      $success = $test_conn->Execute("CREATE DATABASE $database_name");

      if ($success)
	{
	  $identifiedby = "";

	  if ($password)
	    {
	      $identifiedby = "IDENTIFIED BY '".$password."'";
	    }

	  $success = $test_conn->Execute("GRANT ALL ON $database_name.* TO $username $identifiedby");

	  if (!$success)
	    {
	      $errorstring = $test_conn->ErrorMsg();
	    }
	}
      else
	{
	  $errorstring = $test_conn->ErrorMsg();
	}
    }
  else
    {
      $errorstring = $test_conn->ErrorMsg();
    }

  return $success;
}

function write_dbinfo($encryptionkey, $server, $username, $password, $database_name)
{
  $file = fopen ("../includes/my_defines.php", "w");

  if (!$file) 
    {
      $errorstring =  "Unable to open file includes/my_defines.php for writing.";
      return false;
    }

  $endtag = "?>";
  $starttag = "<?";

  $nbytes = fwrite($file, "$starttag\n");

  if (!$nbytes)
    {
      $errorstring =  "Unable to write to includes/my_defines.php.";
      return false;
    }

  $nbytes = fwrite($file, "define('DB_SERVER', '$server');\n");

  if (!$nbytes)
    {
      $errorstring =  "Unable to write to includes/my_defines.php.";
      return false;
    }

  $nbytes = fwrite($file, "define('DB_SERVER_USERNAME', '$username');\n");
  if (!$nbytes)
    {
      $errorstring =  "Unable to write to includes/my_defines.php.";
      return false;
    }

  $nbytes = fwrite($file, "define('DB_SERVER_PASSWORD', '$password');\n");
  if (!$nbytes)
    {
      $errorstring =  "Unable to write to includes/my_defines.php.";
      return false;
    }

  $nbytes = fwrite($file, "define('DB_DATABASE', '$database_name');\n");
  if (!$nbytes)
    {
      $errorstring =  "Unable to write to includes/my_defines.php.";
      return false;
    }

  $nbytes = fwrite($file, "define('ENCRYPTION_KEY', '$encryptionkey');\n");
  if (!$nbytes)
    {
      $errorstring =  "Unable to write to includes/my_defines.php.";
      return false;
    }

  $nbytes = fwrite($file, "$endtag\n");

  if (!$nbytes)
    {
      $errorstring =  "Unable to write to includes/my_defines.php.";
      return false;
    }

  fclose($file);

  return true;
}


function create_tables($db_type, $server, $username, $password, $database_name)
{
  global $errorstring;
  //  ADOLoadCode($db_type);

  $test_conn = &ADONewConnection($db_type);
  //  $test_conn->debug=0;
  $success = $test_conn->PConnect($server, $username, $password, $database_name);

  if (!$success)
    {
      $errorstring = $test_conn->ErrorMsg();
      return 0;
    }

  $file = fopen ("sqlscripts/$db_type/database.sql", "r");

  //  echo $file;
  if (!$file) 
    {
      $errorstring =  "Unable to open file sqlscripts/$db_type/database.sql.";
      return false;
    }
    
  $state = 0;
  $query = "";
  $tables_shown = 0;
  $tables_created = 0;
  while (!feof ($file)) {
    $line = fgets ($file);
        
    if ($state == 0 && stristr($line, "create "))
      {
	$state = 1;
      }
    if ($state == 1 && stristr($line, ");"))
            
      if (($state == 1 && preg_match("/\) *(type){0,1} *= *(myisam|innodb){0,1};/i", $line)) || ($state == 1 && preg_match("/;$/m", $line)))
	{
	  $line = substr($line, 0, strrpos($line, ";"));
	  $state = 2;
	}
        
    if ($state == 1 || $state == 2)
      {
	$query .= $line;
      }
        
    if ($state == 2)
      {
	$state = 0;
	$success = $test_conn->Execute($query);
	
	if (!$success)
	  {
	    echo "<b>".$test_conn->ErrorMsg()."</b></td>";
	    $errorstring = $test_conn->ErrorMsg();
	    return false;
	  }
	
	$query = "";
	$tables_created++;
      }
  }
  fclose($file);
  return $tables_created;
}

function fill_tables($db_type, $server, $username, $password, $database_name, $language)
{
  global $errorstring;

  ADOLoadCode($db_type);
  $test_conn = &ADONewConnection();
  $test_conn->debug=0;
  $success = $test_conn->PConnect($server, $username, $password, $database_name);

  if (!$success)
    {
      $errorstring = $test_conn->ErrorMsg();
      return 0;
    }

  $file = fopen ("sqlscripts/$db_type/filldata.sql", "r");

  if (!$file) {
    $errorstring =  "Unable to open file sqlscripts/$db_type/filldata.sql.";
    return false;
  }

  $fstats = fstat($file);
  $filesize = $fstats[7];
  ?>
    <form name="mainform">
       <input style="text-align:right; border-width: 0; border: 0; background-color:#ddddda;" type="text" name="filesize" size="10" value="0"> / <?= (int)($filesize/1024) ?> KB
       </form>
       <?

       $state = 0;
  $query = "";
  $tables_shown = 0;
  $rows_inserted = 0;

  while (!feof ($file)) {
    $line = fgets ($file);

    if ($state == 0 && stristr($line, "insert into"))
      {
	$state = 1;
      }

    if ($state == 1 && stristr($line, ");"))
      {
	$state = 2;
      }

    if ($state == 1 || $state == 2)
      {
	$query .= $line;
      }

    if ($state == 2)
      {
	$state = 0;

	$success = $test_conn->Execute($query);

	if (!$success)
	  {
	    echo "<b>".$test_conn->ErrorMsg()."</b></td>";
	    $errorstring = $test_conn->ErrorMsg();
	    return false;
	  }

	$query = "";
	$rows_inserted++;
	if ($rows_inserted % 100 == 0)
	  {
	    $position = ftell($file);
	    ?>
	      <script type="text/javascript" language="Javascript">
		 document.mainform.filesize.value = <?= (int)($position/1024) ?>
		 </script>
		 <?
		 }
      }
  }


  $position = ftell($file);
  ?>
    <script type="text/javascript" language="Javascript">
       document.mainform.filesize.value = <?= (int)($position/1024) ?>
       </script>
       <?

       fclose($file);

  return $rows_inserted;
}

function fill_tables_MySQL($db_type, $server, $username, $password, $database_name, $language)
{
  global $errorstring;
  ADOLoadCode($db_type);
  $test_conn = &ADONewConnection();
  $test_conn->debug=0;
  $success = $test_conn->PConnect($server, $username, $password, $database_name);

  if (!$success)
    {
      $errorstring = $test_conn->ErrorMsg();
      return 0;
    }

  $file = fopen ("sqlscripts/$db_type/filldata.sql", "r");

  if (!$file) {
    $errorstring =  "Unable to open file sqlscripts/$db_type/filldata.sql.";
    return false;
  }

  $fstats = fstat($file);
  $filesize = $fstats[7];
  ?>

    <form name="mainform">
       <input style="text-align:right; border-width: 0; border: 0; background-color:#ddddda;" type="text" name="filesize" size="10" value="0"> / <?= (int)($filesize/1024) ?> KB
       </form>
       <?

       $state = 0;
  $query = "";
  $tables_shown = 0;
  $rows_inserted = 0;
  $table = "";
  $linesread = 0;

  while (!feof ($file)) {
    $line = fgets ($file);
    $linesread++;

    if (stristr($line, "insert into"))
      {
	$matches = array();
	if (preg_match("/insert +into +([^ ]+) +(\(.+\) +)?values +(.+);/i", $line, $matches))
	  {
	    // same table, add to multiple values
	    if ($table == $matches[1] && $columns == $matches[2] && strlen($query) < 10000)
	      {
		$query .= ", ".$matches[3];
	      }
	    else // do the insert
	      {
		if (!empty($query))
		  {
		    $success = $test_conn->Execute($query);

		    if (!$success)
		      {
			echo "<b>".$test_conn->ErrorMsg()."</b></td>";
			$errorstring = $test_conn->ErrorMsg();
			return false;
		      }
		  }

		$table = $matches[1];
		$columns = $matches[2];
		$values = $matches[3];
		$query = "insert into $table $columns values $values";
	      }
	  }

	if ($linesread % 100 == 0)
	  {
	    $position = ftell($file);
	    ?>
	      <script type="text/javascript" language="Javascript">
		 document.mainform.filesize.value = <?= (int)($position/1024) ?>
		 </script>
		 <?
		 }
      }
  }

  $position = ftell($file);
  ?>
    <script type="text/javascript" language="Javascript">
       document.mainform.filesize.value = <?= (int)($position/1024) ?>
       </script>

       <?

       fclose($file);
  return true;
}

// return value here.

function database_exists($db_type, $server, $username, $password, $database_name)
{
  ADOLoadCode($db_type);
  $test_conn = &ADONewConnection();
  $test_conn->debug=0;
  $success = $test_conn->PConnect($server, $username, $password, $database_name);

  return $success;
}
?>
