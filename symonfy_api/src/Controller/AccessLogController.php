<?php


namespace App\Controller;


use App\DataTransferObjects\Transformation\AccessLogTransformer;
use App\DataTransferObjects\Transformation\CustomerTransformer;
use App\Entity\AccessLog;
use App\Entity\Customer;
use App\Form\Type\AccessType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AccessLogController extends AbstractApiController
{
    private AccessLogTransformer $accessLogTransformer;

    public function __construct(AccessLogTransformer $accessLogTransformer)
    {
        $this->accessLogTransformer = $accessLogTransformer;
    }

    public function indexAction(Request $request): Response
    {
        $accessLogs = $this->getDoctrine()
            ->getRepository(AccessLog::class)
            ->findAllDesc();

        if (!$accessLogs) {
            throw new NotFoundHttpException('No Access Logs Found');
        }

        return $this->respond($this->accessLogTransformer->transformCollection($accessLogs));
    }

    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(AccessType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var AccessLog $accessLog */
        $accessLog = $form->getData();

        $this->getDoctrine()->getManager()->persist($accessLog);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($this->accessLogTransformer->transformFromEntity($accessLog));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getIndividualLinkAction(Request $request): Response
    {
        $linkId = $request->get('link_id');
        $startTime = $request->get('start_time');
        $endTime = $request->get('end_time');
        $count_mode = $request->get('count', false);

        if (!$linkId) {
            throw new NotFoundHttpException('Link id not provided');
        }

        if (!$startTime) {
            throw new NotFoundHttpException('Start Date not provided');
        }

        if (!$endTime) {
            throw new NotFoundHttpException('Finish Date not provided');
        }

        if (!$count_mode) {
            $accessLogs = $this->getDoctrine()
                ->getRepository(AccessLog::class)
                ->findByLinkIdWithTimeStamps($linkId, $startTime, $endTime);
            
             return $this->respond($this->accessLogTransformer->transformCollection($accessLogs));          
        }

        $accessLogs = $this->getDoctrine()
            ->getRepository(AccessLog::class)
            ->findByLinkIdWithTimeStampsCount($linkId, $startTime, $endTime);

        return $this->respond($accessLogs);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getUrlByType(Request $request): Response
    {
        $url_type = $request->get('link_type');
        $count_mode = $request->get('count', false);

        if (!$url_type) {
            throw new NotFoundHttpException('Url Type not provided');
        }

        if ($count_mode) {
            $accessLogs = $this->getDoctrine()
                ->getRepository(AccessLog::class)
                ->findByLinkTypeCount();

            return $this->respond($accessLogs);
        }

        $accessLogs = $this->getDoctrine()
                ->getRepository(AccessLog::class)
                ->findByLinkType($url_type);

        return $this->respond($this->accessLogTransformer->transformCollection($accessLogs));
    }

    public function findCustomerJourney(Request $request): Response
    {
        $accessLogs = $this->getCustomerJourney($request);
        $pairs = array();
        foreach ($accessLogs as $accessLog) {
            /* @var AccessLog $accessLog */
            $url_name = $accessLog->getUrl()->getId();
            $url_type = $accessLog->getUrl()->getLinkType();
            $timestamp = $accessLog->getTimestamp();
            $pairs[] = ["url_id" => $url_name, "url_type" => $url_type, "timestamp" => $timestamp];
        }

        return $this->respond($pairs);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getCustomerJourney(Request $request): array
    {
        $customer_id = $request->get('id');
        $accessLogs = $this->getDoctrine()
            ->getRepository(AccessLog::class)
            ->findByCustomerJourney($customer_id);

        return $accessLogs;
    }

}