<?php
/*
 *
 * Insertar regiones en magento 2
 * probado en magento 2.3.4
 * 11 abril 2020
 * por Pedro Herrera Pilco Arequipa-Perú
 * 
 */

use Magento\Framework\App\Bootstrap;

// importamos el archivo para Inicialización de la aplicación Magento y bootstrap
   //(este es bootstrap es de magento no el de hoja de estilo que conocemos);
// este esta en la diguiente ruta app/bootstrap.php   (podemos usar lo siguiente:)
//require __DIR__ . '/app/bootstrap.php'; //  usando __Dir__ para rellenar el origen
//tienes que apuntar a este archivo (en el directorio de magento2)
require 'app/bootstrap.php';

// pasamos el parametro
$params = $_SERVER;
// creamos la instansia de la aplicaccion 
$bootstrap = Bootstrap::create(BP, $params);
 // creamos la instansia del manejador de objetos
$objectManager = $bootstrap->getObjectManager();
 // pedimos la coneccion
$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
// hacemos la conecion
$connection = $resource->getConnection();
// podemos apuntar a la tabla que deseamos
//$tableName = $resource->getTableName('mgwm_directory_country_region'); 
 
// Preparamos la consulta

 // creamos una matriz que contenga los datos pertenecientes a code y default_name de la tablas a insertar.
 $new_regions = array(
    'AQP' => 'Arequipa',
    'MOQ' => 'Moquegua',
    'TAC' => 'Tacna',
    'PUN' => 'Puno',
    'CUS' => 'Cuzco',
     );

// Indicamos el dato que va en conuntry_code
// aqui ponemos el Código de país al que se añaden las regiones, en este caso Peru, este tiene que corresponder al pais (ISO 3166-2)(asi esta en la tabla que tiene la relacion de paise en magento)

$country_code = 'PE';
       
// Idioma para el que son válidos los nombres de las regiones,
// si el idioma por defecto para tu magento es es_ES (esto esta en tienda->configuracion->general->Opciones de configuración regional->configuracion regional)
// se supone que instalaste el idioma español es_ES.
$locale = 'es_ES';

foreach ($new_regions as $region_code => $region_name) { 

    //insertamos en la primera tabla directory_country_region, 
    //no te olvides de cambiar el prefijo en este caso es: mgwm_ (esto se le añade al nombre de la tabla)
    $query = "INSERT INTO `mgwm_directory_country_region` (`region_id`,`country_id`,`code`,`default_name`) VALUES (NULL,?,?,?)";
    $connection->query($query,array($country_code,$region_code,$region_name));
           
    // Obtenemos el id insertado (dado que necesitamos este id para ingresar en la segunda tabla en la columna region_id)
    $region_id = $connection->lastInsertId();

    // Insertamos en la segunda tabla mgwm_directory_country_region_name
    //no te olvides de cambiar el prefijo en este caso es: mgwm_ (esto se le añade al nombre de la tabla)
    $sql = "INSERT INTO `mgwm_directory_country_region_name` (`locale`,`region_id`,`name`) VALUES (?,?,?)";
    $connection->query($sql,array($locale,$region_id,$region_name));
}
    echo 'done';
  // espero que te sirva atentamente: Pedro Herrera Pilco - Arequipa-Perú
  // Modificas los datos del array (con los departamento o estados de tu pais) 
  // guardas en un archivo in-regiones.php
  // Subes este archivo a tu servidor www.tu-tienda.com (donde esta tu index)
  // ejecutas el archivo accediendo en tu navegador a --> www.tu-tienda.com/in-regiones.php
  // si todo esta bien te mostrara done!
  // si hay errores puedes buscar documentacion en magento. ()
  ?>
