<?php

namespace App\DataFixtures;

use App\Entity\Heatmap;
use App\Enum\CustomerEnum;
use App\Enum\LinkEnum;
use App\Enum\LinkTypeEnum;
use App\Repository\CustomerRepository;
use App\Repository\LinkTypeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    const HEATMAP = [
        [
            'customerName' => CustomerEnum::JOHN, 
            'link' => LinkEnum::LINK1, 
            'linkType' => LinkTypeEnum::HOMEPAGE_LINK_TYPE
        ],
        [
            'customerName' => CustomerEnum::JOHN, 
            'link' => LinkEnum::LINK2, 
            'linkType' => LinkTypeEnum::CHECKOUT_LINK_TYPE
        ],
        [
            'customerName' => CustomerEnum::DOE, 
            'link' => LinkEnum::LINK1, 
            'linkType' => LinkTypeEnum::HOMEPAGE_LINK_TYPE
        ],
        [
            'customerName' => CustomerEnum::LUKE, 
            'link' => LinkEnum::LINK1, 
            'linkType' => LinkTypeEnum::HOMEPAGE_LINK_TYPE
        ],
    ];
    
    const GROUP = 'TEST';
    
    /**
     * @var CustomerRepository
     */
    private $customerRepository;
    /**
     * @var LinkTypeRepository
     */
    private $linkTypeRepository;

    public function __construct(CustomerRepository $customerRepository, LinkTypeRepository $linkTypeRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->linkTypeRepository = $linkTypeRepository;
    }
    
    public static function getGroups(): array
    {
        return [self::GROUP];
    }

    public function load(ObjectManager $manager)
    {
        $this->loadHeatmap($manager);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AppFixtures::class,
        ];
    }

    private function loadHeatmap(ObjectManager $manager)
    {
        foreach (self::HEATMAP as $item) {
            $customer = $this->customerRepository->findOneBy(['name' => $item['customerName']]);
            $linkType = $this->linkTypeRepository->findOneBy(['name' => $item['linkType']]);
            
            $heatmap = new Heatmap();
            $heatmap->setCustomer($customer);
            $heatmap->setLink($item['link']);
            $heatmap->setLinkType($linkType);
            $manager->persist($heatmap);
        }
    }
}
