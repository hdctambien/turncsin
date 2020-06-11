<?php
session_start();
include '../lib/util.php';
include '../lib/util_admin.php';

$config = parse_ini_file("../config/config.ini");

// check if admin is logged in
adminCheck();

if(isset($_POST['slug']))
{
  $slug = $_POST['slug'];
  $slug = preg_replace("/[^a-zA-Z0-9_]/", '', $slug);

  if(countTurnedInAssignments($slug) > 0) error('Cannot delete assignment that has already recieved submissions');

  $path = '../assignments/'.$slug;
  if(file_exists($path))
  {
    delete_files($path);
  }
}

redirect();


/* ********* */
/* FUNCTIONS */
/* ********* */
exit();

function error($message)
{
  redirect();
}
?>
