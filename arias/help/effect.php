<!doctype html public "-//W3C//DTD HTML 4.0 //EN">
<?php
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
include("../includes/lang/1.php");

function getHelpPage() {
	global $lang;
    if (empty($_GET['page'])) {
        $page = 'help.php';
    } else {
        $page = $_GET['page'];
    }
    if (false == is_file($page)) {
        $page = 'file_not_found.php';
    }
    include($page);
}
?>

<html>
<head>
		<title>Effect</title>
		<link href="../includes/style/bluish.css">
</head>
<body>
        <body bgcolor="#B0C4DE">
        <BODY TEXT="#000000">
        <BODY VLINK="#000000">
        <BODY ALINK="#000000">
        <FONT COLOR="#000000" FACE="Verdana, Tahoma, sans-serif">

        
<?php
getHelpPage();

?>
</body>
</html>
