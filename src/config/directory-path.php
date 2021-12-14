<?php

/**
 * Clase para obtener los directorios de diversos elementos.
 * 
 * Las variables son estáticas.
 */
class DirectoryPath
{
  // Variables no estáticas, ya que, si lo hacemos así, nos da error por no ser
  // constantes.
  //
  // $_SERVER["HTTP_HOST"]: Obtiene el host (localhost:8012), pero no lo puedo
  // asignar a una constante. Serviría en este caso si la clase la pudiera
  // instanciar y utilizar variables e clase, pero por el momento no lo
  // requiero.
  const LOCALHOST = "http://localhost:8012/";
  const CONFIG_URL = __DIR__;
  
  const BARE_PROJECT_FOLDER_DIR = "fdw-2021-2022-a/proyecto-yeicobF/";
  const PROJECT_FOLDER_DIR = self::LOCALHOST . self::BARE_PROJECT_FOLDER_DIR;
  
  // const SRC_FOLDER_URL = self::BARE_PROJECT_FOLDER_DIR . "src/";

  const SRC_FOLDER_URL = "src/";
  const ASSETS_URL = self::SRC_FOLDER_URL . "assets/";
  const ICO_URL = self::ASSETS_URL . "ico/";
  const IMG_URL = self::ASSETS_URL . "img/";
  const VIEWS_URL = self::SRC_FOLDER_URL . "views/";
  const LAYOUTS_URL = self::VIEWS_URL . "layouts/";
  const MODELS_URL = self::SRC_FOLDER_URL . "models/";
  const CONTROLLERS_URL = self::SRC_FOLDER_URL . "controllers/";
}
