# FDW: PROYECTO DEL SEMESTRE - SITIO WEB DE INFORMACIÓN DE PELÍCULAS

[![wakatime](https://wakatime.com/badge/user/f530d0b3-bc24-4950-bbff-22005567ed42/project/0bbc5c34-41c4-4f26-8b53-9e71c21f7c96.svg)](https://wakatime.com/badge/user/f530d0b3-bc24-4950-bbff-22005567ed42/project/0bbc5c34-41c4-4f26-8b53-9e71c21f7c96)

En este repositorio voy a estar trabajando con mi proyecto del semestre de la
materia de Fundamentos de Desarrollo Web de 7SEM, la cual estoy recursando.

En mi caso decidí trabajar con un **sitio web de información de películas**, el
cual es el mismo proyecto que elegí la primera vez que cursé la materia. Dicho
proyecto se encuentra en el siguiente repositorio:

- [Github / Fundamentos-web / proyecto-yeicobf](https://github.com/Fundamentos-Web/proyecto-yeicobF "Github / Fundamentos-web / proyecto-yeicobf")

> Los requerimientos del proyecto se encuentran en el documento:

- [Lineamientos del proyecto](DOCUMENTOS/Lineamientos%20del%20proyecto.pdf "Lineamientos del proyecto")

> Mi propuesta inicial del proyecto la realicé el Martes, 24 de AGOSTO, y se
> encuentra en el documento:

- [Propuesta inicial del proyecto / Películas](DOCUMENTOS/FDW_PropuestaInicialProyecto_Martes-24-AGOSTO-2021.pdf "Propuesta inicial del proyecto / Películas")

- [FDW: PROYECTO DEL SEMESTRE - SITIO WEB DE INFORMACIÓN DE PELÍCULAS](#fdw-proyecto-del-semestre---sitio-web-de-información-de-películas)
  - [CRONOGRAMA DE HITOS](#cronograma-de-hitos)
  - [MAQUETACIÓN](#maquetación)
  - [Funcionalidades por rol](#funcionalidades-por-rol)
  - [Pantallas](#pantallas)
  - [Diseño de la base de datos](#diseño-de-la-base-de-datos)
    - [Diagrama Entidad-Relación](#diagrama-entidad-relación)
    - [BD creada en MySQL](#bd-creada-en-mysql)
      - [ENUM para el rol](#enum-para-el-rol)
      - [Tablas creadas manualmente con llaves foráneas](#tablas-creadas-manualmente-con-llaves-foráneas)
      - [Screenshots](#screenshots)
  - [FUENTES CONSULTADAS](#fuentes-consultadas)

## CRONOGRAMA DE HITOS

En esta sección se encontrarán los hitos importantes a lo largo del desarrollo
del proyecto.

|                  Fecha                   |                                                                        Hito                                                                         |
| :--------------------------------------: | :-------------------------------------------------------------------------------------------------------------------------------------------------: |
|      Martes, 24 de AGOSTO del 2021       |                                                                Inicio del proyecto.                                                                 |
| Martes, 24 de AGOSTO del 2021 [11:59 PM] | Entrega de la [propuesta inicial del proyecto](DOCUMENTOS/FDW_PropuestaInicialProyecto_Martes-24-AGOSTO-2021.pdf "Propuesta inicial del proyecto"). |

## MAQUETACIÓN

Ya que ya cursé esta materia una vez, alcancé a hacer la maquetación para
computadoras, pero no para dispositivos móviles. Esta la hice en Figma y se
encuentra en el siguiente enlace:

- [Maquetación en Figma de FDW [6SEM]](https://www.figma.com/file/gKba72jKVkr2P3BkLNTXH9/MOVIES-WEBSITE-FDW-PROYECTO-6SEM-MAYO-2021?node-id=0%3A1 "Maquetación en Figma de FDW [6SEM]")

## Funcionalidades por rol

La lista de pantallas que están disponibles son las siguientes:

- Administrador
  - Agregar película
- Usuario registrado
  - Comentar película
  - Dar like a comentarios
  - Calificar película
  - Inicio de sesión
- Generales
  - Ver lista de películas
  - Ver detalles de película
  - Registro

## Pantallas

- Administrador
  - Agregar película
  - Eliminar película
- Usuario registrado
  - Editar perfil
- Usuario general
  - Inicio
  - Registro
  - Login
  - Detalles de película

## Diseño de la base de datos

Hice un diseño más complejo del que tuve que implementar, ya que, hubiese
aumentado la complejidad de la página por las tablas de actor, director y
género, ya que, hubiese tenido que implementar tablas intermedias, además de la
lógica necesaria.

### Diagrama Entidad-Relación

El diagrama lo hice con la aplicación StarUML en su versión gratuita. Lo que
impide es los diagramas se puedan exportar sin la marca de agua.

![Diagrama Entidad-Relación (más complejo de lo que implementé)](DOCUMENTOS/Diagrama-EntidadRelacion/FDW_EntidadRelacion_09-DIC-2021.svg "Diagrama Entidad-Relación (más complejo de lo que implementé)")

### BD creada en MySQL

En la tabla de películas, en lugar de utilizar tablas intermedias para los
actores, directores y géneros, lo guardé como cadena para no aumentar la
complejidad del sitio.

#### ENUM para el rol

El tipo de dato ENUM permite ingresar valores definidos en la creación de la
tabla y pesa menos bytes que si se almacenara como VARCHAR. Esto se puede
encontraren la documentación oficial de MySQL:

["MySQL 8.0 Reference Manual / ... / The ENUM Type / 11.3.5 The ENUM Type"](https://dev.mysql.com/doc/refman/8.0/en/enum.html "MySQL 8.0 Reference Manual  /  ...  /  The ENUM Type / 11.3.5 The ENUM Type")

#### Tablas creadas manualmente con llaves foráneas

[https://dev.mysql.com/doc/refman/8.0/en/ansi-diff-foreign-keys.html](https://dev.mysql.com/doc/refman/8.0/en/ansi-diff-foreign-keys.html "https://dev.mysql.com/doc/refman/8.0/en/ansi-diff-foreign-keys.html")

- comentario_pelicula

  ```sql
  USE `fdw_dic_2021_proyecto_final`;
  CREATE TABLE `comentario_pelicula`(
    id INT NOT NULL AUTO_INCREMENT,
    pelicula_id INT NOT NULL,
    usuario_id INT NOT NULL,
    comentario VARCHAR(256) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (pelicula_id) REFERENCES pelicula(id),
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
  );
  ```

- like_comentario

  ```sql
  CREATE TABLE `fdw_dic_2021_proyecto_final`.`like_comentario`(
    comentario_pelicula_id INT NOT NULL,
    usuario_id INT NOT NULL,
    FOREIGN KEY (comentario_pelicula_id) REFERENCES comentario_pelicula(id),
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
  );
  ```

- calificacion_usuario_pelicula

  ```sql
  CREATE TABLE `fdw_dic_2021_proyecto_final`.`calificacion_usuario_pelicula`(
    pelicula_id INT NOT NULL,
    usuario_id INT NOT NULL,
    numero_estrellas FLOAT NOT NULL,
    FOREIGN KEY (pelicula_id) REFERENCES pelicula(id),
    FOREIGN KEY (usuario_id) REFERENCES usuario(id)
  );
-  ```

#### Screenshots

![Creación BD de MySQL con phpMyAdmin](SCREENSHOTS/Creacion-BD-phpMyAdmin/1.png "Creación BD de MySQL con phpMyAdmin")
![Creación BD de MySQL con phpMyAdmin](SCREENSHOTS/Creacion-BD-phpMyAdmin/2.png "Creación BD de MySQL con phpMyAdmin")
![Creación BD de MySQL con phpMyAdmin](SCREENSHOTS/Creacion-BD-phpMyAdmin/3.png "Creación BD de MySQL con phpMyAdmin")
![Creación BD de MySQL con phpMyAdmin](SCREENSHOTS/Creacion-BD-phpMyAdmin/4.png "Creación BD de MySQL con phpMyAdmin")
![Creación BD de MySQL con phpMyAdmin](SCREENSHOTS/Creacion-BD-phpMyAdmin/5.png "Creación BD de MySQL con phpMyAdmin")
![Creación BD de MySQL con phpMyAdmin](SCREENSHOTS/Creacion-BD-phpMyAdmin/6.png "Creación BD de MySQL con phpMyAdmin")

## FUENTES CONSULTADAS

A continuación se encontrará una lista con algunas de las fuentes (las que
recordé registrar) que me fueron útiles.

- [https://stackoverflow.com/questions/8112831/implementing-comments-and-likes-in-database](https://stackoverflow.com/questions/8112831/implementing-comments-and-likes-in-database "https://stackoverflow.com/questions/8112831/implementing-comments-and-likes-in-database") -[]("")
- [https://stackoverflow.com/questions/490464/how-to-design-a-movie-database](https://stackoverflow.com/questions/490464/how-to-design-a-movie-database "https://stackoverflow.com/questions/490464/how-to-design-a-movie-database")
- [https://stackoverflow.com/questions/10628186/mysql-store-multiple-references-for-another-table-inside-one-cell-and-select-it](https://stackoverflow.com/questions/10628186/mysql-store-multiple-references-for-another-table-inside-one-cell-and-select-it "https://stackoverflow.com/questions/10628186/mysql-store-multiple-references-for-another-table-inside-one-cell-and-select-it")
- [https://ahrefs.com/blog/es/enlaces-nofollow/](https://ahrefs.com/blog/es/enlaces-nofollow/ "https://ahrefs.com/blog/es/enlaces-nofollow/")
- [https://behind-the-scenes.net/using-nofollow-noreferrer-and-noopener-rel-attributes/](https://behind-the-scenes.net/using-nofollow-noreferrer-and-noopener-rel-attributes/ "https://behind-the-scenes.net/using-nofollow-noreferrer-and-noopener-rel-attributes/")
- [https://ayudawp.com/la-vulnerabilidad-target_blank-la-solucion-relnoopener-noreferrer-wordpress/](https://ayudawp.com/la-vulnerabilidad-target_blank-la-solucion-relnoopener-noreferrer-wordpress/ "https://ayudawp.com/la-vulnerabilidad-target_blank-la-solucion-relnoopener-noreferrer-wordpress/")
- [https://mathiasbynens.github.io/rel-noopener/](https://mathiasbynens.github.io/rel-noopener/ "https://mathiasbynens.github.io/rel-noopener/")
- [https://css-tricks.com/use-target_blank/](https://css-tricks.com/use-target_blank/ "https://css-tricks.com/use-target_blank/")
- [https://fontawesome.com/v5.15/how-to-use/on-the-web/advanced/svg-asynchronous-loading](https://fontawesome.com/v5.15/how-to-use/on-the-web/advanced/svg-asynchronous-loading "https://fontawesome.com/v5.15/how-to-use/on-the-web/advanced/svg-asynchronous-loading")
