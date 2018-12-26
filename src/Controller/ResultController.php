<?php
/**
 * Created by PhpStorm.
 * User: zea
 * Date: 15/12/2018
 * Time: 15:17
 */

namespace App\Controller;
use App\Entity\Result;
use App\Entity\User;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
/**
 * Class UserController
 *
 * @package App\Controller
 *
 * @Route(path=ResultController::RESULT_API_PATH, name="api_result_")
 */
class ResultController extends AbstractController
{
    const RESULT_API_PATH = "/api/v1/results";

    /**
     * @Route(path="", name="getc", methods={ Request::METHOD_GET } )
     * @return Response
     */
    public function getCResult(): Response
    {
        $em = $this->getDoctrine()->getManager();
        /** @var Result[] $results */
        $results = $em->getRepository(Result::class)->findAll();

        return (null === $results)
            ? $this->error404()
            : new JsonResponse([ 'results' => $results ]);
    }

    /**
     * @Route(path="/{id}", name="get", methods={ Request::METHOD_GET } )
     * @param Result|null $id
     * @return Response
     */
    public function getResult(?Result $id = null): Response
    {
        return (null === $id)
            ? $this->error404()
            : new JsonResponse([ 'result' => $id ]);
    }

    /**
     * @Route(path="", name="post", methods={ Request::METHOD_POST })
     * @param Request $request
     * @return Response
     */
    public function postResult(Request $request): Response
    {
        $datosPeticion = $request->getContent();
        $datos = json_decode($datosPeticion, true);

        if (!array_key_exists('user_id', $datos)) {
            return $this->error422();
        }

        $user =$this->getDoctrine()->getManager()->find(User::class, $datos['user_id']);
        if (!$user) {
            return $this->error400();
        }
        $newTimestamp =$datos['time'] ?? 'now';
        /** @var Result $result */

        $result = new Result(
            $datos['result'],
            $user,
            new DateTime($newTimestamp)
        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($result);
        $em->flush();

        return new JsonResponse(
            [ 'result' => $result ],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="/{id}", name="put", methods={ Request::METHOD_PUT })
     * @param Request $request
     * @return Response
     */
    public function putResult(?Result $Result = null, Request $request): Response
    {
        if (null === $Result) {
            return $this->error404();
        }
        $datosPeticion = $request->getContent();
        $datos = json_decode($datosPeticion, true);

        if (!array_key_exists('user_id', $datos)) {
            return $this->error422();
        }

        $user =$this->getDoctrine()->getManager()->find(User::class, $datos['user_id']);
        if (!$user) {
            return $this->error400();
        }
        $newTimestamp =$datos['time'] ?? 'now';

        $Result->setResult($datos['result']);
        $Result->setUser($user);
        $Result->setTime(new DateTime($newTimestamp));
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse(
            ['Result' => $Result],
            Response::HTTP_OK
        );
    }
    /**
     * @Route(path="/{id}", name="delete", methods={ Request::METHOD_DELETE } )
     * @param Result|null $result
     * @return Response
     */
    public function deleteResult(?Result $result = null): Response
    {
        // No existe
        if (null === $result) {
            return $this->error404();
        }

        // Existe -> eliminar y devolver 204
        $em = $this->getDoctrine()->getManager();
        $em->remove($result);
        $em->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
    /**
     * Genera una respuesta 400 - Bad Request
     * @return JsonResponse
     * @codeCoverageIgnore
     */
    private function error400(): JsonResponse
    {
        $mensaje = [
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => 'Bad Request'
        ];

        return new JsonResponse(
            $mensaje,
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * Genera una respuesta 404 - Not Found
     * @return JsonResponse
     * @codeCoverageIgnore
     */
    private function error404(): JsonResponse
    {
        $mensaje = [
            'code' => Response::HTTP_NOT_FOUND,
            'message' => 'Not Found'
        ];

        return new JsonResponse(
            $mensaje,
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Genera una respuesta 422 - Unprocessable Entity
     * @return JsonResponse
     * @codeCoverageIgnore
     */
    private function error422(): JsonResponse
    {
        $mensaje = [
            'code' => Response::HTTP_UNPROCESSABLE_ENTITY,
            'message' => 'Unprocessable Entity'
        ];

        return new JsonResponse(
            $mensaje,
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}