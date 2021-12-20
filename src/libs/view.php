<?php

class View
{
  private $data;
  private $view_name;


  public function __construct()
  {
  }

  /**
   * Renderizar vista solicitada.
   *
   * @param string $view_name Nombre de la vista que quiero cargar. Este será un
   * archivo.
   * @param array $data Datos de la vista. La vista será la encargada de hacer
   * operaciones para mostrar la información.
   * @return void
   */
  function render(string $view_name, array $data = []): void
  {
    $this->data = $data;
    $this->view_name = $view_name;

    // Llamar al archivo de la vista requerida.
    require DOCUMENT_ROOT . SRC . "views/" . $this->view_name . ".php";
  }
}
