<?php

namespace App\Controller;

use App\Entity\Customer;
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
    public function getCustomers(): Response
    {
        $customers = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->findAll();
        
        return $this->json(['customers' => $customers]);
    }
}
