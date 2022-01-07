<?php

use Libs\Controller;

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
  . "usuario.php";

if (Controller::redirectIfIdNotFound(view_path: "peliculas/index.php")) {
  return;
}


$id_pelicula = $_GET["id"];
$nombre_pelicula;


$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Detalles de película",
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

      <?php
      include_once
        FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
        . "pelicula.php";
      ?>

    </main>
    <div class="row">
      <section class="comments__container col-12 col-md-8">
        <h2>Comentarios</h2>
        <form action="" method="post" class="comments__form">
          <!-- Hay que definir el método a utilizar con un input hidden. -->
          <input type="hidden" name="_method" value="POST">

          <textarea placeholder="Ingresa un comentario" name="nuevo-comentario" id="" rows="5"></textarea>
          <footer class="comments__form__buttons">
            <img src="<?php echo IMG_FOLDER; ?>../avatar/1.jpg" alt="Username" class="circle-avatar">

            <input type="submit" value="Publicar" class="comments__form__btn btn btn-primary">
          </footer>
        </form>

        <!-- Comentarios ya publicados. -->
        <?php
        include_once
          FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"]
          . "comentario-pelicula.php";
        ?>

        <?php
        include $path . VIEWS . "components/posted-comment.php";
        ?>
      </section>
      <aside class="best-movies-container col-12 col-md-4">
        <?php
        require_once FOLDERS_WITH_DOCUMENT_ROOT["LAYOUTS"] . "movies/mejores-peliculas.php";
        ?>
      </aside>
    </div>
  </div>




  <?php
  include $path . LAYOUTS . "footer.php";
  ?>
</body>

</html>
