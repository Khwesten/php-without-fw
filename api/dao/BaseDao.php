<?php

namespace Dao;

use Utils\ConnectionFactory;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 27/04/2017
 * Time: 07:55
 */
class BaseDao extends ConnectionFactory
{
    protected $connection;

    function __construct()
    {
        $connectionFactory = new ConnectionFactory();
        $this->connection = $connectionFactory->get();
    }
}