<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";
require_once __DIR__ . "/../libs/model.php";
require_once __DIR__ . "/../models/pelicula.php";
require_once __DIR__ . "/login.php";

use Pelicula as ModelPelicula;
use Model as Model;
use Libs\Controller;
use Controllers\Login;

class Pelicula extends Controller
{
}

Controller::startSession();
Model::initDbConnection();

$result = 0;
$message = "";


// Obtener los datos del usuario actual, ya que, estos pudieron haber sido
// actualizados.
if (
  Controller::isGet()
) {

  return;
}

if (
  Controller::isCurrentFileView()
  || Controller::isCurrentFileAnotherController("pelicula")
) {
  return;
}

Controller::redirectIfNonExistentPostMethod("login/index.php");

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
