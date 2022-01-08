<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../models/like-comentario.php";
require_once __DIR__ . "/usuario.php";

use Libs\Controller;
use Controllers\Usuario;
use LikeComentario as ModelLikeComentario;
use Model;

class LikeComentario extends Controller
{
  /**
   * Obtener el número de likes y dislikes de un comentario.
   *
   * @param ModelLikeComentario $like_comentario
   * @return array Array asociativo con ["likes" => int, "dislikes" => int]
   */
  public static function getInteractionsNumber(
    array $db_interactions_comment
  ) {
    $likes = $dislikes = 0;
    foreach ($db_interactions_comment as $comment) {
      if (
        $comment["tipo"] === "like"
        || $comment["tipo"] === ModelLikeComentario::TIPO_ENUM_INDEX["like"]
      ) {
        $likes += 1;
      } else {
        $dislikes += 1;
      }
    }

    return [
      "likes"  => $likes,
      "dislikes"  => $dislikes,
    ];
  }

  /**
   * Obtener interacción del usuario con comentario.
   * 
   * Devuelve el tipo de interacción "like" o "dislike" si ha interaccionado. De
   * otra forma, devuelve false si es que no tiene interacción.
   *
   * @param array $db_interactions_comment Array con interacciones obtenidas de
   * la base de datos.
   * @param integer $user_id
   * @return string | bool "like" | "dislike" | false
   */
  public static function getUserInteractionWithComment(
    array $db_interactions_comment,
    int $user_id
  ): string | bool {
    foreach ($db_interactions_comment as $comment) {
      if ($comment["usuario_id"] === $user_id) {
        return $comment["tipo"];
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

// Obtener interacciones del comentario.
if (Controller::isGet()) {
  $comentario_pelicula_id_exists =
    array_key_exists(
      "comentario_pelicula_id",
      $_GET
    ) && is_numeric($_GET["comentario_pelicula_id"]);

  // Obtener los likes y dislikes del comentario.
  $db_interactions = ModelLikeComentario::getInteractionsComentario(
    $_GET["comentario_pelicula_id"]
  );
}

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
  $result = ModelLikeComentario::delete(
    $non_empty_fields["comentario_pelicula_id"],
    $non_empty_fields["usuario_id"],
  );
}

if (
  (Controller::isMethodPost($post) || Controller::isMethodPut($post))
  &&
  !Controller::areRequiredFieldsFilled(
    ModelLikeComentario::REQUIRED_FIELDS,
    $non_empty_fields
  )
) {
  $error["error"] = "No se enviaron los datos correctamente.";
  echo json_encode($error);
  return;
}

$interaccion_comentario = new ModelLikeComentario(
  comentario_pelicula_id: $non_empty_fields["comentario_pelicula_id"],
  usuario_id: $non_empty_fields["usuario_id"],
  tipo: $non_empty_fields["tipo"]
);

if (Controller::isMethodPost($post)) {
  $result = $interaccion_comentario->insertLikeComentario();
}

if (Controller::isMethodPut($post)) {
  $result = $interaccion_comentario->update();
}

// Todos los métodos harán este procedimiento para obtener el estado actual de
// las interacciones del comentario.
if (
  is_array($result)
  || is_array($db_interactions)
) {
  if ($db_interactions === null) {
    $db_interactions = $result;
  }

  $usuario_id_exists =
    array_key_exists(
      "usuario_id",
      $_GET
    ) && is_numeric($_GET["usuario_id"]);

  if (!$comentario_pelicula_id_exists) {
    $error["error"] = "No se especificaron los datos esperados.";
    echo json_encode($error);
    return;
  }
  $comment_interactions = LikeComentario::getInteractionsNumber(
    $db_interactions
  );

  $user_interaction = null;
  // Si se envía el ID del usuario, hay que obtener su interacción actual.
  if ($usuario_id_exists) {
    $user_interaction = LikeComentario::getUserInteractionWithComment(
      $db_interactions,
      Usuario::getId()
    );
  }

  $likes = $comment_interactions["likes"];
  $dislikes = $comment_interactions["dislikes"];
  // Resultados del estado del comentario.
  $results = $comment_interactions;

  if ($user_interaction !== false && strlen($user_interaction) > 0) {
    $results["user_interaction"] = $user_interaction;
  }

  echo json_encode($results);
  return;
}

$message = Model::OPERATION_INFO[$result];

if ($result === 1) {
  // https://www.php.net/manual/es/function.http-response-code.php
  http_response_code(200);
  // Solo puedo regresar JSON en AJAX.
  echo $interaccion_comentario->returnJson();
  return;
}

$error["error"] = $message;
echo json_encode($error);
return;
