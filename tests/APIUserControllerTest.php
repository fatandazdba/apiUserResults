<?php
/**
 * PHP version 7.2
 * apiUserResults - APIUserControllerTest.php
 *
 * @author   Freddy Tandazo <freddy.tandazo.yanez@alumnos.upm.es>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
 * Date: 05/12/2018
 * Time: 10:29
 */

namespace App\test\Controller;

use App\Controller\APIUserController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class APIUserControllerTest
 *
 * @package App\Tests\Controller
 * @coversDefaultClass \App\Controller\APIUserController
 */
class APIUserControllerTest extends WebTestCase
{
    /** @var Client $client */
    private static $client;

    public static function setUpBeforeClass()
    {
        self::$client = static::createClient();
    }

    /**
     * Implements testGetcUsers200
     * @covers ::getcUsers
     */
    public function testGetcUsers200()
    {

        self::$client->request(
            Request::METHOD_GET,
            APIUserController::USER_API_PATH
        );
        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertJson($response->getContent());
        $datosRecibidos = json_decode($response->getContent(), true);
        self::assertArrayHasKey('users', $datosRecibidos);
    }

    /**
     * Implements testGetcUser400
     * @covers ::getcUsers
     */
    public function testGetcUser404()
    {
        self::$client->request(
            request::METHOD_GET,
            APIUserController::USER_API_PATH . "/us"
        );
        /** @var Response $response */
        $response = self::$client->getResponse();
        $data = json_decode($response->getContent(), true);
        self::assertEquals($data['code'], $response->getStatusCode());

    }


    /**
     * @dataProvider providerId
     * @covers ::getUserUnique
     * @param $id
     * @return int
     */
    public function testGetUniqueUser($id): int
    {

        self::$client->request(
            request::METHOD_GET,
            APIUserController::USER_API_PATH . "/" . $id
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_OK, $response->getStatusCode()
        );

        self::assertJson($response->getContent());
        $dataResponse = json_decode($response->getContent(), true);
        self::assertArrayHasKey('user', $dataResponse);
        self::assertArrayHasKey('username', $dataResponse['user']);
        return $id;
    }

    /**
     * @dataProvider providerExistUser
     *@covers ::getUniqueUserByUsername
     */
    public function testGetUserByUsername($data)
    {
        $username = $data['username'];
        self::$client->request(
            request::METHOD_GET,
            APIUserController::USER_API_PATH . "/username/" . $username
        );

        /**@var Response $response*/
        $response = self::$client->getResponse();
        self::assertJson($response->getContent());
        $data = json_decode($response->getContent(), true);
        self::assertArrayHasKey("user", $data);
        self::assertEquals("200", $response->getStatusCode());
    }

    /**
     * @dataProvider providerDuplicateUser
     * @covers ::postUser
     */
    public function testPostUserDuplicate($data)
    {
        self::$client->request(
            Request::METHOD_POST,
            APIUserController::USER_API_PATH,
            [], [], [], json_encode($data)
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(422, $response->getStatusCode());
    }

    /**
     * @depends      testGetUniqueUser
     * @dataProvider providerUser
     * @param $data
     * @return int $id
     */
    public function testPostUser201($data)
    {
        self::$client->request(
            Request::METHOD_POST,
            APIUserController::USER_API_PATH,
            [], [], [], json_encode($data)
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_CREATED,
            $response->getStatusCode()
        );

    }


    /**
     * @covers ::deleteUser
     */
    public function testDeleteUser204()
    {
        self::$client->request(
            Request::METHOD_DELETE,
            APIUserController::USER_API_PATH . "/" . 12 // Comprobar en la base
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());

    }

    /**
     * @depends providerId
     * @param $id
     */
    public function testDeleteUser404($id)
    {
        self::$client->request(
            Request::METHOD_DELETE,
            APIUserController::USER_API_PATH . "/" . $id
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }


    public function providerUser()
    {
        return [
                [
                    [
                        'username' => 'freddy' . rand(1, 5641414),
                        'email' => 'freddy' . rand(1, 5641414) . '@gmail.com',
                        'password' => 'freddy',
                        'enabled' => 'true'
                    ]
                ]
        ];

    }

    public function providerId()
    {
        return [
            "iD" => ['1']
        ];
    }

    public function providerUserUpdates()
    {
        return [
                [
                    [
                        'email' => "freddy5456456456@gmail.com"
                    ]
                ]
        ];
    }

    public function providerDuplicateUser()
    {
     return [
                [
                    [
                        "username" => "freddy_870",
                        "email" => "freddy@gmail.com_870",
                        "password" => "$2y$10\$tqKfDAaC9sIUA6mporYaq.gEYV15C6c5milXQL9FURqdUHnbVtbjm",
                        "enable" => "false"
                    ]
                ]
     ];
    }

    public function providerExistUser()
    {
        return [
                [
                    [
                        "username" => "freddy",
                        "email" => "freddy@gmail.com",
                        "password" => "1234",
                        "enable" => "false"
                    ]
                ]
        ];
    }
}

