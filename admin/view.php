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
        <h4>View <a href="edit.php?assignment=<?= $slug ?>"><?= getArrayValue($info, 'name', 'Unnamed Assignment') ?></a></h4>

        <table class="u-full-width">
          <thead>
            <tr>
              <th>Slug</th>
              <th>Turned In</th>
              <th>File</th>
              <th>Open</th>
              <th>Close</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
              <tr>
                <td><?= $slug ?></td>
                <td><?= countTurnedInAssignments($slug) ?></td>
                <td><?= getArrayValue($info, 'file', '*') ?></td>
                <td><?= getArrayValue($info, 'open', 'Whenever') ?></td>
                <td><?= getArrayValue($info, 'close', 'Never') ?></td>
                <td>
                  <a href="download.php?assignment=<?= $slug ?>">Download</a>
                </td>
              </tr>
          </tbody>
        </table>

        <a href="<?= url() ?>">Return to admin</a>

        <?php if(countTurnedInAssignments($slug) > 0): ?>
          <h2>Turned in</h2>
          <ul>
            <?php foreach(getTurnedInAssignments($slug) AS $student): ?>
              <li><?= $student ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        </div>
      </div>
    </div>
</body>
</html>
