<?php

include_once "../models/DB.php";
include_once "../models/usuario.php";
include_once "../models/model.php";

Model::initDbConnection();

// $usuario = new Usuario(
//   "Avelardo",
//   "Juárez",
//   "abel_juarez",
//   "abelito",
//   Usuario::ROLES_ENUM_INDEX["normal"]
// );
// 
// $update_usuario = new Usuario(
//   "Abel",
//   "Hernán",
//   "ab_her",
//   "ab",
//   Usuario::ROLES_ENUM_INDEX["normal"],
//   id: 1
// );
// 
// 
// echo var_dump(Model::OPERATION_INFO[$usuario->insertUsuario()]) . '<br><br>';
// echo var_dump(Model::OPERATION_INFO[$update_usuario->updateUsuario()]) . '<br><br>';
// $usuario = new Usuario(
//   "Avelardofasdfdasf",
//   "Juárez",
//   "abel_juarezssa",
//   "abelito",
//   Usuario::ROLES_ENUM_INDEX["administrador"]
// );
// 
// $update_usuario = new Usuario(
//   "Abelfasdf",
//   "Hernán",
//   "jacob_ffsa",
//   "ab",
//   Usuario::ROLES_ENUM_INDEX["normal"],
//   id: 11
// );
// 
// 
// echo var_dump(Model::OPERATION_INFO[$usuario->insertUsuario()]) . '<br><br>';
// echo var_dump(Model::OPERATION_INFO[$update_usuario->updateUsuario()]) . '<br><br>';

/* --------------------------------- DELETE --------------------------------- */

$usuario_db = Model::getRecord(
  table: Usuario::TABLE_NAME,
  where_clause: [
    "name" => "id",
    "value" => "12"
  ],
  pdo_params: Usuario::PDO_PARAMS
)[0];

$usuario = new Usuario(
  nombres: $usuario_db["nombres"],
  apellidos: $usuario_db["apellidos"],
  username: $usuario_db["username"],
  password: $usuario_db["password"],
  rol: Usuario::ROLES_ENUM_INDEX[$usuario_db["rol"]],
  id: $usuario_db["id"],
  foto_perfil: $usuario_db["foto_perfil"]
);

$usuario->delete();
