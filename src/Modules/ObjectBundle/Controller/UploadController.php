<?php

namespace Modules\ObjectBundle\Controller;

use BaseClasses\{
    BaseController,
    BaseEntityManager,
    CheckClient
};
use BaseExceptions\ApiException;
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

class UploadController extends BaseController
{
    /**
     * Add file.
     *
     * @SWG\Tag(name="Object")
     * @Route("/v1/file", methods={"POST"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns status",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(property="status", type="boolean"),
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
     *     name="upload-id",
     *     in="header",
     *     required=true,
     *     type="string",
     *     default="string",
     *     description="The unique identifier for the file"
     * )
     * @SWG\Parameter(
     *     name="Portion-From",
     *     in="header",
     *     required=true,
     *     type="integer",
     *     default="integer",
     *     description="The position of the sent block"
     * )
     * @SWG\Parameter(
     *     name="title",
     *     type="string",
     *     in="body",
     *     description="The field used to title",
     *     minLength="4",
     *     maxLength="51",
     *     required=true
     * )
     *
     * @Rest\View
     *
     * @param Request $request
     * @return Response
     * @throws ApiException
     */
    public function addAction(Request $request) {
        $hash = $request->headers->get('upload-id');
        $filename = $hash . '.temp';
        $dir = $this->get('kernel')->getRootDir() . '/../load/';

        if ($request->get('action')) {
            if (is_file($dir . 'result' . '.temp')) {
                unlink($dir . 'result' . '.temp');
            }

            rename($dir . $filename, $dir . 'result' . '.temp');

            $fw = fopen($dir . $filename, 'wb');
            if ($fw) {
                fclose($fw);
            }

            //TODO записать в БД
            //TODO Сохранить файл
            //TODO определяем куда положить файл по sh(x)
        } else {
            try {
                if (intval($request->headers->get('Portion-From')) == 0) {
                    $fout = fopen($dir . $filename, "wb");
                } else {
                    $fout = fopen($dir . $filename, "ab");
                }

                $fin = fopen("php://input", "rb");
                if ($fin) {
                    while (!feof($fin)) {
                        $data = fread($fin, 1024 * 1024);
                        fwrite($fout, $data);
                    }
                    fclose($fin);
                }

                fclose($fout);
            } catch (\Throwable $e) {
                throw new ApiException('error downloading file', 500);
            }
        }

        return $this->jsonResponse(['status' => true]);
    }
}