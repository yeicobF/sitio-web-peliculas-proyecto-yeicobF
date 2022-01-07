<?php
require_once __DIR__ . "/../../../config/config.php";
require_once FOLDERS_WITH_DOCUMENT_ROOT["CONTROLLERS"] . "pelicula.php";
?>

<section class="row">
  <?php Controllers\Pelicula::renderEveryMovie(); ?>
  <!-- Póster de películas. -->
  <!-- <figure class="movie-poster   col-6 col-sm-4">
      <div class="movie-poster__year-image">
        <a rel="noopener noreferrer" href="<?php echo VIEWS_FOLDER; ?>peliculas/detalles-pelicula/index.php?id=7" class="">
          <img class="movie-poster__img" src="<?php echo ASSETS_FOLDER; ?>img/movie-posters/friday-the-13th/1.jpg" alt="Friday the 13th">
        </a>
        <time datetime="2021" class="movie-poster__year">1980</time>
      </div>
      <figcaption class="movie-poster__title">Friday the 13th</figcaption>
    </figure> -->
</section>
