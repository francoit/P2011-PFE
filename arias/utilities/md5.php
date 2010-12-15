<?
include('../includes/functions.php');
  echo 'String: '.$foo.'<br>';
  echo 'String (md5): '.md5($foo).'<br>';
  echo 'String: (pw)'.pwencrypt($foo).'<br>';
?>
