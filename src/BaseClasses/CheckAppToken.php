<?php

namespace BaseClasses;

use BaseExceptions\ApiException;
use Modules\AuthBundle\Repository\AppTokenRepository;
use Symfony\Component\{
    DependencyInjection\Container,
    HttpFoundation\Request,
    HttpKernel\Event\GetResponseEvent
};
use Traits\ContainerAwareTrait;

class CheckAppToken {

    use ContainerAwareTrait;

    /**
     * Check the correctness of the token
     */
    public function checkToken(GetResponseEvent $event)
    {
        if($this->getDomain($event->getRequest()) != 'api.' . self::getContainer()->getParameter('app.domain')) {
            return;
        }

        if(self::getContainer()->getParameter('dev_mode') && strpos($event->getRequest()->getRequestUri(), '/_profiler/') !== false) {
           return;
        }

        $appToken = $this->getAppToken($event->getRequest());

        $token = AppTokenRepository::get()->getByToken($appToken);
        if(is_null($token)) {
            throw new ApiException('app_token', 401);
        }
    }

    /**
     * Get app-token from the headers
     *
     * @param Request $request
     * @return string
     * @throws ApiException
     */
    private function getAppToken(Request $request) : string
    {
        $appToken = $request->headers->get('app-token');
        if(empty($appToken)) {
            throw new ApiException('app_token', 401);
        }

        return $appToken;
    }

    /**
     * Get the current domain
     *
     * @param Request $request
     * @return string
     */
    private function getDomain(Request $request) : string
    {
        return $request->headers->get('host');
    }
}