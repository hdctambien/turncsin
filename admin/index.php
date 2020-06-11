<?php
session_start();
include '../lib/util.php';
include '../lib/util_admin.php';

$config = parse_ini_file("../config/config.ini");

// check if admin is logged in
adminCheck();

//get list of assignments
$assignments = getAssignments();
?><!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Turner Inner</title>
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

        <a href="create.php">Create new assignment</a>

        <table class="u-full-width">
          <thead>
            <tr>
              <th>Assignment</th>
              <th>Turned In</th>
              <th>File</th>
              <th>Open</th>
              <th>Close</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($assignments AS $assignment): ?>
              <?php
                $slug = getArrayValue($assignment, 'slug', 'unknown');
                $params = "assignment=${slug}";
              ?>
              <tr>
                <td><a href="edit.php?<?= $params ?>"><?= getArrayValue($assignment, 'name', $slug) ?></a></td>
                <td><?= countTurnedInAssignments($slug) ?></td>
                <td><?= getArrayValue($assignment, 'file', '*') ?></td>
                <td><?= getArrayValue($assignment, 'open', 'Whenever') ?></td>
                <td><?= getArrayValue($assignment, 'close', 'Never') ?></td>
                <td>
                  <a href="download.php?<?= $params ?>">Download</a>
                  <a href="view.php?<?= $params ?>">View</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        </div>
      </div>
    </div>
  <script src="../js/admin.js"></script>
</body>
</html>
