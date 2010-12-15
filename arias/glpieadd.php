<?php include('includes/main.php'); ?>
<?php include('includes/glfunctions.php'); ?>
<?
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php //glpieadd.php

   //  Select a pre-defined pie chart & edit or print
   //  Create a new pie chart definition
   echo TextTitle($lang['STR_GENERAL_LEDGER_PIE_CHART']);
   echo '<center>';
   $choice=0;
//   echo $editprint.$addpie.$delpie.$savepie.$editslice.$saveslice.$addslice.$newslice;
   if ($saveslice) {
      $AddUpdate=0; //update flag
      GLPieSliceDetailAddUpdate($AddUpdate,$id,$sliceid,0,$glaccountid,$companyid,$slicedetaillastchangedate);
      $editslice; // return to EDIT after save new
   };

   //$editprint="Edit or Print" edit/print existing pie
   //$addpie="Add New Pie" to create a new pie
   //$delpie="Delete Pie" to delete existing pie
   //$savepie="Save Pie" to save changes then go to edit slice details
   //$editslice="Edit Slice Details" to edit a pie slice details
   //$saveslice="Save New Details" to save a pie slice details
   //$addslice="Add New Detail" to create a new pie slice detail
   //$newslice="Pick New Slice" to pick a new slice for detail edit
   if ($editprint||$addpie) { //add or edit main pie info
       //get main pie data now
       $choice=1;
       echo '<form action="glpieadd.php" method="post"><input type="hidden" name="nonprintable" value="1"><table>';
       if ($addpie) $id="";
       echo '<input type="hidden" name="id" value="'.$id.'">';
       $AddUpdate=formglPie($id,$editprint);

       echo '</table><input type="submit" name="savepie" value="'.$lang['STR_SAVE_PIE'].'">';
       if ($editprint) {
          echo '<input type="submit" name="delpie" value="'.$lang['STR_DELETE_PIE'].'"></form><br>';
          echo '<br><a href="glpie.php?printable=1&id='.$id.'">'.$lang['STR_CREATE_PIE_CHART'].'</a>';
       } else {
       echo '</form><br>';
       }
   } elseif ($delpie&&$id) {
          //delete pie info in glpie, glpieslice, and glpieslicedetail
          DeletePie($id);
          DeletePieSlice($id,0);    //0=no specific slice
          DeletePieSliceDetail($id,0,0);  //0=no specific slice or detail
   } elseif ($savepie||$newslice) {
        if ($savepie) {
          if ($id) {
          $AddUpdate=1;
       } else {
          $AddUpdate=0;
       };
          glPieAddUpdate($AddUpdate,$id,$name,$description,$begindate,$findate,$lastchangedate);
          if (!$id) {//if this was an add, need to read id
              $recordSet=&$conn->Execute('select id from glpie where name='.sqlprep($name).' and description='.sqlprep($description));
              if (!$recordSet->EOF) $id=$recordSet->fields[0];
          };
          for ($scount=1;$scount<13;$scount++) {
              if (${"sliceid".$scount}) { //this slice exists already
                  if (${"slicedelete".$scount}) { //delete, do not update
                      DeletePieSlice($id,${"sliceid".$scount});
                  } else {//update slice data
                      glPieSliceAddUpdate(1,$id,${"sliceid".$scount},${"slicename".$scount},${"slicebegindate".$scount},${"slicefindate".$scount},${"slicelastchangedate".$scount});
                  };
              } elseif (${"slicename".$scount}) { //add a new slice
                  if (!${"slicedelete".$scount}) glPieSliceAddUpdate(0,$id,${"sliceid".$scount},${"slicename".$scount},${"slicebegindate".$scount},${"slicefindate".$scount},${"slicelastchangedate".$scount});
              };
          };
       };
       //pick a slice to enter details for
          echo '<form action="glpieadd.php" method="post">';
          echo '<table><tr><th colspan="2">'.$lang['STR_GENERAL_LEDGER_PIE_SLICE_DEFINITION'].'</th></tr>';
          echo '<input type="hidden" name="id" value="'.$id.'">';
          echo '<input type="hidden" name="name" value="'.$name.'">';
          echo '<tr><td>Slice to Edit:</td><td><select name="sliceid">';
          $recordSet = &$conn->Execute('select id, name from glpieslice where glpieid='.$id.' order by name');
          while (!$recordSet->EOF) {
                     echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
                     $recordSet->MoveNext();
          };
          echo '</select></td></tr>';
          echo '</table><input type="submit" name="editslice" value="'.$lang['STR_EDIT_SLICE_DETAILS'].'">';
          echo '</form>';
          $choice=1;
     } elseif ($editslice) {
            //display current slice details and allow edit.
            //Also have ADD NEW and SAVE buttons
          echo '<form action="glpieadd.php" method="post">';
          echo '<table><tr><th colspan="2">'.$lang['STR_GENERAL_LEDGER_PIE_CHART_DEFINITION'].'</th></tr>';
          echo '<input type="hidden" name="id" value="'.$id.'">';
          echo '<input type="hidden" name="name" value="'.$name.'">';
          echo '<input type="hidden" name="sliceid" value="'.$sliceid.'">';
          $recordSet=&$conn->Execute('select name from glpieslice where id='.sqlprep($sliceid));
          if (!$recordSet->EOF) {
                $slicename=$recordSet->fields[0];
          } else {
                die(TextError($lang['STR_INVALID_SLICE']));
          };
          echo '<tr><th colspan="3">'.$lang['STR_SLICE'].': '.$slicename.'</th></tr>';
          echo '<tr><th>'.$lang['STR_GL_ACCOUNT_DESCRIPTION'].'</th>';
          if ($multi_company) echo '<th>'.$lang['STR_COMPANY_ZERO_EQUALS_ALL'].'</th>';
          echo '<th>'.$lang['STR_DELETE_DETAIL_LINE'].'<input type="checkbox" checked></th></tr>';
          $recordSet=&$conn->Execute('select glpieslicedetail.glaccountid,glpieslicedetail.companyid,glpieslicedetail.lastchangedate,glaccount.name,glaccount.description,glpieslicedetail.id from glpieslicedetail,glaccount where glpieslicedetail.glaccountid=glaccount.id and glpieslicedetail.glpiesliceid='.sqlprep($sliceid));
          $dcount=1;
          while (!$recordSet->EOF) {
                   $dcount=$dcount+1;
                   echo '<input type="hidden" name="slicedetaillastchangedate'.$dcount.'" value="'.$recordSet->fields[2].'">';
                   echo '<input type="hidden" name="slicedetailid'.$dcount.'" value="'.$recordSet->fields[5].'>"';
                   echo '<tr><td><select name="glaccountid'.$dcount.'"'.INC_TEXTBOX.'>';
                   $recordSet1=&$conn->Execute('select glaccount.id, glaccount.name, glaccount.description  from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by name');
                   while (!$recordSet1->EOF) {
                     $sel="";
                     if ($recordSet->fields[0]==$recordSet1->fields[0]) $sel=" selected ";
                     echo '<option value="'.$recordSet1->fields[0].'"'.$sel.'>'.$recordSet1->fields[1].' - '.$recordSet1->fields[2]."\n";
                     $recordSet1->MoveNext();
                   };
                   echo '</select></td>';
                   if ($multi_company) {  //only ask company if companyid under account number is for ANY
                        echo '<td><select name="companyid'.$dcount.'"'.INC_TEXTBOX.'>';
                        echo '<option value="0"'.checkequal(0,$recordSet->fields[1]," selected").'>'.$lang['STR_ZERO_ALL_COMPANIES'];
                        $recordSet2 = &$conn->Execute('select id, name from gencompany order by name');
                        while (!$recordSet2->EOF) {
                                 echo '<option value="'.$recordSet2->fields[0].'"'.checkequal($recordSet2->fields[0],$recordSet->fields[1]," selected").'>'.$recordSet2->fields[1]."\n";
                                 $recordSet2->MoveNext();
                        };
                        echo '</select></td>';
                   } else {
                     echo '<input type="hidden" name="companyid'.$dcount.'" value="0">';
                   };
                   echo '<td><input name="detaildelete'.$dcount.'" type="checkbox"></td></tr>';
                   $recordSet->MoveNext();
          };
          echo '<input type="hidden" name="dcount" value="'.$dcount.'">';
          echo '</table><input type="submit" name="saveslice" value="'.$lang['STR_SAVE_DETAILS'].'">';
          echo '<input type="submit" name="addslice" value="'.$lang['STR_ADD_NEW_DETAIL'].'"></form>';
          $choice=1;
     } elseif ($addslice||$saveslice) { //save details first in either case
          for ($dc=1;$dc<=$dcount;$dc++){
                  if (${"detaildelete".$dc}) {
               DeletePieSliceDetail($id,$sliceid,${"slicedetailid".$dc});
                  } else {
                        GLPieSliceDetailAddUpdate(1,$id,$sliceid,${"slicedetailid".$dc},${"glaccountid".$dc},${"companyid".$dc},${"slicedetaillastchangedate".$dc});
                  };
          };
          if ($saveslice) {
               //quit at this point if save only
               //ask if edit another slice first
               echo '<form action="glpieadd.php" method="post"><table>';
               echo '<input type="hidden" name="id" value="'.$id.'">';
               echo '<input type="hidden" name="name" value="'.$name.'">';
               echo '<input type="hidden" name="sliceid" value="'.$sliceid.'">';
               echo '</table><input type="submit" name="newslice" value="'.$lang['STR_PICK_NEW_SLICE'].'"></form>';
          } else { // add new detail now
            echo '<form action="glpieadd.php" method="post">';
            echo '<table><tr><th colspan="2">'.$lang['STR_GENERAL_LEDGER_PIE_CHART_DEFINITION'].'</th></tr>';
            echo '<input type="hidden" name="id" value="'.$id.'">';
            echo '<input type="hidden" name="name" value="'.$name.'">';
            echo '<input type="hidden" name="sliceid" value="'.$sliceid.'">';
            $recordSet=&$conn->Execute('select name from glpieslice where id='.sqlprep($sliceid));
            if (!$recordSet->EOF) {
                $slicename=$recordSet->fields[0];
            } else {
                die(TextError($lang['STR_INVALID_SLICE']));
            };
            echo '<tr><th colspan="3">'.$lang['STR_SLICE'].' : '.$slicename.'</th></tr>';
            echo '<tr><td>'.$lang['STR_GL_ACCOUNT'].':</th><td><select name="glaccountid" >';
            $recordSet1=&$conn->Execute('select id, name, description from glaccount where (companyid=0 or companyid='.sqlprep($active_company).') order by name');
            while (!$recordSet1->EOF) {
                echo '<option value="'.$recordSet1->fields[0].'"'.$sel.'>'.$recordSet1->fields[1].' - '.$recordSet1->fields[2]."\n";
                $recordSet1->MoveNext();
            };
            if ($multi_company) {
                 echo '<td><select name="companyid" '.INC_TEXTBOX.'><option value="0">'.$lang['STR_ZERO_ALL_COMPANIES'];
                 $recordSet2 = &$conn->Execute('select id, name from gencompany order by name');
                 while (!$recordSet2->EOF) {
                   $sel="";
                   if( $active_company==$recordSet2->fields[0]) $sel=" selected ";
                   echo '<option value="'.$recordSet2->fields[0].'"'.$sel.'>'.$recordSet2->fields[1]."\n";
                   $recordSet2->MoveNext();
                 };
            };
            echo '</select></td></tr>';
            echo '</table><input type="submit" name="saveslice" value="'.$lang['STR_SAVE_NEW_DETAIL'].'">';
            echo '</form>';
            $choice=1;
          };
     };
     if ($choice==0) { // select an existing pie or press button to add a new one
     // option button to delete selection instead of submit if want this
     echo '<form action="glpieadd.php" method="post">';
     $recordSet = &$conn->Execute('select id, name from glpie where cancel=0 order by name');
     if (!$recordSet->EOF) {
          echo '<table><tr><th colspan="2">'.$lang['STR_GENERAL_LEDGER_PIE_CHART_DEFINITION'].'</th></tr>';
          echo '<td>'.$lang['STR_PIE_CHART_TO_EDIT_PRINT'].':</td><td><select name="id">';
          while (!$recordSet->EOF) {
               echo '<option value="'.$recordSet->fields[0].'">'.$recordSet->fields[1]."\n";
               $recordSet->MoveNext();
          };
          echo '</select></td></tr></table><input type="submit" name="editprint" value="'.$lang['STR_EDIT_OR_PRINT'].'">';
     };
     echo '<input type="submit" name="addpie" value="'.$lang['STR_ADD_NEW_PIE'].'">';
     echo '</form>';
     }
     
     echo '</center>';
?>

<?php include('includes/footer.php'); ?>
