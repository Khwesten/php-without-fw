<?php

namespace Utils;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 26/04/2017
 * Time: 19:30
 */
class Application
{
    function __construct()
    {
        global $routes;

        $routes = [
            "get" => [],
            "post" => [],
            "put" => [],
            "delete" => []
        ];
    }

    public function start()
    {
        $this->setHandlerCustomException();
        $this->checkConfig();

        $this->getRoute();
    }

    public function getRoute()
    {
        $verbMethod = $this->getMethodVerb();

        global $routes;

        $uri = $this->getMainUri();

        $routesByMethod = $routes[$verbMethod];

        $this->searchRoute($uri, $routesByMethod);

        throw new CustomException(HttpStatusCode::NOT_FOUND, MessageConstants::ROUTE_NOT_FOUND);
    }

    public function handlerCustomException(\Utils\CustomException $exception)
    {
        http_response_code($exception->getCode());

        if (!headers_sent()) {
            header('Content-Type: application/json');
        }

        echo $exception->toJson();
    }

    private function checkConfig()
    {
        $configFile = dirname(__FILE__) . "../../config/config.ini";

        if (!file_exists($configFile)) {
            throw new CustomException(500, "Create a config.ini file in '/config/'. Use '/config/model.config.ini' as parameter");
        }
    }

    private function setHandlerCustomException()
    {
        set_exception_handler(array($this, "handlerCustomException"));
    }

    private function getMethodVerb()
    {
        return strtolower($_SERVER["REQUEST_METHOD"]);
    }

    private function getMainUri()
    {
        return explode('?', $_SERVER['REQUEST_URI'])[0];
    }

    private function searchRoute(string $uri, array $routes)
    {
        foreach ($routes as $key => $value) {
            if ($uri == $value['route']) {
                $closure = $value['closure'];
                $closure->__invoke();
            }
        }
    }

}