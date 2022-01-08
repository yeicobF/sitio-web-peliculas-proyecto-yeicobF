<?php

use Usuario as GlobalUsuario;
use Controllers\Usuario;
use Libs\Controller;

$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/config.php";

include_once
  FOLDERS_WITH_DOCUMENT_ROOT["LAYOUTS"]
  . "base-html-head.php";

include_once
  FOLDERS_WITH_DOCUMENT_ROOT["LIBS"]
  . "controller.php";
include_once
  FOLDERS_WITH_DOCUMENT_ROOT["MODELS"]
  . "usuario.php";

Controller::startSession();
Model::initDbConnection();
$db_user = GlobalUsuario::getById($_GET["id"]);

if ($db_user === null) {
  Controller::redirectView(
    view_path: "index.php",
    error: "No se encontró el usuario."
  );
  return false;
}

include_once
  FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
  . "usuario.php";

$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Detalles de perfil - {$db_user["username"]}",
  _includeOwnFramework: true,
  _includeFontAwesome: true
);
?>

<!DOCTYPE html>
<html lang="es">

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
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>movies/comments.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>footer/footer.css">

  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>form/form.css">

  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>editar-perfil/editar-perfil.css">
  <link rel="stylesheet" href="<?php echo CSS_FOLDER; ?>editar-perfil/detalles-perfil.css">

  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body class="body-container">
  <?php
  include $path . LAYOUTS . "navbar.php";
  ?>

  <div class="fill-height-flex container-fluid container-lg">
    <?php
    if (!Usuario::getRequest()) {
      return;
    }
    ?>
  </div>

  <?php
  include $path . LAYOUTS . "footer.php";
  ?>
</body>
