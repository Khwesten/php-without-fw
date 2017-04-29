<?php

namespace Utils;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 28/04/2017
 * Time: 13:44
 */
class Route
{
    public static function get($route, $closure) {
        global $routes;
        $routes['get'][] = ['route' => "/api".$route, 'closure' => $closure];
    }

    public static function post($route, $closure) {
        global $routes;
        $routes['post'][] = ['route' => "/api".$route, 'closure' => $closure];
    }

    public static function put($route, $closure) {
        global $routes;
        $routes['put'][] = ['route' => "/api".$route, 'closure' => $closure];
    }

    public static function delete($route, $closure) {
        global $routes;
        $routes['delete'][] = ['route' => "/api".$route, 'closure' => $closure];
    }
}