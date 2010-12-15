<?php require_once('includes/main.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
	echo '<center>';
        echo texttitle($lang['STR_MESSAGE_CENTER']);
        echo '<table align="center">';
        echo '<tr><td><img src="images/temp/bb2.gif"></td></tr></table>';
        if ($message) { //send a message
                if (substr_count($user,';')) $user=explode(';', $user); //make array of usernames, incase users specified more than 1 separated with ; (RFC 822)
                if (!is_array($user)) $user=array($user);
                foreach ($user as $data) {
                        $data=trim($data);
                        if (substr_count($data,'@')&&ALLOW_EXTERNAL_EMAIL) {
                                $recordSet = &$conn->Execute("select genuser.name from genuser where id=".sqlprep($userid));
                                mail($data,'NOLA E-mail from '.$recordSet->fields[0], $message);
                                echo textsuccess('Message sent to '.$data.'.');
                        } else {
                                $recordSet = &$conn->Execute("select genuser.id from genuser where name=".sqlprep($data));
                                if ($recordSet->EOF) { //if user specified doesn't exist
                                        echo texterror('User '.$data.' doesn\'t exist.');
                                        $newmessage=1;
                                        $defaultuser.=$data;
                                        $defaultmessage=$message;
                                } else {
                                        $username=$data;
                                        $user=$recordSet->fields[0];
                                        $conn->Execute('insert into genmessage (userid,sourceuserid,entrydate,message) values ('.sqlprep($user).', '.sqlprep($userid).', NOW(), '.sqlprep($message).')');
                                        echo textsuccess('Message sent to '.$username.'.<br>');
                                };
                        };
                };
                echo '<br>';
        };
        if ($messageid) {
                $recordSet = &$conn->Execute("select genuser.name, genmessage.entrydate, genmessage.message, genmessage.readdate, genmessage.userid, genmessage.sourceuserid from genmessage,genuser where genuser.id=genmessage.sourceuserid and genmessage.id=".sqlprep($messageid));
                if (!$recordSet||$recordSet->EOF) die(texterror('Message not found.'));
                if ($recordSet->fields[3]=='0001-01-01 00:00:00'&&$recordSet->fields[4]==$userid) {
                        $conn->Execute("update genmessage set readdate=NOW() where genmessage.id=".sqlprep($messageid));
                        $readdate=createtime('Y-m-d');
                } elseif ($recordSet->fields[3]<>'0001-01-01 00:00:00') {
                        $readdate=$recordSet->fields[3];
                } else {
                        $readdate='';
                };
                echo '<table border="1"><tr><td>'.$lang['STR_FROM'].':</td><td>'.$recordSet->fields[0].'</td></tr>';
                echo '<tr><td>'.$lang['STR_SENT_ON'].':</td><td>'.$recordSet->fields[1].'</td></tr>';
                echo '<tr><td>'.$lang['STR_FIRST_READ_ON'].':</td><td>'.$readdate.'</td></tr>';
                echo '<tr><td>'.$lang['STR_MESSAGE'].':</td><td>'.nl2br($recordSet->fields[2]).'</td></tr></table><br>';
                if ($recordSet->fields[5]<>$userid) echo '<a href="genmessage.php?newmessage=1&defaultuser='.$recordSet->fields[0].'&defaultmessage=Re: '.$recordSet->fields[2].'">Send Reply</a><br>';
                echo '<a href="genmessage.php">'.$lang['STR_BACK_TO_MAILBOX'].'</a>';
        } elseif ($newmessage) {
                echo '<form name="mainform" method="post" action="genmessage.php">';
                echo '<table border="1"><tr><td>To:</td><td><input type="text" name="user" value="'.$defaultuser.'" size="30" maxlength="50"'.INC_TEXTBOX.'></td></tr>';
                echo '<tr><td>'.$lang['STR_SENT_ON'].':</td><td>'.createtime('Y-m-d').'</td></tr>';
                echo '<tr><td>'.$lang['STR_MESSAGE'].':</td><td><textarea name="message" rows="4" columns="30">'.$defaultmessage.'</textarea></td></tr></table><br>';
                echo '<input type="submit" value="'.$lang['STR_SEND_MESSAGE'].'"></form>';
        } else {
                $recordSet = &$conn->Execute("select count(*) from genmessage where readdate='0001-01-01 00:00:00' and userid=".sqlprep($userid));
                if (!$recordSet->EOF) if ($recordSet->fields[0]) {
                        echo '<center><table border="1"><tr><th colspan="3"><font size="+1">'.$recordSet->fields[0].'</font> new message(s).</th></tr><tr><th>Sender</th><th>Received</th><th>Preview</th></tr>';
                        $recordSet2 = &$conn->Execute("select genuser.name, genmessage.entrydate, substring(genmessage.message from 1 for 30) as message, genmessage.id from genmessage,genuser where genuser.id=genmessage.sourceuserid and genmessage.readdate='0001-01-01 00:00:00' and genmessage.userid=".sqlprep($userid).' order by genmessage.entrydate desc');
                        while (!$recordSet2->EOF) {
                                echo '<tr><td>'.$recordSet2->fields[0].'</td><td>'.$recordSet2->fields[1].'</td><td><a href="genmessage.php?messageid='.$recordSet2->fields[3].'">'.$recordSet2->fields[2].'</a></td></tr>';
                                $recordSet2->MoveNext();
                        };
                        echo '</table></center><br>';
                };
                $recordSet = &$conn->Execute("select count(*) from genmessage where userid=".sqlprep($userid)." and genmessage.readdate>'0001-01-01 00:00:00'");
                if (!$recordSet->EOF) if ($recordSet->fields[0]) {
                        echo '<center><table border="1"><tr><th colspan="4">Previous '.$recordSet->fields[0].' read message(s).</th></tr><tr><th>Sender</th><th>Received</th><th>Preview</th></tr>';
                        $recordSet2 = &$conn->SelectLimit("select genuser.name, genmessage.entrydate, substring(genmessage.message from 1 for 30) as message, genmessage.id from genmessage,genuser where genuser.id=genmessage.sourceuserid and genmessage.userid=".sqlprep($userid)." and genmessage.readdate>'0001-01-01 00:00:00' order by genmessage.entrydate desc",10);
                        while (!$recordSet2->EOF) {
                                echo '<tr><td>'.$recordSet2->fields[0].'</td><td>'.$recordSet2->fields[1].'</td><td><a href="genmessage.php?messageid='.$recordSet2->fields[3].'">'.$recordSet2->fields[2].'</a></td></tr>';
                                $recordSet2->MoveNext();
                        };
                        echo '</table></center><br>';
                };
                $recordSet = &$conn->Execute("select count(*) from genmessage where sourceuserid=".sqlprep($userid));
                if (!$recordSet->EOF) if ($recordSet->fields[0]) {
                        echo '<center><table border="1"><tr><th colspan="4">Previous '.$recordSet->fields[0].' sent message(s).</th></tr><tr><th>Receiver</th><th>Sent</th><th>Read</th><th>Preview</th></tr>';
                        $recordSet2 = &$conn->SelectLimit("select genuser.name, genmessage.entrydate, substring(genmessage.message from 1 for 30) as message, genmessage.id, genmessage.readdate from genmessage,genuser where genuser.id=genmessage.userid and genmessage.sourceuserid=".sqlprep($userid)." order by genmessage.entrydate desc",10);
                        while (!$recordSet2->EOF) {
                                if ($recordSet2->fields[4]<>'0001-01-01 00:00:00') {
                                        $readstr='<img src="images/check.jpg">';
                                } else {
                                        $readstr='';
                                };
                                echo '<tr><td>'.$recordSet2->fields[0].'</td><td>'.$recordSet2->fields[1].'</td><td align="center">'.$readstr.'</td><td><a href="genmessage.php?messageid='.$recordSet2->fields[3].'">'.$recordSet2->fields[2].'</a></td></tr>';
                                $recordSet2->MoveNext();
                        };
                        echo '</table></center><br>';
                };
                echo '<a href="genmessage.php?newmessage=1">'.$lang['STR_SEND_NEW_MESSAGE'].'</a><br>';
        };
	echo '</center>';
?>
<?php require_once('includes/footer.php'); ?>
