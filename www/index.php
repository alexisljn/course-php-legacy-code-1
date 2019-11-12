<?php

//use App\Core\BaseSQL;
use App\Core\Routing;
//use App\Core\Validator;
use App\Core\Container;

require 'conf.inc.php';

function myAutoloader($class)
{
    $prefix = 'App\\';
    $baseDir = __DIR__.'/src/';

    $prefixLength = strlen($prefix);
    if (0 !== strncmp($prefix, $class, $prefixLength)) {
        return;
    }
    $relativeClass = substr($class, $prefixLength);
    $file = $baseDir.str_replace('\\', '/', $relativeClass).'.php';
    if (file_exists($file)) {
        require $file;

        return;
    }

    throw new Exception('Erreur bro');
    /*//var_dump($class);
    // 1) Isoler la classe du namespace
    $dividedCoreNamespace = explode('\\', $class);
    $count = count($dividedCoreNamespace);
    $classPath = "core/".$dividedCoreNamespace[$count-1].".php";

    $dividedModelsNamespace = explode('\\', $class);
    $count = count($dividedModelsNamespace);
    $classModel = "models/".$dividedModelsNamespace[$count-1].".php";
    if(file_exists($classPath)){
    // 2) Réinclure  le namespace ?
    include $classPath;
    }else if(file_exists($classModel)){
    include $classModel;
    }*/
}

// La fonction myAutoloader est lancé sur la classe appelée n'est pas trouvée
spl_autoload_register('myAutoloader');

// Récupération des paramètres dans l'url - Routing
$slug = explode('?', $_SERVER['REQUEST_URI'])[0];
$routes = Routing::getRoute($slug);
extract($routes);

$container = new Container();

$cObject = $container->get($c);

//$cObject = new $c();

if (method_exists($cObject, $a)) {
    $cObject->$a();
} else {
    die('méthode '.$a.' existe pas');
}

// Vérifie l'existence du fichier et de la classe pour charger le controlleur
/*if( file_exists($cPath) ){
    include $cPath;
    if( class_exists($c)){
        //instancier dynamiquement le controller
        $cObject = new $c();
        //vérifier que la méthode (l'action) existe
        if( method_exists($cObject, $a) ){
            //appel dynamique de la méthode
            $cObject->$a();
        }else{
            die("La methode ".$a." n'existe pas");
        }

    }else{
        die("La class controller ".$c." n'existe pas");
    }
}else{
    die("Le fichier controller ".$c." n'existe pas");
}*/
