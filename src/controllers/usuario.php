<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
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

  /**
   * Obtenemos foto de perfil del usuario.
   * 
   * Si no hay foto, es decir, que es null, regresar una genérica.
   *
   * @return void
   */
  public static function getFotoPerfil()
  {
    // echo var_dump($_SESSION);

    $foto_perfil = $_SESSION["foto_perfil"];
    $username = $_SESSION["username"];
    $alt = "Detalles de usuario - {$username}";
    $detalles_perfil_url =
      FOLDERS_WITH_LOCALHOST["VIEWS"]
      . "user/index.php?id={$_SESSION["id"]}";
    if (!empty($foto_perfil)) {
?>
      <a href='<?php echo $detalles_perfil_url; ?>'>
        <img src='data:image/jpeg; base64, <?php echo $foto_perfil; ?>' alt='<?php echo $alt; ?>' class='circle-avatar'>
      </a>
    <?php
      return;
    }
    ?>

    <!-- 
    Font Awesome permite apilar elementos. Aquí ponemos un círculo detrás del 
    ícono para simular una foto de perfil circular con el ícono en medio.
    -->
    <span class="fa-stack circle-avatar profile-picture">
      <i class="fa-stack-2x fa-solid fa-circle circle-avatar profile-picture__circle"></i>
      <i class="fa-stack-1x fa-solid fa-user-astronaut fa-inverse profile-picture__icon"></i>
    </span>
<?php
  }
}

if (Controller::isCurrentFileView()) {
  return;
}

if (Controller::isGet()) {
  return;
}

Controller::redirectIfNonExistentPostMethod("login/index.php");

echo var_dump($_POST);

/* ------------------------- ELIMINACIÓN DE USUARIO ------------------------- */
if (Controller::isMethodDelete()) {
  // Controller::startSession();
  echo "DELETE";

  // session_destroy();
}

/* ------------------------------ NUEVO USUARIO ----------------------------- */
if (Controller::isMethodPost()) {
  echo "POST";
}

/* ------------------------ ACTUALIZACIÓN DE USUARIO ------------------------ */
if (Controller::isMethodPut()) {
  echo "PUT";
}

// Al final de cualquiera de los procedimientos, redirigir a la pestaña
// principal.
// Controller::redirectView();
