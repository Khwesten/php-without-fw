<?php

namespace Controller;

use Dao\DatabaseDao;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 27/04/2017
 * Time: 08:14
 */
class Database
{
    public function create() {
        $dbDao = new DatabaseDao();
        $dbDao->create();
    }
}