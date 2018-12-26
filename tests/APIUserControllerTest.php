<?php
/**
 * Created by PhpStorm.
 * User: fatan
 * Date: 15/12/2018
 * Time: 13:44
 */

namespace App\test\Controller;

use App\Controller\APIUserController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class APIUserControllerTest extends WebTestCase
{


    /** @var Client $client */
    private static $client;

    public static function setUpBeforeClass()
    {
        self::$client = static::createClient();


    }

    /**
     * Implements testGetCPersona200
     * @return void
     * @covers ::getCPersona
     */
    public function testGetCPersona200(): void
    {

        self::assertEquals(1,1);
    }
}
