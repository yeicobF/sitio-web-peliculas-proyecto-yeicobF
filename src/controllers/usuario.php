<?php

require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../libs/usuario.php";

namespace Controllers;

use Libs\Controller;

class Usuario extends Controller
{
  /**
   * Obtener parte del arreglo del sesión necesaria.
   *
   * @param object $user
   * @return array
   */
  public static function getSessionArrayElements(object $user): array
  {
    $session_details = $user->getParamValues();
    unset($session_details["password"]);
    return $session_details;
  }
}
