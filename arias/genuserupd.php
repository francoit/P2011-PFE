<?php include('includes/main.php'); ?>
<?php include('includes/genfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
        echo '<center>';
        echo texttitle($lang['STR_USER_UPDATE']);
        if (!$usersupervisor) $id=$userid;       //if the user isn't a supervisor, only let him edit his own id.
        if (DEMO_MODE) echo $lang['STR_UPDATE_USER_DISABLED_IN_DEMO_MODE'].'<br>';
        if ($id&&!DEMO_MODE) {
                if ($delete) {
                        if (genuserdelete($id)) die(); //genuserdelete returns a message on success/failure
                } elseif ($name) {
                        if (genuserupdate($id, $name, $newpassword, $newpassword2, $stylesheetid, $dlanguage)&&$usersupervisor) genuserupdaterights($id, $raccessap, $raccessar, $raccessgl, $raccesspay, $raccessinv, $raccessest, $raccessfix, $raccessimp, $waccessap, $waccessar, $waccessgl, $waccesspay, $waccessinv, $waccessest, $waccessfix, $waccessimp, $saccessap, $saccessar, $saccessgl, $saccesspay, $saccessinv, $saccessest, $saccessfix, $saccessimp, $supervisoro, $active);
                };
                echo '<form action="genuserupd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
                echo '<input type="hidden" name="id" value="'.$id.'">';
                formgenuserupdate($id);
                if ($usersupervisor) formgenuserupdaterights($id);
                echo '</table><br><input type="submit" value="'.$lang['STR_SAVE_CHANGES'].'"></form>';
                if ($usersupervisor){
                    echo '<br><a href="javascript:confirmdelete(\'genuserupd.php?delete=1&id='.$id.'\')">'.$lang['STR_DELETE_THIS_USER'].'</a>';
               }
        } else {
                echo '<form action="genuserupd.php" method="post"><table>';
                formgenuserselect('id');
                echo '</table><br><input type="submit" value="'.$lang['STR_EDIT_SELECTED_USER'].'"></form>';
                echo '<br><a href="genuseradd.php">'.$lang['STR_ADD_NEW_USER'].'</a>';
               }
        
        echo '</center>';
?>

<?php include('includes/footer.php'); ?>
