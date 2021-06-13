<?php

namespace App\Controller;

use App\Model\ModelInterface;
use App\Services\RequestService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractFOSRestController
{
    /**
     * @var \App\Services\RequestService
     */
    private $requestService;

    /**
     * baseController constructor.
     */
    public function __construct(RequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    /**
     * @throws \Exception
     */
    public function getModelFromRequest(string $modelClassName, Request $request): ModelInterface
    {
        return $this->requestService->createModelFromRequest($modelClassName, $request);
    }
}
