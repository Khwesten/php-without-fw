<?php

namespace Model;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 26/04/2017
 * Time: 17:31
 */
class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    private $nif;
    private $phone;
    private $address;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNif()
    {
        return $this->nif;
    }

    /**
     * @param mixed $nif
     * @return User
     */
    public function setNif($nif)
    {
        $this->nif = $nif;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress(): Address
    {
        if (!$this->address) {
            $addressModel = new AddressDao();
            $this->address = $addressModel->getByUserId($this->id);
        }

        return $this->address;
    }

    /**
     * @param mixed $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function hydrate($viewObject)
    {
        $this->name = $viewObject->name;
        $this->email = $viewObject->email;
        $this->password = password_hash($viewObject->password, PASSWORD_DEFAULT);

        if ($viewObject->nif) {
            $this->nif = $viewObject->nif;
        }

        if ($viewObject->phone) {
            $this->phone = $viewObject->phone;
        }

        $viewObject->street = $viewObject->street ?? null;
        $viewObject->zipCode = $viewObject->zipCode ?? null;
        $viewObject->place = $viewObject->place ?? null;
        $viewObject->country = $viewObject->country ?? null;

        if ($viewObject->street || $viewObject->zipCode || $viewObject->place || $viewObject->country) {
            $address = new Address();
            $address->hydrate($viewObject);

            $this->address = $address;
        }
    }

    public function toArray()
    {
        $obj = [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "nif" => $this->nif,
            "phone" => $this->phone,
        ];

        return $obj;
    }
}