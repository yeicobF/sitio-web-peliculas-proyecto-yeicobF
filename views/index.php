<?php
include_once "../views/layouts/base-html-head.php";
$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Inicio",
  _includeOwnFramework: true,
  _includeFontAwesome: true
);
// var_dump($_SERVER);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  echo $baseHtmlHead->getHtmlBaseHead();
  ?>

  <!-- CSS -->
  <!-- CSS Propios -->
  <link rel="stylesheet" href="css/config.css">
  <link rel="stylesheet" href="css/components/components.css">
  <link rel="stylesheet" href="css/menu/menu.css">
  <link rel="stylesheet" href="css/utilities/utilities.css">
  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body>
  <?php
  include "../views/layouts/navbar.php";
  ?>

</body>

</html>
