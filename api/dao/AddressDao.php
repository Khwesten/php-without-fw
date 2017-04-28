<?php

namespace Dao;

use Model\Address;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 27/04/2017
 * Time: 18:46
 */
class AddressDao extends BaseDao implements IAddressDao
{
    const MODEL_USER_NAMESPACE = "\\Model\\Address";

    public function getByUserId(int $userId)
    {
        $statement = $this->connection->prepare("SELECT * FROM address WHERE userId = :userId");

        $statement->execute(array(":userId" => $userId));

        $statement->setFetchMode(\PDO::FETCH_CLASS, self::MODEL_ADDRESS_NAMESPACE);
        $address = $statement->fetch();

        return $address;
    }

    public function create(Address $addressModel): int
    {
        $lastInsertId = 0;

        try {
            $this->connection->beginTransaction();

            $statement = $this->connection->prepare(
                "INSERT into address (userId, street, zipCode, place, country) 
                VALUES(:userId, :street, :zipCode, :place, :country)"
            );

            $userId = $addressModel->getUserId();
            $street = $addressModel->getStreet();
            $zipCode = $addressModel->getZipCode();
            $place = $addressModel->getPlace();
            $country = $addressModel->getCountry();

            $statement->bindParam(":userId", $userId, \PDO::PARAM_INT);
            $statement->bindParam(":street", $street, \PDO::PARAM_STR);
            $statement->bindParam(":zipCode", $zipCode, \PDO::PARAM_STR);
            $statement->bindParam(":place", $place, \PDO::PARAM_STR);
            $statement->bindParam(":country", $country, \PDO::PARAM_STR);

            $statement->execute();

            $lastInsertId = $this->connection->lastInsertId();

            $this->connection->commit();
        } catch (PDOExecption $e) {
            $this->connection->rollBack();
            throw new CustomException(500, $e->getMessage());
        }

        return $lastInsertId;
    }
}