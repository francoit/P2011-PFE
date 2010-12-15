<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?
     function estquotecblstockadd($name,$orderflag) {
          global $conn, $lang, $userid;
          checkpermissions('est');
          if ($conn->Execute("insert into estquotecblgenstock (name,orderflag, entrydate, entryuserid, lastchangeuserid) VALUES (".sqlprep($name).",".sqlprep($orderflag).", NOW(), ".sqlprep($userid).", ".sqlprep($userid).")") === false) {
                     echo texterror("Error adding stock.");
               return 0;
          } else {
                     echo textsuccess("Stock added successfully.");
               return 1;
          };
     };

     function formestquotecblstockadd() {
     	  global $conn, $lang;
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Stock Name:</td><td><input type="text" name="name" size="30" maxlength="30"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Priority:</td><td><input type="text" name="orderflag" size="10" maxlength="10" value="50"'.INC_TEXTBOX.'></td></tr>';
          return 1;
     };

     function estquotecblstockupd($name,$id,$orderflag,$lastchangedate) {
          global $conn, $lang, $userid;
          checkpermissions('est');
          $recordSet=&$conn->Execute("select count(*) from estquotecblgenstock where id=".sqlprep($id)." and lastchangedate=".sqlprep($lastchangedate));
          if (!$recordSet->EOF) {
               if ($recordSet->fields[0]==0) {
                    showwhochanged($id,"stock","id");
                    return 0;
               };
          };
          if ($conn->Execute('update estquotecblgenstock set name='.sqlprep($name).', orderflag='.sqlprep($orderflag).', lastchangeuserid='.sqlprep($userid).', lastchangedate=NOW() where id='.sqlprep($id)) === false) {
                     echo texterror("Error updating stock.");
               return 0;
          } else {
                     echo textsuccess("Stock updated successfully.");
               return 1;
          };
     };

     function formestquotecblstockupd($genstockid,$name,$orderflag) {
          global $conn, $lang, $userid;
          $recordSet=&$conn->Execute('select name,lastchangedate,orderflag from estquotecblgenstock where id='.sqlprep($genstockid));
          if (!$recordSet->EOF) {
                $name=$recordSet->fields[0];
                $lastchangedate=$recordSet->fields[1];
                $orderflag=$recordSet->fields[2];
          } else { // how could this happen??
            die(texterror("Could not find stock to edit"));
          };
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Stock Name:</td><td><input type="text" name="name" size="30" maxlength="30" value="'.$name.'"'.INC_TEXTBOX.'></td></tr>';
          echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Priority:</td><td><input type="text" name="orderflag" size="10" maxlength="10" value="'.$orderflag.'"'.INC_TEXTBOX.'></td></tr>';
          echo '<input type="hidden" name="lastchangedate" value="'.$lastchangedate.'">';
          echo '<input type="hidden" name="genstockid" value="'.$genstockid.'">';

          return 1;
     };

     function formestquotecblsubstockadd($name,$weight,$turnaround,$substockname,$suborderflag,$parts) {
              global $conn, $lang,$userid;
              if (!$suborderflag) $suborderflag=50;
              echo '<tr><th> Stock = '.$name.'</th></tr>';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Subgroup Name:</td><td><input type="text" name="substockname" size="50" maxlength="50" value="'.$substockname.'"'.INC_TEXTBOX.'></td></tr>';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Weight/M 8.5 x 11 Sheets:</td><td><input type="text" name="weight" size="10" maxlength="10" value="'.$weight.'"'.INC_TEXTBOX.'></td></tr>';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Turnaround Days:</td><td><input type="text" name="turnaround" size="3" maxlength="3" value="'.$turnaround.'"'.INC_TEXTBOX.'></td></tr>';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Sort Priority:</td><td><input type="text" name="suborderflag" size="10" maxlength="10" value="'.$suborderflag.'"'.INC_TEXTBOX.'></td></tr>';
              echo '<tr><td align="'.TABLE_LEFT_SIDE_ALIGN.'">Number of Parts:</td><td><input type="text" name="parts" size="1" maxlength="1" value="'.$parts.'"'.INC_TEXTBOX.'></td></tr>';
    };
?>