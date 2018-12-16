<?php
/**
 * PHP version 7.2
 * upm_project - APIUserController.php
 *
 * @author   Freddy Tandazo <freddy.tandazo.yanez@alumnos.upm.es>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://www.etsisi.upm.es ETS de Ingeniería de Sistemas Informáticos
 * Date: 05/12/2018
 * Time: 10:29
 */

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class APIUserController
 *
 * @package App\Controller
 *
 * @Route(path=APIUserController::USER_API_PATH, name="api_user_")
 */
class APIUserController extends AbstractController
{
    // Ruta API User
    public const USER_API_PATH = '/api/v1/user';

    /**
     * @Route(path="", name="getc", methods={ Request::METHOD_GET } )
     * @return Response
     */
    public function getCUsers(): Response
    {
        $em = $this->getDoctrine()->getManager();
        /** @var User[] $users */
        $users = $em->getRepository(User::class)->findAll();

        return (null === $users)
            ? $this->error404()
            : new JsonResponse([ 'users' => $users ]);
    }

    /**
     * @Route(path="/{id}", name="get", methods={ Request::METHOD_GET } )
     * @param User|null $user
     * @return Response
     */
    public function getUser(?User $user = null): Response
    {
        // $user = $this->getDoctrine()->getManager()->find(User::class, $dni);

        return (null === $user)
            ? $this->error404()
            : new JsonResponse([ 'user' => $user ]);
    }

    /**
     * @Route(path="", name="post", methods={ Request::METHOD_POST })
     * @param Request $request
     * @return Response
     */
    public function postUser(Request $request): Response
    {
        $datosPeticion = $request->getContent();
        $datos = json_decode($datosPeticion, true);

        // No envia DNI: 422
        if (!array_key_exists('id', $datos)) {
            return $this->error422();
        }

        // El DNI ya existe: 400
        if ($this->getDoctrine()->getManager()->find(User::class, $datos['id'])) {
            return $this->error400();
        }

        /** @var User $user */
        $user = new User(
            $datos['username'] ?? null,
            $datos['email'] ?? null,
            $datos['password'] ?? null

        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse(
            [ 'user' => $user ],
            Response::HTTP_CREATED
        );
    }

    /**
     * @Route(path="", name="put", methods={ Request::METHOD_PUT })
     * @param Request $request
     * @return Response
     */
    public function putUser(Request $request): Response
    {
        $datosPeticion = $request->getContent();
        $datos = json_decode($datosPeticion, true);
        echo "ssss";
        // No envia DNI: 422
        if (!array_key_exists('id', $datos)) {
            return $this->error422();
        }
        echo "mmmmmmmmm";
        if ($this->getDoctrine()->getManager()->find(User::class, $datos['id'])) {
            /** @var User $user */
            $user = new User(

                $datos['username'] ?? null,
                $datos['email'] ?? null,
                $datos['password'] ?? null

            );

            $em = $this->getDoctrine()->getManager();
            $em->merge($user);
            $em->flush();

            return new JsonResponse(
                [ 'user' => $user ],
                Response::HTTP_CREATED
            );
        }
        else{
            return new JsonResponse(
                null,
                Response::HTTP_BAD_REQUEST
            );
        }


    }

    /**
     * @Route(path="/{id}", name="delete", methods={ Request::METHOD_DELETE } )
     * @param User|null $user
     * @return Response
     */
    public function deleteUser(?User $user = null): Response
    {
        // No existe
        if (null === $user) {
            return $this->error404();
        }

        // Existe -> eliminar y devolver 204
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
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
