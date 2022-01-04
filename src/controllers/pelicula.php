<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../libs/model.php";
require_once __DIR__ . "/../models/pelicula.php";

use Pelicula as ModelPelicula;
use Model as Model;
use Libs\Controller;

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

  public static function getEveryMovie()
  {
  }

  public static function getBestMovies()
  {
  }

  /**
   * Obtener elementos en forma de lista.
   *
   * @param array $items
   * @return void
   */
  private static function getListItems(array $items, string $classes)
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
  public static function getPoster($movie)
  {

    if (!empty($movie->poster)) {
      $poster = Controller::getEncodedImage($movie->poster);
?>
      <img src="data:image/jpeg; base64, <?php echo $poster; ?>" alt="<?php echo $movie->nombre_original; ?>" class="movie-poster__img movie-details__img">
    <?php
      return;
    }
    ?>

    <!-- 
    Si no hay poster, poner un fondo y un ícono que lo indique.
    -->
    <span class="fa-stack movie-poster__img movie-details__img movie-details__no-poster">
      <i class="fa-solid fa-border-none"></i>
      <p>No poster</p>
    </span>
  <?php
  }

  public static function getDetailedMovie(ModelPelicula $movie)
  {
    $time = $movie->getSplitTime();
    $horas = $time["horas"];
    $minutos = $time["minutos"];
    $segundos = $time["segundos"];

    $display_time = "{$horas}h {$minutos}m";
    $display_time .= $segundos > 0 ? " {$segundos}s" : "";
  ?>
    <section class="movie-details__poster col-12 col-sm-4">

      <?php
      self::getPoster($movie);

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

        <h1>Nombre original</h1>
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
        <data value="4.5" class="movie-details__user-rating">4.5/5</data>
        <time datetime="<?php echo "PT{$horas}H{$minutos}M{$segundos}S"; ?>"><?php echo $display_time; ?></time>
        <data value="<?php echo $movie->restriccion_edad; ?>" class="movie-details__age-rating">
          Clasificación: <?php echo $movie->restriccion_edad; ?>
        </data>
      </section>
      <p class="movie-details__synopsis">
        <?php echo $movie->resumen_trama; ?>
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
<?php
  }
}

Controller::startSession();
Model::initDbConnection();

$result = 0;
$message = "";

/* -------------------------- DETALLES DE PELÍCULA -------------------------- */
if (
  Controller::isGet()
  && Controller::getKeyExist("id")
  && is_numeric($_GET["id"])
  // Solo se pide el ID en la vista de detalles.
  && Controller::containsSpecificViewPath("detalles-pelicula")
) {
  $db_movie = ModelPelicula::getMovie($_GET["id"]);

  if ($db_movie === null) {
    Controller::redirectView(
      view_path: "peliculas/index.php",
      error: "No se encontró la película."
    );
    return;
  }

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

  Pelicula::getDetailedMovie($movie);
  return;
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
  $non_empty_fields["id"] === null
  && is_numeric($non_empty_fields["id"])
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
  echo "DELETE";
  if ($result === 1) {
  }
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

  $result = Model::updateRecord(
    table: ModelPelicula::TABLE_NAME,
    param_values: $non_empty_fields,
    where_clause_names: [
      "id"
    ],
    where_clause_values: [
      Usuario::getId()
    ],
    unique_attributes: ModelPelicula::UNIQUE_ATTRIBUTES,
    pdo_params: ModelPelicula::PDO_PARAMS
  );
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
