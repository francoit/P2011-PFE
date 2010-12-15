<?PHP

// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)

//shouldn't need to edit anything after this, only edit includes/defines.php
  require_once('includes/defines.php');
  require_once('includes/adodb/adodb.inc.php');
  require_once('includes/functions.php');


  ADOLoadCode(DB_TYPE);
  $conn = &ADONewConnection();
  $conn->PConnect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
  global $userid;
  require_once('authentication/secure.php');
  require_once('includes/header.php');
  require_once('includes/ups/upsrate.php');
  //handle enter key, highlighting on forms.
  if (FIELD_TAB) $inctextbox.=' onKeyPress="return handleEnter(this, event)"';
  if (FIELD_HIGHLIGHT) $inctextbox.=' onFocus="highlightField(this,'.FIELD_AUTO_SELECT.')" onBlur="normalField(this)"';
  define('INC_TEXTBOX',$inctextbox);
  
 PHP ?>
