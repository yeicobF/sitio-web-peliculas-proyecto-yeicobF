<?php

include_once "../libs/DB.php";
include_once "../models/calificacion-usuario-pelicula.php";
include_once "../libs/model.php";

Model::initDbConnection();

$calificacion = new CalificacionUsuarioPelicula(
  2,
  3,
  "4.5"
);

echo $calificacion->insertCalificacionUsuarioPelicula() . "<br><br>";
echo $calificacion->update("2") . "<br><br>";

// echo var_dump($calificacion->getParamValues());
