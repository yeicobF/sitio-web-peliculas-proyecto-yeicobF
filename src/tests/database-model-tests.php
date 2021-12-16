<?php

include_once "../models/DB.php";
include_once "../models/usuario.php";
include_once "../models/model.php";

Model::initDbConnection();

// var_dump(Usuario::getEveryElement());

Usuario::insertNew(
  "F Jav",
  "Esquivel",
  "esquivel_test_1",
  "contra",
  Usuario::ROLES_ENUM_INDEX["normal"],
);

$foundUsers = Usuario::getEveryElement();



# code...
foreach ($foundUsers as $user) {
  echo "User: {$user->_nombres}\n";
  foreach ($user as $key => $value) {
    echo "{$key}: {$value}, ";
  }
}
