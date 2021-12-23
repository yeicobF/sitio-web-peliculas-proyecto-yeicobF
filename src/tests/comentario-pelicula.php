<?php
include_once "../libs/DB.php";
include_once "../models/pelicula.php";
include_once "../models/usuario.php";
include_once "../models/comentario-pelicula.php";
include_once "../libs/model.php";

// https://www.php.net/manual/es/function.date.php#116914
// https://www.php.net/manual/es/timezones.america.php
$defaultTimeZone = 'America/Mexico_City';
if (date_default_timezone_get() != $defaultTimeZone) {
  date_default_timezone_set($defaultTimeZone);
}

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
  generos: "acción, drama",
  id: 2,
);

$usuario = new Usuario(
  "Avelardo",
  "Juárez",
  "abel_juarez",
  "abelito",
  Usuario::ROLES_ENUM_INDEX["normal"],
);

/* --------------------- INSERTAR COMENTARIO EN PELÍCULA -------------------- */
$comentario = new ComentarioPelicula(
  pelicula_id: $pelicula->id,
  usuario_id: 14,
  comentario: "No me esperaba ese final!",
  // https://dev.mysql.com/doc/refman/8.0/en/datetime.html#:~:text=MySQL%20retrieves%20and%20displays%20DATE,%3Amm%3Ass%20%27%20format.
  // 'YYYY-MM-DD' -> The supported range is '1000-01-01' to '9999-12-31'.
  // Constantes para el formato: https://www.php.net/manual/es/datetime.constants.php
  fecha: Model::getCurrentDate(),
  /**
   * https://dev.mysql.com/doc/refman/8.0/en/time.html96 
   *
   * MySQL retrieves and displays TIME values in 'hh:mm:ss' format (or
   * 'hhh:mm:ss' format for large hours values). TIME values may range from
   * '-838:59:59' to '838:59:59'. The hours part may be so large because the
   * TIME type can be used not only to represent a time of day (which must be
   * less than 24 hours), but also elapsed time or a time interval between two
   * events (which may be much greater than 24 hours, or even negative).
   */
  hora: Model::getCurrentTime(),
);

echo var_dump(Model::OPERATION_INFO[$comentario->insertComentarioPelicula()]) . "<br><br>";
