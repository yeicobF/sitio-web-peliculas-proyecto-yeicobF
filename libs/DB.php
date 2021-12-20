<?php

require_once __DIR__ . "/../config/config.php";

/**
 * Para la base de datos, utilizamos el patrón de diseño Singleton, el cual,
 * solo permite tener una instancia de una misma clase. Esto para evitar
 * problemas al tener más de una instancia de la base de datos.
 */

/**
 * Clase para lograr conexión con base de datos.
 */
class DBConnection
{
  /**
   * Variable estática para solo tener una sola instancia.
   */
  private static $connection;
  const DB_NAME = "fdw_dic_2021_proyecto_final";
  const HOST = "localhost";
  const CHARSET = "utf8";
  const USERNAME = "root";
  const PASSWORD = "";
  /** 
   * Método para regresar el elemento de la conexión si es que ya está creado.
   * Esto para cumplir con el patrón Singleton.  
   * */
  public static function getConnection()
  {
    /**
     * Crear objeto de conexión.
     *
     * Aquí, como `$connection` no está definida en el scope de la función, pero
     * en la clase como variable estática, hay que llamarla utilizando la
     * palabra reservada `self` y utilizando 2 puntos (::) como lo haríamos en
     * el llamado de una variable estática de clase.
     */
    if (self::$connection == null) {
      /**
       * Hacemos uso de la biblioteca PDO, la cual, nos permite operar con bases
       * de datos sin importar el gestor. Esto nos ayuda a no tener que
       * reescribir el código al migrar a otro gestor de bases de datos.
       * 
       * `$connection = new PDO($dns, $username, $password);`
       * 
       * Hay que indicar 3 parámetros al primer atributo de la función:
       * 
       * 1. Tipo de manejador de bases de datos (MySQL en nuestro caso).
       * 1.1. Host: Dónde se encuentra nuestro servidor de BD.
       * 1.2. Nombre de la base de datos.
       * 1.3. Forma de interpretación de cadenas. Se recomienda que utilicemos:
       * 
       * `charset=utf8`
       * 
       * 2. Username
       * 3. Contraseña
       * 
       * - Ejemplo:
       * 
       * `$connection = new PDO(
       *    mysql:host=localhost;dbname=escuela;charset=utf8', 
       *    root', 
       *    Starosa20'
       * )`
       * 
       */
      /**
       * https://www.php.net/manual/en/pdo.construct.php#:~:text=Parameters-,dsn,-The%20Data%20Source
       * 
       * The Data Source Name, or DSN, contains the information required to
       * connect to the database.
       *
       * In general, a DSN consists of the PDO driver name, followed by a colon,
       * followed by the PDO driver-specific connection syntax. Further
       * information is available from the PDO driver-specific documentation.
       */
      $data_source_name =
        "mysql:host=" . self::HOST
        . "; dbname=" . self::DB_NAME
        . "; charset=" . self::CHARSET;


      self::$connection = new PDO(
        $data_source_name,
        self::USERNAME,
        self::PASSWORD
      );

      /**
       * Ahora hay que agregar los atributos especiales al objeto.
       */
      // Manejo de errores.
      self::$connection->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
      );
      self::$connection->setAttribute(
        PDO::ATTR_EMULATE_PREPARES,
        false
      );
    }

    // Si la conexión ya está creada, se devuelve la que se creó en lugar de
    // crear otra.
    return self::$connection;
  }
}
