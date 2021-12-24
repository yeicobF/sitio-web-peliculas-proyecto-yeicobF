<?php

include_once "../libs/DB.php";
include_once "../models/calificacion-usuario-pelicula.php";
include_once "../libs/model.php";

Model::initDbConnection();

$calificacion_get = CalificacionUsuarioPelicula::getCalificacionUsuarioPelicula(2, 3)[0];

$calificacion = new CalificacionUsuarioPelicula(
  $calificacion_get["pelicula_id"],
  $calificacion_get["usuario_id"],
  $calificacion_get["numero_estrellas"],
);

// echo $calificacion->insertCalificacionUsuarioPelicula() . "<br><br>";

echo var_dump($calificacion_get) . "<br><br>";
echo var_dump($calificacion) . "<br><br>";

echo $calificacion->update(3.5) . "<br><br>";

// echo var_dump($calificacion->getParamValues());
