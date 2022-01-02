<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../libs/model.php";
require_once __DIR__ . "/../models/usuario.php";

use Usuario as ModelUsuario;
use Model as Model;
use Libs\Controller;

class Usuario extends Controller
{

  public static ?ModelUsuario $current_user = null;

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

  /**
   * Obtener arreglo con los datos del usuario actual.
   *
   * @param int $id
   * @return array
   */
  public static function getCurrentUserData(int $id): array
  {
    return Model::getRecord(
      table: ModelUsuario::TABLE_NAME,
      where_clause_names: ["id"],
      where_clause_values: [$id],
      pdo_params: ModelUsuario::PDO_PARAMS
    );
  }

  public static function verifyPasswordToUpdate(
    $current_password,
    $new_password
  ) {
  }

  /**
   * Actualizar los valores de la sesión que sean enviados en un arreglo
   * asociativo.
   *
   * @param array $session_values Arreglo asociativo con los valores a
   * actualizar de la sesión.
   * @return void
   */
  public static function updateSessionValues(array $session_values)
  {
    foreach ($session_values as $key => $value) {
      // Solo actualizar las llaves existentes.
      if (isset($_SESSION[$key])) {
        $_SESSION[$key] = $value;
      }
    }
  }

  public static function getUsername()
  {
    return $_SESSION["username"];
  }
  public static function getNombres()
  {
    return $_SESSION["nombres"];
  }
  public static function getApellidos()
  {
    return $_SESSION["apellidos"];
  }
  public static function getRol()
  {
    return $_SESSION["rol"];
  }
  public static function getId()
  {
    return $_SESSION["id"];
  }
}

Controller::startSession();
Model::initDbConnection();

$result = 0;
$message = "";



// Obtener los datos del usuario actual, ya que, estos pudieron haber sido
// actualizados.
if (
  Controller::isGet()
  && str_contains(
    $_SERVER["SCRIPT_FILENAME"],
    "editar-perfil/index.php"
  )
) {
  Usuario::updateSessionValues(Usuario::getCurrentUserData($_SESSION["id"])[0]);
  return;
}

if (Controller::isCurrentFileView()) {
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
  // echo "PUT";
  unset($_POST["_method"]);

  $non_empty_fields = Controller::getNonEmptyFormFields($_POST);
  // showElements($non_empty_fields);

  $result = Model::updateRecord(
    table: ModelUsuario::TABLE_NAME,
    param_values: $non_empty_fields,
    where_clause_names: [
      "id"
    ],
    where_clause_values: [
      Usuario::getId()
    ],
    unique_attributes: ModelUsuario::UNIQUE_ATTRIBUTES,
    pdo_params: ModelUsuario::PDO_PARAMS
  );

  if ($result === 1) {
    Usuario::updateSessionValues($non_empty_fields);
  }

  // showElements($_SESSION);
}

$message = Model::OPERATION_INFO[$result];
// showElements(array($message));

// Al final de cualquiera de los procedimientos, redirigir a la pestaña
// principal.
if ($result === 1) {
  Controller::redirectView(message: $message);
  return;
}

Controller::redirectView(error: $message);
