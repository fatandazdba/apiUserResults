<?php
/**
 * PHP version 7.2
 * apiUserResults  - APIUserController.php
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
    public const USER_API_PATH = '/api/v1/users';

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
     * @Route(path="/username/{username}", name="get_user_by_username", methods={Request::METHOD_GET})
     * @param $username
     * @return JsonResponse
     */
    public function getUniqueUserByUsername($username){

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(["username" => $username]);


        return (null == $username)
            ? $this->error404()
            : new JsonResponse(["user" => $user], Response::HTTP_OK);
    }

    /**
     * @Route(path="", name="post", methods={Request::METHOD_POST})
     * @param Request $request
     * @return JsonResponse
     */
    public function postUser(Request $request): JsonResponse
    {

        $datosPeticion = $request->getContent();
        $datos = json_decode($datosPeticion, true);

        //check if required data exist
        if (empty($datos['username']) || empty($datos['email'])) {
            return $this->error422();
        }

        $dbUser = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['username' => $datos['username']]);
        $dbEmail = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneBy(['email' => $datos['email']]);

        //check if user exist
        if ($dbUser || $dbEmail) {
            return $this->error422();
        }

        $user = new User(
            $datos['username'],
            $datos['email'],
            $datos['password'],
            $datos['enabled']
        );

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse(
            ['user' => $user],
            Response::HTTP_CREATED
        );// created 201

    }

    /**
     * @Route(path="/{id}", name="update_user", methods={ Request::METHOD_PUT })
     * @param Request $request
     * @param User|null $user
     * @return JsonResponse
     */
    public function updateUser(Request $request, User $user = null): JsonResponse
    {

        if (null == $user) {
            return $this->error404();
        }


        $datosPeticion = $request->getContent();
        $datos = json_decode($datosPeticion, true);


        if (empty($datos)) {
            return $this->error400();
        }

        if (isset($datos['username'])) {
            $user->setUsername($datos['username']);
        }

        if (isset($datos['email'])) {
            $user->setEmail($datos['email']);
        }

        if (isset($datos['password'])) {
            $user->setPassword($datos['password']);
        }

        if (isset($datos['enable'])) {
            $user->setEnabled($datos['enable']);
        }

        $em = $this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();

        return new JsonResponse(
            ['user' => $user],
            Response::HTTP_ACCEPTED
        ); // accepted 202
    }
    /**
     * @Route(path="/{id}", name="delete_user", methods={Request::METHOD_DELETE })
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
     * @Route(path="", name="options_users", methods={ Request::METHOD_OPTIONS })
     * @return Response
     * @codeCoverageIgnore
     */
    public function optionsUsers(): Response
    {
        /** @var array $options */
        $options = "GET,POST";
        return new JsonResponse([],Response::HTTP_OK ,["Allow" => $options]);
    }


    /**
     * @Route(path="/{id}", name="options_user_unique", methods={ Request::METHOD_OPTIONS })
     * @return Response
     * @codeCoverageIgnore
     */
    public function optionsUser(): Response
    {
        /** @var array $options */
        $options="GET,UPDATE, DELETE";
        return new JsonResponse([],Response::HTTP_OK ,["Allow" => $options]);
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
