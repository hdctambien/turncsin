<?php
session_start();
include 'lib/util.php';

$config = parse_ini_file("config/config.ini");

$logged_in = isLoggedIn();

//get list of assignments
$assignments = [];
if($logged_in)
{
  $assignments = getAssignments($config['timezone']);
}

/* ********* */
/* FUNCTIONS */
/* ********* */
function getAssignments($timezone)
{
  $base = 'assignments/';
  $folders = scandir($base);
  $assignments = [];

  foreach($folders AS $folder)
  {
    if($folder == '.' || $folder == '..') continue;
    if(!is_dir($base.$folder)) continue;

    $info = getAssignmentInfo($folder);

    //calcualte current time
    $date = new DateTime("now", new DateTimeZone($timezone) );
    $time = $date->format('Y-m-d H:i');
    $time = strtotime($time);

    //check if open
    if(isset($info['open']))
    {
      $open = $info['open'];
      $open = strtotime($open);

      if($time < $open) continue;
    }

    //check if closed
    if(isset($info['close']))
    {
      $closed = $info['close'];
      $closed = strtotime($closed);

      if($time > $closed) continue;
    }

    //backup name incase the ini file is not configured correctly
    if(!isset($info['name']))
    {
      $info['name'] = $folder;
    }

    $info['slug'] = $folder;

    array_push($assignments, $info);
  }

  return $assignments;
}

function getAssignmentInfo($slug)
{
  $path = "assignments/${slug}/info.ini";
  if(!file_exists($path)) return [];
  return parse_ini_file($path);
}
?><!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Turner Inner</title>
  <meta name="description" content="Turn CS In">
  <meta name="author" content="Mr. May">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/skeleton.css">

  <link rel="icon" type="image/png" href="img/favicon.png">

</head>

<body>
  <?php if($logged_in): ?>
    <a href="logout.php">logout</a>
  <?php endif; ?>

  <div class="container">
    <div class="row">
      <div class="one-half" style="margin-top: 10%">
        <h1>Turner Inner</h1>

  <?php if($logged_in): ?>

    <form method="post" action="submit.php" enctype="multipart/form-data">

      <div class="row">
        <div class="six columns">
          <label for="name">Your Name (format: first last)</label>
          <input class="u-full-width" type="text" id="name" name="name" autocomplete="off" required pattern="[A-Za-z ]{1,30}" maxlength="30"/>
        </div>
        <div class="six columns">
          <label for="assignment">Assignment</label>
          <select class="u-full-width" id="assignment" name="assignment" required>
            <option value="">Choose assignment...</option>
            <?php foreach($assignments AS $assignment): ?>
              <option value="<?= $assignment['slug'] ?>"><?= $assignment['name'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <label for="upload">Upload File</label>
      <input type="file" id="upload" name="upload" accept=".java,.zip"/>

      <label for="paste">or Paste File Content</label>
      <textarea class="u-full-width" id="paste" name="paste"></textarea>

      <p>Note: uploaded files override pasted content.</p>

      <button class="button-primary">Submit</button>
    </form>
  <?php else: ?>
    <form method="post" action="login.php">
      <label for="password">Password</label>
      <input type="password" id="password" name="password"/>
      <button class="button-primary">Login</button>
    </form>
  <?php endif; ?>
          </div>
        </div>
      </div>
</body>
</html>
