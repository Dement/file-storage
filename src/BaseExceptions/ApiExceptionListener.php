<?php

namespace BaseExceptions;

use Doctrine\{
    ORM\NoResultException,
    DBAL\Exception\DriverException
};

use Symfony\Component\{
    HttpFoundation\JsonResponse,
    HttpKernel\Event\GetResponseForExceptionEvent,
    HttpKernel\Exception\BadRequestHttpException,
    HttpKernel\Exception\MethodNotAllowedHttpException,
    HttpKernel\Exception\NotFoundHttpException
};

class ApiExceptionListener
{
    /**
     * The method intercepts all exception and throws his
     *
     * @param GetResponseForExceptionEvent $event
     * @return null|JsonResponse
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof NoResultException) {
            $data = ['error' => [
                'common' => ['access_denied_or_no_content']
            ]];
            $response = new JsonResponse($data);
            $response->setStatusCode(404);
            $event->setResponse($response);
            return $response;
        }

        if ($exception instanceof ValidException) {
            $response = new JsonResponse(unserialize($exception->getMessage()));
            $response->setStatusCode(412);
            $event->setResponse($response);
            return $response;
        }

        if ($exception instanceof DriverException && $exception->getSQLState() == 'P0001') {
            $data = ['error' => [
                'common' => [substr($exception->getPrevious()->getMessage(),strpos($exception->getPrevious()->getMessage(),'ERROR:')+8)]
            ]];
            $response = new JsonResponse($data);
            $response->setStatusCode(406);
            $event->setResponse($response);
        }

        if ($exception instanceof DriverException && $exception->getSQLState() == '22003') {
            $data = ['error' => [
                'common' => ['value_too_big']
            ]];
            $response = new JsonResponse($data);
            $response->setStatusCode(406);
            $event->setResponse($response);
            return $response;
        }

        if ($exception instanceof NotFoundHttpException) {
            $data = ['error' => [
                'common' => ['not_found']
            ]];
            $response = new JsonResponse($data);
            $response->setStatusCode(404);
            $event->setResponse($response);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $data = ['error' => [
                'common' => ['method_not_found']
            ]];
            $response = new JsonResponse($data);
            $response->setStatusCode(404);
            $event->setResponse($response);
        }

        if ($exception instanceof BadRequestHttpException) {
            $data = ['error' => [
                'common' => ['bad_request']
            ]];
            $response = new JsonResponse($data);
            $response->setStatusCode(400);
            $event->setResponse($response);
        }

        if (!$exception instanceof ApiException) {
            return null;
        }

        $data = ['error' => [
            'common' => [$exception->getMessage()]
        ]];
        $response = new JsonResponse($data);
        $response->setStatusCode($exception->getCode() ?: 500);
        $event->setResponse($response);
    }
}