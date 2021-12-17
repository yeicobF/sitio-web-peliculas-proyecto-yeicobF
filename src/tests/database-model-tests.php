<?php

include_once "../models/DB.php";
include_once "../models/usuario.php";
include_once "../models/model.php";

Model::initDbConnection();

// var_dump(Usuario::getEveryElement());

/* -------------------------------- INSERCIÓN ------------------------------- */

// Usuario::insertNewUsuario(
//   nombres: "Jacob F",
//   apellidos: "F",
//   username: "jacob_ff",
//   password: "contrase",
//   rol: Usuario::ROLES_ENUM_INDEX["normal"],
// );

/* ----------------------------------- GET ---------------------------------- */

// $foundUsers = Usuario::getEveryElement();
// 


# code...
// foreach ($foundUsers as $user) {
//   echo "User: {$user->_nombres}\n";
//   foreach ($user as $key => $value) {
//     echo "{$key}: {$value}, ";
//   }
// }

/* -------------------------------------------------------------------------- */
/* --------------------------- EXISTE UN REGISTRO. -------------------------- */
// echo var_dump(Model::attributeExists(Usuario::TABLE_NAME, "username", "jacob_ff", Usuario::PDO_PARAMS)) . "<br>";
// echo var_dump(Model::attributeExists(Usuario::TABLE_NAME, "username", "inventado_no_existente", Usuario::PDO_PARAMS)) . "<br>";
// echo var_dump(Model::attributeExists(Usuario::TABLE_NAME, "nombres", "F Javier", Usuario::PDO_PARAMS)) . "<br>";
// echo var_dump(Model::attributeExists(Usuario::TABLE_NAME, "nombres", "F Javiersss", Usuario::PDO_PARAMS)) . "<br>";

/* ------------------------------ ACTUALIZACIÓN ----------------------------- */
$param_values = [
  "nombres" => "Jacob Gonzalo",
  "apellidos" => "González",
  "username" => "nuevo_username_2",
  "password" => "holanuevas",
  "rol" => Usuario::ROLES_ENUM_INDEX["administrador"],
];
$where_clause = [
  "name" => "id",
  "value" => "10",
];

// echo "Update: "
//   . var_dump(Model::updateRecord(Usuario::TABLE_NAME, $param_values, $where_clause, Usuario::PDO_PARAMS))
//   . "Update: "
//   . "<br>";
// 
// echo "DeleteRecord: "
//   . var_dump(Model::deleteRecord(Usuario::TABLE_NAME, $where_clause, Usuario::PDO_PARAMS))
//   . "<br>";

var_dump(Model::getEveryRecord(
  Usuario::TABLE_NAME,
  $where_clause["name"],
  11,
  Usuario::PDO_PARAMS
));
