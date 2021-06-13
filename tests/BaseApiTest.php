<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseApiTest extends WebTestCase
{
    /**
     * @var KernelBrowser
     */
    protected KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = $this->createClient();
    }
    
    /**
     * Gets the response
     *
     * @return mixed
     */
    protected function getApiResponse()
    {
        return $this->client->getResponse();
    }

    /**
     * Gets the response
     *
     * @return mixed
     */
    protected function getStatusCode()
    {
        return $this->getApiResponse()->getStatusCode();
    }

    /**
     * Gets and array with the content of the response
     *
     * @return mixed
     */
    protected function getResponseContent()
    {
        return json_decode($this->getApiResponse()->getContent(), true);
    }
}
