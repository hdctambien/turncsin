<?php
session_start();
include '../lib/util.php';
include '../lib/util_admin.php';

$config = parse_ini_file("../config/config.ini");

// check if admin is logged in
adminCheck();

?><!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Turner Inner | Create New Assignment ?></title>
  <meta name="description" content="Admin | Turn CS In">
  <meta name="author" content="Mr. May">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="../css/normalize.css">
  <link rel="stylesheet" href="../css/skeleton.css">
  <link rel="stylesheet" href="../css/styles.css?v=1.0">

  <link rel="icon" type="image/png" href="../img/favicon.png">

</head>

<body>
    <a href="../logout.php">logout</a>

  <div class="container">
    <div class="row">
      <div class="one-half" style="margin-top: 10%">
        <h1>Turner Inner</h1>
        <h4>Create New Assignment</h4>

          <form action="save_create.php" method="POST">

            <label for="slug">Slug</label>
            <input class="u-full-width" type="text" id="slug" name="slug"/>

            <label for="name">Name</label>
            <input class="u-full-width" type="text" id="name" name="name"/>

            <label for="file">Required File Name</label>
            <input class="u-full-width" type="text" id="file" name="file"/>

            <div class="row">
              <div class="six columns">
                <label for="open">Open Date (Y-m-d h:i)</label>
                <input class="u-full-width" type="text" id="open" name="open"/>
              </div>
              <div class="six columns">
                <label for="close">Close Date (Y-m-d h:i)</label>
                <input class="u-full-width" type="text" id="close" name="close"/>
              </div>
            </div>


            <button class="button-primary">Create Assignment</button>
            <a href="<?= url() ?>">Cancel</a>
          </form>

        </div>
      </div>
    </div>
  <script src="../js/admin.js"></script>
</body>
</html>
