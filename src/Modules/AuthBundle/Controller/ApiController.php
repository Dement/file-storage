<?php

namespace Modules\AuthBundle\Controller;

use BaseClasses\{
    BaseController,
    CheckClient
};
use BaseExceptions\ValidException;
use Modules\AuthBundle\{
    Entity\UserToken,
    Repository\UserRepository,
    Security\ApiTokenManager
};
use Symfony\Component\HttpFoundation\{
    Request,
    Response
};
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends BaseController
{
    /**
     * User authorization.
     *
     * @SWG\Tag(name="Auth")
     * @Route("/v1/login", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns token",
     *     @SWG\Schema(
     *         type="object",
     *         example={
     *              "token": "token"
     *         }
     *     )
     * )
     * @SWG\Parameter(
     *     name="login",
     *     type="string",
     *     in="body",
     *     description="The field used to login",
     *     required=true
     * )
     *
     * @SWG\Parameter(
     *     name="pass",
     *     type="string",
     *     in="body",
     *     description="The field used to password",
     *    required=true
     * )
     *
     * @Rest\View
     *
     * @param Request $request
     * @return Response
     * @throws ValidException
     */
    public function loginAction(Request $request) {

        $login = $request->request->get('login');
        $pass = $request->request->get('pass');

        if(empty($login) || empty($pass)) {
            $errors = [];

            if (empty($login)) {
                $errors['login'] = ['not_null'];
            }

            if (empty($pass)) {
                $errors['pass'] = ['not_null'];
            }

            throw new ValidException($this->fieldError($errors));
        }

        $user = UserRepository::get()->getByUserName($login);
        CheckClient::modelExistenceOrNull($user);

        if ($user->getPassword() != $pass) {
            throw new ValidException($this->fieldError(['pass' => ['wrong_pass']]));
        }

        $em = $this->getDoctrine()->getManager();
        /** @var ApiTokenManager $tokenManager */
        $tokenManager = self::getContainer()->get('api.token_manager');

        $token = new UserToken();
        $token->setUser($user)
            ->setToken($tokenManager->generateToken())
            ->setActivation(true)
            ->setExpiredTime(time() + self::getContainer()->getParameter('user_token_expired_time'));

        $em->persist($token);
        $em->flush();

        return $this->jsonResponse(['token' => $token->getToken()]);
    }


    /**
     * Get an authorized user
     *
     * @SWG\Tag(name="Auth")
     * @Route("/v1/profile/current", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns profile user",
     *     @SWG\Schema(
     *         type="object",
     *         example={
     *              "id": 1,
     *              "lastName": "Pypkin",
     *              "name": "Vasa"
     *         }
     *     )
     * )
     * @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="TOKEN",
     *     description="Authorization"
     * )
     *
     * @Rest\View
     *
     * @return Response
     */
    public function currentAction() {
        $profile = $this->getCurrentProfile();
        CheckClient::modelExistenceOrNull($profile);

        return $this->jsonResponse($profile);
    }

    /**
     * The method returns an error serialize
     *
     * @param $error
     * @return string
     */
    private function fieldError($error) {
        return serialize([
            'error' => $error,
        ]);
    }
}
