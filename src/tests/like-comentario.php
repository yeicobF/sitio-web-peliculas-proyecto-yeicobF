<?php

include_once "../libs/DB.php";
include_once "../models/like-comentario.php";
include_once "../libs/model.php";

Model::initDbConnection();

$like_comentario = new LikeComentario(
  2,
  3,
  1
);

// $resultInsert = $like_comentario->insertLikeComentario();
// $resultDelete = $like_comentario->delete();
$resultUpdate = $like_comentario->update();

// echo $resultInsert . "<br><br>";
// echo $resultDelete . "<br><br>";
echo $resultUpdate . "<br><br>";
