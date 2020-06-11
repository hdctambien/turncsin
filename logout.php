<?php
session_start();
include 'lib/util.php';
unset($_SESSION['logged_in']);
redirect();
exit();
?>
