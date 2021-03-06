<?php
error_reporting(E_ALL);

ini_set("log_errors", TRUE);

// Aquí registrar los error_logs de la página.
ini_set("error_log", __DIR__ . "../logs/php-error.log");

// error_log("hola en index, prueba propio log");

// Utilizar la clase App para el ruteo.
require_once __DIR__ . "/libs/app.php";
require_once __DIR__ . "/libs/DB.php";
require_once __DIR__ . "/libs/model.php";
require_once __DIR__ . "/libs/controller.php";
require_once __DIR__ . "/libs/view.php";
require_once __DIR__ . "/config/config.php";

// Que automáticamente se ejecute el constructor y se empiecen a hacer las
// validaciones.
// $app = new App();
// $app->start();

//Redireccionar a la página principal
header("Location: views/index.php");
