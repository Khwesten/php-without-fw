<?php

namespace Dao;

use Model\User;
use Utils\HttpStatusCode;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 27/04/2017
 * Time: 07:55
 */
class UserDao extends BaseDao implements IUserDao
{
    const MODEL_USER_NAMESPACE = "\\Model\\User";

    function __construct()
    {
        parent::__construct();
    }

    public function create(User $userModel): int
    {
        $lastInsertId = 0;

        try {
            $this->connection->beginTransaction();

            $statement = $this->connection->prepare(
                "INSERT into users (name, password, email, nif, phone) VALUES(:name, :password, :email, :nif, :phone)"
            );

            $name = $userModel->getName();
            $password = $userModel->getPassword();
            $email = $userModel->getEmail();
            $nif = $userModel->getNif();
            $phone = $userModel->getPhone();

            $statement->bindParam(":name", $name, \PDO::PARAM_STR);
            $statement->bindParam(":password", $password, \PDO::PARAM_STR);
            $statement->bindParam(":email", $email, \PDO::PARAM_STR);
            $statement->bindParam(":nif", $nif, \PDO::PARAM_INT);
            $statement->bindParam(":phone", $phone, \PDO::PARAM_STR);

            $statement->execute();

            $lastInsertId = $this->connection->lastInsertId();

            $this->connection->commit();
        } catch (PDOExecption $e) {
            $this->connection->rollBack();
            throw new CustomException(HttpStatusCode::INTERNAL_SERVER_ERROR, $e->getMessage());
        }

        return $lastInsertId;
    }

    public function getByEmail(string $email)
    {
        $statement = $this->connection->prepare("SELECT * FROM users WHERE email = :email");

        $statement->execute(array(":email" => $email));

        $statement->setFetchMode(\PDO::FETCH_CLASS, self::MODEL_USER_NAMESPACE);
        $user = $statement->fetch();

        return $user;
    }
}