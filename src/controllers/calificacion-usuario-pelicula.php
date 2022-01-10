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
  public static function getNumberOfReviews(
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
}

Controller::startSession();
Model::initDbConnection();

if (
  Controller::isCurrentFileView()
  || Controller::isCurrentFileAnotherController("like-comentario")
) {
  return;
}

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
