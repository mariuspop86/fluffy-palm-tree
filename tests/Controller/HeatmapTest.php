<?php

namespace App\Tests\Controller;

use App\Enum\CustomerEnum;
use App\Enum\LinkEnum;
use App\Enum\LinkTypeEnum;
use App\Repository\CustomerRepository;
use App\Repository\LinkTypeRepository;
use App\Tests\BaseApiTest;
use DateTime;
use Symfony\Component\HttpFoundation\Response;

class HeatmapTest extends BaseApiTest
{
    private ?object $customerRepository;
    private ?object $linkTypeRepository;

    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = self::$container;
        $this->customerRepository = $container->get(CustomerRepository::class);
        $this->linkTypeRepository = $container->get(LinkTypeRepository::class);
    }
    
    public function testHitsByLink(): void
    {
        $yesterdayDate = new DateTime('yesterday');
        $yesterday = $yesterdayDate->format('Y-m-d');
        $todayDate = new DateTime('now');
        $today = $todayDate->format('Y-m-d');

        $this->client->request('GET', '/api/heatmaps/hits/link?from='.$yesterday.'&to='.$today);
        $content = $this->getResponseContent();
        list($firstItem, $secondItem) = $content;
        
        $this->assertResponseIsSuccessful();
        $this->assertEquals(3, $firstItem['hits']);
        $this->assertEquals(LinkEnum::LINK1, $firstItem['link']);
        $this->assertEquals(1, $secondItem['hits']);
        $this->assertEquals(LinkEnum::LINK2, $secondItem['link']);
    }

    public function testHitsByLinkWithNoQuery(): void
    {
        $this->client->request('GET', '/api/heatmaps/hits/link');
        $content = $this->getResponseContent();
        
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertArrayHasKey('error', $content);
        $this->assertArrayHasKey('from', $content['error']);
        $this->assertArrayHasKey('to', $content['error']);
        $this->assertEquals('This value should not be blank.', $content['error']['from']);
        $this->assertEquals('This value should not be blank.', $content['error']['to']);
    }
    
    public function testHitsByLinkType(): void
    {
        $yesterdayDate = new DateTime('yesterday');
        $yesterday = $yesterdayDate->format('Y-m-d');
        $todayDate = new DateTime('now');
        $today = $todayDate->format('Y-m-d');

        $this->client->request('GET', '/api/heatmaps/hits/linkType?from='.$yesterday.'&to='.$today);
        $content = $this->getResponseContent();
        list($firstItem, $secondItem) = $content;

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $firstItem['hits']);
        $this->assertEquals(LinkTypeEnum::CHECKOUT_LINK_TYPE, $firstItem['name']);
        $this->assertEquals(3, $secondItem['hits']);
        $this->assertEquals(LinkTypeEnum::HOMEPAGE_LINK_TYPE, $secondItem['name']);
    }

    public function testHitsByLinkTypeWithNoQuery(): void
    {
        $this->client->request('GET', '/api/heatmaps/hits/linkType');
        $content = $this->getResponseContent();

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertArrayHasKey('error', $content);
        $this->assertArrayHasKey('from', $content['error']);
        $this->assertArrayHasKey('to', $content['error']);
        $this->assertEquals('This value should not be blank.', $content['error']['from']);
        $this->assertEquals('This value should not be blank.', $content['error']['to']);
    }
    
    public function testGetJourneyForJohnCustomer(): void
    {
        $customer = $this->customerRepository->findOneBy(['name' => CustomerEnum::JOHN]);
        $this->client->request('GET', '/api/heatmaps/journey/customer/'.$customer->getId());
        $content = $this->getResponseContent();
        
        $this->assertResponseIsSuccessful();
        $this->assertCount(2, $content);
        list($firstItem, $secondItem) = $content;
        $this->assertArrayHasKey('accessed', $firstItem);
        $this->assertArrayHasKey('link', $firstItem);
        $this->assertEquals(LinkEnum::LINK1, $firstItem['link']);
        $this->assertArrayHasKey('accessed', $secondItem);
        $this->assertArrayHasKey('link', $secondItem);
        $this->assertEquals(LinkEnum::LINK2, $secondItem['link']);
    }

    public function testGetJourneyForLukeCustomer(): void
    {
        $customer = $this->customerRepository->findOneBy(['name' => CustomerEnum::LUKE]);
        $this->client->request('GET', '/api/heatmaps/journey/customer/'.$customer->getId());
        $content = $this->getResponseContent();

        $this->assertResponseIsSuccessful();
        $this->assertCount(1, $content);
        list($firstItem) = $content;
        $this->assertArrayHasKey('accessed', $firstItem);
        $this->assertArrayHasKey('link', $firstItem);
        $this->assertEquals(LinkEnum::LINK1, $firstItem['link']);
    }
    
    public function testGetJourneyForFakeCustomer(): void
    {
        $this->client->request('GET', '/api/heatmaps/journey/customer/999999');
        $content = $this->getResponseContent();
        
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertArrayHasKey('error', $content);
        $this->assertEquals('Customer not found', $content['error']);
    }
    
    public function testGetJourneyForCustomerWithNoJourney(): void
    {
        $customer = $this->customerRepository->findOneBy(['name' => CustomerEnum::SKYWALKER]);
        $this->client->request('GET', '/api/heatmaps/journey/customer/'.$customer->getId());
        $content = $this->getResponseContent();
        
        $this->assertResponseIsSuccessful();
        $this->assertEmpty($content);
    }

    public function testGetSimilarJourneyForJohnCustomer(): void
    {
        $customer = $this->customerRepository->findOneBy(['name' => CustomerEnum::JOHN]);
        $this->client->request('GET', '/api/heatmaps/similar-journey/customer/'.$customer->getId());
        $content = $this->getResponseContent();

        $this->assertResponseIsSuccessful();
        $this->assertEmpty($content);
    }
    
    public function testGetSimilarJourneyForLukeCustomer(): void
    {
        $customer = $this->customerRepository->findOneBy(['name' => CustomerEnum::LUKE]);
        $this->client->request('GET', '/api/heatmaps/similar-journey/customer/'.$customer->getId());
        $content = $this->getResponseContent();
        
        $this->assertResponseIsSuccessful();
        $this->assertCount(2, $content);
        list($firstItem, $secondItem) = $content;
        $this->assertArrayHasKey('customer_id', $firstItem);
        $this->assertArrayHasKey('customer_name', $firstItem);
        $this->assertEquals(CustomerEnum::JOHN, $firstItem['customer_name']);
        $this->assertArrayHasKey('customer_id', $secondItem);
        $this->assertArrayHasKey('customer_name', $secondItem);
        $this->assertEquals(CustomerEnum::DOE, $secondItem['customer_name']);
    }
    
    public function testGetSimilarJourneyForFakeCustomer(): void
    {
        $this->client->request('GET', '/api/heatmaps/similar-journey/customer/999999');
        $content = $this->getResponseContent();

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertArrayHasKey('error', $content);
        $this->assertEquals('Customer not found', $content['error']);
    }
    
    public function testSaveHeatmap(): void
    {
        $customer = $this->customerRepository->findOneBy(['name' => CustomerEnum::LUKE]);
        $linkType = $this->linkTypeRepository->findOneBy(['name' => LinkTypeEnum::STATIC_PAGE_LINK_TYPE]);
        $body = [
            "customer_id" => $customer->getId(),
            "link_id" => $linkType->getId(),
            "link" => LinkEnum::LINK3
        ];
        $this->client->request('POST', '/api/heatmaps', [], [], [], json_encode($body));
        $content = $this->getResponseContent();

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertEmpty($content);
    }

    public function testSaveHeatmapWithFakeCustomer(): void
    {
        $linkType = $this->linkTypeRepository->findOneBy(['name' => LinkTypeEnum::STATIC_PAGE_LINK_TYPE]);
        $body = [
            "customer_id" => 999999,
            "link_id" => $linkType->getId(),
            "link" => LinkEnum::LINK3
        ];
        $this->client->request('POST', '/api/heatmaps', [], [], [], json_encode($body));
        $content = $this->getResponseContent();

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertArrayHasKey('error', $content);
        $this->assertEquals('Customer not found', $content['error']);
    }

    public function testSaveHeatmapWithFakeLinkId(): void
    {
        $customer = $this->customerRepository->findOneBy(['name' => CustomerEnum::LUKE]);
        
        $body = [
            "customer_id" => $customer->getId(),
            "link_id" => 99999,
            "link" => LinkEnum::LINK3
        ];
        $this->client->request('POST', '/api/heatmaps', [], [], [], json_encode($body));
        $content = $this->getResponseContent();

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertArrayHasKey('error', $content);
        $this->assertEquals('Type not found', $content['error']);
    }

    public function testSaveHeatmapWithInvalidBody(): void
    {
        $customer = $this->customerRepository->findOneBy(['name' => CustomerEnum::LUKE]);
        $body = [
            "customer_id" => $customer->getId(),
            "link" => LinkEnum::LINK3
        ];
        $this->client->request('POST', '/api/heatmaps', [], [], [], json_encode($body));
        $content = $this->getResponseContent();

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertArrayHasKey('error', $content);
        $this->assertArrayHasKey('link_id', $content['error']);
        $this->assertEquals('This value should not be blank.', $content['error']['link_id']);
    }
}
