<?php

$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/directory-path.php";

include_once $path
  . DirectoryPath::LAYOUTS
  . "base-html-head.php";

$src_folder = DirectoryPath::getPathWithLocalhost(DirectoryPath::SRC);
$views_folder = DirectoryPath::getPathWithLocalhost(DirectoryPath::VIEWS);
$img_folder =
  DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS)
  . "img/";

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
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/config.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/components/components.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/menu/menu.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/utilities/utilities.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/transformations/rotate.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/movies/movies.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/movies/movie-details.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/footer/footer.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/form/form.css">

  <!-- SCRIPTS -->
  <script defer src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::SRC) . "js/navbar.js"; ?>" type="module"></script>


  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body class="body-container">
  <?php
  include $path . DirectoryPath::LAYOUTS . "navbar.php";
  ?>

  <div class="fill-height-flex container-fluid container-lg">
    <form class="form__container row" action="" method="POST">
      <input type="hidden" name="_method" value="POST">

      <section class="col-12">
        <label for="spanish-movie-name">Nombre en español</label>
        <div class="form__input__container">
          <input autocomplete="off" type="text" name="spanish-movie-name" id="username" placeholder="Ingresa el nombre en español">
          <i class="form__input__icon fas fa-film"></i>
        </div>
      </section>
      <section class="col-12 col-lg-4">
        <label for="original-movie-name">Directores</label>
        <div class="form__input__container">
          <input autocomplete="off" type="text" name="original-movie-name" id="username" placeholder="Ingresa el nombre en español">
          <i class="form__input__icon fas fa-film"></i>
        </div>
      </section>
      
      <section class="col-12 col-lg-4">
        <label for="original-movie-name">Actores</label>
        <div class="form__input__container">
          <input autocomplete="off" type="text" name="original-movie-name" id="username" placeholder="Ingresa el nombre en español">
          <i class="form__input__icon fas fa-film"></i>
        </div>
      </section>
      <section class="col-12 col-lg-4">
        <label for="original-movie-name">Géneros</label>
        <div class="form__input__container">
          <input autocomplete="off" type="text" name="original-movie-name" id="username" placeholder="Ingresa el nombre en español">
          <i class="form__input__icon fas fa-film"></i>
        </div>
      </section>

      <section class="col-6 col-sm-4">
        <label for="upload-picture">Póster</label>
        <input class="form__input__picture" type="file" name="upload-picture" class="form-control">
      </section>
    </form>
  </div>




  <?php
  include $path . DirectoryPath::LAYOUTS . "footer.php";
  ?>
</body>

</html>
