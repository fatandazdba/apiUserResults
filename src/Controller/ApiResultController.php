<?php
/**
 * PHP version 7.2
 * apiUserResults  - APIResultController.php
 *
 * @author   Freddy Tandazo <freddy.tandazo.yanez@alumnos.upm.es>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
 * Date: 05/12/2018
 * Time: 10:29
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\Result;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class ApiResultController
 * @package App\Controller
 * @Route(path=ApiResultController::RESULT_API_PATH, name="api_result_")
 */
class ApiResultController extends AbstractController
{
    //ruta de la api de result
    const RESULT_API_PATH='/api/v1/results';

    /**
     * @Route(path="", name="getc", methods={ Request::METHOD_GET })
     * @return Response
     */
    public function getCResult():Response{
        $em=$this->getDoctrine()->getManager();
        /**
         * @var Result[] $results
         */
        $results =$em-> getRepository(Result::class)->findAll();

        return (null=== $results)
            ? $this-> error404()
            : new JsonResponse( ['results' => $results],Response::HTTP_OK);


    }
    /**
     * @Route(path="/{id}", name="get_result",methods={Request::METHOD_GET})
     * * @param Result $result
     * @return Response
     */
    public function getResult(?Result $result=null): Response
    {
        return (null === $result)
            ? $this->error404()
            : new JsonResponse(['result' => $result], Response::HTTP_OK);

    }
    /**
     * @Route(path="/user/{user_id}")
     * @param $user_id
     * @return JsonResponse
     */
    public function getResultsByUser($user_id){

        $em = $this->getDoctrine()->getManager();
        $userResults = $em->getRepository(Result::class)->findBy(["user" => $user_id]);

        return(null == $userResults)
            ? $this->error404()
            : new JsonResponse(['user_results' => $userResults], Response::HTTP_OK);

    }

    /**
     * @Route(path="/{id}", name="delete", methods={ Request::METHOD_DELETE } )
     * @param Result|null $result
     * @return Response
     */
    public function deleteResult(?Result $result = null): Response
    {
        if (null === $result) {
            return $this->error404();
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($result);
        $em->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route(path="", name="post",methods={Request::METHOD_POST})
     * @param Request $request
     * @return Response
     */
    public function postResults(Request $request):Response{
        $datosPeticion=$request->getContent();
        $datos=json_decode($datosPeticion,true);

        if(empty($datos['result']) || empty($datos['user_id']))
        {
            return $this->error422();
        }

        /** @var User $user */
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find($datos['user_id']);

        if($user===null){
            return $this->error400();
        }

        $newTimestamp =new DateTime($datos['time']) ??new DateTime("now");
        /**
         * @var Result result
         */
        $result= new Result($datos['result'],$user,$newTimestamp);
        $em=$this->getDoctrine()->getManager();
        $em ->persist($result);
        $em->flush();
        return new JsonResponse(
            ["result" => $result],
            Response::HTTP_CREATED
        );

    }

    /**
     * @Route(path="/{id}", name="put", methods={Request::METHOD_PUT})
     * @param Result|null $result
     * @param Request $request
     * @return Response
     */
    public function putResult(?Result $result=null, Request $request):Response{

        if (null === $result) {
            return $this->error404();
        }
        $datosPeticion = $request->getContent();
        $datos=json_decode($datosPeticion,true);
        if(empty($datos['result']) || empty($datos['user_id']))
        {
            return $this->error422();
        }

        /** @var User $user */
        $user=$this->getDoctrine()->getManager()->getRepository(User::class)->find($datos['user_id']);

        if($user===null){
            return $this->error400();
        }

        if (isset($datos['result'])){
            $result->setResult($datos['result']);
        };

        $newTimestamp =new DateTime($datos['time']) ??new DateTime("now");

        $result->setTime($newTimestamp);
        $result->setUser($user);
        $em=$this->getDoctrine()->getManager();
        $em ->merge($result);
        $em->flush();
        return new JsonResponse(
            ["result" => $result],
            202

        );

    }
    /**
     * @Route(path="", name="options", methods={ Request::METHOD_OPTIONS })
     * @return Response
     */
    public function getCOptionsResult():Response{
        /** @var array $options */

        return new JsonResponse([],Response::HTTP_OK ,["Allow" => "GET,POST"]);
    }
    /**
     * @Route(path="/{id}", name="options_result", methods={ Request::METHOD_OPTIONS })
     * @param Result|null $result
     * @return Response
     */
    public function getOptionsResult(?Result $result = null):Response{

        if (null === $result) {
            return $this->error404();
        }
        return new JsonResponse([],Response::HTTP_OK ,["Allow" => "GET,PUT,DELETE"]);
    }


    private function error404() : JsonResponse
    {
        $mensaje=[
            'code'=> Response::HTTP_NOT_FOUND,
            'mensaje' => 'Not found resource not found'
        ];
        return new JsonResponse(
            $mensaje,
            Response::HTTP_NOT_FOUND
        );
    }

    private function error422() : JsonResponse
    {
        $mensaje=[
            'code'=> Response::HTTP_UNPROCESSABLE_ENTITY,
            'mensaje' => 'Unprocessable entity result or user_id is left out'
        ];
        return new JsonResponse(
            $mensaje,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }


    /**
     * @return JsonResponse
     *
     */
    private function error400() : JsonResponse
    {
        $mensaje=[
            'code'=> Response::HTTP_BAD_REQUEST,
            'mensaje' => 'Bad Request User do not exists'
        ];
        return new JsonResponse(
            $mensaje,
            Response::HTTP_BAD_REQUEST
        );
    }
}