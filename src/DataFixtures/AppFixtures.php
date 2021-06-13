<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\LinkType;
use App\Enum\CustomerEnum;
use App\Enum\LinkTypeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    const GROUP = 'APP';
    
    public function load(ObjectManager $manager)
    {
        $this->loadLinkTypes($manager);
        $this->loadCustomer($manager);        

        $manager->flush();
    }
    
    private function loadLinkTypes(ObjectManager $manager) 
    {
        foreach (LinkTypeEnum::LINK_TYPES as $type) {
            $linkType = new LinkType();
            $linkType->setName($type);
            $manager->persist($linkType);
        }
    }
    
    private function loadCustomer(ObjectManager $manager)
    {
        foreach (CustomerEnum::CUSTOMERS as $name) {
            $customer = new Customer();
            $customer->setName($name);
            $manager->persist($customer);
        }
    }

    public static function getGroups(): array
    {
        return [self::GROUP];
    }
}
