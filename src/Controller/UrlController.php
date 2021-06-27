<?php


namespace App\Controller;

use App\DataTransferObjects\Transformation\UrlTransformer;
use App\Entity\Url;
use App\Form\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UrlController extends AbstractApiController
{
    private UrlTransformer $urlTransformer;

    public function __construct(UrlTransformer $urlTransformer)
    {
        $this->urlTransformer = $urlTransformer;
    }


    public function indexAction(Request $request): Response
    {
        $urls = $this->getDoctrine()->getRepository(Url::class)->findAll();

        return $this->respond($this->urlTransformer->transformCollection($urls));
    }

    public function createAction(Request $request): Response
    {

        $form = $this->buildForm(UrlType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Url $url */
        $url = $form->getData();

        $this->getDoctrine()->getManager()->persist($url);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($url);
    }
}