<?php
/**
 * PHP version 7.2
 * apiUserResults - APIResultControllerTest.php
 *
 * @author   Freddy Tandazo <freddy.tandazo.yanez@alumnos.upm.es>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
 * Date: 05/12/2018
 * Time: 10:29
 */

namespace App\test\Controller;

use App\Controller\ApiResultController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiPersonaControllerTest
 *
 * @package App\Tests\Controller
 * @coversDefaultClass \App\Controller\ApiResultController
 */
class ApiResultControllerTest extends WebTestCase
{
    /** @var Client $client */
    private static $client;

    public static function setUpBeforeClass()
    {
        self::$client = static::createClient();
    }

    /**
     * @covers ::getUniqueResult
     */
    public function testGetUniqueResult()
    {
        self::$client->request(
            Request::METHOD_GET,
            ApiResultController::RESULT_API_PATH . "/" . 2 // Comprobar en la base
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertJson($response->getContent());
        self::assertEquals(
            Response::HTTP_OK, $response->getStatusCode()
        );
        $data = json_decode($response->getContent(), true);
        self::assertArrayHasKey('time', $data['result']);
    }

    /**
     * @covers ::getUniqueResult
     */
    public function testGetUniqueResult404()
    {
        self::$client->request(
            Request::METHOD_GET,
            ApiResultController::RESULT_API_PATH . "/" . 73 //Comprobar en la base
        );

        /**
         * @var Response $response
         */

        $response = self::$client->getResponse();
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }


    /**
     * @dataProvider providerExistUserId
     * @covers ::getResultsByUser
     */
    public function testGetResultsByUserId($data)
    {
        $user_id = $data['user'];
        self::$client->request(
            Request::METHOD_GET,
            ApiResultController::RESULT_API_PATH . '/user/' . $user_id
        );
        /**
         * @var Response $response
         */

        $response = self::$client->getResponse();
        self::assertJson($response->getContent());
        $data = json_decode($response->getContent(), true);
        self::assertEquals('200', $response->getStatusCode());
    }

    /**
     * @dataProvider providerResult
     * @covers ::postResult
     * @param $data
     */
    public function testPostResult($data)
    {
        $entrydata = $data;
        self::$client->request(
            Request::METHOD_POST,
            ApiResultController::RESULT_API_PATH,
            [], [], [], json_encode($data)
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_CREATED,
            $response->getStatuscode()
        );

        self::assertJson($response->getContent());
        $data = json_decode($response->getContent(), true);
        self::assertArrayHasKey('result', $data);
        self::assertArrayHasKey('user', $data['result']);
    }

    /**
     * @dataProvider providerFakeUserId
     * @param $data
     */
    public function testPostUserDontExist($data)
    {
        self::$client->request(
            Request::METHOD_POST,
            ApiResultController::RESULT_API_PATH,
            [], [], [], json_encode($data)
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        self::assertJson($response->getContent());
        $data = json_decode($response->getContent(), true);
        self::assertArrayNotHasKey('result', $data);

    }

    /**
     * @dataProvider providerIncompleteParams
     * @covers ::postResult
     */
    public function testPostResultIncompleteParams($data)
    {
        self::$client->request(
            Request::METHOD_POST,
            ApiResultController::RESULT_API_PATH,
            [], [], [], json_encode($data)
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $response->getStatusCode()
        );
    }


    /**
     * @covers ::getResults
     */
    public function testGetResults()
    {
        self::$client->request(
            Request::METHOD_GET,
            ApiResultController::RESULT_API_PATH
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertJson($response->getContent());
        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        self::assertArrayHasKey('results', $data);
        self::assertArrayHasKey("user", $data['results'][6]);
    }

    /**
     * @covers ::deleteResult
     */
    public function testDeleteResult()
    {
        self::$client->request(
            Request::METHOD_DELETE,
            ApiResultController::RESULT_API_PATH . "/" . 2  //Comprobar en la base
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }


    /**
     * @dataProvider providerDataResultUpdate
     * @covers ::updateResult
     */
    public function testUpdateResult($data)
    {
        $entrydata = $data;

        self::$client->request(
            Request::METHOD_PUT,
            ApiResultController::RESULT_API_PATH . "/" . 3, //Comprobar en la base
            [], [], [], json_encode($data)
        );

        /** @var Response $response */
        $response = self::$client->getResponse();
        self::assertEquals(
            Response::HTTP_ACCEPTED, $response->getStatusCode()
        );
        self::assertJson($response->getContent());
        $data = json_decode($response->getContent(), true);
        self::assertEquals($data['result']['result'], $entrydata['result']);

    }

    /**
     * @dataProvider providerFakeUserId
     * @covers ::updateResult
     * @param $id
     */
    public function testUpdateResultUserNotFound($data)
    {

        self::$client->request(
            Request::METHOD_PUT,
            ApiResultController::RESULT_API_PATH . "/" . 55, //Comprobar en la base
            [], [], [], json_encode($data)
        );

        /**@var Response $response */
        $response = self::$client->getResponse();
        $responseJsonToArray = json_decode($response->getContent(), true);
        self::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }


    public function providerResult()
    {
        return [
            [
                [
                    'result' => rand(0, 10e3),
                    'user_id' => 1,
                    'time' => '2018-01-01 12:12:12'
                ]
            ]
        ];

    }

    public function providerFakeUserId()
    {
        return [
            [
                [
                    'result' => '333',
                    'user_id' => rand(200, 10e3),
                    'time' => '2018-01-01 12:12:12'
                ]
            ]
        ];
    }

    public function providerIncompleteParams()
    {
        return [
            [
                [
                    'result' => null,
                    'user_id' => null,
                    'time' => '2018-12-12 20:00:00'
                ]
            ]
        ];
    }

    public function providerDataResultUpdate()
    {
        return [
            [
                [
                    'result' => 101 . rand(0, 10e2),
                    'user_id' => 5,
                    'time' => ''
                ]
            ]
        ];
    }

    public function providerExistUserId()
    {

        return [
            [
                [
                    'user' => 1
                ]
            ]
        ];
    }
}
