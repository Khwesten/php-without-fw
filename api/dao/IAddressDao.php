<?php

namespace Dao;

use Model\Address;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 27/04/2017
 * Time: 18:47
 */
interface IAddressDao
{
    public function getByUserId(int $userId);
    public function create(Address $addressModel): int;
}