<!-- Contenedor de películas. -->
<main class="movies-container col-12 col-md-8">
  <header class="movies__header">
    <h2 class="movies-container__title">Películas</h2>
    <?php
    if (
      Controllers\Login::isUserLoggedIn()
      && Controllers\Usuario::isAdmin()
    ) {
    ?>
      <a rel="noopener noreferrer" href="<?php echo URL_PAGE["agregar-pelicula"]; ?>" class="btn btn-light">Agregar película</a>
    <?php
    }

    ?>

  </header>

  <section class="row">
    <!-- Póster de películas. -->
    <figure class="movie-poster   col-6 col-sm-4">
      <!-- 
            Contenedor para que el año tenga posición relativa al póster y 
            no a todo el contenedor. 
          -->
      <?php
      if (Controllers\Usuario::isAdmin()) {
      ?>
        <form action="<?php echo Libs\Controller::FILES["pelicula"]; ?>" method="POST" class="movie-poster__admin-buttons">
          <input type="hidden" name="_method" value="DELETE">
          <input type="hidden" name="id" value="1">

          <a rel="noopener noreferrer" href="<?php echo URL_PAGE["editar-pelicula"] . "?id=1"; ?>" class="btn btn-primary">
            <i class="fa-solid fa-pen-to-square"></i>
          </a>
          <button type="submit" class="btn btn-danger">
            <i class="fa-solid fa-trash"></i>
          </button>
        </form>
      <?php
      }

      ?>
      <div class="movie-poster__year-image">
        <a rel="noopener noreferrer" href="<?php echo VIEWS_FOLDER; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
          <img class="movie-poster__img" src="<?php echo ASSETS_FOLDER; ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
        </a>
        <time datetime="2021" class="movie-poster__year">2021</time>
      </div>
      <figcaption class="movie-poster__title">Spiderman: No Way Home</figcaption>
    </figure>

    <!-- Póster de películas. -->
    <figure class="movie-poster   col-6 col-sm-4">
      <div class="movie-poster__year-image">
        <a rel="noopener noreferrer" href="<?php echo VIEWS_FOLDER; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
          <img class="movie-poster__img" src="<?php echo ASSETS_FOLDER; ?>img/movie-posters/friday-the-13th/1.jpg" alt="Friday the 13th">
        </a>
        <time datetime="2021" class="movie-poster__year">1980</time>
      </div>
      <figcaption class="movie-poster__title">Friday the 13th</figcaption>
    </figure>

    <!-- Póster de películas. -->
    <figure class="movie-poster   col-6 col-sm-4">
      <div class="movie-poster__year-image">
        <a rel="noopener noreferrer" href="<?php echo VIEWS_FOLDER; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
          <img class="movie-poster__img" src="<?php echo ASSETS_FOLDER; ?>img/movie-posters/avengers-endgame/1.jpg" alt="Avengers: Endgame">
        </a>
        <time datetime="2021" class="movie-poster__year">2019</time>
      </div>
      <figcaption class="movie-poster__title">Avengers: Endgame</figcaption>
    </figure>

    <!-- Póster de películas. -->
    <figure class="movie-poster   col-6 col-sm-4">
      <div class="movie-poster__year-image">
        <a rel="noopener noreferrer" href="<?php echo VIEWS_FOLDER; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
          <img class="movie-poster__img" src="<?php echo ASSETS_FOLDER; ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
        </a>
        <time datetime="2021" class="movie-poster__year">2021</time>
      </div>
      <figcaption class="movie-poster__title">Spiderman: No Way Home</figcaption>
    </figure>

    <!-- Póster de películas. -->
    <figure class="movie-poster   col-6 col-sm-4">
      <div class="movie-poster__year-image">
        <a rel="noopener noreferrer" href="<?php echo VIEWS_FOLDER; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
          <img class="movie-poster__img" src="<?php echo ASSETS_FOLDER; ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
        </a>
        <time datetime="2021" class="movie-poster__year">2021</time>
      </div>
      <figcaption class="movie-poster__title">Spiderman: No Way Home</figcaption>
    </figure>

  </section>
</main>
