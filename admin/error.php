<?php
include '../lib/util.php';
 ?><!doctype html>

<html lang="en">
<head>
 <meta charset="utf-8">

 <title>Turner Inner</title>
 <meta name="description" content="Turn CS In">
 <meta name="author" content="Mr. May">

 <meta name="viewport" content="width=device-width, initial-scale=1">

 <link rel="stylesheet" href="../css/normalize.css">
 <link rel="stylesheet" href="../css/skeleton.css">

 <link rel="icon" type="image/png" href="img/favicon.png">

</head>

<body>
 <div class="container">
   <div class="row">
     <div class="one-half" style="margin-top: 10%">
       <h1>Turner Inner</h1>

       <h3>Error!</h3>
       <p><?= filter_var($_GET['message'], FILTER_SANITIZE_STRING) ?></p>
       <a href="<?= $_SERVER['HTTP_REFERER'] ?>">Try again</a>

     </div>
   </div>
 </div>
</body>
</html>
