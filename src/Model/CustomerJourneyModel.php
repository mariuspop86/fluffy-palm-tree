<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class CustomerJourneyModel implements ModelInterface
{
    /**
     * @Assert\NotBlank
     */
    private $customer_id;

    /**
     * @return int
     */
    public function getCustomerId(): int
    {
        return $this->customer_id;
    }

    /**
     * @param int $customer_id
     */
    public function setCustomerId(int $customer_id): void
    {
        $this->customer_id = $customer_id;
    }
    
}
