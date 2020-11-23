<?php include "functions/test112320/includes/autoloader.php"; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="style/timeline/test11162021.css">

    <title>Document</title>
</head>
<body>

<?php include "elements/menu.php"?>

<div class="data-task">

<?php
$doneJobe = new DoneJobe('https://gkb81.ru/sovety/');
$doneJobe->putSomeData();
?>

</div>


<script type="text/javascript" src="ajax/jquery351.min.js"></script>

</body>
</html>