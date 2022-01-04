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
  <link rel="stylesheet" href="<?php echo $css_folder; ?>config.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>components/components.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>menu/menu.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>utilities/utilities.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>transformations/rotate.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>movies/movies.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>movies/movie-details.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>movies/comments.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>footer/footer.css">
  <link rel="stylesheet" href="<?php echo $css_folder; ?>form/form.css">

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

      <section class="movie-details__poster col-12 col-sm-4">
        <img class="movie-poster__img movie-details__img" src="<?php echo $img_folder; ?>movie-posters/spiderman-no-way-home/1.jpg" alt="Póster de película">

        <?php
        if (Controllers\Usuario::isAdmin()) {
        ?>
          <form action="<?php echo "{$controllers_folder}pelicula.php"; ?>" class="form__buttons movie-details__form__buttons" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="id" value="1">

            <a rel="noopener noreferrer" href="<?php echo "{$url_page["editar-pelicula"]}?id={$id_pelicula}"; ?>" class="btn btn-info">
              Editar película
            </a>
            <button type="submit" class="btn btn-danger">
              Eliminar película
            </button>
          </form>
        <?php
        }

        ?>

      </section>
      <section class="movie-details col-12 col-sm-8">
        <!-- Título original y en español. -->
        <header class="movie-details__title">
          <h1>Nombre español</h1>
          <h2>Nombre original</h2>
        </header>
        <section class="movie-details__info">
          <time datetime="2021" class="movie-details__year">2021</time>
          <!-- Calificaciones de la película. -->
          <data value="4.5" class="movie-details__user-rating">4.5/5</data>
          <time datetime="PT2H28M">2h 38m</time>
          <data value="18" class="movie-details__age-rating">
            Clasificación: 18+
          </data>
        </section>
        <p class="movie-details__synopsis">
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sint iste, ratione consequatur aliquid dolores cupiditate facere molestiae alias officia nisi totam modi ullam. Praesentium adipisci expedita iusto ullam deserunt illo?

          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Sint iste, ratione consequatur aliquid dolores cupiditate facere molestiae alias officia nisi totam modi ullam. Praesentium adipisci expedita iusto ullam deserunt illo?
        </p>
        <ul class="movie-details__cast">
          <li class="movie-details__cast__type">
            <h3 class="movie-details__cast__type__title">
              Director/es:
            </h3>
            <ul>
              <li class="movie-details__cast__member">Lorem, ipsum dolor.</li>
              <li class="movie-details__cast__member">Lorem, ipsum dolor.</li>
              <li class="movie-details__cast__member">Lorem, ipsum dolor.</li>
              <li class="movie-details__cast__member">Lorem, ipsum.</li>
            </ul>
          </li>
          <li class="movie-details__cast__type">
            <h3 class="movie-details__cast__type__title">
              Actor/es:
            </h3>
            <ul>
              <li class="movie-details__cast__member">Lorem, ipsum dolor.</li>
              <li class="movie-details__cast__member">Lorem, ipsum.</li>
            </ul>
          </li>
          <li class="movie-details__cast__type">
            <h3 class="movie-details__cast__type__title">
              Géneros:
            </h3>
            <ul>
              <li class="movie-details__cast__member">Comedia</li>
              <li class="movie-details__cast__member">Acción</li>
            </ul>
          </li>
        </ul>
      </section>
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
            <img src="<?php echo $img_folder; ?>../avatar/1.jpg" alt="Username" class="circle-avatar">
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
            <img src="<?php echo $img_folder; ?>../avatar/1.jpg" alt="Username" class="circle-avatar">
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
        <figure class="row movie-poster   col-12">
          <!--
            Contenedor para que el año tenga posición relativa al póster y
            no a todo el contenedor.
          -->
          <div class="movie-poster__year-image col-6">
            <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
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
        <figure class="row movie-poster   col-12">
          <!--
            Contenedor para que el año tenga posición relativa al póster y
            no a todo el contenedor.
          -->
          <div class="movie-poster__year-image col-6">
            <a rel="noopener noreferrer" href="<?php echo $views_folder; ?>peliculas/detalles-pelicula/index.php?id=id_pelicula" class="">
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
