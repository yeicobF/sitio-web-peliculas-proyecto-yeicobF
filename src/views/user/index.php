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
    <main class="movie-details__container row">
      <figure class="profile-details__figure col-12 col-sm-4">
        <!-- <figcaption>
          <h2 class="edit-profile__title">Nombre del usuario</h2>
        </figcaption> -->
        <!-- <img src="<?php echo $img_folder; ?>../avatar/1.jpg" alt="Username" class="circle-avatar"> -->

        <?php
        Usuario::getFotoPerfil();
        ?>
        <figcaption>
          <h2 class="profile-details__username edit-profile__username"><?php echo $username; ?></h2>
        </figcaption>
      </figure>
      <section class="movie-details col-12 col-sm-8">
        <!-- Título original y en español. -->
        <header class="movie-details__title">
          <h1>Nombre español</h1>
          <h2>Nombre original</h2>
        </header>
        <section class="movie-details__info">
          <time datetime="2021" class="movie-details__year">2021</time>
          <!-- Calificaciones de la película. -->
          <data value="4.5" class="movie-details__user-rating">4.5/5</data>
          <time datetime="PT2H28M">2h 38m</time>
          <data value="18" class="movie-details__age-rating">
            Clasificación: 18+
          </data>
        </section>
        <p class="movie-details__synopsis">
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sint iste, ratione consequatur aliquid dolores cupiditate facere molestiae alias officia nisi totam modi ullam. Praesentium adipisci expedita iusto ullam deserunt illo?

          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sint iste, ratione consequatur aliquid dolores cupiditate facere molestiae alias officia nisi totam modi ullam. Praesentium adipisci expedita iusto ullam deserunt illo?
        </p>
        <ul class="movie-details__cast">
          <li class="movie-details__cast__type">
            <h3 class="movie-details__cast__type__title">
              Director/es:
            </h3>
            <ul>
              <li class="movie-details__cast__member">Lorem, ipsum dolor.</li>
              <li class="movie-details__cast__member">Lorem, ipsum dolor.</li>
              <li class="movie-details__cast__member">Lorem, ipsum dolor.</li>
              <li class="movie-details__cast__member">Lorem, ipsum.</li>
            </ul>
          </li>
          <li class="movie-details__cast__type">
            <h3 class="movie-details__cast__type__title">
              Actor/es:
            </h3>
            <ul>
              <li class="movie-details__cast__member">Lorem, ipsum dolor.</li>
              <li class="movie-details__cast__member">Lorem, ipsum.</li>
            </ul>
          </li>
          <li class="movie-details__cast__type">
            <h3 class="movie-details__cast__type__title">
              Géneros:
            </h3>
            <ul>
              <li class="movie-details__cast__member">Comedia</li>
              <li class="movie-details__cast__member">Acción</li>
            </ul>
          </li>
        </ul>
      </section>
    </main>
  </div>

  <?php
  include $path . LAYOUTS . "footer.php";
  ?>
</body>
