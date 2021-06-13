<?php

namespace App\Controller;

use App\Entity\LinkType;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class LinkController extends AbstractFOSRestController
{
    /**
     * @Route("/linkTypes", name="get_link_types", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of link types",
     *     @OA\JsonContent(
     *        type="object",
     *        @OA\Property(
     *            property="linkTypes",
     *            type="array",
     *            @OA\Items(
     *                type="object",
     *                @OA\Property(
     *                    property="id",
     *                    example="1"
     *                ),
     *                @OA\Property(
     *                    property="name",
     *                    example="product"
     *                ),
     *            )
     *        )
     *     )
     * )
     * @OA\Tag(name="Link type")
     *
     * NOTE: API create just for better usability
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
