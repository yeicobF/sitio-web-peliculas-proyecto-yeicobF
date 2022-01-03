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
  <link rel="stylesheet" href="<?php echo $css_folder; ?>config.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>components/components.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>menu/menu.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>utilities/utilities.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>transformations/rotate.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>movies/movies.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>movies/movie-details.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>movies/comments.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>footer/footer.css">

  <link rel="stylesheet" href="<?php echo $css_folder; ?>form/form.css">

  <link rel="stylesheet" href="<?php echo $css_folder; ?>editar-perfil/editar-perfil.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>editar-perfil/detalles-perfil.css">

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
        <!-- <img src="<?php echo $img_folder; ?>../avatar/1.jpg" alt="Username" class="circle-avatar"> -->


        <div class="col-12 col-sm-6 col-md-4">
          <?php
          Usuario::getFotoPerfil();
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
          <a href="<?php echo $url_page["editar-perfil"]; ?>" class="btn btn-primary btn-fit-content">
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