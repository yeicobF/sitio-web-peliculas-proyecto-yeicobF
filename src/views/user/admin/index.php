<!-- Agregar y eliminar películas -->
<?php
// Obtener la raíz del proyecto en el disco duro y no en el URL como en __DIR__.
// https://stackoverflow.com/questions/13369529/full-url-not-working-with-php-include
$path = "{$_SERVER["DOCUMENT_ROOT"]}/";

include_once $path
  . "fdw-2021-2022-a/proyecto-yeicobF/"
  . "src/config/directory-path.php";

include_once $path
  . DirectoryPath::VIEWS
  . "layouts/base-html-head.php";

$src_folder = DirectoryPath::getPathWithLocalhost(DirectoryPath::SRC);
$views_folder = DirectoryPath::getPathWithLocalhost(DirectoryPath::VIEWS);

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
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/config.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/components/components.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/menu/menu.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/utilities/utilities.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/transformations/rotate.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/movies/movies.css">
  <link rel="stylesheet" href="<?php echo $src_folder; ?>css/footer/footer.css">

  <!-- SCRIPTS -->
  <script defer src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::SRC) . "js/navbar.js"; ?>" type="module"></script>


  <?php
  echo $baseHtmlHead->getTitle();
  ?>
</head>

<body>
  <?php
  include $path . DirectoryPath::LAYOUTS . "navbar.php";
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
                <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
              </a>
              <time datetime="2021" class="movie-poster__year">2021</time>
            </div>
            <figcaption class="movie-poster__title">Spiderman: No Way Home</figcaption>
          </figure>

          <!-- Póster de películas. -->
          <figure class="movie-poster   col-6 col-sm-4">
            <div class="movie-poster__year-image">
              <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
                <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>img/movie-posters/friday-the-13th/1.jpg" alt="Friday the 13th">
              </a>
              <time datetime="2021" class="movie-poster__year">1980</time>
            </div>
            <figcaption class="movie-poster__title">Friday the 13th</figcaption>
          </figure>

          <!-- Póster de películas. -->
          <figure class="movie-poster   col-6 col-sm-4">
            <div class="movie-poster__year-image">
              <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
                <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>img/movie-posters/avengers-endgame/1.jpg" alt="Avengers: Endgame">
              </a>
              <time datetime="2021" class="movie-poster__year">2019</time>
            </div>
            <figcaption class="movie-poster__title">Avengers: Endgame</figcaption>
          </figure>

          <!-- Póster de películas. -->
          <figure class="movie-poster   col-6 col-sm-4">
            <div class="movie-poster__year-image">
              <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
                <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
              </a>
              <time datetime="2021" class="movie-poster__year">2021</time>
            </div>
            <figcaption class="movie-poster__title">Spiderman: No Way Home</figcaption>
          </figure>

          <!-- Póster de películas. -->
          <figure class="movie-poster   col-6 col-sm-4">
            <div class="movie-poster__year-image">
              <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
                <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
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
              <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
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
              <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>img/movie-posters/friday-the-13th/1.jpg" alt="Friday the 13th">
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
              <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
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
              <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(DirectoryPath::ASSETS); ?>img/movie-posters/friday-the-13th/1.jpg" alt="Friday the 13th">
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
  include $path . DirectoryPath::LAYOUTS . "footer.php";
  ?>
</body>

</html>
