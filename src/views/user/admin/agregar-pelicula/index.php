<?php

use Libs\Controller;
use Controllers\Login;
use Controllers\Usuario;

$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/config.php";

include_once $path
  . LAYOUTS
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

Login::redirectIfUserNotLoggedIn("login/index.php");
Usuario::redirectIfNotAdmin();

$agregar_pelicula_action = "{$controllers_folder}pelicula.php";

$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Agregar película",
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
  <link rel="stylesheet" href="<?php echo $css_folder; ?>footer/footer.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>form/form.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>form/add-movie.css">




  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body class="body-container">
  <?php
  include $path . LAYOUTS . "navbar.php";
  ?>

  <div class="fill-height-flex container-fluid container-xl">
    <form class="form__container row" enctype="multipart/form-data" action="<?php echo $agregar_pelicula_action; ?>" method="POST" autocomplete="off">
      <h1 class="col-12 form__movie__title">Agregar película</h1>
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
            <input required autocomplete="off" type="text" name="directores" placeholder="Ingresa el nombre en español">
            <i class="form__input__icon fas fa-user"></i>
          </div>
        </section>

        <section class="col-12 col-lg-4">
          <label for="actores">Actores</label>
          <div class="form__input__container">
            <input required autocomplete="off" type="text" name="actores" placeholder="Ingresa el nombre en español">
            <i class="form__input__icon fas fa-user"></i>
          </div>
        </section>
        <section class="col-12 col-lg-4">
          <label for="generos">Géneros</label>
          <div class="form__input__container">
            <input required autocomplete="off" type="text" name="generos" placeholder="Ingresa el nombre en español">
            <i class="form__input__icon fas fa-info"></i>
          </div>
        </section>
      </div>
      <div class="form__movie__details row">

        <section class="col-12 col-sm-4 col-md-6 col-xl-12">
          <label for="poster">Póster</label>
          <input class="form__input__picture" type="file" name="poster" class="form-control">
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
            <input required autocomplete="off" type="number" name="horas" placeholder="Horas" min="0" max="100">
            <span class="vertical-line"></span>

            <label for="minutos">m: </label>
            <input required autocomplete="off" type="number" name="minutos" placeholder="Minutos" min="0" max="59">
            <span class="vertical-line"></span>

            <label for="segundos">s: </label>
            <input required autocomplete="off" type="number" name="segundos" placeholder="Segundos" min="0" max="59">
            <i class="form__input__icon fas fa-clock"></i>
          </div>
        </section>
      </div>
      <section class="col-12">
        <label for="resumen_trama">Sinopsis</label>
        <textarea class="form__text-input" name="resumen_trama" id="" cols="" rows=""></textarea>
      </section>

      <section class="form__buttons add-movie__buttons">
        <a href="<?php echo "{$views_folder}index.php"; ?>" title="Cancelar" class="btn btn-danger form__button" type="submit">
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
