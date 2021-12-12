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
  const LOCALHOST = "http://localhost:8012/";
  const PROJECT_FOLDER_DIR = self::LOCALHOST . "fdw-2021-2022-a/proyecto-yeicobF/";
  const SRC_FOLDER_URL = self::PROJECT_FOLDER_DIR . "src/";
  const ASSETS_URL = self::SRC_FOLDER_URL . "assets/";
  const ICO_URL = self::ASSETS_URL . "ico/";
  const IMG_URL = self::ASSETS_URL . "img/";
  const VIEWS_URL = self::SRC_FOLDER_URL . "views/";
  const LAYOUTS_URL = self::VIEWS_URL . "layouts/";
  const MODELS_URL = self::SRC_FOLDER_URL . "models/";
  const CONTROLLERS_URL = self::SRC_FOLDER_URL . "controllers/";
}
