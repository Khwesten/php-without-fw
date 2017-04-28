<?php

namespace Dao;

use Model\User;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 27/04/2017
 * Time: 07:55
 */
interface IUserDao
{
    public function create(User $userModel): int;
    public function getByEmail(string $email);
}