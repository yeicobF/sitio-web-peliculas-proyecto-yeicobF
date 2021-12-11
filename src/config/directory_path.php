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
  public $localhost = "http://localhost:8012/";
  public $project_folder_dir;
  public $src_folder_url;
  public $assets_url;
  public $ico_url;
  public $img_url;
  public $views_url;
  public $models_url;
  public $controllers_url;

  public function __construct()
  {
    $this->project_folder_dir = "{$this->localhost}fdw-2021-2022-a/proyecto-yeicobF/";
    $this->src_folder_url = "{$this->project_folder_dir}src/";
    $this->assets_url = "{$this->src_folder_url}assets/";
    $this->ico_url = "{$this->assets_url}ico/";
    $this->img_url = "{$this->assets_url}img/";
    $this->views_url = "{$this->src_folder_url}views/";
    $this->models_url = "{$this->src_folder_url}models/";
    $this->controllers_url = "{$this->src_folder_url}controllers/";
  }
}
