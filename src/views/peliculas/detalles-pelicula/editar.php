<?php

use Libs\Controller;
use Controllers\Login;
use Controllers\Usuario;
use Controllers\Pelicula;

include_once __DIR__ . "/../../../config/config.php";

include_once FOLDERS_WITH_DOCUMENT_ROOT["LAYOUTS"]
  . "base-html-head.php";

include_once
  FOLDERS_WITH_DOCUMENT_ROOT["LIBS"]
  . "controller.php";
include_once
  FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
  . "login.php";
include_once
  FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
  . "usuario.php";
include_once
  FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
  . "pelicula.php";

Login::redirectIfUserNotLoggedIn("login/index.php");
Usuario::redirectIfNotAdmin();

if (Controller::redirectIfIdNotFound(view_path: "peliculas/index.php")) {
  return;
}

$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Editar película - " . Pelicula::$current_movie->nombre_original,
  _includeOwnFramework: true,
  _includeFontAwesome: true
);
?>

<head>
  <?php
  echo $baseHtmlHead->getHtmlBaseHead();
  ?>

  <!-- CSS -->
  <!-- CSS Propios -->
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>config.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>components/components.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>menu/menu.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>utilities/utilities.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>transformations/rotate.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>movies/movies.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>movies/movie-details.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>footer/footer.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>form/form.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>form/add-movie.css">

  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body class="body-container">
  <?php
  include $path . LAYOUTS . "navbar.php";
  ?>

  <div class="container-fluid container-lg fill-height-flex">

  </div>

  <?php
  include $path . LAYOUTS . "footer.php";
  ?>
</body>

</html>
