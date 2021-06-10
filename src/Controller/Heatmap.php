<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class Heatmap extends AbstractController
{
    /**
     * @Route("/heatmap", name="get_heatmap")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getHeatmap(Request $request): Response
    {
        return $this->json(['body' => 'Success.']);
    }
}
