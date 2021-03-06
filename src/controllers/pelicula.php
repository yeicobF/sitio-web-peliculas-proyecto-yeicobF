<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../libs/model.php";
require_once __DIR__ . "/../models/pelicula.php";
require_once __DIR__ . "/../models/calificacion-usuario-pelicula.php";
require_once __DIR__ . "/usuario.php";
require_once __DIR__ . "/calificacion-usuario-pelicula.php";

use Pelicula as ModelPelicula;
use CalificacionUsuarioPelicula as ModelCalificacionUsuarioPelicula;
use Model as Model;
use Libs\Controller;
use Controllers\Usuario;
use Controllers\CalificacionUsuarioPelicula;

/**
 * Hay 3 posibilidades para el GET:
 * 
 * - Poster + año y nombre en la parte principal de películas.
 * - Poster y otros detalles en mejores películas.
 * - Detalles de película.
 * 
 * Además, si no se envía el ID, se obtendrán todas las películas en la categoría mencionada.
 */
class Pelicula extends Controller
{
  const DISPLAY_TYPE = [
    "minimal",
    "some-details",
    "detailed",
  ];

  /**
   * Película que se visualiza actualmente.
   *
   * @var ModelPelicula
   */
  public static $current_movie;

  public static function renderMinimalDetailsMovie(ModelPelicula $movie)
  {
    $url_id = "?id={$movie->id}";
    $edit_url = URL_PAGE["editar-pelicula"] . $url_id;
    $details_url = URL_PAGE["detalles-pelicula"] . $url_id;
?>
    <!-- Póster de películas. -->
    <figure class="movie-poster col-6 col-sm-4">
      <!-- 
            Contenedor para que el año tenga posición relativa al póster y 
            no a todo el contenedor. 
          -->
      <?php
      if (Usuario::isAdmin()) {
      ?>
        <form action="<?php echo Controller::FILES["pelicula"]; ?>" method="POST" class="movie-poster__admin-buttons">
          <input type="hidden" name="_method" value="DELETE">
          <input type="hidden" name="id" value="<?php echo $movie->id; ?>">

          <a rel="noopener noreferrer" href="<?php echo $edit_url; ?>" class="btn btn-primary">
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
        <a rel="noopener noreferrer" href="<?php echo $details_url; ?>" class="pretty-shadow">
          <?php self::renderPoster($movie) ?>
        </a>
        <time datetime="<?php echo $movie->release_year; ?>" class="movie-poster__year">
          <?php echo $movie->release_year; ?>
        </time>
      </div>
      <figcaption class="movie-poster__title">
        <?php echo $movie->nombre_original; ?>
      </figcaption>
    </figure>
    <?php
  }

  public static function renderEveryMovie()
  {
    $db_movies = ModelPelicula::getEveryMovie();

    if ($db_movies === null) {
      echo "<h3>No hay películas en la base de datos</h3>";
      return;
    }

    foreach ($db_movies as $db_movie) {
      $movie = new ModelPelicula(
        nombre_original: $db_movie["nombre_original"],
        duracion: $db_movie["duracion"],
        release_year: $db_movie["release_year"],
        restriccion_edad: $db_movie["restriccion_edad"],
        resumen_trama: $db_movie["resumen_trama"],
        actores: $db_movie["actores"],
        directores: $db_movie["directores"],
        generos: $db_movie["generos"],
        id: $db_movie["id"],
        nombre_es_mx: $db_movie["nombre_es_mx"],
        poster: $db_movie["poster"],
      );

      self::renderMinimalDetailsMovie($movie);
    }
  }

  public static function renderBestMovies()
  {
  }



  /**
   * Obtener poster de película si es que tiene.
   * 
   * Si no tiene póster, entregar algo más.
   *
   * @param ModelPelicula $movie
   * @return void
   */
  public static function renderPoster(
    $movie,
    string $poster_classes = ""
  ) {

    if (!empty($movie->poster)) {
      $poster = Controller::getEncodedImage($movie->poster);
    ?>
      <img src="data:image/jpeg; base64, <?php echo $poster; ?>" alt="<?php echo $movie->nombre_original; ?>" class="movie-poster__img pretty-shadow <?php echo $poster_classes; ?>">
    <?php
      return;
    }
    ?>

    <!-- 
    Si no hay poster, poner un fondo y un ícono que lo indique.
    -->
    <span class="fa-stack movie-poster__img movie__no-poster pretty-shadow <?php echo $poster_classes; ?>">
      <i class="fa-solid fa-border-none"></i>
      <p>No poster</p>
    </span>
    <?php
  }

  /**
   * Renderizar estrellas de una película.
   *
   * Se crean mitades de estrellas, pero una en orientación original y otra
   * invertida. Esto se logra con ciertas clases de font-awesome, que, en este
   * caso se ocupará: 
   *
   * `fa-flip-horizontal`
   *
   * Fuente:
   * https://fontawesome.com/v5.15/how-to-use/on-the-web/styling/rotating-icons
   *
   * @param float $average_movie_stars
   * @param float $user_movie_review
   * @return void
   */
  private static function renderMovieStarsInHalves(
    float $average_movie_stars = null,
    float $user_movie_review = null
  ) {
    $half_star = "fas fa-star-half";
    $flip_horizontal = "fa-flip-horizontal";
    $active_class = "";

    // 10 medias estrellas (2 por estrella).
    for ($i = 0, $current_star = 0.5; $i < (5 * 2); $i++, $current_star += .5) {
      $active_class = $half_star;
      if ($user_movie_review >= $current_star) {
        $active_class .= " movie-details__stars--active--user-review";
      } else {
        if ($average_movie_stars >= $current_star) {
          $active_class .= " movie-details__stars--active--every-review";
        }
      }
      if ($i % 2 === 0) {
    ?>
        <div class="movie-details__stars__one-complete fa-stack">
          <i data-star="<?php echo $current_star; ?>" class="<?php echo $active_class; ?>  fa-stack-2x"></i>
        <?php
        // Si no es par, invertimos.
      } else {
        ?>
          <i data-star="<?php echo $current_star; ?>" class="<?php echo "{$active_class} {$flip_horizontal}"; ?>  fa-stack-2x"></i>
        </div>
      <?php
      }
    }
  }

  private static function renderFullMovieStars(
    float $average_movie_stars = null,
    float $user_movie_review = null
  ) {
    $star = "fas fa-star";

    // 10 medias estrellas (2 por estrella).
    for ($i = 1; $i <= 5; $i++) {
      $active_class = $star . " movie-details__stars__one-complete";
      if ($user_movie_review >= $i) {
        $active_class .= " movie-details__stars--active--user-review";
      } else {
        if ($average_movie_stars >= $i) {
          $active_class .= " movie-details__stars--active--every-review";
        }
      } ?>
      <i data-star="<?php echo $i; ?>" class="<?php echo $active_class; ?>"></i>
    <?php
    }
  }

  /**
   * Renderizar detalles en cuanto a las estrellas de una película.
   *
   * @param ModelPelicula $movie
   * @return void
   */
  public static function renderMovieStarsDetails(
    ModelPelicula $movie
  ) {
    $db_movie_stars =
      ModelCalificacionUsuarioPelicula::getCalificacionesPelicula(
        $movie->id
      );

    $reviews_number = CalificacionUsuarioPelicula::getReviewsNumber(
      $db_movie_stars
    );

    $average_movie_stars = null;
    if ($reviews_number > 0) {
      $average_movie_stars = CalificacionUsuarioPelicula::getAverageMovieStars(
        $db_movie_stars
      );
    }

    $user_movie_review = null;
    if (Login::isUserLoggedIn()) {
      $user_movie_review =
        ModelCalificacionUsuarioPelicula::getCalificacionUsuarioPelicula(
          $movie->id,
          Usuario::getId()
        );
    }

    $reviews_number_text =
      "(por {$reviews_number} usuario"
      . ($reviews_number > 1 ? "s" : "")
      . ")";

    ?>
    <section class="movie-details__stars-container">
      <?php
      if (!Login::isUserLoggedIn()) {
      ?>
        <header class="movie-details__stars__all">
          <?php
          self::renderMovieStarsInHalves(
            $average_movie_stars,
            $user_movie_review
          );
          ?>
        </header>
      <?php
      } else {
      ?>

        <!-- Contenedor de todas las estrellas. -->
        <form id="movie-stars-form" class="movie-details__stars__all" action="" method="POST">
          <input type="hidden" name="pelicula_id" value="<?php echo $movie->id; ?>">
          <input type="hidden" name="usuario_id" value="<?php echo Usuario::getId(); ?>">

          <?php
          self::renderFullMovieStars(
            $average_movie_stars,
            $user_movie_review
          );
          ?>
        </form>
      <?php
      }
      ?>

      <footer>
        <ul class="movie-details__stars__users-reviews-info">

          <li class="movie-details__stars__every-user-average">
            <?php
            if ($reviews_number === 0) {
            ?>
              Ningún usuario ha calificado la película.
            <?php
            } else {
            ?>
              <p>Calificación: </p>
              <data title="user-review" value="<?php echo $average_movie_stars; ?>"><?php echo $average_movie_stars; ?>/5</data>
              <p> estrellas <?php echo $reviews_number_text; ?></p>
              <span class="circle movie-details__stars--active--every-review"></span>
            <?php
            }
            ?>
          </li>
          <li class="movie-details__user-stars">
            <?php
            if (Login::isUserLoggedIn()) {
              if ($user_movie_review === null) {
            ?>
                Aún no has calificado la película.
              <?php
              } else {
              ?>
                <p>Tu calificación: </p>
                <data title="user-review" value="<?php echo $user_movie_review; ?>"><?php echo $user_movie_review; ?>/5</data>
                <p> estrellas</p>
                <span class="circle movie-details__stars--active--user-review"></span>

            <?php
              }
            }
            ?>
          </li>
        </ul>
      </footer>

    </section>
  <?php
  }

  public static function renderDetailedMovie(ModelPelicula $movie)
  {
    $time = $movie->getSplitTime();
    $horas = $time["horas"];
    $minutos = $time["minutos"];
    $segundos = $time["segundos"];

    $display_time = "{$horas}h {$minutos}m";
    $display_time .= $segundos > 0 ? " {$segundos}s" : "";

    $actores = Controller::stringToUppercasePerWord(", ", $movie->actores);
    $directores = Controller::stringToUppercasePerWord(", ", $movie->directores);
    $generos = Controller::stringToUppercasePerWord(", ", $movie->generos);
  ?>
    <section class="movie-details__poster col-12 col-sm-4">

      <?php
      self::renderPoster($movie, "movie-details__img");

      if (Usuario::isAdmin()) {
      ?>
        <form action="<?php echo CONTROLLERS_FOLDER . "pelicula.php"; ?>" class="form__buttons movie-details__form__buttons" method="POST">
          <input type="hidden" name="_method" value="DELETE">
          <input type="hidden" name="id" value="<?php echo $movie->id; ?>">

          <a rel="noopener noreferrer" href="<?php echo URL_PAGE["editar-pelicula"] . "?id={$movie->id}"; ?>" class="btn btn-info">
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

        <h1><?php echo $movie->nombre_original; ?></h1>
        <?php
        if ($movie->nombre_es_mx !== null) {
          echo "<h2>{$movie->nombre_es_mx}</h2>";
        }
        ?>
      </header>
      <section class="movie-details__info">
        <time datetime="<?php echo $movie->release_year; ?>" class="movie-details__year"><?php echo $movie->release_year; ?></time>
        <!-- Calificaciones de la película. -->
        <!-- Hay que obtenerlas de otra tabla y promediarlas. -->
        <time datetime="<?php echo "PT{$horas}H{$minutos}M{$segundos}S"; ?>"><?php echo $display_time; ?></time>
      </section>
      <data value="<?php echo $movie->restriccion_edad; ?>" class="movie-details__age-rating">
        <h3>Clasificación: </h3>
        <p><?php echo $movie->restriccion_edad; ?></p>
      </data>
      <?php
      self::renderMovieStarsDetails($movie);
      ?>
      <p class="movie-details__synopsis">
        <?php echo $movie->resumen_trama; ?>
      </p>
      <ul class="movie-details__cast">
        <li class="movie-details__cast__type">
          <h3 class="movie-details__cast__type__title">
            Director/es:
          </h3>
          <ul>
            <?php echo Controller::getListItems($directores, "movie-details__cast__member"); ?>
          </ul>
        </li>
        <li class="movie-details__cast__type">
          <h3 class="movie-details__cast__type__title">
            Actor/es:
          </h3>
          <ul>
            <?php echo Controller::getListItems($actores, "movie-details__cast__member"); ?>
          </ul>
        </li>
        <li class="movie-details__cast__type">
          <h3 class="movie-details__cast__type__title">
            Géneros:
          </h3>
          <ul>
            <?php echo Controller::getListItems($generos, "movie-details__cast__member"); ?>
          </ul>
        </li>
      </ul>
    </section>
<?php
  }
}

Controller::startSession();
Model::initDbConnection();

Pelicula::$current_movie = null;
$result = 0;
$message = "";

/* -------------------------- DETALLES DE PELÍCULA -------------------------- */


if (
  Controller::isGet()
  && Controller::getKeyExist("id")
  && is_numeric($_GET["id"])
) {
  $db_movie = ModelPelicula::getMovie($_GET["id"]);

  if ($db_movie === null) {
    Controller::redirectView(
      view_path: "peliculas/index.php",
      error: "No se encontró la película."
    );
    return;
  }

  Pelicula::$current_movie = new ModelPelicula(
    nombre_original: $db_movie["nombre_original"],
    duracion: $db_movie["duracion"],
    release_year: $db_movie["release_year"],
    restriccion_edad: $db_movie["restriccion_edad"],
    resumen_trama: $db_movie["resumen_trama"],
    actores: $db_movie["actores"],
    directores: $db_movie["directores"],
    generos: $db_movie["generos"],
    id: $db_movie["id"],
    nombre_es_mx: $db_movie["nombre_es_mx"],
    poster: $db_movie["poster"],
  );

  // Solo se pide el ID en la vista de detalles.
  if (Controller::containsSpecificViewPath("detalles-pelicula/index.php")) {
    Pelicula::renderDetailedMovie(Pelicula::$current_movie);
  }
  return Pelicula::$current_movie->returnJson();
}

if (
  Controller::isCurrentFileView()
  || Controller::isCurrentFileAnotherController("pelicula")
) {
  return;
}

Controller::redirectIfNonExistentPostMethod("peliculas/index.php");

/* -------------------------------------------------------------------------- */
// Un usuario sin rol de administrador no puede hacer las siguientes
// operaciones:
// - POST
// - PUT
// - DELETE
Usuario::redirectIfNotAdmin();

// echo var_dump($_POST);

// Campos del formulario.
$form_fields = $_POST;
unset($form_fields["_method"]);

// Verificar si hay una foto. Si la hay, obtenerla.
if (Controller::fileExists("poster")) {
  $form_fields["poster"] = Controller::getFile("poster");
}

$non_empty_fields = Controller::getNonEmptyFormFields(
  $form_fields
);

/* ------------------ OBTENER DURACIÓN COMO UNA SOLA CADENA ----------------- */
$hours = $non_empty_fields["horas"];
$minutes = $non_empty_fields["minutos"];
$seconds = $non_empty_fields["segundos"];

$duracion = Controller::getTime($hours, $minutes, $seconds);

// h:m:s = Mínimo 5 caracteres.
if (strlen($duracion) >= 5) {
  $non_empty_fields["duracion"] = $duracion;
}

/* ---------------------------------- POST ---------------------------------- */
/* ----------------------------- NUEVA PELÍCULA ----------------------------- */
if (Controller::isMethodPost()) {

  // Revisar que todos los campos menos "fecha_nacimiento" tienen datos.
  if (!Controller::areRequiredFieldsFilled(
    ModelPelicula::REQUIRED_FIELDS,
    $non_empty_fields
  )) {
    Controller::redirectView(
      view_path: "user/admin/agregar-pelicula/index.php",
      error: "No se ingresaron los datos de todos los campos."
    );
    return;
  }

  $pelicula = new ModelPelicula(
    nombre_original: $non_empty_fields["nombre_original"],
    duracion: $non_empty_fields["duracion"],
    release_year: $non_empty_fields["release_year"],
    restriccion_edad: $non_empty_fields["restriccion_edad"],
    resumen_trama: $non_empty_fields["resumen_trama"],
    actores: $non_empty_fields["actores"],
    directores: $non_empty_fields["directores"],
    generos: $non_empty_fields["generos"],
    nombre_es_mx: $non_empty_fields["nombre_es_mx"],
    poster: $non_empty_fields["poster"],
  );

  $result = $pelicula->insert();
}

/* --------------------- El id debe estar especificado. ---------------------
 */

// Tanto DELETE como PUT requieren de un ID.
if (
  !Controller::idExists(false, $non_empty_fields)
  && (Controller::isMethodDelete()
    || Controller::isMethodPut()
  )
) {
  Controller::redirectView(
    "user/index.php",
    error: "No se envió el ID de la película."
  );
  return;
}

/* --------------------------------- DELETE --------------------------------- */
/* ------------------------- ELIMINACIÓN DE PELÍCULA ------------------------ */
if (Controller::isMethodDelete()) {
  $result = ModelPelicula::delete($non_empty_fields["id"]);
}

// No se mandaron datos. Esto solo es posible en una actualización, ya que, no
// hay campos requeridos como en el registro.
if (count($non_empty_fields) === 0) {
  Controller::redirectView(
    "user/index.php",
    error: "No se enviaron datos en el formulario."
  );
  return;
}

/* --------------------------------- UPDATE --------------------------------- */
/* ------------------------ ACTUALIZACIÓN DE PELÍCULA ----------------------- */
if (Controller::isMethodPut()) {

  // $result = Model::updateRecord(
  //   table: ModelPelicula::TABLE_NAME,
  //   param_values: $non_empty_fields,
  //   where_clause_names: [
  //     "id"
  //   ],
  //   where_clause_values: [
  //     Usuario::getId()
  //   ],
  //   unique_attributes: ModelPelicula::UNIQUE_ATTRIBUTES,
  //   pdo_params: ModelPelicula::PDO_PARAMS
  // );
}

$message = Model::OPERATION_INFO[$result];
// showElements(array($message));

// Al final de cualquiera de los procedimientos, redirigir a la pestaña
// principal.
if ($result === 1) {
  $view_path = "peliculas/index.php";

  if (Controller::isMethodPut()) {
    $view_path = "peliculas/detalles-pelicula/index.php?id={$non_empty_fields["id"]}";

    // Redirigir a los detalles del usuario.
    // Controller::redirectView(
    //   view_path: $view_path,
    //   message: $message
    // );
    // return;
  }

  Controller::redirectView(
    view_path: $view_path,
    message: $message
  );
  return;
}

Controller::redirectView(error: $message);
