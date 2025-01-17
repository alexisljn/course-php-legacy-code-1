<?php

namespace App\Core;

class Routing
{
    public static $routeFile = 'routes.yml';

    public static function getRoute($slug)
    {
        var_dump($slug);
        $routes = yaml_parse_file(self::$routeFile);
        if (isset($routes[$slug])) {
            if (empty($routes[$slug]['controller']) || empty($routes[$slug]['action'])) {
                die('Il y a une erreur dans le fichier routes.yml');
            }
            $c = ucfirst($routes[$slug]['controller']); // Inutile le ucfirst
            var_dump($c);
            $a = $routes[$slug]['action'].'Action';
            $cPath = 'src/Controller/'.$c.'.php';
        } else {
            return ['c' => null, 'a' => null, 'cPath' => null];
        }

        return ['c' => $c, 'a' => $a, 'cPath' => $cPath];
    }

    public static function getSlug($c, $a)
    {
        $routes = yaml_parse_file(self::$routeFile);

        foreach ($routes as $slug => $cAndA) {
            if (!empty($cAndA['controller']) &&
                !empty($cAndA['action']) &&
                $cAndA['controller'] == $c &&
                $cAndA['action'] == $a) {
                return $slug;
            }
        }

        return null;
    }
}
