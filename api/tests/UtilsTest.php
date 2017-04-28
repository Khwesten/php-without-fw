<?php

namespace Test;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 08/04/2017
 * Time: 02:36
 */
class UtilsTest
{
    public static function getJson($path)
    {
        $json = file_get_contents($path);
        $json = '[' . str_replace('}{', '},{', $json) . ']';
        return json_decode($json, true)[0];
    }
}