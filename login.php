<?php
$user=$_POST['user']??'';
$pass=$_POST['pass']??'';
if($user==='admin' && $pass==='admin123'){
   $ip=$_SERVER['HTTP_X_FORWARDED_FOR']??$_SERVER['REMOTE_ADDR'];
   file_put_contents('log.txt',date('Y-m-d H:i:s')." | $ip | $user\n",FILE_APPEND);
   header('Location: dashboard.html');
   exit;
}
header('Location: index.html?err=1');
?>
