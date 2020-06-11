<?php
session_start();

include 'lib/util.php';

$config = parse_ini_file("config/config.ini");

//check if user is logged in
$is_admin = false;
$logged_in = false;
if(isset($_SESSION["logged_in"]))
{
    $logged_in = true;
    $is_admin = $_SESSION["logged_in"] === "admin";
}

$fields = ['name', 'assignment', 'paste'];
foreach($fields AS $field)
{
  if(isset($_POST[$field]))
  {
    $$field = trim($_POST[$field]);
  }
  else
  {
    error("That didn\'t work...");
  }
}

// sanatize name
// only letters and spaces
// replace spaces with underscores
$name = strtolower($name);
$name = preg_replace("/[^a-z ]/i", '', $name);
$name = trim($name);
$name = preg_replace('/\s+/', '_',$name);

// Check for name
if("" == $name || "_" == $name)
{
  error("You must enter your name!");
}

// sanatize assignment
// only letters, numbers, and underscores
$assignment = trim($assignment);
$assignment = preg_replace("/[^a-zA-Z0-9_]/", '', $assignment);

$assignment_folder = "assignments/${assignment}/";
$upload_folder = "${assignment_folder}${name}/";

//verify that assignment is a valid name
if(!is_dir($assignment_folder))
{
  error("Assignment not found");
}

//get assignment $info
$info = [];
if(file_exists($assignment_folder.'info.ini'))
{
  $info = parse_ini_file($assignment_folder.'info.ini');
}

//calcualte current time
$date = new DateTime("now", new DateTimeZone($config['timezone']) );
$time = $date->format('Y-m-d H:i');
$time = strtotime($time);

//check if open
if(isset($info['open']))
{
  $open = $info['open'];
  $open = strtotime($open);

  if($time < $open) error('Assignment not found');
}

//check if closed
if(isset($info['close']))
{
  $closed = $info['close'];
  $closed = strtotime($closed);

  if($time > $closed) error('This assignment is closed');
}

//check if file uploaded or pasted
if($_FILES['upload']['error'] == 0)
{
  createUploadFolder($upload_folder);
  backup($upload_folder, basename($_FILES['upload']['name']), $config['timezone']);
  $filename = $upload_folder . basename($_FILES['upload']['name']);

  if(!checkFileName($filename, $info)) error("You must upload the file: ${info['file']}");

  if (move_uploaded_file($_FILES['upload']['tmp_name'], $filename)) {
    createTimestamp($upload_folder, $filename, $config['timezone']);
    success();
  } else {
      error("An error occured while uploading file.");
  }
}
else if('' != $paste)
{
  // file pasted
  $filename = getFileName($paste);
  if(!$filename)
  {
    error("Unable to determine name of class");
  }

  if(!checkFileName($filename, $info)) error("You must upload the file: ${info['file']}");

  // create file in /assignments/$assignment/$name folder
  createUploadFolder($upload_folder);
  backup($upload_folder, $filename, $config['timezone']);
  file_put_contents($upload_folder.'/'.$filename, $paste);
  createTimestamp($upload_folder, $filename, $config['timezone']);
  success();
}
else
{
  // no file uploaded or pasted
  error("You must submit a file!");
}

error("You found an uncaught error! Congratulations!");
/* ********** */
/** FUNCTIONS */
/* ********** */
exit();
function checkFileName($filename, $info)
{
  if(!isset($info['file'])) return true;
  if(''==$info['file']) return true;
  return $filename == $info['file'];
}

function backup($path, $filename, $timezone)
{
  //if $filename already exists, rename to append .backupTMDHMS
  if(file_exists($path.$filename))
  {
    $date = new DateTime("now", new DateTimeZone($timezone) );
    $time = $date->format('YmdHis');
    $ext = ".backup${time}";

    $newFilename = $filename.$ext;
    rename($path.$filename, $path.$newFilename);
  }

}

function createUploadFolder($path)
{
  if(!is_dir($path))
  {
    mkdir($path);
  }
}

function createTimestamp($path, $filename, $timezone)
{
  //store the current time stamp in a file called log.txt
  $date = new DateTime("now", new DateTimeZone($timezone) );
  $time = $date->format('Y-m-d H:i:s');
  $data = "${time} : ${filename}\n";
  file_put_contents($path."log.txt", $data, FILE_APPEND | LOCK_EX);
}

function getFileName($content)
{
  //remove line breaks
  $content = preg_replace('/\r|\n/', ' ', $content );

  //add space buffer to curly braces
  $content = preg_replace('/{/', ' { ',$content);

  //remove excess spaces
  $content = preg_replace('/\s+/', ' ',$content);

  // find beginning of class name
  $needle = "public class ";
  $index = strpos($content, $needle);
  if(false === $index) return false;
  $index +=  + strlen($needle);

  // find end of class name
  $content = substr($content, $index);
  $index = strpos($content, " ");
  if(false === $index) return false;

  // extract class name
  $name = substr($content, 0, $index);

  $name = trim($name);
  if('' == $name) return false;

  return $name.'.java';
}

function success()
{
  redirect('success.php');
}

function error($message)
{
  redirect('error.php?message='.$message);
}
?>
