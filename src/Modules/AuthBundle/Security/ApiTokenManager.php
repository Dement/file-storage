<?php

namespace Modules\AuthBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\{
    HttpFoundation\RequestStack,
    DependencyInjection\Container
};

class ApiTokenManager {

    private $request;

    /** @var  Container */
    private $container;

    /** @var  EntityManager */
    protected $em;

    public function __construct(RequestStack $request, Container $container) {
        $this->request = $request->getCurrentRequest();
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();
    }

    /**
     * Get the current token
     *
     * @return string
     */
    public function getCurrentToken() : string
    {
        return $this->request->headers->get('auth-token') ?? '';
    }

    /**
     * It generates a random number for the confirmation token
     *
     * @return int
     */
    public function generateActivationCode() : int
    {
        return mt_rand(100000, 999999);
    }

    /**
     * Generates an unique access token.
     *
     * @return string
     */
    public function generateToken() : string
    {
        if (@file_exists('/dev/urandom')) { // Get 100 bytes of random data
            $randomData = file_get_contents('/dev/urandom', false, null, 0, 100);
        } elseif (function_exists('openssl_random_pseudo_bytes')) { // Get 100 bytes of pseudo-random data
            $bytes = openssl_random_pseudo_bytes(100, $strong);
            if (true === $strong && false !== $bytes) {
                $randomData = $bytes;
            }
        }

        // Last resort: mt_rand
        if (empty($randomData)) { // Get 108 bytes of (pseudo-random, insecure) data
            $randomData = mt_rand() . mt_rand() . mt_rand() . uniqid(mt_rand(), true) . microtime(true) . uniqid(
                    mt_rand(),
                    true
                );
        }

        return rtrim(strtr(base64_encode(hash('sha256', $randomData)), '+/', '-_'), '=');
    }
}