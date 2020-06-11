<?php
session_start();

include 'lib/util.php';

$config = parse_ini_file("config/config.ini");

$password = isset($_POST['password']) ? $_POST['password'] : false;

if($password == $config['admin_password'])
{
  $_SESSION['logged_in'] = "admin";
}
else if($password == $config['password'])
{
  $_SESSION['logged_in'] = true;
}
else
{
  unset($_SESSION['logged_in']);
}

redirect();
exit()
?>
