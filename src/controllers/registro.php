<?php

namespace Controllers;

require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../libs/controller.php";

use Libs\Controller;

class Registro extends Controller
{
  const REQUIRED_FIELDS = [
    "username",
    "nombres",
    "apellidos",
    "password"
  ];

  /**
   * Revisar que todos los datos de registro hayan sido especificados.
   *
   * @param array $form_fields
   * @return bool
   */
  public static function areRequiredFieldsFilled($form_fields)
  {
    /**
     * an array containing all the entries from array1 that are not present in
     * any of the other arrays.
     * 
     * Si es igual a 0, significa que encontró todos los valores.
     */
    $diff = array_diff(self::REQUIRED_FIELDS, array_keys($form_fields));
    return count($diff) === 0;
  }
}
