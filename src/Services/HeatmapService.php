<?php

namespace App\Services;

use App\Entity\Heatmap;
use App\Model\CustomerJourneyModel;
use App\Model\HeatmapModel;
use App\Model\HitModel;
use App\Repository\HeatmapRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class HeatmapService
{
    private CustomerService $customerService;
    
    private LinkService $linkService;
    
    private EntityManagerInterface $entityManager;
    
    private HeatmapRepository $heatmapRepository;

    /**
     * HeatmapService constructor.
     */
    public function __construct(
        CustomerService $customerService,
        LinkService $linkService,
        EntityManagerInterface $entityManager,
        HeatmapRepository $heatmapRepository
    ) {
        $this->customerService = $customerService;
        $this->linkService = $linkService;
        $this->entityManager = $entityManager;
        $this->heatmapRepository = $heatmapRepository;
    }

    /**
     * @throws Exception
     */
    public function getHitsLink(HitModel $hitModel): array
    {
        return $this->heatmapRepository->findLinkHitsByRange(
            new DateTime($hitModel->getFrom()),
            new DateTime($hitModel->getTo())
        );
    }

    /**
     * @throws Exception
     */
    public function getHitsLinkTypes(HitModel $hitModel): array
    {
        return $this->heatmapRepository->findLinkTypeHitsByRange(
            new DateTime($hitModel->getFrom()),
            new DateTime($hitModel->getTo())
        );
    }
    
    /**
     * @throws Exception
     */
    public function getJourneyByCustomer(CustomerJourneyModel $customerJourneyModel): array
    {
        $journey = $this->getJourneyByCustomerQueryResult($customerJourneyModel->getCustomerId());

        $result = [];
        foreach ($journey as $key => $item) {
            $item['accessed'] = $item['createdAt']->format('Y-m-d h:m:s');
            unset($item['createdAt']);
            $result[$key] = $item;
        }

        return $result;
    }

    /**
     * @note: This function will work fine for a small customer base, 
     * but because there is a query for each customer it will be an issue for a large customer base.
     * A solution to the notice above would be generate the similar journey by a cron,
     * other solution are possible but this is the first one I can think of.
     * 
     * @throws Exception
     */
    public function getSimilarJourneyByCustomer(CustomerJourneyModel $customerJourneyModel): array
    {
        $journey = $this->getJourneyByCustomerQueryResult($customerJourneyModel->getCustomerId());

        $links = $this->getLinksFromResult($journey);

        $uniqueLinks = array_unique($links);
        
        $similarJourneyCustomers = $this->heatmapRepository->getSimilarJourneyUniqueCustomers(
            $customerJourneyModel->getCustomerId(), 
            $uniqueLinks
        );
        
        $customerIds = []; 
        foreach ($similarJourneyCustomers as $item) {
            $customerJourneys = $this->heatmapRepository->getJourneyByCustomer($item['id']);
            $similarLinks =  $this->getLinksFromResult($customerJourneys);
            $this->getSimilarCustomers($customerIds, $item, $links, $similarLinks);
        }

        return $customerIds;
    }

    /**
     * @throws Exception
     */
    public function saveHeatmap(HeatmapModel $heatmapModel)
    {
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
     * @throws Exception
     */
    protected function getJourneyByCustomerQueryResult(int $customerId): array
    {
        $customer = $this->customerService->getCustomerById($customerId);

        return $this->heatmapRepository->getJourneyByCustomer(
            $customer->getId()
        );
    }
    
    protected function getLinksFromResult(array $queryResult): array 
    {
        $links = [];
        foreach ($queryResult as $item) {
            $links[] = $item['link'];
        }
        
        return $links;
    }
    
    protected function getSimilarCustomers(array &$customerIds, array $customer, array $links, array $similarLinks)
    {
        $similarLinks = implode(',', $similarLinks);
        $links = implode(',', $links);
        if (str_contains($similarLinks, $links)) {
            $customerIds[] = [
                'customer_id' => $customer['id'],
                'customer_name' => $customer['name'],
            ];
        }
    }
}
