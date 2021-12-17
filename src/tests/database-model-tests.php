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
echo var_dump(Model::attributeExists(Usuario::TABLE_NAME, "username", "jacob_ff", Usuario::PDO_PARAMS)) . "<br>";
echo var_dump(Model::attributeExists(Usuario::TABLE_NAME, "username", "inventado_no_existente", Usuario::PDO_PARAMS)) . "<br>";
echo var_dump(Model::attributeExists(Usuario::TABLE_NAME, "nombres", "F Javier", Usuario::PDO_PARAMS)) . "<br>";
echo var_dump(Model::attributeExists(Usuario::TABLE_NAME, "nombres", "F Javiersss", Usuario::PDO_PARAMS)) . "<br>";
