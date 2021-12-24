<?php

include_once "../libs/DB.php";
include_once "../models/like-comentario.php";
include_once "../libs/model.php";

Model::initDbConnection();

$found_like_comentario = LikeComentario::getLikeComentario(2, 3)[0];

$like_comentario = new LikeComentario(
  comentario_pelicula_id: $found_like_comentario["comentario_pelicula_id"],
  usuario_id: $found_like_comentario["usuario_id"],
  tipo: $found_like_comentario["tipo"]
);

echo "Antes de update" . var_dump($like_comentario) . "<br><br>";

// 
// $resultInsert = $like_comentario->insertLikeComentario();
// // $resultDelete = $like_comentario->delete();
$resultUpdate = $like_comentario->update();

echo "Después de update" . var_dump(LikeComentario::getLikeComentario(2, 3)[0]) . "<br><br>";
// 
// // echo $resultInsert . "<br><br>";
// // echo $resultDelete . "<br><br>";
// echo $resultUpdate . "<br><br>";
