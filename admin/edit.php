<?php
session_start();
include '../lib/util.php';
include '../lib/util_admin.php';

$config = parse_ini_file("../config/config.ini");

// check if admin is logged in
adminCheck();

$slug = getAssignmentSlugOrRedirect();
$info = getAssignmentInfo($slug);

?><!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Turner Inner | Edit <?= getArrayValue($info, 'name', 'Unnamed Assignment') ?></title>
  <meta name="description" content="Admin | Turn CS In">
  <meta name="author" content="Mr. May">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="../css/normalize.css">
  <link rel="stylesheet" href="../css/skeleton.css">

  <link rel="icon" type="image/png" href="../img/favicon.png">

</head>

<body>
    <a href="../logout.php">logout</a>

  <div class="container">
    <div class="row">
      <div class="one-half" style="margin-top: 10%">
        <h1>Turner Inner</h1>
        <h4>Edit <?= getArrayValue($info, 'name', 'Unnamed Assignment') ?></h4>

          <form action="save_edit.php" method="POST">
            <input type="hidden" name="slug" value="<?= $slug ?>"/>
            <label for="name">Name</label>
            <input class="u-full-width" type="text" id="name" name="name" value="<?=  geTArrayValue($info, 'name', '') ?>"/>

            <label for="file">Required File Name</label>
            <input class="u-full-width" type="text" id="file" name="file" value="<?=  geTArrayValue($info, 'file', '') ?>"/>

            <div class="row">
              <div class="six columns">
                <label for="open">Open Date (Y-m-d h:i)</label>
                <input class="u-full-width" type="text" id="open" name="open" value="<?=  geTArrayValue($info, 'open', '') ?>"/>
              </div>
              <div class="six columns">
                <label for="close">Close Date (Y-m-d h:i)</label>
                <input class="u-full-width" type="text" id="close" name="close" value="<?=  geTArrayValue($info, 'close', '') ?>"/>
              </div>
            </div>


            <button class="button-primary">Save Changes</button>
            <a href="<?= url() ?>">Cancel</a>
          </form>

          <?php if(countTurnedInAssignments($slug) == 0): ?>
          <form action="delete_assignment.php" method="POST" onclick="return confirm('Are you sure you want to delete this assignment?')">
            <input type="hidden" name="slug" value="<?= $slug ?>"/>
            <button>Delete this Assignment</button>
          </form>
          <?php endif; ?>

        </div>
      </div>
    </div>
</body>
</html>
