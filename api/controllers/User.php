<?php

namespace Controller;
use Dao\AddressDao;
use Dao\UserDao;
use Utils\CustomException;
use Utils\HttpStatusCode;
use Utils\MessageConstants;
use Utils\Response;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 26/04/2017
 * Time: 19:21
 */
class User
{
    private $viewObject;
    private $userDao;

    const REPRESENTATION_COUNTRY_PORTUGAL = "POR";

    function __construct()
    {
        $this->userDao = $userDao = new UserDao();
    }

    public function register() {
        $this->viewObject = (object) $_POST;

        $this->checkFields();
        $this->checkEmailExist();
        $this->isStrongPassword();
        $result = $this->insertUserWithAddress();

        if ($result) {
            throw new CustomException(HttpStatusCode::OK, MessageConstants::CREATED_USER_SUCCESSFUL);
        } else {
            throw new CustomException(HttpStatusCode::BAD_REQUEST, MessageConstants::COULDN_T_CREATE_USER);
        }
    }

    public function getByEmail() {
        $requestBody = (object) $_GET;

        $requestBody->email = $requestBody->email ?? false;

        if ($requestBody->email) {
            $userDao = new UserDao();
            $user = $userDao->getByEmail($requestBody->email);

            if (!$user) {
                throw new CustomException(HttpStatusCode::NOT_FOUND,
                    MessageConstants::DON_T_EXIST_USER_WITH_THIS_EMAIL);
            }

            throw new CustomException(
                HttpStatusCode::OK, MessageConstants::ALREADY_EXIST_AN_USER_WITH_THIS_EMAIL, $user->toArray()
            );
        } else {
            throw new CustomException(HttpStatusCode::BAD_REQUEST, MessageConstants::THE_EMAIL_CANT_BE_EMPTY);
        }
    }

    private function checkFields()
    {
        $viewObject = $this->viewObject;

        $viewObject->email = $viewObject->email ?? false;
        $viewObject->confirmEmail = $viewObject->confirmEmail ?? false;
        $viewObject->password = $viewObject->password ?? false;
        $viewObject->confirmPassword = $viewObject->confirmPassword ?? false;
        $viewObject->name = $viewObject->name ?? false;

        $viewObject->nif = $viewObject->nif ?? false;
        $viewObject->zipCode = $viewObject->zipCode ?? false;
        $viewObject->country = $viewObject->country ?? false;
        $viewObject->phone = $viewObject->phone ?? false;

        if (!$viewObject->name) {
            throw new CustomException(HttpStatusCode::BAD_REQUEST, MessageConstants::THE_NAME_CANT_BE_EMPTY);
        }

        if (!$viewObject->email) {
            throw new CustomException(HttpStatusCode::BAD_REQUEST, MessageConstants::THE_EMAIL_CANT_BE_EMPTY);
        }

        if (!$viewObject->password) {
            throw new CustomException(HttpStatusCode::BAD_REQUEST, MessageConstants::THE_PASSWORD_CANT_BE_EMPTY);
        }

        if (strlen($viewObject->password) < 8) {
            throw new CustomException(
                HttpStatusCode::BAD_REQUEST, MessageConstants::THE_PASSWORD_NEED_BE_HAVE_A_MINIMUM_8_CHARACTER
            );
        }

        if ($viewObject->email != $viewObject->confirmEmail) {
            throw new CustomException(
                HttpStatusCode::BAD_REQUEST, MessageConstants::THE_CONFIRM_EMAIL_VALUE_IS_DIFFERENT_FROM_EMAIL
            );
        }

        if ($viewObject->password != $viewObject->confirmPassword) {
            throw new CustomException(
                HttpStatusCode::BAD_REQUEST, MessageConstants::THE_CONFIRM_PASSWORD_VALUE_IS_DIFFERENTE_FROM_PASSWORD
            );
        }

        if ($viewObject->nif) {
            $nifRegex = '/^\d{9}$/';

            $hasRightFormat = $this->hasRegex($viewObject->nif, $nifRegex);

            if (!$hasRightFormat) {
                throw new CustomException(HttpStatusCode::BAD_REQUEST, MessageConstants::THE_NIF_MUST_HAVE_9_NUMBER);
            }
        }

        if ($viewObject->zipCode) {
            $zipCodeRegex = '/^\d{4}\-{1}\d{3}$/';

            $hasRightFormat = $this->hasRegex($viewObject->zipCode, $zipCodeRegex);

            if (!$hasRightFormat) {
                throw new CustomException(
                    HttpStatusCode::BAD_REQUEST,
                    MessageConstants::THE_ZIP_CODE_MUST_BE_HAVE_THE_FORMAT_EUQAL_TO_0000_HYPHEN_000
                );
            }
        }

        if ($viewObject->phone && $viewObject->country == self::REPRESENTATION_COUNTRY_PORTUGAL) {
            $portugalPhoneNumberRegex = '/\d{2}\ {1}\d{3}\ {1}\d{4}/';

            $hasRightFormat = $this->hasRegex($this->viewObject->password, $portugalPhoneNumberRegex);

            if (!$hasRightFormat) {
                throw new CustomException(
                    HttpStatusCode::BAD_REQUEST,
                    MessageConstants::THE_PHONE_NUMBER_MUST_BE_HAVE_THE_FORMAT_EQUAL_TO_00_SPACE_000_SPACE_0000
                );
            }
        }
    }

    private function isStrongPassword()
    {
        $twoTinyCharRegex = '/[a-zA-Z]{2,}/';
        $twoNumberRegex = '/\d{2,}/';
        $twoEspecialCharRegex = '/\W{2,}/';
        $oneCapitalCharRegex = '/[A-Z]{1,}/';

        $hasTwoTinyChar = $this->hasRegex($this->viewObject->password, $twoTinyCharRegex);
        $hasTwoNumber = $this->hasRegex($this->viewObject->password, $twoNumberRegex);
        $hasTwoEspecialChar = $this->hasRegex($this->viewObject->password, $twoEspecialCharRegex);
        $hasOneCapitalChar = $this->hasRegex($this->viewObject->password, $oneCapitalCharRegex);

        if (!$hasTwoTinyChar || !$hasTwoNumber || !$hasTwoEspecialChar || !$hasOneCapitalChar) {
            throw new CustomException(
                HttpStatusCode::BAD_REQUEST,
                MessageConstants::THE_PASSWORD_MUST_HAVE_3_LETTERS_1_CAPITAL_2_NUMBER_AND_2_SPECIAL_CHAR
            );
        }
    }

    private function hasRegex(string $string, string $regex) : bool
    {
        preg_match_all($regex, $string, $matches, PREG_SET_ORDER, 0);

        $value = ($matches) ? true : false;

        return $value;
    }

    private function checkEmailExist()
    {
        $result = $this->userDao->getByEmail($this->viewObject->email);

        if ($result) {
            throw new CustomException(HttpStatusCode::OK, MessageConstants::ALREADY_EXIST_AN_USER_WITH_THIS_EMAIL);
        }
    }

    private function insertUserWithAddress(): bool
    {
        $userModel = new \Model\User();
        $userModel->hydrate($this->viewObject);

        $userId = $this->userDao->create($userModel);

        $userModel->getAddress()->setUserId($userId);

        $addressId = true;

        if ($userModel->getAddress()) {
            $addressDao = new AddressDao();
            $addressId = $addressDao->create($userModel->getAddress());
        }

        $result = $userId && $addressId;

        return $result;
    }
}