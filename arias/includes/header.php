<html><head>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)

        if (is_numeric($deflanguage)) require_once('includes/lang/'.$deflanguage.'.php');
        require_once('includes/lang/1.php');
        
        $recordSet = &$conn->Execute('select currencyid from gencompany where id=$companyid');
        if (is_numeric($recordSet) || !$recordSet->EOF) {
        $currencyid = intval($recordSet->fields[0]);
        } else {   
        die(texterror('New Db Schema is Require, please check.'));
        }

        //this sets color defines according to user stylesheet
        if (isset($user_stylesheet)) if (file_exists('includes/style/'.$user_stylesheet.'.php')) include_once('includes/style/'.$user_stylesheet.'.php');

        if (strrpos($PHP_SELF,"/")) { //get page name
                $docfilename=substr($PHP_SELF,strrpos($PHP_SELF,"/")+1,strlen($PHP_SELF)-strrpos($PHP_SELF,"/"));
        } else {
                $docfilename=substr($PHP_SELF,1);
        };
        if ($printable&&!$nonprintable) { //if the printable flag is set, use the print.css stylesheet, else use the normal one.  The non-printable flag provides a way to disable the print option, which would be good for pages that should not be resubmitted, which is what happens when the print link is clicked.
                echo '<link rel="stylesheet" type="text/css" href="includes/style/print.css">';
                if (PRINT_AUTO_POPUP) {
                        echo '<script language="JavaScript">'."\n";
                        echo '        function printMe() {'."\n";
                        echo '                self.print();'."\n";
                        echo '        }'."\n";
                        echo '</script>';
                        $printmestr=";printMe()";
                };
        } else {
                if (file_exists('includes/style/'.$user_stylesheet.'.css')) {
                        if (strtolower(substr($docfilename,0,4))=="help") {
                                echo '<link rel="stylesheet" type="text/css" href="includes/style/help'.$user_stylesheet.'.css">';
                        } else {
                                echo '<link rel="stylesheet" type="text/css" href="includes/style/'.$user_stylesheet.'.css">';
                        };
                } else {
                        if (strtolower(substr($docfilename,0,4))=="help") {
                                echo '<link rel="stylesheet" type="text/css" href="includes/style/help.css">';
                        } else {
                                echo '<link rel="stylesheet" type="text/css" href="includes/style/bluish.css">';
                        };
                };
        };
?>

<? if (SHOW_TOOLTIPS): ?>
<?
include_once("includes/horimenu.css");
include_once("js/horimenu2.js");
?>
<script type="text/javascript" language="JavaScript"><!--
function toggle(object) {
  var Event = window.event || arguments.callee.caller.arguments[0];

  if (document.getElementById) {
    if (document.getElementById(object).style.visibility == 'visible')
      document.getElementById(object).style.visibility = 'hidden';
    else {
      document.getElementById(object).style.left = Event.x+15;
      document.getElementById(object).style.top  = Event.y-20;
      document.getElementById(object).style.visibility = 'visible';
      }
  }

  else if (document.layers && document.layers[object] != null) {
    if (document.layers[object].visibility == 'visible' ||
     document.layers[object].visibility == 'show' )
      document.layers[object].visibility = 'hidden';
    else {
      document.layers[object].left = Event.x+15;
      document.layers[object].top  = Event.y-20;
      document.layers[object].visibility = 'visible';
      }
  }

  else if (document.all) {
    if (document.all[object].style.visibility == 'visible')
      document.all[object].style.visibility = 'hidden';
    else {
      document.all[object].style.pixelLeft = document.body.scrollLeft + Event.x + 1;
      document.all[object].style.pixelTop = document.body.scrollTop + Event.y + 1;
      document.all[object].style.visibility = 'visible';
      }
  }

  return false;
}
//--></script>
<? else: ?>
        <script language="JavaScript">
        function toggle(object) {
        }
        </script>
<? endif; ?>
        <script language="JavaScript" src="js/overlib.js"></script>
        <script language="JavaScript" src="js/donothing.js"></script>
        <script language="JavaScript" src="js/confirm.js"></script>
        <script language="JavaScript" src="js/validatedate.js"></script>
        <script language="JavaScript">
        <!--
