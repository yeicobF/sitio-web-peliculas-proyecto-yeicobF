<!-- Póster de películas con todo y detalles a la derecha. -->
<figure class="row movie-poster   col-12">
  <!--
    Contenedor para que el año tenga posición relativa al póster y
    no a todo el contenedor.
  -->
  <div class="movie-poster__year-image col-6">
    <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
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
