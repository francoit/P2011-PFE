<? include_once('../includes/defines.php'); ?>
<?
//usage - utilities/image.php?text=text[&bg=FFFFFF][&t1=000000][&t2=0000FF][&font=times.ttf][&s=11][&xpad=9][&ypad=9]
  Header("Content-type: image/jpeg");
  if(!isset($font)) $font='TIMES.TTF';
  if(!isset($text)) $text='text not set';
  if(!isset($s)) $s=11;
  $size = imagettfbbox($s,0, FONT_PATH.'/'.$font,$text);
  if(!isset($dx))  $dx = abs($size[2]-$size[0]);
  if(!isset($dy))  $dy = abs($size[5]-$size[3]);
  if(!isset($xpad))  $xpad=9;
  if(!isset($ypad))  $ypad=9;
  $im = imagecreate($dx+$xpad,$dy+$ypad); //create a palatted image.   this is strangely enough looking better than true color on our test platforms.
  if(isset($bg)) { //convert colors from hex triplets to single hex values
      $bg=unhexize($bg);
      $bg0=$bg[0];
      $bg1=$bg[1];
      $bg2=$bg[2];
  } else {
      $bg0=0x2C;
      $bg1=0x6D;
      $bg2=0xAF;
  };
  if(isset($t2)) {
      $t2=unhexize($t2);
      $t20=$t2[0];
      $t21=$t2[1];
      $t22=$t2[2];
  } else {
      $t20=0;
      $t21=0;
      $t22=0;
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
  $bgc = ImageColorAllocate($im, $bg0,$bg1,$bg2);
  imageFilledRectangle($im, 0, 0, $dx+$xpad, $dy+$ypad, $bgc);
  if (isset($trans)) imagecolortransparent ($im, $bgc); //make background transparent
  $t2c = ImageColorAllocate($im, $t20,$t21,$t22);
  $t1c = ImageColorAllocate($im, $t10,$t11,$t12);
  ImageRectangle($im,0,0,$dx+$xpad-1,$dy+$ypad-1,$t2c);
  ImageRectangle($im,0,0,$dx+$xpad,$dy+$ypad,$t1c);
  ImageTTFText($im, $s, 0, (int)($xpad/2)+1, $dy+(int)($ypad/2), $t2c, FONT_PATH.'/'.$font, $text);
  ImageTTFText($im, $s, 0, (int)($xpad/2), $dy+(int)($ypad/2)-1, $t1c, FONT_PATH.'/'.$font, $text);
  imageinterlace ($im, 1); //interlace image if it is a jpeg
  if (isset($trans)) imagecolortransparent ($im, $bgc); //make background transparent
  Imagejpeg($im);
  ImageDestroy($im);
  
  
function unhexize ($color) {
    $color=hexdec($color);
    $red=($color&0xFF0000)>>16;
    $green = ($color&0xFF00)>>8;
    $blue = ($color&0xFF);
    return array ($red, $green, $blue);
}

?>
