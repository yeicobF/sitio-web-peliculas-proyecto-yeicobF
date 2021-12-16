<?php

include_once "../models/DB.php";
include_once "../models/usuario.php";
include_once "../models/model.php";

Model::initDbConnection();

// var_dump(Usuario::getEveryElement());

$foundUsers = Usuario::getEveryElement();



# code...
// foreach (Usuario::getEveryElement() as $user) {
//   echo "User: {$user->_nombres}\n";
//   foreach ($user as $key => $value) {
//     echo "{$key}: {$value}, ";
//   }
// }
