<?php

namespace BaseClasses;

use BaseExceptions\ApiException;
use Serializer\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\{
    HttpFoundation\Response,
    Yaml\Yaml
};
use Traits\CurrentUser;

class BaseController extends Controller
{
    use CurrentUser;

    /**
     * Get the configuration for serialization
     *
     * @return array
     * @throws ApiException
     */
    protected function getSerializationConfig() : array {
        $route = $this->get('request_stack')->getCurrentRequest()->get('_route');

        $controllerClass = debug_backtrace()[2]['class'];
        if (preg_match('~(Modules\\\\.*Bundle)~is', $controllerClass, $matches)) {
            $bundle = str_replace('\\', '/', $matches[1]);
        } else {
            throw new ApiException('Could_not_determine_called_bundle');
        }

        $configPath = $this->get('kernel')->getRootDir() . '/../src/' . $bundle . '/Resources/config/serialization.yml';
        if (file_exists($configPath)) {
            $config = Yaml::parse(file_get_contents($configPath));
            if (isset($config[$route])) {
                $config = $config[$route];
            } else {
                $config = [];
            }
        } else {
            $config = [];
        }

        return $config;
    }

    /**
     * Serialized data
     *
     * @param $data
     * @param int $statusCode
     * @param $format
     * @return Response
     * @throws ApiException
     */
    protected function jsonResponse($data, $statusCode = 200, $format = Serializer::FORMAT_JSON) : Response {
        /** @var \Serializer\Serializer $json */
        $json = $this->get('custom_serializer')->serialize($data, $this->getSerializationConfig(), $format);
        return new Response($json, $statusCode, ['Content-Type' => 'application/json']);
    }
}