<!-- 
Podría haber 2 póster: 

- 1 con el nombre debajo
- Con los detalles a la derecha.
-->

<!-- Póster de películas con solo título. -->
<!-- Póster de películas. -->
<figure class="movie-poster   col-6 col-sm-4">
  <!-- 
    Contenedor para que el año tenga posición relativa al póster y 
    no a todo el contenedor. 
  -->
  <div class="movie-poster__year-image">
    <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
      <img class="movie-poster__img" src="<?php echo DirectoryPath::getPathWithLocalhost(ASSETS); ?>img/movie-posters/spiderman-no-way-home/1.jpg" alt="Spiderman: No Way Home">
    </a>
    <time datetime="2021" class="movie-poster__year">2021</time>
  </div>
  <figcaption class="movie-poster__title">Spiderman: No Way Home</figcaption>
</figure>
