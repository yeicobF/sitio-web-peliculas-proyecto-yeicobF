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

    <!-- Los datos del formulario serán llenados con XMLHttpRequest. -->
    <form class="form__container row" enctype="multipart/form-data" action="<?php echo $agregar_pelicula_action; ?>" method="POST" autocomplete="off">
      <h1 class="col-12 form__movie__title">Editar detalles de película</h1>
      <h2 id="nombre-original-title"></h2>
      <h3 id="nombre-es-mx-title"></h3>
      <input type="hidden" name="_method" value="POST">

      <section class="col-12">
        <label for="nombre_original">Nombre original</label>
        <div class="form__input__container">
          <input required autocomplete="off" type="text" name="nombre_original" placeholder="Ingresa el nombre original">
          <i class="form__input__icon fas fa-film"></i>
        </div>
      </section>

      <section class="col-12">
        <label for="nombre_es_mx">Nombre en español</label>
        <div class="form__input__container">
          <input autocomplete="off" type="text" name="nombre_es_mx" placeholder="Ingresa el nombre en español">
          <i class="form__input__icon fas fa-film"></i>
        </div>
      </section>

      <div class="row form__movie__cast">

        <section class="col-12 col-lg-4">
          <label for="directores">Directores</label>
          <div class="form__input__container">
            <input required autocomplete="off" type="text" name="directores" placeholder="Ingresa a los directores">
            <i class="form__input__icon fas fa-user"></i>
          </div>
        </section>

        <section class="col-12 col-lg-4">
          <label for="actores">Actores</label>
          <div class="form__input__container">
            <input required autocomplete="off" type="text" name="actores" placeholder="Ingresa a los actores">
            <i class="form__input__icon fas fa-user"></i>
          </div>
        </section>
        <section class="col-12 col-lg-4">
          <label for="generos">Géneros</label>
          <div class="form__input__container">
            <input required autocomplete="off" type="text" name="generos" placeholder="Ingresa los géneros">
            <i class="form__input__icon fas fa-info"></i>
          </div>
        </section>
      </div>
      <div class="form__movie__details row">

        <section class="col-12 col-sm-4 col-md-6 col-xl-12">
          <label for="poster">Póster</label>
          <input class="form__input__picture" type="file" name="poster" class="form-control" placeholder="Póster de la película">
        </section>
        <section class="col-12 col-sm-4 col-md-6 col-xl-4">
          <label for="restriccion_edad">Clasificación de edad</label>
          <div class="form__input__container">
            <input required autocomplete="off" type="text" name="restriccion_edad" placeholder="Clasificación de edad">
            <i class="form__input__icon fas fa-address-card"></i>
          </div>
        </section>
        <section class="col-12 col-sm-4 col-md-6 col-xl-4">
          <label for="release_year">Año</label>
          <div class="form__input__container">
            <input required autocomplete="off" type="number" min="1800" max="2022" step="1" name="release_year" placeholder="Año">
            <i class="form__input__icon fas fa-calendar-plus"></i>
          </div>
        </section>
        <section class="col-12 col-md-6 col-xl-4">
          <label for="duracion">Duración</label>
          <div name="duracion" class="add-movie__duracion form__input__container">
            <label for="horas">h: </label>
            <input required value=0 autocomplete="off" type="number" name="horas" placeholder="Horas" min="0" max="100">
            <span class="vertical-line"></span>

            <label for="minutos">m: </label>
            <input required value=0 autocomplete="off" type="number" name="minutos" placeholder="Minutos" min="0" max="59">
            <span class="vertical-line"></span>

            <label for="segundos">s: </label>
            <input required value=0 autocomplete="off" type="number" name="segundos" placeholder="Segundos" min="0" max="59">
            <i class="form__input__icon fas fa-clock"></i>
          </div>
        </section>
      </div>
      <section class="col-12">
        <label for="resumen_trama">Sinopsis</label>
        <textarea class="form__text-input" name="resumen_trama" id="" cols="" rows="" placeholder="Ingresa la sinopsis de la trama"></textarea>
      </section>

      <section class="form__buttons add-movie__buttons">
        <a href="<?php echo VIEWS_FOLDER . "index.php"; ?>" title="Cancelar" class="btn btn-danger form__button" type="submit">
          Cancelar
        </a>
        <button title="Guardar cambios" class="btn btn-success form__button" type="submit">
          Guardar cambios
        </button>
      </section>
    </form>

  </div>

  <?php
  include $path . LAYOUTS . "footer.php";
  ?>
</body>

</html>
