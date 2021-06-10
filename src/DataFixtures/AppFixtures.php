<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\LinkType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    const LINK_TYPES = [
        'product', 
        'category',
        'static-page', 
        'checkout', 
        'homepage'
    ];

    const CUSTOMERS = [
        'John',
        'Doe',
        'Luke',
        'Skywalker',
    ];
    
    public function load(ObjectManager $manager)
    {
        $this->loadLinkTypes($manager);
        $this->loadCustomer($manager);        

        $manager->flush();
    }
    
    private function loadLinkTypes(ObjectManager $manager) 
    {
        foreach (self::LINK_TYPES as $type) {
            $linkType = new LinkType();
            $linkType->setName($type);
            $manager->persist($linkType);
        }
    }
    
    private function loadCustomer(ObjectManager $manager)
    {
        foreach (self::CUSTOMERS as $name) {
            $customer = new Customer();
            $customer->setName($name);
            $manager->persist($customer);
        }
    }
}
