<?php

namespace App\Services;

use App\Entity\Heatmap;
use App\Model\HeatmapModel;
use App\Model\HitModel;
use App\Repository\HeatmapRepository;
use App\Traits\RequestTrait;
use App\Transformer\HeatmapTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HeatmapService
{
    use RequestTrait;
    /**
     * @var CustomerService
     */
    private $customerService;
    /**
     * @var LinkService
     */
    private $linkService;
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var HeatmapTransformer
     */
    private $heatmapTransformer;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var HeatmapRepository
     */
    private $heatmapRepository;

    /**
     * HeatmapService constructor.
     */
    public function __construct(
        CustomerService $customerService,
        LinkService $linkService,
        ValidatorInterface $validator,
        HeatmapTransformer $heatmapTransformer,
        EntityManagerInterface $entityManager,
        HeatmapRepository $heatmapRepository
    ) {
        $this->customerService = $customerService;
        $this->linkService = $linkService;
        $this->validator = $validator;
        $this->heatmapTransformer = $heatmapTransformer;
        $this->entityManager = $entityManager;
        $this->heatmapRepository = $heatmapRepository;
    }

    /**
     * @throws \Exception
     */
    public function getHitsLink(Request $request): array
    {
        $hitModel = $this->heatmapTransformer->convertDataToHitModel($request);
        $this->validateHitModel($hitModel);
        
        $result = $this->heatmapRepository->findLinkHitsByRange(
            new \DateTime($hitModel->getFrom()),
            new \DateTime($hitModel->getTo())
        );
        
        return $result;
    }

    /**
     * @throws \Exception
     */
    public function getHitsLinkTypes(Request $request): array
    {
        $hitModel = $this->heatmapTransformer->convertDataToHitModel($request);
        $this->validateHitModel($hitModel);

        $result = $this->heatmapRepository->findLinkTypeHitsByRange(
            new \DateTime($hitModel->getFrom()),
            new \DateTime($hitModel->getTo())
        );

        return $result;
    }
    
    /**
     * @throws \Exception
     */
    public function getHeatmapByCustomer(Request $request): array
    {
        $customer = $this->customerService->getCustomerById($request->get('customer_id'));

        $result = $this->heatmapRepository->findHeatmapByCustomer(
            $customer->getId()
        );

        return $result;
    }

    /**
     * @throws \Exception
     */
    public function saveHeatmap(Request $request)
    {
        $content = $this->getValidatedRequestContent($request->getContent());
        $heatmapModel = $this->heatmapTransformer->convertDataToHeatmapModel($content);
        $this->validateHeatmapModel($heatmapModel);
        
        $customer = $this->customerService->getCustomerById($heatmapModel->getCustomerId());
        $linkType = $this->linkService->getLinkTypeById($heatmapModel->getLinkId());
        $heatmap = new Heatmap();
        $heatmap->setCustomer($customer);
        $heatmap->setLink($heatmapModel->getLink());
        $heatmap->setLinkType($linkType);
        
        $this->entityManager->persist($heatmap);
        $this->entityManager->flush();
    }

    /**
     * @throws \Exception
     */
    private function validateHeatmapModel(HeatmapModel $heatmapModel)
    {
        $errors = $this->validator->validate($heatmapModel);
        if (count($errors) == 0) {
            return;
        }
        $message = [];
        foreach ($errors as $error) {
            $message[$error->getPropertyPath()] = $error->getMessage();
        }
        throw new \Exception(json_encode($message));
    }

    /**
     * @throws \Exception
     */
    private function validateHitModel(HitModel $hitModel)
    {
        $errors = $this->validator->validate($hitModel);
        if (count($errors) == 0) {
            return;
        }
        $message = [];
        foreach ($errors as $error) {
            $message[$error->getPropertyPath()] = $error->getMessage();
        }
        throw new \Exception(json_encode($message));
    }
}
