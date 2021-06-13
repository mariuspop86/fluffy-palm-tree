<?php

namespace App\Controller;

use App\Model\CustomerJourneyModel;
use App\Model\HeatmapModel;
use App\Model\HitModel;
use App\Services\HeatmapService;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class HeatmapController extends BaseController
{
    /**
     * @Route("/heatmaps/hits/link", name="get_hits_link", methods={"GET"})
     * 
     * @OA\Parameter(
     *     name="from",
     *     in="query",
     *     description="The field used as the start of the interval",
     *     example="2021-06-10",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="to",
     *     in="query",
     *     description="The field used as the end of the interval",
     *     example="2021-06-14",
     *     @OA\Schema(type="string")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns the links hits",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(
     *             type="object",
     *             @OA\Property(
     *                 property="hits",
     *                 example="3"
     *             ),
     *             @OA\Property(
     *                 property="link",
     *                 example="https://link.com/1"
     *             )
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad Request",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(
     *             property="errors",
     *             type="object",
     *                 @OA\Property(
     *                     property="from",
     *                     example="This value should not be blank."
     *                 )
     *         )
     *         
     *     )
     * )
     * @OA\Tag(name="Heatmap")
     * 
     * NOTE: this should be paginated but it was not requested :P
     * 
     * @param Request        $request
     * @param HeatmapService $heatmapService
     *
     * @return Response
     */
    public function getHitsLink(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $model = $this->getModelFromRequest(HitModel::class, $request);
            $result = $heatmapService->getHitsLink($model);
        } catch (Exception $error) {
            return $this->json(['error' => json_decode($error->getMessage())], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result);
    }

    /**
     * @Route("/heatmaps/hits/linkType", name="get_hits_link_type", methods={"GET"})
     *
     * @OA\Parameter(
     *     name="from",
     *     in="query",
     *     description="The field used as the start of the interval",
     *     example="2021-06-10",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="to",
     *     in="query",
     *     description="The field used as the end of the interval",
     *     example="2021-06-14",
     *     @OA\Schema(type="string")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns the link types hits",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(
     *             type="object",
     *             @OA\Property(
     *                 property="hits",
     *                 example="3"
     *             ),
     *             @OA\Property(
     *                 property="name",
     *                 example="product"
     *             )
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad Request",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(
     *             property="errors",
     *             type="object",
     *                 @OA\Property(
     *                     property="from",
     *                     example="This value should not be blank."
     *                 )
     *         )
     *
     *     )
     * )
     * @OA\Tag(name="Heatmap")
     *
     * NOTE: this should be paginated but it was not requested :P
     * 
     * @param Request        $request
     * @param HeatmapService $heatmapService
     *
     * @return Response
     */
    public function getHitsLinkTypes(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $model = $this->getModelFromRequest(HitModel::class, $request);
            $result = $heatmapService->getHitsLinkTypes($model);
        } catch (Exception $error) {
            return $this->json(['error' => json_decode($error->getMessage())], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result);
    }
    
    /**
     * @Route("/heatmaps/journey/customer/{id}", name="get_heatmap_by_customer", methods={"GET"}, requirements={"customer_id"="\d+"})
     *
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The id of the customer",
     *     @OA\Schema(type="string")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns the journey of a customer",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(
     *             type="object",
     *             @OA\Property(
     *                 property="link",
     *                 example="https://link.com/1"
     *             ),
     *             @OA\Property(
     *                 property="accessed",
     *                 example="2021-06-13 04:06:57"
     *             )
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad Request",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(
     *             property="errors",
     *             example="Customer not found"
     *         )
     *     )
     * )
     * @OA\Tag(name="Heatmap")
     *
     * @param Request        $request
     * @param HeatmapService $heatmapService
     *
     * @return Response
     */
    public function getJourneyByCustomer(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $model = $this->getModelFromRequest(CustomerJourneyModel::class, $request);
            $result = $heatmapService->getJourneyByCustomer($model);
        } catch (Exception $error) {
            return $this->json(['error' => json_decode($error->getMessage())], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result);
    }

    /**
     * @Route("/heatmaps/similar-journey/customer/{id}", name="get_similar_heatmap_by_customer", methods={"GET"}, requirements={"customer_id"="\d+"})
     *
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The id of the customer",
     *     @OA\Schema(type="string")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Returns a list of customers with a similar joruney",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(
     *             type="object",
     *             @OA\Property(
     *                 property="customer_id",
     *                 example="11"
     *             ),
     *             @OA\Property(
     *                 property="customer_name",
     *                 example="Doe"
     *             )
     *         )
     *     )
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad Request",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(
     *             property="errors",
     *             example="Customer not found"
     *         )
     *     )
     * )
     * @OA\Tag(name="Heatmap")
     *
     * @param Request        $request
     * @param HeatmapService $heatmapService
     *
     * NOTE: this should be paginated but it was not requested :P
     * 
     * @return Response
     */
    public function getSimilarJourneyByCustomer(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $model = $this->getModelFromRequest(CustomerJourneyModel::class, $request);
            $result = $heatmapService->getSimilarJourneyByCustomer($model);
        } catch (Exception $error) {
            return $this->json(['error' => json_decode($error->getMessage())], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($result);
    }
    
    /**
     * @Route("/heatmaps", name="save_heatmap", methods={"POST"})
     *
     * @OA\RequestBody(
     *     description="body",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(
     *             property="customer_id",
     *             @OA\Schema(type="integer"),
     *             example=11
     *         ),
     *         @OA\Property(
     *             property="link_id",
     *             @OA\Schema(type="integer"),
     *             example=1
     *         ),
     *         @OA\Property(
     *             property="link",
     *             example="https://link.com/1"
     *         ),
     *     )
     * )
     * @OA\Response(
     *     response=201,
     *     description="Save customer journey"
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad Request",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(
     *             property="errors",
     *             example="Customer not found"
     *         )
     *     )
     * )
     * 
     * @OA\Tag(name="Heatmap")
     *
     * @param Request        $request
     * @param HeatmapService $heatmapService
     *
     * @return Response
     */
    public function saveHeatmap(Request $request, HeatmapService $heatmapService): Response
    {
        try {
            $model = $this->getModelFromRequest(HeatmapModel::class, $request);
            $heatmapService->saveHeatmap($model);
        } catch (Exception $error) {
            return $this->json(['error' => json_decode($error->getMessage())], Response::HTTP_BAD_REQUEST);
        }
            
        return $this->json('', Response::HTTP_CREATED);
    }
}
