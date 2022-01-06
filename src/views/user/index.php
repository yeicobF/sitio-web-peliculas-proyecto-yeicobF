<?php

use Controllers\Login;
use Controllers\Usuario;

$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/config.php";

include_once
  FOLDERS_WITH_DOCUMENT_ROOT["LAYOUTS"]
  . "base-html-head.php";

include_once
  FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
  . "login.php";

include_once
  FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
  . "usuario.php";

Login::redirectIfUserNotLoggedIn("login/index.php");

$username = $_SESSION["username"];

$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Detalles de perfil - {$username}",
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
    <main class="profile-details">
      <h1 class="profile-details__title">
        Detalles de perfil
      </h1>
      <figure class="profile-details__figure row">
        <!-- <figcaption>
          <h2 class="edit-profile__title">Nombre del usuario</h2>
        </figcaption> -->
        <!-- <img src="<?php echo IMG_FOLDER; ?>../avatar/1.jpg" alt="Username" class="circle-avatar"> -->


        <div class="col-12 col-sm-6 col-md-4">
          <?php
          Usuario::renderFotoPerfil(
            usuario_id: $_SESSION["id"],
            username: $_SESSION["username"],
            foto_perfil: $_SESSION["foto_perfil"]
          );
          ?>
        </div>
        <figcaption class="profile-details__figcaption col-12 col-sm-6  col-md-8">
          <header class="profile-details__figcaption__header">
            <h2>
              <?php
              echo Usuario::getNombres() . " " . Usuario::getApellidos();
              ?>
            </h2>
            <h3 class="profile-details__username edit-profile__username">
              <?php echo $username; ?>
            </h3>
          </header>
          <a href="<?php echo URL_PAGE["editar-perfil"]; ?>" class="btn btn-primary btn-fit-content">
            Editar perfil
          </a>
        </figcaption>
      </figure>

    </main>
  </div>

  <?php
  include $path . LAYOUTS . "footer.php";
  ?>
</body>
