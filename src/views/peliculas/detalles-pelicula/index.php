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

if (!Controller::getKeyExist("id")) {
  Controller::redirectView(error: "No se especificó un ID.");
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
          <input type="submit" value="Publicar" class="btn btn-info">
        </form>

        <!-- Comentarios ya publicados. -->
        <article class="comments__posted">
          <figure class="comments__details">
            <img src="<?php echo IMG_FOLDER; ?>../avatar/1.jpg" alt="Username" class="circle-avatar">
            <figcaption class="comments__details__info">
              <h3 class="comments__details__title">Username Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic ad numquam natus quaerat maiores vitae voluptatem. Voluptates perspiciatis sequi delectus, illum adipisci earum modi, error, ad totam eos praesentium hic?</h3>
              <time class="comments__details__time-ago" datetime="PT3H">Hace 3 horas</time>
            </figcaption>
          </figure>
          <main class="comments__text">
            <p>
              Lorem ipsum, dolor sit amet consectetur adipisicing elit. Repellendus accusantium officiis deserunt cum temporibus iure in sed alias a corporis?
            </p>
          </main>
          <footer class="comments__interaction">
            <form name="comment-likes" class="" action="" method="post">
              <button class="comments__interaction__button" type="button"><i class="fas fa-thumbs-up"></i></button>
              <data value="2">2</data>
            </form>
            <form name="comment-dislikes" class="" action="" method="post">
              <button class="comments__interaction__button selected" type="button"><i class="fas fa-thumbs-down"></i></button>
              <data value="4">4</data>
            </form>
          </footer>
        </article>
        <!-- Comentarios ya publicados. -->
        <article class="comments__posted">
          <figure class="comments__details">
            <img src="<?php echo IMG_FOLDER; ?>../avatar/1.jpg" alt="Username" class="circle-avatar">
            <figcaption class="comments__details__info">
              <h3 class="comments__details__title">Username Lorem ipsum dolor sit amet consectetur adipisicing elit. Hic ad numquam natus quaerat maiores vitae voluptatem. Voluptates perspiciatis sequi delectus, illum adipisci earum modi, error, ad totam eos praesentium hic?</h3>
              <time class="comments__details__time-ago" datetime="PT3H">Hace 3 horas</time>
            </figcaption>
          </figure>
          <main class="comments__text">
            <p>
              Lorem ipsum, dolor sit amet consectetur adipisicing elit. Repellendus accusantium officiis deserunt cum temporibus iure in sed alias a corporis?
            </p>
          </main>
          <footer class="comments__interaction">
            <form name="comment-likes" class="" action="" method="post">
              <button class="comments__interaction__button" type="button"><i class="fas fa-thumbs-up"></i></button>
              <data value="2">2</data>
            </form>
            <form name="comment-dislikes" class="" action="" method="post">
              <button class="comments__interaction__button selected" type="button"><i class="fas fa-thumbs-down"></i></button>
              <data value="4">4</data>
            </form>
          </footer>
        </article>

        <?php
        include $path . VIEWS . "components/posted-comment.php";
        ?>
      </section>
      <aside class="best-movies-container col-12 col-md-4">
        <h2 class="best-movies-container__title">Mejores Películas</h2>
        <!-- Póster de películas. -->
        <figure class="row movie-poster   col-12">
          <!--
            Contenedor para que el año tenga posición relativa al póster y
            no a todo el contenedor.
          -->
          <div class="movie-poster__year-image col-6">
            <a rel="noopener noreferrer" href="<?php echo VIEWS_FOLDER; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
              <img class="movie-poster__img" src="<?php echo FOLDERS_WITH_LOCALHOST["ASSETS"]; ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
            </a>
            <time datetime="2021" class="movie-poster__year">2021</time>
          </div>
          <figcaption class="col-6">
            <ul class="best-movies__details-list">
              <li class="movie-poster__title">Spiderman: No Way Home</li>
              <li><data value="4.5">4.5/5</data></li>
              <li><time datetime="PT2H28M">2h 38m</time></li>
            </ul>
          </figcaption>
        </figure>
        <figure class="row movie-poster   col-12">
          <!--
            Contenedor para que el año tenga posición relativa al póster y
            no a todo el contenedor.
          -->
          <div class="movie-poster__year-image col-6">
            <a rel="noopener noreferrer" href="<?php echo VIEWS_FOLDER; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
              <img class="movie-poster__img" src="<?php echo FOLDERS_WITH_LOCALHOST["ASSETS"]; ?>img/movie-posters/friday-the-13th/1.jpg" alt="Friday the 13th">
            </a>
            <time datetime="2021" class="movie-poster__year">1980</time>
          </div>
          <figcaption class="col-6">
            <ul class="best-movies__details-list">
              <li class="movie-poster__title">Friday the 13th</li>
              <li><data value="4">4/5</data></li>
              <li><time datetime="PT2H35M">1h 35m</time></li>
            </ul>
          </figcaption>
        </figure>
        <!-- Póster de películas. -->
        <figure class="row movie-poster   col-12">
          <!--
            Contenedor para que el año tenga posición relativa al póster y
            no a todo el contenedor.
          -->
          <div class="movie-poster__year-image col-6">
            <a rel="noopener noreferrer" href="<?php echo VIEWS_FOLDER; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
              <img class="movie-poster__img" src="<?php echo FOLDERS_WITH_LOCALHOST["ASSETS"]; ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
            </a>
            <time datetime="2021" class="movie-poster__year">2021</time>
          </div>
          <figcaption class="col-6">
            <ul class="best-movies__details-list">
              <li class="movie-poster__title">Spiderman: No Way Home</li>
              <li><data value="4.5">4.5/5</data></li>
              <li><time datetime="PT2H28M">2h 38m</time></li>
            </ul>
          </figcaption>
        </figure>
        <figure class="row movie-poster   col-12">
          <!--
            Contenedor para que el año tenga posición relativa al póster y
            no a todo el contenedor.
          -->
          <div class="movie-poster__year-image col-6">
            <a rel="noopener noreferrer" href="<?php echo VIEWS_FOLDER; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
              <img class="movie-poster__img" src="<?php echo FOLDERS_WITH_LOCALHOST["ASSETS"]; ?>img/movie-posters/friday-the-13th/1.jpg" alt="Friday the 13th">
            </a>
            <time datetime="2021" class="movie-poster__year">1980</time>
          </div>
          <figcaption class="col-6">
            <ul class="best-movies__details-list">
              <li class="movie-poster__title">Friday the 13th</li>
              <li><data value="4">4/5</data></li>
              <li><time datetime="PT2H35M">1h 35m</time></li>
            </ul>
          </figcaption>
        </figure>
      </aside>
    </div>
  </div>




  <?php
  include $path . LAYOUTS . "footer.php";
  ?>
</body>

</html>
