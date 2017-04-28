<?php

namespace Test;

use Controller\User;
use Utils\CustomException;
use Utils\HttpStatusCode;
use Utils\MessageConstants;

include __DIR__ . '/../vendor/autoload.php';

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 28/04/2017
 * Time: 11:47
 */
class UserTest extends \PHPUnit\Framework\TestCase
{
    protected $userController;

    public function setUp()
    {
        $_POST = UtilsTest::getJson("data/userToInsert.json");

        $this->userController = ($this->userController) ?? new User();
    }

    public function tearDown()
    {
        $_POST = null;
        $_GET = null;
    }

    public function testInsertUserWithOutName()
    {
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage(MessageConstants::THE_NAME_CANT_BE_EMPTY);
        $this->expectExceptionCode(HttpStatusCode::BAD_REQUEST);

        unset($_POST["name"]);

        $this->userController->register();
    }

    public function testInsertUserWithOutEmail()
    {
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage(MessageConstants::THE_EMAIL_CANT_BE_EMPTY);
        $this->expectExceptionCode(HttpStatusCode::BAD_REQUEST);

        unset($_POST["email"]);

        $this->userController->register();
    }

    public function testInsertUserWithOutPassword()
    {
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage(MessageConstants::THE_PASSWORD_CANT_BE_EMPTY);
        $this->expectExceptionCode(HttpStatusCode::BAD_REQUEST);

        unset($_POST["password"]);

        $this->userController->register();
    }

    public function testInsertUserWithPasswordLessThan8()
    {
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage(MessageConstants::THE_PASSWORD_NEED_BE_HAVE_A_MINIMUM_8_CHARACTER);
        $this->expectExceptionCode(HttpStatusCode::BAD_REQUEST);

        $_POST["password"] = "123123";

        $this->userController->register();
    }

    public function testInsertUserWithEmailDifferentFromConfirmEmail()
    {
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage(MessageConstants::THE_CONFIRM_EMAIL_VALUE_IS_DIFFERENT_FROM_EMAIL);
        $this->expectExceptionCode(HttpStatusCode::BAD_REQUEST);

        $_POST["email"] = "email@email.com";

        $this->userController->register();
    }

    public function testInsertUserWithPasswordDifferentFromConfirmPassword()
    {
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage(MessageConstants::THE_CONFIRM_PASSWORD_VALUE_IS_DIFFERENTE_FROM_PASSWORD);
        $this->expectExceptionCode(HttpStatusCode::BAD_REQUEST);

        $_POST["password"] = "different";

        $this->userController->register();
    }

    public function testInsertUserWithNifWithSizeEqualTo9AndLetterOnMiddle()
    {
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage(MessageConstants::THE_NIF_MUST_HAVE_9_NUMBER);
        $this->expectExceptionCode(HttpStatusCode::BAD_REQUEST);

        $_POST["nif"] = "1111I1111";

        $this->userController->register();
    }

    public function testInsertUserWithZipCodeOnWrongFormat()
    {
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage(MessageConstants::THE_ZIP_CODE_MUST_BE_HAVE_THE_FORMAT_EUQAL_TO_0000_HYPHEN_000);
        $this->expectExceptionCode(HttpStatusCode::BAD_REQUEST);

        $_POST["zipCode"] = "321-3121";

        $this->userController->register();
    }

    public function testInsertUserWithWeakPassword()
    {
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage(MessageConstants::THE_PASSWORD_MUST_HAVE_3_LETTERS_1_CAPITAL_2_NUMBER_AND_2_SPECIAL_CHAR);
        $this->expectExceptionCode(HttpStatusCode::BAD_REQUEST);

        $this->userController->register();
    }

    public function testGetEmailWithNonExistentEmail()
    {
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage(MessageConstants::DON_T_EXIST_USER_WITH_THIS_EMAIL);
        $this->expectExceptionCode(HttpStatusCode::NOT_FOUND);

        $_GET["email"] = "nonexistent@email.com";

        $this->userController->getByEmail();
    }
}