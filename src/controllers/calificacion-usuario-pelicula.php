<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../models/like-comentario.php";
require_once __DIR__ . "/usuario.php";

use Libs\Controller;
use Controllers\Usuario;
use CalificacionUsuarioPelicula as ModelCalificacionUsuarioPelicula;
use Model;

/**
 * Calificación de una película por usuario.
 *
 * Se harán peticiones mediante AJAX y se utilizarán sus funciones en otras
 * clases.
 */
class CalificacionUsuarioPelicula extends Controller
{
  /**
   * Obtener el número de calificaciones que ha recibido la película.
   *
   * @return int
   */
  public static function getReviewsNumber(
    array $db_movie_reviews
  ) {
    return count($db_movie_reviews);
  }
  /**
   * Obtener calificación promedio de la película en base a 5.
   *
   * @return double
   */
  public static function getAverageMovieStars(
    array $db_movie_reviews
  ) {
    $total_stars = 0;

    foreach ($db_movie_reviews as $movie_review) {
      $total_stars += $movie_review["numero_estrellas"];
    }

    $stars_average = $total_stars / count($db_movie_reviews);

    return $stars_average;

    // https://www.kavoir.com/2012/10/php-round-to-the-nearest-0-5-1-0-1-5-2-0-2-5-etc.html
    // return round($stars_average * 2) / 2;
  }

  /**
   * Obtener la calificación que el usuario le dio a la película.
   *
   * Si no hay review de ese usuario, devolver false, indicando que no ha hecho
   * interacción.
   *
   * @return double | bool
   */
  public static function getUserMovieStars(
    array $db_movie_reviews,
    int $user_id
  ) {
    foreach ($db_movie_reviews as $movie_review) {
      if ($movie_review["usuario_id"] === $user_id) {
        return $movie_review["numero_estrellas"];
      }
    }

    return false;
  }

  public static function fetchCurrentMovieStarsData(
    $db_movie_reviews,
    $usuario_id = null,
  ) {

    $results = [
      "average_movie_stars" => self::getAverageMovieStars($db_movie_reviews),
      "reviews_number" => self::getReviewsNumber($db_movie_reviews)
    ];

    if ($usuario_id !== null && is_numeric($usuario_id)) {
      $results["user_movie_stars"] = self::getUserMovieStars($db_movie_reviews, $usuario_id);
    }
    return $results;
  }
}

Controller::startSession();
Model::initDbConnection();

if (
  Controller::isCurrentFileView()
  || Controller::isCurrentFileAnotherController("like-comentario")
) {
  return;
}



if (Controller::isGet()) {
  $pelicula_id_exists =
    Controller::getKeyExist("pelicula_id")
    && is_numeric("pelicula_id");

  if (!$pelicula_id_exists) {
    $error["error"] = "No se especificaron los datos esperados.";
    echo json_encode($error);
    return;
  }

  $db_movie_reviews =
    ModelCalificacionUsuarioPelicula::getCalificacionesPelicula(
      $_GET["comentario_pelicula_id"]
    );

  $usuario_id = null;

  if (array_key_exists("usuario_id", $_GET) && is_numeric($usuario_id)) {
    $usuario_id = $_GET["usuario_id"];
  }

  echo json_encode(
    CalificacionUsuarioPelicula::fetchCurrentMovieStarsData(
      $db_movie_reviews,
      $usuario_id
    )
  );
  return;
}

/* ---------------------------------- POST ---------------------------------- */

$error = ["error" => ""];
$view_path = "peliculas/index.php";
// Para recibir JSON como post, no lo podemos hacer en $_POST porque ese es un
// arreglo y no una cadena.
// Fuente: https://www.geeksforgeeks.org/how-to-receive-json-post-with-php/
$json_post = file_get_contents('php://input');

// Vamos a recibir la información en formato JSON, por lo que, hay que
// transformar a un arreglo asociativo.
// https://www.php.net/manual/es/function.json-decode.php
$post = json_decode(
  json: $json_post,
  associative: true
);

if (
  !Controller::isPost($post)
  || !Controller::isMethodExistent($post)
) {
  $error["error"] = "No se envió un método POST válido.";
}

// El usuario no puede hacer ningún procedimiento POST si no ha iniciado sesión.
if (!Login::isUserLoggedIn()) {
  $error["error"] = "No has iniciado sesión.";
}

if (strlen($error["error"]) > 0) {
  echo json_encode($error);
  return;
}


$non_empty_fields = Controller::getNonEmptyFormFields(
  $post
);
unset($non_empty_fields["_method"]);

if (Controller::isMethodDelete($post)) {
  if (!Controller::areRequiredFieldsFilled(
    ModelCalificacionUsuarioPelicula::REQUIRED_DELETION_FIELDS,
    $non_empty_fields
  )) {
    $error["error"] = "No se enviaron los datos correctamente.";
    echo json_encode($error);
    return;
  }

  $result = ModelCalificacionUsuarioPelicula::delete(
    $non_empty_fields["pelicula_id"],
    $non_empty_fields["usuario_id"],
  );
}

if (
  (Controller::isMethodPost($post) || Controller::isMethodPut($post))
  &&
  !Controller::areRequiredFieldsFilled(
    ModelCalificacionUsuarioPelicula::REQUIRED_FIELDS,
    $non_empty_fields
  )
) {
  $error["error"] = "No se enviaron los datos correctamente.";
  echo json_encode($error);
  return;
}

$db_movie_reviews = new ModelCalificacionUsuarioPelicula(
  pelicula_id: $non_empty_fields["pelicula_id"],
  usuario_id: $non_empty_fields["usuario_id"],
  numero_estrellas: $non_empty_fields["numero_estrellas"]
);

if (Controller::isMethodPost($post)) {
  $result = $db_movie_reviews->insertCalificacionUsuarioPelicula();
}

if (Controller::isMethodPut($post)) {
  $result = $db_movie_reviews->update();
}

$message = Model::OPERATION_INFO[$result];

if ($result === 1) {
  // https://www.php.net/manual/es/function.http-response-code.php
  http_response_code(200);
  // Solo puedo regresar JSON en AJAX.
  echo json_encode(["message" => $message]);
  return;
}

$error["error"] = $message;
echo json_encode($error);
return;
