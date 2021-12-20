<?php
// Obtener la raíz del proyecto en el disco duro y no en el URL como en __DIR__.
// https://stackoverflow.com/questions/13369529/full-url-not-working-with-php-include
// $path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once __DIR__ . "/../config/config.php";

include_once DOCUMENT_ROOT
  . VIEWS
  . "layouts/base-html-head.php";

include_once DOCUMENT_ROOT
  . SRC
  . "logs/error-reporting.php";

$baseHtmlHead = new BaseHtmlHead(
  _pageName: "Inicio",
  _includeOwnFramework: true,
  _includeFontAwesome: true
);

// error_log("prueba log");

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
  <link rel="stylesheet" href="<?php echo $css_folder; ?>footer/footer.css">

  <!-- SCRIPTS -->



  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body>
  <?php
  include DOCUMENT_ROOT . LAYOUTS . "navbar.php";
  ?>

  <div class="container-fluid container-lg">
    <div class="row">

      <!-- Contenedor de películas. -->
      <main class="movies-container col-12 col-sm-8">
        <h2 class="movies-container__title">Películas</h2>
        <section class="row">
          <!-- Póster de películas. -->
          <figure class="movie-poster   col-6 col-sm-4">
            <!-- 
              Contenedor para que el año tenga posición relativa al póster y 
              no a todo el contenedor. 
            -->
            <div class="movie-poster__year-image">
              <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
                <img class="movie-poster__img" src="<?php echo $assets_folder; ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
              </a>
              <time datetime="2021" class="movie-poster__year">2021</time>
            </div>
            <figcaption class="movie-poster__title">Spiderman: No Way Home</figcaption>
          </figure>

          <!-- Póster de películas. -->
          <figure class="movie-poster   col-6 col-sm-4">
            <div class="movie-poster__year-image">
              <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
                <img class="movie-poster__img" src="<?php echo $assets_folder; ?>img/movie-posters/friday-the-13th/1.jpg" alt="Friday the 13th">
              </a>
              <time datetime="2021" class="movie-poster__year">1980</time>
            </div>
            <figcaption class="movie-poster__title">Friday the 13th</figcaption>
          </figure>

          <!-- Póster de películas. -->
          <figure class="movie-poster   col-6 col-sm-4">
            <div class="movie-poster__year-image">
              <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
                <img class="movie-poster__img" src="<?php echo $assets_folder; ?>img/movie-posters/avengers-endgame/1.jpg" alt="Avengers: Endgame">
              </a>
              <time datetime="2021" class="movie-poster__year">2019</time>
            </div>
            <figcaption class="movie-poster__title">Avengers: Endgame</figcaption>
          </figure>

          <!-- Póster de películas. -->
          <figure class="movie-poster   col-6 col-sm-4">
            <div class="movie-poster__year-image">
              <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
                <img class="movie-poster__img" src="<?php echo $assets_folder; ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
              </a>
              <time datetime="2021" class="movie-poster__year">2021</time>
            </div>
            <figcaption class="movie-poster__title">Spiderman: No Way Home</figcaption>
          </figure>

          <!-- Póster de películas. -->
          <figure class="movie-poster   col-6 col-sm-4">
            <div class="movie-poster__year-image">
              <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
                <img class="movie-poster__img" src="<?php echo $assets_folder; ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
              </a>
              <time datetime="2021" class="movie-poster__year">2021</time>
            </div>
            <figcaption class="movie-poster__title">Spiderman: No Way Home</figcaption>
          </figure>

        </section>
      </main>
      <!-- Mejores películas. Es un sidebar. -->
      <aside class="best-movies-container   col-12 col-sm-4">
        <h2 class="best-movies-container__title">Mejores Películas</h2>
        <!-- Póster de películas. -->
        <figure class="row movie-poster   col-12">
          <!--
            Contenedor para que el año tenga posición relativa al póster y
            no a todo el contenedor.
          -->
          <div class="movie-poster__year-image col-6">
            <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
              <img class="movie-poster__img" src="<?php echo $assets_folder; ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
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
            <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
              <img class="movie-poster__img" src="<?php echo $assets_folder; ?>img/movie-posters/friday-the-13th/1.jpg" alt="Friday the 13th">
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
            <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
              <img class="movie-poster__img" src="<?php echo $assets_folder; ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
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
            <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
              <img class="movie-poster__img" src="<?php echo $assets_folder; ?>img/movie-posters/friday-the-13th/1.jpg" alt="Friday the 13th">
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
  include DOCUMENT_ROOT . LAYOUTS . "footer.php";
  ?>
</body>

</html>
