<?php

include_once "../libs/DB.php";
include_once "../models/pelicula.php";
include_once "../libs/model.php";

Model::initDbConnection();

$pelicula = new Pelicula(
  nombre_original: "Spiderman",
  duracion: "2h30m",
  nombre_es_mx: "Hombre Araña",
  release_year: "2021",
  restriccion_edad: "E",
  resumen_trama: "Película del hombre araña",
  actores: "Tom Holland",
  directores: "Jon Watts",
  generos: "acción, drama"
);

echo var_dump($pelicula) . "<br><br>";
echo var_dump($pelicula->getParamValues()) . "<br><br>";
echo var_dump($pelicula->returnJson()) . "<br><br>";
// echo var_dump(Model::OPERATION_INFO[$pelicula->insertPelicula()]) . "<br><br>";

