<?php

require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../libs/model.php";
require_once __DIR__ . "/usuario.php";

namespace Controllers;

use Libs\Controller;
use Controllers\Usuario;

class Login extends Controller
{
  public static function login(Usuario $user)
  {
    session_start();

    // Agregar valores del usuario a la sesión.
    $_SESSION = array_merge(
      $_SESSION,
      Usuario::getSessionArrayElements($user)
    );
  }
}

if (!Controller::getMethod() === "POST") {
  Controller::redirectView("login/index.php");
}

// El método sí es POST.
