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
    public function get($route, $closure) {
        global $routes;
        $routes['get'][] = ['route' => "/api".$route, 'closure' => $closure];
    }

    public function post($route, $closure) {
        global $routes;
        $routes['post'][] = ['route' => "/api".$route, 'closure' => $closure];
    }

    public function put($route, $closure) {
        global $routes;
        $routes['put'][] = ['route' => "/api".$route, 'closure' => $closure];
    }

    public function delete($route, $closure) {
        global $routes;
        $routes['delete'][] = ['route' => "/api".$route, 'closure' => $closure];
    }
}