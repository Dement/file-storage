<?php

namespace Modules\AuthBundle\Security;

use BaseClasses\BaseEntityManager;
use BaseExceptions\ApiException;
use Modules\AuthBundle\Repository\{
    UserRepository,
    UserTokenRepository
};
use Symfony\Component\HttpFoundation\{
    JsonResponse,
    Request
};
use Symfony\Component\Security\{
    Core\Authentication\Token\TokenInterface,
    Core\Exception\AuthenticationException,
    Core\User\UserInterface,
    Core\User\UserProviderInterface,
    Guard\AbstractGuardAuthenticator
};
use Traits\ContainerAwareTrait;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator {

    use ContainerAwareTrait;

    private $em;

    public function __construct(BaseEntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Called on every request. Return whatever credentials you want,
     * or null to stop authentication.
     */
    public function getCredentials(Request $request)
    {
        if(self::getContainer()->getParameter('dev_mode') && strpos($request->getRequestUri(), '/_profiler/') !== false) {
            return;
        }

        /** @var ApiTokenManager $tokenManager */
        $tokenManager = self::getContainer()->get('api.token_manager');
        $token = $tokenManager->getCurrentToken();

        if (!$token || !$this->isValidToken($token)) {
            throw new ApiException('auth_token', 401);
        }

        // What you return here will be passed to getUser() as $credentials
        return array(
            'token' => $token,
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $credentials['token'];

        // if null, authentication will fail
        // if a User object, checkCredentials() is called
        $user = UserRepository::get()->getByToken($token);
        if($user->isLocked()) {
            throw new ApiException('user_is_blocked', 401);
        }

        self::getContainer()->get('current_user')->setUser($user);
        
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case

        // return true to cause authentication success
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
//        $data = array(
//            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
//
//            // or to translate this message
//            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
//        );
//
//        return new JsonResponse($data, 403);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = array(
            // you might translate this message
            'message' => 'Authentication Required'
        );

        return new JsonResponse($data, 401);
    }

    public function supportsRememberMe()
    {
        return false;
    }

    private function isValidToken($token) {
        $tokenEntity = UserTokenRepository::get()->getByToken($token);

        if(is_null($tokenEntity) || !$tokenEntity->getActivation() || $tokenEntity->getExpiredTime() < time()) {
            return false;
        }

        if(!$tokenEntity->getUser()->getActivation()) {
            return false;
        }

        return true;
    }

}