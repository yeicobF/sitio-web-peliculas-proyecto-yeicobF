<?php
include_once __DIR__ . "/" . "../config/directory-path.php";
include_once "../views/layouts/base-html-head.php";

$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Inicio",
  _includeOwnFramework: true,
  _includeFontAwesome: true
);
// var_dump($_SERVER);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  echo $baseHtmlHead->getHtmlBaseHead();
  ?>

  <!-- CSS -->
  <!-- CSS Propios -->
  <link rel="stylesheet" href="../css/config.css">
  <link rel="stylesheet" href="../css/components/components.css">
  <link rel="stylesheet" href="../css/menu/menu.css">
  <link rel="stylesheet" href="../css/utilities/utilities.css">
  <link rel="stylesheet" href="../css/transformations/rotate.css">
  <link rel="stylesheet" href="../css/movies/movies.css">

  <!-- SCRIPTS -->
  <script defer type="module">
    import {
      activateToggleRotate180
    } from "../js/toggle/toggle-rotate.js";

    // Activamos la rotación de los elementos del navbar con la clase rotate.
    activateToggleRotate180(".navbar-nav .nav-link", ".rotate");
  </script>
  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body>
  <?php
  include "../views/layouts/navbar.php";
  ?>

  <div class="container-fluid container-lg">
    <div class="row">

      <!-- Contenedor de películas. -->
      <div class="movies-container border border-primary col-12 col-sm-8">
        <h2>Películas</h2>
        <div class="row movies__row border border-secondary">
          <!-- Póster de películas. -->
          <div class="movie-poster border border-secondary col-6 col-sm-4">
            <figure class="">
              <!-- 
                Contenedor para que el año tenga posición relativa al póster y 
                no a todo el contenedor. 
              -->
              <div class="movie-poster__year-image">
                <a href="<?php echo DirectoryPath::VIEWS_URL; ?>peliculas/detalles-pelicula.php?id=id_pelicula" class="">
                  <img class="movie-poster__img" src="<?php echo DirectoryPath::IMG_URL; ?>movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
                </a>
                <time datetime="2021" class="movie-poster__year">2021</time>
              </div>
              <figcaption class="movie-poster__title">Spiderman: No Way Home</figcaption>
            </figure>
          </div>

          <!-- Póster de películas. -->
          <div class="movie-poster border border-secondary col-6 col-sm-4">
            <figure class="">
              <div class="movie-poster__year-image">
                <a href="<?php echo DirectoryPath::VIEWS_URL; ?>peliculas/detalles-pelicula.php?id=id_pelicula" class="">
                  <img class="movie-poster__img" src="<?php echo DirectoryPath::IMG_URL; ?>movie-posters/friday-the-13th/1.jpg" alt="Friday the 13th">
                </a>
                <time datetime="2021" class="movie-poster__year">1980</time>
              </div>
              <figcaption class="movie-poster__title">Friday the 13th</figcaption>
            </figure>
          </div>

          <!-- Póster de películas. -->
          <div class="movie-poster border border-secondary col-6 col-sm-4">
            <figure class="">
              <div class="movie-poster__year-image">
                <a href="<?php echo DirectoryPath::VIEWS_URL; ?>peliculas/detalles-pelicula.php?id=id_pelicula" class="">
                  <img class="movie-poster__img" src="<?php echo DirectoryPath::IMG_URL; ?>movie-posters/avengers-endgame/1.jpg" alt="Avengers: Endgame">
                </a>
                <time datetime="2021" class="movie-poster__year">2019</time>
              </div>
              <figcaption class="movie-poster__title">Avengers: Endgame</figcaption>
            </figure>
          </div>

          <!-- Póster de películas. -->
          <div class="movie-poster border border-secondary col-6 col-sm-4">
            <figure class="">
              <div class="movie-poster__year-image">
                <a href="<?php echo DirectoryPath::VIEWS_URL; ?>peliculas/detalles-pelicula.php?id=id_pelicula" class="">
                  <img class="movie-poster__img" src="<?php echo DirectoryPath::IMG_URL; ?>movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
                </a>
                <time datetime="2021" class="movie-poster__year">2021</time>
              </div>
              <figcaption class="movie-poster__title">Spiderman: No Way Home</figcaption>
            </figure>
          </div>

          <!-- Póster de películas. -->
          <div class="movie-poster border border-secondary col-6 col-sm-4">
            <figure class="">
              <div class="movie-poster__year-image">
                <a href="<?php echo DirectoryPath::VIEWS_URL; ?>peliculas/detalles-pelicula.php?id=id_pelicula" class="">
                  <img class="movie-poster__img" src="<?php echo DirectoryPath::IMG_URL; ?>movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
                </a>
                <time datetime="2021" class="movie-poster__year">2021</time>
              </div>
              <figcaption class="movie-poster__title">Spiderman: No Way Home</figcaption>
            </figure>
          </div>


        </div>
      </div>
      <!-- Mejores películas. Es un sidebar. -->
      <div class="best-movies-container border border-primary col-12 col-sm-4">
        <h2>Mejores Películas</h2>
      </div>
    </div>
  </div>

  <footer>

  </footer>
</body>

</html>
