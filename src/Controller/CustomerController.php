<?php

namespace App\Controller;

use App\Services\CustomerService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class CustomerController extends AbstractFOSRestController
{
    /**
     * @Route("/customers", name="get_customers", methods={"GET"})
     *
     * @return Response
     */
    public function getCustomers(CustomerService $customerService): Response
    {
        $customers = $customerService->getCustomers();
        
        return $this->json(['customers' => $customers]);
    }
}
