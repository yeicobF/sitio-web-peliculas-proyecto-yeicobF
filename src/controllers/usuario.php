<?php

namespace Controllers;

require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../models/usuario.php";

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

if (Controller::isCurrentFileView()) {
  return;
}

if (Controller::isGet()) {
  return;
}

Controller::redirectIfNonExistentPostMethod("login/index.php");

/* ------------------------- ELIMINACIÓN DE USUARIO ------------------------- */
if (Controller::isMethodDelete()) {
  Controller::startSession();
  session_destroy();
}

/* ------------------------------ NUEVO USUARIO ----------------------------- */
if (Controller::isMethodPost()) {
}

/* ------------------------ ACTUALIZACIÓN DE USUARIO ------------------------ */
if (Controller::isMethodPut()) {
}

// Al final de cualquiera de los procedimientos, redirigir a la pestaña
// principal.
Controller::redirectView();
