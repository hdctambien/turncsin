<?php
session_start();
include '../lib/util.php';
include '../lib/util_admin.php';

$config = parse_ini_file("../config/config.ini");

// check if admin is logged in
adminCheck();

$fields = ['slug', 'name', 'file', 'open', 'close'];
$data = [];
foreach($fields AS $field)
{
  if(isset($_POST[$field]))
  {
    $$field = trim($_POST[$field]);
    $$field = filter_var($$field, FILTER_SANITIZE_STRING);
    $data[$field] = $$field;
  }
}

if(!isset($slug) || '' == $slug)
{
  error('Slug must be specified');
}

//remove slug from ini data
unset($data['slug']);

//check if assignment already exits
$slug = preg_replace("/[^a-zA-Z0-9_]/", '', $slug);

$basepath = "../assignments/";
$path = $basepath.$slug;

if(file_exists($path))
{
  error("Assignment already exists");
}

//validate format of open/close
$regex = '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}/';
if(isset($open))
{
  if(!preg_match($regex, $open))
  {
    unset($data['open']);
  }
}

if(isset($open))
{
  if(!preg_match($regex, $close))
  {
    unset($data['close']);
  }
}

// create folder for assignment
mkdir($path);


// create info.ini file for assignment
write_php_ini($data, $path.'/info.ini');

redirect();

/* ********* */
/* FUNCTIONS */
/* ********* */
exit();
function error($message)
{
  redirect('error.php?message='.$message);
}
?>
