<?php
// Copyright 2001 Noguska (All code unless noted otherwise)
// Copyright 2002, 2003 Free Software Foundation (All language array code or where directly annotated in code)
?>
<?php
//helpglpieadd.php

echo '<center><b>GL Pie Chart - Part One</b></center><br>';
echo '<i>Pie Charts allow you to visually compare data such as sales from different sources, sales from different time frames, etc.</i><br><br>';
echo '<b>PIE CHART TO EDIT/PRINT: </b>Select a previously defined Pie Chart from the pull-down list.<br><br>';
echo '<b>EDIT or PRINT: </b>Edit the information on an existing pie chart, or print the chart.<br><br><br>';
echo '<b>ADD NEW PIE </b>This is the only choice you have if you have not created a pie chart before. Use this button to start defining a new pie chart. <br><br>';


echo '<center><b>GL Pie Chart - Part Two</b></center><br>';
echo '<i>Adding or Editing a Pie Chart will bring you to this screen next.</i><br><br>';
echo '<b>PIE NAME: </b>What you want the title of the pie chart to be.<br><br>';
echo '<b>PIE DESCRIPTION: </b>It is a good idea to put in a full description of the pie chart in case you have several very similar ones that you create.<br><br><br>';
echo '<b>BEGIN DATE: </b>Date fields will usually need editing when creating a pie chart. NOTE: Entering beginning date here can then be applied to ALL the slices of the pie by leaving those dates blank.<br><br><br>';
echo '<b>END DATE: </b>End Date for data to be included in the pie chart (see note above).<br><br><br>';
echo '<b>SLICE NAME: </b>Name each of the slices of the pie. You are allowed a maximum of 12 slices for the pie, but you can leave as many blank as you wish.<br><br><br>';
echo '<b>SLICE BEGIN DATE: </b>Only fill this date in if it will be DIFFERENT for each slice (for example when comparing sales for each month of the year, then each of the 12 slices, each representing one month, would have different begin and end dates. However, if you are comparing sales of differnt types, all for the SAME time frame, leave these dates blank, and use the general dates at the top.<br><br><br>';
echo '<b>SLICE END DATE: </b>See notes under Slice Begin Date.<br><br><br>';
echo '<b>DELETE SLICE: </b>Check the delete box if you want to remove a previously entered pie slice.<br><br><br>';


echo '<b>SAVE PIE: </b>Select SAVE if you have made any changes to the pie definition. <br><br>';
echo '<b>DELETE PIE: </b>You will only see this button if you are EDITING an existing pie. <br><br>';
echo '<b>Create Pie Chart: </b>Select this link at the bottom of your display to actually create a pie chart using the data entered. As with Delete, this only appears when Edit or Print has been selected at the start. <br><br>';

echo '<center><H2><b>GL Pie Chart - Part Three</b></H2></center><br>';
echo '<i>SAVE PIE in Part Two will bring you to this screen.</i><br><br>';
echo '<b>SLICE TO EDIT: </b>Select the slice you want to enter details for.<br><br>';
echo '<b>Edit Slice Details: </b>Press this button to bring up detail editing for selected slice.<br><br><br>';


echo '<center><b>GL Pie Chart - Part Four</b></center><br>';
echo '<i>This is where the real work to creating a pie chart is involved. Here you must specify which General Ledger accounts and which companies (if you have multiple companies) that are to be summed up into the slice listed at the top.</i><br><br>';
echo '<b>GL Account-Description: </b>Select a General Ledger Account from the pull-down list.<br><br>';
echo '<b>Company: </b>If you have multiple companies, select which ones to include in the slice.<br><br><br>';
echo '<b>Delete Detail Line: </b>Check this box if you want to remove this account from the slice.<br><br><br>';
echo '<b>SAVE DETAILS: </b>Save any information showing, and select another slice to edit.<br><br><br>';
echo '<b>ADD NEW DETAIL: </b>Press this button to add a new detail (it will be your ONLY option when starting on a brand-new slice.<br><br><br>';

echo '<center><b>GL Pie Chart - Part Five</b></center><br>';
echo '<i>You will see this screen when ADDING a NEW DETAIL.</i><br><br>';
echo '<b>GL Account: </b>Select a General Ledger Account from the pull-down list.<br><br>';
echo '<b>Company: </b>If you have multiple companies, select which ones to include in the slice.<br><br><br>';
echo '<b>SAVE NEW DETAIL: </b>Add the new detail to your slice.<br><br><br>';

?>

