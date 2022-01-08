<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/usuario.php";

use Libs\Controller;
use Controllers\Usuario;
use LikeComentario as ModelLikeComentario;

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
