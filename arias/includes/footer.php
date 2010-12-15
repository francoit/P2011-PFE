<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
        //echo '</td></tr></table>';

        //check if a help file exists, and create link
        if ($helpanchor) $anchorstr='#'.$helpanchor;
        if (file_exists("$DOCUMENT_ROOT/help/help".$docfilename)) $helpstr=' - <a href="javascript:doNothing()" onclick="top.newWin = window.open(\'../help/help'.$docfilename.'?nonprintable=1'.$anchorstr.'\',\'cal\',\'dependent=yes,width=500,height=600,screenX=200,screenY=300,titlebar=yes,toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1\')"'.tooltip('This link opens a new window with help for this specific page.').'">Help</a>';
        $message=easteregg(createtime('md')).' '; //print special message on holidays
        if (createtime('Y')>2001) $yearstr='-'.createtime('Y'); //create date range range for copyright notice

        echo '<br><tr><td valign="bottom"><table valign="bottom" align="center">';
        if (!$printable) {
            echo '<tr align="center"><td align="center" class="footer">'.$message.' &copy; <a href="http://arias.sourceforge.net" target="_blank">ARIA</a>'.$yearstr.'</td></tr>';
            echo '<tr align="center"><td align="center" class="footer">http://arias.sourceforge.net '.$helpstr.'  -  ';
        }

        if (!$nonprintable) { //get variables to create print link, unless we shouldn't make one
                while(list($key, $val) = each($HTTP_POST_VARS)) {
                        $key = urlencode(stripslashes($key));
                        $val = urlencode(stripslashes($val));
                        if ($key!="printable") $getstring .= "$key=$val&";
                        $poststring .= "$key=$val&";
                };
                while(list($key, $val) = each($HTTP_GET_VARS)) {
                        $key = urlencode(stripslashes($key));
                        $val = urlencode(stripslashes($val));
                        if ($key!="printable") $getstring .= "$key=$val&";
                };
                if (strlen($poststring.$getstring)<1024) { //create the correct print link (printable or normal)
                    if (!$printable) {
                            //echo '<a href="javascript:location.replace(\''.$docfilename.'?'.$poststring.$getstring.'printable=1\')"'.tooltip('This link changes the page to a black and white printable format.').'>Print</a>';
                            echo '<a href="javascript:doNothing()" onclick="top.newWin = window.open (\''.$docfilename.'?'.$poststring.$getstring.'printable=1\',\'print\',\'dependent=yes,width=600,height=420,screenX=100,screenY=50,titlebar=yes,menubar=yes,scrollbars=1,resizable=1\')"'.tooltip('This link changes the page to a black and white printable format.').'>'.$lang['STR_PRINT'].'</a><br><br>';
                            echo '<a href="mailto:jflechtner@users.sourceforge.net?subject=ARIA user feedback">'.$lang['STR_SUBMIT_USER_FEEDBACK'].'</a>';
                    } else {
                            //echo '<tr align="center"><td align="center" class="footer"><a href="javascript:location.replace(\''.$docfilename.'?'.$poststring.$getstring.'printable=0\')"'.tooltip('This link changes the page to it\'s normal viewing format.').'>This is a computer generated form -- No Signature Required.</a>';
                    };
                };
        };
        
        echo '</font></td></tr></table></center>';
?>