<?         if (FIELD_TAB): ?>
                <? require_once('js/handleenter.js'); ?>
        <? else: ?>
                function handleEnter (field, event) {
                }
        <? endif; ?>
        //-->
        </script>

        <script language="JavaScript">
        <!--
        <? if (FIELD_HIGHLIGHT): ?>
                <? require_once('js/highlightfield.js'); ?>
        <? else: ?>
                function highlightField (field,select) {
                }

                function normalField (field) {
                }

                function highlightFieldFirst () {
                }
        <? endif; ?>
        //-->
        </script>

        <script language="JavaScript">
        <!--
	function validatenum(field) {
		var valid = "0123456789.-"
		var temp;
		for (var i=0; i<field.value.length; i++) {
			temp = "" + field.value.substring(i, i+1);
			if (valid.indexOf(temp) == "-1") {
				field.value=(field.value.substring(0,i)+(field.value.substring(i+1,field.value.length)));
				i--
			}
		}
	}
        //-->
        </script>

        <script language="JavaScript">
        <!--
	function validateint(field) {
		var valid = "0123456789"
		var temp;
		for (var i=0; i<field.value.length; i++) {
			temp = "" + field.value.substring(i, i+1);
			if (valid.indexOf(temp) == "-1") {
				field.value=(field.value.substring(0,i)+(field.value.substring(i+1,field.value.length)));
				i--
			}
		}
	}
        //-->
        </script>
        
        <script language="JavaScript">
        <!--
	function validateintsigned(field) {
		var valid = "-0123456789"
		var temp;
		for (var i=0; i<field.value.length; i++) {
			temp = "" + field.value.substring(i, i+1);
			if (valid.indexOf(temp) == "-1") {
				field.value=(field.value.substring(0,i)+(field.value.substring(i+1,field.value.length)));
				i--
			}
		}
	}
        //-->
        </script>
        
        <script language="Javascript1.1">
            function imgchange(imgName,imgSrc) {
                if (document.images) {
                    document.images[imgName].src = imgSrc;
                }
            }
            function imgchange2(imgName, imgSrc) {
                if (document.images) {
                    document[imgName].src = eval(imgSrc + ".src");
                }
            }
        </script>
</head>
<body onLoad="highlightFieldFirst()<?=$printmestr.$menubdstr;?>" class="<?= $bodystyle ?>">
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<table border=0 height="100%" width="100%" cellspacing="2" cellpadding="0">
<tr>
<td valign=top height="1"> 
<?
if (!$printable) {
     // load Drop down menu only if CSS is define and Not on help menu
     if ((CSS_MENU == 1) && (strncmp($docfilename,help,4)) && (strncmp($docfilename,lookup,6)) && (strncmp($docfilename,label,5))) {
         include("horimenu2.php");
     } else {
         echo navlinks();
     }
        if (MESSAGING_INSTANT_ON) { //if we should check for new messages.
                if ($docfilename<>"genmessage.php"&&$docfilename<>"menu.php"&&$docfilename<>"index.php") {  //and we're not on a page that won't like us checking
                        $recordSet = &$conn->CacheExecute(60,"select count(*) from genmessage where readdate='0001-01-01 00:00:00' and userid=".sqlprep($userid));
                        if (!$recordSet->EOF) if ($recordSet->fields[0]) {
                                echo '<center><table border=0><tr><th colspan="3"><font size="+1">'.$recordSet->fields[0].'</font> '.STR_NEW_MESSAGES_IN_YOUR_INBOX.'.</th></tr><tr><th>'.ucfirst(STR_SENDER).'</th><th>'.ucfirst(STR_SENT).'</th><th>'.ucfirst(STR_PREVIEW).'</th></tr>';
                                $recordSet2 = &$conn->Execute("select genuser.name, genmessage.entrydate, substring(genmessage.message from 1 for 30) as message, genmessage.id from genmessage,genuser where genuser.id=genmessage.sourceuserid and genmessage.readdate='0001-01-01 00:00:00' and genmessage.userid=".sqlprep($userid));
                                while ($recordSet2&&!$recordSet2->EOF) {
                                        echo '<tr><td>'.$recordSet2->fields[0].'</td><td>'.$recordSet2->fields[1].'</td><td><a href="genmessage.php?messageid='.$recordSet2->fields[3].'">'.$recordSet2->fields[2].'</a></td></tr>';
                                        $recordSet2->MoveNext();
                                };
                                echo '</table></center><br>';
                        };
                };
        };
}
?>
</td>
</tr> 
<tr>
<td valign=top> 
