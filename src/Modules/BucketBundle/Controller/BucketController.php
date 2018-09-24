<?php

namespace Modules\BucketBundle\Controller;

use BaseClasses\{
    BaseController,
    BaseEntityManager,
    CheckClient
};
use Helpers\RequestHelper;
use Modules\BucketBundle\{
    Entity\Bucket,
    Forms\BucketType,
    Repository\BucketRepository
};
use Symfony\Component\HttpFoundation\{
    Request,
    Response
};
use FOS\RestBundle\Controller\Annotations as Rest;
use Validator\ValidErrors;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;

class BucketController extends BaseController
{
    /**
     * Create Bucket.
     *
     * @SWG\Tag(name="Bucket")
     * @Route("/v1/bucket", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns bucket",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="id", type="int"),
     *         @SWG\Property(property="title", type="string"),
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
     * @SWG\Parameter(
     *     name="title",
     *     type="string",
     *     in="body",
     *     description="The field used to title",
     *     minLength="5",
     *     maxLength="255",
     *     required=true
     * )
     *
     * @Rest\View
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request) {
        $bucket = new Bucket();
        $bucket->setProfile($this->getCurrentProfile());

        return $this->jsonResponse($this->processForm($bucket, $request));
    }

    /**
     * Ubdate Bucket.
     *
     * @SWG\Tag(name="Bucket")
     * @Route("/v1/bucket/{id}", methods={"PUT"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns bucket",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="id", type="int"),
     *         @SWG\Property(property="title", type="string"),
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
     * @SWG\Parameter(
     *     name="title",
     *     type="string",
     *     in="body",
     *     description="The field used to title",
     *     minLength="5",
     *     maxLength="255",
     *     required=true
     * )
     *
     * @Rest\View
     *
     * @param Request $request
     * @return Response
     */
    public function updateAction($id, Request $request) {
        $bucket = BucketRepository::get()->getById($id);
        CheckClient::modelExistenceOrNull($bucket);
        $this->checkCurrentProfile($bucket->getProfile());

        return $this->jsonResponse($this->processForm($bucket, $request));
    }

    /**
     * Delete Bucket.
     *
     * @SWG\Tag(name="Bucket")
     * @Route("/v1/bucket/{id}", methods={"DELETE"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns bucket",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="status", type="bool"),
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
     * @param Request $request
     * @return Response
     */
    public function deleteAction($id, Request $request) {
        $bucket = BucketRepository::get()->getById($id);
        CheckClient::modelExistenceOrNull($bucket);
        $this->checkCurrentProfile($bucket->getProfile());

        /** @var BaseEntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->remove($bucket);
        $em->flush();

        return $this->jsonResponse(['status' => true]);
    }

    /**
     * Get Bucket.
     *
     * @SWG\Tag(name="Bucket")
     * @Route("/v1/bucket/{id}", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns bucket",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="id", type="int"),
     *         @SWG\Property(property="title", type="string"),
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
     * @param Request $request
     * @return Response
     */
    public function getAction($id) {
        $bucket = BucketRepository::get()->getById($id);
        CheckClient::modelExistenceOrNull($bucket);
        $this->checkCurrentProfile($bucket->getProfile());

        return $this->jsonResponse($bucket);
    }

    /**
     * Get list Bucket.
     *
     * @SWG\Tag(name="Bucket")
     * @Route("/v1/bucket", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns collection bucket",
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(
     *              type="object",
     *              @SWG\Property(property="id", type="int"),
     *              @SWG\Property(property="title", type="string"),
     *         )
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
     * @param Request $request
     * @return Response
     */
    public function getListAction() {
        $bucket = BucketRepository::get()->getListByProfileId($this->getCurrentProfile()->getId());

        return $this->jsonResponse($bucket);
    }

    protected function processForm(Bucket $bucket, Request $request, $clearMissing = true)
    {
        $form = $this->createForm(BucketType::class, $bucket);
        $form->submit(RequestHelper::formatForm($request), $clearMissing);

        if (!$form->isValid()) {
            ValidErrors::render($form);
        }

        /** @var BaseEntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $em->persist($bucket);
        $em->flush();

        return $bucket;
    }
}