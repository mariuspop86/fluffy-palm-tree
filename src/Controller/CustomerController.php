<?php

namespace App\Controller;

use App\Services\CustomerService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class CustomerController extends AbstractFOSRestController
{
    /**
     * @Route("/customers", name="get_customers", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of customers",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(
     *            property="customers",
     *            type="array",
     *            @OA\Items(
     *                type="object",
     *                @OA\Property(
     *                    property="id",
     *                    example="1"
     *                ),
     *                @OA\Property(
     *                    property="name",
     *                    example="Doe"
     *                ),
     *            )
     *        )
     *     )
     * )
     * @OA\Tag(name="customer")
     *
     * @param CustomerService $customerService
     *
     * NOTE: API create just for better usability 
     *       
     * @return Response
     */
    public function getCustomers(CustomerService $customerService): Response
    {
        $customers = $customerService->getCustomers();
        
        return $this->json(['customers' => $customers]);
    }
}
