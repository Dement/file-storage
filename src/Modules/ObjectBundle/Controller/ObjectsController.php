<?php

namespace Modules\ObjectBundle\Controller;

use BaseClasses\{
    BaseController,
    CheckClient
};
use BaseClasses\BaseEntityManager;
use BaseExceptions\ApiException;
use BaseExceptions\ValidException;
use Modules\BucketBundle\Entity\Bucket;
use Modules\BucketBundle\Repository\BucketRepository;
use Modules\ObjectBundle\Entity\Objects;
use Modules\ObjectBundle\Forms\ObjectsType;
use Modules\ObjectBundle\Repository\ObjectsRepository;
use Symfony\Component\HttpFoundation\{
    Request,
    Response
};
use FOS\RestBundle\Controller\Annotations as Rest;
use Validator\ValidErrors;

class ObjectsController extends BaseController
{
    /**
     * Create folder
     *
     * @Rest\View
     *
     * @param $id
     * @param Request $request
     * @return Response
     * @throws ApiException
     */
    public function createAction($id, Request $request) {
        $bucket = BucketRepository::get()->getById($id);
        CheckClient::modelExistenceOrNull($bucket);
        $this->checkCurrentProfile($bucket->getProfile());

        $title = $request->get('title');
        $this->checkTitle($title);

        //TODO определять по sh(x)
        /** @var BaseEntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();

        if (!is_null($key = $request->get('key'))) {
            $array = explode('/', $key);
            $path = '';
            foreach ($array as $item) {
                if (empty($item) === false) {
                    $md5 = md5($path . $item);

                    if (is_null(ObjectsRepository::get()->getByEtag($md5))) {
                        $data = [
                            'key' => $path . $item,
                            'etag' => $md5,
                        ];

                        $object = new Objects();
                        $object->setBucket($bucket)
                            ->setCreated(new \DateTime())
                            ->setDelete(false);

                        $this->processForm($object, $data, $em);
                    }

                    $path .= $item . '/';
                }
            }

            $substring = $key;
            $length = strlen($key) + 2;
        } else {
            $substring = '';
            $length = 0;
        }

        $object = new Objects();
        $object->setBucket($bucket)
            ->setCreated(new \DateTime())
            ->setDelete(false);

        if ($key[iconv_strlen($key) - 1] != "/" && $length != 0) {
            $key .= '/';
        }

        $data = [
            'key' => $key . $title,
            'etag' => md5($key . $title),
        ];

        $this->processForm($object, $data, $em);

        try {
            $em->flush();

            $em->getConnection()->commit();
        } catch (\Throwable $e) {
            $em->getConnection()->rollback();
            throw new ApiException("save_server_error" , 406);
        }

        $objects = ObjectsRepository::get()->getListLikeByFolder($substring, $length);
        return $this->jsonResponse($objects);
    }

    /**
     * Update folder
     *
     * @Rest\View
     *
     * @param $id
     * @param Request $request
     * @return Response
     * @throws ApiException
     */
    public function updateAction($id, Request $request) {
        $bucket = BucketRepository::get()->getById($id);
        CheckClient::modelExistenceOrNull($bucket);
        $this->checkCurrentProfile($bucket->getProfile());

        $key = $request->get('key');
        if ($key[iconv_strlen($key) - 1] == "/") {
            $key = substr($key, 0, iconv_strlen($key) - 1);
        }

        $md5 = md5($key);

        if (is_null($object = ObjectsRepository::get()->getByEtag($md5))) {
            throw new ApiException('Folder not found', 404);
        }

        $title = $request->get('title');
        $this->checkTitle($title);

        //TODO определять по sh(x)
        /** @var BaseEntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();

        if (($pos = strripos($key, '/')) ==! false) {
            $newKey = substr($key, 0, $pos + 1);
        } else {
            $newKey = '';
        }

        $data = [
            'key' => $newKey . $title,
            'etag' => md5($newKey . $title),
        ];

        $this->processForm($object, $data, $em);

        //TODO это надо делать на чистом sql запросе.
        $objects = ObjectsRepository::get()->getListLikeByKey($key . '/');
        $objects->forAll(function ($iter, $item) use ($em, $key, $newKey, $title) {
            $item->setKey(str_replace($key, $newKey . $title, $item->getKey()));
            $item->setEtag(md5($item->getKey()));
            $em->persist($item);
            return true;
        });

        try {
            $em->flush();

            $em->getConnection()->commit();
        } catch (\Throwable $e) {
            $em->getConnection()->rollback();
            throw new ApiException("save_server_error" , 406);
        }

        $substring = $newKey;
        $length = strlen($newKey) + 2;
        $objects = ObjectsRepository::get()->getListLikeByFolder($substring, $length);
        return $this->jsonResponse($objects);
    }

    /**
     * Delete folder
     *
     * @Rest\View
     *
     * @param $id
     * @param Request $request
     * @return Response
     * @throws ApiException
     */
    public function deleteAction($id, Request $request) {
        $bucket = BucketRepository::get()->getById($id);
        CheckClient::modelExistenceOrNull($bucket);
        $this->checkCurrentProfile($bucket->getProfile());

        //TODO проверка на null
        $key = $request->get('key');
        if (($pos = strripos($key, '/')) ==! false) {
            $substring = substr($key, 0, $pos + 1);
            $length = strlen($substring) + 1;
        } else {
            $substring = '';
            $length = 0;
        }

        $md5 = md5($key);
        if (is_null($object = ObjectsRepository::get()->getByEtag($md5))) {
            throw new ApiException('Folder not found', 404);
        }

        //TODO определять по sh(x)
        /** @var BaseEntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();

        $object->setDelete(true);
        $em->persist($object);

        try {
            ObjectsRepository::get()->updateByKey($key . '/');
            $em->flush();

            //TODO ставим задачу очереди на удаления файлов и папок

            $em->getConnection()->commit();
        } catch (\Throwable $e) {
            $em->getConnection()->rollback();
            throw new ApiException("save_server_error" , 406);
        }

        $objects = ObjectsRepository::get()->getListLikeByFolder($substring, $length);
        return $this->jsonResponse($objects);
    }

    /**
     * Get list of objects in the folder
     *
     * @Rest\View
     *
     * @param $id
     * @param Request $request
     * @return Response
     * @throws ApiException
     */
    public function getListAction($id, Request $request) {
        $bucket = BucketRepository::get()->getById($id);
        CheckClient::modelExistenceOrNull($bucket);
        $this->checkCurrentProfile($bucket->getProfile());

        if (is_null($key = $request->get('key'))) {
            $key = '';
            $length = 0;
            $substring = '';
        } else {
            if ($key[iconv_strlen($key) - 1] == "/") {
                $key = substr($key, 0, iconv_strlen($key) - 1);
            }
            $md5 = md5($key);
            $length = strlen($key) + 2;
            $substring = $key . '/';
        }

        if (is_null($object = ObjectsRepository::get()->getByEtag($md5)) && !empty($key)) {
            throw new ApiException('Folder not found', 404);
        }

        $objects = ObjectsRepository::get()->getListLikeByFolder($substring, $length);
        return $this->jsonResponse($objects);
    }

    protected function processForm(Objects $objects, array $request, BaseEntityManager $em, $clearMissing = true)
    {
        $form = $this->createForm(ObjectsType::class, $objects);
        $form->submit($request, $clearMissing);

        if (!$form->isValid()) {
            ValidErrors::render($form);
        }

        $em->persist($objects);
    }

    private function checkTitle($title) {
        if (empty($title)) {
            throw new ValidException(serialize(['error' => ['title' => ['not_null']]]), 406);
        } else {
            $count = iconv_strlen($title);
            if ($count < 4) {
                throw new ValidException(serialize(['error' => ['title' => ['Your first name must be at least 4 characters long']]]), 406);
            }
            if ($count > 51) {
                throw new ValidException(serialize(['error' => ['title' => ['Your first name cannot be longer than 50 characters']]]), 406);
            }
        }
    }
}