<?

// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)

$cfgIndexpage = '/index.php';
$bgImage = 'login.png';                 // Choose the background image
$bgRotate = true;                         // Rotate the background image from list
                                          // (This overrides the $bgImage setting)

/****** Lists ******/
// List of backgrounds to rotate through
$backgrounds[] = 'login.png';
$backgrounds[] = 'login.png';
$backgrounds[] = 'login.png';


/****** Database ******/
$useDatabase = true;                     // choose between using a database or data as input
require_once('includes/defines.php');


  require_once('includes/adodb/adodb.inc.php');
  require_once('includes/functions.php');
  ADOLoadCode(DB_TYPE);
  $conn = &ADONewConnection();
  $conn->PConnect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
  
if (defined("SYS_DEFAULT_LANG")) $deflanguage = SYS_DEFAULT_LANG; //Overwrite Default_lang
if (!$deflanguage) $deflanguage=DEFAULT_LANG; //if default language isn't set in genuser, set it from defines.php
if (!$deflanguage) $deflanguage=SD_ENGLISH; //if default language still isn't set, make it english
if (!$deflanguage) $deflanguage=1; //if default language still isn't set, make it english
include_once('includes/lang/1.php');
if (is_numeric($deflanguage)) include('includes/lang/'.$deflanguage.'.php');
if (defined(SYS_DEFAULT_STR_CHAR_SET)) $lang['STR_CHAR_SET'] = SYS_DEFAULT_STR_CHAR_SET; //Overwrite Char_set

// https support
if (getenv("HTTPS") == 'on') {
	$cfgUrl = 'https://';
} else {
	$cfgUrl = 'http://';
}

// getting other login variables
if ($message) $messageOld = $message;
$message = false;


// include functions and variables
function admEmail() {
	// create administrators email link
	global $admEmail;
	return("<A HREF='mailto:$admEmail'>$admEmail</A>");
}

// logout first if requested
if ($logout || $HTTP_GET_VARS["logout"] || $HTTP_POST_VARS["logout"]) { // logout
	include('authentication/logout.php');
}
// loading login check
include('authentication/checklogin.php');
?>
