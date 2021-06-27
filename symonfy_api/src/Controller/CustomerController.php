<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObjects\Transformation\CustomerTransformer;
use App\Entity\AccessLog;
use App\Entity\Customer;
use App\Form\Type\CustomerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerController extends AbstractApiController
{
    private CustomerTransformer $customerTransformer;

    public function __construct(CustomerTransformer $customerTransformer)
    {
        $this->customerTransformer = $customerTransformer;
    }

    public function indexAction(Request $request): Response
    {
        $customers = $this->getDoctrine()->getRepository(Customer::class)->findAll();

        if (!$customers) {
            throw new NotFoundHttpException('No Customers Found');
        }

        return $this->respond($this->customerTransformer->transformCollection($customers));
    }

    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(CustomerType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->respond($form, Response::HTTP_BAD_REQUEST);
        }

        /** @var Customer $customer */
        $customer = $form->getData();

        $this->getDoctrine()->getManager()->persist($customer);
        $this->getDoctrine()->getManager()->flush();

        return $this->respond($this->customerTransformer->transformFromEntity($customer));
    }

    public function findComparableCustomerJournies(Request $request): Response
    {
        $customers = $this->getDoctrine()->getRepository(Customer::class)->findAll();

        if (!$customers) {
            throw new NotFoundHttpException('No Customers Found');
        }

        $customer_id = (int) $request->get('id');

        $targetCustomer=$this->getDoctrine()->getRepository(Customer::class)->findOneBy(['id' => $customer_id]);


        if (!$targetCustomer) {
            throw new NotFoundHttpException('No Customers Found');
        }
        $targetCustomerJourney=$targetCustomer->getCustomerJourneyHash();

        $matchingCustomers=array();
        foreach ($customers as $customer) {
            /* @var Customer $customer */
            if($customer_id !== $customer->getId() && $targetCustomerJourney === $customer->getCustomerJourneyHash()){
                $matchingCustomers[]=$customer;
            }
        }

        return $this->respond($this->customerTransformer->transformCollection($matchingCustomers));
    }
}
