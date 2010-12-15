 -r nola/docmgmtadd.php nola.orig/docmgmtadd.php
120,130d119
<         $nondisallowedfile=1;
<         foreach($disallowedfileext as $this) {
<                 if ($substr_count($file_name, $this)) {
<                      $nondisallowedfile=0;
<                      break;
<                 };
<         };
< 
<         // illegal file type!
<         if ($nondisallowedfile != 1) die(texterror('This file type is not supported.'));
< 
 -r nola/includes/defines.php nola.orig/includes/defines.php
301,303d300
< //disallowed file extentions
<   $disallowedfileext=array('.php','.phps','.php3');
< 
 -r nola/invitemadd1.php nola.orig/invitemadd1.php
21,31d20
<                 $nondisallowedfile=1; 
<                 foreach($disallowedfileext as $this) {
<                    if ($substr_count($graphic_name, $this)) {
<                        $nondisallowedfile=0;
<                        break;
<                    };
<                 };
< 
<                 // illegal file type!
<                 if ($nondisallowedfile != 1) die(texterror('This file type is not supported.'));
< 
45,55d33
<                 $nondisallowedfile=1;
<                 foreach($disallowedfileext as $this) {
<                    if ($substr_count($catalogsheet_name, $this)) {
<                        $nondisallowedfile=0;
<                        break;
<                    };
<                 };
< 
<                 // illegal file type!
<                 if ($nondisallowedfile != 1) die(texterror('This file type is not supported.'));
< 
 -r nola/invitemupd.php nola.orig/invitemupd.php
27,37d26
<                     $nondisallowedfile=1;
<                     foreach($disallowedfileext as $this) {
<                         if ($substr_count($graphic_name, $this)) {
<                             $nondisallowedfile=0;
<                             break;
<                         };
<                     };
< 
<                     // illegal file type!
<                     if ($nondisallowedfile != 1) die(texterror('This file type is not supported.'));
< 
51,61d39
<                     $nondisallowedfile=1;
<                     foreach($disallowedfileext as $this) {
<                         if ($substr_count($catalogsheet_name, $this)) {
<                             $nondisallowedfile=0;
<                             break;
<                         };
<                     };
< 
<                     // illegal file type!
<                     if ($nondisallowedfile != 1) die(texterror('This file type is not supported.'));
< 
171c149
< <?php include('includes/footer.php'); ?>
---
> <?php include('includes/footer.php'); ?>
\ No newline at end of file

