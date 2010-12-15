<?PHP

// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)

include_once('includes/defines.php');
if (is_numeric($deflanguage)) include('includes/lang/'.$deflanguage.'.php');
$Browser_Type  =  strtok($HTTP_USER_AGENT,  "/");
if ( ereg( "MSIE", $HTTP_USER_AGENT) || ereg( "Mozilla/5.0", $HTTP_USER_AGENT) ) {
        $theTable = 'WIDTH="375" HEIGHT="245"';
} else {
        $theTable = 'WIDTH="404" HEIGHT="249"';
}

if ( ereg("php\.exe", $PHP_SELF) || ereg("php3\.cgi", $PHP_SELF) || ereg("phpts\.exe", $PHP_SELF) ) {
        // $documentLocation = $HTTP_ENV_VARS["PATH_INFO"];
        $documentLocation = getenv("PATH_INFO");
} else {
        $documentLocation = $PHP_SELF;
}
if ( getenv("QUERY_STRING")) $documentLocation .= "?" . getenv("QUERY_STRING");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML><HEAD><TITLE>ARIA <? echo $lang['STR_LOGIN']; ?></TITLE>
<META content="text/html; charset=<? echo $lang['STR_CHAR_SET'];?>" http-equiv=Content-Type>

<SCRIPT LANGUAGE="JavaScript">
<!--
function checkData() {
        var f1 = document.forms[0];
        var wm = "The following fields are empty\n\r\n";
        var noerror = 1;

        // --- entered_login ---
        var t1 = f1.entered_login;
        if (t1.value == "" || t1.value == " ") {
                wm += "Login\r\n";
                noerror = 0;
        }

        // --- entered_password ---
        var t1 = f1.entered_password;
        if (t1.value == "" || t1.value == " ") {
                wm += "Password\r\n";
                noerror = 0;
        }

        // --- check if errors occurred ---
        if (noerror == 0) {
                alert(wm);
                return false;
        }
        else return true;
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</SCRIPT>


<script language="JavaScript">
NS4 = (document.layers) ? true : false;

function checkEnter(event)
{
        var code = 0;

        if (NS4)
                code = event.which;
        else
                code = event.keyCode;
        if (code==13)
                document.mainform.submit();
}
</script>
<? if (XBS_LOGON_SHOW): ?> <!-- show xbs custom login screen-->
</head>
<body bgColor=#ffffff leftMargin=0
onload="MM_preloadImages('authentication/images/message2.jpeg','authentication/images/help2.jpeg')" topMargin=0
marginheight="0" marginwidth="0">
<TABLE align=center border=0 cellPadding=0 cellSpacing=0 width=677>
  <TBODY>
  <TR>
    <TD colSpan=2>
      <TABLE border=0 cellPadding=0 cellSpacing=0 width="100%">
        <TBODY>
        <TR>
           <TD width="72%"><a href="http://www.xerox.com/"><IMG border="0" height=77
            src="authentication/images/NOK_001_01.jpeg" width=535></a></TD>
          <TD align=middle width="28%"><A
            href="new.html#"
            onmouseout=MM_swapImgRestore()
            onmouseover="MM_swapImage('message','','authentication/images/message2.jpeg',1)"><IMG
            border=0 height=27 name=message
            src="authentication/images/message1.jpeg"
            width=149></A><BR><A href="new.html#"
            onmouseout=MM_swapImgRestore()
            onmouseover="MM_swapImage('help','','authentication/images/help2.jpeg',1)"><IMG border=0
            height=27 name=help src="authentication/images/help1.jpeg"
            width=147></A> </TD></TR></TBODY></TABLE></TD></TR>
  <TR>
    <TD height=306 vAlign=center width=351>
      <TABLE border=0 cellPadding=10 cellSpacing=0 width="100%">
        <TBODY>
        <TR>
          <TD height=330>
            <P><FONT face="Arial, Helvetica, sans-serif" size=+1>Welcome to
            XBS/UTC Print Management System. </FONT></P>
            <P><FONT face="Arial, Helvetica, sans-serif" size=+1>This system
            utilizes the Noguska Print Management Software and is your "One
            Stop" for all print requirements. From on-line quotes, order
            tracking and inventory control to "Print on Demand". We have
            endeavored to cover every aspect of your print requirements.
            However, in the unlikely event your requirement is not covered in
            the selection offered, please feel free to contact us online via the
            "Message Center" or, if your need is urgent, dial
            1-800-NOGUSKA</FONT></P>

            <form action='<?PHP echo $documentLocation; ?>' METHOD="post" name="mainform" onSubmit="return checkData()">
                        <B><I><NOBR><?PHP if ($message) echo $message; ?>
                        </NOBR></I></B><br>
            <table><tr><td>
                        <B><FONT FACE="Arial,Helvetica,sans-serif" SIZE="-1" COLOR="#000000"><? echo $lang['STR_LOGIN']; ?>: </FONT></B></td>
                        <td><INPUT TYPE="text" NAME="entered_login" STYLE="font-size: 9pt;" TABINDEX="1"><br></td></tr>
                        <tr><td><B><FONT FACE="Arial,Helvetica,sans-serif" SIZE="-1" COLOR="#000000"><? echo $lang['STR_PASSWORD']; ?>: </FONT></B> </td>
                        <td><INPUT TYPE="password" NAME="entered_password" STYLE="font-size: 9pt;" TABINDEX="1" onKeyPress="checkEnter(event)"></td></tr></table>

                        <? if (ALLOW_LOGIN_CUSTOMER||ALLOW_LOGIN_VENDOR) {
                   if (!ALLOW_LOGIN_INTERNAL) {
                        echo '<input type="hidden" name="external_login" value="1">';
                   } else {
                        echo '<font FACE="Arial,Helvetica,sans-serif" size="-1" color="#000000">Extranet User?: </font><input type="checkbox" name="external_login" value="1"><br>';
                   };
               };
            ?>
            <? if (DEMO_MODE) echo 'Demo Mode Enabled. Login with...<br> username: demo<br>password: pass<br>'; ?><? if (SOFTWARE_SHOW_PRINT_MANAGEMENT_CUSTOM_CBL) echo CBL_LOGON_NOTES;?></font><br>
            </form>


            </TD></TR></TBODY></TABLE>
      <P><BR></P></TD>
      <TD height=306 width=350><IMG height=250
      src="authentication/images/copier.gif" width=350><a href="http://www.noguska.com/"><IMG height=246
      src="authentication/images/noguska3.jpeg" border="0" width=345></a></TD></TR></TBODY></TABLE>
<P>&nbsp;</P></BODY>

<? else: ?> <!-- show normal login screen-->
<link rel="stylesheet" type="text/css" href="includes/style/stylesheet.css">
<img src="authentication/images/side.png" width="15%" height="100%" align=left>
<img src="authentication/images/side.png" width="15%" height="100%" align=right>
</head>
<body text="Black"><center>
<form action='<?PHP echo $documentLocation; ?>' METHOD="post" name="mainform" onSubmit="return checkData()">
<TABLE WIDTH="70%" HEIGHT="100%" CELLPADDING="0" CELLSPACING="0"><TR><TD ALIGN="center" VALIGN="middle">

        <!-- Place your logo here -->

        <TABLE <?PHP echo $theTable; ?> CELLPADDING="0" CELLSPACING="0" BACKGROUND="authentication/images/<?PHP echo $bgImage; ?>"><TR><TD ALIGN="center" ALIGN="middle">
                <TABLE CELLPADDING="4" WIDTH="100%" HEIGHT="100%" BACKGROUND="" BORDER="4" BORDERCOLOR="#000000">

                <TR><TD ALIGN="center" COLSPAN="2">
                        <B><I><NOBR><?PHP if ($message) echo $message; ?>
                        </NOBR></I></B>
                </TD></TR>
                <tr><TD VALIGN="center"></TD>
                <td ALIGN="left" VALIGN="bottom">
                        <table cellpadding=4 cellspacing=1 BACKGROUND="">
                        <TR><TD ALIGN="left" COLSPAN="2"><h1><font COLOR="#000000"><? echo $lang['STR_LOGIN']; ?></font></h1></TD></TR>
                        <tr><td><B><FONT FACE="Arial,Helvetica,sans-serif" SIZE="<h2>" COLOR="#000000"><? echo $lang['STR_LOGIN']; ?>: </FONT></B></td>
                        <td> <INPUT TYPE="text" NAME="entered_login" STYLE="font-size: 12pt;" TABINDEX="1"></td></tr>
                        <tr><td><B><FONT FACE="Arial,Helvetica,sans-serif" SIZE="<h2>" COLOR="#000000"><? echo $lang['STR_PASSWORD']; ?>:</FONT></B></td>
                        <td> <INPUT TYPE="password" NAME="entered_password" STYLE="font-size: 12pt;" TABINDEX="1" onKeyPress="checkEnter(event)"></td></tr>
                        </table>
                        <? if (ALLOW_LOGIN_CUSTOMER||ALLOW_LOGIN_VENDOR) {
                   if (!ALLOW_LOGIN_INTERNAL) {
                        echo '<input type="hidden" name="external_login" value="1">';
                   } else {
                        echo '<font FACE="Arial,Helvetica,sans-serif" size="-1" color="#000000">Extranet User?: </font><input type="checkbox" name="external_login" value="1">';
                   };
               };
            ?>
            <INPUT TYPE="submit" value="<? echo $lang['STR_LOGIN']; ?>" TABINDEX="1">
                </td></tr></table>
        </TD></TR></TABLE>
</TD></TR><tr><td valign="top" align="center"><font color="#FFFFFF"><? if (DEMO_MODE) echo 'Demo Mode Enabled. Login with...<br> username: demo<br>password: pass<br>'; ?><? if (SOFTWARE_SHOW_PRINT_MANAGEMENT_CUSTOM_CBL) echo CBL_LOGON_NOTES;?></font></td></tr></TABLE>
</form>


</center>


<? endif; ?>

<SCRIPT LANGUAGE="JavaScript">
<!--
document.forms[0].entered_login.select();
document.forms[0].entered_login.focus();
//-->
</SCRIPT>
</html>
