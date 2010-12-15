<? include_once('../includes/defines.php'); ?>
<?
//usage - images/dynmenubutton.php?image=image.png&text=text[&t1=000000][&font=times.ttf]
  Header("Content-type: image/png");
  if(!isset($font)) $font='ARIAL.TTF';
  if(!isset($text)) $text='text not set';
  if(!isset($s)) $s=10;
//$image='temp/blankwhitebutton.png';
  $im = imagecreatefrompng($image); //create a image from the image passed
  if(isset($bg)) {
      $bg=unhexize($bg);
      $bg10=$bg[0];
      $bg11=$bg[1];
      $bg12=$bg[2];
  } else {
      $bg10=255;
      $bg11=255;
      $bg12=255;
  };
  if(isset($t1)) {
      $t1=unhexize($t1);
      $t10=$t1[0];
      $t11=$t1[1];
      $t12=$t1[2];
  } else {
      $t10=0;
      $t11=0;
      $t12=0;
  };
//  $transcolor=imagecolorat(0,0);
  $transcolor=imagecolorat($im,0,0);
  imagecolorset ($im, 21, $bg10, $bg11, $bg12);
  if ($bg10-33>0) $bg20=$bg10-33;
  if ($bg11-33>0) $bg21=$bg11-33;
  if ($bg12-33>0) $bg22=$bg12-33;
  imagecolorset ($im, 11, $bg20, $bg21, $bg22); //shadow

  $t1c = ImageColorAllocate($im, $t10,$t11,$t12);
  if (strlen($text)>13) {
     $size = imagettfbbox($s-3,0, FONT_PATH.'/'.$font,$text);
     if(!isset($dx))  $dx = abs($size[2]-$size[0]);
     if(!isset($dy))  $dy = abs($size[5]-$size[3]);
     $x=imagesx($im)/2-$dx/2-1;
     $y=imagesy($im)/2+$dy/2+1;
     ImageTTFText($im, $s-3, 0, $x, $y, $t1c, FONT_PATH.'/'.$font, $text);
  } else {
     $size = imagettfbbox($s,0, FONT_PATH.'/'.$font,$text);
     if(!isset($dx))  $dx = abs($size[2]-$size[0]);
     if(!isset($dy))  $dy = abs($size[5]-$size[3]);
     $x=imagesx($im)/2-$dx/2-1;
     $y=imagesy($im)/2+$dy/2+1;
     ImageTTFText($im, $s, 0, $x, $y, $t1c, FONT_PATH.'/'.$font, $text);
  };
  imageinterlace ($im, 1); //interlace image if it is a jpeg
  Imagepng($im);
  ImageDestroy($im);
  
  
function unhexize ($color) {
    $color=hexdec($color);
    $red=($color&0xFF0000)>>16;
    $green = ($color&0xFF00)>>8;
    $blue = ($color&0xFF);
    return array ($red, $green, $blue);
}

?>
