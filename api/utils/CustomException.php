<?php

namespace Utils;

use Exception;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 28/04/2017
 * Time: 11:47
 */
class CustomException extends \Exception
{
    private $data;

    public function __construct($code = 0, $message = "", $data = "", Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->data = $data;
    }

    public function toJson() {
        $response = ["code" => $this->code, "message" => $this->message, "data" => $this->data];
        return json_encode($response);
    }
}