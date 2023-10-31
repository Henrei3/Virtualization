<?php


use App\LDAP\Lib\Psr4AutoloaderClass;

require_once __DIR__ . '/../src/lib/Psr4AutoloaderClass.php';


// On instancie un namespace pour faciliter l'importation de classes dans notre projet
$loader = new Psr4AutoloaderClass();

$loader ->addNamespace('App\PGVM', __DIR__.'/../src/');

$loader -> register();



?>

