<?php

namespace App\Controller;

use App\Entity\LinkType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class LinkController extends AbstractFOSRestController
{
    /**
     * @Route("/linkTypes", name="get_link_types", methods={"GET"})
     *
     * @return Response
     */
    public function getLinkTypes(): Response
    {
        $linkTypes = $this->getDoctrine()
            ->getRepository(LinkType::class)
            ->findAll();

        return $this->json(['linkTypes' => $linkTypes]);
    }
}
