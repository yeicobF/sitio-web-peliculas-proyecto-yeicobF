<?php

$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/directory-path.php";
include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/config.php";

include_once $path
  . LAYOUTS
  . "base-html-head.php";

$src_folder = DirectoryPath::getPathWithLocalhost(SRC);
$views_folder = DirectoryPath::getPathWithLocalhost(VIEWS);
$img_folder =
  DirectoryPath::getPathWithLocalhost(ASSETS)
  . "img/";
$css_folder = DirectoryPath::getPathWithLocalhost(CSS);

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

  <!-- SCRIPTS -->
  <script defer src="<?php echo DirectoryPath::getPathWithLocalhost(SRC) . "js/navbar.js"; ?>" type="module"></script>


  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body class="body-container">
  <?php
  include $path . LAYOUTS . "navbar.php";
  ?>

  <div class="fill-height-flex container-fluid container-xl">
    <form class="form__container row" enctype="multipart/form-data" action="" method="POST" autocomplete="off">
      <h1 class="col-12 form__movie__title">Agregar película</h1>

      <input type="hidden" name="_method" value="POST">

      <section class="col-12">
        <label for="spanish-movie-name">Nombre en español</label>
        <div class="form__input__container">
          <input autocomplete="off" type="text" name="spanish-movie-name" placeholder="Ingresa el nombre en español">
          <i class="form__input__icon fas fa-film"></i>
        </div>
      </section>
      <section class="col-12">
        <label for="original-movie-name">Nombre original</label>
        <div class="form__input__container">
          <input autocomplete="off" type="text" name="original-movie-name" placeholder="Ingresa el nombre original">
          <i class="form__input__icon fas fa-film"></i>
        </div>
      </section>
      <div class="row form__movie__cast">

        <section class="col-12 col-lg-4">
          <label for="directors">Directores</label>
          <div class="form__input__container">
            <input autocomplete="off" type="text" name="directors" placeholder="Ingresa el nombre en español">
            <i class="form__input__icon fas fa-user"></i>
          </div>
        </section>

        <section class="col-12 col-lg-4">
          <label for="actors">Actores</label>
          <div class="form__input__container">
            <input autocomplete="off" type="text" name="actors" placeholder="Ingresa el nombre en español">
            <i class="form__input__icon fas fa-user"></i>
          </div>
        </section>
        <section class="col-12 col-lg-4">
          <label for="genres">Géneros</label>
          <div class="form__input__container">
            <input autocomplete="off" type="text" name="genres" placeholder="Ingresa el nombre en español">
            <i class="form__input__icon fas fa-info"></i>
          </div>
        </section>
      </div>
      <div class="form__movie__details row">

        <section class="col-12 col-md-3">
          <label for="upload-picture">Póster</label>
          <input class="form__input__picture" type="file" name="upload-picture" class="form-control">
        </section>
        <section class="col-12 col-sm-4 col-md-3">
          <label for="year">Año</label>
          <div class="form__input__container">
            <input autocomplete="off" type="number" min="1800" max="2022" step="1" name="year" placeholder="Año">
            <i class="form__input__icon fas fa-calendar-plus"></i>
          </div>
        </section>
        <section class="col-12 col-sm-4 col-md-3">
          <label for="running-time">Duración</label>
          <div class="form__input__container">
            <input autocomplete="off" type="text" name="running-time" placeholder="Duración">
            <i class="form__input__icon fas fa-clock"></i>
          </div>
        </section>
        <section class="col-12 col-sm-4 col-md-3">
          <label for="age-rating">Clasificación de edad</label>
          <div class="form__input__container">
            <input autocomplete="off" type="text" name="age-rating" placeholder="Clasificación de edad">
            <i class="form__input__icon fas fa-address-card"></i>
          </div>
        </section>
      </div>
      <section class="col-12">
        <label for="synopsis">Sinopsis</label>
        <textarea class="form__text-input" name="synopsis" id="" cols="" rows=""></textarea>
      </section>

      <section class="form__buttons">
        <button title="Cancelar" class="btn form__button" type="submit">
          Cancelar
        </button>
        <button title="Guardar cambios" class="btn form__button" type="submit">
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
