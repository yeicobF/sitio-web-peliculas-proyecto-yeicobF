<!-- 
Podría haber 2 póster: 

- 1 con el nombre debajo
- Con los detalles a la derecha.
-->

<!-- Póster de películas con solo título. -->
<figure class="movie-poster border border-secondary col-6 col-sm-4">
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


<!-- Póster de películas con todo y detalles. -->
<figure class="row movie-poster border border-secondary col-12">
  <!--
    Contenedor para que el año tenga posición relativa al póster y
    no a todo el contenedor.
  -->
  <div class="movie-poster__year-image col-6">
    <a href="<?php echo DirectoryPath::VIEWS_URL; ?>peliculas/detalles-pelicula.php?id=id_pelicula" class="">
      <img class="movie-poster__img" src="<?php echo DirectoryPath::IMG_URL; ?>movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
    </a>
    <time datetime="2021" class="movie-poster__year">2021</time>
  </div>
  <figcaption class="col-6">
    <ul class="best-movies__details-list">
      <li class="movie-poster__title">Spiderman: No Way Home</li>
      <li>4.5/5</li>
      <li><time datetime="PT2H28M">2h 38m</time></li>
    </ul>
  </figcaption>
</figure>
